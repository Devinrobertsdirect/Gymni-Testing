<?php

namespace App\Http\Controllers;

use Config;
use App\Models\Desmode;
use App\Models\ExerciseDescription;
use DB;
use Illuminate\Http\Request;

class DesmodeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->desmode = new Desmode;
    }

    public function index()
    {
        $where = [];

        $desmode = DB::table('description_mode')
            ->leftJoin('video_mode', 'video_mode.id', '=', 'description_mode.video_mode_lastid')
            ->leftJoin('share_description_mode', 'share_description_mode.description_mode_id', '=', 'description_mode.id')
            ->leftJoin('like_description_mode', 'like_description_mode.description_mode_id', '=', 'description_mode.id')
            ->select('video_mode.video_title', 'description_mode.*', DB::raw("count(like_description_mode.id) as total_like"), DB::raw("count(share_description_mode.id) as total_share"))
            ->orderBy('description_mode.id', 'desc')
            ->groupBy('description_mode.id')
            ->get();

        return view('desmode.index', compact('desmode'));
    }

    public function get_cate_video(Request $request)
    {
        $videid = $_GET['id'];
        $getvideo_mode_title = DB::table('video_mode')->where('id', $videid)->orderBy('id', 'DESC')->get();

        if (count($getvideo_mode_title) != 0) {
            $res['cat'] = $getvideo_mode_title[0]->category;
            echo json_encode($res);
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        $get_demo_tag       = DB::table('demo_video')->get();
        $get_workout_tag    = DB::table('demo_video')->where('category', 'Workout Video')->get();
        $get_desMode    = DB::table('description_mode')->get();
        $user               = Config::get('video.demovideo');
        $category           = Config::get('video.category');
        $muscle_group       = Config::get('video.muscle_group');
        $instructor         = Config::get('video.instructor');
        $intensityrating    = Config::get('video.intensityrating');
        // $getvideo_mode_title = DB::table('video_mode')->orderBy('id', 'DESC')->where('status',1)->get();
        $getvideo_mode_title = DB::table('video_mode')->orderBy('id', 'DESC')->get();
        //print_r(($getvideo_mode_title)); die;

        return view('desmode.create', compact('get_desMode', 'getvideo_mode_title', 'category', 'muscle_group', 'instructor', 'intensityrating', 'user', 'get_demo_tag', 'get_workout_tag'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $data = $request->all();

        $roundData = [];
        #print_r($request->description);die;

        if (!empty($data['round_description'])) {
            foreach ($data['round_description'] as $round => $des) {
                $i = 0;
                foreach ($des as $r => $v) {
                    if ($r == 'des') {
                        $roundData[$i][] = array(
                            "description" => $v[0]
                        );
                    }

                    if ($r == 'file') {
                        foreach ($v as $img) {
                            $chk = explode('.', $img->getClientOriginalName());

                            if ($chk[1] != "png") {
                                if ($chk[1] != "jpg") {
                                    if ($chk[1] != "jpeg") {
                                        return back()->with('error', __('Please upload jpg or png images.'));
                                    }
                                }
                            }

                            $name = time() . "_" . $img->getClientOriginalName();
                            $img->move(public_path() . '/images/', $name);
                            $imgData[] = $name;
                        }

                        $roundData[$i][] = array(
                            "file" => $imgData
                        );
                    }

                    $i++;
                    unset($imgData);
                }
            }
        }

        if (!empty($roundData[1][0])) {

            foreach ($roundData[0] as $rkey => $rval) {

                foreach ($roundData[1][0] as  $img) {

                    $roundDataGet[] = array(

                        "description" => $rval['description'],

                        "images"      => $img

                    );
                }
            }
        }

        $data = $request->all();

        // if (!empty($_POST['demovideo'])) {

        //     $insClient['demo_videoid'] = implode(",", array_unique($_POST['demovideo']));
        // }

        $demoVideotag = $request->input('tag', []);

        $demovideoId = DB::table('demo_video')
            ->where(function ($query) use ($demoVideotag) {
                foreach ($demoVideotag as $tag) {
                    $query->orWhere('tag', 'LIKE', "%$tag%");
                }
            })
            ->pluck('id')
            ->toArray();;

        if (!empty($demovideoId)) {
            $insClient['demo_videoid'] = implode(",", array_unique($demovideoId));
        }

        // Ensure all inputs are strings
        $insClient['img_title'] = is_array($data['img_title']) ? implode(",", $data['img_title']) : $data['img_title'];
        $insClient['category'] = is_array($data['category']) ? implode(",", $data['category']) : $data['category'];
        $insClient['muscle_group'] = is_array($data['muscle_group']) ? implode(",", $data['muscle_group']) : $data['muscle_group'];
        $insClient['equipment'] = is_array($data['equipment']) ? implode(",", $data['equipment']) : $data['equipment'];
        $insClient['instructor'] = is_array($data['instructor']) ? implode(",", $data['instructor']) : $data['instructor'];
        // $insClient['description'] = is_array($data['description']) ? json_encode($data['description']) : $data['description'];
        $insClient['intensity_rating'] = is_array($data['intensity_rating']) ? implode(",", $data['intensity_rating']) : $data['intensity_rating'];
        $insClient['video_mode_lastid'] = is_array($data['video_mode_lastid']) ? implode(",", $data['video_mode_lastid']) : $data['video_mode_lastid'];

        if (!empty($roundDataGet)) {
            $insClient['round_description'] = json_encode($roundDataGet);
        }

        if (!empty($data['filee'])) {
            $video = Desmode::uploadVideo($data['filee'], 'video');
            $insClient['demo_video'] = $video;
        }

        $this->desmode->create($insClient);

        // Get the last inserted id to add foreign key in the exercise description table
        $id = DB::getPdo()->lastInsertId();
        
        // Process exercise description
        $exerciseData = [];
        if (!empty($request->description)) {
            foreach ($request->description as $exerciseBlock) {
                $exerciseTitle = $exerciseBlock['exercise_title'] ?? '';
                $notes = $exerciseBlock['notes'] ?? '';
                foreach ($exerciseBlock['exercises'] as $exercise) {

                    if (empty($exercise['exercise_name'])) {
                        continue;
                    }

                    $exerciseData[] = [
                        'description_mode_id' => $id,
                        'exercise_title' => $exerciseTitle,
                        'exercise_name' => $exercise['exercise_name'] ?? '',
                        'sets' => $exercise['sets']['value'] ?? 0,
                        'sets_status' => $exercise['sets']['status'] ?? '',
                        'reps' => $exercise['reps']['value'] ?? 0,
                        'reps_status' => $exercise['reps']['status'] ?? '',
                        'weight' => $exercise['weight']['value'] ?? 0,
                        'weight_status' => $exercise['weight']['status'] ?? '',
                        'rpe' => $exercise['rpe']['value'] ?? 0,
                        'rpe_status' => $exercise['rpe']['status'] ?? '',
                        'notes' => $notes,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }
        
        // Insert exercise data into the database
        if (!empty($exerciseData)) {
            DB::table('exercise_description')->insert($exerciseData);
        }

        return response()->json(['status' => 1, 'message' => 'Description mode added successfully'], 200);
    }

    public function show($id)
    {
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if ($id == false) {
            return abort(404);
        } else {
            $desmode        = Desmode::where('id', $id)->first();
            $demo_videoid   = $desmode['demo_videoid'];
            $data = array();
            if (!empty($demo_videoid)) {
                $data = DB::select("SELECT * FROM demo_video  WHERE id IN ($demo_videoid)");
            }
            $exerciseDescription = ExerciseDescription::where('description_mode_id', $id)->select('exercise_title', 'exercise_name', 'sets', 'reps', 'weight', 'rpe')->get();
            // dd($exerciseDescription);
            return view('desmode.show', compact('desmode', 'data', 'exerciseDescription'));
        }
    }



    /**

     * Show the form for editing the specified resource.

     * @param  \App\Models\Desmode  $desmode

     * @return \Illuminate\Http\Response

     */

    public function edit($id)
    {
        $get_demo_tag       = DB::table('demo_video')->whereNotNull('tag')->where('tag', '!=', '')->get();
        $get_workout_tag    = DB::table('demo_video')->where('category', 'Workout Video')->get();
        $category           = Config::get('video.category');
        $muscle_group       = Config::get('video.muscle_group');
        $instructor         = Config::get('video.instructor');
        $desmode            = $this->desmode->select('*')->where('id', $id)->get()->first();
        $exerciseDescription = ExerciseDescription::where('description_mode_id', $id)->get();
        $demo_videoid       = $desmode['demo_videoid'];
        $getvideo_mode_title = DB::table('video_mode')->orderBy('id', 'DESC')->get();

        $data = array();
        if (!empty($demo_videoid)) {
            $data = DB::select("SELECT * FROM demo_video  WHERE id IN ($demo_videoid)");
        }

        $user = Config::get('video.demovideo');

        return view('desmode.edit', compact(
            'getvideo_mode_title',
            'desmode',
            'category',
            'muscle_group',
            'instructor',
            'user',
            'data',
            'get_demo_tag',
            'get_workout_tag',
            'exerciseDescription'
        ));
    }

    /**
     * Update the specified resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Desmode  $desmode
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        $data = $request->all();

        $roundData = $roundDataGet = $imgData = [];

        if (!empty($data['round_description'])) {
            foreach ($data['round_description'] as $round => $file) {
                $i = 0;
                foreach ($file['file'] as $r => $f) {
                    if (@is_array(getimagesize($f))) {
                        $chk =  explode('.', $f->getClientOriginalName());

                        if ($chk[1] != "png") {
                            if ($chk[1] != "jpg") {
                                if ($chk[1] != "jpeg") {
                                    return back()->with('error', __('Please upload jpg or png images.'));
                                }
                            }
                        }

                        if (@is_array(getimagesize($f))) {
                            $name = time() . "_" . $f->getClientOriginalName();
                            $f->move(public_path() . '/images/', $name);
                            $imgData[] = $name;
                        } else {
                            $imgData[] = $f;
                        }
                    } else {
                        if (@is_array(getimagesize($f))) {
                            print_r($f);
                            exit;

                            $chk =  explode('.', $f->getClientOriginalName());
                            if ($chk[1] != "png") {
                                if ($chk[1] != "jpg") {
                                    if ($chk[1] != "jpeg") {
                                        return back()->with('error', __('Please upload jpg or png images.'));
                                    }
                                }
                            }
                        } else {
                            if (@is_array(getimagesize($f))) {
                                $chk =  explode('.', $f->getClientOriginalName());
                                if ($chk[1] != "png") {
                                    if ($chk[1] != "jpg") {
                                        if ($chk[1] != "jpeg") {
                                            return back()->with('error', __('Please upload jpg or png images.'));
                                        }
                                    }
                                }
                            } else {
                                $chk =  explode('.', $f);
                                if (isset($chk[1]) && $chk[1] != "png") {
                                    if ($chk[1] != "jpg") {
                                        if ($chk[1] != "jpeg") {
                                            return back()->with('error', __('Please upload jpg or png images.'));
                                        }
                                    }
                                }
                            }
                            $imgData[] = $f;
                        }
                    }
                }

                $roundData[$i][] = array(
                    "images" => $imgData
                );

                foreach ($file['des'] as $r => $f) {
                    $roundData[$i][] = array(
                        "description" => $f
                    );
                }

                $i++;
                unset($imgData);
            }
        }

        if (!empty($roundData)) {
            foreach ($roundData[0] as $rkey => $rval) {
                if ($rkey % 2 != 0) {
                    $roundDataGet[$rkey] = array(
                        "description" => $roundData[0][$rkey]['description']
                    );
                } else {
                    $roundDataGet[$rkey] = array(
                        "images" => $roundData[0][$rkey]['images']
                    );
                }
            }
        }

        if (!empty($roundData)) {
            foreach ($roundData[0] as $roundkey => $roundval) {
                if (!empty($roundData[0][$roundkey + 1]['description'])) {
                    $roundDataGetNew[] = array(
                        "description" => $roundData[0][$roundkey + 1]['description'],
                        "images"      => $roundval['images']
                    );
                }
            }

            $desmode = $desmode = $this->desmode->select('*')->where('id', $id)->get()->first();

            if (!empty($desmode->round_description)) {
                $round_description = json_decode($desmode->round_description);
                foreach ($round_description as $rkey => $rval) {
                    foreach ($rval->images as $ikey => $ival) {
                        $arraySearch = array_search($ival, $roundDataGetNew[$rkey]['images'], true);

                        if ($arraySearch == false) {
                            $roundDataGetNew[$rkey]['images'][] = $ival;
                        }
                    }
                }
            }

            foreach ($roundDataGetNew as $key => $val) {
                $roundDataGetNew[$key]['images'] = array_unique($val['images']);
            }
        }

        // $imgs = [];
        // if (!empty($_POST['demovideo'])) {
        //     $insClient['demo_videoid'] =  implode(",", array_unique($_POST['demovideo']));
        // }

        if (!empty($data['demovideo']) && is_array($data['demovideo'])) {
            $insClient['demo_videoid'] = implode(",", array_unique($data['demovideo']));
        }

        $insClient['img_title']         = filter_var($data['img_title']);
        $insClient['category']          = filter_var(trim($data['category']), FILTER_SANITIZE_STRING);
        $insClient['muscle_group']      = filter_var($data['muscle_group'], FILTER_SANITIZE_STRING);
        $insClient['equipment']         = filter_var($data['equipment'], FILTER_SANITIZE_STRING);
        $insClient['instructor']        = filter_var($data['instructor'], FILTER_SANITIZE_STRING);
        // $insClient['description']       = $data['description'];
        $insClient['video_mode_lastid']  = filter_var($data['video_mode_lastid'], FILTER_SANITIZE_STRING);

        if (!empty($roundDataGetNew)) {
            $insClient['round_description'] = json_encode($roundDataGetNew);
        }

        if (!empty($data['filee'])) {
            $video  =   Desmode::uploadVideo($data['filee'], 'video');
            $insClient['demo_video'] = $video;
        }

        $this->desmode->where('id', $id)->update($insClient);
        #print_r($request->description);die;
        // Process exercise description
        $exerciseData = [];
        if (!empty($request->description)) {
            // First, delete existing exercise descriptions for update
            DB::table('exercise_description')->where('description_mode_id', $id)->delete();

            foreach ($request->description as $exerciseBlock) {
                $exerciseTitle = $exerciseBlock['exercise_title'] ?? '';
                $notes = $exerciseBlock['notes'] ?? '';

                foreach ($exerciseBlock['exercises'] as $exercise) {
                    $exerciseData[] = [
                        'description_mode_id' => $id,
                        'exercise_title' => $exerciseTitle,
                        'exercise_name' => $exercise['exercise_name'] ?? '',
                        'sets' => $exercise['sets']['value'] ?? 0,
                        'sets_status' => $exercise['sets']['status'] ?? '',
                        'reps' => $exercise['reps']['value'] ?? 0,
                        'reps_status' => $exercise['reps']['status'] ?? '',
                        'weight' => $exercise['weight']['value'] ?? 0,
                        'weight_status' => $exercise['weight']['status'] ?? '',
                        'rpe' => $exercise['rpe']['value'] ?? 0,
                        'rpe_status' => $exercise['rpe']['status'] ?? '',
                        'notes' => $notes,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }
        #print_r($exerciseData);die;
        // Insert exercise data into the database
        if (!empty($exerciseData)) {
            DB::table('exercise_description')->insert($exerciseData);
        }

        return response()->json(['status' => 1, 'message' => 'Description mode added successfully'], 200);

        // return redirect()->route('desmode.index')->with('success', 'Description mode updated successfully.');
    }



    public function delete_video()

    {

        $rowid      = $_GET['id'];

        $video_id   = $_GET['vid'];

        $data       = DB::select("SELECT * from description_mode where id='" . $rowid . "'");



        $unsetdata  = (explode(",", $data[0]->demo_videoid));

        if (($key = array_search($video_id, $unsetdata)) !== false) {

            unset($unsetdata[$key]);
        }



        $datas['demo_videoid'] = implode(" ", $unsetdata);



        DB::table('description_mode')->where('id', $rowid)->update($datas);

        return redirect()->back()->with('success', 'demo video deleted successfully.');
    }



    /**

     * Remove the specified resource from storage.

     * @param  \App\Models\Desmode  $desmode

     * @return \Illuminate\Http\Response

     */

    public function destroy($id)
    {
        $desmode = $this->desmode->where('id', $id)->get()->first();
        $desmode->delete();

        $exerciseDescription = ExerciseDescription::where('description_mode_id', $id)->get();

        foreach ($exerciseDescription as $description) {
            $description->delete();
        }

        $deleted = DB::table('logweight')->where('description_mode_id', $id)->delete();
        
        return redirect('desmode')->with('success', 'Description mode deleted successfully');
    }

    private function ImagesValidate($request, $id = null)

    {

        $validate['img_title']      = 'required';

        $validate['category']       = 'required';

        $validate['muscle_group']   = 'required';

        $validate['equipment']      = 'required';

        $validate['instructor']     = 'required';

        $validate['description']    = 'required';



        if (empty($id)) {

            $validate['filee']  = 'required|mimes:mp4,mov,ogg,qt';
        } else {

            $validate['filee']  = 'nullable|mimes:mp4,mov,ogg,qt';
        }



        $messages = [

            'img_title.required'    => __('Please Enter Video Title'),

            'category.required'     => __('Please Select Category'),

            'muscle_group.required' => __('Please Select Muscle Group'),

            'equipment.required'    => __('Please Enter Equipment'),

            'instructor.required'   => __('Please Select Instructor'),

            'description.required'  => __('Please Enter Description'),

            'filee.mimes'           => __('Please Upload a Right File (only video)')

        ];



        $request->validate($validate, $messages);
    }



    public function delImg(Request $request)

    {

        $data       = $request->all();

        $id         = $data['desId'];

        $img        = $data['img'];

        $desmode    = $this->desmode->select('*')->where('id', $id)->get()->first();



        if (!empty($desmode->round_description)) {

            $round_description = json_decode($desmode->round_description);



            foreach ($round_description as $rd) {

                foreach ($rd->images as $imgs => $v) {

                    if ($v == $img) {

                        unset($rd->images[$imgs]);
                    }
                }



                $rd->images = array_values($rd->images);
            }



            $insClient['round_description'] = json_encode($round_description);

            $this->desmode->where('id', $id)->update($insClient);

            return redirect()->back()->with('success', 'Image deleted successfully.');
        }
    }



    public function delBlog(Request $request)

    {

        $data       = $request->all();

        $id         = $data['desId'];

        $des        = $data['des'];

        $desmode    = $this->desmode->select('*')->where('id', $id)->get()->first();



        if (!empty($desmode->round_description)) {

            $round_description = json_decode($desmode->round_description);



            foreach ($round_description as $rkey => $rval) {

                if ($rval->description == $des) {

                    unset($round_description[$rkey]);
                }
            }



            $round_description = array_values($round_description);



            $insClient['round_description'] = json_encode($round_description);

            $this->desmode->where('id', $id)->update($insClient);

            return redirect()->back()->with('success', 'Section deleted successfully.');
        }
    }



    public function uploadDemoVideo(Request $request)

    {

        $data   = $request->all();

        $id     = $data['id'];

        return view('desmode.uploadDemoVideo', compact('id'));
    }



    public function demoVideo(Request $request)

    {

        $data   = $request->all();

        $id     = $data['id'];



        $this->videoUploadValidate($request, $id);



        if (!empty($data['file'])) {

            $video = Desmode::uploadVideo($data['file'], 'video');

            $insClient['demo_video'] = $video;
        }



        $this->desmode->where('id', $id)->update($insClient);

        return redirect()->route('videomode.index')->with('success', 'Demo video upload successfully.');
    }



    private function videoUploadValidate($request, $id = null)

    {

        if (!empty($id)) {

            $validate['file'] = 'nullable|mimes:mp4,mov,ogg,qt';
        } else {

            $validate['file'] = 'required|mimes:mp4,mov,ogg,qt';
        }



        $messages = [];

        $request->validate($validate, $messages);
    }



    public function checkVideoMode(Request $request)
    {

        $id   = $request->value;

        $getvideo       = DB::table('description_mode')->where('video_mode_lastid', $id)->first();

        if (!empty($getvideo->video_mode_lastid)) {

            return response()->json(['status' => 1, 'message' => 'Description mode already added'], 200);
        } else {

            return response()->json(['status' => 2, 'message' => 'Description mode exist'], 200);
        }
    }



    public function logweight_add($id)
    {

        if ($id) {



            $videomode = DB::table('video_mode')->get();

            return view('desmode.logweight_add', compact('videomode', 'id'));
        }
    }
}
