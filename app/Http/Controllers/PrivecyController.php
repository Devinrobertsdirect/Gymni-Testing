<?php

namespace App\Http\Controllers;

use App\Models\Privacypolicy;
use Illuminate\Http\Request;
use App\Models\PrivacypolicyMulti;
use App\Models\UserFeedback;
use App\Models\Posts;
use DB;

class PrivecyController extends Controller
{
    
  


    public function privecy()
    {
        $where = [];
        //$privacypolicy = $this->privacypolicy->first();
       // $privacypolicyMulti = $this->privacypolicyMulti->orderBy('id', 'desc')->latest()->paginate(10);
      //  $privacypolicyMulti = DB::table('privacy_policy_multi')->orderBy('id', 'DESC')->get();
        return view('privecy');
    }
    public function term_condetion(){
        return view('term_condetion');
    }

    public function user_feedback_details()
    {
        #$user_feedback_arr = DB::table('user_feedback')->orderBy('id', 'DESC')->get();
        $user_feedback_arr = DB::table('user_feedback')
        ->join('users', 'user_feedback.user_id', '=', 'users.id') // Join users table
        ->select('user_feedback.*', 'users.name as username','users.phone','users.email') // Select required fields
        ->get();
        #print_r($user_feedback_arr);die;
        return view('user-feedback',compact('user_feedback_arr'));
    }

    public function destroy($id)
    {
        $feedback = UserFeedback::find($id);

        if (!$feedback) {
            return redirect()->back()->with('error', 'Feedback not found.');
        }

        $feedback->delete();

        return redirect()->back()->with('success', 'Feedback deleted successfully.');
    }

    
    public function report_management()
    {
        #$user_feedback_arr = DB::table('user_feedback')->orderBy('id', 'DESC')->get();
        $user_feedback_arr = DB::table('post_reports')
        ->join('users', 'post_reports.reported_by', '=', 'users.id') // Join users table
        ->join('post', 'post_reports.post_id', '=', 'post.id')
        ->join('users as post_creator', 'post.user_id', '=', 'post_creator.id')
        ->select('post_reports.*', 'users.name as username','users.phone','users.email','post.post_img as post_image','post.created_at as post_created_at','post_creator.name as post_creator_name','post_creator.email as post_creator_email')
        ->get();

     
        #->join('post', 'post_reports.post_id', '=', 'post.id')
        #'post.post_img as post_image'
        #print_r($user_feedback_arr);die;
        return view('report-management',compact('user_feedback_arr'));
    }

}
