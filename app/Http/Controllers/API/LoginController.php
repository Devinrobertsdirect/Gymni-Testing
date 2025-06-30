<?php







namespace App\Http\Controllers\API;







use App\Http\Controllers\Controller;



use Illuminate\Http\Request;



use Illuminate\Support\Facades\Validator;



use App\Models\User;

use App\Models\VideoMode;



use App\Models\Group;

use App\Models\Posts;

use App\Models\PostBlock;

use App\Models\PostReport;

use App\Models\UserFeedback;



use Illuminate\Support\Facades\Auth;



use Illuminate\Support\Facades\Input;



use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\Log;





use Session;



use Hash;



use DB;



use OpenSSLCertificateSigningRequest;



// use Stripe;



use Twilio\Rest\Client;

use Twilio\Exceptions\TwilioException;







class LoginController extends Controller



{



  public $response                        = ['msg' => '', 'msg_type' => 'success'];



  public $requestType                     = [];



  public function __construct()



  {



    $this->user       = new User();
  }











  //   public function SendSms($receiverNumber)



  //   {



  //     require app_path() . '/twilo/vendor/autoload.php';



  //     $receiverNumber = '+91' . $receiverNumber;



  //     try {



  //       $account_sid = 'ACecf575de55c98a785cfb95286ad0d5ef';



  //       $auth_token = 'ddf4ffc3502262c97a77b11481fbf5d0';



  //       $twilio_number = '+18663688423';



  //       $client = new Client($account_sid, $auth_token);



  //       $send = $client->messages->create($receiverNumber, [



  //         'from' => $twilio_number,



  //         'body' => 'ok'



  //       ]);



  //       return true;



  //     } catch (Exception $e) {



  //       dd("Error: " . $e->getMessage());



  //     }



  //   }







  public function SendSms($receiverNumber, $otp)



  {



    // Autoload the Twilio SDK



    require_once app_path('twilo/vendor/autoload.php');







    // Prepend country code if not already included



    $receiverNumber = '+1' . $receiverNumber;







    try {



      // Twilio credentials



      $account_sid = 'ACecf575de55c98a785cfb95286ad0d5ef';



      $auth_token = 'ddf4ffc3502262c97a77b11481fbf5d0';



      $twilio_number = '+18663688423';







      // Create a Twilio client instance



      $client = new Client($account_sid, $auth_token);







      // Create the OTP message



      $message = "Your OTP is: $otp";







      // Send the SMS



      $client->messages->create($receiverNumber, [



        'from' => $twilio_number,



        'body' => $message,



      ]);







      return true;
    } catch (Exception $e) {



      // Handle the exception



      return "Error: " . $e->getMessage();
    }
  }







  //   public function login(Request $request)



  //   {



  //     //  $this->SendSms(7020966086);



  //     if (is_numeric($request->get('emailOrPhone'))) {



  //       $pass = md5($request->password);



  //       $data =      DB::select(DB::raw("SELECT * FROM `users` WHERE `phone` = $request->emailOrPhone AND `password` = " . '"' . $pass . '"'));







  //       if (count($data) == 1) {



  //         $datas['token'] =          $request->get('token');



  //         $datas['device_type']   = $request->get('device_type');



  //         //print_r($data); die;



  //         DB::table('users')->where('id', $data[0]->id)->update($datas);



  //         $check_payment = DB::table('users')->select('payment_status')->where('id', $data[0]->id)->first();



  //         $this->response['msg']              = "Login Successfully";



  //         $this->response['msg_type']         = "success";



  //         $this->response['code']             = 200;



  //         $this->response['user_id']          = $data[0]->id;



  //         $this->response['payment_status']             = $check_payment->payment_status;



  //         return response()->json($this->response);

  //       } else {



  //         $this->response['msg']              = "Wrong credentials";



  //         $this->response['msg_type']         = "failed";



  //         $this->response['code']             = 400;



  //         return response()->json($this->response);

  //       }

  //     } elseif (filter_var($request->get('emailOrPhone'), FILTER_VALIDATE_EMAIL)) {







  //       $pass = md5($request->password);



  //       $data =      DB::select(DB::raw("SELECT * FROM `users` WHERE `email` =" . '"' . $request->emailOrPhone . '"' . " AND `password` = " . '"' . $pass . '"'));







  //       if (count($data) == 1) {



  //         $datas['token'] = $request->get('token');



  //         $datas['device_type']   = $request->get('device_type');







  //         DB::table('users')->where('id', $data[0]->id)->update($datas);



  //         $check_payment = DB::table('users')->select('payment_status')->where('id', $data[0]->id)->first();



  //         $this->response['msg']              = "Login Successfully";



  //         $this->response['msg_type']         = "success";



  //         $this->response['code']             = 200;



  //         $this->response['user_id']          = $data[0]->id;



  //         $this->response['payment_status']             = $check_payment->payment_status;



  //         return response()->json($this->response);

  //       } else {



  //         $this->response['msg']              = "Wrong credentials";



  //         $this->response['msg_type']         = "failed";



  //         $this->response['code']             = 400;



  //         return response()->json($this->response);

  //       }

  //     }







  //     $this->response['msg']              = "please enter required fields";



  //     $this->response['msg_type']         = "failed";



  //     $this->response['code']             = 400;



  //     return response()->json($this->response);

  //   }


  public function login(Request $request)
  {
    if (!$request->filled('emailOrPhone') || !$request->filled('password')) {
      return response()->json(['msg' => "Please enter required fields", 'msg_type' => "failed", 'code' => 400]);
    }

    $emailOrPhone = $request->get('emailOrPhone');
    $passwordHash = md5($request->password);

    $isPhone = is_numeric($emailOrPhone);
    $column = $isPhone ? 'phone' : 'email';

    // Retrieve user
    $data = DB::table('users')->where($column, $emailOrPhone)->where('password', $passwordHash)->first();

    if (!$data) {
      return response()->json(['msg' => "Wrong credentials", 'msg_type' => "failed", 'code' => 400]);
    }

    $subscription = DB::table('tbl_subscription_purches')->where('user_id', $data->id)->first();

    $maxActiveSessions = null;

    if ($subscription) {

      switch ($subscription->type) {
        case 'freeplan':
          $maxActiveSessions = null;
          break;

        case 'MonthlyTwoUser':
          $maxActiveSessions = 2;
          break;

        case 'MonthlyFourUser':
          $maxActiveSessions = 4;
          break;

        case 'Premium_Monthly':
        case 'Premium_Annual':
        case 'Gym_Annual':
          $maxActiveSessions = 1;
          break;
      }
    }

    $activeSessionsCount = DB::table('login_histories')->where('user_id', $data->id)->count();

    if ($maxActiveSessions !== null && $activeSessionsCount >= $maxActiveSessions) {
      //$this->sendOtpRequest(new Request(['emailOrPhone' => $emailOrPhone]));

      return response()->json(['msg' => "You have reached the maximum number of active sessions. An OTP has been sent for verification.", 'msg_type' => "otp_required", 'code' => 402]);
    }

    $token = bin2hex(random_bytes(30));
    $fcmToken = $request->get('token');

    $historyData = [
      'user_id' => $data->id,
      'token' => $token,
      'device_type' => $request->get('device_type'),
      'created_at' => now(),
    ];

    DB::table('login_histories')->insert($historyData);

    DB::table('users')->where('id', $data->id)->update(['token' => $token, 'fcm_token' => $fcmToken]);

    $check_payment = DB::table('users')->select('payment_status', 'name', 'profile_status')->where('id', $data->id)->first();
    $bearerTokenResponse = $check_payment->profile_status === 'pending' ? null : $token;

    return response()->json([
      'msg' => "Login Successfully",
      'msg_type' => "success",
      'code' => 200,
      'user_id' => $data->id,
      'token' => $bearerTokenResponse,
      'name' => $check_payment->name,
      'profile_status' => $check_payment->profile_status,
      'payment_status' => $check_payment->payment_status,
    ]);
  }



  public function sendOtpRequest(Request $request)
  {

    
    $request->validate([
      'emailOrPhone' => 'required',
    ]);

    $input = $request->get('emailOrPhone');

    $user = DB::table('users')->where(function ($query) use ($input) {
      $query->where('email', $input)
        ->orWhere('phone', $input);
    })->first();

    if (!$user) {
      return response()->json(['msg' => "User not found", 'msg_type' => "failed", 'code' => 404]);
    }

    $otp = rand(100000, 999999);
    $recipient = $user->email ?: $user->phone;

    if (filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
       //$this->sendEmailOtp($recipient, $otp);
      //$done = mail($recipient, 'Verification Code', "Your OTP is: $otp. Do not share it with anyone.", array('from' => 'gymnifitnessapp@gmail.com'));
      Mail::send('emails.otp', ['otp' => $otp], function ($message) use ($recipient) {
        $message->to($recipient)
               ->from('gymnifitnessapp@gmail.com', 'Gymni Fitness')
                ->subject('Your OTP for Gymni Fitness');
      });
      #print_r($done);die;
    } else {
      $this->sendSmsOtp($recipient, $otp);
    }

    DB::table('users')->where('id', $user->id)->update(['auth_otp' => $otp]);

    return response()->json([
      'msg' => "A verification OTP has been sent.",
      'msg_type' => "success",
      'code' => 200,
    ]);
  }



  private function sendEmailOtp($email, $otp)
  {
    Mail::raw("Your OTP is: $otp", function ($message) use ($email) {
      $message->to($email)
      ->from('gymnifitnessapp@gmail.com', 'Gymni')
        ->subject('Your OTP');
    });
  }



  // public function verifyAuthOtp(Request $request)

  // {

  //     $request->validate([

  //         'emailorphone' => 'required',

  //         'otp' => 'required',

  //         'token' => 'required',

  //         'device_type' => 'nullable',

  //     ]);



  //     $input = $request->get('emailorphone');

  //     $otp = $request->get('otp');



  //     $user = DB::table('users')->where(function($query) use ($input) {

  //         $query->where('email', $input)

  //               ->orWhere('phone', $input);

  //     })->first(['id', 'auth_otp']);



  //     Log::info('Verifying OTP', ['user' => $user, 'input_otp' => $otp]);



  //     if (!$user) {

  //         Log::warning('User not found', ['input' => $input]);

  //         return response()->json([

  //             'msg' => "User not found",

  //             'msg_type' => "failed",

  //             'code' => 404

  //         ]);

  //     }



  //     // Check if the OTP is incorrect

  //     if ($otp !== $user->auth_otp) {

  //         Log::error('Invalid OTP', ['expected' => $user->auth_otp, 'provided' => $otp]);

  //         return response()->json([

  //             'msg' => "Invalid OTP",

  //             'msg_type' => "failed",

  //             'code' => 400

  //         ]);

  //     }



  //     // Clear all old login histories for the user

  //     DB::table('login_histories')->where('user_id', $user->id)->delete();



  //     // Generate a new token

  //     $token = bin2hex(random_bytes(30));



  //     // Insert new login history

  //     DB::table('login_histories')->insert([

  //         'user_id' => $user->id,

  //         'token' => $token,

  //         'device_type' => $request->get('device_type'),

  //         'created_at' => now(),

  //     ]);



  //     // Update user's token

  //     DB::table('users')->where('id', $user->id)->update(['token' => $token]);



  //     // Get user payment and profile status

  //     $checkPayment = DB::table('users')->select('payment_status', 'name', 'profile_status')->where('id', $user->id)->first();



  //     return response()->json([

  //         'msg' => "Login Successfully",

  //         'msg_type' => "success",

  //         'code' => 200,

  //         'user_id' => $user->id,

  //         'token' => $token,

  //         'name' => $checkPayment->name,

  //         'profile_status' => $checkPayment->profile_status,

  //         'payment_status' => $checkPayment->payment_status,

  //     ]);

  // }







  public function verifyAuthOtp(Request $request)
  {
    $request->validate([
      'emailOrPhone' => 'required',
      'otp' => 'required',
      'token' => 'required',
      'device_type' => 'nullable',
    ]);

    $input = $request->get('emailOrPhone');
    $otp = $request->get('otp');

    $user = DB::table('users')->where(function ($query) use ($input) {
      $query->where('email', $input)
        ->orWhere('phone', $input);
    })->first(['id', 'auth_otp']);

    Log::info('Verifying OTP', ['user' => $user, 'input_otp' => $otp]);

    if (!$user) {
      Log::warning('User not found', ['input' => $input]);

      return response()->json(['msg' => "User not found", 'msg_type' => "failed", 'code' => 404]);
    }

    if ($otp != $user->auth_otp) {
      Log::error('Invalid OTP', ['expected' => $user->auth_otp, 'provided' => $otp]);

      return response()->json(['msg' => "Invalid OTP", 'msg_type' => "failed", 'code' => 400]);
    }

    DB::table('login_histories')->where('user_id', $user->id)->delete();

    $token = bin2hex(random_bytes(30));
    $fcmToken = $request->get('token');

    DB::table('login_histories')->insert([
      'user_id' => $user->id,
      'token' => $token,
      'device_type' => $request->get('device_type'),
      'created_at' => now(),
    ]);

    // Update user's token
    DB::table('users')->where('id', $user->id)->update(['token' => $token, 'fcm_token' => $fcmToken]);

    // Get user payment and profile status
    $checkPayment = DB::table('users')->select('payment_status', 'name', 'profile_status')->where('id', $user->id)->first();

    return response()->json([
      'msg' => "Login Successfully",
      'msg_type' => "success",
      'code' => 200,
      'user_id' => $user->id,
      'token' => $token,
      'name' => $checkPayment->name,
      'profile_status' => $checkPayment->profile_status,
      'payment_status' => $checkPayment->payment_status,
    ]);
  }

  public function check_user(Request $request)
  {
    $emailOrPhone = $request->input('emailOrPhone');



    if (!empty($emailOrPhone)) {



      $otp = $this->generateOTP();



      if (is_numeric($emailOrPhone)) {



        $check         = DB::table('users')->where('phone', $request->emailOrPhone)->get();



        if (count($check) == 0) {



          //mail($request->emailOrPhone, 'Verification Code', "Your OTP Verification Code is $otp. Do not share it with anyone.", array('from' => 'gymnifitnessapp@gmail.com'));



          $message = "Your verification code is $otp";



          $this->SendSms($request->emailOrPhone, $otp);







          $this->response['msg']              = "Otp send successfully";



          $this->response['msg_type']         = "success";



          $this->response['code']             = 200;



          $this->response['OTP']          = $otp;



          $this->response['type']          = 'mobile';







          return response()->json($this->response);
        } else {



          $this->response['msg']              = "this user allready exist";



          $this->response['msg_type']         = "failed";



          $this->response['code']             = 400;



          return response()->json($this->response);
        }
      } else {



        $check         = DB::table('users')->where('email', $request->emailOrPhone)->get();



        if (count($check) == 0) {



          mail($request->emailOrPhone, 'Verification Code', "Your OTP Verification Code is $otp. Do not share it with anyone.", array('from' => 'gymnifitnessapp@gmail.com'));

          //  $this->sendOtpRequest(new Request(['emailOrPhone' => $request->emailOrPhone]));



          $this->response['msg']              = "Otp send successfully";



          $this->response['msg_type']         = "success";



          $this->response['code']             = 200;



          $this->response['OTP']          = $otp;



          $this->response['type']          = 'email';



          return response()->json($this->response);
        } else {



          $this->response['msg']              = "User Already Exist.";



          $this->response['msg_type']         = "failed";



          $this->response['code']             = 400;



          return response()->json($this->response);
        }
      }
    } else {



      $this->response['msg']              = "All input field are requred";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;



      return response()->json($this->response);
    }
  }







  public function signup(Request $request)
  {
    $data =  $request->all();
    $otp = $this->generateOTP();

    if (empty($data['name']) || empty($data['emailOrPhone']) || empty($data['password'])) {

      $this->response['msg'] = "please enter required fields";
      $this->response['msg_type'] = "failed";
      $this->response['code'] = 400;

      return response()->json($this->response);
    }

    if (is_numeric($request->get('emailOrPhone'))) {

      $pass = md5($request->password);
      $data = DB::select(DB::raw("SELECT * FROM `users` WHERE `phone` =" . '"' . $request->emailOrPhone . '"'));

      if (empty($data)) {
        $insClient['name']               = $request->get('name');
        $insClient['phone']              = $request->get('emailOrPhone');
        $insClient['password']           = $pass;
        $insClient['otp']                = $otp;
        $insClient['payment_status']     = 0;
        $insClient['subs_plan_start']    = date('Y-m-d');
        $insClient['subs_plan_end']      = date('Y-m-d', strtotime("+30 days"));
        // $insClient['token']              = $request->get('token');
        $insClient['fcm_token']          = $request->get('token');
        $insClient['device_type']        = $request->get('device_type');
        $user = $this->user->create($insClient)->id;

        $check_payment = DB::table('users')->select('payment_status')->where('id', $user)->first();

        $this->response['payment_status']   = $check_payment->payment_status;
        $this->response['msg']              = "Sign up successfully";
        $this->response['msg_type']         = "success";
        $this->response['code']             = 200;
        $this->response['user_id']          = $user;
        $this->response['otp']              = $otp;

        return response()->json($this->response);
      } else {
        $this->response['msg']              = "User already register with this phone number";
        $this->response['msg_type']         = "failed";
        $this->response['code']             = 400;

        return response()->json($this->response);
      }
    } elseif (filter_var($request->get('emailOrPhone'), FILTER_VALIDATE_EMAIL)) {
      $pass = md5($request->password);
      $data = DB::select(DB::raw("SELECT * FROM `users` WHERE `email` =" . '"' . $request->emailOrPhone . '"'));

      if (empty($data)) {
        $insClient['name']               = $request->get('name');
        $insClient['email']              = $request->get('emailOrPhone');
        $insClient['password']           = $pass;
        $insClient['otp']                = $otp;
        $insClient['subs_plan_start']    =  date('Y-m-d');
        $insClient['subs_plan_end']      = date('Y-m-d', strtotime("+30 days"));
        $insClient['payment_status']     =  0;
        // $insClient['token']              = $request->get('token');
        $insClient['fcm_token']          = $request->get('token');
        $insClient['device_type']        = $request->get('device_type');

        $user = $this->user->create($insClient)->id;

        $this->sendOtpRequest(new Request(['emailOrPhone' => $request->emailOrPhone]));

        $this->response['msg']              = "Sign up successfully";
        $this->response['msg_type']         = "success";
        $this->response['code']             = 200;
        $this->response['user_id']          = $user;
        $this->response['otp']              = $otp;

        return response()->json($this->response);
      } else {
        $this->response['msg']              = "User already register with this email";
        $this->response['msg_type']         = "failed";
        $this->response['code']             = 400;
        return response()->json($this->response);
      }
    }
    $this->response['msg']              = "please enter required fields";
    $this->response['msg_type']         = "failed";
    $this->response['code']             = 400;

    return response()->json($this->response);
  }



  // public function signup(Request $request)

  // {

  //     $data = $request->all();



  //     $otp = $this->generateOTP();



  //     // Validate required fields

  //     if (empty($data['name']) || empty($data['emailOrPhone']) || empty($data['password'])) {

  //         return response()->json([

  //             'msg' => "Please enter required fields",

  //             'msg_type' => "failed",

  //             'code' => 400

  //         ]);

  //     }



  //     // Check if the input is a phone number

  //     if (is_numeric($request->get('emailOrPhone'))) {

  //         $pass = md5($request->password);

  //         $existingUser = DB::select("SELECT * FROM `users` WHERE `phone` = ?", [$request->emailOrPhone]);



  //         if (empty($existingUser)) {

  //             // Generate a unique bearer token

  //             $userToken = bin2hex(random_bytes(30));



  //             $insClient = [

  //                 'name' => $request->get('name'),

  //                 'phone' => $request->get('emailOrPhone'),

  //                 'password' => $pass,

  //                 'otp' => $otp,

  //                 'payment_status' => 0,

  //                 'subs_plan_start' => date('Y-m-d'),

  //                 'subs_plan_end' => date('Y-m-d', strtotime("+30 days")),

  //                 'user_token' => $userToken, // Save the generated token

  //                 'device_type' => $request->get('device_type'),

  //             ];



  //             $user = $this->user->create($insClient)->id;



  //             $check_payment = DB::table('users')->select('payment_status')->where('id', $user)->first();

  //             $this->response['payment_status'] = $check_payment->payment_status;



  //             $this->response['msg'] = "Sign up successfully";

  //             $this->response['msg_type'] = "success";

  //             $this->response['code'] = 200;

  //             $this->response['user_id'] = $user;

  //             $this->response['otp'] = $otp;

  //             $this->response['user_token'] = $userToken; // Include token in the response



  //             return response()->json($this->response);

  //         } else {

  //             $this->response['msg'] = "User already registered with this phone number";

  //             $this->response['msg_type'] = "failed";

  //             $this->response['code'] = 400;



  //             return response()->json($this->response);

  //         }

  //     } elseif (filter_var($request->get('emailOrPhone'), FILTER_VALIDATE_EMAIL)) {

  //         $pass = md5($request->password);

  //         $existingUser = DB::select("SELECT * FROM `users` WHERE `email` = ?", [$request->emailOrPhone]);



  //         if (empty($existingUser)) {

  //             // Generate a unique bearer token

  //             $userToken = bin2hex(random_bytes(30));



  //             $insClient = [

  //                 'name' => $request->get('name'),

  //                 'email' => $request->get('emailOrPhone'),

  //                 'password' => $pass,

  //                 'otp' => $otp,

  //                 'subs_plan_start' => date('Y-m-d'),

  //                 'subs_plan_end' => date('Y-m-d', strtotime("+30 days")),

  //                 'payment_status' => 0,

  //                 'user_token' => $userToken, // Save the generated token

  //                 'device_type' => $request->get('device_type'),

  //             ];



  //             $user = $this->user->create($insClient)->id;



  //             mail($request->emailOrPhone, 'Verification Code', "Your OTP Verification Code is $otp. Do not share it with anyone.", ['from' => 'gymnifitnessapp@gmail.com']);



  //             $this->response['msg'] = "Sign up successfully";

  //             $this->response['msg_type'] = "success";

  //             $this->response['code'] = 200;

  //             $this->response['user_id'] = $user;

  //             $this->response['otp'] = $otp;

  //             $this->response['user_token'] = $userToken; // Include token in the response



  //             return response()->json($this->response);

  //         } else {

  //             $this->response['msg'] = "User already registered with this email";

  //             $this->response['msg_type'] = "failed";

  //             $this->response['code'] = 400;



  //             return response()->json($this->response);

  //         }

  //     }



  //     $this->response['msg'] = "Please enter required fields";

  //     $this->response['msg_type'] = "failed";

  //     $this->response['code'] = 400;



  //     return response()->json($this->response);

  // }





  //   public function social_login(Request $request)



  //   {



  //     $datass =  $request->all();







  //     $social_id = $request->input('social_id');



  //     $email =   $request->input('email');



  //     if (!empty($social_id)) {



  //       $pass  =   md5($request->password);



  //       $data  =   DB::select(DB::raw("SELECT * FROM `users` WHERE `social_login` =" . '"' . $social_id . '"'));



  //       // print_r($data); die;



  //       if (count($data) != 0) {



  //         $datas['token'] = $request->get('token');



  //         //  print_r($datas['token']); die;



  //         $datas['device_type']   = $request->get('device_type');



  //         $datas['social_login'] = $social_id;



  //         DB::table('users')->where('id', $data[0]->id)->update($datas);



  //         // $check_userlogin = data



  //         if ($data[0]->user_login == 'first') {



  //           $logintime = 'first_time';

  //         } else {



  //           $logintime = 'second_time';

  //         }



  //         $this->response['msg']              = "you have login successfully";



  //         $this->response['msg_type']         = "success";



  //         $this->response['code']             = 200;



  //         $this->response['user_id']                    = $data[0]->id;



  //         $this->response['payment_status']             = $data[0]->payment_status;



  //         $this->response['user_login']                 = $logintime;







  //         return response()->json($this->response);



  //         exit;

  //       } else {







  //         $insClient['social_login']          = $request->get('social_id');



  //         $insClient['subs_plan_start']       =  date('Y-m-d');



  //         $insClient['subs_plan_end']         = date('Y-m-d', strtotime("+30 days"));



  //         $insClient['payment_status']        =  0;



  //         $insClient['token']                 = $request->get('token');



  //         $insClient['device_type']           = $request->get('device_type');



  //         $user = $this->user->create($insClient)->id;



  //         $check_payment = DB::table('users')->select('payment_status')->where('id', $user)->first();



  //         // print_r($check_payment); die;



  //         $this->response['msg']              = "you have login successfully";



  //         $this->response['msg_type']         = "success";



  //         $this->response['code']             = 200;



  //         $this->response['user_id']             = $user;



  //         $this->response['payment_status']             = $check_payment->payment_status;



  //         $this->response['user_login']             = 'first_time';



  //         return response()->json($this->response);



  //         exit;

  //       }

  //     } else {



  //       $this->response['msg']              = "please enter required fields";



  //       $this->response['msg_type']         = "failed";



  //       $this->response['code']             = 400;



  //       return response()->json($this->response);

  //     }

  //   }

  // public function social_login(Request $request)

  // {

  //     // Validate request data

  //     $request->validate([

  //         'social_id' => 'required|string',

  //         'email' => 'required|string|email',

  //     ]);



  //     $social_id = $request->input('social_id');

  //     $token = bin2hex(random_bytes(30)); 





  //     $user = DB::table('users')->where('social_login', $social_id)->first();



  //     if ($user) {



  //         $updateData = [

  //             'token' => $token,

  //             'email' => $request->get('email'),

  //         ];





  //         DB::table('users')->where('id', $user->id)->update($updateData);





  //         DB::table('login_histories')->insert([

  //             'user_id' => $user->id,

  //             'token' => $token,

  //             'created_at' => now(),

  //         ]);





  //         $loginTime = $user->user_login === 'first' ? 'first_time' : 'second_time';



  //         return response()->json([

  //             'msg' => "You have logged in successfully",

  //             'msg_type' => "success",

  //             'code' => 200,

  //             'user_id' => $user->id,

  //             'payment_status' => $user->payment_status,

  //             'user_login' => $loginTime,

  //             'token' => $token,

  //         ]);

  //     } else {



  //         $newUserData = [

  //             'social_login' => $social_id,

  //             'email' => $request->get('email'),

  //             'subs_plan_start' => now(),

  //             'subs_plan_end' => now()->addDays(30),

  //             'payment_status' => 0,

  //             'token' => $token,

  //         ];





  //         $userId = DB::table('users')->insertGetId($newUserData);





  //         DB::table('login_histories')->insert([

  //             'user_id' => $userId,

  //             'token' => $token,

  //             'created_at' => now(),

  //         ]);



  //         return response()->json([

  //             'msg' => "You have logged in successfully",

  //             'msg_type' => "success",

  //             'code' => 200,

  //             'user_id' => $userId,

  //             'payment_status' => 0,

  //             'user_login' => 'first_time',

  //             'token' => $token,

  //         ]);

  //     }

  // }



  public function social_login(Request $request)
  {
    $request->validate([
      'social_id' => 'required|string',
      'email' => 'required|string|email',
    ]);

    $social_id = $request->input('social_id');
    $email = $request->input('email');
    $token = bin2hex(random_bytes(30));
    $fcmToken = $request->input('token');

    $user = DB::table('users')->where('email', $email)->first();

    if ($user) {
      $updateData = ['token' => $token, 'email' => $email, 'fcm_token' => $fcmToken];

      DB::table('users')->where('id', $user->id)->update($updateData);

      $subscription = DB::table('tbl_subscription_purches')->where('user_id', $user->id)->first();

      $maxActiveSessions = null;

      if ($subscription) {

        switch ($subscription->type) {
          case 'freeplan':
            $maxActiveSessions = null;
            break;

          case 'MonthlyTwoUser':
            $maxActiveSessions = 2;
            break;

          case 'MonthlyFourUser':
            $maxActiveSessions = 4;
            break;

          case 'Premium_Monthly':
          case 'Premium_Annual':
          case 'Gym_Annual':
            $maxActiveSessions = 1;
            break;
        }
      }

      $activeSessionsCount = DB::table('login_histories')->where('user_id', $user->id)->count();

      if ($maxActiveSessions !== null && $activeSessionsCount >= $maxActiveSessions) {
       // $this->sendOtpRequest(new Request(['emailOrPhone' => $email]));

        return response()->json(['msg' => "You have reached the maximum number of active sessions. An OTP has been sent for verification.", 'msg_type' => "otp_required", 'code' => 402]);
      }

      // Insert login history
      DB::table('login_histories')->insert([
        'user_id' => $user->id,
        'token' => $token,
        'created_at' => now(),
      ]);

      $loginTime = $user->user_login === 'first' ? 'first_time' : 'second_time';

      return response()->json([
        'msg' => "You have logged in successfully",
        'msg_type' => "success",
        'code' => 200,
        'user_id' => $user->id,
        'payment_status' => $user->payment_status,
        'user_login' => $loginTime,
        'token' => $token,
      ]);
    } else {

      $newUserData = [
        'social_login' => $social_id,
        'email' => $email,
        'subs_plan_start' => now(),
        'subs_plan_end' => now()->addDays(30),
        'payment_status' => 0,
        'token' => $token,
        'fcm_token' => $fcmToken
      ];

      $userId = DB::table('users')->insertGetId($newUserData);

      DB::table('login_histories')->insert([
        'user_id' => $userId,
        'token' => $token,
        'created_at' => now(),
      ]);

      return response()->json([
        'msg' => "You have logged in successfully",
        'msg_type' => "success",
        'code' => 200,
        'user_id' => $userId,
        'payment_status' => 0,
        'user_login' => 'first_time',
        'token' => $token,
      ]);
    }
  }





  public function forgotpassword(Request $request)



  {



    $data =  $request->all();



    if (empty($data['emailOrPhone'])) {



      $this->response['msg']              = "please enter required fields";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;



      return response()->json($this->response);
    }







    if (is_numeric($request->get('emailOrPhone'))) {







      $pass = md5($request->password);







      $data = User::select('id')->where('phone', $request->emailOrPhone)->get()->first();



      //  print_r($data['id']); die;



      $otp = $this->generateOTP();



      if (empty($data)) {



        $this->response['msg']              = "Failed! phone number is not registered.";



        $this->response['msg_type']         = "failed";



        $this->response['code']             = 400;



        return response()->json($this->response);
      } else {



        User::where('phone', $request->emailOrPhone)->update(['otp' => $otp]);



        $this->SendSms($request->emailOrPhone, $otp);



        // $user['otp'] = $otp;



        // $data->save();



        $this->response['msg']              = "Send OTP on mobile";



        $this->response['msg_type']         = "success";



        $this->response['code']             = 200;



        $this->response['otp']              = $otp;



        $this->response['user_id']              = $data['id'];



        return response()->json($this->response);
      }
    } elseif (filter_var($request->get('emailOrPhone'), FILTER_VALIDATE_EMAIL)) {







      $pass = md5($request->password);



      $user       = User::select('id')->where('email', $request->emailOrPhone)->get()->first();







      if (empty($user)) {







        $this->response['msg']              = "Failed! email is not registered.";



        $this->response['msg_type']         = "failed";



        $this->response['code']             = 400;



        return response()->json($this->response);
      } else {



        $otp = $this->generateOTP();



        User::where('email', $request->emailOrPhone)->update(['otp' => $otp]);



        mail($request->emailOrPhone, 'Forgot Password Verification Code', "Your OTP Verification Code is $otp. Do not share it with anyone.", array('from' => 'gymnifitnessapp@gmail.com'));



        // $user['otp'] = $otp;



        // $user->save();



        if (Mail::failures() != 0) {



          $this->response['msg']              = "Success!,Your OTP Verification Code is send to provided email. Do not share it with anyone.";



          $this->response['msg_type']         = "success";



          $this->response['code']             = 200;



          $this->response['user_id']           = $user->id;



          $this->response['otp']              = $otp;



          return response()->json($this->response);
        } else {



          $this->response['msg']              = "failed, Failed! there is some issue with email provider";



          $this->response['msg_type']         = "failed";



          $this->response['code']             = 400;



          return response()->json($this->response);
        }
      }
    }



    $this->response['msg']              = "please enter required fields";



    $this->response['msg_type']         = "failed";



    $this->response['code']             = 400;



    return response()->json($this->response);
  }



  public function resend_otp(Request $request)



  {



    $data =  $request->all();



    if (empty($data['emailOrPhone'])) {



      $this->response['msg']              = "please enter required fields";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;



      return response()->json($this->response);
    }



    if (is_numeric($request->get('emailOrPhone'))) {



      $pass = md5($request->password);



      $data = User::select('id')->where('phone', $request->emailOrPhone)->get()->first();



      $otp = $this->generateOTP();



      if (empty($data)) {



        $this->response['msg']              = "Failed! phone number is not registered.";



        $this->response['msg_type']         = "failed";



        $this->response['code']             = 400;



        return response()->json($this->response);
      } else {



        $user['otp'] = $otp;



        $data->save();



        $this->response['msg']              = "Send OTP on mobile";



        $this->response['msg_type']         = "success";



        $this->response['code']             = 200;



        $this->response['otp']              = $otp;



        return response()->json($this->response);
      }
    } elseif (filter_var($request->get('emailOrPhone'), FILTER_VALIDATE_EMAIL)) {



      $pass = md5($request->password);



      $user       = User::select('id')->where('email', $request->emailOrPhone)->get()->first();



      if (empty($user)) {



        $this->response['msg']              = "Failed! email is not registered.";



        $this->response['msg_type']         = "failed";



        $this->response['code']             = 400;



        return response()->json($this->response);
      } else {



        $otp = $this->generateOTP();



        // mail($request->emailOrPhone, 'Verification Code', "Your OTP Verification Code is $otp. Do not share it with anyone.", array('from' => 'gymnifitnessapp@gmail.com'));

        $this->sendOtpRequest(new Request(['emailOrPhone' => $request->emailOrPhone]));

        $user['otp'] = $otp;



        $user->save();



        if (Mail::failures() != 0) {



          $this->response['msg']              = "Success!,Your OTP Verification Code is send to provided email. Do not share it with anyone.";



          $this->response['msg_type']         = "success";



          $this->response['code']             = 200;



          $this->response['userid']           = $user->id;



          $this->response['otp']              = $otp;



          return response()->json($this->response);
        } else {



          $this->response['msg']              = "failed, Failed! there is some issue with email provider";



          $this->response['msg_type']         = "failed";



          $this->response['code']             = 400;



          return response()->json($this->response);
        }
      }
    }



    $this->response['msg']              = "please enter required fields";



    $this->response['msg_type']         = "failed";



    $this->response['code']             = 400;



    return response()->json($this->response);
  }











  private function generateOTP()



  {



    return random_int(100000, 999999);
  }



  //   public function create_profile(Request $request)



  //   {



  //     $data =  $request->all();



  //     if (!empty($data['user_id']) && !empty($data['dob']) && !empty($data['gender'])   && !empty($data['profile_bio'])) {



  //       if (!empty($request->file('image'))) {



  //         $file = $request->file('image');



  //         $filename = date('YmdHi') . $file->getClientOriginalName();



  //         $file->move(public_path('profile'), $filename);



  //         $profile_img = 'profile/' . $filename;



  //         // print_r($filename); die;



  //       }



  //       $datas['name'] =  $data['name'];



  //       $datas['email'] =  $data['email'];



  //       $datas['phone'] =  $data['phone'];



  //       $datas['dob'] = $data['dob'];



  //       $datas['gender'] = $data['gender'];



