<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\PurchaseSubscription;



/*

|--------------------------------------------------------------------------

| API Routes

|--------------------------------------------------------------------------

|

| Here is where you can register API routes for your application. These

| routes are loaded by the RouteServiceProvider within a group which

| is assigned the "api" middleware group. Enjoy building your API!

|

*/



Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('subscription_pup_sub_check', [LoginController::class, 'subscription_pup_sub_check']);
Route::post('payinapp_Subscription', [LoginController::class, 'payinappSubscription']);
Route::get('check-subscription-price', [LoginController::class, 'checksubscriptionprice']);

Route::middleware(['bearer.token'])->group(function () {
    Route::post('getall_post', [LoginController::class, 'getall_post'])->name('getall_post');
    Route::post('search_friend', [LoginController::class, 'search_friend'])->name('search_friend');
    Route::post('buttom_search', [LoginController::class, 'buttom_search'])->name('buttom_search');
    Route::post('purchase', [PurchaseSubscription::class, 'purchase']);
    Route::post('get_single_term_condetion', [LoginController::class, 'get_single_term_condetion'])->name('get_single_term_condetion');

    Route::post('get_multiple_term_condetion', [LoginController::class, 'get_multiple_term_condetion'])->name('get_multiple_term_condetion');

    Route::post('get_privecy_policy', [LoginController::class, 'get_privecy_policy'])->name('get_privecy_policy');

    Route::post('get_multiple_privecy_policy', [LoginController::class, 'get_multiple_privecy_policy'])->name('get_multiple_privecy_policy');



    Route::post('get_multiple_about', [LoginController::class, 'get_multiple_about'])->name('get_multiple_about');

    Route::post('get_about', [LoginController::class, 'get_about'])->name('get_about');

    Route::post('update_profile', [LoginController::class, 'update_profile'])->name('update_profile');

    Route::post('add_goal', [LoginController::class, 'add_goal'])->name('add_goal');



    Route::post('goal_list', [LoginController::class, 'goal_list'])->name('goal_list');

    Route::post('add_challenges', [LoginController::class, 'add_challenges'])->name('add_challenges');

    Route::post('get_user_challenges', [LoginController::class, 'get_user_challenges'])->name('get_user_challenges');



    Route::post('change_password', [LoginController::class, 'change_password'])->name('change_password');

    Route::post('get_profile', [LoginController::class, 'get_profile'])->name('get_profile');

    Route::post('get_post', [LoginController::class, 'get_post'])->name('get_post');



    Route::post('get_user_post_image', [LoginController::class, 'get_user_post_image'])->name('get_user_post_image');

    Route::post('pubSubService', [PurchaseSubscription::class, 'pubSubService'])->name('pubSubService');
    Route::post('get_fitness', [LoginController::class, 'get_fitness'])->name('get_fitness');

    Route::post('user_like', [LoginController::class, 'user_like'])->name('user_like');



    Route::post('userpost_comment', [LoginController::class, 'userpost_comment'])->name('userpost_comment');



    Route::post('get_comment', [LoginController::class, 'get_comment'])->name('get_comment');



    Route::post('get_group', [LoginController::class, 'get_group'])->name('get_group');







    Route::post('create_post', [LoginController::class, 'create_post'])->name('create_post');

    Route::post('get_user_uploaded_img', [LoginController::class, 'get_user_uploaded_img'])->name('get_user_uploaded_img');



    Route::post('get_group_post', [LoginController::class, 'get_group_post'])->name('get_group_post');



    Route::post('get_group_user', [LoginController::class, 'get_group_user'])->name('get_group_user');



    Route::post('get_user_total_group', [LoginController::class, 'get_user_total_group'])->name('get_user_total_group');



    Route::post('upload_user_profile', [LoginController::class, 'upload_user_profile'])->name('upload_user_profile');



    Route::post('get_subscription', [LoginController::class, 'get_subscription'])->name('get_subscription');



    Route::post('share_post', [LoginController::class, 'share_post'])->name('share_post');



    Route::post('exit_group', [LoginController::class, 'exit_group'])->name('exit_group');



    Route::post('notification', [LoginController::class, 'notification'])->name('notification');



    Route::post('clear_notification', [LoginController::class, 'clear_notification'])->name('clear_notification');



    Route::post('clear_all_notification', [LoginController::class, 'clear_all_notification'])->name('clear_all_notification');



    Route::post('get_all_fitness', [LoginController::class, 'get_all_fitness'])->name('get_all_fitness');



    Route::post('add_preferences', [LoginController::class, 'add_preferences'])->name('add_preferences');



    Route::post('get_preferences', [LoginController::class, 'get_preferences'])->name('get_preferences');

    Route::post('add_rating', [LoginController::class, 'addRating']);

    Route::post('get_fitness_detail', [LoginController::class, 'get_fitness_detail'])->name('get_fitness_detail');



    Route::post('like_video_mode', [LoginController::class, 'like_video_mode'])->name('like_video_mode');



    Route::post('video_mode_comments', [LoginController::class, 'video_mode_comments'])->name('video_mode_comments');



    Route::post('get_video_mode_coment', [LoginController::class, 'get_video_mode_coment'])->name('get_video_mode_coment');



    Route::post('share_video_mode', [LoginController::class, 'share_video_mode'])->name('share_video_mode');



    Route::post('download_video_mode', [LoginController::class, 'download_video_mode'])->name('download_video_mode');



    Route::post('get_user_download', [LoginController::class, 'get_user_download'])->name('get_user_download');



    Route::post('get_fitness_description_mode', [LoginController::class, 'get_fitness_description_mode'])->name('get_fitness_description_mode');



    Route::post('unlike_video_mode', [LoginController::class, 'unlike_video_mode'])->name('unlike_video_mode');



    Route::post('remove_download', [LoginController::class, 'remove_download'])->name('remove_download');



    Route::post('remove_all_download', [LoginController::class, 'remove_all_download'])->name('remove_all_download');



    Route::post('get_user_image', [LoginController::class, 'get_user_image'])->name('get_user_image');



    Route::post('get_user_video', [LoginController::class, 'get_user_video'])->name('get_user_video');



    //////////////////////////////////////////////////////////////////////////////



    Route::post('like_discription_mode', [LoginController::class, 'like_discription_mode'])->name('like_discription_mode');



    Route::post('unlike_discription_mode', [LoginController::class, 'unlike_discription_mode'])->name('unlike_discription_mode');

    Route::post('discription_mode_comments', [LoginController::class, 'discription_mode_comments'])->name('discription_mode_comments');

    Route::post('get_discription_mode_comment', [LoginController::class, 'get_discription_mode_comment'])->name('get_discription_mode_comment');

    Route::post('share_discription_mode', [LoginController::class, 'share_discription_mode'])->name('share_discription_mode');



    Route::post('userfollow', [LoginController::class, 'userfollow'])->name('userfollow');

    Route::post('userunfollow', [LoginController::class, 'userunfollow'])->name('userunfollow');

    Route::post('get_fitness_search', [LoginController::class, 'get_fitness_search'])->name('get_fitness_search');

    Route::post('user_another_porfile', [LoginController::class, 'user_another_porfile'])->name('user_another_porfile');



    Route::post('get_fitness_search_fillter', [LoginController::class, 'get_fitness_search_fillter'])->name('get_fitness_search_fillter');



    Route::post('compleate_goal', [LoginController::class, 'compleate_goal'])->name('compleate_goal');

    Route::post('add_workout', [LoginController::class, 'add_workout'])->name('add_workout');

    Route::post('goal_detail', [LoginController::class, 'goal_detail'])->name('goal_detail');

    Route::post('get_goal_datewise', [LoginController::class, 'get_goal_datewise'])->name('get_goal_datewise');

    Route::post('read_notifucation', [LoginController::class, 'read_notifucation'])->name('read_notifucation');

    Route::post('read_notification', [LoginController::class, 'read_notification'])->name('read_notification');

    Route::post('getall_post_detail', [LoginController::class, 'getall_post_detail'])->name('getall_post_detail');

    Route::post('read_challenges', [LoginController::class, 'read_challenges'])->name('read_challenges');



    Route::post('get_goal_detail', [LoginController::class, 'get_goal_detail'])->name('get_goal_detail');

    Route::post('get_challenge_detail', [LoginController::class, 'get_challenge_detail'])->name('get_challenge_detail');

    Route::post('remove_user_post', [LoginController::class, 'remove_user_post'])->name('remove_user_post');



    Route::post('accept_challenge', [LoginController::class, 'accept_challenge'])->name('accept_challenge');

    Route::post('cancel_challenge', [LoginController::class, 'cancel_challenge'])->name('cancel_challenge');





    Route::post('get_muscle_group', [LoginController::class, 'get_muscle_group'])->name('get_muscle_group');



    Route::post('get_mobility', [LoginController::class, 'get_mobility'])->name('get_mobility');



    Route::post('fitness_filter', [LoginController::class, 'fitness_filter'])->name('fitness_filter');



    Route::post('get_user_saved_filter', [LoginController::class, 'get_user_saved_filter'])->name('get_user_saved_filter');



    Route::post('complete_challenge', [LoginController::class, 'complete_challenge'])->name('complete_challenge');

    Route::post('won_challenge', [LoginController::class, 'won_challenge'])->name('won_challenge');

    Route::post('user_cancel_challenge', [LoginController::class, 'user_cancel_challenge'])->name('user_cancel_challenge');





    Route::post('payment', [LoginController::class, 'payment'])->name('payment');



    Route::post('goal_count_parsentage', [LoginController::class, 'goal_count_parsentage'])->name('goal_count_parsentage');



    Route::post('get_current_month_goal', [LoginController::class, 'get_current_month_goal'])->name('get_current_month_goal');

    Route::post('purches_plane_detail', [LoginController::class, 'purches_plane_detail'])->name('purches_plane_detail');



    Route::post('check_user_plane', [LoginController::class, 'check_user_plane'])->name('check_user_plane');



    Route::post('buttom_search1', [LoginController::class, 'buttom_search1'])->name('buttom_search1');

    Route::post('get_user_download1', [LoginController::class, 'get_user_download1'])->name('get_user_download1');



    Route::post('get_group_user1', [LoginController::class, 'get_group_user1'])->name('get_group_user1');





    /////////////////16-02-2023-------///

    Route::get('get_goal_type', [LoginController::class, 'get_goal_type'])->name('get_goal_type');

    Route::post('get_workout_type', [LoginController::class, 'get_workout_type'])->name('get_workout_type');

    Route::get('get_fitness_video', [LoginController::class, 'get_fitness_video'])->name('get_fitness_video');



    Route::get('get_current_week_goal', [LoginController::class, 'get_current_week_goal'])->name('get_current_week_goal');



    Route::post('add_workout_reps', [LoginController::class, 'add_workout_reps'])->name('add_workout_reps');



    Route::post('check_user_group_exist', [LoginController::class, 'check_user_group_exist'])->name('check_user_group_exist');

    Route::post('compleate_noof_goal', [LoginController::class, 'compleate_noof_goal'])->name('compleate_noof_goal');



    Route::post('add_workout_reps_r1', [LoginController::class, 'add_workout_reps_r1'])->name('add_workout_reps_r1');



    Route::post('test12', [LoginController::class, 'test12'])->name('test12');



    Route::post('test12', [LoginController::class, 'test12'])->name('test12');



    Route::post('getGroupCreater', [LoginController::class, 'getGroupCreater'])->name('getGroupCreater');







    Route::post('filter_data', [LoginController::class, 'filter_data'])->name('filter_data');



    Route::post('logout', [LoginController::class, 'logout'])->name('logout');



    Route::post('get_category_video', [LoginController::class, 'get_category_video'])->name('get_category_video');



    Route::post('add_fitness_clander', [LoginController::class, 'add_fitness_clander'])->name('add_fitness_clander');

    Route::post('add_step_count', [LoginController::class, 'add_step_count'])->name('add_step_count');



    Route::post('show_workout_detail', [LoginController::class, 'show_workout_detail'])->name('show_workout_detail');



    Route::post('loglist', [LoginController::class, 'loglist'])->name('loglist');

    Route::post('getcatVideo', [LoginController::class, 'getcatVideo'])->name('getcatVideo');

    Route::post('create_group', [LoginController::class, 'create_group'])->name('create_group');



    Route::post('getuser_group', [LoginController::class, 'getuser_group'])->name('getuser_group');

    Route::post('delete_group', [LoginController::class, 'delete_group'])->name('delete_group');



    Route::post('add_checkfriend_r', [LoginController::class, 'add_checkfriend_r'])->name('add_checkfriend_r');

    Route::post('search_friend_r', [LoginController::class, 'search_friend_r'])->name('search_friend_r');





    Route::post('getall_feedpost', [LoginController::class, 'getall_feedpost'])->name('getall_feedpost');



    Route::get('getallUser', [LoginController::class, 'getallUser'])->name('getallUser');

    Route::post('getlogWeight', [LoginController::class, 'getlogWeight'])->name('getlogWeight');



    Route::post('getlogweightval', [LoginController::class, 'getlogweightval'])->name('getlogweightval');

    Route::post('addlogweight', [LoginController::class, 'addlogweight'])->name('addlogweight');

    Route::post('getcircute', [LoginController::class, 'getcircute'])->name('getcircute');

    Route::post('addlogweight_r', [LoginController::class, 'addlogweight_r'])->name('addlogweight_r');





    Route::post('getlog', [LoginController::class, 'getlog'])->name('getlog');



    Route::post('share_post_group', [LoginController::class, 'share_post_group'])->name('share_post_group');

    Route::post('deleteGoal', [LoginController::class, 'deleteGoal'])->name('deleteGoal');

    Route::post('updateGoal', [LoginController::class, 'updateGoal'])->name('updateGoal');

    Route::post('get-user-circuit', [LoginController::class, 'GetUserCircuit']);

    Route::post('get-user-round', [LoginController::class, 'GetRound']);



    Route::post('delete-account', [LoginController::class, 'deleteAccount']);



    Route::post('updateSubscription', [PurchaseSubscription::class, 'updateSubscription']);

    Route::post('userFollows_android', [LoginController::class, 'userFollows_adiroide']);

    Route::post('user_subscription_check', [LoginController::class, 'usersubscriptioncheck']);

    //new
    Route::post('block-user', [LoginController::class, 'block_user'])->name('block-user');
    Route::post('report-user', [LoginController::class, 'report_user'])->name('report-user');
    Route::post('user-feedback', [LoginController::class, 'user_feedback'])->name('user-feedback');
    Route::post('unblock_user', [LoginController::class, 'user_unblock'])->name('unblock_user');

    Route::post('get-video-desc', [LoginController::class, 'get_video_desc'])->name('get-video-desc');
    Route::post('update_logweight', [LoginController::class, 'update_logweight'])->name('update_logweight');
});





Route::post('login', [LoginController::class, 'login'])->name('login');

Route::post('signup', [LoginController::class, 'signup'])->name('signup');

Route::post('social_login', [LoginController::class, 'social_login'])->name('social_login');

Route::post('forgotpassword', [LoginController::class, 'forgotpassword'])->name('forgotpassword');

Route::post('create_profile', [LoginController::class, 'create_profile'])->name('create_profile');

Route::post('reset_password', [LoginController::class, 'reset_password'])->name('reset_password');


Route::post('verfiy_otp', [LoginController::class, 'verfiy_otp'])->name('verfiy_otp');

Route::post('resend_otp', [LoginController::class, 'resend_otp'])->name('resend_otp');

Route::post('check_user', [LoginController::class, 'check_user'])->name('check_user');

Route::post('auth-send-otp', [LoginController::class, 'sendOtpRequest'])->name('auth-send-otp');

Route::post('verify-Otp', [LoginController::class, 'verifyAuthOtp'])->name('verify-Otp');





Route::get('/test', [LoginController::class, 'test'])->name('test');

