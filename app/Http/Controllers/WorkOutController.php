<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AboutUs;
use App\Models\AboutUsMulti;

class WorkOutController extends Controller
{
    public function __construct(){
          $this->middleware('auth');
          $this->aboutus    =  New AboutUs;
          $this->aboutusmulti    =  New AboutUsMulti;

    }


    public function index()
    {
        $workout = DB::table('goal_type')->get();
     return view('workout.index',compact('workout'));
        
    }
    public function add_workout(Request $request){
                  // print_r($_POST); die;
                   $add_goal =  DB::table('goal_type')->insert([
                    'goal_type'      => $request->input('goal_type'),
                    'workout_type'   => $request->input('workout_type'),
                    'created_at'     => date('Y-m-d')
                    ]);
                    if($add_goal){
                        return redirect()->route('add-workout')->with('success','workout Us data created successfully.');
                    } else{
                        return redirect()->route('add-workout')->with('error','Something is wrong.');
                    }
    }


    public function delete_goal(Request $request){
                    $id = $_GET['id'];
                    $deleted = DB::table('goal_type')->where('id', $id)->delete();
                    if($deleted){
                        return redirect()->route('add-workout')->with('success','workout  deleted successfully.');
                    } else{
                        return redirect()->route('add-workout')->with('error','Something is wrong.');
                    }

                   

    }
    public function update_data(Request $request){
        $id = $_GET['edit_id'];
        $edit_workout = DB::table('goal_type')->where('id',$id)->get();
        $workout = DB::table('goal_type')->get();
        return view('workout.index',compact('workout','edit_workout'));              
    }

    public function update_workout(Request $request){
        // print_r($_POST); die;


        $update_goal  =  DB::table('goal_type')
        ->where('id', $request->input('id'))
        ->update(['goal_type' => $request->input('goal_type'),'workout_type' =>$request->input('workout_type') ]);


          if($update_goal){
              return redirect()->route('add-workout')->with('success','workout  updated created successfully.');
          } else{
              return redirect()->route('add-workout')->with('error','Something is wrong.');
          }
}


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
   
}
