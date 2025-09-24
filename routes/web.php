<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomAuthController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\VideoModeController;
use App\Http\Controllers\DesmodeController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\AboutusController;
use App\Http\Controllers\TermConditionController;
use App\Http\Controllers\PrivacypolicyController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\DemovideoController;
use App\Http\Controllers\PrivecyController;
use App\Http\Controllers\WorkOutController;
use App\Http\Controllers\LogWeightController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ManagerController;

Route::get('/clear-cache', function () {
    $configCache = Artisan::call('config:cache');
    $clearCache = Artisan::call('cache:clear');
    // return what you want
});

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('admin', [CustomAuthController::class, 'dashboard'])->name('dashboard');

Route::get('/', [CustomAuthController::class, 'index'])->name('mylogin');
Route::get('dashboard', [CustomAuthController::class, 'dashboard']);
Route::get('login', [CustomAuthController::class, 'index'])->name('login');
Route::post('custom-login', [CustomAuthController::class, 'customLogin'])->name('login.custom');
Route::get('registration', [CustomAuthController::class, 'registration'])->name('register-user');
Route::post('custom-registration', [CustomAuthController::class, 'customRegistration'])->name('register.custom');
Route::get('signout', [CustomAuthController::class, 'signOut'])->name('signout');
Route::resource('/users', UsersController::class);
Route::resource('/videomode', VideoModeController::class);
Route::resource('/desmode', DesmodeController::class);
Route::resource('/subscription', SubscriptionController::class);
Route::any('delImg', [DesmodeController::class, 'delImg'])->name('delImg');
Route::any('delBlog', [DesmodeController::class, 'delBlog'])->name('delBlog');
Route::any('uploadDemoVideo', [DesmodeController::class, 'uploadDemoVideo'])->name('uploadDemoVideo');
Route::any('demoVideo', [DesmodeController::class, 'demoVideo'])->name('demoVideo');

// @rahul
Route::get('logweight_add/{id}', [DesmodeController::class, 'logweight_add'])->name('logweight_add');
// @rahul

Route::resource('/aboutus', AboutusController::class);
Route::any('updateContent', [AboutusController::class, 'updateContent'])->name('updateContent');
Route::resource('/termCondition', TermConditionController::class);
Route::any('updateContentTerm', [TermConditionController::class, 'updateContentTerm'])->name('updateContentTerm');
Route::resource('/privacypolicy', PrivacypolicyController::class);
Route::any('updateContentPriv', [PrivacypolicyController::class, 'updateContentPriv'])->name('updateContentPriv');

// Route::resource('/group', GroupController::class);
Route::any('tophighlikeVideos', [VideoModeController::class, 'tophighlikeVideos'])->name('tophighlikeVideos');
Route::any('topplans', [SubscriptionController::class, 'topplans'])->name('topplans');
Route::resource('/groups', GroupController::class);
Route::any('delImgAbout', [AboutusController::class, 'delImgAbout'])->name('delImgAbout');
Route::any('delImgTerm', [TermConditionController::class, 'delImgTerm'])->name('delImgTerm');
Route::any('delImgPriv', [PrivacypolicyController::class, 'delImgPriv'])->name('delImgPriv');
Route::any('delImgGrp', [GroupController::class, 'delImgGrp'])->name('delImgGrp');
Route::get('/demovideo', [DemovideoController::class, 'index'])->name('demovideo');
Route::get('/create-video', [DemovideoController::class, 'create'])->name('demovideo.create-video');
Route::post('add-video', [DemovideoController::class, 'store'])->name('demovideo.add-video');
Route::any('delete-video/{id}', [DemovideoController::class, 'destroy']);
Route::any('edit-video/{id}', [DemovideoController::class, 'edit'])->name('edit');
Route::any('update', [DemovideoController::class, 'update'])->name('demovideo.update');
Route::post('demo_video_status', [DemovideoController::class, 'demo_video_status'])->name('demo_video_status');
Route::post('select_video', [VideoModeController::class, 'add_video'])->name('videomode.add_video');
Route::post('add-workout', [VideoModeController::class, 'add_workout'])->name('videomode.add_workout');
Route::any('remove', [VideoModeController::class, 'delete_video'])->name('remove.deletev');
Route::any('remove-videos', [DesmodeController::class, 'delete_video'])->name('remove.video');
Route::get('/privecy-policy', [PrivecyController::class, 'privecy'])->name('privecy-policy');
Route::get('/term-condition', [PrivecyController::class, 'term_condetion'])->name('term-condition');
Route::get('/get_cate_video', [DesmodeController::class, 'get_cate_video'])->name('getid.get_cate_video');
Route::get('createthumb', [VideoModeController::class, 'createthumb'])->name('createthumb');
Route::get('fitness_status', [VideoModeController::class, 'fitness_status'])->name('fitness_status');
Route::post('video_mode_status', [VideoModeController::class, 'video_mode_status'])->name('video_mode_status');
Route::get('add-workout', [WorkOutController::class, 'index'])->name('add-workout');
Route::post('add-workout-data', [WorkOutController::class, 'add_workout'])->name('add-workout-data');
Route::get('delete_goal', [WorkOutController::class, 'delete_goal'])->name('delete_goal');
Route::get('update_data', [WorkOutController::class, 'update_data'])->name('update_data');
Route::post('update_workout', [WorkOutController::class, 'update_workout'])->name('update_workout');
Route::get('add-logweight', [LogWeightController::class, 'index'])->name('add-logweight');
Route::get('logweight-List', [LogWeightController::class, 'logWeightList'])->name('logweight-List');
Route::post('addLogweight', [LogWeightController::class, 'addLogweight'])->name('addLogweight');
Route::get('edit-logweight/{id}', [LogWeightController::class, 'editlogweight'])->name('edit-logweight');
Route::post('updateLogweight', [LogWeightController::class, 'updateLogweight'])->name('updateLogweight');
Route::get('deleteLogwight/{id}', [LogWeightController::class, 'deleteLogwight'])->name('deleteLogwight');
Route::get('check', [LogWeightController::class, 'check'])->name('check');
Route::post('checkVideoMode', [DesmodeController::class, 'checkVideoMode'])->name('checkVideoMode');
Route::get('user-feedback-details', [PrivecyController::class, 'user_feedback_details'])->name('user-feedback-details');
Route::delete('/feedback/{id}', [PrivecyController::class, 'destroy'])->name('feedback.delete');
Route::get('report-management', [PrivecyController::class, 'report_management'])->name('report-management');

