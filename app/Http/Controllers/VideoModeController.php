<?php



namespace App\Http\Controllers;



use Config;

use App\Models\VideoMode;

use Illuminate\Http\Request;

use DB;



class VideoModeController extends Controller

{

    public function __construct()

    {

        $this->middleware('auth');

        $this->videomode = new VideoMode;
    }



    public function index()

    {



        $where      = [];

        $heading    = "Video Mode List";







        //$videomode  = DB::table('video_mode')->orderBy('id', 'DESC')->get();

        $videomode = DB::table('video_mode')

            ->join('demo_video', 'demo_video.id', '=', 'video_mode.workout_video_id')

            ->leftJoin('like_video_mode', 'like_video_mode.video_mode_id', '=', 'video_mode.id')

            ->leftJoin('share_video_mode', 'share_video_mode.video_mode_id', '=', 'video_mode.id')

            ->select('video_mode.*', DB::raw("count(like_video_mode.id) as total_like"), DB::raw("count(share_video_mode.id) as total_share"))

            ->orderBy('video_mode.id', 'desc')

            ->groupBy('video_mode.id')

            ->get();

        // print_r($videomode); die;



        $desmode    = DB::table('description_mode')->orderBy('id', 'DESC')->get();





        return view('videomode.index', compact('videomode', 'heading', 'desmode'));
    }



    public function tophighlikeVideos()

    {

        $where      = [];

        $heading    = "Top Highest Like Videos";

        $videomode  = $this->videomode->where('like', '<>', '')->orderBy('like', 'desc')->latest()->paginate(10);

        return view('videomode.index', compact('videomode', 'heading'));
    }



    /**

     * Show the form for creating a new resource.

     * @return \Illuminate\Http\Response

     */

    public function create()

    {



        $get_demo_tag       = DB::table('demo_video')->get();

        // $get_workout_tag    = DB::table('demo_video')->where('category', 'Workout Video')->whereNotNull('tag')->get();

        $get_workout_tag    = DB::table('demo_video')->get();

        // dd($get_workout_tag);

        $user               = Config::get('video.demovideo');

        $category           = Config::get('video.category');

        $muscle_group       = Config::get('video.muscle_group');

        $instructor         = Config::get('video.instructor');

        $intensityrating    = Config::get('video.intensityrating');

        //print_r($get_demo_tag); die;



        return view('videomode.create', compact('category', 'muscle_group', 'instructor', 'intensityrating', 'user', 'get_demo_tag', 'get_workout_tag'));
    }



    public function add_video(Request $request)

    {





        $mm = ($_POST['selected']);

        $id = isset($request->id) ? $request->id : [];

        //  print_r($id);

        //$mm = (($_POST['selected'])); 

        $tag    = implode("|", $mm);

        // $tag    = end($_POST['selected']);

        //implode(" ",$arr);

        // print_r($tag); die;



        //    WHERE CONCAT(",", `setcolumn`, ",") REGEXP ",(val1|val2|val3),"

        $checkedValue = $request->checked;

        //$data   = DB::select('SELECT * from demo_video  WHERE CONCAT(",", `tag`, ",") REGEXP ",('.$tag.'),"');

        //$data = DB::select('SELECT * FROM demo_video WHERE tag = ?', [$tag]);

        $data = DB::table('demo_video')

            ->whereIn('tag', $mm)

            ->get();

        // $data   = DB::select("SELECT * from demo_video where  FIND_IN_SET('$tag', tag)");

        $output = '';

        foreach ($data as $val) {

            if (in_array($val->id, $id)) {

                $checkeds = 'checked';
            } else {

                $checkeds = '';
            }



            $dd = rand();

            $output .= '<tr>

                <td>

                    <div class="form-check">

                        <input class="form-check-input" ' . $checkeds . ' onchange="getCheckval(' . $val->id . ')" type="checkbox" value="' . $val->id . '" id="flexCheckDefault" name="demovideo[]">

                        <label class="form-check-label" for="flexCheckDefault"></label>

                    </div>

                </td>

                <td>' . $val->title . '</td>

                <td>' . $val->description . '</td>

                <td>' . $val->tag . '</td> 

                <td>

                    <button type="button" class="btn btn-primary btn-sm viewvideo" data-toggle="modal" data-target="#myModal_' . $dd . '" ><i class="fas fa-eye" style="color:white"></i></button>

                    <button type="button" class="btn btn-danger btn-sm btnDelete"><i class="fas fa-trash" style="color:white;"></i></button> 

                    <div class="modal" id="myModal_' . $dd . '">

                        <div class="modal-dialog">

                            <div class="modal-content">

                                <div class="modal-header">

                                    <h4 class="modal-title"></h4>

                                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                                </div>

                                <div class="modal-body">



                                <video controls="" name="media" style="width: 470px !important"><source src=" ' . $val->url . '" type="video/mp4" ></video>



                               

                                

                                </div>

                            </div>

                        </div>

                    </div>

                </td> 

            </tr>';
        };



        echo json_encode($output);
    }



