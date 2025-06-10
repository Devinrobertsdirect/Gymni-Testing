<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AboutUs;
use App\Models\AboutUsMulti;

class AboutusController extends Controller
{
    public function __construct(){
          $this->middleware('auth');
          $this->aboutus    =  New AboutUs;
          $this->aboutusmulti    =  New AboutUsMulti;

    }


    public function index()
    {
        $where = [];
        $aboutus = $this->aboutus->first();
        $aboutusmulti = DB::table('aboutus_multi')->orderBy('id', 'DESC')->get();
        //$aboutusmulti = $this->aboutusmulti->orderBy('id', 'desc')->latest()->paginate(10);
        $aboutusmulti = DB::table('aboutus_multi')->orderBy('id', 'DESC')->get();
        return view('aboutus.index',compact('aboutus','aboutusmulti'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
   public function create() {
        return view('aboutus.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->aboutValidate($request);
        $data = $request->all();

        $insClient['title']       = filter_var($data['title'],FILTER_SANITIZE_STRING);
        $insClient['content']    = filter_var($data['content'],FILTER_SANITIZE_STRING);
      
        if(!empty($data['file'])){
              $img  =   AboutUsMulti::uploadVideo($data['file'],'about');
              $insClient['image'] =  $img ;
        }

        $this->aboutusmulti->create($insClient);
        return redirect()->route('aboutus.index')->with('success','About Us data created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   public function show($id) {
        $id    = filter_var($id,FILTER_VALIDATE_INT);
        if($id == false)
        {
             return abort(404);
        }
        else{
        $aboutusmulti        = AboutUsMulti::where('id',$id)->first();
        $aboutus             = $this->aboutus->first();
        return view('aboutus.show',compact('aboutus','aboutusmulti'));
        }
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $aboutus = $this->aboutusmulti->select('*')->where('id',$id)->get()->first();
        return view('aboutus.edit',compact('aboutus'));
    }

     public function updateContent(Request $request) {
            $data                   = $request->all();
            $id = $data['id'];
            $this->mainAboutValidate($request,$id);
            $data                   = $request->all();
            $insClient['title']       = $data['title'];
            $insClient['content']     = $data['content'];
            $this->aboutus->where('id',$data['id'])->update($insClient);
            return redirect()->route('aboutus.index')->with('success','About us data updated successfully.');
    }


    public function update(Request $request, $id)
    {
        $this->aboutValidate($request,$id);
        $data = $request->all();

        $insClient['title']       = filter_var($data['title'],FILTER_SANITIZE_STRING);
        $insClient['content']    = filter_var($data['content'],FILTER_SANITIZE_STRING);
      
        if(!empty($data['file'])){
              $img  =   AboutUsMulti::uploadVideo($data['file'],'about');
              $insClient['image'] =  $img ;
        }

        $this->aboutusmulti->where('id',$id)->update($insClient);
        return redirect()->route('aboutus.index')->with('success','About Us data updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $aboutusmulti = $this->aboutusmulti->where('id',$id)->get()->first();
        $aboutusmulti->delete();
        return redirect()->route('aboutus.index')->with('success','About Us data deleted successfully');
    }


     private function aboutValidate($request,$id=null) {
      
        $validate['title']               = 'required|regex:/^[a-z A-Z]{3,30}$/';
     
        // if(!empty($id)){
        //     $validate['file']            = 'nullable|mimes:jpeg,png,jpg,gif';
        // }else{
        //     $validate['file']            = 'required|mimes:jpeg,png,jpg,gif';
        // }

        $validate['content']            = 'required';
      
        $messages = [
           'title.required'          => __('Please Enter Title'),
           'title.regex'             => __('Please Enter Valid Title'),
           'content.required'        => __('Please Enter Content')
             
        ];
        $request->validate($validate,$messages);
        
    }

    private function mainAboutValidate($request,$id=null) {
      
        $validate['title']               = 'required|regex:/^[a-z A-Z]{3,30}$/';
        $validate['content']            = 'required';
      
        $messages = [
           'title.required'          => __('Please Enter Title'),
           'title.regex'             => __('Please Enter Valid Title'),
           'content.required'        => __('Please Enter Content')
             
        ];
        $request->validate($validate,$messages);
        
    }

    public function delImgAbout(Request $request)
    {
        $data = $request->all();
        $id = $data['Id'];
        $insClient['image'] = null;
        $this->aboutusmulti->where('id',$id)->update($insClient);
        return redirect()->back()->with('success','Image deleted successfully.');     
    }
}