Route::get('/clear', function () {
    \Artisan::call('config:cache');
    \Artisan::call('cache:clear');
    \Artisan::call('route:clear');
    \Artisan::call('view:clear');
    \Artisan::call('config:clear');
    echo 'dump-autoload complete';
});

// Admin Routes
Route::middleware(['auth'])->group(function () {
    // Admin Dashboard
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // User Management
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
        Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
        Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
        
        // Workout Management
        Route::get('/workouts', [AdminController::class, 'workouts'])->name('workouts');
        Route::get('/workouts/create', [AdminController::class, 'createWorkout'])->name('workouts.create');
        Route::post('/workouts', [AdminController::class, 'storeWorkout'])->name('workouts.store');
        Route::get('/workouts/{workout}/edit', [AdminController::class, 'editWorkout'])->name('workouts.edit');
        Route::put('/workouts/{workout}', [AdminController::class, 'updateWorkout'])->name('workouts.update');
        Route::delete('/workouts/{workout}', [AdminController::class, 'deleteWorkout'])->name('workouts.destroy');
        
        // Manager Management
        Route::get('/managers', [AdminController::class, 'managers'])->name('managers');
        Route::post('/managers/{user}/generate-code', [AdminController::class, 'generateTrainerCode'])->name('managers.generate-code');
    });
    
    // Manager Routes
    Route::prefix('manager')->name('manager.')->group(function () {
        Route::get('/', [ManagerController::class, 'dashboard'])->name('dashboard');
        Route::get('/dashboard', [ManagerController::class, 'dashboard'])->name('dashboard');
        
        // Client Management
        Route::get('/clients', [ManagerController::class, 'clients'])->name('clients');
        Route::get('/clients/{client}', [ManagerController::class, 'clientDetails'])->name('clients.show');
        Route::post('/clients/{client}/assign-workout', [ManagerController::class, 'assignWorkoutToClient'])->name('clients.assign-workout');
        Route::get('/clients/{client}/progress', [ManagerController::class, 'clientProgress'])->name('clients.progress');
        
        // Workout Management
        Route::get('/workouts', [ManagerController::class, 'workouts'])->name('workouts');
        Route::get('/workouts/create', [ManagerController::class, 'createWorkout'])->name('workouts.create');
        Route::post('/workouts', [ManagerController::class, 'storeWorkout'])->name('workouts.store');
        Route::get('/workouts/{workout}/edit', [ManagerController::class, 'editWorkout'])->name('workouts.edit');
        Route::put('/workouts/{workout}', [ManagerController::class, 'updateWorkout'])->name('workouts.update');
        
        // Trainer Code Management
        Route::get('/trainer-code', [ManagerController::class, 'trainerCode'])->name('trainer-code');
        Route::post('/regenerate-trainer-code', [ManagerController::class, 'regenerateTrainerCode'])->name('regenerate-trainer-code');
    });
});