  //       $datas['weight'] = $data['weight'];



  //       $datas['gols'] = $data['goals'];



  //       $datas['profile_bio'] = $data['profile_bio'];



  //       $datas['user_login'] = 'second';



  //       //  print_r($datas); die;



  //       if (!empty($request->file('image'))) {



  //         $datas['profile_img'] = $profile_img;

  //       }



  //       DB::table('users')->where('id', $data['user_id'])->update($datas);



  //       $userprofile =  DB::select('select * from users where id= "' . $data['user_id'] . '"');;



  //       //print_r($userprofile);



  //       foreach ($userprofile as $row) {



  //         $datass[] = array(



  //           'user_id' =>  $row->id,



  //           'name' =>  $row->name,



  //           'email' =>  $row->email,



  //           'phone' =>  $row->phone,



  //           'dob' =>  $row->dob,



  //           'gender' =>  $row->gender,



  //           'weight' =>  $row->weight,



  //           'gols' =>  $row->gols,



  //           'profile_bio' =>  $row->profile_bio,



  //           'profile_img' =>  url('') . $row->profile_img,







  //         );

  //       }







  //       $this->response['msg']              = "profile created successfully";



  //       $this->response['msg_type']         = "success";



  //       $this->response['code']             = 200;



  //       $this->response['data']             = $datass;



  //       //  $this->response['user_id']          = $user;



  //       return response()->json($this->response);

  //     } else {



  //       $this->response['msg']              = "please enter required fields";



  //       $this->response['msg_type']         = "failed";



  //       $this->response['code']             = 400;



  //       return response()->json($this->response);

  //     }

  //   }





  // public function create_profile(Request $request)

  // {

  //     $data = $request->all();



  //     if (!empty($data['user_id']) && !empty($data['dob']) && !empty($data['gender']) && !empty($data['profile_bio'])) {

  //         $profile_img = null;



  //         if (!empty($request->file('image'))) {

  //             $file = $request->file('image');

  //             $filename = date('YmdHi') . $file->getClientOriginalName();

  //             $file->move(public_path('profile'), $filename);

  //             $profile_img = 'profile/' . $filename;

  //         }



  //         $datas = [

  //             'name' => $data['name'],

  //             'email' => $data['email'],

  //             'phone' => $data['phone'],

  //             'dob' => $data['dob'],

  //             'gender' => $data['gender'],

  //             'weight' => $data['weight'],

  //             'gols' => $data['goals'],

  //             'profile_bio' => $data['profile_bio'],

  //             'user_login' => 'second',

  //             // Add profile image if it exists

  //             'profile_img' => $profile_img,

  //         ];



  //         // Update the user's profile

  //         DB::table('users')->where('id', $data['user_id'])->update($datas);



  //         // Update profile_status to 'complete'

  //         DB::table('users')->where('id', $data['user_id'])->update(['profile_status' => 'complete']);



  //   $token = bin2hex(random_bytes(30));



  // //         // Update user's token in the database

  //         DB::table('users')->where('id', $data['user_id'])->update(['token' => $token]);





  //         // Retrieve updated user profile

  //         $userprofile = DB::table('users')->where('id', $data['user_id'])->first();



  //         if ($userprofile) {

  //             $datass = [

  //                 'user_id' => $userprofile->id,

  //                 'name' => $userprofile->name,

  //                 'email' => $userprofile->email,

  //                 'phone' => $userprofile->phone,

  //                 'dob' => $userprofile->dob,

  //                 'gender' => $userprofile->gender,

  //                 'weight' => $userprofile->weight,

  //                 'gols' => $userprofile->gols,

  //                 'profile_bio' => $userprofile->profile_bio,

  //                 'profile_img' => url($userprofile->profile_img),

  //                   'token' => $token,

  //             ];



  //             $this->response['msg'] = "Profile created successfully";

  //             $this->response['msg_type'] = "success";

  //             $this->response['code'] = 200;

  //             $this->response['data'] = $datass; // Corrected from 'code' to 'data'

  //         } else {

  //             $this->response['msg'] = "User profile not found";

  //             $this->response['msg_type'] = "failed";

  //             $this->response['code'] = 404;

  //         }



  //         return response()->json($this->response);

  //     } else {

  //         $this->response['msg'] = "Please enter required fields";

  //         $this->response['msg_type'] = "failed";

  //         $this->response['code'] = 400;

  //         return response()->json($this->response);

  //     }

  // }

  public function create_profile(Request $request)

  {

    $data = $request->all();



    if (!empty($data['user_id'])) {

      $profile_img = null;



      // Handle file upload

      if (!empty($request->file('image'))) {

        $file = $request->file('image');

        $filename = date('YmdHi') . $file->getClientOriginalName();

        $file->move(public_path('profile'), $filename);

        $profile_img = 'profile/' . $filename;
      }



      $datas = [

        'name' => $data['name'],

        'email' => $data['email'],

        // 'phone' => $data['phone'],

        'dob' => $data['dob'],

        'gender' => $data['gender'],

        'weight' => $data['weight'],

        'gols' => $data['goals'],

        'profile_bio' => $data['profile_bio'],

        'user_login' => 'second',

        'profile_img' => $profile_img,

      ];





      DB::table('users')->where('id', $data['user_id'])->update($datas);





      DB::table('users')->where('id', $data['user_id'])->update(['profile_status' => 'complete']);



      $token = bin2hex(random_bytes(30));

      DB::table('users')->where('id', $data['user_id'])->update(['token' => $token]);





      $userprofile = DB::table('users')->where('id', $data['user_id'])->first();



      if ($userprofile) {



        $datass = [

          'user_id' => $userprofile->id,

          'name' => $userprofile->name,

          'email' => $userprofile->email,

          'phone' => $userprofile->phone,

          'dob' => $userprofile->dob,

          'gender' => $userprofile->gender,

          'weight' => $userprofile->weight,

          'gols' => $userprofile->gols,

          'profile_bio' => $userprofile->profile_bio,

          'profile_img' => $userprofile->profile_img ? url($userprofile->profile_img) : null, // Set to null if no image



          'token' => $token,

        ];

        DB::table('login_histories')->updateOrInsert(

          ['user_id' => $userprofile->id],

          [

            'token' => $token,

            'device_type' => $request->get('device_type', 'web'),

            'email' => $userprofile->email,

            'created_at' => now(),

          ]

        );



        $this->response = [

          'msg' => "Profile created successfully",

          'msg_type' => "success",

          'code' => 200,

          'data' => [$datass]

        ];
      } else {

        $this->response = [

          'msg' => "User profile not found",

          'msg_type' => "failed",

          'code' => 404,

          'data' => []

        ];
      }



      return response()->json($this->response);
    } else {

      $this->response = [

        'msg' => "Please enter required fields",

        'msg_type' => "failed",

        'code' => 400,

        'data' => []

      ];

      return response()->json($this->response);
    }
  }







  public function verfiy_otp(Request $request)



  {



    $data =  $request->all();



    // print_r($data); die;



    if (empty($data['otp']) && empty($data['userid'])) {



      $this->response['msg']              = "please enter required fields";



      $this->response['status']         = "false";



      $this->response['code']             = 400;



      return response()->json($this->response);
    } else {



      $datas = User::select('*')->where('id', $request->userid)->get()->first();



      #print_r($datas['otp']); die;



      if (!empty($datas['otp'])) {

        //echo "{$datas['otp']} == {$data['otp']}";die;

        if ($datas['otp'] == $data['otp']) {



          if (empty($datas['email'])) {



            $email = '';
          } else {



            $email = $datas['email'];
          }







          if (empty($datas['phone'])) {



            $phone = '';
          } else {



            $phone = $datas['phone'];
          }







          $this->response['msg']              = "otp verify successfully";



          $this->response['status']         = "success";



          $this->response['userid']           = $data['userid'];



          $this->response['name']           = $datas['name'];



          $this->response['email']           = $email;



          $this->response['phone']           = $phone;



          $this->response['code']             = 200;



          return response()->json($this->response);
        } else {







          $this->response['msg']              = "you given OTP not matched";



          $this->response['status']         = "false";



          $this->response['code']             = 400;



          return response()->json($this->response);
        }
      } else {



        $this->response['msg']              = "this user id not found our db";



        $this->response['status']         = "false";



        $this->response['code']             = 400;



        return response()->json($this->response);
      }



      // print_r($data['otp']);



    }
  }











  public function reset_password(Request $request)



  {



    $data =  $request->all();



    // print_r($data); die;







    if (empty($data['userid']) || empty($data['new_password']) || empty($data['conf_password'])) {



      $this->response['msg']              = "please enter required fields";



      $this->response['status']         = "false";



      $this->response['code']             = 400;



      return response()->json($this->response);
    } else {



      $datas = User::select('id')->where('id', $request->userid)->get()->first();



      if (!empty($datas)) {



        //  echo "cvcvcv"; die;



        if ($data['new_password'] == $data['conf_password']) {



          $new_password['password']  = md5($data['conf_password']);







          DB::table('users')



            ->where('id', $data['userid'])



            ->update($new_password);



          $this->response['msg']              = "password cheange successfully";



          $this->response['msg_type']         = "success";



          $this->response['status']         = "true";



          $this->response['userid']           = $data['userid'];



          $this->response['code']             = 200;



          return response()->json($this->response);
        } else {



          $this->response['msg']              = "new password and confirm password not matched";



          $this->response['status']         = "false";



          $this->response['code']             = 400;



          return response()->json($this->response);
        }
      } else {



        $this->response['msg']              = "this user not register our db";



        $this->response['status']         = "false";



        $this->response['code']             = 400;



        return response()->json($this->response);
      }
    }
  }







  public function get_single_term_condetion()



  {



    $terms_condition = DB::select('select * from terms_condition');



    if (!empty($terms_condition)) {



      $this->response['msg']              = "data found successfully";



      $this->response['msg_type']         = "success";



      $this->response['data']         = $terms_condition;



      $this->response['code']             = 200;
    } else {



      $this->response['msg']              = "no data found";



      $this->response['msg_type']         = "false";



      $this->response['data']         = $terms_condition;



      $this->response['code']             = 200;
    }



    return response()->json($this->response);
  }







  public function get_multiple_term_condetion()



  {



    $terms_condition_multi = DB::select('select * from terms_condition_multi');



    if (!empty($terms_condition_multi)) {

      $this->response['code']             = 200;



      $this->response['msg']              = "data found successfully";



      $this->response['msg_type']         = "success";



      $this->response['data']         = $terms_condition_multi;
    } else {

      $this->response['code']             = 200;

      $this->response['msg']              = "no data found";



      $this->response['msg_type']         = "false";



      $this->response['data']         = $terms_condition_multi;
    }



    return response()->json($this->response);
  }











  public function get_privecy_policy()



  {



    $privacy_policy = DB::select('select * from privacy_policy');



    if (!empty($privacy_policy)) {



      $this->response['msg']              = "data found successfully";



      $this->response['msg_type']         = "success";



      $this->response['data']         = $privacy_policy;



      $this->response['code']             = 200;
    } else {



      $this->response['msg']              = "no data found";



      $this->response['msg_type']         = "false";



      $this->response['data']         = $privacy_policy;



      $this->response['code']             = 200;
    }



    return response()->json($this->response);
  }



  public function get_multiple_privecy_policy()



  {



    $privacy_policy_multi = DB::select('select * from privacy_policy_multi');



    if (!empty($privacy_policy_multi)) {



      $this->response['msg']              = "data found successfully";



      $this->response['msg_type']         = "success";



      $this->response['data']         = $privacy_policy_multi;



      $this->response['code']             = 200;
    } else {



      $this->response['msg']              = "no data found";



      $this->response['msg_type']         = "false";



      $this->response['data']         = $privacy_policy_multi;



      $this->response['code']             = 200;
    }



    return response()->json($this->response);
  }







  public function get_multiple_about()



  {



    $aboutus_multi = DB::select('select * from aboutus_multi');



    if (!empty($aboutus_multi)) {



      foreach ($aboutus_multi as $row) {



        $data[] = array(



          'image'         =>  url('') . '/' . 'aboutImage/' . $row->image,



          'title'         =>  $row->title,



          'content'       =>  $row->content,



          'created_at'    => $row->created_at







        );
      }



      $this->response['msg']              = "data found successfully";



      $this->response['msg_type']         = "success";



      $this->response['data']         = $data;



      $this->response['code']             = 200;
    } else {



      $this->response['msg']              = "no data found";



      $this->response['msg_type']         = "false";



      $this->response['data']         = $aboutus_multi;



      $this->response['code']             = 200;
    }



    return response()->json($this->response);
  }







  public function get_about()



  {



    $aboutus = DB::select('select * from aboutus');



    if (!empty($aboutus)) {



      $this->response['msg']              = "data found successfully";



      $this->response['msg_type']         = "success";



      $this->response['data']         = $aboutus;



      $this->response['code']             = 200;
    } else {



      $this->response['msg']              = "no data found";



      $this->response['msg_type']         = "false";



      $this->response['data']         = $aboutus;



      $this->response['code']             = 200;
    }



    return response()->json($this->response);
  }







  public function update_profile(Request $request)



  {







    $data =  $request->all();







    //print_r($data); die;



    if (!empty($data['user_id'])) {







      if (!empty($request->file('image'))) {



        $file = $request->file('image');



        $filename = date('YmdHi') . $file->getClientOriginalName();



        $file->move(public_path('profile'), $filename);



        $profile_img = 'profile/' . $filename;



        // print_r($filename); die;



      }



      $userprofile =  DB::select('select * from users where id= "' . $data['user_id'] . '"');



      //print_r($userprofile); die;



      $check_useremail = $userprofile[0]->email;



      if ($check_useremail == $data['email']) {



        //  echo 1; die;



        $datas['email'] =  $data['email'];
      }



      //echo $check_useremail; die;



      $datas['name'] =  $data['name'];







      #$datas['phone'] =  $data['phone'];

      $datas['phone'] = isset($data['phone']) ? $data['phone'] : null;



      #$datas['dob'] = $data['dob'];

      $datas['dob'] = isset($data['dob']) ? $data['dob'] : null;



      #$datas['gender'] = $data['gender'];

      $datas['gender'] = isset($data['gender']) ? $data['gender'] : null;



      #$datas['weight'] = $data['weight'];

      $datas['weight'] = isset($data['weight']) ? $data['weight'] : null;



      $datas['gols'] = $data['gols'];



      $datas['user_login'] = 'second';



      $datas['profile_bio'] = $data['profile_bio'];



      if (!empty($request->file('image'))) {



        $datas['profile_img'] = $profile_img;
      }



      //print_r($datas); die;



      DB::table('users')



        ->where('id', $data['user_id'])



        ->update($datas);



      $userprofile =  DB::select('select * from users where id= "' . $data['user_id'] . '"');;



      //print_r($userprofile);



      foreach ($userprofile as $row) {



        $datass[] = array(



          'user_id' =>  $row->id,



          'name' =>  $row->name,



          'email' =>  $row->email,



          'phone' =>  $row->phone,



          'dob' =>  $row->dob,



          'gender' =>  $row->gender,



          'weight' =>  $row->weight,



          'user_login' =>  $row->user_login,



          'gols' =>  $row->gols,



          'profile_bio' =>  $row->profile_bio,



          'profile_img' =>  url('') . $row->profile_img,



        );
      }



      $this->response['msg']              = "profile created  successfully";



      $this->response['msg_type']         = "success";



      $this->response['code']             = 200;



      $this->response['data']             = $datass;



      //  $this->response['user_id']          = $user;



      return response()->json($this->response);
    } else {



      $this->response['msg']              = "please enter required fields";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;



      return response()->json($this->response);
    }
  }







  //   public function get_profile(Request $request)



  //   {



  //     $user_id =   $request->get('user_id');



  //     try {



  //       if (!empty($user_id)) {



  //         $userprofile =  DB::select('select * from users where id= "' . $user_id . '"');



  //         $total_groupuser   = DB::select("SELECT * from groups where  FIND_IN_SET('$user_id', members) OR user_id = $user_id");



  //         // $total_groupuser   = DB::select("SELECT * from groups where user_id = $user_id");



  //         $total_save_workout = DB::table('dowload_video_mode')



  //           ->join('video_mode', 'video_mode.id', '=', 'dowload_video_mode.video_mode_id')



  //           ->join('demo_video', 'demo_video.id', '=', 'video_mode.workout_video_id')



  //           ->select('dowload_video_mode.*', 'video_mode.*', 'demo_video.thum_img')



  //           ->where('dowload_video_mode.user_id', $user_id)



  //           ->count();



  //         //  $total_save_workout         = DB::table('dowload_video_mode')->where('user_id', $user_id)->count();



  //         //dd($total_save_workout);



  //         foreach ($userprofile as $row) {



  //           $datass[] = array(



  //             'user_id'             =>  $row->id,



  //             'name'                =>  $row->name,



  //             'email'               =>  $row->email,



  //             'phone'               =>  $row->phone,



  //             'dob'                 =>  $row->dob,



  //             'gender'              =>  $row->gender,



  //             'weight'              =>  $row->weight,



  //             'gols'                =>  $row->gols,



  //             'profile_bio'         =>  $row->profile_bio,



  //             'profile_img'         =>  url('') . '/' . $row->profile_img,



  //             'total_group'         => count($total_groupuser),



  //             'total_save_workout'  => $total_save_workout,



  //             'payment_status' =>   $row->payment_status,







  //           );

  //         }



  //         // print_r($data); die;



  //         if (!empty($userprofile)) {



  //           $this->response['msg']              = "data found successfully";



  //           $this->response['msg_type']         = "success";



  //           $this->response['status']         = "true";



  //           $this->response['code']             = 200;



  //           $this->response['data']             = $datass;



  //           return response()->json($this->response);

  //         } else {



  //           $this->response['msg']              = "no data found";



  //           $this->response['msg_type']         = "failed";



  //           $this->response['status']         = "false";



  //           $this->response['code']             = 400;



  //           return response()->json($this->response);

  //         }

  //       } else {



  //         $this->response['msg']              = "please enter user id fields";



  //         $this->response['msg_type']         = "failed";



  //         $this->response['status']         = "false";



  //         $this->response['code']             = 400;



  //         return response()->json($this->response);

  //       }

  //     } catch (\Exception $e) {



  //       $this->response['msg']              = $e->getMessage();



  //       $this->response['msg_type']         = "failed";



  //       $this->response['code']             = 400;



  //       return response()->json($this->response);

  //     }

  //   }

  // public function get_profile(Request $request)

  // {

  //     $user_id = $request->get('user_id');

  //  $token = $request->bearerToken();

  //     try {

  //         if (!empty($user_id)) {

  //             $userprofile = DB::select('select * from users where id= ?', [$user_id]);

  //             $total_groupuser = DB::select("SELECT * from groups where FIND_IN_SET(?, members) OR user_id = ?", [$user_id, $user_id]);



  //             $total_save_workout = DB::table('dowload_video_mode')

  //                 ->join('video_mode', 'video_mode.id', '=', 'dowload_video_mode.video_mode_id')

  //                 ->join('demo_video', 'demo_video.id', '=', 'video_mode.workout_video_id')

  //                 ->select('dowload_video_mode.*', 'video_mode.*', 'demo_video.thum_img')

  //                 ->where('dowload_video_mode.user_id', $user_id)

  //                 ->count();



  //             foreach ($userprofile as $row) {

  //                 $datass[] = array(

  //                     'user_id'             => $row->id,

  //                     'name'                => $row->name,

  //                     'email'               => $row->email,

  //                     'phone'               => $row->phone,

  //                     'dob'                 => $row->dob,

  //                     'gender'              => $row->gender,

  //                     'weight'              => $row->weight,

  //                     'gols'                => $row->gols,

  //                     'profile_bio'         => $row->profile_bio,

  //                     'profile_img'         => url('') . '/' . $row->profile_img,

  //                     'total_group'         => count($total_groupuser),

  //                     'total_save_workout'  => $total_save_workout,

  //                     'payment_status'      => $row->payment_status,

  //                     'subscription_status'  => $row->subscription_status,

  //                 );

  //             }



  //             if (!empty($userprofile)) {

  //                 $this->response['msg']      = "Data found successfully";

  //                 $this->response['msg_type']  = "success";

  //                 $this->response['status']    = "true";

  //                 $this->response['code']      = 200;

  //                 $this->response['data']      = $datass;

  //                 return response()->json($this->response);

  //             } else {

  //                 $this->response['msg']      = "No data found";

  //                 $this->response['msg_type']  = "failed";

  //                 $this->response['status']    = "false";

  //                 $this->response['code']      = 400;

  //                 return response()->json($this->response);

  //             }

  //         } else {

  //             $this->response['msg']      = "Please enter user ID fields";

  //             $this->response['msg_type']  = "failed";

  //             $this->response['status']    = "false";

  //             $this->response['code']      = 400;

  //             return response()->json($this->response);

  //         }

  //     } catch (\Exception $e) {

  //         $this->response['msg']      = $e->getMessage();

  //         $this->response['msg_type']  = "failed";

  //         $this->response['code']      = 400;

  //         return response()->json($this->response);

  //     }

  // }





  public function get_profile(Request $request)

  {

    $user_id = $request->get('user_id');

    $token = $request->bearerToken();



    // Validate token (you can implement your own logic here)

    if (empty($token) || !$this->isValidToken($token, $user_id)) {

      return response()->json([

        'msg' => "Unauthorized access. Invalid token.",

        'msg_type' => "failed",

        'code' => 403

      ]);
    }



    try {

      if (!empty($user_id)) {

        $userprofile = DB::select('select * from users where id= ?', [$user_id]);

        $total_groupuser = DB::select("SELECT * from groups where FIND_IN_SET(?, members) OR user_id = ?", [$user_id, $user_id]);



        // $total_save_workout = DB::table('dowload_video_mode')

        //     ->join('video_mode', 'video_mode.id', '=', 'dowload_video_mode.video_mode_id')

        //     ->join('demo_video', 'demo_video.id', '=', 'video_mode.workout_video_id')

        //     ->select('dowload_video_mode.*', 'video_mode.*', 'demo_video.thum_img')

        //     ->where('dowload_video_mode.user_id', $user_id)

        //     ->count();



        $total_save_workout = DB::table('dowload_video_mode')

          ->join('demo_video', 'demo_video.id', '=', 'dowload_video_mode.video_mode_id')

          ->select('dowload_video_mode.*', 'video_mode.*', 'demo_video.thum_img')

          ->where('dowload_video_mode.user_id', $user_id)

          ->count();



        # echo $user_id;die;



        foreach ($userprofile as $row) {

          $datass[] = array(

            'user_id'             => $row->id,

            'name'                => $row->name,

            'email'               => $row->email,

            'phone'               => $row->phone,

            'dob'                 => $row->dob,

            'gender'              => $row->gender,

            'weight'              => $row->weight,

            'gols'                => $row->gols,

            'profile_bio'         => $row->profile_bio,

            'profile_img'         => url('') . '/' . $row->profile_img,

            'total_group'         => count($total_groupuser),

            'total_save_workout'  => $total_save_workout,

            'payment_status'      => $row->payment_status,

            'subscription_status'  => $row->subscription_status,

          );
        }



        if (!empty($userprofile)) {

          $this->response['msg']      = "Data found successfully";

          $this->response['msg_type']  = "success";

          $this->response['status']    = "true";

          $this->response['code']      = 200;

          $this->response['data']      = $datass;

          return response()->json($this->response);
        } else {

          $this->response['msg']      = "No data found";

          $this->response['msg_type']  = "failed";

          $this->response['status']    = "false";

          $this->response['code']      = 400;

          return response()->json($this->response);
        }
      } else {

        $this->response['msg']      = "Please enter user ID fields";

        $this->response['msg_type']  = "failed";

        $this->response['status']    = "false";

        $this->response['code']      = 400;

        return response()->json($this->response);
      }
    } catch (\Exception $e) {

      $this->response['msg']      = $e->getMessage();

      $this->response['msg_type']  = "failed";

      $this->response['code']      = 400;

      return response()->json($this->response);
    }
  }



  // Function to validate the token (implement your logic)

  private function isValidToken($token, $user_id)

  {

    // Check if the token exists in the database for the user

    $exists = DB::table('login_histories')

      ->where('user_id', $user_id)

      ->where('token', $token)

      ->exists();



    return $exists;
  }



  public function change_password(Request $request)



  {



    $data =  $request->all();



    if (!empty($data['userid']) && !empty($data['Old_password'] && !empty($data['new_password']) && !empty($data['confirmnew_password']))) {



      $datas = User::select('password')->where('id', $request->userid)->get()->first();



      if (!empty($datas['password'])) {



        if (md5($data['Old_password']) == $datas['password']) {



          if ($data['new_password'] == $data['confirmnew_password']) {



            $updatepass['password'] = md5($data['confirmnew_password']);



            DB::table('users')



              ->where('id', $data['userid'])



              ->update($updatepass);

            // Remove all login history entries for this user

            DB::table('login_histories')->where('token', $request->bearerToken())->delete();



            $this->response['msg']              = "password has been changed successfully";



            $this->response['msg_type']         = "success";



            $this->response['code']             = 200;



            //  $this->response['user_id']          = $user;



            return response()->json($this->response);
          } else {



            $this->response['msg']              = "new password and confirm password not matched";



            $this->response['msg_type']         = "failed";



            $this->response['status']         = "false";



            $this->response['code']             = 400;



            return response()->json($this->response);
          }
        } else {



          $this->response['msg']              = "old password not matched";



          $this->response['msg_type']         = "failed";



          $this->response['status']         = "false";



          $this->response['code']             = 400;



          return response()->json($this->response);
        }
      } else {



        $this->response['msg']              = "this user not exit or db";



        $this->response['msg_type']         = "failed";



        $this->response['status']         = "false";



        $this->response['code']             = 400;



        return response()->json($this->response);
      }
    } else {



      $this->response['msg']              = "please enter required fields";



      $this->response['msg_type']         = "failed";



      $this->response['status']         = "false";



      $this->response['code']             = 400;



      return response()->json($this->response);
    }
  }







  public  function add_goal(Request $request)



  {



    $data =  $request->all();



    if (!empty($data['user_id']) && !empty($data['goal']) && !empty($data['category']) && !empty($data['title']) && !empty($data['goal_description'])) {



      $add = DB::table('goals')->insert([



        'user_id'          =>  $request->get('user_id'),



        // 'user_id'          =>  $request->get('user_id'),



        'type'             =>  $request->get('type'),



        'goal'             =>  $request->get('goal'),



        'category'         =>  $request->get('category'),



        'title'            =>  $request->get('title'),



        'goal_description' =>  $request->get('goal_description'),



        'date'             =>  date('d-m-Y'),



        'no_of_workout'             =>  $request->get('no_of_workout')







      ]);



      $id = DB::getPdo()->lastInsertId();







      if ($add) {



        DB::table('notification')->insert([



          'user_id'        => $request->get('user_id'),



          'receiver_id'    =>  $request->get('user_id'),



          'post_id'         =>  $id,



          'title'          =>  'New Goal',



          'message'        =>  'New goal added',



          'type'           => 'goal',







        ]);



        $this->response['msg']              = "new goal created  successfully";



        $this->response['msg_type']         = "success";



        $this->response['code']             = 200;



        return response()->json($this->response);
      } else {



        $this->response['msg']              = "something is wrong";



        $this->response['msg_type']         = "fieled";



        $this->response['code']             = 200;



        return response()->json($this->response);
      }
    } else {



      $this->response['msg']              = "please enter required fields";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;



      return response()->json($this->response);
    }
  }







  public function goal_list(Request $request)



  {



    $user_id = $request->get('user_id');



    $type = trim(rtrim($request->get('type'), 'Goals'));



    // print_r($type); die;



    if (!empty($user_id)) {



      if ($type == 'Monthly') {



        $user_goal = DB::select('select * from goals where user_id= "' . $user_id . '" AND goal="' . $type . '" ORDER BY id DESC');
      } else if ($type == 'Weekly') {



        $user_goal = DB::select('select * from goals where user_id= "' . $user_id . '" AND goal="' . $type . '"ORDER BY id DESC');
      } else {



        $user_goal = DB::select('select * from goals where user_id= "' . $user_id . '"ORDER BY id DESC');
      }







      if (!empty($user_goal)) {



        $this->response['msg']              = "data found successfully";



        $this->response['msg_type']         = "success";



        $this->response['data']         = $user_goal;



        $this->response['code']             = 200;
      } else {



        $this->response['msg']              = "data found successfully";



        $this->response['msg_type']         = "success";



        $this->response['data']         = $user_goal;



        $this->response['code']             = 200;
      }
    } else {



      $this->response['msg']              = "please enter user id fields";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;
    }



    return response()->json($this->response);
  }







  public function goal_detail(Request $request)



  {



    $id = $request->get('id');



    if (!empty($id)) {



      $user_goal = DB::select('select * from goals where id= "' . $id . '"');



      $sum_step = DB::select("select sum(compleate_user_step) as total from step_count where  goal_id='" . $id . "'");



      // print_r($sum_step); die;



      if (count($user_goal) != 0) {



        foreach ($user_goal as $user_goals) {



          $data[] = array(



            'id'   => $user_goals->id,



            'user_id'   => $user_goals->user_id,



            'goal' => $user_goals->goal,



            'category'  => $user_goals->category,



            'title'  => $user_goals->title,



            'goal_description'  => $user_goals->goal_description,



            'date'   => $user_goals->date,



            'status'  => $user_goals->status,



            'completed_date'   => $user_goals->completed_date,



            'created_at'   => $user_goals->created_at,



            'type'   => $user_goals->type ? $user_goals->type : '',



            'no_of_workout'   => $user_goals->no_of_workout,



            'completed_workout'  => $user_goals->completed_workout,



            'notification_status'  => $user_goals->notification_status,



            'compleate_user_step'     => $sum_step[0]->total ? (int)$sum_step[0]->total : 0,



          );
        }



        $this->response['msg']              = "data found successfully";



        $this->response['msg_type']         = "success";



        $this->response['data']         = $data;



        $this->response['code']             = 200;
      } else {



        $this->response['msg']              = "something is wrong";



        $this->response['msg_type']         = "failed";



        $this->response['code']             = 400;
      }
    } else {



      $this->response['msg']              = "please enter goal id";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;
    }



    return response()->json($this->response);
  }















  public function search_friend(Request $request)



  {



    $query = [];



    $search_term = $request->get('search');



    if (!empty($search_term)) {



      $query = DB::table("users")->where('name', 'LIKE', '%' . $search_term . '%')->where('role', '!=', 1)->get();
    } else {



      $query = DB::table("users")->where('role', '!=', 1)->get();
    }



    // print_r($query); die;



    if (!empty($query)) {



      $this->response['msg']              = "data found successfully";



      $this->response['msg_type']         = "success";



      $this->response['data']         = $query;



      $this->response['code']             = 200;
    } else {



      $this->response['msg']              = "no found successfully";



      $this->response['msg_type']         = "false";



      $this->response['data']         = $query;



      $this->response['code']             = 200;
    }







    return response()->json($this->response);
  }







  public function search_friend_r(Request $request)



  {



    $search_term = $request->get('search');



    $user_id = $request->get('user_id');



    if (!empty($search_term)) {



      $query = DB::table("users")->where('name', 'LIKE', '%' . $search_term . '%')->where('role', '!=', 1)->get();
    } else {



      $query = DB::table("users")->where('role', '!=', 1)->get();
    }







    // print_r($query); die;



    if (count($query) != 0) {



      foreach ($query as $row) {



        $check = DB::select('select * from friendcheck where user_id= "' . $user_id . '" AND friend_id="' . $row->id . '"');



        if (!empty($check)) {



          $status = 1;
        } else {



          $status = 0;
        }



        $arraydata[] = array(



          'id' => $row->id,



          'name' => $row->name,



          'status' => $status



        );
      }







      $this->response['msg']              = "data found successfully";



      $this->response['msg_type']         = "success";



      $this->response['data']         = $arraydata;



      $this->response['code']             = 200;
    } else {



      $this->response['msg']              = "no found successfully";



      $this->response['msg_type']         = "false";



      $this->response['data']         = $query;



      $this->response['code']             = 200;
    }







    return response()->json($this->response);
  }











  public function add_checkfriend_r(Request $request)



  {



    $userId = $request->userid;



    $friendid = $request->friendid;



    $status = $request->status;



    if (!empty($userId && $friendid)) {



      if ($status == 1) {



        $add =   DB::table('friendcheck')->insert([



          'user_id'        => $request->get('userid'),



          'friend_id'       =>  $request->get('friendid'),



        ]);



        $message = 'added';
      } else {



        $add =   DB::delete('delete from friendcheck where user_id="' . $userId . '" AND friend_id="' . $friendid . '"');



        $message = 'removed';
      }



      if ($add) {



        $this->response['msg']              = $message;



        $this->response['msg_type']         = "success";



        $this->response['code']             = 200;
      } else {



        $this->response['msg']              = 'Something is wrong';



        $this->response['msg_type']         = "false";



        $this->response['code']             = 200;
      }
    } else {



      $this->response['msg']              = "All field are required";



      $this->response['msg_type']         = "false";



      $this->response['code']             = 200;
    }



    return response()->json($this->response);
  }



  public function add_challenges(Request $request)
  {
    $data =  $request->all();
    if (!empty($data['challenge_friend']) && !empty($data['user_id'])  && !empty($data['category']) && !empty($data['title']) && !empty($data['description'])) {

      $user_sendchalenge =  $request->get('challenge_friend');

      foreach ($user_sendchalenge as $key => $value) {
        $id[] = DB::table('challenges')->insertGetId(
          [
            'user_id'            =>  $request->get('user_id'),
            'goal'               =>  $request->get('goal'),
            'category'           =>  $request->get('category'),
            'title'              =>  $request->get('title'),
            'description'        =>  $request->get('description'),
            'challenge_friend'   => $value
          ]
        );
      }

      $user_che =  $request->get('challenge_friend');
      $last_inserteid = $id;
      $challenge_sender = DB::table('users')->select('name')->where('id', $data['user_id'])->first();

      if (!empty($challenge_sender->name)) {
        $sender_name = $challenge_sender->name;
      } else {
        $sender_name = '';
      }

      $users = DB::table('users')->select('token', 'device_type', 'id')->whereIn('id', $data['challenge_friend'])->get();

      if (count($users) != 0) {
        foreach ($users as $row) {
          if (!empty($row->fcm_token) && !empty($row->device_type)) {

            $device_token = $row->fcm_token;

            $sendData = array(
              'body'     => $sender_name . ' ' . 'Send you challenge.',
              'title'    => 'Send challenge',
              'sound'    => 'Default',
            );

            $this->fcmNotification($device_token, $sendData);
          }
        }
      }

      for ($i = 0; $i < count($last_inserteid); $i++) {
        $array[] = array(
          'receiver_id'    => $user_che[$i],
          'user_id'        => $request->get('user_id'),
          'title'          =>  $request->get('title'),
          'message'        =>  $request->get('description'),
          'type'           => 'challenge',
          'post_id'      => $last_inserteid[$i]
        );
      }

      DB::table('notification')->insert($array);

      $this->response['msg']              = "challenges created  successfully";
      $this->response['msg_type']         = "success";
      $this->response['code']             = 200;

      return response()->json($this->response);
    } else {
      $this->response['msg']              = "please enter required fields";
      $this->response['msg_type']         = "failed";
      $this->response['code']             = 400;

      return response()->json($this->response);
    }
  }



  public function get_user_challenges(Request $request)



