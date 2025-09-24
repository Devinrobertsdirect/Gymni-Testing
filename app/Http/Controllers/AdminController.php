<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Workout;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Dashboard
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_managers' => User::where('role', 'manager')->count(),
            'total_clients' => User::where('role', 'client')->count(),
            'total_workouts' => Workout::count(),
            'public_workouts' => Workout::where('is_public', true)->count(),
        ];

        $recent_users = User::latest()->take(5)->get();
        $recent_workouts = Workout::with('creator')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recent_users', 'recent_workouts'));
    }

    // User Management
    public function users()
    {
        $users = User::with('trainer')->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function createUser()
    {
        $managers = User::where('role', 'manager')->get();
        return view('admin.users.create', compact('managers'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,manager,client',
            'assigned_trainer_id' => 'nullable|exists:users,id'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'assigned_trainer_id' => $request->assigned_trainer_id,
            'is_active' => true
        ]);

        // Generate trainer code if role is manager
        if ($request->role === 'manager') {
            $user->generateTrainerCode();
        }

        return redirect()->route('admin.users')->with('success', 'User created successfully!');
    }

    public function editUser(User $user)
    {
        $managers = User::where('role', 'manager')->get();
        return view('admin.users.edit', compact('user', 'managers'));
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,manager,client',
            'assigned_trainer_id' => 'nullable|exists:users,id',
            'is_active' => 'boolean'
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'assigned_trainer_id' => $request->assigned_trainer_id,
            'is_active' => $request->has('is_active')
        ]);

        // Generate trainer code if role changed to manager and doesn't have one
        if ($request->role === 'manager' && !$user->trainer_code) {
            $user->generateTrainerCode();
        }

        return redirect()->route('admin.users')->with('success', 'User updated successfully!');
    }

    // Workout Management
    public function workouts()
    {
        $workouts = Workout::with('creator')->paginate(20);
        return view('admin.workouts.index', compact('workouts'));
    }

    public function createWorkout()
    {
        return view('admin.workouts.create');
    }

    public function storeWorkout(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:strength,cardio,hiit,mobility,yoga,pilates,crossfit,other',
            'difficulty' => 'required|in:beginner,intermediate,advanced',
            'duration' => 'nullable|integer|min:1',
            'equipment' => 'nullable|string',
            'muscle_groups' => 'nullable|array',
            'instructions' => 'nullable|string',
            'video_file' => 'nullable|file|mimes:mp4,avi,mov,wmv|max:102400', // 100MB max
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB max
            'is_public' => 'boolean',
            'tags' => 'nullable|array',
            'calories_burned' => 'nullable|integer|min:1',
            'sets' => 'nullable|integer|min:1',
            'reps' => 'nullable|integer|min:1',
            'weight' => 'nullable|numeric|min:0',
            'rest_time' => 'nullable|integer|min:0'
        ]);

        $workoutData = $request->except(['video_file', 'image_file']);

        // Handle file uploads
        if ($request->hasFile('video_file')) {
            $videoPath = $request->file('video_file')->store('workout-videos', 'public');
            $workoutData['video_url'] = Storage::url($videoPath);
        }

        if ($request->hasFile('image_file')) {
            $imagePath = $request->file('image_file')->store('workout-images', 'public');
            $workoutData['image_url'] = Storage::url($imagePath);
        }

        $workoutData['created_by'] = auth()->id();

        Workout::create($workoutData);

        return redirect()->route('admin.workouts')->with('success', 'Workout created successfully!');
    }

    public function editWorkout(Workout $workout)
    {
        return view('admin.workouts.edit', compact('workout'));
    }

    public function updateWorkout(Request $request, Workout $workout)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:strength,cardio,hiit,mobility,yoga,pilates,crossfit,other',
            'difficulty' => 'required|in:beginner,intermediate,advanced',
            'duration' => 'nullable|integer|min:1',
            'equipment' => 'nullable|string',
            'muscle_groups' => 'nullable|array',
            'instructions' => 'nullable|string',
            'video_file' => 'nullable|file|mimes:mp4,avi,mov,wmv|max:102400',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'is_public' => 'boolean',
            'tags' => 'nullable|array',
            'calories_burned' => 'nullable|integer|min:1',
            'sets' => 'nullable|integer|min:1',
            'reps' => 'nullable|integer|min:1',
            'weight' => 'nullable|numeric|min:0',
            'rest_time' => 'nullable|integer|min:0'
        ]);

        $workoutData = $request->except(['video_file', 'image_file']);

        // Handle file uploads
        if ($request->hasFile('video_file')) {
            // Delete old video if exists
            if ($workout->video_url) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $workout->video_url));
            }
            $videoPath = $request->file('video_file')->store('workout-videos', 'public');
            $workoutData['video_url'] = Storage::url($videoPath);
        }

        if ($request->hasFile('image_file')) {
            // Delete old image if exists
            if ($workout->image_url) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $workout->image_url));
            }
            $imagePath = $request->file('image_file')->store('workout-images', 'public');
            $workoutData['image_url'] = Storage::url($imagePath);
        }

        $workout->update($workoutData);

        return redirect()->route('admin.workouts')->with('success', 'Workout updated successfully!');
    }

    public function deleteWorkout(Workout $workout)
    {
        // Delete associated files
        if ($workout->video_url) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $workout->video_url));
        }
        if ($workout->image_url) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $workout->image_url));
        }

        $workout->delete();

        return redirect()->route('admin.workouts')->with('success', 'Workout deleted successfully!');
    }

    // Manager Management
    public function managers()
    {
        $managers = User::where('role', 'manager')->withCount('clients')->paginate(20);
        return view('admin.managers.index', compact('managers'));
    }

    public function generateTrainerCode(User $user)
    {
        if ($user->isManager()) {
            $code = $user->generateTrainerCode();
            return response()->json(['trainer_code' => $code]);
        }

        return response()->json(['error' => 'User is not a manager'], 400);
    }
}
