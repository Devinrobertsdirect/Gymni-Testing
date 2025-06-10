<?php

namespace App\Http\Controllers;

use App\Models\Privacypolicy;
use Illuminate\Http\Request;
use App\Models\PrivacypolicyMulti;
use DB;

class PrivacypolicyController extends Controller
{
    
     public function __construct(){
     $this->middleware('auth');
     $this->privacypolicy    =  New Privacypolicy;
     $this->privacypolicyMulti    =  New PrivacypolicyMulti;

    }


    public function index()
    {
        $where = [];
        $privacypolicy = $this->privacypolicy->first();
       // $privacypolicyMulti = $this->privacypolicyMulti->orderBy('id', 'desc')->latest()->paginate(10);
        $privacypolicyMulti = DB::table('privacy_policy_multi')->orderBy('id', 'DESC')->get();
        return view('privacypolicy.index',compact('privacypolicy','privacypolicyMulti'));
    }


    public function updateContentPriv(Request $request) {
            $this->mainPrivValidate($request);
            $data                   = $request->all();
            $insClient['title']       = $data['title'];
            $insClient['content']     = $data['content'];
            $this->privacypolicy->where('id',$data['id'])->update($insClient);
            return redirect()->route('privacypolicy.index')->with('success','Privacy Policy data updated successfully.');
    }

    private function mainPrivValidate($request,$id=null) {
      
        $validate['title']               = 'required';
        $validate['content']            = 'required';
      
        $messages = [
           'title.required'          => __('Please Enter Title'),
           'content.required'        => __('Please Enter Content')
             
        ];
        $request->validate($validate,$messages);
        
    }

    public function create() {
        return view('privacypolicy.create');
    }


    public function store(Request $request)
    {
        $this->privValidate($request);
        $data = $request->all();

        $insClient['title']       = filter_var($data['title'],FILTER_SANITIZE_STRING);
        $insClient['content']    = filter_var($data['content'],FILTER_SANITIZE_STRING);
      
        if(!empty($data['file'])){
              $img  =   PrivacypolicyMulti::uploadVideo($data['file'],'privacypolicy');
              $insClient['image'] =  $img ;
        }

        $this->privacypolicyMulti->create($insClient);
        return redirect()->route('privacypolicy.index')->with('success','Privacy Policy data created successfully.');
    }


    private function privValidate($request,$id=null) {
      
        $validate['title']               = 'required|regex:/^[a-z A-Z]{3,30}$/';
     
        if(!empty($id)){
            $validate['file']            = 'nullable|mimes:jpeg,png,jpg,gif';
        }else{
            $validate['file']            = 'nullable|mimes:jpeg,png,jpg,gif';
        }

        $validate['content']            = 'required';
      
        $messages = [
           'title.required'          => __('Please Enter Title'),
           'title.regex'             => __('Please Enter Valid Title'),
           'content.required'        => __('Please Enter Content')
             
        ];
        $request->validate($validate,$messages);
        
    }


     public function show($id) {
        $id    = filter_var($id,FILTER_VALIDATE_INT);
        if($id == false)
        {
             return abort(404);
        }
        else{
        $privacypolicyMulti        = PrivacypolicyMulti::where('id',$id)->first();
        $privacypolicy             = $this->privacypolicy->first();
        return view('privacypolicy.show',compact('privacypolicy','privacypolicyMulti'));
        }
        
    }


    public function edit($id)
    {
        $privacypolicy = $this->privacypolicyMulti->select('*')->where('id',$id)->get()->first();
        return view('privacypolicy.edit',compact('privacypolicy'));
    }



    public function update(Request $request, $id) {
        $this->mainPrivValidate($request,$id);
        $data = $request->all();

        $insClient['title']       = filter_var($data['title'],FILTER_SANITIZE_STRING);
        $insClient['content']    = filter_var($data['content'],FILTER_SANITIZE_STRING);
      
        if(!empty($data['file'])){
              $img  =   PrivacypolicyMulti::uploadVideo($data['file'],'privacypolicyMulti');
              $insClient['image'] =  $img ;
        }

        $this->privacypolicyMulti->where('id',$id)->update($insClient);
        return redirect()->route('privacypolicy.index')->with('success','Privacy policy data updated successfully.');
    }



     public function destroy($id) {
        $privacypolicyMulti = $this->privacypolicyMulti->where('id',$id)->get()->first();
        $privacypolicyMulti->delete();
        return redirect()->route('privacypolicy.index')->with('success','Privacy Policy data deleted successfully');
    }

    public function delImgPriv(Request $request)
    {
        $data = $request->all();
        $id = $data['Id'];
        $insClient['image'] = null;
        $this->privacypolicyMulti->where('id',$id)->update($insClient);
        return redirect()->back()->with('success','Image deleted successfully.');     
    }


   
}