  {



    $user_id = $request->get('user_id');



    //  print_r($user_id); die;



    $status = $request->get('status');



    if (!empty($user_id)) {



      // $datauser_challenge   = DB::select('SELECT * FROM `challenges` WHERE `challenge_friend` IN ("'.$user_id.'")');



      if ($status == 1) {



        $datauser_challenge =  DB::select('select * from challenges where status = 1 AND challenge_friend="' . $user_id . '" ORDER BY id DESC');
      } else if ($status == 2) {



        $datauser_challenge =  DB::select('select * from challenges where status = 2 AND challenge_friend="' . $user_id . '" ORDER BY id DESC');
      } else {







        $datauser_challenge = DB::table('challenges')->select('*')->where('challenge_friend', $user_id)->orderByDesc("id")->get();
      }







      if (count($datauser_challenge) != 0) {



        foreach ($datauser_challenge as $row) {







          $challegedata[] = array(



            'id'                      =>  $row->id,



            'user_id'                 =>  $row->user_id,



            'goal'                    =>  $row->goal ? $row->goal : '',



            'category'                =>  $row->category ? $row->category : '',



            'title'                   =>  $row->title ? $row->title : '',



            'description'             =>  $row->description ? $row->description : '',



            'challenge_friend'        =>  $row->challenge_friend ? $row->challenge_friend : '',



            'status'                  =>  $row->status,



            'read_status'             => $row->read_status ? $row->read_status : '',



            'challenge_status'             => $row->challenge_status,



            'created_at'              =>  $row->created_at ? $row->created_at : '',



          );
        }



        //  print_r($challegedata); die;



        $this->response['msg']              = "data found successfully";



        $this->response['msg_type']         = "success";



        $this->response['code']             = 200;



        $this->response['data']             = $challegedata;



        return response()->json($this->response);
      } else {



        $this->response['msg']              = "no challenges found";



        $this->response['msg_type']         = "failed";



        $this->response['code']             = 200;



        $this->response['data']             = $datauser_challenge;



        return response()->json($this->response);
      }
    } else {



      $this->response['msg']              = "please enter user id";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;



      return response()->json($this->response);
    }
  }



  public function create_post(Request $request)
  {
    $user_id         = $request->get('user_id');
    $group_id        = $request->input('group_id');
    $post            = $request->input('post');
    $post_img        = $request->file('post_img');
    $thumble_img     = $request->file('thumble_img');
    $type            = $request->input('type');
    $new_name        = '';
    $selected_type   = '';
    $new_name1       = '';

    if (!empty($post_img)) {
      $new_name = 'post_img/' . rand() . '.' . $post_img->getClientOriginalExtension();
      $extension = pathinfo($new_name, PATHINFO_EXTENSION);
      $allowed = array('gif', 'png', 'jpg', 'jpeg', 'webp');

      if (in_array($extension, $allowed)) {
        $selected_type = 'selected_img';
      } else {
        $selected_type = 'selected_video';

        if (!empty($thumble_img)) {
          $new_name1 = 'thumble_img/' . rand() . '.' . $thumble_img->getClientOriginalExtension();
          $extension = pathinfo($thumble_img, PATHINFO_EXTENSION);
          $allowed = array('gif', 'png', 'jpg', 'jpeg', 'webp');

          if (in_array($extension, $allowed)) {
            $selected_type = 'selected_img';
          } else {
            $selected_type = 'selected_video';
          }

          $thumble_img->move(public_path('thumble_img'), $new_name1);
        }
      }

      $post_img->move(public_path('post_img'), $new_name);
    }

    $add =  DB::table('post')->insert([
      'user_id'          =>   $request->get('user_id'),
      'post'             =>   $request->get('post'),
      'group_id'         =>   $request->get('group_id'),
      'post_img'         =>   $new_name,
      'selected_type'    =>   $selected_type,
      'thumble_img'      =>   $new_name1,
      'share_date_time'  =>   date("Y-m-d H:i:s"),
      'type'  =>              $request->input('type')
    ]);

    if ($add == 1) {
      if (!empty($group_id)) {

        $get_group_user = DB::table('groups')->select('group_name', 'members')->where('id', $group_id)->first();

        $get_create_postNamer = DB::table('users')->select('name')->where('id', $user_id)->first();
        $group_id = (explode(',', $get_group_user->members));
        $arr = array_diff($group_id, array($user_id));

        $users = DB::table('users')->select('id', 'token', 'device_type')->whereIn('id', $arr)->get();

        if (!empty($get_create_postNamer->name)) {
          $name  = $get_create_postNamer->name;
        } else {
          $name  = '';
        }

        if (count($users) != 0) {

          foreach ($users as $row) {
            if (!empty($row->fcm_token) && !empty($row->device_type)) {

              $device_token = $row->fcm_token;

              $sendData = array(
                'body'     =>  $name . ' ' . 'upload new post.',
                'title'    => 'New Post',
                'sound'    => 'Default',
              );

              $aa =  DB::table('notification')->insert([
                'user_id'        => $request->get('user_id'),
                'receiver_id'    =>  $row->id,
                'title'          =>  $get_group_user->group_name . ' ' . 'New post in group',
                'message'        =>  $get_group_user->group_name . ' ' . 'New post added in your group',
                'type'           => 'Post',
                //'post_id'        => $request->input('post_id')
              ]);

              $this->fcmNotification($device_token, $sendData);
            }
          }
        }
      }

      $this->response['msg']              = "post created successfully";
      $this->response['msg_type']         = "success";
      $this->response['code']             = 200;
    } else {
      $this->response['msg']              = "something is wrong";
      $this->response['msg_type']         = "failed";
      $this->response['code']             = 400;
    }

    return response()->json($this->response);
  }







  public function sendGroupNotification($group_id, $user_id)
  {
    $get_group_user = DB::table('groups')->select('group_name', 'members')->where('id', $group_id)->first();
    $get_create_postNamer = DB::table('users')->select('name')->where('id', $user_id)->first();

    $group_id = (explode(',', $get_group_user->members));

    $arr = array_diff($group_id, array($user_id));

    $users = DB::table('users')->select('id', 'token', 'device_type')->whereIn('id', $arr)->get();

    $name  = $get_create_postNamer->name;

    if (!empty($get_create_postNamer->name)) {
      $name  = $get_create_postNamer->name;
    } else {
      $name  = '';
    }

    if (count($users) != 0) {

      foreach ($users as $row) {
        if (!empty($row->fcm_token) && !empty($row->device_type)) {

          $device_token = $row->fcm_token;

          $sendData = array(
            'body'     =>  $name . ' ' . 'upload new post.',
            'title'    => 'New Post',
            'sound'    => 'Default',
          );

          $aa =  DB::table('notification')->insert([
            'user_id'        => $user_id,
            'receiver_id'    =>  $row->id,
            'title'          =>  $get_group_user->group_name . ' ' . 'New post in group',
            'message'        =>  $get_group_user->group_name . ' ' . 'New post added in your group',
            'type'           => 'Post',
          ]);

          $this->fcmNotification($device_token, $sendData);
        }
      }
    }
  }











  public function facebook_time_ago($timestamp)



  {



    // dd($timestamp);



    $time_ago = strtotime($timestamp);







    $current_time = time();



    $time_difference = $current_time - $time_ago;



    $seconds = $time_difference;







    $minutes = round($seconds / 60);



    $hours = round($seconds / 3600);



    $days = round($seconds / 86400);



    $weeks = round($seconds / 604800);



    $months = round($seconds / 2629746); // Average seconds in a month



    $years = round($seconds / 31556952); // Average seconds in a year







    if ($seconds <= 60) {



      return "Just Now";
    } else if ($minutes <= 60) {



      return $minutes == 1 ? "one minute ago" : "$minutes minutes ago";
    } else if ($hours <= 24) {



      return $hours == 1 ? "an hour ago" : "$hours hours ago";
    } else if ($days <= 7) {



      return $days == 1 ? "yesterday" : "$days days ago";
    } else if ($weeks <= 4.3) { // 4.3 weeks = 30.1 days



      return $weeks == 1 ? "a week ago" : "$weeks weeks ago";
    } else if ($months <= 12) {



      return $months == 1 ? "a month ago" : "$months months ago";
    } else {



      return $years == 1 ? "one year ago" : "$years years ago";
    }
  }















  public function get_user_uploaded_img(Request $request)



  {



    $user_id = $request->get('user_id');



    if (!empty($user_id)) {



      $post_img = DB::select('select post_img from post where user_id= "' . $user_id . '"');



      if (!empty($post_img)) {



        foreach ($post_img as $post_imgs) {



          $array[] = array(



            'post_img' => url()->current() . $post_imgs->post_img



          );
        }



        $this->response['msg']              = "data found successfully";



        $this->response['msg_type']         = "success";



        $this->response['code']             = 200;



        $this->response['data']             = $array;
      } else {



        $this->response['msg']              = "no post image found";



        $this->response['msg_type']         = "success";



        $this->response['code']             = 200;



        $this->response['data']             = $post_img;
      }
    } else {



      $this->response['msg']              = "please enter user id";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;
    }



    return response()->json($this->response);
  }







  public function get_fitness()



  {



    $fitness = DB::table('description_mode')->orderBy('category')->get();



    foreach ($fitness as $fitnesss) {



      $arra1[] = array(



        'round_description' => json_decode($fitnesss->round_description, true)



      );
    }



    foreach ($fitness as $row) {



      $data[] = array(







        'img_title'          =>  $row->img_title,



        'description'        =>  $row->description,



        'category'           =>  $row->category,



        'muscle_group'       =>  $row->muscle_group,



        'equipment'          =>  $row->equipment,



        'rating'             =>  $row->rating,



        'intensity'          =>  $row->intensity,



        'instructor'         =>  $row->instructor,



        'like'               =>  $row->like,



        'share'              =>  $row->share,



        'intensity_rating'   =>  $row->intensity_rating,



        'demo_video'         =>  $row->demo_video,



        'demo_videoid'       =>  $row->demo_videoid,



        // 'round_description' => $arra1



      );
    }



    if (!empty($data)) {



      $this->response['msg']              = "data found successfully";



      $this->response['msg_type']         = "success";



      $this->response['code']             = 200;



      $this->response['data']             = $data;
    } else {



      $this->response['msg']              = "no post image found";



      $this->response['msg_type']         = "success";



      $this->response['code']             = 200;



      $this->response['data']             = $data;
    }



    return response()->json($this->response);
  }







  public function user_like(Request $request)
  {
    $user_id = $request->input('user_id');
    $post_id = $request->input('post_id');
    $like_post = $request->input('like_post');

    $postUserId = DB::table('post')->select('user_id')->where('id', $post_id)->get();
    //$status    = $request->input('status');
    if (!empty($user_id && $post_id && $like_post)) {

      if ($like_post == 'yes') {

        $user_like_post = DB::select('select * from post_like where user_id= "' . $user_id . '" AND post_id="' . $post_id . '" AND  like_post="' . $like_post . '"');

        if (empty($user_like_post)) {
          $user_like_post = DB::select('select * from post where  id="' . $post_id . '"');

          $total_post_comment      = DB::table('post_comment')->where('post_id', $post_id)->get();

          $get_token = DB::table('users')->select('fcm_token')->where('id', $postUserId[0]->user_id)->get();

          $get_liker_name = DB::table('users')->select('name')->where('id', $user_id)->get();

          if (count($get_token) != 0) {
            $device_token = $get_token[0]->fcm_token;
            if (!empty($get_liker_name[0]->name)) {
              $name = $get_liker_name[0]->name;
            } else {
              $name = '';
            }
            $sendData = array(
              'body'     => $name . ' ' . 'Like your post',
              'title'    => 'like your post',
              'sound'    => 'Default',
            );

            $this->fcmNotification($device_token, $sendData);
          }

          $data['user_id']   = $request->input('user_id');
          $data['post_id']   = $request->input('post_id');
          $data['like_post'] = $request->input('like_post');

          DB::table('post_like')->insert($data);

          DB::table('notification')->insert([
            'user_id'        => $request->get('user_id'),
            'receiver_id'    => $user_like_post[0]->user_id,
            'title'          =>  ' like your post',
            'message'        =>  ' like your post',
            'type'           => 'like',
            'post_id'        => $request->input('post_id')
          ]);

          $like_count         = DB::table('post_like')->where('post_id', $post_id)->count();
          $datas['total_like'] = $like_count;

          $update_pro =  DB::table('post')->where('id', $post_id)->update($datas);

          $this->response['msg']              = "user like successfully";
          $this->response['msg_type']         = "success";
          $this->response['code']             = 200;
          $this->response['total_like']             = $like_count;
        } else {
          $like_count         = DB::table('post_like')->where('post_id', $post_id)->count();

          $this->response['msg']              = "you have alreday like this post";
          $this->response['msg_type']         = "success";
          $this->response['code']             = 200;
          $this->response['total_like']       = $like_count;
        }
      } else {
        DB::delete('delete from post_like where user_id="' . $user_id . '" AND post_id="' . $post_id . '"');

        $like_count         = DB::table('post_like')->where('post_id', $post_id)->count();

        $datas['total_like'] = $like_count;

        $update_pro =  DB::table('post')->where('id', $post_id)->update($datas);

        $this->response['msg']              = "user dislike successfully";
        $this->response['msg_type']         = "success";
        $this->response['code']             = 200;
        $this->response['total_like']             = $like_count;
      }
    } else {
      $this->response['msg']              = "all field is required";
      $this->response['msg_type']         = "failed";
      $this->response['code']             = 400;
    }

    return response()->json($this->response);
  }

  public function userpost_comment(Request $request)
  {
    $user_id = $request->input('user_id');
    $post_id = $request->input('post_id');
    $comment = $request->input('comment');

    if (!empty($user_id && $post_id && $comment)) {
      $data['user_id'] = $request->input('user_id');
      $data['post_id'] = $request->input('post_id');
      $data['comment'] = $request->input('comment');

      DB::table('post_comment')->insert($data);

      $user_like_post = DB::select('select * from post where  id="' . $post_id . '"');

      if (count($user_like_post) == 0) {
        $receiver_id =  0;
      } else {
        $receiver_id =  $user_like_post[0]->user_id;
      }

      $get_token = DB::table('users')->select('fcm_token')->where('id', $user_like_post[0]->user_id)->get();

      $get_liker_name = DB::table('users')->select('name')->where('id', $user_id)->get();

      if (count($get_token) != 0) {
        $device_token = $get_token[0]->fcm_token;
        $sendData = array(
          'body'     => $get_liker_name[0]->name ?  $get_liker_name[0]->name : '' . 'commented on post',
          'title'    => 'comment your post',
          'sound'    => 'Default',
        );

        $this->fcmNotification($device_token, $sendData);
      }

      DB::table('notification')->insert([
        'user_id'        => $request->get('user_id'),
        'receiver_id'    => $receiver_id,
        'title'          =>  ' commented on post',
        'message'        =>  $request->input('comment'),
        'type'           => 'comment',
        'post_id'        => $request->input('post_id')
      ]);

      $comment_count      = DB::table('post_comment')->where('post_id', $post_id)->count();
      $total_post_comment      = DB::table('post_comment')->where('post_id', $post_id)->get();

      $data1 = DB::table('post_comment')
        ->join('users', 'users.id', '=', 'post_comment.user_id')
        ->select('post_comment.*', 'users.name', 'users.profile_img')
        ->orderBy('post_comment.id', 'ASC')
        ->where('post_comment.post_id', $post_id)
        ->get();

      foreach ($data1 as $row) {
        $comments[] = array(
          'comment' => $row->comment,
          'name'   => $row->name,
          'profile_img' => $row->profile_img ? url('') . '/' . $row->profile_img : '',
          'time'             => $this->facebook_time_ago($row->create_at)
        );
      }

      $this->response['msg']              = "comment  created successfully";
      $this->response['msg_type']         = "success";
      $this->response['code']             = 200;
      $this->response['total_comment']             = $comment_count;
      $this->response['data']             = $comments;
    } else {
      $this->response['msg']              = "all field is required";
      $this->response['msg_type']         = "failed";
      $this->response['code']             = 400;
    }

    return response()->json($this->response);
  }

  public function get_comment(Request $request)
  {
    $post_id = $request->input('post_id');



    //echo $post_id; die;



    $comment = array();



    if (!empty($post_id)) {



      $count = DB::table('post_comment')->where('post_id', $post_id)->count();



      $data = DB::table('post_comment')



        ->join('users', 'users.id', '=', 'post_comment.user_id')



        ->select('post_comment.*', 'users.name', 'users.profile_img')



        ->orderBy('post_comment.id', 'desc')



        ->where('post_comment.post_id', $post_id)



        ->get();







      foreach ($data as $row) {



        $comment[] = array(



          'comment' => $row->comment,



          'name'   => $row->name,



          'profile_img' => $row->profile_img ? url('') . '/' . $row->profile_img : '',



          'time'             => $this->facebook_time_ago($row->create_at),



        );
      }







      //    print_r($data); die;



      if (count($comment) != 0) {



        $this->response['msg']              = "comment found successfully";



        $this->response['msg_type']         = "success";



        $this->response['data']         = $comment;



        $this->response['total_comment']         = $count;



        $this->response['code']             = 200;
      } else {



        $this->response['msg']              = "no comment found";



        $this->response['msg_type']         = "false";







        $this->response['code']             = 200;
      }
    } else {



      $this->response['msg']              = "please enter post id";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;
    }



    return response()->json($this->response);
  }







  public function get_group(Request $request)



  {



    $user_id = $request->input('user_id');



    $search = $request->input('search');



    try {



      if (!empty($user_id)) {



        if (!empty($search)) {



          $total_groupuser   = DB::select("SELECT * from groups where group_name Like '%$search%' AND  user_id = $user_id");
        } else {



          $total_groupuser   = DB::select("SELECT * from groups where  FIND_IN_SET('$user_id', members) OR user_id = $user_id");



          //$total_groupuser   = DB::select("SELECT * from groups where user_id = $user_id");



        }



        if (!empty($total_groupuser)) {



          foreach ($total_groupuser as $row) {



            $createdUser   = DB::select("SELECT * from users where   id = $user_id");



            $data[] = array(







              'image' =>   url('') . '/group/' . $row->image,



              'group_name' => $row->group_name,



              'group_description' => $row->group_description,



              'created_by' => $createdUser[0]->name,



              'group_id' => $row->id,



              'user_id' => $row->user_id,







            );
          }



          $this->response['msg']              = "group found successfully";



          $this->response['msg_type']         = "success";



          $this->response['data']         = $data;



          $this->response['code']             = 200;
        } else {



          $this->response['msg']              = "no group found successfully";



          $this->response['msg_type']         = "success";



          $this->response['data']         = $total_groupuser;



          $this->response['code']             = 200;
        }
      } else {



        $this->response['msg']              = "please enter user id";



        $this->response['msg_type']         = "failed";



        $this->response['code']             = 400;
      }



      return response()->json($this->response);
    } catch (\Exception $e) {



      $this->response['msg']              = $e->getMessage();



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;







      return response()->json($this->response);
    }
  }







  public function delete_group(Request $request)



  {



    $user_id = $request->input('user_id');



    $group_id = $request->input('group_id');



    if (!empty($user_id && $group_id)) {



      $getGroup         = DB::table('groups')->where('user_id', $user_id)->where('id', $group_id)->first();



      if (!empty($getGroup->image)) {



        unlink('public/group/' . $getGroup->image);
      }



      $delete =  DB::delete('delete from groups where user_id="' . $user_id . '" AND id="' . $group_id . '"');



      if ($delete) {



        $this->response['msg']              = "Group Deleted Successfully";



        $this->response['msg_type']         = "success";



        $this->response['code']             = 200;
      } else {



        $this->response['msg']              = "Something is wrong try again";



        $this->response['msg_type']         = "failed";



        $this->response['code']             = 400;
      }
    } else {



      $this->response['msg']              = "please enter user id";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;
    }



    return response()->json($this->response);
  }







  public function filterFunc($item)



  {



    global $filters;



    foreach ($filters as $key => $value) {



      if ($item[$key] != $value) {



        return false;
      }
    }



    return true;
  }







  public function getall_post(Request $request)

  {



    try {



      $type = $request->input('type');



      $groupId = $request->input('group_id');



      $user_id = $request->input('user_id');



      if (empty($user_id)) {

        $auth_id = auth()->id();

        $blockedUsers = DB::table('post_block')

          ->where('block_by', $auth_id)

          ->pluck('block_user_id')

          ->toArray();



        $reportedPosts = DB::table('post_reports')->where('reported_by', $auth_id)->pluck('post_id')->toArray();
      } else {

        $blockedUsers = DB::table('post_block')->where('block_by', $user_id)->pluck('block_user_id')->toArray();

        $reportedPosts = DB::table('post_reports')->where('reported_by', $user_id)->pluck('post_id')->toArray();
      }











      $createrName = '';

      //echo $user_id;die;





      $data = DB::table('post')



        ->join('users', 'users.id', '=', 'post.user_id')



        ->leftJoin('post_like', 'post_like.post_id', '=', 'post.id')

        ->whereNotIn('post.user_id', $blockedUsers)

        ->whereNotIn('post.id', $reportedPosts)

        ->leftJoin('groups', 'groups.id', '=', 'post.group_id');







      if ($groupId !== 'All Post' && !empty($groupId)) {



        $data = $data->where('post.group_id', $groupId);



        $groupData = DB::table('groups')->select('user_id')->where('id', $groupId)->first();







        if (!empty($groupData->user_id)) {



          $groupCreator = DB::table('users')->select('name')->where('id', $groupData->user_id)->first();



          $createrName = $groupCreator->name ?? '';
        }
      } elseif ($groupId == 'All Post') {



        $data = $data->whereNull('post.group_id');
      }







      $data = $data->select(



        'post.*',



        'groups.group_name',



        'groups.image',



        'post.thumble_img',



        'users.name',



        'users.profile_img',



        DB::raw("count(post_like.id) as total_like")



      )



        ->groupBy('post.id')



        ->where('post.status', 1)

        ->whereNotIn('post.user_id', $blockedUsers)

        ->whereNotIn('post.id', $reportedPosts)

        ->whereNull('post.group_type');







      if ($type == 'Latest') {



        $data = $data->orderBy('post.id', 'desc');
      } elseif ($type == 'Trending') {



        $data = $data->orderBy('total_like', 'desc');
      } else {



        $data = $data->orderBy('post.share_date_time', 'desc');
      }







      $data = $data->get();







      $removedPosts = DB::table('remove_user_post')->select('post_id')->where('user_id', $user_id)->pluck('post_id')->toArray();







      $user_goal = [];

      #print_r($data);die;

      foreach ($data as $row) {



        if (!in_array($row->id, $removedPosts)) {



          $userids = explode(",", $row->shared_user);



          $getsharedUser = DB::table('users')->select('name')->whereIn('id', $userids)->get();



          $getshareUser = DB::table('users')->select('name')->where('id', $row->share_by)->first();







          $like_user = DB::table('post_like')->where('user_id', $user_id)->where('post_id', $row->id)->first();



          $share_user = DB::table('post_share')->where('post_id', $row->id)->count();



          $like = $like_user ? 'yes' : 'no';



          $like_id = $like_user->id ?? null;



          $like_count = DB::table('post_like')->where('post_id', $row->id)->count();



          $comment_count = DB::table('post_comment')->where('post_id', $row->id)->count();







          $post_data = $row->selected_type == 'url' ? $row->post_img : ($row->post_img ? url($row->post_img) : '');



          $thumble_img = $row->thumble_img ? url($row->thumble_img) : '';







          $user_goal[] = [



            'post_img' => $post_data,



            'profile_img' => $row->profile_img ? url($row->profile_img) : '',



            'name' => $row->name,



            'post' => $row->post,



            'thumble_img' => $thumble_img,



            'selected_type' => $row->selected_type,



            'user_id' => $row->user_id,



            'id' => $row->id,



            'time' => \Carbon\Carbon::createFromTimeStamp(strtotime($row->created_at))->diffForHumans(),



            //$this->facebook_time_ago($row->created_at),



            'total_comment' => $comment_count,



            'total_like' => $row->total_like,



            'user_like' => $like,



            'like_id' => $like_id,



            'total_share' => $share_user,



            'group_id' => $row->group_id ?? '',



            'group_name' => $row->group_name ?? '',



            'group_image' => $row->image ? url('group/' . $row->image) : '',



            'created_by' => $createrName,



            'caption' => $row->caption ?? '',



            'shared_user' => $getsharedUser ?? '',



            'share_by' => $getshareUser->name ?? ''



          ];
        }
      }







      if (!empty($user_goal)) {



        return response()->json([



          'msg' => 'data found successfully',



          'msg_type' => 'success',



          'code' => 200,



          'data' => $user_goal



        ]);
      } else {



        return response()->json([



          'msg' => 'no data found',



          'msg_type' => 'failed',



          'code' => 400



        ]);
      }
    } catch (\Exception $e) {



      return response()->json([



        'msg' => $e->getMessage(),



        'msg_type' => 'failed',



        'code' => 400



      ]);
    }
  }







  public function get_user_post_image(Request $request)



  {



    //echo 1; die;



    $user_id = $request->input('user_id');



    $post_img = DB::select('select post_img from post where user_id= "' . $user_id . '" AND selected_type="selected_img"');



    //print_r($post_img); die;



    if (count($post_img) != 0) {



      $allowed = array('gif', 'png', 'jpg', 'jpeg');







      foreach ($post_img as $row) {



        $extension = pathinfo($row->post_img, PATHINFO_EXTENSION);



        if (in_array($extension, $allowed)) {



          $data[] = array(







            'user_post_img'           => url('') . '/' . $row->post_img



          );
        }
      }



      $this->response['msg']              = "data found successfully";



      $this->response['msg_type']         = "success";



      $this->response['code']             = 200;



      $this->response['data']             = $data;
    } else {



      $this->response['msg']              = "no data found";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;
    }



    // $extension = pathinfo($post_img, PATHINFO_EXTENSION);



    return response()->json($this->response);
  }







  public function notification(Request $request)



  {



    $user_id = $request->input('user_id');



    if (!empty($user_id)) {



      //  $get_notification         = DB::table('notification')->where('receiver_id', $user_id)->get();







      $get_notification = DB::table('notification')



        ->join('users', 'users.id', '=', 'notification.user_id')



        ->select('notification.id', 'notification.user_id', 'notification.post_id', 'notification.msg_read', 'notification.receiver_id', 'notification.message', 'notification.title', 'notification.type', 'notification.status', 'users.name', 'users.profile_img')



        ->where('notification.receiver_id', $user_id)



        ->where('notification.status', 1)



        ->orderBy('notification.id', 'desc')



        ->get();



      // print_r($get_notification); die;



      if (count($get_notification) != 0) {



        foreach ($get_notification as $row) {



          //print_r($row);



          $notifys[] = array(



            'user_id'           => $row->user_id,



            'message'           => $row->message,



            'type'              => $row->type,



            'title'             => $row->title,



            'sender_name'       => $row->name,



            'notification_id'   => $row->id,



            'post_id'           => $row->post_id,



            'receiver_id'       => $row->receiver_id,



            'msg_read'          => $row->msg_read,



          );
        }



        //print_r($notifys); 



        // die;







        $this->response['msg']              = "notification found";



        $this->response['msg_type']         = "success";



        $this->response['code']             = 200;



        $this->response['data']             = $notifys;
      } else {



        $this->response['msg']              = "no notification found";



        $this->response['msg_type']         = "failed";



        $this->response['code']             = 200;
      }
    } else {



      $this->response['msg']              = "please enter user id";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;
    }



    return response()->json($this->response);
  }



  public function get_group_post(Request $request)



  {



    $group_id = $request->input('group_id');



    $user_id = $request->input('user_id');



    if (!empty($group_id)) {



      if (empty($user_id)) {

        $auth_id = auth()->id();

        $blockedUsers = DB::table('post_block')

          ->where('block_by', $auth_id)

          ->pluck('block_user_id')

          ->toArray();



        $reportedPosts = DB::table('post_reports')->where('reported_by', $auth_id)->pluck('post_id')->toArray();
      } else {

        $blockedUsers = DB::table('post_block')->where('block_by', $user_id)->pluck('block_user_id')->toArray();

        $reportedPosts = DB::table('post_reports')->where('reported_by', $user_id)->pluck('post_id')->toArray();
      }



      $data = DB::table('post')



        ->join('users', 'users.id', '=', 'post.user_id')



        ->select('post.*', 'users.name', 'users.profile_img')



        ->orderBy('post.id', 'desc')



        ->where('post.group_id', $group_id)

        ->whereNotIn('post.user_id', $blockedUsers)

        ->whereNotIn('post.id', $reportedPosts)



        ->where('post.status', 1)



        ->get();



      if (count($data) != 0) {



        $remove = DB::table('remove_user_post')->select('post_id')->where('user_id', $user_id)->get();



        //  $array = array_values($remove);



        $i = 1;



        $array1 = array();



        if (count($remove) != 0) {



          foreach ($remove as $row1) {







            $array1[] = array(



              'post_id' => $row1->post_id



            );
          }
        }



        $numerical = array();



        $sep = ':';







        foreach ($array1 as $k => $v) {



          $numerical[] = $v['post_id'];
        }



        //print_r($data); die;



        $user_goal = [];



        $getshareUser = [];



        foreach ($data as $row) {



          if (!in_array($row->id, $numerical)) {



            $like_user = DB::select('select id from post_like where user_id= "' . $user_id . '" AND post_id="' . $row->id . '"');



            $share_user = DB::select('select id from post_share where post_id="' . $row->id . '"');



            if (!empty($row->shared_user)) {



              $userids = explode(",", $row->shared_user);



              $getshareUser = DB::table('users')->select('name')->whereIn('id', $userids)->get();
            }



            $getshareUserby = DB::table('users')->select('name')->where('id', $row->share_by)->first();







            $total_share = count($share_user);



            if (empty($like_user)) {



              $like = 'no';



              $like_id = null;
            } else {



              $like = 'yes';



              $like_id = $like_user[0]->id;
            }







            if ($row->selected_type == 'url') {



              $post_data = $row->post_img;



              $thumble_img = $row->thumble_img;
            } else {



              $post_data = $row->post_img ? url('') . '/' . $row->post_img : '';



              $thumble_img = $row->thumble_img ? url('') . '/' . $row->thumble_img : '';
            }







            $like_count         = DB::table('post_like')->where('post_id', $row->id)->count();



            $comment_count      = DB::table('post_comment')->where('post_id', $row->id)->count();



            $user_goal[] = array(



              'post_img'              => $post_data, //$row->post_img ? url('') . '/' . $row->post_img : '',



              'name'                  =>  $row->name,



              'post'                  => $row->post,



              'user_id'               => $row->user_id,



              'id'                    =>  $row->id,



              'selected_type'         =>  $row->selected_type,



              'time'                  => $this->facebook_time_ago($row->created_at),



              'total_comment'         => $comment_count,



              'total_like'            => $like_count,



              'user_like'             => $like,



              'like_id'               => $like_id,



              'total_share'           =>  $total_share,



              'profile_img'           =>  $row->profile_img ?  url('')  . '/' . $row->profile_img : '',



              'thumble_img'           => $thumble_img, //$row->thumble_img ?  url('')  . '/' . $row->thumble_img : '',



              'caption'               => $row->caption ? $row->caption : '',



              'shared_user'              => $getshareUser ? $getshareUser : [],



              'share_by'         => $getshareUserby ? $getshareUserby->name : ''











            );
          }
        }



        $this->response['msg']              = "data found successfully";



        $this->response['msg_type']         = "success";



        $this->response['code']             = 200;



        $this->response['data']             = $user_goal;
      } else {



        $this->response['msg']              = "no data found this group";



        $this->response['msg_type']         = "success";



        $this->response['code']             = 200;
      }
    } else {



      $this->response['msg']              = "please enter group id";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;
    }



    return response()->json($this->response);
  }







  public function get_group_user(Request $request)



  {



    $group_id = $request->input('group_id');



    if (!empty($group_id)) {



      $group =  DB::select('select * from groups where id= "' . $group_id . '"');



      $groupuser = array();



      if ($group[0]->members != '') {



        $userid =  (explode(",", $group[0]->members));



        // print_r($userid);



        foreach ($userid as $user_id) {



          $userimage =  DB::select('select * from users where id= "' . $user_id . '"');



          foreach ($userimage as $row) {



            $groupuser[] = array(



              'user_image'     => $row->profile_img ? url('') . '/' . $row->profile_img : '',



              'user_id'        => $row->id,



              'name'           => $row->name ? $row->name : '',



            );
          }
        }



        $this->response['msg']              = " user found this group";



        $this->response['msg_type']         = "success";



        $this->response['code']             = 200;



        $this->response['data']             = $groupuser;
      } else {



        $this->response['msg']              = "no user found this group";



        $this->response['msg_type']         = "success";



        $this->response['code']             = 200;
      }
    } else {



      $this->response['msg']              = "please enter group id";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;
    }



    return response()->json($this->response);
  }



  public function get_user_total_group(Request $request)



  {



    // print_r($_POST); die; 



    $user_id = $request->input('user_id');



    if (!empty($user_id)) {



      $total_groupuser   = DB::select("SELECT * from groups where  FIND_IN_SET('$user_id', members)");



      if (!empty($total_groupuser)) {



        $this->response['msg']              = "user found in group";



        $this->response['msg_type']         = "success";



        $this->response['code']             = 200;



        $this->response['total_group']             = count($total_groupuser);
      } else {



        $this->response['msg']              = "user not found in group";



        $this->response['msg_type']         = "success";



        $this->response['code']             = 200;



        $this->response['total_group']             = count($total_groupuser);
      }
    } else {



      $this->response['msg']              = "please enter user id";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;
    }



    return response()->json($this->response);
  }







  public function upload_user_profile(Request $request)



  {



    $user_id = $request->input('user_id');



    $profile_img = $request->file('profile_img');



    if (!empty($user_id && $profile_img)) {



      $userprofile =  DB::select('select profile_img from users where id= "' . $user_id . '"');



      //   if (!empty($userprofile[0]->profile_img)) {



      //     unlink('public/' . $userprofile[0]->profile_img);



      //   }



      $filename = date('YmdHi') . $profile_img->getClientOriginalName();



      $profile_img->move(public_path('profile'), $filename);



      $profile_img = 'profile/' . $filename;



      $datas['profile_img'] = $profile_img;



      $update_pro =  DB::table('users')



        ->where('id', $user_id)



        ->update($datas);



      if ($update_pro) {



        $profiledata =  DB::select('select profile_img from users where id= "' . $user_id . '"');



        $this->response['profile_img'] = $profiledata[0]->profile_img ?  url('')  . '/' . $profiledata[0]->profile_img : '';



        $this->response['msg']              = "profile updated successfully";



        $this->response['msg_type']         = "success";



        $this->response['code']             = 200;
      } else {



        $this->response['msg']              = "something is wrong";



        $this->response['msg_type']         = "false";



        $this->response['code']             = 400;
      }
    } else {



      $this->response['msg']              = "All input fields are required";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;
    }



    return response()->json($this->response);
  }



  public function get_subscription(Request $request)