    public function add_workout()

    {

        $tags   = ($_POST['selecteds']);

        //   $data   = DB::select("SELECT * from demo_video where  FIND_IN_SET('$tags', tag)");

        $data   = DB::select("SELECT * from demo_video where  id = $tags");

        $output = '';





        foreach ($data as $val) {

            $dd = rand();

            $output .= '<tr>

                <td> 

                    <div class="form-check">

                        <input type="radio" class="form-check-input" id="radio2"  value="' . $val->id . '" name="w_video" value="option2">

                        <label class="form-check-label" for="radio2"></label>

                    </div>

                </td>

                <td>' . $val->title . '</td>

                <td>' . $val->description . '</td>

                <td>' . $val->tag . '</td> 

                <td>

                    <button type="button" class="btn btn-primary btn-sm viewvideo" data-toggle="modal" data-target="#myModal_' . $dd . '"><i class="fas fa-eye" style="color:white"></i></button>

                    <button type="button" class="btn btn-danger btn-sm workout_videos"><i class="fas fa-trash" style="color:white;"></i></button> 

                    <div class="modal" id="myModal_' . $dd . '">

                        <div class="modal-dialog">

                            <div class="modal-content">

                                <div class="modal-header">

                                    <h4 class="modal-title"></h4>

                                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                                </div>

                                <div class="modal-body">

                                <video controls="" name="media" style="width: 470px !important"><source src=" ' . $val->url . '" type="video/mp4" ></video>

                                </div>

                            </div>

                        </div>

                    </div>

                </td> 

            </tr>';
        };



        echo json_encode($output);
    }



    public function delete_video()

    {

        $rowid      = $_GET['id'];

        $video_id   = $_GET['vid'];

        $data       = DB::select("SELECT * from video_mode where id='" . $rowid . "'");

        $unsetdata  = (explode(",", $data[0]->demo_videoid));



        if (($key = array_search($video_id, $unsetdata)) !== false) {

            unset($unsetdata[$key]);
        }



        $datas['demo_videoid'] =  implode(" ", $unsetdata);



        DB::table('video_mode')->where('id', $rowid)->update($datas);

        return redirect()->back()->with('success', 'demo video deleted successfully.');
    }



    public function store(Request $request)

    {

        //  $this->videoUploadValidate($request);



        $check  = DB::table('video_mode')->where('video_title', $request->video_title)->first();

        if (!empty($check)) {

            return redirect()->route('videomode.index')->with('success', 'Video title already uploaded.');
        }

        $data = $request->all();

        $insClient['workout_video_id'] = filter_var($data['w_video'], FILTER_SANITIZE_STRING);

        if (!empty($_POST['demovideo'])) {

            $insClient['demo_videoid'] = implode(",", array_unique($_POST['demovideo']));
        }

        if (!empty($data['w_video'])) {

            $insClient['workout_video_id']  = filter_var(trim($data['w_video']), FILTER_SANITIZE_STRING);
        }

        if (!empty($data['workout_time'])) {

            $insClient['duration']          = filter_var($data['workout_time'], FILTER_SANITIZE_STRING);
        }



        $insClient['video_title']       = filter_var($data['video_title']);

        $insClient['category']          = filter_var($data['category'], FILTER_SANITIZE_STRING);



        if (!empty($data['muscle_group'])) {

            $insClient['muscle_group']      = filter_var($data['muscle_group'], FILTER_SANITIZE_STRING);
        }

        if (!empty($data['equipment'])) {

            $insClient['equipment']         = filter_var(trim($data['equipment']), FILTER_SANITIZE_STRING);
        }

        if (!empty($data['instructor'])) {

            $insClient['instructor']        = filter_var(trim($data['instructor']), FILTER_SANITIZE_STRING);
        }
        if (!empty($data['description'])) {
            $insClient['description']       =  $data['description'];
            if (!empty($data['intensity_rating']))
                $insClient['intensity_rating']  = filter_var($data['intensity_rating'], FILTER_SANITIZE_STRING);
        }
        if (!empty($data['file'])) {

            $video      = VideoMode::uploadVideo($data['file'], 'video');

            $duration   = VideoMode::videoDuration(public_path('videos/' . $video));

            $insClient['video_path'] =  $video;

            $insClient['duration']   =  $duration;
        }
        $lastid =  VideoMode::insertGetId($insClient);
        $get_send_notification_user  = DB::table('users')->get();

        if (sizeof($get_send_notification_user) > 1) {

            foreach ($get_send_notification_user as $row) {

                if ($row->device_type && $row->token) {

                    $device_token = $row->token;

                    $sendData = array(

                        'body'     => $row->name ? $row->name : '' . 'Upload new fitness video.',

                        'title' => 'Fitness Video',

                        'sound' => 'Default',

                    );

                    $this->fcmNotification($device_token, $sendData);
                }
            }
        }
        $res['status'] = 1;
        $res['last_id'] = $lastid;
        return redirect()->route('videomode.index')->with('success', 'Video uploaded successfully.');
    }



