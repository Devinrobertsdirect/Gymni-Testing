<?php



namespace App\Http\Controllers;



use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\Models\AboutUs;

use App\Models\AboutUsMulti;



class LogWeightController extends Controller

{

    public function __construct()

    {

        $this->middleware('auth');

    }

    public function index(Request $request)
    {
        $id = $request->desid;
        $videomodes = DB::table('description_mode')->where('id', $id)->first();

      //  dd($videomodes);

        $getVideoModeId = $videomodes->video_mode_lastid;

        $videomode = DB::table('video_mode')->where('id', $getVideoModeId)->get();

        return view('logweight.logweight', compact('videomode'));

    }

    // public function addLogweight(Request $request)

    // {

    //     $workout_title   = $request->workout_title;

    //     $circuit_type    = $request->circuit_type;

    //     $round           = $request->round;

    //     $exercise        = $request->exercise;

    //     $check = DB::table('logweight')->where('workout_title', $workout_title)->where('round', $round)->where('circuit_type', $circuit_type)->first();

    //     if (empty($check)) {

    //         $add =  DB::table('logweight')->insert([

    //             'workout_title'            =>     $workout_title,

    //             'circuit_type'             =>     $circuit_type,

    //             'round'                    =>     $round,

    //             'exercise'                 =>   implode(",", $exercise),

    //             'created_at'               =>  date('Y-m-d h:i:s')

    //         ]);

    //         if ($add) {

    //             return redirect()->route('logweight-List')->with('success', 'logweight added successfully.');

    //         } else {

    //             return redirect()->route('add-logweight')->with('error', 'Something is wrong.');

    //         }

    //     } else {

    //         return redirect()->route('add-logweight')->with('error', 'Logweight already added');

    //     }

    // }





    public function addLogweight(Request $request)

    {

        $workout_title   = $request->workout_title;

        $circuit_type    = $request->circuit_type;

        $round           = $request->round;

        $exercise        = $request->exercise;

        $desId           = $request->desId;

        $check = DB::table('logweight')->where('workout_title', $workout_title)->where('round', $round)->where('circuit_type', $circuit_type)->first();

       //dd($check);

        if (empty($check)) {

            $add =  DB::table('logweight')->insert([

                'workout_title'            =>     $workout_title,

                'circuit_type'             =>     $circuit_type,

                'description_mode_id'        => $request->desId,

                'round'                    =>     $round,

                'exercise'                 =>   implode(",", $exercise),

                'reps'                 =>   implode(",", $request->reps),

                //'count'                 =>   implode(",", $request->count),

                'created_at'               =>  date('Y-m-d h:i:s')

            ]);

            if ($add) {

               // return redirect('https://www.example.com');

                return redirect('logweight-List?desid='.$desId)->with('success', 'logweight added successfully.');

            } else {

               // return redirect('logweight-List?desid='.$desId)->with('error', 'Something is wrong.');

                return redirect('logweight-List?desid='.$desId)->with('error', 'Something is wrong.');

            }

        } else {

            return redirect()->back()->with('error', 'Logweight already added');

          //  return redirect()->route('add-logweight')->with('error', 'Logweight already added');

        }

    }

    public function check(Request $request)

    {

        $round = $request->round;

        $title = $request->title;

        $circuit_type = $request->circuit_type;

        $check = DB::table('logweight')->where('workout_title', $title)->where('round', $round)->where('circuit_type', $circuit_type)->first();

        if (!empty($check)) {

            $resp['status'] = false;

        } else {

            $resp['status'] = true;

        }

        echo json_encode($resp);

    }

    public function logWeightList(Request $request)

    {

        $fitnessId = $request->desid;

      //  dd($fitnessId);

      $getLogweight = [];

      $videomode   = [];

        $id = DB::table('description_mode')->where('id', $fitnessId)->first();

        if(!empty($id)){



        } else{

            

        }

        $getLogweight = DB::table('logweight')

            ->join('video_mode', 'video_mode.id', '=', 'logweight.workout_title')

            ->orderBy('logweight.id', 'DESC')

            ->where('logweight.description_mode_id',$fitnessId)

            ->select('logweight.*', 'video_mode.video_title', 'video_mode.category')->get();

            $videomode = DB::table('video_mode')->get();

        

        return view('logweight.logweightList', compact('getLogweight','videomode', 'id'));

    }

    public function deleteLogwight($id)

    {

        if (!empty($id)) {

            $getfitnessId  =   DB::table('logweight')->where('id', $id)->first();

            if ($getfitnessId->workout_title) {

                $deleted = DB::table('user_logweight')->where('fitness_id', $getfitnessId->workout_title)->delete();

            }

            $deleted = DB::table('logweight')->where('id', $id)->delete();

            return redirect()->back()->with('error', 'Logweight already added');

            // return redirect()->route('logweight-List')->with('success', 'logweight deleted successfully');

        } else {

            return redirect()->back()->with('error', 'Logweight already added');

           // return redirect()->route('logweight-List')->with('error', 'Something is wrong.');

        }

    }

    public function editlogweight($id)

    {

        $getLogweightid = DB::table('logweight')->where('id', $id)->first();

       // dd($getLogweightid);

        $videomode = DB::table('video_mode')->where('id', $getLogweightid->workout_title)->get();

        return view('logweight.editLogweight', compact('getLogweightid', 'videomode'));

    }





    public function updateLogweight(Request $request)

    {

        $workout_title   = $request->workout_title;

        $circuit_type    = $request->circuit_type;

        $round           = $request->round;

        $exercise        = array_filter($request->exercise);

     //   $count        = array_filter($request->count);

        $reps        = array_filter($request->reps);

        $logweightId        = $request->logweightId;

        $desId = $request->desid;



        $add = DB::table('logweight')->where('id', $logweightId)

            ->update([

                'workout_title'            =>     $workout_title,

                'circuit_type'             =>     $circuit_type,

                'round'                    =>     $round,

                'exercise'                 =>   implode(",", $exercise),

                //'count'                    =>   implode(",", $count),

                'reps'                     =>   implode(",", $reps),

                'created_at'               =>  date('Y-m-d h:i:s')

            ]);



        if ($add) {

            return redirect('logweight-List?desid='.$desId)->with('success', 'logweight updated successfully.');

           // return redirect()->route('logweight-List')->with('success', 'logweight updated successfully.');

        } else {

            return redirect()->route('logweight-List')->with('error', 'Something is wrong.');

        }

    }

    public function copyLogweight(Request $request){

               

    }

}