  {



    $user_id = $request->input('user_id');



    $subscription =  DB::select('select * from subscription_plan');



    if (count($subscription) != 0) {



      $check_sub         = DB::table('users')->where('id', $user_id)->get();



      //  print_r($check_sub[0]->payment_status); die;







      foreach ($subscription as $row) {



        $subscriptiondata[] = array(



          'id'                      => $row->id,



          'title'                   => $row->title,



          'text'                    => $row->text,



          'price'                   => $row->price,



          'discount'                => $row->discount,



          'device_at_a_time'        => $row->device_at_a_time,



          'per_member'              => $row->per_member,



          'auto_renewal'            => $row->auto_renewal,



          'discount_codes'          => $row->discount_codes,



          'one_month_free_trial'    => $row->one_month_free_trial,



          'plan_for'                => $row->plan_for,



          'created_at'              => $row->created_at,



          'updated_at'              => $row->updated_at,



          'deleted_at'              => $row->deleted_at,











        );
      }



      $payment_status          = $check_sub[0]->payment_status;



      $this->response['msg']              = "data found successfully";



      $this->response['msg_type']         = "success";



      $this->response['code']             = 200;



      $this->response['data']             = $subscriptiondata;



      $this->response['payment_status']             = $check_sub[0]->payment_status;
    } else {



      $this->response['msg']              = "no data found";



      $this->response['msg_type']         = "false";



      $this->response['code']             = 400;
    }



    return response()->json($this->response);
  }


  public function share_post(Request $request)
  {
    $user_id = $request->input('user_id');
    $post_id = $request->input('post_id');
    $share_by = $request->input('share_by');
    $shared_user = $request->input('shared_user');
    $type = $request->input('type');

    try {
      if (!empty($user_id && $post_id)) {
        $user_like_post = DB::select('select * from post_share where user_id= "' . $user_id . '" AND post_id="' . $post_id . '"');

        $sharepost =   DB::table('post_share')->insert([
          'user_id'        => $request->get('user_id'),
          'post_id'        => $post_id,
          'share_status'   => 1,
          'shared_user'    => $shared_user ?  implode(",", $shared_user) : '',
        ]);

        $share_count         = DB::table('post_share')->where('post_id', $post_id)->count();

        if ($sharepost) {

          if (!empty($shared_user)) {
            $users = DB::table('users')->whereIn('id', $shared_user)->get();
          }

          if (count($users) != 0) {

            foreach ($users as $row) {
              if (!empty($row->fcm_token) && !empty($row->device_type)) {
                $sendData = array(
                  'body'     => 'Shared new post',
                  'title'    => 'Share Post',
                  'sound'    => 'Default',
                );

                $this->fcmNotification($row->fcm_token, $sendData);
              }
            }
          }

          $datas['share_date_time'] = date("Y-m-d H:i:s");
          $datas['shared_user'] = $shared_user ?  implode(",", $shared_user) : '';
          $datas['share_by'] = $share_by;
          $datas['type'] = $type;
          $update_pro =  DB::table('post')->where('id', $post_id)->update($datas);

          $this->response['msg']              = "post share successfully";
          $this->response['msg_type']         = "success";
          $this->response['code']             = 200;
          $this->response['total_share']      = $share_count;
        } else {
          $this->response['msg']              = "something is wrong";
          $this->response['msg_type']         = "false";
          $this->response['code']             = 400;
        }
      } else {
        $this->response['msg']              = "All input fields are required";
        $this->response['msg_type']         = "failed";
        $this->response['code']             = 400;
      }

      return response()->json($this->response);
    } catch (\Exception $e) {

      $this->response['msg']              = "post share successfully";
      $this->response['msg_type']         = "success";
      $this->response['code']             = 200;
      $this->response['total_share']      = $share_count;

      return response()->json($this->response);
    }
  }


  public function exit_group(Request $request)
  {
    $user_id = $request->input('user_id');



    $group_id = $request->input('group_id');



    if (!empty($user_id && $group_id)) {



      $user_ingroup = DB::select('select members from groups where id= "' . $group_id . '"');



      if (!empty($user_ingroup)) {



        $arr = explode(",", $user_ingroup[0]->members);



        $key = array_search($user_id, $arr, true);



        if ($key !== false) {



          unset($arr[$key]);
        }



        $data['members'] =  implode(",", $arr);



        $removegroup =  DB::table('groups')



          ->where('id', $group_id)



          ->update($data);



        if ($removegroup) {



          $this->response['msg']              = "you have removed successfully this group";



          $this->response['msg_type']         = "success";



          $this->response['code']             = 200;



          return response()->json($this->response);



          exit;
        } else {



          $this->response['msg']              = "something is wrong";



          $this->response['msg_type']         = "false";



          $this->response['code']             = 400;



          return response()->json($this->response);



          exit;
        }
      }
    } else {



      $this->response['msg']              = "All input fields are required";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;
    }



    return response()->json($this->response);



    exit;
  }



  public function clear_notification(Request $request)



  {



    $notifiaction_id =  $request->input('notification_id');



    if (!empty($notifiaction_id)) {



      $data['status'] = 0;



      $update =   DB::table('notification')



        ->where('id', $notifiaction_id)



        ->update($data);



      if ($update) {



        $this->response['msg']              = "notification clear successfully";



        $this->response['msg_type']         = "success";



        $this->response['code']             = 200;
      } else {



        $this->response['msg']              = "something is wrong";



        $this->response['msg_type']         = "false";



        $this->response['code']             = 400;
      }
    } else {



      $this->response['msg']              = "please enter notification id";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;
    }



    return response()->json($this->response);
  }







  public function clear_all_notification(Request $request)



  {



    $reciver_id =  $request->input('receiver_id');



    if (!empty($reciver_id)) {



      $data['status'] = 0;



      $update =   DB::table('notification')



        ->where('receiver_id', $reciver_id)



        ->update($data);



      if ($update) {



        $this->response['msg']              = "all notification clear successfully";



        $this->response['msg_type']         = "success";



        $this->response['code']             = 200;
      } else {



        $this->response['msg']              = "something is wrong";



        $this->response['msg_type']         = "false";



        $this->response['code']             = 400;
      }
    } else {



      $this->response['msg']              = "please enter receiver id";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;
    }



    return response()->json($this->response);
  }







  //   public function get_all_fitness(Request $request)



  //   {



  //     try {



  //       $user_id      =     $request->input('user_id');



  //       $category      =     $request->input('category');



  //       // $get_all_fitness =  DB::select('select * from video_mode Order By category');



  //       $get_all_fitness = DB::table('video_mode')



  //         ->join('demo_video', 'demo_video.id', '=', 'video_mode.workout_video_id')



  //         // ->Leftjoin('demo_video', 'demo_video.id', '=', 'video_mode.workout_video_id')



  //         //->where('video_mode.status', 1)



  //         ->Where('video_mode.category', $category)



  //         ->orderBy('video_mode.id', 'DESC')



  //         ->select('video_mode.*', 'demo_video.url', 'demo_video.thum_img', 'demo_video.costum_thumImg')







  //         ->get();



  //       //  dd($get_all_fitness); die;



  //       if (count($get_all_fitness) != 0) {



  //         foreach ($get_all_fitness as $row) {



  //           $get_all_fitness1 =  DB::select('select * from preferences where user_id="' . $user_id . '" AND fitness_id = "' . $row->id . '"');



  //           //print_r($get_all_fitness1);



  //           if (empty($get_all_fitness1)) {



  //             $add_in = 'no';

  //           } else {



  //             $add_in = 'yes';

  //           }











  //           $fitness[] = array(



  //             'video_title'        => $row->video_title ? $row->video_title : '',



  //             'thumb_url'         => $row->costum_thumImg ? url('/') . '/costumThumbimg/' . $row->costum_thumImg : '',



  //             'fitness_id'         => $row->id,



  //             'category'           => $row->category ? $row->category : '',



  //             'duration'           => $row->duration ? $row->duration : '',



  //             'intensity_rating'   => $row->intensity_rating,



  //             'equipment'          => $row->equipment ?  $row->equipment : '',



  //             'muscle_group'       => $row->muscle_group ? $row->muscle_group : '',



  //             'add_in'             => $add_in



  //           );

  //         }



  //         $this->response['msg']              = "data found successfully";



  //         $this->response['msg_type']         = "success";



  //         $this->response['code']             = 200;



  //         $this->response['data']             = $fitness;

  //       } else {



  //         $this->response['msg']              = "no data found";



  //         $this->response['msg_type']         = "false";



  //         $this->response['code']             = 400;

  //       }



  //       return response()->json($this->response);

  //     } catch (\Exception $e) {



  //       $this->response['msg']              = $e->getMessage();



  //       $this->response['msg_type']         = "failed";



  //       $this->response['code']             = 400;



  //       return response()->json($this->response);

  //     }

  //   }



  public function get_all_fitness(Request $request)

  {

    try {

      $user_id = $request->input('user_id');

      $category = $request->input('category');



      $get_all_fitness = DB::table('video_mode')

        ->join('demo_video', 'demo_video.id', '=', 'video_mode.workout_video_id')

        ->where('video_mode.category', $category)

        ->where('video_mode.show_status', 1)

        ->orderBy('video_mode.id', 'DESC')

        ->select('video_mode.*', 'demo_video.url', 'demo_video.thum_img', 'demo_video.costum_thumImg')

        ->get();



      $fitness = []; // Initialize the fitness array



      if (count($get_all_fitness) != 0) {

        foreach ($get_all_fitness as $row) {

          $get_all_fitness1 = DB::select('select * from preferences where user_id = ? AND fitness_id = ?', [$user_id, $row->id]);

          $add_in = empty($get_all_fitness1) ? 'no' : 'yes';



          $average_rating = DB::table('user_ratings')

            ->where('fitness_id', $row->id)

            ->select(DB::raw('SUM(user_rating) as total_rating, COUNT(*) as total_users'))

            ->first();



          if ($average_rating && $average_rating->total_users > 0) {

            $average = $average_rating->total_rating / $average_rating->total_users;

            $average_rating = number_format($average, 2);
          } else {

            $average_rating = '0';
          }



          // Add the fitness data to the array

          $fitness[] = array(

            'video_title' => $row->video_title ?: '',

            'thumb_url' => $row->costum_thumImg ? url('/') . '/costumThumbimg/' . $row->costum_thumImg : '',

            'fitness_id' => $row->id,

            'category' => $row->category ?: '',

            'duration' => $row->duration ?: '',

            'intensity_rating' => $row->intensity_rating,

            'equipment' => $row->equipment ?: '',

            'muscle_group' => $row->muscle_group ?: '',

            'add_in' => $add_in,

            'average_rating' => $average_rating,

          );
        }



        $this->response['msg'] = "Data found successfully";

        $this->response['msg_type'] = "success";

        $this->response['code'] = 200;

        $this->response['data'] = $fitness;
      } else {

        $this->response['msg'] = "No data found";

        $this->response['msg_type'] = "false";

        $this->response['code'] = 400;
      }



      return response()->json($this->response);
    } catch (\Exception $e) {

      $this->response['msg'] = $e->getMessage();

      $this->response['msg_type'] = "failed";

      $this->response['code'] = 400;

      return response()->json($this->response);
    }
  }







  public function add_preferences(Request $request)



  {



    $user_id      =     $request->input('user_id');



    $fitness_id   =     $request->input('fitness_id');



    $status   =     $request->input('status');



    if (!empty($user_id && $fitness_id && $status)) {



      if ($status == 'yes') {



        $check = DB::select('select * from preferences where user_id= "' . $user_id . '" AND fitness_id="' . $fitness_id . '"');







        if (count($check) == 0) {



          $sharepost =   DB::table('preferences')->insert([



            'user_id'        => $request->get('user_id'),



            'fitness_id'    => $fitness_id,



          ]);



          if ($sharepost) {



            $this->response['msg']              = "fitness added successfully";



            $this->response['msg_type']         = "success";



            $this->response['code']             = 200;
          } else {



            $this->response['msg']              = "something is wrong";



            $this->response['msg_type']         = "false";



            $this->response['code']             = 400;
          }
        } else {



          $this->response['msg']              = "this fitness allready added preferences";



          $this->response['msg_type']         = "false";



          $this->response['code']             = 400;
        }
      } else {



        DB::delete('delete from preferences where user_id="' . $user_id . '" AND fitness_id="' . $fitness_id . '"');



        $this->response['msg']              = "data remove successfull";



        $this->response['msg_type']         = "success";



        $this->response['code']             = 200;
      }
    } else {



      $this->response['msg']              = "all input field required";



      $this->response['msg_type']         = "false";



      $this->response['code']             = 400;
    }



    return response()->json($this->response);
  }







  public function get_preferences(Request $request)



  {



    $user_id      =     $request->input('user_id');



    if (!empty($user_id)) {



      $get_preferences = DB::table('preferences')



        ->join('video_mode', 'video_mode.id', '=', 'preferences.fitness_id')



        ->join('demo_video', 'demo_video.id', '=', 'video_mode.workout_video_id')



        ->select('video_mode.*', 'preferences.user_id', 'preferences.id', 'demo_video.thum_img')



        // ->orderBy('post_comment.id', 'desc')



        ->where('preferences.user_id', $user_id)



        ->get();



      // print_r($get_preferences); die;



      if (count($get_preferences) != 0) {



        foreach ($get_preferences as $row) {



          $preferences[] = array(



            'thum_img'                 => $row->thum_img,



            'user_id'                  => $row->user_id,



            'category'                 => $row->category,



            'duration'                 => $row->duration,



            'intensity_rating'         => $row->intensity_rating,



            'equipment'                => $row->equipment,



            'muscle_group'             => $row->muscle_group



          );
        }



        $this->response['msg']              = "data found successfully";



        $this->response['msg_type']         = "success";



        $this->response['code']             = 200;



        $this->response['data']             = $preferences;
      } else {



        $this->response['msg']              = "no data found";



        $this->response['msg_type']         = "false";



        $this->response['code']             = 400;
      }
    } else {



      $this->response['msg']              = "all input field required";



      $this->response['msg_type']         = "false";



      $this->response['code']             = 400;
    }



    return response()->json($this->response);
  }







  public function addRating(Request $request)

  {

    $validated = $request->validate([

      'user_id' => 'required|numeric',

      'fitness_id' => 'required|numeric',

      'user_rating' => 'required|numeric',

    ]);



    // Check if a rating already exists

    $existingRating = DB::table('user_ratings')

      ->where('user_id', $validated['user_id'])

      ->where('fitness_id', $validated['fitness_id'])

      ->first();



    if ($existingRating) {

      // Update existing rating

      DB::table('user_ratings')

        ->where('id', $existingRating->id)

        ->update([

          'user_rating' => $validated['user_rating'],

          'updated_at' => now(),

        ]);



      $this->response['msg'] = "Rating updated successfully";
    } else {

      // Insert new rating

      DB::table('user_ratings')->insert([

        'user_id' => $validated['user_id'],

        'fitness_id' => $validated['fitness_id'],

        'user_rating' => $validated['user_rating'],

        'created_at' => now(),

        'updated_at' => now(),

      ]);



      $this->response['msg'] = "Rating added successfully";
    }



    $this->response['msg_type'] = "success";

    $this->response['code'] = 200;



    return response()->json($this->response);
  }

















  //   public function get_fitness_detail(Request $request)



  //   {



  //     $array1 = array();



  //     $fitness_id      =     $request->input('fitness_id');



  //     $user_id      =     $request->input('user_id');



  //     if (!empty($fitness_id)) {



  //       $get_all_fitness = DB::table('video_mode')



  //         ->join('demo_video', 'demo_video.id', '=', 'video_mode.workout_video_id')



  //         //->where('video_mode.status', 1)



  //         ->Where('video_mode.id', $fitness_id)



  //         ->select('video_mode.*', 'demo_video.url', 'demo_video.thum_img')



  //         ->get();



  //       // print_r($get_all_fitness); die;







  //       //   $get_all_fitness =  DB::select('select * from video_mode where id="' . $fitness_id . '"');



  //       $workvideo = [];



  //       if (count($get_all_fitness) != 0) {



  //         foreach ($get_all_fitness as $row) {



  //           $workout_video_id  = (explode(",", $row->workout_video_id));



  //           $like_video =  DB::select('select * from like_video_mode where video_mode_id = "' . $row->id . '" AND user_id="' . $user_id . '"');



  //           $total_like =  DB::select('select * from like_video_mode where video_mode_id = "' . $row->id . '"');



  //           if (count($like_video) == 0) {



  //             $like_status = 'no';

  //           } else {



  //             $like_status = 'yes';

  //           }



  //           foreach ($workout_video_id as $video) {



  //             $video =  DB::select('select * from demo_video where id = "' . $video . '"');



  //             foreach ($video as $fitness_video) {



  //               $array1[] = array(



  //                 'url'      => $fitness_video->url



  //               );



  //               // preg_match('/<iframe.*?src="(.*?)"/', $fitness_video->url, $matches);



  //               // $thumb_url = $matches[1];











  //               $array2[] = array(



  //                 'url2'      =>  $fitness_video->url



  //               );

  //             }

  //           }



  //           $new_str = str_replace(' ', '', $row->description);



  //           $workvideo[] = array(



  //             'description_id'    => $row->id,



  //             'video_title'       => $row->video_title,



  //             'description'       => str_replace('&nbsp;', '', $row->description), //str_replace('&nbsp;','',$row->description),



  //             'category'          => $row->category,



  //             'duration'          => $row->duration,



  //             'muscle_group'      => $row->muscle_group,



  //             'equipment'         => $row->equipment,



  //             'intensity_rating'  => $row->intensity_rating,



  //             'workout_video_id'  => $row->workout_video_id,



  //             'user_like'         => $like_status,



  //             'total_like'        => count($total_like)





  //           );

  //         }



  //         $this->response['msg']              = "data found successfully";



  //         $this->response['msg_type']         = "true";



  //         $this->response['code']             = 200;



  //         $this->response['data']             = $workvideo;



  //         $this->response['video']             = $array1;



  //         $this->response['video1']             = $array2;

  //       } else {



  //         $this->response['msg']              = "no data found";



  //         $this->response['msg_type']         = "false";



  //         $this->response['code']             = 400;

  //       }

  //     } else {



  //       $this->response['msg']              = "please enter fitness id";



  //       $this->response['msg_type']         = "failed";



  //       $this->response['code']             = 400;

  //     }



  //     return response()->json($this->response);

  //   }



  public function get_fitness_detail(Request $request)

  {

    $array1 = [];

    $array2 = [];

    $fitness_id = $request->input('fitness_id');

    $user_id = $request->input('user_id');



    if (!empty($fitness_id)) {

      $get_all_fitness = DB::table('video_mode')

        ->join('demo_video', 'demo_video.id', '=', 'video_mode.workout_video_id')

        ->where('video_mode.id', $fitness_id)

        ->where('video_mode.show_status', 1)

        ->select('video_mode.*', 'demo_video.url', 'demo_video.thum_img')

        ->get();



      $workvideo = [];



      if ($get_all_fitness->isNotEmpty()) {

        foreach ($get_all_fitness as $row) {

          $workout_video_id = explode(",", $row->workout_video_id);





          $like_video = DB::select('select * from like_video_mode where video_mode_id = ? AND user_id = ?', [$row->id, $user_id]);

          $total_like = DB::select('select * from like_video_mode where video_mode_id = ?', [$row->id]);



          $like_status = count($like_video) == 0 ? 'no' : 'yes';





          $user_rating = DB::table('user_ratings')

            ->where('user_id', $user_id)

            ->where('fitness_id', $row->id)

            ->value('user_rating');



          foreach ($workout_video_id as $video) {

            $video_data = DB::select('select * from demo_video where id = ?', [$video]);



            foreach ($video_data as $fitness_video) {

              $array1[] = ['url' => $fitness_video->url];

              $array2[] = ['url2' => $fitness_video->url];
            }
          }



          $workvideo[] = [

            'description_id'    => $row->id,

            'video_title'       => $row->video_title,

            'description'       => str_replace('&nbsp;', '', $row->description),

            'category'          => $row->category,

            'duration'          => $row->duration,

            'muscle_group'      => $row->muscle_group,

            'equipment'         => $row->equipment,

            'intensity_rating'  => $row->intensity_rating,

            'workout_video_id'  => $row->workout_video_id,

            'user_like'         => $like_status,

            'total_like'        => count($total_like),

            'user_rating'       => $user_rating

          ];
        }



        $this->response['msg']      = "data found successfully";

        $this->response['msg_type'] = "true";

        $this->response['code']     = 200;

        $this->response['data']     = $workvideo;

        $this->response['video']     = $array1;

        $this->response['video1']    = $array2;
      } else {

        $this->response['msg']      = "no data found";

        $this->response['msg_type'] = "false";

        $this->response['code']     = 400;
      }
    } else {

      $this->response['msg']      = "please enter fitness id";

      $this->response['msg_type'] = "failed";

      $this->response['code']     = 400;
    }



    return response()->json($this->response);
  }







  public function like_video_mode(Request $request)



  {



    $video_mode_id =   $request->input('id');



    $user_id =   $request->get('user_id');



    if (!empty($video_mode_id && $user_id)) {







      $video =  DB::select('select * from like_video_mode where video_mode_id = "' . $video_mode_id . '" AND user_id="' . $user_id . '"');



      if (count($video) == 0) {



        $user_like = 'no';
      } else {



        $user_like = 'yes';
      }



      //print_r($video); die;



      if (count($video) == 0) {



        $like_videomode =   DB::table('like_video_mode')->insert([



          'user_id'        => $request->get('user_id'),



          'video_mode_id'    =>  $video_mode_id,



        ]);



        $videos =  DB::select('select * from like_video_mode where video_mode_id = "' . $video_mode_id . '" AND user_id="' . $user_id . '"');



        if (count($videos) == 0) {



          $user_likes = 'no';
        } else {



          $user_likes = 'yes';
        }



        $totallike_count         = DB::table('like_video_mode')->where('video_mode_id', $video_mode_id)->count();



        //print_r($totallike_count);



        if ($like_videomode) {



          $this->response['msg']              = "you have like successfully";



          $this->response['msg_type']         = "true";



          $this->response['code']             = 200;



          $this->response['total_like']       = $totallike_count;



          $this->response['user_like']        = $user_likes;
        } else {



          $this->response['msg']              = "something is wrong";



          $this->response['msg_type']         = "failed";



          $this->response['code']             = 400;



          $this->response['total_like']       = $totallike_count;



          $this->response['user_like']        = $user_likes;
        }
      } else {



        $totallike_count         = DB::table('like_video_mode')->where('video_mode_id', $video_mode_id)->count();



        $this->response['msg']              = "you have already like this post";



        $this->response['msg_type']         = "false";



        $this->response['code']             = 200;



        $this->response['total_like']       = $totallike_count;



        $this->response['user_like']        = $user_like;
      }
    } else {



      $this->response['msg']              = "all input field are required";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;
    }



    return response()->json($this->response);
  }







  public function unlike_video_mode(Request $request)



  {



    $video_mode_id =   $request->input('id');



    $user_id =   $request->get('user_id');



    if (!empty($video_mode_id && $user_id)) {







      $unlike =  DB::delete('delete from like_video_mode where user_id="' . $user_id . '" AND video_mode_id="' . $video_mode_id . '"');



      if ($unlike) {



        $totallike_count         = DB::table('like_video_mode')->where('video_mode_id', $video_mode_id)->count();



        $this->response['msg']              = "user unlike successfully";



        $this->response['msg_type']         = "success";



        $this->response['code']             = 200;



        $this->response['total']             = $totallike_count;
      } else {



        $this->response['msg']              = "something is wrong";



        $this->response['msg_type']         = "failed";



        $this->response['code']             = 400;
      }
    } else {



      $this->response['msg']              = "all input field are required";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;
    }



    return response()->json($this->response);
  }







  public function video_mode_comments(Request $request)



  {



    $video_mode_id =   $request->input('id');



    $user_id =   $request->get('user_id');



    $comment =   $request->get('comment');







    if (!empty($video_mode_id && $user_id && $comment)) {







      $video =  DB::select('select * from like_video_mode where video_mode_id = "' . $video_mode_id . '" AND user_id="' . $user_id . '"');







      $comment_videomode =   DB::table('comment_video_mode')->insert([



        'user_id'        => $request->get('user_id'),



        'video_mode_id'    =>  $video_mode_id,



        'comment'          => $request->get('comment')



      ]);







      $commentparpost = DB::table('comment_video_mode')



        ->join('users', 'users.id', '=', 'comment_video_mode.user_id')



        ->select('comment_video_mode.*', 'users.name', 'users.profile_img')



        ->orderBy('comment_video_mode.id', 'desc')



        ->where('comment_video_mode.video_mode_id', $video_mode_id)



        ->get();



      foreach ($commentparpost as $row) {



        $video_comment[] = array(



          'comment'               => $row->comment,



          'name'                  => $row->name,



          'profile_img'           =>  $row->profile_img ?  url('')  . '/' . $row->profile_img : '',



          'post_time'             => $this->facebook_time_ago($row->create_at)



        );
      }



      // $totalcomment_count         = DB::table('comment_video_mode')->where('video_mode_id', $video_mode_id)->count();







      $totalcomment_count = DB::table('comment_video_mode')



        ->join('users', 'users.id', '=', 'comment_video_mode.user_id')



        ->select('comment_video_mode.*', 'users.name', 'users.profile_img')



        ->orderBy('comment_video_mode.id', 'desc')



        ->where('comment_video_mode.video_mode_id', $video_mode_id)



        ->count();







      if ($comment_videomode) {



        $this->response['msg']              = "you have commented successfully";



        $this->response['msg_type']         = "true";



        $this->response['code']             = 200;



        $this->response['total_comment']             = $totalcomment_count;



        $this->response['data']             = $video_comment;
      } else {



        $this->response['msg']              = "something is wrong";



        $this->response['msg_type']         = "failed";



        $this->response['code']             = 400;



        $this->response['total_comment']             = $totalcomment_count;
      }
    } else {



      $this->response['msg']              = "all input field are required";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;



      //  $this->response['total_comment']             = $totalcomment_count;



    }



    return response()->json($this->response);
  }







  public function get_video_mode_coment(Request $request)



  {



    $video_mode_id =   $request->input('id');







    if (!empty($video_mode_id)) {



      $data = DB::table('comment_video_mode')



        ->join('users', 'users.id', '=', 'comment_video_mode.user_id')



        ->select('comment_video_mode.*', 'users.name', 'users.profile_img')



        ->orderBy('comment_video_mode.id', 'desc')



        ->where('comment_video_mode.video_mode_id', $video_mode_id)



        ->get();







      //  dd(count($data));







      if (count($data) != 0) {



        foreach ($data as $row) {



          $video_comment[] = array(



            'comment'               => $row->comment,



            'name'                  => $row->name,



            'profile_img'           =>  $row->profile_img ?  url('')  . '/' . $row->profile_img : '',



            'post_time'             => $this->facebook_time_ago($row->create_at)



          );
        }



        $totalcomment_count         = DB::table('comment_video_mode')->where('video_mode_id', $video_mode_id)->count();



        $this->response['msg']              = "comment found successfully";



        $this->response['msg_type']         = "success";



        $this->response['code']             = 200;



        $this->response['data']             = $video_comment;



        $this->response['total_comment']             = count($data);
      } else {



        $totalcomment_count         = DB::table('comment_video_mode')->where('video_mode_id', $video_mode_id)->count();



        $this->response['msg']              = "no data found";



        $this->response['msg_type']         = "success";



        $this->response['code']             = 400;



        $this->response['total_comment']             = $totalcomment_count;
      }
    } else {



      $this->response['msg']              = "please entered video id";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;
    }



    return response()->json($this->response);
  }







  public function share_video_mode(Request $request)



  {



    try {



      $user_id          = $request->input('user_id');



      $video_mode_id    =   $request->input('id');



      $workout_video_id = $request->input('workout_video_id');



      if (!empty($user_id && $video_mode_id && $workout_video_id)) {







        $sharepost =   DB::table('share_video_mode')->insert([



          'user_id'        => $request->get('user_id'),



          'video_mode_id'    => $video_mode_id,



          'user_share' => 'yes'



        ]);



        $share_count         = DB::table('share_video_mode')->where('video_mode_id', $video_mode_id)->count();



        if ($sharepost) {



          $get_video_url = DB::table('demo_video')->select('url', 'thum_img')->where('id', $workout_video_id)->get();



          // preg_match('/<iframe.*?src="(.*?)"/', $get_video_url[0]->url, $matches);



          // $thumb_url = $matches[1];



          $groupId =  $request->get('group_id');



          if (!empty($groupId)) {



            for ($i = 0; $i < count($groupId); $i++) {







              $add =  DB::table('post')->insert([



                'user_id'          =>   $request->get('user_id'),



                'caption'          =>   $request->get('caption'),



                'post'             =>   $request->get('post'),



                'group_id'         =>    $groupId[$i], //$request->get('group_id'),



                'post_img'         =>   $get_video_url[0]->url,



                'selected_type'    =>   'url',



                'thumble_img'      =>   $get_video_url[0]->thum_img ? $get_video_url[0]->thum_img : '',



                'share_date_time'  =>   date("Y-m-d H:i:s")



              ]);
            }
          } else {



            $add =  DB::table('post')->insert([



              'user_id'          =>   $request->get('user_id'),



              'caption'          =>   $request->get('caption'),



              'post'             =>   $request->get('post'),



              'group_id'         =>   $request->get('group_id'),



              'post_img'         =>   $get_video_url[0]->url,



              'selected_type'    =>   'url',



              'thumble_img'      =>   $get_video_url[0]->thum_img ? $get_video_url[0]->thum_img : '',



              'share_date_time'  =>   date("Y-m-d H:i:s")



            ]);
          }



          //  $share_count         = DB::table('demo_video')->where('video_mode_id', $video_mode_id)->count();



          $this->response['msg']              = "share successfully";



          $this->response['msg_type']         = "success";



          $this->response['code']             = 200;



          $this->response['total_share']      = $share_count;
        } else {



          $this->response['msg']              = "something is wrong";



          $this->response['msg_type']         = "false";



          $this->response['code']             = 400;
        }
      } else {



        $this->response['msg']              = "All input fields are required";



        $this->response['msg_type']         = "failed";



        $this->response['code']             = 400;
      }



      return response()->json($this->response);
    } catch (\Exception $e) {



      $this->response['msg']              = $e->getMessage();



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;



      return response()->json($this->response);
    }
  }







  public function download_video_mode(Request $request)



  {



    $user_id = $request->input('user_id');



    $video_mode_id =   $request->input('id');



    if (!empty($user_id && $video_mode_id)) {



      $user_download = DB::select('select id from dowload_video_mode where user_id= "' . $user_id . '" AND video_mode_id="' . $video_mode_id . '"');



      if (count($user_download) == 0) {



        $dwonload_data =   DB::table('dowload_video_mode')->insert([



          'user_id'        => $request->get('user_id'),



          'video_mode_id'    => $video_mode_id,



          'user_download' => 'yes'



        ]);



        if ($dwonload_data) {



          $this->response['msg']              = "download successfully";



          $this->response['msg_type']         = "success";



          $this->response['code']             = 200;
        } else {



          $this->response['msg']              = "something is wrong";



          $this->response['msg_type']         = "false";



          $this->response['code']             = 400;
        }
      } else {



        $this->response['msg']              = "you have already downlod this video";



        $this->response['msg_type']         = "false";



        $this->response['code']             = 200;
      }
    } else {



      $this->response['msg']              = "All input fields are required";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;
    }



    return response()->json($this->response);
  }







  //   public function get_user_download(Request $request)



  //   {



  //     $user_id = $request->input('user_id');



  //     if (!empty($user_id)) {



  //       $data = DB::table('dowload_video_mode')



  //         ->join('video_mode', 'video_mode.id', '=', 'dowload_video_mode.video_mode_id')



  //         ->join('demo_video', 'demo_video.id', '=', 'video_mode.workout_video_id')



  //         ->select('dowload_video_mode.video_mode_id', 'dowload_video_mode.user_id', 'video_mode.*', 'demo_video.thum_img', 'demo_video.costum_thumImg')



  //         ->where('dowload_video_mode.user_id', $user_id)



  //         ->get();



  //       if (count($data) != 0) {



  //         foreach ($data as $row) {



  //           $download[] = array(



  //             'thum_img'           => $row->costum_thumImg ? url('') . '/costumThumbimg/' . $row->costum_thumImg : '',



  //             'video_mode_id'      => $row->video_mode_id,



  //             'user_id'            => $row->user_id,



  //             'category'           => $row->category,



  //             'duration'           => $row->duration,



  //             'intensity_rating'   => $row->intensity_rating,



  //             'equipment'          => $row->equipment,



  //             'muscle_group'       => $row->muscle_group,



  //             'video_title'        => $row->video_title ? $row->video_title : ''



  //           );

  //         }



  //         $this->response['msg']              = "data found successfully";



  //         $this->response['msg_type']         = "success";



  //         $this->response['code']             = 200;



  //         $this->response['data']             = $download;

  //       } else {



  //         $this->response['msg']              = "no data found";



  //         $this->response['msg_type']         = "false";



  //         $this->response['code']             = 400;

  //       }

  //     } else {



  //       $this->response['msg']              = "All input fields are required";



  //       $this->response['msg_type']         = "failed";



  //       $this->response['code']             = 400;

  //     }



  //     return response()->json($this->response);

  //   }

  public function get_user_download(Request $request)

  {

    $user_id = $request->input('user_id');



    if (!empty($user_id)) {

      // $data = DB::table('dowload_video_mode')

      //     ->join('video_mode', 'video_mode.id', '=', 'dowload_video_mode.video_mode_id')

      //     ->join('demo_video', 'demo_video.id', '=', 'video_mode.workout_video_id')

      //     ->select('dowload_video_mode.video_mode_id', 'dowload_video_mode.user_id', 'video_mode.*', 'demo_video.thum_img', 'demo_video.costum_thumImg')

      //     ->where('dowload_video_mode.user_id', $user_id)

      //     ->get();

      $data = DB::table('dowload_video_mode')

        ->join('demo_video', 'demo_video.id', '=', 'dowload_video_mode.video_mode_id')

        ->join('video_mode', 'video_mode.workout_video_id', '=', 'dowload_video_mode.video_mode_id')

        ->select('dowload_video_mode.*', 'demo_video.*', 'video_mode.*')

        ->where('dowload_video_mode.user_id', $user_id)

        ->get();

      #print_r($data);die;



      if (count($data) != 0) {

        $download = [];

        foreach ($data as $row) {



          $average_rating = DB::table('user_ratings')

            ->where('fitness_id', $row->video_mode_id)

            ->select(DB::raw('SUM(user_rating) as total_rating, COUNT(*) as total_users'))

            ->first();



          if ($average_rating && $average_rating->total_users > 0) {

            $average = $average_rating->total_rating / $average_rating->total_users;

            $average_rating_value = number_format($average, 2);
          } else {

            $average_rating_value = '0';
          }





          $download[] = array(

            'thum_img'           => $row->costum_thumImg ? url('') . '/costumThumbimg/' . $row->costum_thumImg : '',

            'video_mode_id'      => $row->video_mode_id,

            'user_id'            => $row->user_id,

            'category'           => $row->category,

            'duration'           => $row->duration,

            'intensity_rating'   => $row->intensity_rating,

            'equipment'          => $row->equipment,

            'muscle_group'       => $row->muscle_group,

            'video_title'        => $row->video_title ?: '',

            'average_rating'     => $average_rating_value,

          );
        }



        $this->response['msg']              = "Data found successfully";

        $this->response['msg_type']         = "success";

        $this->response['code']             = 200;

        $this->response['data']             = $download;
      } else {

        $this->response['msg']              = "No data found";

        $this->response['msg_type']         = "false";

        $this->response['code']             = 400;
      }
    } else {

      $this->response['msg']              = "All input fields are required";

      $this->response['msg_type']         = "failed";

      $this->response['code']             = 400;
    }



    return response()->json($this->response);
  }





  public function remove_download(Request $request)



