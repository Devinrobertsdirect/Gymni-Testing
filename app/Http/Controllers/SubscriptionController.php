<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\User;
use DB;

class SubscriptionController extends Controller
{
    public function __construct(){
          $this->middleware('auth');
          $this->subscription    =  New Subscription;
    }

     public function index()
    {
        $where = [];
        $header = "Subscription List";
      //  $subscription = $this->subscription->orderBy('id', 'desc')->latest()->paginate(10);
        $subscription = DB::table('subscription_plan')->orderBy('id', 'DESC')->get();
        return view('subscription.index',compact('subscription','header'));
    }



      public function topplans()
    {

      $where = [];
      $header = "Most popular subscription plan";
      $subscription =   User::select('subs_plan', DB::raw('COUNT(subs_plan) AS occurrences'))
                        ->where('subs_plan', '<>', null)
                        ->groupBy('subs_plan')
                        ->orderBy('occurrences', 'DESC')
                        ->limit(5)
                        ->get()->all();
               
        if(!empty($subscription)){
                  foreach($subscription as $k => $v){
                     $subs_plan[] = $v['subs_plan'];
                   }
             } 

        $subscription = $this->subscription->whereIn('id', $subs_plan)->orderBy('id', 'desc')->latest()->paginate(10);
        return view('subscription.index',compact('subscription','header'));
    }

    
     public function show($id) {
        $id    = filter_var($id,FILTER_VALIDATE_INT);
        if($id == false)
        {
             return abort(404);
        }
        else {
          $subscription   = Subscription::where('id',$id)->first();
          return view('subscription.show',compact('subscription'));
        }
        
    }

    public function create() {
        return view('subscription.create');
    }


    public function store(Request $request) {
       $this->userValidate($request);
        $data = $request->all();
        dd($data);
        $insClient['title']         = $data['title'];
        $insClient['text']          = $data['text'];
        $insClient['price']         = $data['price'];
        $insClient['discount']      = $data['discount'];
        $insClient['discount_codes']      = $data['discount_codes'];
        $insClient['device_at_a_time']      = $data['device_at_a_time'];
        $insClient['per_member']            = $data['per_member'];
        $insClient['auto_renewal']            = $data['auto_renewal'];
        $insClient['discount_codes']          = $data['discount_codes'];
        $insClient['plan_for']                = $data['plan_for'];
        $insClient['one_month_free_trial']   =  !empty($data['one_month_free_trial']) ? $data['one_month_free_trial'] : 0 ;

       // dd($insClient);

        $this->subscription->create($insClient);
        return redirect()->route('subscription.index')->with('success','Subscription plan created successfully.');
    }


    private function userValidate($request,$id=null){
      
        $validate['title']               = 'required|regex:/^[a-z A-Z]{3,30}$/';
        $validate['price']               = 'required|numeric';
        $validate['discount']            = 'required|numeric';
        $validate['device_at_a_time']    = 'required|numeric';
        $validate['per_member']          = 'required|numeric';
        $validate['auto_renewal']        = 'required';
        $validate['text']                = 'required';
    

        $messages = [
           'title.required'          => __('Please Enter Title'),
           'title.regex'             => __('Please Enter Valid Title'),
           'price.required'          => __('Please Enter Price'),
           'price.numeric'           => __('Please Enter Valid Price'),
           'discount.required'       => __('Please Enter Discount'),
           'discount.numeric'        => __('Please Enter Valid Discount'),
           'device_at_a_time.required'       => __('Please Select Device At A Time'),
           'device_at_a_time.numeric'        => __('Please Select Valid Device At A Time'),
           'per_member.required'             => __('Please Select Per Member'),
           'auto_renewal.required'           => __('Please Enter Auto Renewal'),
           'text.required'                   => __('Please Enter Text'),
             
        ];
        $request->validate($validate,$messages);
        
    }


     public function destroy($id) {
        $subscription = $this->subscription->where('id',$id)->get()->first();
        $subscription->delete();
        return redirect()->route('subscription.index')->with('success','Subscription deleted successfully');
    }

     public function edit($id) {
         $subscription = $this->subscription->select('*')->where('id',$id)->get()->first();
         return view('subscription.edit',compact('subscription'));
     }

     public function update(Request $request,$id) {
            $this->userValidate($request,$id);
            $data                   = $request->all();
            $insClient['title']         = $data['title'];
            $insClient['text']          = $data['text'];
            $insClient['price']         = $data['price'];
            $insClient['discount']      = $data['discount'];
            $insClient['discount_codes']      = $data['discount_codes'];
            $insClient['device_at_a_time']      = $data['device_at_a_time'];
            $insClient['per_member']            = $data['per_member'];
            $insClient['auto_renewal']            = $data['auto_renewal'];
            $insClient['discount_codes']          = $data['discount_codes'];
            $insClient['plan_for']                = $data['plan_for'];
            $insClient['one_month_free_trial']   =  !empty($data['one_month_free_trial']) ? $data['one_month_free_trial'] : 0 ;
            $this->subscription->where('id',$id)->update($insClient);
            return redirect()->route('subscription.index')->with('success','Subscription updated successfully.');
     }




}
