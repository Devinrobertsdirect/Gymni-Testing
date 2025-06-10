<?php

namespace App\Http\Controllers;

use Config;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    public function __construct(){
          $this->middleware('auth');
          $this->user    =  New User;
    }


    public function index()
    {
        $where = [];
       // $users = $this->user->where('role', 0)->orderBy('id', 'desc')->latest()->paginate(10);
        $users = DB::table('users')->where('role', 0)->orderBy('id', 'DESC')->get();
        return view('users.index',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
       public function store(Request $request) {
        $this->userValidate($request);
        $data = $request->all();

        $insClient['name']       = filter_var($data['name'],FILTER_SANITIZE_STRING);
        $insClient['email']      = filter_var($data['email'],FILTER_VALIDATE_EMAIL);
        $insClient['gender']     = filter_var($data['gender'],FILTER_SANITIZE_STRING);
        $insClient['dob']        = filter_var($data['dob'],FILTER_SANITIZE_STRING);

        if(!empty($insClient['dob'] )){
           $insClient['dob'] =  date("Y-m-d", strtotime($insClient['dob']));

        }

        $data['password']        = filter_var($data['password'],FILTER_SANITIZE_STRING);
        $insClient['password']   = Hash::make($data['password']);
        $insClient['weight']      = filter_var($data['weight'],FILTER_VALIDATE_INT);
        $insClient['gols']        = filter_var($data['gols'],FILTER_SANITIZE_STRING);
        $insClient['profile_bio']     = filter_var($data['profile_bio'],FILTER_SANITIZE_STRING);
        $insClient['phone']           = $data['phone'];
        $this->user->create($insClient);
        return redirect()->route('users.index')->with('success','User created successfully.');
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
        $user        = User::where('id',$id)->first();
        return view('users.show',compact('user'));
        }
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
         $user = $this->user->select('*')->where('id',$id)->get()->first();
         //dd($user);
          return view('users.edit',compact('user'));
     }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function update(Request $request,$id) {
            $this->userValidate($request,$id);
            $data                   = $request->all();
            $insClient['name']       = filter_var($data['name'],FILTER_SANITIZE_STRING);
            $insClient['email']      = filter_var($data['email'],FILTER_VALIDATE_EMAIL);
            $insClient['gender']     = filter_var($data['gender'],FILTER_SANITIZE_STRING);
            $insClient['dob']        = filter_var($data['dob'],FILTER_SANITIZE_STRING);

            if(!empty($insClient['dob'] )){
               $insClient['dob'] =  date("Y-m-d", strtotime($insClient['dob']));

            }

            $data['password']        = filter_var($data['password'],FILTER_SANITIZE_STRING);
            $insClient['password']   = Hash::make($data['password']);
            $insClient['weight']      = filter_var($data['weight'],FILTER_VALIDATE_INT);
            $insClient['gols']        = filter_var($data['gols'],FILTER_SANITIZE_STRING);
            $insClient['profile_bio']     = filter_var($data['profile_bio'],FILTER_SANITIZE_STRING);
            $insClient['phone']           = filter_var($data['phone'],FILTER_SANITIZE_STRING);
            $this->user->where('id',$id)->update($insClient);
            return redirect()->route('users.index')->with('success','User updated successfully.');
        }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $user = $this->user->where('id',$id)->get()->first();
        $user->delete();
        return redirect()->route('users.index')->with('success','user deleted successfully');
    }

   private function userValidate($request,$id=null){
      
        $validate['name']               = 'required|regex:/^[a-z A-Z]{3,30}$/';
        $validate['phone']              = 'required|max:17|min:17';

        if(!empty($id)){
            $validate['email']          = 'required|email|unique:users,email,'.$id.',id';
            $validate['password']       = 'nullable|min:6|regex:/[a-zA-Z0-9\s]+/';
        }else{
            $validate['email']          = 'required|email|unique:users';
            $validate['password']       = 'required|min:6|regex:/[a-zA-Z0-9\s]+/';
        }

        $validate['dob']                = 'required';
        $validate['gender']             = 'required';
        $validate['weight']             = 'required|numeric';
        $validate['gols']               = 'required';
        $validate['profile_bio']        = 'required';

        $messages = [
           'name.required'          => __('Please Enter Name'),
           'name.regex'             => __('Please Enter Valid Name'),
           'email.required'         => __('Please Enter Email'),
           'email.email'            => __('Please Enter Valid Email'),
           'email.unique'           => __('Email Already Registered'),
           'password.required'      => __('Please Enter Password'),
           'password.regex'         => __('Please Enter Valid Password (minimum length 6 including alpha numeric)'),
           'dob.required'           => __('Please Enter DOB'),
           'gender.required'        => __('Please Enter Gender'),
           'weight.required'        => __('Please Enter Weight'),
           'weight.numeric'         => __('Please Enter Valid Weight'),
           'gols.required'          => __('Please Enter Gols'),
           'profile_bio.required'   => __('Please Enter Profile Bio'),
             
        ];
        $request->validate($validate,$messages);
        
    }
}