  {



    $user_id        = $request->input('user_id');



    $video_mode_id  = $request->input('video_mode_id');



    if (!empty($user_id && $video_mode_id)) {



      $remove_video_mode =  DB::delete('delete from dowload_video_mode where video_mode_id="' . $video_mode_id . '" AND user_id="' . $user_id . '"');



      if ($remove_video_mode) {



        $this->response['msg']              = "data removed successfully";



        $this->response['msg_type']         = "success";



        $this->response['code']             = 200;
      } else {



        $this->response['msg']              = "something is wrong";



        $this->response['msg_type']         = "failed";



        $this->response['code']             = 400;
      }
    } else {



      $this->response['msg']              = "All input fields are required";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;
    }



    return response()->json($this->response);
  }







  public function remove_all_download(Request $request)



  {



    $user_id   =   $request->input('user_id');



    if (!empty($user_id)) {



      $remove_all =  DB::delete('delete from dowload_video_mode where user_id="' . $user_id . '"');



      if ($remove_all) {



        $this->response['msg']              = "data removed successfully";



        $this->response['msg_type']         = "success";



        $this->response['code']             = 200;
      } else {



        $this->response['msg']              = "something is wrong";



        $this->response['msg_type']         = "failed";



        $this->response['code']             = 400;
      }
    } else {



      $this->response['msg']              = "please enter user id";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;
    }



    return response()->json($this->response);
  }











  public function get_user_image(Request $request)



  {



    $user_id   =   $request->input('user_id');



    if (!empty($user_id)) {



      $get_post =  DB::select('select post_img from post where user_id= "' . $user_id . '"');



      if (!empty($get_post)) {



        $allowed = array('gif', 'png', 'jpg');



        foreach ($get_post as $row) {



          $extension = pathinfo($row->post_img, PATHINFO_EXTENSION);



          if (in_array($extension, $allowed)) {



            $image[] = array(



              'image' => url('') . '/' . $row->post_img



            );
          }
        }



        if (!empty($image)) {



          $this->response['msg']              = "data found successfully";



          $this->response['msg_type']         = "true";



          $this->response['code']             = 200;



          $this->response['data']             = $image;
        } else {



          $image = array();



          $this->response['msg']              = "no data found successfully";



          $this->response['msg_type']         = "true";



          $this->response['code']             = 200;



          $this->response['data']             = $image;
        } //   







      } else {



        $this->response['msg']              = "no data found";



        $this->response['msg_type']         = "false";



        $this->response['code']             = 200;
      }
    } else {



      $this->response['msg']              = "please enter user id";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;
    }



    return response()->json($this->response);
  }







  public function get_user_video(Request $request)



  {



    $user_id   =   $request->input('user_id');







    // $image = array();



    if (!empty($user_id)) {



      $get_post =  DB::select('select selected_type,thumble_img,post_img from post where user_id= "' . $user_id . '"');



      if (!empty($get_post)) {



        $allowed = array('gif', 'png', 'jpg', 'jpeg');







        foreach ($get_post as $row) {



          $extension = pathinfo($row->post_img, PATHINFO_EXTENSION);



          if (!in_array($extension, $allowed)) {



            if ($row->selected_type == 'url') {



              $thumbnail_img =  $row->thumble_img;



              $image  =   $row->post_img;
            } else {



              $thumbnail_img =   url('') . '/' . $row->thumble_img;



              $image  =   url('') . '/' . $row->post_img;
            }







            $images[] = array(



              'type'            => $row->selected_type,



              'thumbnail_img'   => $thumbnail_img,



              'image'           => $image



            );
          }
        }







        if (!empty($image)) {



          $this->response['msg']              = "data found successfully";



          $this->response['msg_type']         = "true";



          $this->response['code']             = 200;



          $this->response['data']             = $images;



          //$this->response['workout']             = $workout;



          // 



        } else {



          $image = array();



          $this->response['msg']              = "no data found successfully";



          $this->response['msg_type']         = "true";



          $this->response['code']             = 200;



          $this->response['data']             = $image;
        }
      } else {



        $this->response['msg']              = "no data found";



        $this->response['msg_type']         = "false";



        $this->response['code']             = 200;
      }
    } else {



      $this->response['msg']              = "please enter user id";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;
    }



    return response()->json($this->response);
  }











  ////////////////////////description mode////////////////////////////







  public function get_fitness_description_mode(Request $request)



  {



    $description_id      =     $request->input('description_id');



    $user_id      =     $request->input('user_id');



    if (!empty($description_id)) {



      $get_all_fitness =  DB::select('select * from description_mode where video_mode_lastid = "' . $description_id . '"');



      $totalcomment_count         = DB::table('comment_description_mode')->where('description_mode_id', $description_id)->count();



      //  print_r($totalcomment_count); die;



      $total_like = DB::table('like_description_mode')->where('description_mode_id', $description_id)->count();



      $userLike =  DB::select('select * from like_description_mode where description_mode_id = "' . $description_id . '" AND user_id="' . $user_id . '"');



      if (!empty($userLike)) {



        $totallike   = 'yes';
      } else {



        $totallike   = 'no';
      }



      if (!empty($get_all_fitness)) {



        $memberdata = array();



        $qurey = [];



        // dd($get_all_fitness);



        foreach ($get_all_fitness as $row) {







          $round_description =  json_decode($row->round_description);



          if (!empty($round_description)) {



            $array = array_values($round_description);



            $new_m = array();







            if (!empty($row->demo_videoid)) {







              $qurey  = DB::table('demo_video')->whereIn('id', [$row->demo_videoid])->select('url')->get();
            }



            foreach ($array as $row1) {



              if (!empty($row1->images[0])) {



                $img = url('') . '/images/' . $row1->images[0];
              } else {



                $img = '';
              }



              $memberdata[] = array(



                'description'    =>  $row1->description,



                'images'         => $img,



              );



              //  print_r($memberdata);



            }
          }



          //  print_r($qurey);







          $workdescription[] = array(



            'title'             => $row->img_title,



            'category'          => $row->category,



            'muscle_group'      => $row->muscle_group,



            'equipment'         => $row->equipment,



            'intensity_rating'  => $row->intensity_rating,



            'total_like'        => $total_like,



            'total_comment'     => $totalcomment_count,



            'user_like'         => $totallike,



            'description'       => $row->description ? $row->description : ''







          );
        }



        $this->response['msg']              = "data found successfully";



        $this->response['msg_type']         = "true";



        $this->response['code']             = 200;



        $this->response['data']             = $workdescription;



        $this->response['memberdata']             = $memberdata;



        $this->response['video']             = $qurey;



        //$this->response['video']             = $array1;



      } else {



        $this->response['msg']              = "no data found";



        $this->response['msg_type']         = "false";



        $this->response['code']             = 200;
      }
    } else {



      $this->response['msg']              = "please enter fitness description id";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;
    }



    return response()->json($this->response);
  }















  public function like_discription_mode(Request $request)



  {



    $description_mode_id =   $request->input('id');



    $user_id =   $request->get('user_id');



    if (!empty($description_mode_id && $user_id)) {







      $video =  DB::select('select * from like_description_mode where description_mode_id = "' . $description_mode_id . '" AND user_id="' . $user_id . '"');



      if (count($video) == 0) {



        $user_like = 'no';
      } else {



        $user_like = 'yes';
      }



      //print_r($video); die;



      if (count($video) == 0) {



        $like_videomode =   DB::table('like_description_mode')->insert([



          'user_id'        => $request->get('user_id'),



          'description_mode_id'    =>  $description_mode_id,



        ]);



        $videos =  DB::select('select * from like_description_mode where description_mode_id = "' . $description_mode_id . '" AND user_id="' . $user_id . '"');



        if (count($videos) == 0) {



          $user_likes = 'no';
        } else {



          $user_likes = 'yes';
        }



        $totallike_count         = DB::table('like_description_mode')->where('description_mode_id', $description_mode_id)->count();



        if ($like_videomode) {



          $this->response['msg']              = "you have like successfully";



          $this->response['msg_type']         = "true";



          $this->response['code']             = 200;



          $this->response['total_like']       = $totallike_count;



          $this->response['user_like']        = $user_likes;
        } else {



          $this->response['msg']              = "something is wrong";



          $this->response['msg_type']         = "failed";



          $this->response['code']             = 400;



          $this->response['total_like']       = $totallike_count;



          $this->response['user_like']        = $user_likes;
        }
      } else {



        $totallike_count         = DB::table('like_description_mode')->where('description_mode_id', $description_mode_id)->count();



        $this->response['msg']              = "you have already like this post";



        $this->response['msg_type']         = "false";



        $this->response['code']             = 200;



        $this->response['total_like']             = $totallike_count;



        $this->response['user_like']             = $user_like;
      }
    } else {



      $this->response['msg']              = "all input field are required";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;
    }



    return response()->json($this->response);
  }







  public function unlike_discription_mode(Request $request)



  {



    $description_mode_id =   $request->input('id');



    $user_id =   $request->get('user_id');



    if (!empty($description_mode_id && $user_id)) {







      $unlike =  DB::delete('delete from like_description_mode where user_id="' . $user_id . '" AND description_mode_id="' . $description_mode_id . '"');



      if ($unlike) {



        $totallike_count         = DB::table('like_description_mode')->where('description_mode_id', $description_mode_id)->count();



        $this->response['msg']              = "user unlike successfully";



        $this->response['msg_type']         = "success";



        $this->response['code']             = 200;



        $this->response['total']             = $totallike_count;
      } else {



        $this->response['msg']              = "something is wrong";



        $this->response['msg_type']         = "failed";



        $this->response['code']             = 400;
      }
    } else {



      $this->response['msg']              = "all input field are required";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;
    }



    return response()->json($this->response);
  }







  public function discription_mode_comments(Request $request)



  {



    $description_mode_id =   $request->input('id');



    $user_id =   $request->get('user_id');



    $comment =   $request->get('comment');



    $description_mode = '';



    if (!empty($description_mode_id && $user_id && $comment)) {







      $totallike =  DB::select('select * from like_description_mode where description_mode_id = "' . $description_mode_id . '"');















      $comment_videomode =   DB::table('comment_description_mode')->insert([



        'user_id'        => $request->get('user_id'),



        'description_mode_id'    =>  $description_mode_id,



        'comment'          => $request->get('comment')



      ]);



      $commentparpost = DB::table('comment_description_mode')



        ->join('users', 'users.id', '=', 'comment_description_mode.user_id')



        ->select('comment_description_mode.*', 'users.name', 'users.profile_img')



        // ->orderBy('post_comment.id', 'desc')



        ->where('comment_description_mode.description_mode_id', $description_mode_id)



        ->get();



      // print_r($commentparpost); die;



      $description_mode = array();



      foreach ($commentparpost as $row) {











        $description_mode[] = array(



          'comment'               => $row->comment,



          'name'                  => $row->name,



          'profile_img'           =>  $row->profile_img ?  url('')  . '/' . $row->profile_img : '',



          'post_time'             => $this->facebook_time_ago($row->create_at),







        );
      }



      $totalcomment_count         = DB::table('comment_description_mode')->where('description_mode_id', $description_mode_id)->count();



      if ($comment_videomode) {



        $this->response['msg']              = "you have commented successfully";



        $this->response['msg_type']         = "true";



        $this->response['code']             = 200;



        $this->response['total_comment']             = $totalcomment_count;



        $this->response['data']             = $description_mode;
      } else {



        $this->response['msg']              = "something is wrong";



        $this->response['msg_type']         = "failed";



        $this->response['code']             = 400;



        $this->response['total_comment']             = $totalcomment_count;
      }
    } else {



      $this->response['msg']              = "all input field are required";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;



      //  $this->response['total_comment']             = $totalcomment_count;



    }



    return response()->json($this->response);
  }







  public function get_discription_mode_comment(Request $request)



  {



    $description_mode_id =   $request->input('id');



    $totalcomment_count         = DB::table('comment_description_mode')->where('description_mode_id', $description_mode_id)->count();



    if (!empty($description_mode_id)) {



      $data = DB::table('comment_description_mode')



        ->join('users', 'users.id', '=', 'comment_description_mode.user_id')



        ->select('comment_description_mode.*', 'users.name', 'users.profile_img')



        // ->orderBy('post_comment.id', 'desc')



        ->where('comment_description_mode.description_mode_id', $description_mode_id)



        ->get();







      if (count($data) != 0) {



        foreach ($data as $row) {



          $video_comment[] = array(



            'comment'               => $row->comment,



            'name'                  => $row->name,



            'profile_img'           =>  $row->profile_img ?  url('')  . '/' . $row->profile_img : '',



            'post_time'             => $this->facebook_time_ago($row->create_at)



          );
        }



        $this->response['msg']              = "comment found successfully";



        $this->response['msg_type']         = "success";



        $this->response['code']             = 200;



        $this->response['total_comment']             = $totalcomment_count;



        $this->response['data']             = $video_comment;
      } else {



        $this->response['msg']              = "no data found";



        $this->response['msg_type']         = "success";



        $this->response['code']             = 400;



        $this->response['total_comment']             = $totalcomment_count;
      }
    } else {



      $this->response['msg']              = "please entered video id";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;



      $this->response['total_comment']             = $totalcomment_count;
    }



    return response()->json($this->response);
  }







  public function share_discription_mode(Request $request)



  {



    $user_id = $request->input('user_id');



    $description_mode_id =   $request->input('id');



    if (!empty($user_id && $description_mode_id)) {







      $sharepost =   DB::table('share_description_mode')->insert([



        'user_id'        => $request->get('user_id'),



        'description_mode_id'    => $description_mode_id,



        'user_share' => 'yes'



      ]);



      $share_count         = DB::table('share_description_mode')->where('description_mode_id', $description_mode_id)->count();



      if ($sharepost) {



        $this->response['msg']              = "share successfully";



        $this->response['msg_type']         = "success";



        $this->response['code']             = 200;



        $this->response['total_share']      = $share_count;
      } else {



        $this->response['msg']              = "something is wrong";



        $this->response['msg_type']         = "false";



        $this->response['code']             = 400;
      }
    } else {



      $this->response['msg']              = "All input fields are required";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;
    }



    return response()->json($this->response);
  }







  //   public function userFollows_adiroide(Request $request)

  //   {



  //     $user_id = $request->input('user_id');



  //     $follow_id = $request->input('follow_id');



  //     $total_follow         = DB::table('userfollow')->where('follow_id', $follow_id)->where('user_id', $user_id)->first();



  //     if (!empty($total_follow)) {



  //       DB::table('userfollow')->where('follow_id', $follow_id)->where('user_id', $user_id)->delete();



  //       $total_following         = DB::table('userfollow')->where('follow_id', $follow_id)->count();



  //       $total_follower         = DB::table('userfollow')->where('user_id', $follow_id)->count();



  //       $this->response['msg']                       = "user unfollow successfully";



  //       $this->response['msg_type']                  = "success";



  //       $this->response['code']                      = 200;



  //       $this->response['total_following']             =  $total_follow;



  //       $this->response['total_follower']             = $total_follower;



  //       return response()->json($this->response);

  //     } else {



  //       $follow =   DB::table('userfollow')->insert([



  //         'user_id'        => $request->get('user_id'),



  //         'follow_id'    => $follow_id



  //       ]);



  //       $total_following         = DB::table('userfollow')->where('follow_id', $follow_id)->count();



  //       $total_follower         = DB::table('userfollow')->where('user_id', $follow_id)->count();



  //       $this->response['msg']              = "user follow successfully";



  //       $this->response['msg_type']         = "success";



  //       $this->response['code']             = 200;



  //       $this->response['total_following']             = $total_follower;



  //       $this->response['total_follower']             = $total_following;



  //       return response()->json($this->response);

  //     }

  //   }





  // public function userFollows_adiroide(Request $request)

  // {

  //     $user_id = $request->input('user_id');

  //     $follow_id = $request->input('follow_id');



  //     // Check if the user is already following the target user

  //     $total_follow = DB::table('userfollow')->where('follow_id', $follow_id)->where('user_id', $user_id)->first();



  //     if (!empty($total_follow)) {

  //         // If already following, unfollow

  //         DB::table('userfollow')->where('follow_id', $follow_id)->where('user_id', $user_id)->delete();

  //     } else {

  //         // If not following, follow

  //         DB::table('userfollow')->insert([

  //             'user_id' => $user_id,

  //             'follow_id' => $follow_id

  //         ]);

  //     }



  //     // Calculate total following and followers

  //     $total_following = DB::table('userfollow')->where('follow_id', $follow_id)->count();

  //     $total_follower = DB::table('userfollow')->where('user_id', $follow_id)->count();



  //     // Prepare the response based on whether the action was a follow or unfollow

  //     $action = empty($total_follow) ? "user follow successfully" : "user unfollow successfully";



  //     return response()->json([

  //         "msg" => $action,

  //         "msg_type" => "success",

  //         "code" => 200,

  //         "total_following" => $total_following,

  //         "total_follower" => $total_follower,

  //     ]);

  // }



  public function userFollows_adiroide(Request $request)

  {

    $user_id = $request->input('user_id');

    $follow_id = $request->input('follow_id');



    // Check if the user is already following the target user

    $existingFollow = DB::table('userfollow')

      ->where('user_id', $user_id)

      ->where('follow_id', $follow_id)

      ->first();



    // Get updated counts before any action

    #$total_following = DB::table('userfollow')->where('user_id', $follow_id)->count(); // Count of followers for the followed user

    #$total_follower = DB::table('userfollow')->where('user_id', $user_id)->count(); // Count of users the current user is following



    if ($existingFollow) {

      // Unfollow the user

      DB::table('userfollow')

        ->where('user_id', $user_id)

        ->where('follow_id', $follow_id)

        ->delete();



      $this->response['msg'] = "User unfollowed successfully";
    } else {

      // Follow the user

      DB::table('userfollow')->insert([

        'user_id' => $user_id,

        'follow_id' => $follow_id

      ]);



      $this->response['msg'] = "User followed successfully";
    }



    // Get updated counts after the action

    #$total_following_after = DB::table('userfollow')->where('follow_id', $follow_id)->count(); // Count of followers for the followed user

    #$total_follower_after = DB::table('userfollow')->where('user_id', $follow_id)->count(); // Count of users the current user is following

    #$total_following_after = DB::table('userfollow')->where('user_id', $follow_id)->count(); // Count of followers for the followed user

    #$total_follower_after = DB::table('userfollow')->where('user_id', $user_id)->count();



    $total_following_after         = DB::table('userfollow')->where('user_id', $follow_id)->count();

    $total_follower_after         = DB::table('userfollow')->where('follow_id', $follow_id)->count();



    $this->response['msg_type'] = "success";

    $this->response['code'] = 200;

    $this->response['total_following'] = $total_following_after; // Updated count for the followed user

    $this->response['total_follower'] = $total_follower_after; // Updated count for the current user



    return response()->json($this->response);
  }

















  public function userfollow(Request $request)



  {



    $user_id = $request->input('user_id');



    $follow_id = $request->input('follow_id');



    $total_follow         = DB::table('userfollow')->where('follow_id', $follow_id)->where('user_id', $user_id)->get();







    if (!empty($user_id && $follow_id)) {







      $check         = DB::table('userfollow')->where('follow_id', $follow_id)->where('user_id', $user_id)->get();



      if (count($check) == 0) {



        $follow =   DB::table('userfollow')->insert([



          'user_id'        => $request->get('user_id'),

          'follow_id'    => $follow_id



        ]);











        if ($follow) {



          $total_follow         = DB::table('userfollow')->where('follow_id', $follow_id)->count();



          $this->response['msg']              = "user follow successfully";



          $this->response['msg_type']         = "success";



          $this->response['code']             = 200;



          $this->response['total_follwer']             = $total_follow;
        } else {



          $this->response['msg']              = "something is wrong";



          $this->response['msg_type']         = "false";



          $this->response['code']             = 400;
        }
      } else {



        $this->response['msg']              = "you have already follow this user";



        $this->response['msg_type']         = "success";



        $this->response['code']             = 200;
      }
    } else {



      $this->response['msg']              = "All input fields are required";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;
    }



    return response()->json($this->response);
  }







  public function userunfollow(Request $request)



  {



    $user_id = $request->input('user_id');



    $follow_id = $request->input('follow_id');



    if (!empty($user_id && $follow_id)) {



      $check         = DB::table('userfollow')->where('follow_id', $follow_id)->where('user_id', $user_id)->get();







      if (count($check) != 0) {



        $unfollow =  DB::delete('delete from userfollow where user_id="' . $user_id . '" AND follow_id="' . $follow_id . '"');



        $total_follow         = DB::table('userfollow')->where('follow_id', $follow_id)->count();



        if ($unfollow) {



          $this->response['msg']                       = "user unfollow successfully";



          $this->response['msg_type']                  = "success";



          $this->response['code']                      = 200;



          $this->response['total_follwer']             = $total_follow;
        } else {



          $this->response['msg']              = "something is wrong";



          $this->response['msg_type']         = "false";



          $this->response['code']             = 400;
        }
      } else {



        $this->response['msg']              = "you have allready unfollow this user ";



        $this->response['msg_type']         = "false";



        $this->response['code']             = 400;
      }
    } else {



      $this->response['msg']              = "All input fields are required";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;
    }



    return response()->json($this->response);
  }



  public function get_fitness_search(Request $request)



  {



    $serch_term      =     $request->input('serch_term');



    $category      =     $request->input('category');



    $user_id      =     $request->input('user_id');







    //dd($category,$serch_term);



    $get_all_fitness = DB::table('video_mode');



    $get_all_fitness = $get_all_fitness->join('demo_video', 'demo_video.id', '=', 'video_mode.workout_video_id');



    $get_all_fitness = $get_all_fitness->where('video_mode.category', $category);



    $get_all_fitness = $get_all_fitness->where('video_mode.status', 1);

    $get_all_fitness = $get_all_fitness->where('video_mode.show_status', 1);





    if (isset($serch_term)) {



      $get_all_fitness = $get_all_fitness->where(function ($query) use ($serch_term) {



        $query->where('muscle_group', 'LIKE', '%' . $serch_term . '%')



          ->orWhere('equipment', 'LIKE', '%' . $serch_term . '%')



          ->orWhere('video_title', 'LIKE', '%' . $serch_term . '%');
      });



      //  $get_all_fitness = $get_all_fitness->;



      //  $get_all_fitness = $get_all_fitness->;



    }



    //  







    $get_all_fitness = $get_all_fitness->orderBy('video_mode.id', 'DESC');



    $get_all_fitness = $get_all_fitness->select('video_mode.*', 'demo_video.url', 'demo_video.thum_img')->get();



    //print_r($get_all_fitness); die;







    $get_user = DB::table("users")->where('role', 0)



      ->orwhere('name', 'LIKE', '%' . $serch_term . '%')



      ->orwhere('email', 'LIKE', '%' . $serch_term . '%')



      ->orwhere('phone', 'LIKE', '%' . $serch_term . '%')







      ->get();







    if (!empty($get_all_fitness)) {



      $userss = array();



      $fitness = array();



      foreach ($get_user as $row1) {



        $userss[] = array(



          'user_name'      => $row1->name,



          'profile_img'    =>  $row1->profile_img ?  url('')  . '/' . $row1->profile_img : '',



          'user_id'        => $row1->id,



          'type'           => 'user'



        );
      }







      foreach ($get_all_fitness as $row) {



        $get_all_fitness1 =  DB::select('select * from preferences where user_id="' . $user_id . '" AND fitness_id = "' . $row->id . '"');



        //print_r($get_all_fitness1);



        if (empty($get_all_fitness1)) {



          $add_in = 'no';
        } else {



          $add_in = 'yes';
        }



        $fitness[] = array(



          'video_title'        => $row->video_title,



          'thumb_url'         => $row->thum_img,



          'fitness_id'         => $row->id,



          'category'           => $row->category,



          'duration'           => $row->duration,



          'intensity_rating'   => $row->intensity_rating,



          'equipment'          => $row->equipment,



          'muscle_group'       => $row->muscle_group,



          'add_in'             => $add_in



        );
      }



      $this->response['msg']              = "data found successfully";



      $this->response['msg_type']         = "success";



      $this->response['code']             = 200;



      $this->response['data']             = $fitness;



      $this->response['user_data']        = $userss;
    } else {



      $this->response['msg']              = "no data found";



      $this->response['msg_type']         = "false";



      $this->response['code']             = 400;
    }



    return response()->json($this->response);
  }



  public function user_another_porfile(Request $request)

  {



    $another_userid      =     $request->input('another_userid');



    $user_id      =     $request->input('user_id');



    if (!empty($another_userid)) {



      $userprofile =  DB::select('select * from users where id= "' . $another_userid . '"');



      $total_groupuser   = DB::select("SELECT * from groups where  FIND_IN_SET('$another_userid', members)");



      $total_save_workout         = DB::table('dowload_video_mode')->where('user_id', $another_userid)->count();





      #echo $another_userid;die;

      #$total_following         = DB::table('userfollow')->where('user_id', $another_userid)->count();

      #$total_following         = DB::table('userfollow')->where('follow_id', $another_userid)->count();







      #$total_follower         = DB::table('userfollow')->where('user_id', $another_userid)->count();



      $total_following         = DB::table('userfollow')->where('user_id', $another_userid)->count();

      $total_follower         = DB::table('userfollow')->where('follow_id', $another_userid)->count();



      $totalpost         = DB::table('post')->where('user_id', $another_userid)->count();











      $user_follow = DB::table('userfollow')->where('follow_id', $another_userid)->where('user_id', $user_id)->get();







      // print_r($user_follow); die;



      if (count($user_follow) == 0) {



        $user_follow = 'no';
      } else {



        $user_follow = 'yes';
      }



      #$user_block = "";



      if (empty($user_id)) {

        $loggedInUserId = auth()->id();
      } else {

        $loggedInUserId = $user_id;
      }



      $isBlocked = DB::table('post_block')->where('block_by', $loggedInUserId)->where('block_user_id', $another_userid)->exists();

      $user_block = $isBlocked ? "Yes" : "No";



      if (empty($user_id)) {

        $loggedInUserId = auth()->id();

        $blockedUsers = DB::table('post_block')->where('block_by', $loggedInUserId)->where('block_user_id', $another_userid);
      } else {

        $blockedUsers = DB::table('post_block')->where('block_by', $user_id)->where('block_user_id', $another_userid);
      }



      //print_r($userprofile);



      foreach ($userprofile as $row) {



        $datass[] = array(



          'user_id'             =>  $row->id,



          'name'                =>  $row->name ? $row->name : '',



          'email'               =>  $row->email ? $row->email : '',



          'profile_bio'         =>  $row->profile_bio ? $row->profile_bio : '',



          'profile_img'         =>  $row->profile_img ? url('') . '/' . $row->profile_img : '',



          'total_group'         => count($total_groupuser),



          'total_save_workout'  => $total_save_workout,



          'total_follower'      => $total_follower,



          'total_following'      => $total_following,



          'user_follow'         => $user_follow,



          'total_post'          => $totalpost,

          'user_block'         => $user_block







        );
      }



      // print_r($data); die;



      if (!empty($userprofile)) {



        $this->response['msg']              = "data found successfully";



        $this->response['msg_type']         = "success";



        $this->response['status']         = "true";



        $this->response['code']             = 200;



        $this->response['data']             = $datass;



        return response()->json($this->response);
      } else {



        $this->response['msg']              = "no data found";



        $this->response['msg_type']         = "failed";



        $this->response['status']         = "false";



        $this->response['code']             = 400;



        return response()->json($this->response);
      }
    } else {



      $this->response['msg']              = "please enter other user id fields";



      $this->response['msg_type']         = "failed";



      $this->response['status']         = "false";



      $this->response['code']             = 400;



      return response()->json($this->response);
    }
  }







  public function get_fitness_search_fillter(Request $request)



  {



    $duration = $request->input('duration');



    $muscal_group = $request->input('muscal_group');



    $mobility = $request->input('mobility');



    $ratings = $request->input('ratings');



    $date = $request->input('date');



    $user_id = $request->input('user_id');



    if (!empty($duration && $muscal_group && $mobility && $ratings && $date)) {



      $get_all_fitness = DB::table("video_mode")->where('duration', 'LIKE', '%' . $duration . '%')



        ->orwhere('muscle_group', 'LIKE', '%' . $muscal_group . '%')



        ->orwhere('category', 'LIKE', '%' . $mobility . '%')



        ->orwhere('rating', 'LIKE', '%' . $ratings . '%')



        ->orwhere('created_at', '=', date('Y-m-d'))



        ->get();



      if (!empty($get_all_fitness)) {



        foreach ($get_all_fitness as $row) {



          $get_all_fitness1 =  DB::select('select * from preferences where user_id="' . $user_id . '" AND fitness_id = "' . $row->id . '"');



          //print_r($get_all_fitness1);



          if (empty($get_all_fitness1)) {



            $add_in = 'no';
          } else {



            $add_in = 'yes';
          }











          $fitness[] = array(



            'fitness_id'         => $row->id,



            'category'           => $row->category,



            'duration'           => $row->duration,



            'intensity_rating'   => $row->intensity_rating,



            'equipment'          => $row->equipment,



            'muscle_group'       => $row->muscle_group,



            'add_in'             => $add_in



          );
        }



        $this->response['msg']              = "data found successfully";



        $this->response['msg_type']         = "success";



        $this->response['code']             = 200;



        $this->response['data']             = $fitness;
      } else {



        $this->response['msg']              = "no data found";



        $this->response['msg_type']         = "false";



        $this->response['code']             = 400;
      }
    } else {



      $this->response['msg']              = "All input fields are required";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;
    }



    return response()->json($this->response);
  }



  public function compleate_goal(Request $request)
  {
    $user_id = $request->input('user_id');
    $id = $request->input('id');
    $category = $request->input('category');
    $check_type = $request->input('type');

    if (!empty($user_id && $id)) {
      $currentMonth = date('m');

      if ($check_type == 'Monthly') {
        $datas['status'] = 1;
        $datas['completed_date'] = date('Y-m-d');;
        $update =     DB::table('goals')->where('user_id', $user_id)->where('id', $id)->update($datas);

        $currentmonth = DB::table("goals")
          ->where('goal', 'Monthly')
          ->where('user_id', $user_id)
          ->where('category', $category)
          ->where('status', 1)
          ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
          ->count();

        $total_goal = DB::table('goals')
          ->where('goal', 'Monthly')
          ->where('category', $category)
          ->where('user_id', $user_id)
          ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
          ->count();

        if ($total_goal == 0) {
          $calcu = 0;
        } else {
          $calcu = ($currentmonth / $total_goal) * 100;
        }

        if ($update) {
          $check_compleated =      number_format($calcu, 2);
          if (round($check_compleated) == 50) {

            $get_token = DB::table('users')->select('fcm_token', 'name')->where('id', $user_id)->get();

            if (count($get_token) != 0) {
              $device_token = $get_token[0]->fcm_token;
              $sendData = array(
                'body'     =>  'You have' . ' ' . $category . ' ' . 'completed 50% Monthly goal',
                'title'    => 'Completed goal',
                'sound'    => 'Default',
              );

              $this->fcmNotification($device_token, $sendData);
            }
          }

          $check_status =  DB::select('select * from goals where user_id="' . $user_id . '" AND id = "' . $id . '"');

          $this->response['msg']              = "goal completed successfully";
          $this->response['msg_type']         = "success";
          $this->response['code']             = 200;
          $this->response['status']             = $check_status[0]->status;
        } else {
          $this->response['msg']              = "something is wrong";
          $this->response['msg_type']         = "false";
          $this->response['code']             = 200;
        }

        return response()->json($this->response);
        exit;
      } else {
        $datas['status'] = 1;
        $datas['completed_date'] = date('Y-m-d');;
        $update =     DB::table('goals')->where('user_id', $user_id)->where('id', $id)->update($datas);

        if ($update) {
          $monday = strtotime('next Monday -1 week');
          $monday = date('w', $monday) == date('w') ? strtotime(date("Y-m-d", $monday) . " +7 days") : $monday;
          $sunday = strtotime(date("Y-m-d", $monday) . " +6 days");
          $week_start_date =  $this_week_sd = date("Y-m-d", $monday) . ' ' . '00:00:00';
          $this_week_end = date("Y-m-d", $sunday) . ' ' . '23:59:59';

          $compleate_goal = DB::table('goals')
            ->where('status', 1)
            ->where('category', $category)
            ->where('user_id', $user_id)
            ->whereBetween('created_at', [$week_start_date, $this_week_end])
            ->count();

          $total_goal = DB::table('goals')
            ->where('user_id', $user_id)
            ->where('category', $category)
            ->whereBetween('created_at', [$week_start_date, $this_week_end])
            ->count();

          if ($total_goal == 0) {
            $calcu = 0;
          } else {
            $calcu = ($compleate_goal / $total_goal) * 100;
          }

          $check_compleated =      number_format($calcu, 2);

          if (round($check_compleated) == 50) {
            $get_token = DB::table('users')->select('fcm_token', 'name')->where('id', $user_id)->get();

            if (count($get_token) != 0) {
              $device_token = $get_token[0]->fcm_token;

              $sendData = array(
                'body'     =>  'You have' . ' ' . $category . ' ' . 'completed 50% Weekly goal',
                'title'    => 'Completed goal',
                'sound'    => 'Default',
              );

              $this->fcmNotification($device_token, $sendData);
            }
          }

          $check_status =  DB::select('select * from goals where user_id="' . $user_id . '" AND id = "' . $id . '"');
          $this->response['msg']              = "goal completed successfully";
          $this->response['msg_type']         = "success";
          $this->response['code']             = 200;
          $this->response['status']             = $check_status[0]->status;
        } else {
          $this->response['msg']              = "something is wrong";
          $this->response['msg_type']         = "false";
          $this->response['code']             = 200;
        }
      }
    } else {

      $this->response['msg']              = "All input fields are required";
      $this->response['msg_type']         = "failed";
      $this->response['code']             = 400;
    }
    return response()->json($this->response);
  }

  public function show_workout_detail(Request $request)
  {
    $user_id         = $request->user_id;



    $category        = $request->category;



    $completed_date  = $request->completed_date;



    if (!empty($user_id && $category && $completed_date)) {



      $get_workout =  DB::select('select * from workout where user_id="' . $user_id . '" AND completed_date = "' . $completed_date . '" AND category= "' . $category . '"');



      if (count($get_workout) != 0) {







        $this->response['msg']              = "data found successfully";



        $this->response['msg_type']         = "success";



        $this->response['code']             = 200;



        $this->response['status']             = $get_workout;
      } else {



        $this->response['msg']              = "something is wrong";



        $this->response['msg_type']         = "false";



        $this->response['code']             = 200;
      }
    } else {



      $this->response['msg']              = "All input fields are required";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;
    }



    return response()->json($this->response);
  }