    /**

     * Display the specified resource.

     * @param  \App\Models\VideoMode  $videoMode

     * @return \Illuminate\Http\Response

     */

    public function show($id)

    {

        $id = filter_var($id, FILTER_VALIDATE_INT);

        if ($id == false) {

            return abort(404);
        } else {

            $mode = VideoMode::where('id', $id)->first();

            $demo_videoid = $mode['demo_videoid'];



            $data = array();

            if (!empty($demo_videoid)) {

                $data = DB::select("SELECT * FROM demo_video  WHERE id IN ($demo_videoid)");
            }



            return view('videomode.show', compact('mode', 'data'));
        }
    }



    /**

     * Show the form for editing the specified resource.

     * @param  \App\Models\VideoMode  $videoMode

     * @return \Illuminate\Http\Response

     */

    public function edit($id)

    {

        $videomode = $this->videomode->select('*')->where('id', $id)->get()->first();



        $demo_videoid = $videomode['demo_videoid'];

        $workout_video_id = $videomode['workout_video_id'];



        $data = array();

        $w_video = array();



        if (!empty($demo_videoid)) {

            $data = DB::select("SELECT * FROM demo_video  WHERE id IN ($demo_videoid)");
        }



        if (!empty($workout_video_id)) {

            $w_video = DB::select("SELECT * FROM demo_video  WHERE id='" . $workout_video_id . "'");
        }



        $get_demo_tag       = DB::table('demo_video')->where('category', 'Video')->get();



        //   $get_workout_tag    = DB::table('demo_video')->where('category', 'Workout Video')->whereNotNull('tag')->get();

        //  $get_workout_tag    = DB::table('demo_video')->where('category', 'Workout Video')->whereNotNull('tag')->get();

        $get_workout_tag    = DB::table('demo_video')->get();

        $user               = Config::get('video.demovideo');;

        $category           = Config::get('video.category');

        $muscle_group       = Config::get('video.muscle_group');

        $instructor         = Config::get('video.instructor');



        return view('videomode.edit', compact('w_video', 'get_workout_tag', 'videomode', 'category', 'muscle_group', 'instructor', 'user', 'data', 'get_demo_tag'));
    }



    /**

     * Update the specified resource in storage.

     * @param  \Illuminate\Http\Request  $request

     * @param  \App\Models\VideoMode  $videoMode

     * @return \Illuminate\Http\Response

     */

    public function update(Request $request, $id)

