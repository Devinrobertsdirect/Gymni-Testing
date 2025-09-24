<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Workout;
use Illuminate\Support\Facades\Auth;

class ManagerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->isManager() && !Auth::user()->isAdmin()) {
                abort(403, 'Access denied. Manager role required.');
            }
            return $next($request);
        });
    }

    // Manager Dashboard
    public function dashboard()
    {
        $user = Auth::user();
        
        $stats = [
            'total_clients' => $user->clients()->count(),
            'active_clients' => $user->clients()->where('is_active', true)->count(),
            'total_workouts' => Workout::where('created_by', $user->id)->count(),
            'public_workouts' => Workout::where('created_by', $user->id)->where('is_public', true)->count(),
        ];

        $clients = $user->clients()->with('trainer')->paginate(10);
        $my_workouts = Workout::where('created_by', $user->id)->latest()->take(5)->get();

        return view('manager.dashboard', compact('stats', 'clients', 'my_workouts', 'user'));
    }

    // Client Management
    public function clients()
    {
        $clients = Auth::user()->clients()->paginate(20);
        return view('manager.clients.index', compact('clients'));
    }

    public function clientDetails(User $client)
    {
        // Ensure this client belongs to the current manager
        if ($client->assigned_trainer_id !== Auth::id()) {
            abort(403, 'Access denied. This client is not assigned to you.');
        }

        $client_workouts = Workout::where('created_by', Auth::id())
                                 ->where('is_public', true)
                                 ->get();

        return view('manager.clients.show', compact('client', 'client_workouts'));
    }

    public function assignWorkoutToClient(Request $request, User $client)
    {
        // Ensure this client belongs to the current manager
        if ($client->assigned_trainer_id !== Auth::id()) {
            abort(403, 'Access denied. This client is not assigned to you.');
        }

        $request->validate([
            'workout_id' => 'required|exists:workouts,id',
            'scheduled_date' => 'required|date|after:today',
            'notes' => 'nullable|string'
        ]);

        // Create a workout assignment record (you might want to create a separate table for this)
        // For now, we'll use the existing workout events system
        
        return redirect()->back()->with('success', 'Workout assigned to client successfully!');
    }

    // Workout Management for Managers
    public function workouts()
    {
        $workouts = Workout::where('created_by', Auth::id())->paginate(20);
        $public_workouts = Workout::where('is_public', true)->paginate(20);
        
        return view('manager.workouts.index', compact('workouts', 'public_workouts'));
    }

    public function createWorkout()
    {
        return view('manager.workouts.create');
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
            'is_public' => 'boolean',
            'tags' => 'nullable|array',
            'calories_burned' => 'nullable|integer|min:1',
            'sets' => 'nullable|integer|min:1',
            'reps' => 'nullable|integer|min:1',
            'weight' => 'nullable|numeric|min:0',
            'rest_time' => 'nullable|integer|min:0'
        ]);

        $workoutData = $request->all();
        $workoutData['created_by'] = Auth::id();
        $workoutData['is_public'] = $request->has('is_public');

        Workout::create($workoutData);

        return redirect()->route('manager.workouts')->with('success', 'Workout created successfully!');
    }

    public function editWorkout(Workout $workout)
    {
        // Ensure the workout belongs to the current manager
        if ($workout->created_by !== Auth::id()) {
            abort(403, 'Access denied. You can only edit your own workouts.');
        }

        return view('manager.workouts.edit', compact('workout'));
    }

    public function updateWorkout(Request $request, Workout $workout)
    {
        // Ensure the workout belongs to the current manager
        if ($workout->created_by !== Auth::id()) {
            abort(403, 'Access denied. You can only edit your own workouts.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:strength,cardio,hiit,mobility,yoga,pilates,crossfit,other',
            'difficulty' => 'required|in:beginner,intermediate,advanced',
            'duration' => 'nullable|integer|min:1',
            'equipment' => 'nullable|string',
            'muscle_groups' => 'nullable|array',
            'instructions' => 'nullable|string',
            'is_public' => 'boolean',
            'tags' => 'nullable|array',
            'calories_burned' => 'nullable|integer|min:1',
            'sets' => 'nullable|integer|min:1',
            'reps' => 'nullable|integer|min:1',
            'weight' => 'nullable|numeric|min:0',
            'rest_time' => 'nullable|integer|min:0'
        ]);

        $workoutData = $request->all();
        $workoutData['is_public'] = $request->has('is_public');

        $workout->update($workoutData);

        return redirect()->route('manager.workouts')->with('success', 'Workout updated successfully!');
    }

    // Trainer Code Management
    public function trainerCode()
    {
        $user = Auth::user();
        return view('manager.trainer-code', compact('user'));
    }

    public function regenerateTrainerCode()
    {
        $user = Auth::user();
        $code = $user->generateTrainerCode();
        
        return redirect()->back()->with('success', 'Trainer code regenerated: ' . $code);
    }

    // Client Progress Tracking
    public function clientProgress(User $client)
    {
        // Ensure this client belongs to the current manager
        if ($client->assigned_trainer_id !== Auth::id()) {
            abort(403, 'Access denied. This client is not assigned to you.');
        }

        // Here you would typically fetch workout progress, completion rates, etc.
        // For now, we'll show basic client info
        
        return view('manager.clients.progress', compact('client'));
    }
}
