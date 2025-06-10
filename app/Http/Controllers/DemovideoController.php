<?php



namespace App\Http\Controllers;

use Config;

use App\Models\Group;

use App\Models\User;

use Illuminate\Http\Request;

use DB;



class DemovideoController extends Controller

{

  public function __construct()
  {

    $this->middleware('auth');

    $this->group    =  new Group;

    $this->user    =  new User;
  }



  public function index()
  {

    // $where = [];

    $users = DB::table('demo_video')->orderBy('id', 'DESC')->get();

    // print_r($users); die;

    return view('demovideo.index', compact('users'));
  }



  public function show($id)

  {



    $id    = filter_var($id, FILTER_VALIDATE_INT);

    if ($id == false) {

      return abort(404);
    } else {

      $group        = Group::where('id', $id)->first();

      return view('group.show', compact('group'));
    }
  }



  public function destroy($id)

  {



    $image = DB::select('select * from demo_video where id = ?', [$id]);

    if ($image[0]->costum_thumImg) {

      unlink('public/costumThumbimg/' . $image[0]->costum_thumImg);
    }

    DB::delete('delete from demo_video where id = ?', [$id]);

    return redirect()->back()->with('success', 'Demo video deleted successfully.');
  }



  public function create()

  {

    $user =  Config::get('video.demovideo');

    return view('demovideo.create', compact('user'));
  }

  public function get_video_thumb($thumb_url)
  {

    // dd($thumb_url);

    // $curl = curl_init();

    //       curl_setopt_array($curl, array(

    //       CURLOPT_URL => 'https://vimeo.com/api/oembed.json?url='l,

    //       CURLOPT_RETURNTRANSFER => true,

    //       CURLOPT_ENCODING => '',

    //       CURLOPT_MAXREDIRS => 10,

    //       CURLOPT_TIMEOUT => 0,

    //       CURLOPT_FOLLOWLOCATION => true,

    //       CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,

    //       CURLOPT_CUSTOMREQUEST => 'GET',

    //       CURLOPT_HTTPHEADER => array(

    //       'Cookie: __cf_bm=iIvVwSdqgC2wbs2fYECpbTKi56H1fHVUO6NELb1L1ls-1676548981-0-AXFcE/n1Ohx+OjLtsOso1/NOTof+xgJFxBQ7bxEZdOP2C9uTy98K3NV43OOD0XDie0nTZBt5zCPA4cy+q9nuxgg='

    //       ),

    //       ));



    //       $response = curl_exec($curl);



    //       curl_close($curl);

    //        $thumurl_img = (json_decode($response, true));

    //        dd($thumurl_img);

    //        return $thumurl_img['thumbnail_url'];

  }





  public function store(Request $request)
  {

    //  $dd =  $this->get_video_thumb($request->input('url'));

    //print_r($dd); die;

    //   $request->validate([

    //     'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

    // ]);costumThumbimg   thumbnail

    if (!empty($request->file('thumbnail'))) {

      $imageName = time() . '.' . $request->thumbnail->extension();



      $request->thumbnail->move(public_path('costumThumbimg'), $imageName);
    }



    $title = $request->input('title');

    $url = $request->input('url');
    $url2 = $request->input('url2');

    $fitness_video = $url;

    // preg_match('/<iframe.*?src="(.*?)"/', $fitness_video, $matches);

    // $thumb_url = $matches[1];



    //  $image_thum =  $this->get_video_thumb($fitness_video);



    // $image_thum =  $this->ge

    $group_description = $request->input('group_description');

    $tag = implode(" ", $request->input('tag'));

    $category = $request->input('categorty');

    //print_r($tag); die;

    DB::insert('insert into demo_video (title,url,url2,description,tag,category,thum_img,costum_thumImg) values(?,?,?,?,?,?,?,?)', [$title, $url,$url2, $group_description, $tag, $category, $imageName, $imageName]);



    return redirect()->route('demovideo')->with('success', 'Demo video created successfully.');
  }



  public function edit($id)

  {



    $data = array();

    if (!empty($id)) {

      $data = DB::select('select * from demo_video where id = ?', [$id]);
    }

    $user =  Config::get('video.demovideo');;

    return view('demovideo.edit', compact('user', 'data'));
  }



  public function update(Request $request)

  {





    $id = $request->input('id');

    $data['title'] = $request->input('title');

    if (!empty($request->file('thumbnail'))) {

      $image = DB::select('select * from demo_video where id = ?', [$id]);

      //   if($image[0]->costum_thumImg){

      //     unlink('public/costumThumbimg/'.$image[0]->costum_thumImg);

      //   }

      $imageName = time() . '.' . $request->thumbnail->extension();



      $request->thumbnail->move(public_path('costumThumbimg'), $imageName);

      DB::table('demo_video')->where('id', $id)->update(['costum_thumImg' => $imageName]);
    }

    $data['url'] = $request->input('url');
    $data['url2'] = $request->input('url2');

    $data['description'] = $request->input('group_description');

    $fitness_video = $request->input('url');

    // preg_match('/<iframe.*?src="(.*?)"/', $fitness_video, $matches);

    // $thumb_url = $matches[1];

    // $data['thum_img'] =  $this->get_video_thumb($fitness_video);

    $data['tag'] = implode(" ", $request->input('tag'));

    $data['category'] = $request->input('categorty');



    DB::table('demo_video')->where('id', $id)->update($data);

    return redirect()->route('demovideo')->with('success', 'Demo video data updated successfully.');
  }

  private function groupValidate($request, $id = null)
  {



    $validate['group_name']                 = 'required';

    $validate['group_description']          = 'required';

    $validate['created_by']                 = 'required';

    $validate['members']                    = 'required';



    if (!empty($id)) {

      $validate['file']            = 'nullable|mimes:jpeg,png,jpg,gif';
    } else {

      $validate['file']            = 'nullable|mimes:jpeg,png,jpg,gif';
    }



    $messages = [

      'group_name.required'            => __('Please Enter Group Name'),

      'group_description.required'     => __('Please Enter Group Description'),

      'created_by.required'            => __('Please Select Created By'),

      'members.required'               => __('Please Select Members')



    ];

    $request->validate($validate, $messages);
  }



  public function delImgGrp(Request $request)

  {

    $data = $request->all();

    $id = $data['Id'];

    $insClient['image'] = null;

    $this->group->where('id', $id)->update($insClient);

    return redirect()->back()->with('success', 'Image deleted successfully.');
  }



  public function demo_video_status(Request $request)

  {

    $id = $request->v_id;

    $get       = DB::table('demo_video')->where('id', $id)->get();

    if ($get[0]->status == 1) {



      $update = DB::table('demo_video')->where('id', $id)->update(['status' => 0]);

      $res['status'] = 1;

      $res['msg'] = 'Demo Video de-active successfully';
    } else {



      $update = DB::table('demo_video')->where('id', $id)->update(['status' => 1]);



      $res['status'] = 2;



      $res['msg'] = 'Demo Video active successfully';
    }

    echo json_encode($res);
  }
}
