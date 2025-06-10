<?php

namespace App\Http\Controllers;

use App\Models\TermCondition;
use Illuminate\Http\Request;
use App\Models\TermConditionMulti;
use DB;

class TermConditionController extends Controller
{
   public function __construct(){
          $this->middleware('auth');
          $this->termCondition    =  New TermCondition;
          $this->termConditionMulti    =  New TermConditionMulti;

    }

    public function index()
    {
        $where = [];
        $termCondition = $this->termCondition->first();
        //$termConditionMulti = $this->termConditionMulti->orderBy('id', 'desc')->latest()->paginate(10);
        $termConditionMulti = DB::table('terms_condition_multi')->orderBy('id', 'DESC')->get();
        return view('termCondition.index',compact('termCondition','termConditionMulti'));
    }

    public function updateContentTerm(Request $request) {
            $this->mainTermValidate($request);
            $data                   = $request->all();
            $insClient['title']       = $data['title'];
            $insClient['content']     = $data['content'];
            $this->termCondition->where('id',$data['id'])->update($insClient);
            return redirect()->route('termCondition.index')->with('success','About us data updated successfully.');
    }

     private function mainTermValidate($request,$id=null) {
      
        $validate['title']               = 'required';
        $validate['content']            = 'required';
      
        $messages = [
           'title.required'          => __('Please Enter Title'),
           'content.required'        => __('Please Enter Content')
             
        ];
        $request->validate($validate,$messages);
        
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('termCondition.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
   public function store(Request $request)
    {
        $this->termValidate($request);
        $data = $request->all();

        $insClient['title']       = filter_var($data['title'],FILTER_SANITIZE_STRING);
        $insClient['content']    = filter_var($data['content'],FILTER_SANITIZE_STRING);
      
        if(!empty($data['file'])){
              $img  =   TermConditionMulti::uploadVideo($data['file'],'termcondition');
              $insClient['image'] =  $img ;
        }

        $this->termConditionMulti->create($insClient);
        return redirect()->route('termCondition.index')->with('success','Terms Conditions data created successfully.');
    }


    private function termValidate($request,$id=null) {
      
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TermCondition  $termCondition
     * @return \Illuminate\Http\Response
     */
   public function show($id) {
        $id    = filter_var($id,FILTER_VALIDATE_INT);
        if($id == false)
        {
             return abort(404);
        }
        else{
        $termConditionMulti        = TermConditionMulti::where('id',$id)->first();
        $termCondition             = $this->termCondition->first();
        return view('termCondition.show',compact('termCondition','termConditionMulti'));
        }
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TermCondition  $termCondition
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $termCondition = $this->termConditionMulti->select('*')->where('id',$id)->get()->first();
        return view('termCondition.edit',compact('termCondition'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TermCondition  $termCondition
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->termValidate($request,$id);
        $data = $request->all();

        $insClient['title']       = filter_var($data['title'],FILTER_SANITIZE_STRING);
        $insClient['content']    = filter_var($data['content'],FILTER_SANITIZE_STRING);
      
        if(!empty($data['file'])){
              $img  =   TermConditionMulti::uploadVideo($data['file'],'termcondition');
              $insClient['image'] =  $img ;
        }

        $this->termConditionMulti->where('id',$id)->update($insClient);
        return redirect()->route('termCondition.index')->with('success','Term Condition data updated successfully.');
    }

     public function destroy($id) {
        $termConditionMulti = $this->termConditionMulti->where('id',$id)->get()->first();
        $termConditionMulti->delete();
        return redirect()->route('termCondition.index')->with('success','Term Conditions data deleted successfully');
    }


    public function delImgTerm(Request $request)
    {
        $data = $request->all();
        $id = $data['Id'];
        $insClient['image'] = null;
        $this->termConditionMulti->where('id',$id)->update($insClient);
        return redirect()->back()->with('success','Image deleted successfully.');     
    }

    


}