    {

        $this->videoUploadValidate($request, $id);

        $data = $request->all();



        if (!empty($_POST['demovideo'])) {

            $insClient['demo_videoid']       =  implode(",", array_unique($_POST['demovideo']));
        }



        $insClient['workout_video_id']  = filter_var($data['w_video'], FILTER_SANITIZE_STRING);

        $insClient['duration']          = filter_var(trim($data['workout_time']), FILTER_SANITIZE_STRING);

        $insClient['video_title']       = filter_var($data['video_title']);

        $insClient['category']          = filter_var(trim($data['category']), FILTER_SANITIZE_STRING);

        $insClient['muscle_group']      = filter_var(trim($data['muscle_group']), FILTER_SANITIZE_STRING);

        $insClient['equipment']         = filter_var(trim($data['equipment']), FILTER_SANITIZE_STRING);

        $insClient['instructor']        = filter_var(trim($data['instructor']), FILTER_SANITIZE_STRING);

        $insClient['description']       = $data['description'];



        if (!empty($data['file'])) {

            $video      = VideoMode::uploadVideo($data['file'], 'video');

            $duration   = VideoMode::videoDuration(public_path('videos/' . $video));

            $insClient['video_path'] =  $video;

            $insClient['duration']   =  $duration;
        }



        $this->videomode->where('id', $id)->update($insClient);

        return redirect()->route('videomode.index')->with('success', 'video updated successfully.');
    }



    /**

     * Remove the specified resource from storage.

     * @param  \App\Models\VideoMode  $videoMode

     * @return \Illuminate\Http\Response

     */

    public function destroy($id)

    {

        $videomode = $this->videomode->where('id', $id)->get()->first();

        $videomode->delete();

        return redirect()->route('videomode.index')->with('success', 'video deleted successfully');
    }



    private function videoUploadValidate($request, $id = null)

    {

        $validate['video_title']    = 'required';

        $validate['category']       = 'required';

        $validate['muscle_group']   = 'required';

        $validate['equipment']      = 'required';

        $validate['instructor']     = 'required';

        $validate['description']    = 'required';



        $messages = [

            'video_title.required'  => __('Please Enter Video Title'),

            'category.required'     => __('Please Select Category'),

            'muscle_group.required' => __('Please Select Muscle Group'),

            'equipment.required'    => __('Please Enter Equipment'),

            'instructor.required'   => __('Please Select Instructor'),

            'description.required'  => __('Please Enter Description')

        ];



        $request->validate($validate, $messages);
    }





    public function fitness_status(Request $request)
    {

        $id = $request->v_id;

        $get       = DB::table('video_mode')->where('id', $id)->get();

        if ($get[0]->status == 1) {

            $update = DB::table('video_mode')->where('id', $id)->update(['status' => 0]);

            $res['status'] = 1;

            $res['msg'] = 'Fitness de-active successfully';
        } else {

            $update = DB::table('video_mode')->where('id', $id)->update(['status' => 1]);

            $res['status'] = 2;

            $res['msg'] = 'Fitness active successfully';
        }

        echo json_encode($res);
    }





    public function fcmNotification($device_token, $sendData)

    {

        // print_r($device_token); die;

        if (empty($device_token)) {

            return false;
        }

        #API access key from Google API's Console

        if (!defined('API_ACCESS_KEY')) {

            define('API_ACCESS_KEY', 'AAAAMc9Z7z4:APA91bHWHZuOyvtZLmeXqT0S_2ZIAA2mKrrs-e1fbk5aaxR4aty8v4iD3KXTdbJlKLJlQcUTCTgsJdLlUvlTk3bqcHvjg0_IxGr2XplCu-UEKUzIqjtfu7I6vgAAock9n5swSCGtMwHX');
        }



        $fields = array(

            'to'            => $device_token,

            'data'          => $sendData,

            'notification'  => $sendData

        );

        //  print_r($fields); die;



        $headers = array(

            'Authorization: key=' . API_ACCESS_KEY,

            'Content-Type: application/json'

        );

        //$url = 'https://fcm.googleapis.com/fcm/send';

        #Send Request To FireBase Server

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');

        curl_setopt($ch, CURLOPT_POST, true);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        $result = curl_exec($ch);

        //print_r($result); 



        if ($result === false) {

            die('Curl failed ' . curl_error($ch));
        }

        curl_close($ch);

        //return $result;

    }

    public function video_mode_status(Request $request)
    {
        #print_r($request->all());die;
        $id = $request->v_id;
        $get       = DB::table('video_mode')->where('id', $id)->get();

        if ($get[0]->show_status == 1) {

            $update = DB::table('video_mode')->where('id', $id)->update(['show_status' => 0]);
            $res['status'] = 1;
            $res['msg'] = 'Fitness de-activate successfully';
        } else {

            $update = DB::table('video_mode')->where('id', $id)->update(['show_status' => 1]);
            $res['status'] = 2;
            $res['msg'] = 'Fitness activate successfully';
        }

        echo json_encode($res);
    }
}
