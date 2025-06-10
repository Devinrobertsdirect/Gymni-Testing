<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use App\Models\User;
use Session;
use Hash;

class LoginController extends Controller
{
    public $response                        = ['msg'=>'','msg_type'=>'success'];
    public $requestType                     = []; 

    
    public function __construct(){
        $this->user       = new User();
    }

  public function login (Request $request)  {
      print_r("lll");
      exit;
    
    }


}