  public function loglist(Request $request)
  {

    $user_id = $request->user_id;
    $category = $request->category;
    $date = $request->date;

    $logweight = DB::table('workout')->select('*')->where('type', 'logweight')->where('category', $category)->where('completed_date', $date)->where('user_id', $user_id)->get();
    #print_r($logweight);die;

    $logweightwithreps = DB::table('workout')->select('*')->where('type', 'logweightwithreps')->where('category', $category)->where('completed_date', $date)->where('user_id', $user_id)->get();

    $logpace = DB::table('workout')->select('*')->where('type', 'Cardio')->where('completed_date', $date)->where('user_id', $user_id)->get();

    if (count($logweight) != 0) 
    {

      foreach ($logweight as $row) 
      {

        $logweights[] = array(

          'long_weight'   => $row->long_weight,
          'duration'   => $row->duration,

        );
      }
    } else {

      $logweights = [];
    }


    if (count($logweightwithreps) != 0) 
    {

      foreach ($logweightwithreps as $row1) 
      {

        $logweightwithrep[] = array(

          'long_weight'   => $row1->long_weight,
          'reps'          => $row1->reps,
        );
      }
    } else {

      $logweightwithrep = [];
    }



    if (count($logpace) != 0) {



      foreach ($logpace as $row2) {



        $logpaces[] = array(



          'duration'   => $row2->duration,



          'reps'          => $row2->reps,



        );
      }
    } else {



      $logpaces = [];
    }



    if (!empty($logweights || $logweightwithrep || $logpaces)) {



      $this->response['msg']              = "no data found";



      $this->response['msg_type']         = "success";



      $this->response['code']             = 200;



      $this->response['logpaces']         = $logpaces;



      $this->response['logweightwithrep'] = $logweightwithrep;



      $this->response['logweights']             = $logweights;
    } else {



      $this->response['msg']              = "no data found";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;



      $this->response['logpaces']             = $logpaces;



      $this->response['logweightwithrep']             = $logweightwithrep;



      $this->response['logweights']             = $logweights;
    }



    return response()->json($this->response);
  }



















  public function get_goal_datewise(Request $request)



  {



    try {



      $get = [];



      $user_id             = $request->input('user_id');



      $completed_date      = $request->input('completed_date');



      if (!empty($user_id && $completed_date)) {



        $getuser_compleategoale =  DB::select('select * from goals where user_id="' . $user_id . '" AND completed_date = "' . $completed_date . '" AND status=1');



        $add_fitness =  DB::select('select fitness_id from add_fitnessin_calender where user_id="' . $user_id . '" AND completed_date = "' . $completed_date . '"');

        #$add_fitness_desc =  DB::select('select desc_fitness_id from add_fitnessin_calender where user_id="' . $user_id . '" AND completed_date = "' . $completed_date . '"');
        $add_fitness_desc = DB::table('add_fitnessin_calender')->select('*')->where('user_id', $user_id)->where('completed_date',$completed_date)->first();
        #print_R($add_fitness_desc);die;

        $add_fitness_desc2 = DB::table('add_fitnessin_calender')->select('*')->where('user_id', $user_id)->where('completed_date',$completed_date)->get();
        #print_r($add_fitness_desc2);die;

        $get_workout =  DB::select('select * from workout where user_id="' . $user_id . '" AND completed_date = "' . $completed_date . '" GROUP BY category');



        $newDate = date("Y-m-d", strtotime($completed_date));



        $getCat =  DB::table('user_logweight')->join('video_mode', 'video_mode.id', '=', 'user_logweight.fitness_id')
            ->where('user_logweight.user_id', $user_id)
            ->where('user_logweight.completed_date', $newDate)
            ->select('video_mode.category', 'video_mode.video_title', 'user_logweight.fitness_id', 'user_logweight.completed_date', 'user_logweight.user_id')
            ->groupBy('user_logweight.fitness_id')->get();


        #$get_workout = DB::table('workout')->Where('user_id', $user_id)->Where('completed_date', $completed_date)->groupBy('category')->get();
        

        if (count($add_fitness) != 0) {



          $array1 = array();



          foreach ($add_fitness as $row1) 
          {

            $array1[] = array('fitness_id' => $row1->fitness_id);
          }



          $numerical = array();



          $sep = ':';



          foreach ($array1 as $k => $v) 
          {

            $numerical[] = $v['fitness_id'];

          }



          $get = DB::table('video_mode')->select('*')->WhereIn('id', $numerical)->where('video_mode.show_status', 1)->get();
          #print_r($get);die;
          #$get_workout = DB::table('video_mode')->select('*')->Where('id', $add_fitness_desc->desc_fitness_id)->where('show_status', 1)->get();
          #print_r($get_workout);die;
          $data4 = collect();
          foreach($add_fitness_desc2 as $desc2)
          {
            #print_r($desc2);
            $getCat = DB::table('video_mode')->where('id', $desc2->desc_fitness_id)
            ->where('show_status', 1)
            ->get()
            ->map(function ($item) use ($completed_date) {
                $item->completed_date = $completed_date;
                return $item;
            });

            $data4 = $data4->merge($getCat);


          }

          #print_r($get_dataarr );die;
          


          // print_r($get); die;



        } else {



          $get = [];
        }







        if (count($getuser_compleategoale) != 0 ||  count($get) != 0 || count($get_workout) != 0 || count($getCat) != 0) 
        {


          $this->response['msg']              = "data found successfully";
          $this->response['msg_type']         = "success";
          $this->response['status']         = "true";
          $this->response['code']             = 200;
          $this->response['data']             = $getuser_compleategoale;
          $this->response['data1']             = $get;
          $this->response['data3']             = $get_workout;
          $this->response['data4']             = $data4;

          return response()->json($this->response);
        } else {



          $this->response['msg']              = "no data found";
          $this->response['msg_type']         = "false";
          $this->response['status']         = "true";
          $this->response['code']             = 200;
          $this->response['data']             = $getuser_compleategoale;
          $this->response['data1']             = $get;
          $this->response['data2']             = $get_workout;


          return response()->json($this->response);
        }
      } else {


        $this->response['msg']              = "All input fields are required";
        $this->response['msg_type']         = "failed";
        $this->response['code']             = 400;


        return response()->json($this->response);
      }
    } catch (\Exception $e) {



      $this->response['msg']              = $e->getMessage();



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;



      return response()->json($this->response);
    }
  }







  public function add_workout(Request $request)



  {



    $user_id      = $request->input('user_id');



    $duration     = $request->input('duration');



    $long_weight  = $request->input('long_weight');



    $reps         = $request->input('reps');



    $type         = $request->input('type');



    $category         = $request->input('category');



    $fitness_id         = $request->input('fitness_id');



















    $add =   DB::table('workout')->insert([







      'user_id'           => $request->input('user_id'),



      'duration'          => $request->input('duration'),



      'long_weight'       => $request->input('long_weight'),



      'reps'              => $request->input('reps'),



      'type'              =>  $request->input('type'),



      'completed_date'    => date('Y-m-d'),



      'category'          => $request->input('category'),



      'fitness_id'          => $request->input('fitness_id'),



    ]);











    if ($add) {



      $this->response['msg']              = "workout save successfully";



      $this->response['msg_type']         = "success";



      $this->response['code']             = 200;
    } else {



      $this->response['msg']              = "something is wrong";



      $this->response['msg_type']         = "false";



      $this->response['code']             = 400;
    }



    return response()->json($this->response);
  }







  public function add_workout_reps(Request $request)



  {



    $user_id      = $request->input('user_id');



    $duration     = $request->input('duration');



    $long_weight  = $request->input('long_weight');



    $reps         = $request->input('reps');



    $type         = $request->input('type');



    $fitness_id         = $request->input('fitness_id');







    for ($i = 0; $i < count($reps); $i++) {



      $add =   DB::table('workout')->insert([



        'user_id'           => $request->input('user_id'),



        'completed_date'    => date('Y-m-d'),



        'long_weight'       => $long_weight[$i], //$request->input('long_weight'),



        'reps'              => $reps[$i],



        'type'              =>  $request->input('type'),



        'category'          => $request->input('category'),



        'fitness_id'        => $request->input('fitness_id'),



      ]);
    }



    if ($add) {



      $this->response['msg']              = "workout save successfully";



      $this->response['msg_type']         = "success";



      $this->response['code']             = 200;
    } else {



      $this->response['msg']              = "something is wrong";



      $this->response['msg_type']         = "false";



      $this->response['code']             = 400;
    }



    return response()->json($this->response);
  }



















  public function add_workout_reps_r1(Request $request)



  {



    //echo 1; die;



    $user_id      = $request->input('user_id');



    $duration     = $request->input('duration');



    $long_weight  = $request->input('long_weight');



    $reps         = $request->input('reps');



    $type         = $request->input('type');



    $repsdata  =  explode(",", $reps);



    $long_weight  =  explode(",", $long_weight);



    //print_r($repsdata); die;



    for ($i = 0; $i < count($repsdata); $i++) {



      // print_r($reps[$i]);



      $add =   DB::table('workout')->insert([



        'user_id'           => $request->input('user_id'),



        'completed_date'    => date('Y-m-d'),







        'long_weight'       => $long_weight[$i], //$request->input('long_weight'),



        'reps'              => $repsdata[$i],



        'type'              =>  $request->input('type'),



        'category'          => $request->input('category'),



        'fitness_id'        => $request->input('fitness_id'),



      ]);
    }







    if ($add) {



      $this->response['msg']              = "workout save successfully";



      $this->response['msg_type']         = "success";



      $this->response['code']             = 200;
    } else {



      $this->response['msg']              = "something is wrong";



      $this->response['msg_type']         = "false";



      $this->response['code']             = 400;
    }



    return response()->json($this->response);
  }











  public function read_notifucation(Request $request)



  {



    $id           = $request->input('id');



    $user_id      = $request->input('user_id');



    if (!empty($id && $user_id)) {



      $datas['msg_read'] = 'read';



      $update =     DB::table('notification')



        ->where('receiver_id', $user_id)->where('id', $id)->update($datas);



      if ($update) {



        $check_status =  DB::select('select * from notification where receiver_id="' . $user_id . '" AND id = "' . $id . '"');



        $this->response['msg']              = "message read successfully";



        $this->response['msg_type']         = "success";



        $this->response['code']             = 200;



        $this->response['status']             = $check_status[0]->msg_read;
      } else {



        $this->response['msg']              = "something is wrong";



        $this->response['msg_type']         = "false";



        $this->response['code']             = 200;
      }
    } else {



      $this->response['msg']              = "All input fields are required";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;



      return response()->json($this->response);
    }



    return response()->json($this->response);
  }







  public function read_notification(Request $request)



  {



    $msg_read      = $request->input('msg_read');



    $user_id      = $request->input('user_id');



    if (!empty($msg_read && $user_id)) {



      $get_notification = DB::table('notification')



        ->join('users', 'users.id', '=', 'notification.user_id')



        ->select(



          'notification.id',



          'notification.msg_read',



          'notification.receiver_id',



          'notification.message',



          'notification.title',



          'notification.type',



          'notification.status',



          'users.name',



          'users.profile_img'



        )



        ->where('notification.receiver_id', $user_id)



        ->where('notification.msg_read', $msg_read)



        ->get();



      // print_r($get_notification); die;



      if (count($get_notification) != 0) {



        foreach ($get_notification as $row) {



          //print_r($row);



          $notifys[] = array(



            'message'           => $row->message,



            'type'              => $row->type,



            'title'             => $row->title,



            'sender_name'       => $row->name,



            'notification_id'   => $row->id,



            'receiver_id'       => $row->receiver_id,



            'msg_read'          => $row->msg_read,



            'profile_img'       =>  $row->profile_img ?  url('')  . '/' . $row->profile_img : '',



          );
        }







        $this->response['msg']              = "message read successfully";



        $this->response['msg_type']         = "success";



        $this->response['code']             = 200;



        $this->response['data']             = $notifys;
      } else {



        $this->response['msg']              = "no notification found";



        $this->response['msg_type']         = "false";



        $this->response['code']             = 200;
      }
    } else {



      $this->response['msg']              = "All input fields are required";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;
    }



    return response()->json($this->response);
  }











  public function getall_post_detail(Request $request)



  {



    //echo 1; die;



    $post_id = $request->input('post_id');



    $user_id = $request->input('user_id');







    $data = DB::table('post')



      ->join('users', 'users.id', '=', 'post.user_id')



      ->select('post.*', 'users.name', 'users.profile_img')



      ->where('post.id', $post_id)



      ->get();











    foreach ($data as $row) {



      $like_user = DB::select('select id from post_like where user_id= "' . $user_id . '" AND post_id="' . $row->id . '"');



      $share_user = DB::select('select id from post_share where post_id="' . $row->id . '"');



      $total_share = count($share_user);



      if ($row->selected_type == 'url') {



        $post_data    =  $row->post_img;



        $thumble_img  = $row->thumble_img;
      } else {



        $post_data    = $row->post_img ? url('') . '/' . $row->post_img : '';



        $thumble_img  = url('') . '/' . $row->thumble_img;
      }















      if (empty($like_user)) {



        $like = 'no';



        $like_id = null;
      } else {



        $like = 'yes';



        $like_id = $like_user[0]->id;
      }



      $like_count         = DB::table('post_like')->where('post_id', $row->id)->count();



      $comment_count      = DB::table('post_comment')->where('post_id', $row->id)->count();







      $user_goal[] = array(



        'post_img'         => $post_data, //$row->post_img ? url('') . '/' . $row->post_img : '',



        'profile_img'      => $row->profile_img ? url('') . '/' . $row->profile_img : '',



        'name'             => $row->name ? $row->name : '',



        'post'             => $row->post ? $row->post : '',



        'thumble_img'      => $thumble_img,



        'selected_type'    => $row->selected_type,



        'user_id'          => $row->user_id,



        'id'               => $row->id,



        'time'             => $this->facebook_time_ago($row->created_at),



        'total_comment'    => $comment_count,



        'total_like'       => $like_count,



        'user_like'        => $like,



        'like_id'          => $like_id,



        'total_share'      => $total_share







      );
    }







    if (!empty($user_goal)) {



      $this->response['msg']              = "data found successfully";



      $this->response['msg_type']         = "success";



      $this->response['code']             = 200;



      $this->response['data']             = $user_goal;
    } else {



      $this->response['msg']              = "no data found";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;
    }



    return response()->json($this->response);
  }







  public function add_step_count(Request $request)



  {



    $goal_id    = $request->goal_id;



    $step_count = $request->step_count;



    $current_date = date('Y-m-d');



    if (!empty($goal_id &&  $step_count)) {



      $goal         = DB::table('step_count')->where('date', $current_date)->where('goal_id', $goal_id)->get();



      if (count($goal) == 0) {



        $update =   DB::table('step_count')->insert([



          //  'user_id'           => $request->input('user_id'),



          'goal_id'  => $goal_id,



          'compleate_user_step' => $step_count,



          'date'       => date('Y-m-d'),











        ]);
      } else {







        $total_step = $step_count;



        $data['date'] = date('Y-m-d');



        $data['compleate_user_step'] = $total_step;



        $update =     DB::table('step_count')->where('date', $current_date)->where('goal_id', $goal_id)->update($data);
      }



      // print_r($goal); die;



      // $total_step = $goal->compleate_user_step + $step_count;











      if ($update) {



        $this->response['msg']              = "goal updated successfully";



        $this->response['msg_type']         = "success";



        $this->response['code']             = 200;
      } else {



        $this->response['msg']              = "something is wrong";



        $this->response['msg_type']         = "failed";



        $this->response['code']             = 400;
      }
    } else {



      $this->response['msg']              = "All input field are required";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;
    }



    return response()->json($this->response);
  }











  public function get_goal_detail(Request $request)



  {







    $post_id = $request->input('post_id');



    if (!empty($post_id)) {



      $data = DB::table('goals')



        ->join('users', 'users.id', '=', 'goals.user_id')



        ->select('goals.*', 'users.name', 'users.profile_img')



        ->where('goals.id', $post_id)



        ->get();



      $sum_step = DB::select("select sum(compleate_user_step) as total from step_count where  goal_id='" . $post_id . "'");



      // $sum_step = DB::table('step_count')->select('compleate_user_step')->where('goal_id', $post_id)->get();



      //  print_r($sum_step[0]->total); die;



      if (count($data) != 0) {



        $datas['completed_workout']   = $data[0]->completed_workout;



        $datas['goal_id']             = $data[0]->id;



        $datas['goal_id']             = $data[0]->id;



        $datas['user_id']             = $data[0]->user_id;



        $datas['goal']                = $data[0]->goal;



        $datas['category']            = $data[0]->category;



        $datas['title']               = $data[0]->title;



        $datas['goal_description']    = $data[0]->goal_description;



        $datas['compleate_user_step']      = $sum_step[0]->total ? $sum_step[0]->total : 0;



        $datas['date']                = $data[0]->date;



        $datas['status']              = $data[0]->status;



        $datas['completed_date']      = $data[0]->completed_date;



        $datas['name']                = $data[0]->name;



        $datas['profile_img']         = $data[0]->profile_img ? url('') . $data[0]->profile_img : '';







        $this->response['msg']              = "message read successfully";



        $this->response['msg_type']         = "success";



        $this->response['code']             = 200;



        $this->response['goal_data']             = $datas;
      } else {



        $this->response['msg']              = "no data found";



        $this->response['msg_type']         = "failed";



        $this->response['code']             = 400;
      }
    } else {



      $this->response['msg']              = "All input fields are required";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;
    }



    return response()->json($this->response);
  }







  ////////////////////////IOS//////////////////////////



  public function get_goal_detail1(Request $request)



  {







    $post_id = $request->input('post_id');



    if (!empty($post_id)) {



      $data = DB::table('goals')



        ->join('users', 'users.id', '=', 'goals.user_id')



        ->select('goals.*', 'users.name', 'users.profile_img')



        ->where('goals.id', $post_id)



        ->get();



      //  print_r($data); die;



      if (count($data) != 0) {



        foreach ($data as $row) {







          $array1[] = array(



            'goal_id'              => $row->id,



            'user_id'              => $row->user_id,



            'goal'                 => $row->goal,



            'category'             => $row->category,



            'title'                => $row->title,



            'goal_description'     => $row->goal_description,



            'date'                 => $row->date,



            'status'               => $row->status,



            'completed_date'       => $row->completed_date,



            'name'                 => $row->name,



            'profile_img'          => $row->profile_img ? url('') . $row->profile_img : ''











          );
        }







        $this->response['msg']              = "message read successfully";



        $this->response['msg_type']         = "success";



        $this->response['code']             = 200;



        $this->response['goal_data']             = $array1;
      } else {



        $this->response['msg']              = "no data found";



        $this->response['msg_type']         = "failed";



        $this->response['code']             = 400;
      }
    } else {



      $this->response['msg']              = "All input fields are required";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;
    }



    return response()->json($this->response);
  }







  ///////////////////////IOS///////////////////////////



  public function compleate_noof_goal(Request $request)
  {
    $user_id = $request->input('user_id');
    $goal_id = $request->input('goal_id');
    $completed_workout = $request->input('completed_workout');

    $type = $request->input('type');

    if (!empty($user_id && $goal_id && $completed_workout)) {

      $check = DB::table('goals')->select('completed_workout')->where('id', $goal_id)->first();
      $total = $check->completed_workout + $completed_workout;

      $data['completed_workout'] = $total;

      $update =     DB::table('goals')->where('id', $goal_id)->update($data);

      if ($update) {

        $check_goal = DB::table('goals')->select('no_of_workout', 'completed_workout', 'category', 'notification_status')->where('id', $goal_id)->first();

        $calculeate = ($check_goal->completed_workout * 100) / $check_goal->no_of_workout;

        if ($calculeate >= 50) {

          if ($check_goal->notification_status == 0) {
            $get_token = DB::table('users')->select('fcm_token', 'name')->where('id', $user_id)->get();

            if (count($get_token) != 0) {
              $device_token = $get_token[0]->fcm_token;

              $sendData = array(
                'body'     =>  'You have' . ' ' . $check_goal->category . ' ' . 'completed 50% Monthly goal',
                'title'    => 'Completed goal',
                'sound'    => 'Default',
              );

              $this->fcmNotification($device_token, $sendData);

              $update =     DB::table('goals')->where('id', $goal_id)->update(['notification_status' => 1]);
            }
          }
        }

        $this->response['msg']              = "data found successfully";
        $this->response['msg_type']         = "success";
        $this->response['code']             = 200;
      } else {
        $this->response['msg']              = "something is wrong";
        $this->response['msg_type']         = "failed";
        $this->response['code']             = 400;
      }
    } else {
      $this->response['msg']              = "All input fields are required";
      $this->response['msg_type']         = "failed";
      $this->response['code']             = 400;
    }

    return response()->json($this->response);
  }







  public function get_challenge_detail(Request $request)



  {







    $post_id = $request->input('post_id');



    if (!empty($post_id)) {



      $data = DB::table('challenges')



        ->join('users', 'users.id', '=', 'challenges.user_id')



        ->select('challenges.*', 'users.name', 'users.profile_img')



        ->where('challenges.id', $post_id)



        ->get();



      // print_r($data); die;



      if (count($data) != 0) {



        $datas['challenges_id']           = $data[0]->id;



        $datas['user_id']                 = $data[0]->user_id;



        $datas['goal']                    = $data[0]->goal;



        $datas['category']                = $data[0]->category;



        $datas['title']                   = $data[0]->title;



        $datas['description']             = $data[0]->description;



        $datas['status']                  = $data[0]->status;



        $datas['read_status']             = $data[0]->read_status ? $data[0]->read_status : '';



        $datas['challenge_status']        = $data[0]->challenge_status;



        $datas['name']   = $data[0]->name;



        $datas['profile_img']   = $data[0]->profile_img ? url('') . $data[0]->profile_img : '';







        $this->response['msg']              = "data found successfully";



        $this->response['msg_type']         = "success";



        $this->response['code']             = 200;



        $this->response['challenge_data']             = $datas;
      } else {



        $this->response['msg']              = "no data found";



        $this->response['msg_type']         = "failed";



        $this->response['code']             = 400;
      }
    } else {



      $this->response['msg']              = "All input fields are required";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;
    }



    return response()->json($this->response);
  }







  public function remove_user_post(Request $request)



  {



    $user_id = $request->input('user_id');



    $post_id = $request->input('post_id');



    if (!empty($user_id && $post_id)) {



      $get_post = DB::table('post')->select('user_id')->where('id', $post_id)->get();



      //  print_r($get_post[0]->user_id); die;



      if ($get_post[0]->user_id == $user_id) {



        // echo 1; die;



        $data['status']  = 0;



        $remove =  DB::table('post')->where('id', $post_id)->update($data);
      } else {







        $remove =   DB::table('remove_user_post')->insert([



          'user_id'        => $request->get('user_id'),



          'post_id'    => $post_id,



        ]);
      }



      //print_r($remove); die;



      if ($remove) {



        $this->response['msg']              = "post remove successfully";



        $this->response['msg_type']         = "success";



        $this->response['code']             = 200;
      } else {



        $this->response['msg']              = "something is wrong";



        $this->response['msg_type']         = "failed";



        $this->response['code']             = 400;
      }
    } else {



      $this->response['msg']              = "All input fields are required";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;
    }



    return response()->json($this->response);
  }







  public function accept_challenge(Request $request)



  {



    $user_id       = $request->input('user_id');



    $challenges_id  = $request->input('challenge_id');



    if (!empty($user_id && $challenges_id)) {







      $data['challenge_status'] = 1;



      $userchallenges =     DB::table('challenges')->where('id', $challenges_id)->update($data);







      if ($userchallenges) {



        //$data['challenge_friend'] = implode(",", $array1);



        //$update =   DB::table('challenges')->where('id', $challenge_id)->update($data);



        $getchallenge = DB::table('challenges')->select('*')->where('id', $challenges_id)->get();



        $challange_sender_id = $getchallenge[0]->user_id;







        DB::table('notification')->insert([



          'user_id'        => $request->get('user_id'),



          'receiver_id'    => $challange_sender_id,



          'title'          =>  'your challenged accepted',



          'message'        => 'your challenged accepted',



          'type'           => 'challenged_accepted',



          'post_id'        => $challenges_id



        ]);



        $this->response['msg']              = "you have challenge accepted";



        $this->response['msg_type']         = "success";



        $this->response['code']             = 200;
      } else {



        $this->response['msg']              = "something is wrong";



        $this->response['msg_type']         = "failed";



        $this->response['code']             = 400;
      }
    } else {



      $this->response['msg']              = "All input fields are required";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;
    }



    return response()->json($this->response);
  }







  public function cancel_challenge(Request $request)



  {



    //$getchallenge = DB::table('challenges')->select('*')->get();



    // print_r($getchallenge); die;



    $user_id       = $request->input('user_id');



    $challenge_id  = $request->input('challenge_id');



    if (!empty($user_id && $challenge_id)) {



      $getchallenge = DB::table('challenges')->select('*')->where('id', $challenge_id)->get();



      $data['challenge_status'] = 0;



      $userchallenges =     DB::table('challenges')->where('id', $challenge_id)->update($data);







      if ($userchallenges) {



        $getchallenge = DB::table('challenges')->select('*')->where('id', $challenge_id)->get();;



        $challange_sender_id = $getchallenge[0]->user_id;



        DB::table('notification')->insert([



          'user_id'        => $request->get('user_id'),



          'receiver_id'    => $challange_sender_id,



          'title'          =>  'challenge cancelled',



          'message'        =>  'your challenge has been cancelled',



          'type'           => 'challenged_accepted',



          'post_id'        => $challenge_id



        ]);



        $this->response['msg']              = "you have challenge cancel";



        $this->response['msg_type']         = "success";



        $this->response['code']             = 200;
      } else {



        $this->response['msg']              = "something is wrong";



        $this->response['msg_type']         = "failed";



        $this->response['code']             = 400;
      }
    } else {



      $this->response['msg']              = "All input fields are required";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;
    }



    return response()->json($this->response);
  }











  public function buttom_search(Request $request)



  {



    $search_term       = $request->input('search_term');



    if (!empty($search_term)) {



      $user_data = array();



      $fitness_data = array();



      $get_all_fitness = DB::table("video_mode")->where('video_title', 'LIKE', '%' . $search_term . '%')







        ->orwhere('category', 'LIKE', '%' . $search_term . '%')



        ->orwhere('description', 'LIKE', '%' . $search_term . '%')



        ->get();







      $user_search = DB::table("users")->where('role', '!=', 1)



        ->orwhere('name', 'LIKE', '%' . $search_term . '%')



        ->orwhere('email', 'LIKE', '%' . $search_term . '%')



        ->orwhere('phone', 'LIKE', '%' . $search_term . '%')



        ->get();
    } else {



      $get_all_fitness =  DB::table('video_mode')



        ->join('demo_video', 'demo_video.id', '=', 'video_mode.workout_video_id')

        ->where('video_mode.show_status', 1)

        ->select('video_mode.id', 'video_mode.video_title', 'video_mode.category', 'video_mode.description', 'video_mode.workout_video_id', 'video_mode.video_title', 'video_mode.muscle_group')->get();



      $user_search =  DB::table('users')->select('id', 'name', 'email', 'phone', 'profile_img')->where('role', '!=', 1)->get();
    }



    if (!empty($user_search or $get_all_fitness)) {



      $fitness_data = [];



      foreach ($get_all_fitness as $row) {



        $thum_imgs =  DB::table('demo_video')->select('thum_img')->where('id', $row->workout_video_id)->get();



        if (count($thum_imgs) != 0) {



          $img = $thum_imgs[0]->thum_img;



          $fileInfo = pathinfo($img);







          // Check if 'extension' key exists in the array



          $extension = isset($fileInfo['extension']) ? $fileInfo['extension'] : '';







          // Check for valid image extensions



          if ($extension == 'png' || $extension == 'jpg' || $extension == 'jpeg') {



            // Concatenate URL with the image name



            $img = url('') . '/costumThumbimg/' . $img;
          } else {



            $img = $img;
          }
        } else {



          $img = '';
        }







        $fitness_data[] = array(



          'user_id'        => $row->id,



          'name'           => $row->category ? $row->category : '',



          'phone'          => '',



          'video_title'    => $row->video_title ? $row->video_title : '',



          'muscle_group'   => $row->muscle_group ? $row->muscle_group : '',



          //'category'       => $row->category,



          'profile_img'    => '',



          'type'           => 'fitness',



          'thum_img'      => $img,



        );
      }



      $user_data = [];



      foreach ($user_search as $row1) {



        $user_data[] = array(



          'user_id'       => $row1->id,



          'name'          => $row1->name ? $row1->name : '',



          'phone'          => $row1->phone ? $row1->phone : '',



          'video_title'    => '',



          'muscle_group'   => '',



          //'category'      => '',



          'profile_img'   => $row1->profile_img ? url('') . '/' . $row1->profile_img : url(''),



          'type'          => 'user',



          'thum_img'      => '',



        );
      }







      $finalNew = array_merge($fitness_data, $user_data);







      $this->response['msg']              = "data found successfully";



      $this->response['msg_type']         = "success";



      $this->response['code']             = 200;



      $this->response['searchdata']             = $finalNew;



      //  $this->response['data1']             = $user_data;



    } else {



      $this->response['msg']              = "no data found";



      $this->response['msg_type']         = "false";



      $this->response['code']             = 200;
    }







    // } else{



    //   $this->response['msg']              = "All input fields are required";



    //   $this->response['msg_type']         = "failed";



    //   $this->response['code']             = 400;



    // }



    return response()->json($this->response);
  }















  public function buttom_search1(Request $request)



  {



    $search_term       = $request->input('search_term');



    // if (!empty($search_term)) {



    $user_data = array();



    $fitness_data = array();







    if (!empty($search_term)) {



      $get_all_fitness = DB::table("video_mode");



      $get_all_fitness = $get_all_fitness->where('category', 'LIKE', '%' . $search_term . '%')->get();



      $get_all_fitness = $get_all_fitness->orWhere('video_title', 'LIKE', '%' . $search_term . '%')->get();



      $get_all_fitness = $get_all_fitness->orWhere('muscle_group', 'LIKE', '%' . $search_term . '%')->get();



      $user_search = DB::table("users")->where('name', 'LIKE', '%' . $search_term . '%')->where('role', '!=', 1)->get();
    } else {



      $get_all_fitness =  DB::table('video_mode')->select('id', 'video_title', 'category', 'description', 'workout_video_id', 'video_title', 'muscle_group')->where('show_status', 1)->get();



      $user_search =  DB::table('users')->select('id', 'name', 'email', 'phone', 'profile_img')->where('role', '!=', 1)->get();
    }







    $final = array();



    if (!empty($user_search or $get_all_fitness)) {



      foreach ($get_all_fitness as $row) {



        $fitness_data[] = array(



          'user_id'        => $row->id,



          'name'           => $row->category ? $row->category : '',



          'phone'          => '',



          'video_title'    => $row->video_title ? $row->video_title : '',



          'muscle_group'   => $row->muscle_group ? $row->muscle_group : '',



          //'category'       => $row->category,



          'profile_img'    => '',



          'type'           => 'fitness',



        );
      }







      foreach ($user_search as $row1) {



        $user_data[] = array(



          'user_id'       => $row1->id,



          'name'          => $row1->name ? $row1->name : '',



          'phone'          => $row1->phone ? $row1->phone : '',



          'video_title'    => '',



          'muscle_group'   => '',



          //'category'      => '',



          'profile_img'   => $row1->profile_img ? url('') . '/' . $row1->profile_img : url(''),



          'type'          => 'user',



        );
      }



      $final = array_merge($user_data, $fitness_data);



      $this->response['msg']              = "data found successfully";



      $this->response['msg_type']         = "success";



      $this->response['code']             = 200;



      $this->response['searchdata']             = $final;



      //  $this->response['data1']             = $user_data;



    } else {



      $this->response['msg']              = "no data found";



      $this->response['msg_type']         = "false";



      $this->response['code']             = 200;
    }







    return response()->json($this->response);
  }







  public function get_muscle_group()



  {



    $array1 = array('Upper', 'Lower', 'Full', 'Core', 'Pull', 'Push', 'Back', 'Shoulders', 'Arms', 'Chest', 'Glutes', 'Hamstrings', 'Quads');



    $this->response['msg']              = "data found successfully";



    $this->response['msg_type']         = "success";



    $this->response['code']             = 200;



    $this->response['data']             = $array1;



    return response()->json($this->response);
  }











  public function get_mobility()
  {

    #$category_arr         = DB::table('category')->where('status', 1)->get();

    $category_arr = DB::table('category')->where('status', 1)->pluck('name')->toArray();

    #$mobility = array('HIIT', 'Strength', 'Mobility');

    $this->response['msg']              = "data found successfully";
    $this->response['msg_type']         = "success";
    $this->response['code']             = 200;
    $this->response['data']             = $category_arr;

    return response()->json($this->response);
  }



