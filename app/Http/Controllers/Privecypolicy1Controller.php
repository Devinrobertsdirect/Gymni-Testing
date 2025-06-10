<?php

namespace App\Http\Controllers;

use App\Models\Privacypolicy;
use Illuminate\Http\Request;
use App\Models\PrivacypolicyMulti;
use DB;

class PrivecyController extends Controller
{
    
  


    public function privecy()
    {
        $where = [];
        $privacypolicy = $this->privacypolicy->first();
       // $privacypolicyMulti = $this->privacypolicyMulti->orderBy('id', 'desc')->latest()->paginate(10);
      //  $privacypolicyMulti = DB::table('privacy_policy_multi')->orderBy('id', 'DESC')->get();
        return view('privecy',compact('privacypolicy'));
    }
}