  public function fitness_filter(Request $request)
  {


    $durations           = strtok($request->input('duration'), " ");

    if ($durations == 1) {



      $duration = 60;
    } else {



      $duration = $durations;
    }



    //echo $duration; die;



    $user_id            = $request->input('user_id');



    $muscle_group       = $request->input('muscle_group');



    $mobility           = $request->input('mobility');



    $ratings            = $request->input('ratings');



    $saved              = $request->input('saved');







    //print_r($mobility);



    if (!empty($duration or $muscle_group or $mobility or  $ratings)) {







      $brand_filter = '';



      $muscle_group1 = '';



      if (!empty($mobility)) {



        $brand_filter = (implode(",", $mobility));
      }



      if (!empty($muscle_group)) {



        $muscle_group1 = (implode(",", $muscle_group));
      }



      if ($saved == 1) {







        $check         = DB::table('saved_user_filter')->where('user_id', $user_id)->get();



        if (count($check) == 0) {



          $sharepost =   DB::table('saved_user_filter')->insert([



            'duration'        => $request->input('duration'),



            'user_id'    => $request->input('user_id'),



            'muscle_group' => $muscle_group1,



            'ratings'  =>          $request->input('ratings'),



            'mobility' =>      $brand_filter



          ]);
        } else {







          $datas = array(



            'duration'        => $request->input('duration'),



            'user_id'    => $request->input('user_id'),



            'muscle_group' => $muscle_group1,



            'ratings'  =>          $request->input('ratings'),



            'mobility' =>       $brand_filter



          );







          $update =     DB::table('saved_user_filter')->where('user_id', $user_id)->update($datas);
        }
      } else {



        DB::delete('delete from saved_user_filter where user_id="' . $user_id . '"');
      }



      //  $mob    = explode(",", $mobility);







      $brand_filter = array();



      if (!empty($mobility)) {



        $brand_filter = (implode(",", $mobility));
      }







      //  print_r($brand_filter); die;



      if ($ratings == 1) {







        if (!empty($brand_filter && $muscle_group1)) {



          //echo 1; die;



          if ($ratings == 1) {



            $get_all_fitness = DB::table('video_mode')->where('category', $mobility)->orwhere('muscle_group', $muscle_group)->orWhere('duration', $duration)->where('show_status', 1)



              ->orderBy("intensity_rating", "desc")



              ->get();
          } else {



            $get_all_fitness = DB::table('video_mode')->where('category', $mobility)->orwhere('muscle_group', $muscle_group)->orWhere('duration', $duration)->where('show_status', 1)



              ->orderBy("intensity_rating", "desc")



              ->get();
          }



          //  print_r($get_all_fitness); die;



        } else if (!empty($brand_filter)) {



          // echo 2; die;



          if ($ratings == 1) {



            $get_all_fitness = DB::table('video_mode')->whereIn('category', $mobility)->orWhere('duration', $duration)->where('show_status', 1)



              ->orderBy("intensity_rating", "desc")



              ->get();
          } else {



            $get_all_fitness = DB::table('video_mode')->whereIn('category', $mobility)->orWhere('duration', $duration)->where('show_status', 1)



              ->orderBy("intensity_rating", "ASC")



              ->get();
          }
        } else {



          // echo 3; die;



          if ($ratings == 1) {



            $get_all_fitness = DB::table('video_mode')->where('muscle_group', $muscle_group)->orWhere('duration', $duration)->where('show_status', 1)



              ->orderBy("intensity_rating", "desc")



              ->get();
          } else {



            $get_all_fitness = DB::table('video_mode')->where('muscle_group', $muscle_group)->orWhere('duration', $duration)->where('show_status', 1)



              ->orderBy("intensity_rating", "ASC")



              ->get();
          }
        }
      } else {



        if (!empty($brand_filter && $muscle_group1)) {



          //echo 1; die;



          if ($ratings == 1) {



            $get_all_fitness = DB::table('video_mode')->where('category', $mobility)->where('muscle_group', $muscle_group)->orWhere('duration', $duration)->where('show_status', 1)



              ->orderBy("intensity_rating", "desc")



              ->get();
          } else {



            $get_all_fitness = DB::table('video_mode')->where('category', $mobility)->where('muscle_group', $muscle_group)->orWhere('duration', $duration)->where('show_status', 1)



              ->orderBy("intensity_rating", "ASC")



              ->get();
          }
        } else if (!empty($brand_filter)) {







          if ($ratings == 1) {



            $get_all_fitness = DB::table('video_mode')->whereIn('category', $mobility)->orWhere('duration', $duration)->where('show_status', 1)



              ->orderBy("intensity_rating", "desc")



              ->get();
          } else {



            //  print_r($mobility); die;



            $get_all_fitness = DB::table('video_mode')->whereIn('category', $mobility)->orWhere('duration', $duration)->where('show_status', 1)



              ->orderBy("intensity_rating", "ASC")



              ->get();



            // print_r($get_all_fitness); die;



          }
        } else {



          // echo 3; die;



          if ($ratings == 1) {



            $get_all_fitness = DB::table('video_mode')->where('muscle_group', $muscle_group)->where('show_status', 1)



              ->orderBy("intensity_rating", "desc")



              ->get();
          } else {



            $get_all_fitness = DB::table('video_mode')->where('muscle_group', $muscle_group)->where('show_status', 1)



              ->orderBy("intensity_rating", "ASC")



              ->get();
          }
        }



        ////////////new code heare/////////////



        if (!empty($duration)) {



          // echo $duration; die;



          //   $get_all_fitness = DB::table('video_mode')->where('duration','>=',$duration)



          $get_all_fitness = DB::select("select * from video_mode where duration <= $duration and show_status='1'");



          //->orderBy("intensity_rating", "desc")



          // ->get();



        } else {



          $get_all_fitness = DB::table('video_mode')

            ->where('show_status', 1)

            ->orderBy("intensity_rating", "ASC")



            ->get();
        }
      }



      //print_r($get_all_fitness); die;







      if (count($get_all_fitness) != 0) {



        // print_r($get_all_fitness);



        foreach ($get_all_fitness as $row) {



          $get_all_fitness1 =  DB::select('select * from preferences where user_id="' . $user_id . '" AND fitness_id = "' . $row->id . '"');



          //print_r($get_all_fitness1);



          if (empty($get_all_fitness1)) {



            $add_in = 'no';
          } else {



            $add_in = 'yes';
          }



          $check_group_mem = DB::table('demo_video')->select('thum_img')->where('id', $row->workout_video_id)->first();



          if (!empty($check_group_mem->thum_img)) {



            $thumb = $check_group_mem->thum_img;
          } else {



            $thumb = '';
          }







          $fitness[] = array(



            'thumb_url'          => $thumb ? $thumb : '',



            'fitness_id'         => $row->id ? $row->id : '',



            'category'           => $row->category ? $row->category : '',



            'duration'           => $row->duration ? $row->duration : '',



            'intensity_rating'   => $row->intensity_rating ? $row->intensity_rating : '',



            'equipment'          => $row->equipment ? $row->equipment : '',



            'muscle_group'       => $row->muscle_group ? $row->muscle_group : '',



            'add_in'             => $add_in



          );
        }



        $this->response['msg']              = "data found successfully";



        $this->response['msg_type']         = "success";



        $this->response['code']             = 200;



        $this->response['data']             = $fitness;
      } else {



        $this->response['msg']              = "no result found";



        $this->response['msg_type']         = "success";



        $this->response['code']             = 200;



        $this->response['data']             = $get_all_fitness;
      }
    } else {



      $this->response['msg']              = "At least select one field";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;
    }



    return response()->json($this->response);
  }







  public function  filter_data(Request $request)



  {



    $durations           = strtok($request->input('duration'), " ");



    if ($durations == 1) {



      $duration = 60;
    } else {



      $duration = $durations;
    }



    //print_r($duration); die;



    $user_id            = $request->input('user_id');



    $muscle_group       = $request->input('muscle_group');



    $mobility           = $request->input('mobility');



    $ratings            = $request->input('ratings');



    $saved              = $request->input('saved');



    if (!empty($duration or $muscle_group or $mobility or  $ratings)) {











      if ($saved == 1) {



        $check         = DB::table('saved_user_filter')->where('user_id', $user_id)->where('type', $mobility)->get();



        if (count($check) == 0) {







          $sharepost =   DB::table('saved_user_filter')->insert([



            'duration'        => $request->input('duration'),



            'user_id'         => $request->input('user_id'),



            'muscle_group'    => $muscle_group,



            'ratings'         => $request->input('ratings'),



            'mobility'        => $mobility,



            'type'            => $request->input('mobility')



          ]);



          //  print_r($sharepost);



        } else {



          $datas = array(



            'duration'        => $request->input('duration'),



            'user_id'         => $request->input('user_id'),



            'muscle_group'    => $muscle_group,



            'ratings'         => $request->input('ratings'),



            'type'            => $request->input('mobility')



            // 'mobility'        => $mobility



          );



          $update =     DB::table('saved_user_filter')->where('user_id', $user_id)->update($datas);
        }
      } else {



        DB::delete('delete from saved_user_filter where user_id="' . $user_id . '"');
      }







      $get_all_fitness = DB::table('video_mode');



      if (!empty($muscle_group) && $muscle_group != 'Select Muscle') {



        $muscle_group_array = (explode(",", $muscle_group));



        $get_all_fitness = $get_all_fitness->WhereIn('muscle_group', $muscle_group_array);
      }



      if (!empty($mobility)) {



        $mobility_array = $mobility;



        $get_all_fitness = $get_all_fitness->Where('category', $mobility_array);
      }



      if (!empty($duration)) {



        $get_all_fitness = $get_all_fitness->Where('duration',  $duration);
      }



      if ($ratings == 1) {



        $get_all_fitness = $get_all_fitness->orderBy('intensity_rating', 'desc');
      } else {



        $get_all_fitness = $get_all_fitness->orderBy('intensity_rating', 'ASC');
      }







      $get_all_fitness = $get_all_fitness->where('show_status', 1);

      //$get_all_fitness = $get_all_fitness->where('status', 1);







      $get_all_fitness = $get_all_fitness->get();











      if (count($get_all_fitness) != 0) {



        foreach ($get_all_fitness as $row) {



          $get_all_fitness1 =  DB::select('select * from preferences where user_id="' . $user_id . '" AND fitness_id = "' . $row->id . '"');



          if (empty($get_all_fitness1)) {



            $add_in = 'no';
          } else {



            $add_in = 'yes';
          }



          $check_group_mem = DB::table('demo_video')->select('thum_img', 'costum_thumImg')->where('id', $row->workout_video_id)->first();



          if (!empty($check_group_mem->costum_thumImg)) {



            $thumb =  url('') . '/costumThumbimg/' . $check_group_mem->costum_thumImg;
          } else {



            $thumb = '';
          }



          $fitness[] = array(



            'id' => $row->id,



            'video_title'        => $row->video_title,



            'thumb_url'          => $thumb ? $thumb : '',



            'fitness_id'         => $row->id ? $row->id : '',



            'category'           => $row->category ? $row->category : '',



            'duration'           => $row->duration ? $row->duration : '',



            'intensity_rating'   => $row->intensity_rating ? $row->intensity_rating : '',



            'equipment'          => $row->equipment ? $row->equipment : '',



            'muscle_group'       => $row->muscle_group ? $row->muscle_group : '',



            'add_in'             => $add_in



          );
        }











        $this->response['msg']              = "data found successfully";



        $this->response['msg_type']         = "success";



        $this->response['code']             = 200;



        $this->response['data']             = $fitness;
      } else {



        $this->response['msg']              = "no result found";



        $this->response['msg_type']         = "success";



        $this->response['code']             = 200;



        $this->response['data']             = $get_all_fitness;
      }
    } else {



      $this->response['msg']              = "At least select one field";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;
    }



    return response()->json($this->response);
  }











  public function get_user_saved_filter(Request $request)



  {



    $user_id       = $request->input('user_id');



    $type       = $request->input('type');



    if (!empty($user_id)) {



      $check         = DB::table('saved_user_filter')->where('user_id', $user_id)->where('type', $type)->get();



      if (count($check) != 0) {



        $this->response['msg']              = "data found successfully";



        $this->response['msg_type']         = "success";



        $this->response['code']             = 200;



        $this->response['user_id']          =  $check[0]->user_id ? $check[0]->user_id : '';



        $this->response['duration']         =  $check[0]->duration ? $check[0]->duration : '';



        $this->response['mobility']         =  $check[0]->mobility ? $check[0]->mobility : '';



        $this->response['muscle_group']     =  $check[0]->muscle_group ? $check[0]->muscle_group : '';



        $this->response['ratings']          =  $check[0]->ratings ? $check[0]->ratings : '';



        $this->response['status']           =   $check[0]->status ? $check[0]->status : '';
      } else {



        $this->response['msg']              = "no data found";



        $this->response['msg_type']         = "failed";



        $this->response['code']             = 400;
      }
    } else {



      $this->response['msg']              = "All input fields are required";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;
    }



    return response()->json($this->response);
  }







  public function complete_challenge(Request $request)



  {



    $user_id             = $request->input('user_id');



    $challenges_id       = $request->input('challenges_id');



    if (!empty($user_id && $challenges_id)) {



      $data['status'] = 2;



      $update =     DB::table('challenges')->where('id', $challenges_id)->update($data);



      if ($update) {



        $this->response['msg']              = "challenges compleated successfully";



        $this->response['msg_type']         = "success";



        $this->response['code']             = 200;
      } else {



        $this->response['msg']              = "something is wrong";



        $this->response['msg_type']         = "failed";



        $this->response['code']             = 400;
      }
    } else {



      $this->response['msg']                = "All input field are required";



      $this->response['msg_type']           = "failed";



      $this->response['code']               = 400;
    }



    return response()->json($this->response);
  }



  public function won_challenge(Request $request)



  {



    $user_id             = $request->input('user_id');



    $challenges_id       = $request->input('challenges_id');



    if (!empty($user_id && $challenges_id)) {



      $data['status'] = 1;



      $update =     DB::table('challenges')->where('id', $challenges_id)->update($data);



      if ($update) {



        $this->response['msg']              = "challenges won successfully";



        $this->response['msg_type']         = "success";



        $this->response['code']             = 200;
      } else {



        $this->response['msg']              = "something is wrong";



        $this->response['msg_type']         = "failed";



        $this->response['code']             = 400;
      }
    } else {



      $this->response['msg']                = "All input field are required";



      $this->response['msg_type']           = "failed";



      $this->response['code']               = 400;
    }



    return response()->json($this->response);
  }







  public function user_cancel_challenge(Request $request)



  {



    $user_id             = $request->input('user_id');



    $challenges_id       = $request->input('challenges_id');



    if (!empty($user_id && $challenges_id)) {



      $data['status'] = 0;



      $update =     DB::table('challenges')->where('id', $challenges_id)->update($data);



      if ($update) {



        $this->response['msg']              = "your challenge has been cancelled.";



        $this->response['msg_type']         = "success";



        $this->response['code']             = 200;
      } else {



        $this->response['msg']              = "something is wrong";



        $this->response['msg_type']         = "failed";



        $this->response['code']             = 400;
      }
    } else {



      $this->response['msg']                = "All input field are required";



      $this->response['msg_type']           = "failed";



      $this->response['code']               = 400;
    }



    return response()->json($this->response);
  }







  public function read_challenges(Request $request)



  {



    $challenges_id           = $request->input('challenges_id');



    $user_id      = $request->input('user_id');



    if (!empty($challenges_id && $user_id)) {







      $data['read_status'] = 'read';



      $update =     DB::table('challenges')->where('id', $challenges_id)->update($data);



      if ($update) {



        $this->response['msg']              = "message read successfully";



        $this->response['msg_type']         = "success";



        $this->response['code']             = 200;
      } else {



        $this->response['msg']              = "something is wrong";



        $this->response['msg_type']         = "false";



        $this->response['code']             = 200;
      }
    } else {



      $this->response['msg']                = "All input fields are required";



      $this->response['msg_type']           = "failed";



      $this->response['code']               = 400;



      return response()->json($this->response);
    }



    return response()->json($this->response);
  }







  public function payment(Request $request)



  {



    $check_sub         = DB::table('subscription_plan')->where('id', $request->plan_id)->get();



    // // require app_path() . '/Stripe/init.php';



    //print_r($_POST); die;



    $returndata = array();



    $userid     = $request->user_id;



    $planid     = $request->plan_id;



    $token      = $request->token;



    $amount     = $request->amount;







    if (!empty($userid) && !empty($token) && !empty($amount) && !empty($planid)) {



      $getUserEmail         = DB::table('users')->where('id', $request->user_id)->first();



      $email = $getUserEmail->email ? $getUserEmail->email : 'dummy@gmail.com';



      // $stripe = array(



      //   "publishable_key"   => "pk_test_51LPwhwA0lGSr1TDmRAAHRvV5FkwcV7FtBXmogXxSc9F94PzYAYDgrYIQw4iY9txCesZagczsbxEk7c3BKwJe3LuW00FWy1S3Ap",



      //   "secret_key"        => "sk_test_51LPwhwA0lGSr1TDmkVVCh3x2L8WGwmbXTQfcE293Nux9lUwttxewCwjvrnBSvTsVm4GOGdaai9FNIo2ZcIVu3Ry6008PS71B59"



      // );



      $stripe = array(



        "publishable_key"   => "pub - pk_live_51J7jiOLIqD62xTwm8VUMSnmXUS6rizIjnXUrUa2dsabFKPWoTlLSr20isDLBytlKl2y77itrme6ODwk9kkDikpHg00SSqK2T1o",



        "secret_key"        => "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"



      );







      \Stripe\Stripe::setApiKey($stripe['secret_key']);







      // Add customer to stripe



      $customer = \Stripe\Customer::create(array(



        'email'     => $email,



        'source'    => $token



      ));







      // Charge a credit or a debit card



      $charge = \Stripe\Charge::create(



        array(



          'customer'      => $customer->id,



          'amount'        => $amount * 100,



          'currency'      => 'usd',



          'description'   => 'Subscription',



        )



      );







      $chargeJson = $charge->jsonSerialize();



      // print_r($chargeJson); die;



      // Check whether the charge is successful



      if ($chargeJson['amount_refunded'] == 0 && empty($chargeJson['failure_code']) && $chargeJson['paid'] == 1 && $chargeJson['captured'] == 1) {



        $subs_plan_start = date('Y-m-d');



        $sub_end_date =   date('Y-m-d', strtotime("+30 days"));



        DB::table('users')->where('id', $userid)->update(array('payment_status' => '1', 'subs_plan_end' => $sub_end_date, 'subs_plan_start' => $subs_plan_start));







        // $check_sub         = DB::table('subscription_plan')->where('id', $user_id)->get();



        $qurey = DB::table('tbl_subscription_purches')->insert([



          'user_id'                     => $userid,



          'plan_id'                     => $planid,



          'subscription_start_date'     => date('Y-m-d'),



          'token'                       => $token,



          'amount'                      => $amount,



          'amount_refunded'             => $chargeJson['amount_refunded'],



          'failure_code'                => $chargeJson['failure_code'],



          'paid'                        => $chargeJson['paid'],



          'captured'                    => $chargeJson['captured'],



          'subscription_end_date'       => date('Y-m-d', strtotime("+30 days")),



          'plane_expire_status'         => 1



        ]);







        $returndata['status']   = true;



        $returndata['message']  = 'Payment successful.';



        $returndata['txn_id']   = $chargeJson['id'];
      } else {



        $returndata['status']   = false;



        $returndata['message']  = 'Payment failure.';
      }
    } else {



      $returndata['status']   = false;



      $returndata['message']  = 'Please provide required fields.';
    }







    return response()->json($returndata);
  }







  public function goal_count_parsentage(Request $request)



  {



    $user_id      = $request->input('user_id');



    //echo $user_id; die;



    if (!empty($user_id)) {







      $monday = strtotime('next Monday -1 week');



      $monday = date('w', $monday) == date('w') ? strtotime(date("Y-m-d", $monday) . " +7 days") : $monday;



      $sunday = strtotime(date("Y-m-d", $monday) . " +6 days");



      $week_start_date =  $this_week_sd = date("Y-m-d", $monday) . ' ' . '00:00:00';



      $this_week_end = date("Y-m-d", $sunday) . ' ' . '23:59:59';



      $compleate_goal = DB::table('goals')



        ->where('status', 1)



        ->where('user_id', $user_id)



        ->whereBetween('created_at', [$week_start_date, $this_week_end])



        ->count();







      $total_goal = DB::table('goals')



        ->where('user_id', $user_id)



        ->whereBetween('created_at', [$week_start_date, $this_week_end])



        ->count();



      if ($total_goal == 0) {



        $calcu = 0;
      } else {



        $calcu = ($compleate_goal / $total_goal) * 100;
      }



      // print_r($compleate_goal); die;



      $this->response['msg']              = "data found successfully";



      $this->response['msg_type']         = "success";



      $this->response['code']             = 200;



      $this->response['data']             = number_format($calcu, 2);
    } else {



      $this->response['msg']              = "All input fields are required";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;



      // return response()->json($this->response);



    }



    return response()->json($this->response);
  }







  public function get_current_month_goal(Request $request)



  {







    // $get_all_fitness1 =  DB::select('select * from preferences where user_id="' . $user_id . '" AND fitness_id = "' . $row->id . '"');



    $user_id      = $request->input('user_id');



    $current_date      = $request->input('current_date');



    if (!empty($user_id)) {



      $comepleate =  DB::select('select * from goals where MONTH(completed_date)=MONTH("' . $current_date . '") and YEAR(completed_date)=YEAR("' . $current_date . '") AND status=1 AND user_id="' . $user_id . '" ');



      $comepleate1 =  DB::select('select * from add_fitnessin_calender where MONTH(completed_date)=MONTH("' . $current_date . '") and YEAR(completed_date)=YEAR("' . $current_date . '")  AND user_id="' . $user_id . '" ');







      $workout =  DB::select('select * from workout where MONTH(completed_date)=MONTH("' . $current_date . '") and YEAR(completed_date)=YEAR("' . $current_date . '")  AND user_id="' . $user_id . '" ');



      $logwight =  DB::select('select * from user_logweight where MONTH(completed_date)=MONTH("' . $current_date . '") and YEAR(completed_date)=YEAR("' . $current_date . '")  AND user_id="' . $user_id . '" ');



      if (count($comepleate) != 0 || count($comepleate1) != 0 || count($workout) != 0 || count($logwight)) {



        $this->response['msg']              = "data found successfully";



        $this->response['msg_type']         = "success";



        $this->response['code']             = 200;



        $this->response['data']             = $comepleate;



        $this->response['data1']             = $comepleate1;



        $this->response['data2']            = $workout;



        $this->response['data3']            = $logwight;
      } else {



        $this->response['msg']              = " no data found";



        $this->response['msg_type']         = "false";



        $this->response['code']             = 200;



        $this->response['data']             = $comepleate;



        $this->response['data1']             = $comepleate1;
      }
    } else {



      $this->response['msg']              = "All input fields are required";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;
    }



    return response()->json($this->response);
  }











  public function purches_plane_detail(Request $request)



  {



    $user_id      = $request->input('user_id');



    if (!empty($user_id)) {



      $check_sub         = DB::table('tbl_subscription_purches')->where('user_id', $user_id)







        ->where('plane_expire_status', 1)



        ->orderBy('id', 'desc')



        ->limit(1)



        ->get();



      //  print_r($check_sub); die;



      if (!empty($check_sub[0]->plan_id)) {



        $row         = DB::table('subscription_plan')->where('id', $check_sub[0]->plan_id)->get();



        if (count($row) != 0) {



          $subscriptiondata['id']                      = $row[0]->id;



          $subscriptiondata['title']                   = $row[0]->title;



          $subscriptiondata['text']                    = $row[0]->text;



          $subscriptiondata['price']                   = $row[0]->price;



          $subscriptiondata['discount']                = $row[0]->discount;



          $subscriptiondata['device_at_a_time']        = $row[0]->device_at_a_time;



          $subscriptiondata['per_member']              = $row[0]->per_member;



          $subscriptiondata['auto_renewal']            = $row[0]->auto_renewal;



          $subscriptiondata['discount_codes']          = $row[0]->discount_codes;



          $subscriptiondata['one_month_free_trial']    = $row[0]->one_month_free_trial;



          $subscriptiondata['plan_for']                = $row[0]->plan_for;



          $subscriptiondata['created_at']              = $row[0]->created_at;



          $subscriptiondata['updated_at']              = $row[0]->updated_at;



          $subscriptiondata['deleted_at']              = $row[0]->deleted_at;







          $this->response['msg']              = "data found successfully";



          $this->response['msg_type']         = "true";



          $this->response['code']             = 200;



          $this->response['plan_data']             = $subscriptiondata;
        } else {



          $this->response['msg']              = "no subscription  found";



          $this->response['msg_type']         = "failed";



          $this->response['code']             = 400;
        }
      } else {



        $this->response['msg']              = "something is wrong";



        $this->response['msg_type']         = "failed";



        $this->response['code']             = 400;
      }
    } else {



      $this->response['msg']              = "All input fields are required";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;
    }



    return response()->json($this->response);
  }







  public function check_user_plane(Request $request)



  {



    $user_id      = $request->input('user_id');



    if (!empty($user_id)) {



      $users = DB::table('users')->select('subs_plan_start', 'subs_plan_end', 'payment_status')->where('id', $user_id)->get();



      if (count($users) != 0) {



        $this->response['msg']              = "plane active";



        $this->response['msg_type']         = "true";



        $this->response['code']             = 200;



        $this->response['sub_status']             = $users[0]->payment_status;
      } else {



        $this->response['msg']              = "plane active";



        $this->response['msg_type']         = "true";



        $this->response['code']             = 200;



        $this->response['sub_status']             = 0;
      }
    } else {



      $this->response['msg']              = "All input fields are required";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;
    }



    return response()->json($this->response);
  }











  public function check_user_subscription_exp()



  {



    $subs_plan_start = date('Y-m-d');



    DB::table('users')->where('subs_plan_end', $subs_plan_start)->update(array('payment_status' => '0', 'subs_plan_end' => '', 'subs_plan_start' => ''));



    DB::table('tbl_subscription_purches')->where('id', $userid)->update(array('tbl_subscription_purches' => 0));
  }







  public function get_user_download1(Request $request)



  {



    $user_id = $request->input('user_id');



    $search_term = $request->input('search_term');



    if (!empty($user_id)) {



      $data = DB::table('dowload_video_mode')



        ->join('video_mode', 'video_mode.id', '=', 'dowload_video_mode.video_mode_id')



        ->join('demo_video', 'demo_video.id', '=', 'video_mode.workout_video_id')



        ->select('dowload_video_mode.*', 'video_mode.*', 'demo_video.thum_img')



        ->where('dowload_video_mode.user_id', $user_id)



        ->where('video_mode.category', 'LIKE', "%{$search_term}%")



        ->get();











      if (count($data) != 0) {



        $download = array();



        foreach ($data as $row) {







          // echo 1; die;



          $download[] = array(



            'thum_img'           => $row->thum_img,



            'video_mode_id'      => $row->video_mode_id,



            'user_id'            => $row->user_id,



            'category'           => $row->category,



            'duration'           => $row->duration,



            'intensity_rating'   => $row->intensity_rating,



            'equipment'          => $row->equipment,



            'muscle_group'       => $row->muscle_group



          );
        }



        $unique = array_map("unserialize", array_unique(array_map("serialize", $download)));







        if (count($download) != 0) {



          $this->response['msg']              = "data found successfully";



          $this->response['msg_type']         = "success";



          $this->response['code']             = 200;



          $this->response['data']             = $unique;
        } else {



          $this->response['msg']              = "no data found";



          $this->response['msg_type']         = "false";



          $this->response['code']             = 200;



          $this->response['data']             = $download;
        }
      } else {



        $this->response['msg']              = "no data found";



        $this->response['msg_type']         = "false";



        $this->response['code']             = 400;
      }
    } else {



      $this->response['msg']              = "All input fields are required";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;
    }



    return response()->json($this->response);
  }







  public function get_group_user1(Request $request)



  {



    $group_id = $request->input('group_id');



    $search_term = $request->input('search_term');



    if (!empty($group_id)) {



      $group =  DB::select('select * from groups where id= "' . $group_id . '"');



      if ($group[0]->members != '') {



        $groupuser = array();







        $userid =  (explode(",", $group[0]->members));



        foreach ($userid as $user_id) {



          if (!empty($search_term)) {



            $userimage = DB::table("users")->where('name', 'LIKE', '%' . $search_term . '%')->where('id', '=', $user_id)->get();
          } else {



            $userimage =  DB::select('select * from users where id= "' . $user_id . '"');
          }



          foreach ($userimage as $row) {







            $groupuser[] = array(



              'user_image'     => $row->profile_img ? url('') . '/' . $row->profile_img : '',



              'user_id'        => $row->id,



              'name'           => $row->name ? $row->name : '',



            );
          }
        }







        $this->response['msg']              = "user found this group";



        $this->response['msg_type']         = "success";



        $this->response['code']             = 200;



        $this->response['data']             = $groupuser;
      } else {



        $this->response['msg']              = "no user found this group";



        $this->response['msg_type']         = "success";



        $this->response['code']             = 200;
      }
    } else {



      $this->response['msg']              = "please enter group id";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;
    }



    return response()->json($this->response);
  }



  //////////////////16-02-2023////////////////







  public function get_goal_type()



  {







    $goal_type = ['Weekly', 'Monthly'];







    $get_goal_type = DB::table('goal_type')->select('goal_type')->get();



    if (count($goal_type) != 0) {



      $this->response['msg']              = "goal found successfully";



      $this->response['msg_type']         = "success";



      $this->response['code']             = 200;



      $this->response['data']             = $goal_type;
    } else {



      $this->response['msg']              = "not any goal found";



      $this->response['msg_type']         = "success";



      $this->response['code']             = 200;



      $this->response['data']             = $goal_type;
    }



    return response()->json($this->response);
  }











  public function get_workout_type(Request $request)



  {



    $goal_type = $request->input('goal_type');



    $workout_type = DB::table('goal_type')->select('goal_type', 'workout_type')->where('goal_type', $goal_type)->get();



    if (count($workout_type) != 0) {



      $this->response['msg']              = "goal found successfully";



      $this->response['msg_type']         = "success";



      $this->response['code']             = 200;



      $this->response['data']             = $workout_type;
    } else {



      $this->response['msg']              = "not any goal found";



      $this->response['msg_type']         = "success";



      $this->response['code']             = 200;



      $this->response['data']             = $workout_type;
    }



    return response()->json($this->response);
  }











  public function get_fitness_video()



  {



    $get_fitness_video = DB::table('video_mode')



      ->join('demo_video', 'demo_video.id', '=', 'video_mode.workout_video_id')



      ->where('video_mode.status', 1)

      ->where('video_mode.show_status', 1)



      ->orderBy('video_mode.id', 'desc')



      ->select('video_mode.*', 'demo_video.url', 'demo_video.title as video_title', 'demo_video.thum_img', 'demo_video.costum_thumImg')



      ->get();



    //   $get_fitness_video =  $get_fitness_video->get();



    if (count($get_fitness_video) != 0) {



      foreach ($get_fitness_video as $row) {



        // $fitness_video = $row->url;



        // preg_match('/<iframe.*?src="(.*?)"/', $fitness_video, $matches);



        // $thumb_url = $matches[1];



        $array_video[] = array(



          'fitness_id'  => $row->id,



          'video_thumb' => $row->costum_thumImg ? url('/') . '/costumThumbimg/' . $row->costum_thumImg : '',



          'video_url'   => $row->url,



          'category'  => $row->category,



          'video_title'  => $row->video_title,







        );
      }



      $this->response['msg']              = "goal found successfully";



      $this->response['msg_type']         = "success";



      $this->response['code']             = 200;



      $this->response['data']             = $array_video;
    } else {



      $this->response['msg']              = "goal found successfully";



      $this->response['msg_type']         = "success";



      $this->response['code']             = 200;



      $this->response['data']             = $get_fitness_video;
    }



    return response()->json($this->response);
  }







  // public function fcmNotification($device_token, $sendData)



  // {



  //   //



  //   if (empty($device_token)) {



  //     return false;

  //   }



  //   //  print_r($device_token); die;



  //   #API access key from Google API's Console



  //   if (!defined('API_ACCESS_KEY')) {



  //     define('API_ACCESS_KEY', 'AAAAMc9Z7z4:APA91bHWHZuOyvtZLmeXqT0S_2ZIAA2mKrrs-e1fbk5aaxR4aty8v4iD3KXTdbJlKLJlQcUTCTgsJdLlUvlTk3bqcHvjg0_IxGr2XplCu-UEKUzIqjtfu7I6vgAAock9n5swSCGtMwHX');

  //   }







  //   $fields = array(



  //     'to'            => $device_token,



  //     'data'          => $sendData,



  //     'notification'  => $sendData



  //   );



  //   // print_r($fields); die;







  //   $headers = array(



  //     'Authorization: key=' . API_ACCESS_KEY,



  //     'Content-Type: application/json'



  //   );



  //   //print_r($headers); die;



  //   //$url = 'https://fcm.googleapis.com/fcm/send';



  //   #Send Request To FireBase Server



  //   $ch = curl_init();



  //   curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');



  //   curl_setopt($ch, CURLOPT_POST, true);



  //   curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);



  //   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);



  //   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);



  //   curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));



  //   $result = curl_exec($ch);



  //   //print_r($result); 







  //   if ($result === false) {



  //     die('Curl failed ' . curl_error($ch));

  //   }



  //   curl_close($ch);



  //   //return $result;



  // }


  //07-03-2025

  public function generateAccessToken()
  {
    $serviceAccountKeyFile = 'https://sparkfitness.tgastaging.com/notification_file/gymni-c9cc9-firebase-adminsdk-fbsvc-7f12e4c8d3.json';

    // Fetch JSON file content from the URL
    $jsonContent = file_get_contents($serviceAccountKeyFile);
    $serviceAccountKeyFile = json_decode($jsonContent, true);

    // print_r($serviceAccountKeyFile['private_key']);exit;
    $now = time();
    $privateKey = chunk_split($serviceAccountKeyFile['private_key'], 64, "\n");

    // print_r($privateKey);exit;
    $clientEmail = $serviceAccountKeyFile['client_email'];

    $header = ['alg' => 'RS256', 'typ' => 'JWT'];
    $payload = [
      'iss' => $clientEmail,
      'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
      'aud' => 'https://oauth2.googleapis.com/token',
      'iat' => $now,
      'exp' => $now + 3600
    ];

    $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode($header)));

    $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode($payload)));

    $signature = '';

    openssl_sign($base64UrlHeader . "." . $base64UrlPayload, $signature, $privateKey, 'SHA256');

    $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

    if (!openssl_sign($base64UrlHeader . "." . $base64UrlPayload, $signature, $privateKey, 'SHA256')) {
      echo "Signature generation failed: " . openssl_error_string();
      exit;
    }

    $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

    $postFields = http_build_query([
      'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
      'assertion' => $jwt
    ]);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://oauth2.googleapis.com/token');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
      echo 'cURL error: ' . curl_error($ch);
    }

    curl_close($ch);

    $tokenData = json_decode($response, true);

    return $tokenData['access_token'];
  }

  public function fcmNotification($fcmToken, $body)
  {
    $accessToken = $this->generateAccessToken();

    $title = 'Gymini Fitness App';

    $response = $this->sendFCMNotification($title, $body, $fcmToken, $accessToken);
  }

  public function sendFCMNotification($title, $body, $fcmToken, $accessToken)
  {
    // Ensure that the body is an array and extract the necessary fields
    $bodyContent = isset($body['body']) ? $body['body'] : "Default Body";  // Default if not set

    $titleContent = isset($body['title']) ? $body['title'] : "Default Title"; // Default if not set

    // Handle sound and type in the 'data' part of the notification

    $sound = isset($body['sound']) ? $body['sound'] : 'default'; // Default sound if not provided

    $type = isset($body['type']) ? $body['type'] : 'default';  // Default type if not provided

    // The notification payload that will be sent to FCM
    $notification = [
      'message' => [
        'token' => $fcmToken,
        'notification' => [
          'title' => $titleContent,  // String title
          'body' => $bodyContent,    // String body
        ],

        'data' => [
          'sound' => $sound,  // Custom data: sound
          'type' => $type,    // Custom data: type
        ]
      ]
    ];

    $headers = [
      'Authorization: Bearer ' . $accessToken,
      'Content-Type: application/json',
    ];

    // cURL request
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/v1/projects/gymni-c9cc9/messages:send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($notification));

    $result = curl_exec($ch);

    // Check for cURL errors
    if ($result === FALSE) {
      die('FCM Send Error: ' . curl_error($ch));
    }

    curl_close($ch);

    return $result;
  }


  public function test()
  {
    $device_token = 'cqMasBM_Rq-dPgHzpYAMdk:APA91bG8xxhdikURU79V6N7zHouU8-v6umGRd5yMMud1MR-VjggImVQjrZSI-YS6Fg7y4BGl7Eav04kdHZTiqp_TYJ6iNHrmsRe9psNNe6THBZZFVq-_8dBkGxOKqBqDJHtQtZCs2-Sy';

    $sendData = array(
      'body'     => 'You have received a ping request.',
      'title' => 'Ping Request',
      'sound' => 'Default',
    );
    $this->fcmNotification($device_token, $sendData);
  }


  public function check_user_group_exist(Request $request)
  {
    $user_id         = $request->input('user_id');



    $group_id        = $request->input('group_id');



    $check_group_mem = DB::table('groups')->select('members')->where('id', $group_id)->get();



    $g_memebar       = (explode(",", $check_group_mem[0]->members));



    if (in_array($user_id, $g_memebar)) {



      $this->response['msg']              = "user found this group";



      $this->response['msg_type']         = "success";



      $this->response['code']             = 200;



      $this->response['data']             = true;
    } else {



      $this->response['msg']              = "no user found this group";



      $this->response['msg_type']         = "success";



      $this->response['code']             = 200;



      $this->response['data']             = false;
    }



    return response()->json($this->response);
  }



  //   public function logout(Request $request)



  //   {



  //     $user_id         = $request->input('user_id');



  //     if (!empty($user_id)) {



  //       $data['token'] = '';



  //       $data['device_type'] = '';



  //       $update =     DB::table('users')->where('id', $user_id)->update($data);



  //       if ($update) {



  //         $this->response['msg']              = "user logout successfully";



  //         $this->response['msg_type']         = "success";



  //         $this->response['code']             = 200;

  //       } else {



  //         $this->response['msg']              = "something is wrong";



  //         $this->response['msg_type']         = "success";



  //         $this->response['code']             = 200;

  //       }

  //     } else {



  //       $this->response['msg']              = "All input field are required";



  //       $this->response['msg_type']         = "success";



  //       $this->response['code']             = 400;

  //     }



  //     return response()->json($this->response);

  //   }

  public function logout(Request $request)
  {
    $user_id = $request->input('user_id');
    $token = $request->bearerToken();

    if (!empty($user_id) && !empty($token)) {
      // Clear the user's token in the users table
      $update = DB::table('users')->where('id', $user_id)->update([
        'token' => '',
        'fcm_token' => '',
        'device_type' => ''
      ]);

      if ($update) {
        // Remove all login history entries for this user
        DB::table('login_histories')->where('token', $request->bearerToken())->delete();

        return response()->json(['msg' => "User logged out successfully", 'msg_type' => "success", 'code' => 200]);
      } else {
        return response()->json(['msg' => "Something went wrong", 'msg_type' => "failed", 'code' => 500]);
      }
    } else {
      return response()->json(['msg' => "All input fields are required", 'msg_type' => "failed", 'code' => 400]);
    }
  }







  public function get_category_video(Request $request)



  {



    $video_mode_id = $request->video_mode_id;



    if (!empty($video_mode_id)) {



      $workout_type = DB::table('video_mode')->select('demo_videoid')->where('id', $video_mode_id)->where('show_status', 1)->get();



      // print_r($workout_type); die;



      if (count($workout_type) != 0) {



        $demo_video = $workout_type[0]->demo_videoid;



        $id  =  explode(",", $demo_video);



        $demovideo = DB::table('demo_video')->whereIn('id', $id)->get();



        //print_r($demovideo); die;



        if (count($demovideo) != 0) {



          //  print_r($demovideo); die;



          foreach ($demovideo as $row) {







            $data[] = array(



              'title' => $row->title,



              'url'  => $row->url,



              'thum_img'  => $row->thum_img,



              //   'video'  => $row->url



            );
          }



          $this->response['msg']              = "goal found successfully";



          $this->response['msg_type']         = "success";



          $this->response['code']             = 200;



          $this->response['data']             = $data;
        } else {



          $this->response['msg']              = "no data found";



          $this->response['msg_type']         = "failed";



          $this->response['code']             = 400;
        }
      } else {



        $this->response['msg']              = "no data found";



        $this->response['msg_type']         = "failed";



        $this->response['code']             = 400;
      }
    } else {



      $this->response['msg']              = "All input fields are required";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;
    }



    return response()->json($this->response);
  }



  public function getcatVideo(Request $request)
  {



    $catType = $request->type;
    $fitness_id  = $request->fitness_id;
    $workout_type = DB::table('video_mode')->select('*')->get();
    $get_fitness_video = [];
    $getDescription = DB::table('description_mode')->where('video_mode_lastid', $fitness_id)->select('*')->first();

    if (!empty($getDescription)) 
    {

      $myArray = explode(',', $getDescription->demo_videoid);
      $get_fitness_video = DB::table('demo_video')->whereIn('id', $myArray)->get();

    }

    if (count($get_fitness_video) != 0) 
    {

      foreach ($get_fitness_video as  $row) 
      {

        $get_fitness_videos[] = array(

          'video_title' => $row->title,
          'id'  => $row->id,
          'url' => $row->url,
          'vtitle' => $row->title,
          'thum_img' => $row->costum_thumImg ? url('/') . '/costumThumbimg/' . $row->costum_thumImg : '',
          'demo_video_url'  => $row->url ? $row->url : '',
          'tutorial_video_url' =>  $row->url2 ? $row->url2 : ''

        );
      }


      $this->response['msg']              = "data found successfully";
      $this->response['msg_type']         = "success";
      $this->response['code']             = 200;
      $this->response['data']             = $get_fitness_videos;

    } else {

      $this->response['msg']              = "no data found";
      $this->response['msg_type']         = "failed";
      $this->response['code']             = 400;

    }


    //$workout_type = DB::table('video_mode')->select('*')->where('category', $catType)->get();



    return response()->json($this->response);
  }











  public function add_fitness_clander(Request $request)



  {



    $video_mode_id = $request->video_mode_id;



    $user_id = $request->user_id;







    if (!empty($video_mode_id)) {






      $date = date('Y-m-d');
      $workout_type = DB::table('add_fitnessin_calender')->select('id')->where('user_id', $user_id)->where('completed_date', $date)->where('fitness_id', $video_mode_id)->get();



      if (count($workout_type) != 0) {



        $this->response['msg']              = "already exist";



        $this->response['msg_type']         = "failed";



        $this->response['code']             = 400;



        return response()->json($this->response);
      }



      $workout_type = DB::table('video_mode')->select('*')->where('id', $video_mode_id)->where('show_status', 1)->first();



      $data['fitness_id'] = $workout_type->id;



      $data['user_id'] = $request->user_id;



      $data['completed_date'] = date('Y-m-d');



      $add =   DB::table('add_fitnessin_calender')->insert($data);



      if ($add) {



        $this->response['msg']              = "data added successfully";



        $this->response['msg_type']         = "success";



        $this->response['code']             = 200;
      } else {



        $this->response['msg']              = "something is wrong";



        $this->response['msg_type']         = "failed";



        $this->response['code']             = 400;
      }
    } else {



      $this->response['msg']              = "All input fields are required";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;
    }



    return response()->json($this->response);
  }



  public function get_video_calnder(Request $request) {}



  public function create_group(Request $request)



  {



    // $data = $request->all(); 



    $this->group    =  new Group;



    $this->user    =  new User;



    $insClient['group_name']            = $request->group_name; //filter_var($data['group_name'],FILTER_SANITIZE_STRING);



    $insClient['group_description']     = $request->group_description; //filter_var($data['group_description'],FILTER_SANITIZE_STRING);



    $insClient['created_by']            = $request->created_by; //filter_var($data['created_by'],FILTER_VALIDATE_INT);



    $insClient['user_id']               = $request->user_id;



    if (!empty($request->file('file'))) {



      $img  =   Group::uploadVideo($request->file('file'), 'group');



      $insClient['image'] =  $img;
    }



    $adduser = $request->user_id;



    $allGropuUser = $request->members;



    array_push($allGropuUser, $adduser);



    //print_r($allGropuUser); die;







    if (!empty($allGropuUser)) {



      $insClient['members'] = implode(',', $allGropuUser);
    } else {



      $insClient['members']  = '';
    }







    $add =  $this->group->create($insClient);



    if ($add) {



      $this->response['msg']              = "group created Successfully";



      $this->response['msg_type']         = "success";



      $this->response['code']             = 200;
    } else {



      $this->response['msg']              = "something is wrong";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;
    }



    return response()->json($this->response);
  }







  public function getuser_group(Request $request)



  {



    $user_id = $request->input('user_id');



    if (!empty($user_id)) {



      $total_groupuser   = DB::select("SELECT * from groups where   user_id = $user_id");



      //$total_groupuser   = DB::select("SELECT * from groups where  user_id = $user_id', members)");



      if (!empty($total_groupuser)) {



        foreach ($total_groupuser as $row) {



          $data[] = array(







            'image' =>   url('') . '/group/' . $row->image,



            'group_name' => $row->group_name,



            'group_description' => $row->group_description,



            'created_by' => $row->group_name,



            'group_id' => $row->id,







          );
        }



        $this->response['msg']              = "group found successfully";



        $this->response['msg_type']         = "success";



        $this->response['data']         = $data;



        $this->response['code']             = 200;
      } else {



        $this->response['msg']              = "no group found successfully";



        $this->response['msg_type']         = "success";



        $this->response['data']         = $total_groupuser;



        $this->response['code']             = 200;
      }
    } else {



      $this->response['msg']              = "please enter user id";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;
    }











    return response()->json($this->response);
  }







  public function getGroupCreater(Request $request)



  {



    $groupId = $request->group_id;



    if (!empty($groupId)) {



      $get_groupName = DB::table('groups')



        ->leftjoin('users', 'users.id', '=', 'groups.user_id')



        ->where('groups.id', $groupId)



        ->select('users.name', 'groups.group_name')







        ->first();



      if (!empty($get_groupName)) {



        $this->response['msg'] = 'data found successfully';



        $this->response['groupName'] = $get_groupName->group_name;



        $this->response['createdBy'] = $get_groupName->name;



        $this->response['msg_type']         = "success";



        $this->response['code']             = 200;
      } else {



        $this->response['msg'] = 'data found successfully';



        $this->response['groupName'] = $get_groupName->group_name;



        $this->response['createdBy'] = '';



        $this->response['msg_type']         = "success";



        $this->response['code']             = 200;
      }
    } else {



      $this->response['msg']              = "please enter group id";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;
    }



    return response()->json($this->response);
  }







  public function getallUser(Request $request)
  {



    try {


      $getallUser         = DB::table('users')->where('role', '!=', 1)->where('name', '!=', Null)->get();

      if (count($getallUser) != 0) 
      {

        foreach ($getallUser as $row) 
        {

          $allUser[] = array(

            'group_id'       => $row->id, // user_id
            'group_name'     => $row->name ? $row->name : '', //user name
            'image'          => $row->profile_img ? url('') . $row->profile_img : '', /// user profile image

          );
        }



        $this->response['msg']              = "data found successfully";
        $this->response['msg_type']         = "success";
        $this->response['code']             = 200;
        $this->response['data']             = $allUser;

      } else {



        $this->response['msg']              = "no user found";
        $this->response['msg_type']         = "failed";
        $this->response['code']             = 400;

      }



      return response()->json($this->response);
    } catch (\Exception $e) {



      $this->response['msg']              = $e->getMessage();
      $this->response['msg_type']         = "failed";
      $this->response['code']             = 400;


      return response()->json($this->response);

    }
  }


  public function getlogWeight(Request $request)
  {


    $fitnessId = $request->fitnessId;


    try {



      if (!empty($fitnessId)) 
      {


        $get_fitness_video = DB::table('logweight')->join('video_mode', 'video_mode.id', '=', 'logweight.workout_title')
          ->where('logweight.workout_title', $fitnessId)
          ->select('logweight.*', 'video_mode.video_title as video_title')->get();



        if (count($get_fitness_video)) 
        {


          $this->response['msg']              = "data found successfully";
          $this->response['msg_type']         = "success";
          $this->response['code']             = 200;
          $this->response['data']             = $get_fitness_video;

        } else {


          $this->response['msg']              = "no data found";
          $this->response['msg_type']         = "failed";
          $this->response['code']             = 400;

        }
      } else {


        $this->response['msg']              = "enter fitness id";
        $this->response['msg_type']         = "failed";
        $this->response['code']             = 400;

      }



      return response()->json($this->response);
    } catch (\Exception $e) {



      $this->response['msg']              = $e->getMessage();
      $this->response['msg_type']         = "failed";
      $this->response['code']             = 400;



      return response()->json($this->response);
    }
  }







  public function getlogweightval(Request $request)
  {



    $fitnessId = $request->fitnessId;
    $user_id   = $request->user_id;
    $round     = $request->round;
    $circuit_type  = $request->circuit_type;

    try {



      if (!empty($fitnessId && $round && $circuit_type)) 
      {

        $getData = DB::table('user_logweight')->select('*')->where('user_id', $user_id)->where('fitness_id', $fitnessId)->where('round', $round)->where('circuit_type', $circuit_type)->get();
        $data1 = [];

        //$get = DB::table('logweight')->select('exercise')->where('workout_title', $fitnessId)->where('round', $round)->where('circuit_type', $circuit_type)->first();

        $id =  DB::table('description_mode')->where('video_mode_lastid', $request->fitnessId)->first();

        $get = DB::table('logweight')->select('exercise')->where('description_mode_id', $id->id)->where('round', $round)->where('circuit_type', $circuit_type)->first();

        foreach ($getData as $row1) 
        {

          $data1[] = array("ex" =>  $row1->exercise,"reps"  => $row1->reps,'weight'  => $row1->weight);
        }


        $data = [];

        //   $get = DB::table('logweight')->select('exercise', 'count', 'reps')->where('workout_title', $fitnessId)->where('round', $round)->where('circuit_type', $circuit_type)->first();

        $id =  DB::table('description_mode')->where('video_mode_lastid', $request->fitnessId)->first();
        $get = DB::table('logweight')->select('exercise', 'count', 'reps')->where('description_mode_id', $id->id)->where('round', $round)->where('circuit_type', $circuit_type)->first();

        // dd($get);

        if (!empty($get)) 
        {

          $ex       =  explode(',', $get->exercise);
          $reps       =  explode(',', $get->reps);

          $i = 1;
          foreach ($ex as $key => $row) 
          {
            $count = $i++;
            $data[] = array("ex" => $row,'reps'  => $reps[$key]);
          }
        }



        $finalarray = $data1 + $data;


        $this->response['msg']              = "data found successfully";
        $this->response['msg_type']         = "success";
        $this->response['code']             = 200;
        $this->response['data']             = $finalarray;

      } else {

        $this->response['msg']              = "All input field are required";
        $this->response['msg_type']         = "failed";
        $this->response['code']             = 400;
      }



      return response()->json($this->response);
    } catch (\Exception $e) {

      $this->response['msg']              = $e->getMessage();
      $this->response['msg_type']         = "failed";
      $this->response['code']             = 400;

      return response()->json($this->response);
    }
  }



  public function addlogweight(Request $request)



  {



    try {



      $userId        = $request->user_id;



      $fitnessId     = $request->fitnessId;



      $round         = $request->round;



      $circuit_type  = $request->circuit_type;



      $reps          = $request->reps;



      $weight        = $request->weight;



      $exercise      = $request->exercise;







      $add =   DB::delete('delete from user_logweight where round="' . $round . '" AND circuit_type="' . $circuit_type . '" AND  user_id="' . $userId . '" AND fitness_id="' . $fitnessId . '"');

      for ($i = 0; $i < count($reps); $i++) 
      {

        $add =  DB::table('user_logweight')->insert([

          'user_id'            =>   $request->get('user_id'),
          'fitness_id'         =>   $request->get('fitnessId'),
          'round'              => $request->round,
          'circuit_type'       =>   $request->get('circuit_type'),
          'reps'               =>    $reps[$i],
          'weight'             =>   $weight[$i],
          'exercise'           => $exercise[$i],
          'completed_date'           => date('Y-m-d'),

        ]);
      }



      if ($add) {

        $this->response['msg']              = "data found successfully";
        $this->response['msg_type']         = "success";
        $this->response['code']             = 200;

      } else {

        $this->response['msg']              = "All input field are required";
        $this->response['msg_type']         = "failed";
        $this->response['code']             = 400;
      }



      return response()->json($this->response);
    } catch (\Exception $e) {


      $this->response['msg']              = $e->getMessage();
      $this->response['msg_type']         = "failed";
      $this->response['code']             = 400;

      return response()->json($this->response);
    }
  }







  public function getcircute(Request $request)



  {



    try {



      $userId = $request->user_id;



      $fitness_id = $request->fitness_id;



      $completed_date = $request->completed_date;



      if (!empty($userId)) {



        if (empty($completed_date)) {



          $getData = DB::table('user_logweight')->select('*')->where('user_id', $userId)->where('fitness_id', $fitness_id)->get();
        } else {



          $getData = DB::table('user_logweight')->select('*')->where('completed_date', $completed_date)->where('fitness_id', $fitness_id)->where('user_id', $userId)->get();
        }



        $this->response['msg']              = "data found successfully";



        $this->response['msg_type']         = "success";



        $this->response['code']             = 200;



        $this->response['data']             = $getData;
      } else {



        $this->response['msg']              = "All input field are required";



        $this->response['msg_type']         = "failed";



        $this->response['code']             = 400;
      }



      return response()->json($this->response);
    } catch (\Exception $e) {



      $this->response['msg']              = $e->getMessage();



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;



      return response()->json($this->response);
    }
  }



  public function addlogweight_r(Request $request)



  {



    try {



      $userId        = $request->user_id;



      $fitnessId     = $request->fitnessId;



      $round         = $request->round;



      $circuit_type  = $request->circuit_type;



      $reps          = $request->reps;



      $weight        = $request->weight;



      $exercise      = $request->exercise;



      $reps_r  =  explode(",", $reps);



      $weight_r  =  explode(",", $weight);



      $exercise_r  =  explode(",", $exercise);











      $add =   DB::delete('delete from user_logweight where round="' . $round . '" AND circuit_type="' . $circuit_type . '" AND  user_id="' . $userId . '" AND fitness_id="' . $fitnessId . '"');



      for ($i = 0; $i < count($reps_r); $i++) {



        $add =  DB::table('user_logweight')->insert([



          'user_id'            =>   $request->get('user_id'),



          'fitness_id'          =>   $request->get('fitnessId'),



          'round'              => $request->round,



          'circuit_type'       =>   $request->get('circuit_type'),



          'reps'               =>    $reps_r[$i], //$request->get('group_id'),



          'weight'             =>   $weight_r[$i],



          'exercise'           => $exercise_r[$i],



          'completed_date'           => date('Y-m-d'),







        ]);
      }



      if ($add) {



        $this->response['msg']              = "data found successfully";



        $this->response['msg_type']         = "success";



        $this->response['code']             = 200;
      } else {



        $this->response['msg']              = "All input field are required";



        $this->response['msg_type']         = "failed";



        $this->response['code']             = 400;
      }



      return response()->json($this->response);
    } catch (\Exception $e) {



      $this->response['msg']              = $e->getMessage();



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;



      return response()->json($this->response);
    }
  }







  public function share_post_group(Request $request)
  {
    try {
      $user_id     = $request->input('user_id');
      $post_id     = $request->input('post_id');
      $group_id    = $request->input('group_id');
      $share_by    = $request->input('share_by');
      $shared_user = $request->input('shared_user');
      $type        = $request->input('type');

      if (!empty($user_id && $post_id && $group_id && $share_by && $shared_user)) {

        $sharepost =   DB::table('post_share')->insert([
          'user_id'        => $request->get('user_id'),
          'post_id'        => $post_id,
          'share_status'   => 1,
          'shared_user'    => $shared_user ?  implode(",", $shared_user) : '',
        ]);

        $getData = DB::table('post')->select('*')->where('id', $post_id)->first();

        $update_pro = DB::table('post')->insert([
          'user_id'        => $getData->user_id,
          'selected_type'  => $getData->selected_type,
          'post'           => $getData->post,
          'thumble_img'    => $getData->thumble_img,
          'group_id'       => $group_id,
          'post_img'       => $getData->post_img,
          'status'         => $getData->status,
          'total_like'     => $getData->total_like,
          'share_date_time' => date("Y-m-d H:i:s"),
          'caption'        => $getData->caption,
          'created_at'     => date('Y-m-d H:i:s'),
          'shared_user'    => implode(",", $shared_user),
          'share_by'       => $user_id,
          'group_type'     => $type,
        ]);

        if ($update_pro) {

          $users = DB::table('users')->whereIn('id', $shared_user)->get();

          if (count($users) != 0) {
            foreach ($users as $row) {
              if (!empty($row->fcm_token) && !empty($row->device_type)) {

                $sendData = array(
                  'body'     => 'Shared new post in group',
                  'title'    => 'Share Post',
                  'sound'    => 'Default',
                );

                $this->fcmNotification($row->fcm_token, $sendData);
              }
            }
          }

          $share_count         = DB::table('post_share')->where('post_id', $post_id)->count();

          $this->response['msg']              = "post share successfully";
          $this->response['msg_type']         = "success";
          $this->response['code']             = 200;
          $this->response['total_share']      = $share_count;
        } else {
          $this->response['msg']              = "data found successfully";
          $this->response['msg_type']         = "failed";
          $this->response['code']             = 200;
        }
      } else {
        $this->response['msg']              = "All input field are required";
        $this->response['msg_type']         = "failed";
        $this->response['code']             = 400;
      }

      return response()->json($this->response);
    } catch (\Exception $e) {
      $this->response['msg']              = $e->getMessage();
      $this->response['msg_type']         = "failed";
      $this->response['code']             = 400;
      return response()->json($this->response);
    }
  }



  public function deleteGoal(Request $request)
  {
    $userId = $request->user_id;



    $goalId  = $request->goalId;



    try {



      if (!empty($userId && $goalId)) {



        $delete =  DB::table('goals')->where('id', $goalId)->delete();



        if ($delete) {



          $this->response['msg']              = "goal deleted successfully";



          $this->response['msg_type']         = "success";



          $this->response['code']             = 200;
        } else {



          $this->response['msg']              = "something is wrong";



          $this->response['msg_type']         = "failed";



          $this->response['code']             = 400;
        }
      } else {



        $this->response['msg']              = "All input field are required";



        $this->response['msg_type']         = "failed";



        $this->response['code']             = 400;
      }



      return response()->json($this->response);
    } catch (\Exception $e) {



      $this->response['msg']              = $e->getMessage();



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;



      return response()->json($this->response);
    }
  }











  public function updateGoal(Request $request)



  {



    $userId = $request->user_id;



    $goalId  = $request->goalId;



    try {



      if (!empty($userId && $goalId)) {



        $updated = DB::table('goals')->where('id', $goalId)->where('user_id', $userId)->update([



          'user_id'          =>  $request->get('user_id'),



          'type'             =>  $request->get('type'),



          'goal'             =>  $request->get('goal'),



          'category'         =>  $request->get('category'),



          'title'            =>  $request->get('title'),



          'goal_description' =>  $request->get('goal_description'),



          //'date'             =>  date('d-m-Y'),



          'no_of_workout'             =>  $request->get('no_of_workout')







        ]);



        if ($updated) {



          $this->response['msg']              = "goal updated successfully";



          $this->response['msg_type']         = "success";



          $this->response['code']             = 200;
        } else {



          $this->response['msg']              = "something is wrong";



          $this->response['msg_type']         = "failed";



          $this->response['code']             = 400;
        }
      } else {



        $this->response['msg']              = "All input field are required";



        $this->response['msg_type']         = "failed";



        $this->response['code']             = 400;
      }



      return response()->json($this->response);
    } catch (\Exception $e) {



      $this->response['msg']              = $e->getMessage();



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;



      return response()->json($this->response);
    }
  }











  public function GetUserCircuit(Request $request)



  {







    //@sexysir



    // $circuit_type =  DB::table('logweight')->where('workout_title', $request->fitnessId)->groupBy('circuit_type')->pluck('circuit_type');







    // @rahul



    $id =  DB::table('description_mode')->where('video_mode_lastid', $request->fitnessId)->first();





    $circuit_type =  DB::table('logweight')->where('description_mode_id',  $id->id)->groupBy('circuit_type')->pluck('circuit_type');















    $data = [];



    $data['circuits'] = $circuit_type;







    $this->response['msg']              = "data found successfully";



    $this->response['msg_type']         = "success";



    $this->response['code']             = 200;



    $this->response['data']             = $circuit_type;



    return response()->json($this->response);
  }











  public function GetRound(Request $request)



  {



    //$round =  DB::table('logweight')->where('workout_title', $request->fitnessId)->where('circuit_type', $request->circuit_type)->groupBy('round')->pluck('round');



    $id =  DB::table('description_mode')->where('video_mode_lastid', $request->fitnessId)->first();

    $round =  DB::table('logweight')->where('description_mode_id',  $id->id)->where('circuit_type', $request->circuit_type)->groupBy('round')->pluck('round');



    if (sizeof($round)) {



      $data['rounds'] = $round;



      $this->response['msg']              = "data found successfully";



      $this->response['msg_type']         = "success";



      $this->response['code']             = 200;



      $this->response['data']             = $round;
    } else {



      $this->response['msg']              = "Round Not Found for this Circuit";



      $this->response['msg_type']         = "failed";



      $this->response['code']             = 400;
    }



    return response()->json($this->response);
  }







  public function usersubscriptioncheck(Request $request)



  {



    $users = DB::table('users')->select('payment_status')->where('id', $request->user_id)->first();

    // $users =  DB::table('users')->where('id', $request->user_id)->first();



    $this->response['msg']              = "data found successfully";



    $this->response['msg_type']         = "success";



    $this->response['code']             = 200;



    $this->response['data']             = $users;



    return response()->json($this->response);
  }









  public function sendauthOtp(Request $request)

  {

    $request->validate([

      'email' => 'nullable|email',

      'phone' => 'nullable|string',

    ]);



    $otp = $this->generateOtp();

    $expiresAt = now()->addMinutes(5);



    Otp::create([

      'email' => $request->email,

      'phone' => $request->phone,

      'otp' => Hash::make($otp),

      'expires_at' => $expiresAt,

    ]);





    if ($request->email) {

      Mail::raw("Your OTP is: $otp", function ($message) use ($request) {

        $message->to($request->email)

          ->subject('Your OTP Code');
      });
    }





    if ($request->phone) {
    }



    return response()->json(['message' => 'OTP sent successfully.'], 200);
  }



  public function block_user(Request $request)

  {

    #echo "block-user";die;



    $post_id = $request->post_id;

    if (!auth()->check()) {

      return response()->json(['message' => 'Unauthorized'], 401);
    }

    $loggedInUserId = auth()->id();

    $existing_post = Posts::where('id', $post_id)->first();



    if (!$existing_post) {

      return response()->json(['message' => 'Post not found'], 404);
    }



    $post_user_id = $existing_post->user_id;



    // Block the user

    PostBlock::create([

      'block_user_id' => $post_user_id,

      'block_by' => $loggedInUserId,

    ]);



    $this->response['msg']              = "User blocked successfully";

    $this->response['msg_type']         = "success";

    $this->response['code']             = 200;



    return response()->json($this->response);
  }



  public function report_user(Request $request)

  {

    $post_id = $request->post_id;



    $comments = $request->comments;

    $loggedInUserId = auth()->id();

    $existing_post = Posts::where('id', $post_id)->first();



    if (!$existing_post) {

      return response()->json(['message' => 'Post not found'], 404);
    }



    $post_user_id = $existing_post->user_id;



    // Block the user

    PostReport::create([

      'post_id' => $post_id,

      'comments' => $comments,

      'reported_by' => $loggedInUserId,

    ]);



    $this->response['msg']              = "User Report successfully";

    $this->response['msg_type']         = "success";

    $this->response['code']             = 200;



    return response()->json($this->response);
  }



  public function user_feedback(Request $request)

  {

    $user_id = $request->user_id;

    $content = $request->content;



    if (empty($user_id)) {

      $user_id = auth()->id();
    }





    if (!$content) {

      return response()->json(['message' => 'Please Fill the Feedback'], 404);
    }



    UserFeedback::create([

      'user_id' => $user_id,

      'content' => $content,

    ]);



    $this->response['msg']              = "Feedback save successfully";

    $this->response['msg_type']         = "success";

    $this->response['code']             = 200;



    return response()->json($this->response);
  }





  public function user_unblock(Request $request)

  {

    $user_id = $request->input('user_id');

    $another_userid = $request->input('another_userid');



    $loggedInUserId = empty($user_id) ? auth()->id() : $user_id;



    $isBlocked = DB::table('post_block')->where('block_by', $loggedInUserId)->where('block_user_id', $another_userid)->exists();

    #print_r($isBlocked);die;



    if (!$isBlocked) {

      return response()->json(['status' => 'error', 'message' => 'User is not blocked'], 400);

      //return response()->json(['status' => 'error','msg' => 'User is not blocked','code' => 400], 400);

    }



    DB::table('post_block')->where('block_by', $loggedInUserId)->where('block_user_id', $another_userid)->delete();



    return response()->json([

      'status' => 'success',

      'message' => 'User unblocked successfully'

    ]);



    // return response()->json([

    //   'status' => 'success',

    //   'code' => 200,

    //   'msg' => 'User unblocked successfully'

    // ]);

  }



  public function subscription_pup_sub_check(Request $request)

  {

    #echo "hello";die;

    #Log::channel('subscription')->info('Incoming Subscription Data:', ['request' => $request->all()]);



    $jwtToken           = $request->input('message.data');

    $tokenPayload       = base64_decode($jwtToken);

    $jwtPayload         = json_decode($tokenPayload);

    $notificationType   = json_encode($jwtPayload->subscriptionNotification->notificationType);

    $purchaseToken      = json_encode($jwtPayload->subscriptionNotification->purchaseToken);

    $cleanedString      = substr($purchaseToken, 1, -1);



    #DB::table('pubsup')->updateOrCreate(['description' => $jwtToken]);

    #Log::channel('subscription')->info('Decoded JWT Payload:', ['payload' => $jwtPayload]);

    // DB::table('pubsup')->insert([

    //   'description'     => $jwtToken, // Store full JSON response

    //   'created_at'      => now(),

    // ]);



    if ($notificationType == 2) {

      $SubscriptionManagement = DB::table('provider_subscription')

        ->where(['receipt_data' => $cleanedString])

        ->orderBy('id', 'desc')

        ->first();



      $subscriptionEndDate = Carbon::parse($SubscriptionManagement->subscription_end_date)->addDays(30);

      $subscriptionEndDate = $subscriptionEndDate->format('Y-m-d');



      #Log::channel('subscription')->info('Updated Subscription End Date:', ['subscription_end_date' => $subscriptionEndDate]);



      DB::table('provider_subscription')->updateOrCreate(['receipt_data' => $cleanedString], ['subscription_end_date' => $subscriptionEndDate]);
    }



    if ($notificationType == 4) {

      $SubscriptionManagement = DB::table('provider_subscription')

        ->where(['token' => $purchaseToken])

        ->orderBy('id', 'desc')

        ->first();



      if ($SubscriptionManagement) {

        DB::table('provider_subscription')

          ->where('id', $SubscriptionManagement->id)

          ->update(['plane_expire_status' => 0]);



        #Log::channel('subscription')->info('Updated Plane Expiry Status:', ['status' => 0]);

      }
    }



    // $data = DB::table('inapp_purchse')->insert([

    //     'purchase_receipt'  => $request,

    //     'purchase_token'    => '',

    // ]);



    return $this->sendResponse([], 'data get success');
  }



  public function payinappSubscription(Request $request)

  {

    // Set default subscription end date (30 days from now)

    $endDate = Carbon::now()->addDays(30);



    // If subscription type is 'free', extend subscription to 2 months

    if (!empty($request->subscription_type) && $request->subscription_type == 'free') {

      $endDate = Carbon::now()->addDays(90);
    }



    // Insert subscription data into the database

    $isInserted = DB::table('provider_subscription')->insert([

      'provider_id'               => $request->provider_id,

      'type'                      => $request->type,

      'receipt_data'              => $request->receipt_data,

      'subscription_start_date'   => Carbon::now()->format('Y-m-d'),

      'subscription_end_date'     => $endDate->format('Y-m-d'),

    ]);



    // Handle the result of the insert operation

    if ($isInserted) {

      return response()->json([

        'success' => true,

        'message' => 'Subscription added successfully',

      ], 200);
    } else {

      return response()->json([

        'success' => false,

        'message' => 'Failed to add subscription',

      ], 500);
    }
  }



  public function checksubscriptionprice()

  {

    $dateToCheck        = '2024-06-07';

    $startDate          = Carbon::parse($dateToCheck);

    $thirtyDaysLater    = $startDate->addDays(30);

    $currentDate        = Carbon::now();



    if ($currentDate->greaterThanOrEqualTo($thirtyDaysLater)) {

      return $this->sendResponse(false, 'Data fetched successfully');
    } else {

      return $this->sendResponse(true, 'Data fetched successfully');
    }
  }



  public function deleteAccount(Request $request)

  {

    $loggedInUser = auth()->user();

    if (!$loggedInUser) {

      #return $this->sendResponse(false, 'User not found', 404);

      #return $this->sendResponse([], 'User not found',404);

      return response()->json([

        'success' => false,

        'message' => 'User not found',

      ], 404);
    }



    $loggedInUser->delete();

    return response()->json([

      'success' => true,

      'message' => 'Account deleted successfully',

    ], 200);

    #return $this->sendResponse([], 'Account deleted successfully');





  }


  public function get_video_desc(Request $request)
  {

    $fitness_id  = $request->fitness_id;
    $auth_id = auth()->id();
    $date = $request->date;

    $getDescription = DB::table('description_mode')->where('video_mode_lastid', $fitness_id)->first();
    #print_r($getDescription);die;
    if (!empty($getDescription)) 
    {

      $description_id = $getDescription->id;

      $userExerciseData = DB::table('user_exercise_description')->where('created_by', $auth_id)->where('description_mode_id', $description_id)->whereDate('created_at', $date)->get();

      // if(empty($userExerciseData))
      // {

      // }
      #print_r($userExerciseData);die;
      $exercise_description_arr = $userExerciseData->count() > 0 ? $userExerciseData : DB::table('exercise_description')->where('description_mode_id', $description_id)->get();
      #print_r($userExerciseData);die;
      #$exercise_description_arr = DB::table('exercise_description')->where('description_mode_id', $description_id)->get();
      if ($exercise_description_arr->count() > 0) 
      {

        $descriptionData = (array) $getDescription;

        $grouped = $exercise_description_arr->groupBy('exercise_title');

        // Convert to plain array (optional, for JSON clean formatting)
        $exerciseArray = [];

        $descriptionData['category'] = []; // initialize it first

        foreach ($grouped as $title => $items) 
        {

          $firstItem = $items->first();
          $descriptionData['category'][] = [
              'title'    => $title,
              'notes' => $firstItem->notes,
              'set_status'    => $firstItem->sets_status,
              'reps_status'    => $firstItem->reps_status,
              'rpe_status'    => $firstItem->rpe_status,
              'weight_status'    => $firstItem->weight_status,
              'exercise' => $items->values() // ensures clean indexing
          ];
        }
        
        $this->response['msg']      = "Data found successfully";
        $this->response['msg_type'] = "success";
        $this->response['code']     = 200;
       
        $this->response['data']     = $descriptionData;
      } else {
          $this->response['msg']      = "No description found";
          $this->response['msg_type'] = "failed";
          $this->response['code']     = 404;
      }
      

    }else{

        $this->response['msg']      = "No description found";
        $this->response['msg_type'] = "failed";
        $this->response['code']     = 404;
    }


    return response()->json($this->response);
  }

  public function update_logweight(Request $request)
  {

    $data = $request->all();
    $auth_id = auth()->id();

    if (!empty($data)) 
    {

      $allExercises = [];
      $description_mode_id = null;

        foreach ($data['category'] as $categoryData) 
        {
            foreach ($categoryData['exercise'] as $exerciseData) 
            {
                $description_mode_id = $exerciseData['description_mode_id'];
                DB::table('user_exercise_description')->where('created_by', $auth_id)->where('description_mode_id', $exerciseData['description_mode_id'])->delete();
                $allExercises[] = [
                    'description_mode_id' => $exerciseData['description_mode_id'],
                    'exercise_name'       => $exerciseData['exercise_name'],
                    'exercise_title'      => $exerciseData['exercise_title'],
                    'notes'               => $exerciseData['notes'],
                    'reps'                => $exerciseData['reps'],
                    'rpe'                 => $exerciseData['rpe'],
                    'sets'                => $exerciseData['sets'],
                    'weight'              => $exerciseData['weight'],
                    'reps_status'         => $categoryData['reps_status'],
                    'rpe_status'          => $categoryData['rpe_status'],
                    'sets_status'          => $categoryData['set_status'],
                    'weight_status'       => $categoryData['weight_status'],
                    'created_by'          => $auth_id,
                    'created_at'          => now(),
                    'updated_at'          => now(),
                ];
            }
        }

        DB::table('user_exercise_description')->insert($allExercises);

        $workout_type = DB::table('description_mode')->select('video_mode_lastid')->where('id', $description_mode_id)->first();

        if ($workout_type) {
          $calendarData = [
              'desc_fitness_id'     => $workout_type->video_mode_lastid,
              'user_id'        => $auth_id, // or $request->user_id if passed explicitly
              'completed_date' => date('Y-m-d'),
          ];

          DB::table('add_fitnessin_calender')->insert($calendarData);
        }

        $this->response['msg']      = "Data Saved successfully";
        $this->response['msg_type'] = "success";
        $this->response['code']     = 200;
      

    }


    return response()->json($this->response);
  }
}
