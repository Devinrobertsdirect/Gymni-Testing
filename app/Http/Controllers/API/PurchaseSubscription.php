<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Log; // Add this for logging

class PurchaseSubscription extends Controller
{
    // public function purchase(Request $request){
    //     Log::info('Purchase API hit', ['request' => $request->all()]); // Log the request data

    //     // Validate the request data
    //     $validator = Validator::make($request->all(), [
    //         'purchase_token' => 'required|string',
    //       //  'order_id' => 'required|string',
    //         'user_id' => 'required|integer|exists:users,id',
    //         'type' => 'required',
    //     ]);

    //     if ($validator->fails()) {
    //         Log::error('Validation failed', ['errors' => $validator->errors()]);
    //         return response()->json([
    //             'message' => 'Validation failed',
    //             'errors' => $validator->errors(),
    //             'success' => false,
    //             'code' => 422,
    //         ], 422);
    //     }

    //     $subs_plan_start = Carbon::now()->format('Y-m-d');
    //     $sub_end_date = Carbon::now()->addDays(30)->format('Y-m-d');

    //     try {
    //         // Update user's subscription details
    //         $updateUser = DB::table('users')->where('id', $request->user_id)->update([
    //             'payment_status' => '1', 
    //             'subs_plan_end' => $sub_end_date,
    //             'purchase_token' => $request->purchase_token,
    //             'subs_plan_start' => $subs_plan_start,
    //              'subscription_status' => $request->type,
                
    //         ]);

    //         // if (!$updateUser) {
    //         //     throw new \Exception('User update failed');
    //         // }

    //         // Insert into tbl_subscription_purches
    //         $insertSubscription = DB::table('tbl_subscription_purches')->insert([
    //             'user_id' => $request->user_id,
    //             'device_type' => $request->device_type,
    //             'plan_id' => $request->plan_id, // Ensure $request has plan_id
    //             'subscription_start_date' => $subs_plan_start,
    //             'token' => $request->purchase_token,
    //             'order_id' => $request->order_id,
    //             'subscription_end_date' => $sub_end_date,
    //             'plane_expire_status' => 1,
    //             'type' => $request->type,
    //         ]);

    //         if (!$insertSubscription) {
    //             throw new \Exception('Subscription insertion failed');
    //         }

    //         // Insert into subscription_purchase_app
    //         $data = [
    //             'device_type' => $request->device_type,
    //             'purchase_token' => $request->purchase_token,
    //             'order_id' => $request->order_id,
    //             'userId' => $request->user_id,
    //             'created_at' => Carbon::now(),
    //         ];

    //         $add = true; //DB::table('subscription_purchase_app')->insert($data);

    //         if ($add) {
    //             return response()->json([
    //                 'message' => 'Subscription purchase successfully',
    //                 'success' => true,
    //                 'code' => 200,
    //             ], 200);
    //         } else {
    //             throw new \Exception('Final insertion failed');
    //         }

    //     } catch (\Exception $e) {
    //         Log::error('Exception occurred', ['exception' => $e->getMessage()]);
    //         return response()->json([
    //             'message' =>  $e->getMessage(),
    //             'success' => false,
    //             'code' => 500,
    //         ], 500);
    //     }
    // }
// public function purchase(Request $request) {
//     Log::info('Purchase API hit', ['request' => $request->all()]);

  
//     $validator = Validator::make($request->all(), [
//         'purchase_token' => 'required|string',
//         'user_id' => 'required|integer|exists:users,id',
//         'type' => 'required|string',
//     ]);

//     if ($validator->fails()) {
//         Log::error('Validation failed', ['errors' => $validator->errors()]);
//         return response()->json([
//             'message' => 'Validation failed',
//             'errors' => $validator->errors(),
//             'success' => false,
//             'code' => 422,
//         ], 422);
//     }

//     $subs_plan_start = Carbon::now()->format('Y-m-d');
//     $sub_end_date = Carbon::now()->addDays(30)->format('Y-m-d');

//     try {
      
//         $existingSubscription = DB::table('tbl_subscription_purches')
//             ->where('user_id', $request->user_id)
//             ->first();

//         if ($existingSubscription) {
           
//             DB::table('tbl_subscription_purches')->where('user_id', $request->user_id)->update([
//                 'subscription_start_date' => $subs_plan_start,
//                 'subscription_end_date' => $sub_end_date,
//                 'token' => $request->purchase_token,
//                 'order_id' => $request->order_id,
//                 'plane_expire_status' => 1,
//                 'type' => $request->type,
//             ]);
//         } else {
           
//             DB::table('tbl_subscription_purches')->insert([
//                 'user_id' => $request->user_id,
//                 'device_type' => $request->device_type,
//                 'plan_id' => $request->plan_id,
//                 'subscription_start_date' => $subs_plan_start,
//                 'token' => $request->purchase_token,
//                 'order_id' => $request->order_id,
//                 'subscription_end_date' => $sub_end_date,
//                 'plane_expire_status' => 1,
//                 'type' => $request->type,
//             ]);
//         }

//         DB::table('users')->where('id', $request->user_id)->update([
//             'payment_status' => '1',
//             'subs_plan_end' => $sub_end_date,
//             'purchase_token' => $request->purchase_token,
//             'subs_plan_start' => $subs_plan_start,
//             'subscription_status' => $request->type,
//         ]);


//         $data = [
//             'device_type' => $request->device_type,
//             'purchase_token' => $request->purchase_token,
//             'order_id' => $request->order_id,
//             'userId' => $request->user_id,
//             'created_at' => Carbon::now(),
//         ];


//         return response()->json([
//             'message' => 'Subscription purchase successfully',
//             'success' => true,
//             'code' => 200,
//         ], 200);

//     } catch (\Exception $e) {
//         Log::error('Exception occurred', ['exception' => $e->getMessage()]);
//         return response()->json([
//             'message' => $e->getMessage(),
//             'success' => false,
//             'code' => 500,
//         ], 500);
//     }
// }

public function purchase(Request $request) {
    Log::info('Purchase API hit', ['request' => $request->all()]);

    $validator = Validator::make($request->all(), [
        'purchase_token' => 'required|string',
        'user_id' => 'required|integer|exists:users,id',
        'type' => 'required|string',
    ]);

    if ($validator->fails()) {
        Log::error('Validation failed', ['errors' => $validator->errors()]);
        return response()->json([
            'message' => 'Validation failed',
            'errors' => $validator->errors(),
            'success' => false,
            'code' => 422,
        ], 422);
    }

    $subs_plan_start = Carbon::now()->format('Y-m-d');
    $sub_end_date = Carbon::now()->addDays(30)->format('Y-m-d');
    $maxActiveSessions = null; // Default value

  
    switch ($request->type) {
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

    try {
       
        $existingSubscription = DB::table('tbl_subscription_purches')
            ->where('user_id', $request->user_id)
            ->first();

        if ($existingSubscription) {
            DB::table('tbl_subscription_purches')->where('user_id', $request->user_id)->update([
                'subscription_start_date' => $subs_plan_start,
                'subscription_end_date' => $sub_end_date,
                'token' => $request->purchase_token,
                'order_id' => $request->order_id,
                'plane_expire_status' => 1,
                'type' => $request->type,
            ]);
        } else {
            DB::table('tbl_subscription_purches')->insert([
                'user_id' => $request->user_id,
                'device_type' => $request->device_type,
                'plan_id' => $request->plan_id,
                'subscription_start_date' => $subs_plan_start,
                'token' => $request->purchase_token,
                'order_id' => $request->order_id,
                'subscription_end_date' => $sub_end_date,
                'plane_expire_status' => 1,
                'type' => $request->type,
            ]);
        }

       
        DB::table('users')->where('id', $request->user_id)->update([
            'payment_status' => '1',
            'subs_plan_end' => $sub_end_date,
            'purchase_token' => $request->purchase_token,
            'subs_plan_start' => $subs_plan_start,
            'subscription_status' => $request->type,
        ]);

    
$existingTokens = DB::table('login_histories')
    ->where('user_id', $request->user_id)
    // ->orderBy('created_at', 'desc') // Get the latest first
    ->get();
 $bearerToken = $request->bearerToken();
 
 
 
 
    if (!$existingTokens->contains('token', $request->bearerToken())) {
   
    DB::table('login_histories')->insert([
        'user_id' => $request->user_id,
        'token' => $request->bearerToken(),
        'created_at' => Carbon::now(),
    ]);
} 
 
 


 


if ($existingTokens->count() > $maxActiveSessions) {
    // Keep the latest token (which could be the new one)
    $tokensToKeep = $existingTokens->slice(0, $maxActiveSessions)->pluck('token');

    // Remove any token that is not in the tokens to keep
    DB::table('login_histories')
        ->where('user_id', $request->user_id)
        ->whereNotIn('token', $tokensToKeep)
        ->delete();
}


        // Log successful purchase
        return response()->json([
            'message' => 'Subscription purchase successfully',
            'success' => true,
            'code' => 200,
        ], 200);

    } catch (\Exception $e) {
        Log::error('Exception occurred', ['exception' => $e->getMessage()]);
        return response()->json([
            'message' => $e->getMessage(),
            'success' => false,
            'code' => 500,
        ], 500);
    }
}

    
    // public function pubSubService(Request $request){
        
    //                  $jwtToken = 'HViaWktbmV0d29yay5jb20iLCJleHAiOjE1MzYxNTI0MDAsImlhdCI6MTUzNTU0NzYwMH0.'.$request->input('message.data');
    //         $tokenParts = explode(".", $jwtToken);
    //         $tokenHeader = base64_decode($tokenParts[0]);
    //         $tokenPayload = base64_decode($tokenParts[1]);
    //         $jwtHeader = json_decode($tokenHeader);
    //         $jwtPayload = json_decode($tokenPayload);
    //         $notificationType=json_encode($jwtPayload->subscriptionNotification->notificationType);
    //         $purchaseToken=json_encode($jwtPayload->subscriptionNotification->purchaseToken);
    //         $cleanedString = substr($purchaseToken, 1, -1);
    //       if($notificationType==2){
    //             $SubscriptionManagement = User::query()->where(['purchase_token' => $cleanedString])->first();
    //             $subscriptionEndDate = Carbon::parse($SubscriptionManagement->subs_plan_end)->addDays(30);
    //             $subscriptionEndDate = $subscriptionEndDate->format('Y-m-d');
    //         $data = User::where('purchase_token', $cleanedString)
    //             ->update([
    //               'subs_plan_end' => $subscriptionEndDate,
    //          ]);
                
    //         }
    //             $data = DB::table('pubsup')->insertGetId([
    //             'description' => $request,
    //             'user_id' => '',
    //             ]);
                
    // }
                
                
     public function pubSubService(Request $request) {
    $jwtToken = 'HViaWktbmV0d29yay5jb20iLCJleHAiOjE1MzYxNTI0MDAsImlhdCI6MTUzNTU0NzYwMH0.' . $request->input('message.data');
    $tokenParts = explode(".", $jwtToken);
    $tokenHeader = base64_decode($tokenParts[0]);
    $tokenPayload = base64_decode($tokenParts[1]);
    $jwtHeader = json_decode($tokenHeader);
    $jwtPayload = json_decode($tokenPayload);

    $notificationType = $jwtPayload->subscriptionNotification->notificationType;
    $purchaseToken = $jwtPayload->subscriptionNotification->purchaseToken;
    $cleanedString = substr($purchaseToken, 1, -1);

    if ($notificationType == 2) {
        $subscriptionManagement = User::query()->where(['purchase_token' => $cleanedString])->first();

        if ($subscriptionManagement) {
            switch ($subscriptionManagement->plan_type) {
                case 'freeplan':
                    $subscriptionEndDate = null;
                    break;

                case 'Gym_Annual':
                case 'Premium_Annual':
                    $subscriptionEndDate = Carbon::now()->addYear()->format('Y-m-d');
                    break;

                default:
                    \Log::warning("Unexpected plan type for purchase token: {$cleanedString}");
                    return response()->json(['error' => 'Unexpected plan type'], 400);
            }

            User::where('purchase_token', $cleanedString)->update([
                'subs_plan_end' => $subscriptionEndDate,
            ]);
        }
    } elseif ($notificationType == 4) {
        User::where('purchase_token', $cleanedString)->update([
            'payment_status' => '0',
            'subs_plan_end' => Carbon::now()->format('Y-m-d'),
            'subscription_status' => 'canceled'
        ]);
    }

    DB::table('pubsup')->insert([
        'description' => json_encode($request->all()),
        'user_id' => '',
    ]);

    return response()->json(['success' => true]);
}

 

// $data now contains the ID of the newly inserted record

         
     /** Get Last inserted id **/    
         
         
         
         
        //   return response()->json([
        //             'message' => 'Subscription purchase successfully',
        //             'success' => true,
        //             'code' => 200,
        //         ], 200);
                
              
   
    
    
//  public function updateSubscription(Request $request)
// {
//     // Validate input
//     $validator = Validator::make($request->all(), [
//         'id' => 'required|exists:users,id',
//         'payment_id' => 'required|string',
//         'type' => 'required|in:freeplan,premium_monthly,premium_annual,gym_plan_annual',
//     ]);

//     if ($validator->fails()) {
//         return response()->json([
//             'success' => false,
//             'message' => $validator->errors(),
//         ], 400);
//     }

//     DB::beginTransaction();

//     try {
//         // Update subscription_status in users table
//         $updateResult = DB::table('users')
//             ->where('id', $request->id)
//             ->update(['subscription_status' => $request->type]);

//         // Check if the user was updated
//         if ($updateResult === 0) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'No changes were made to the user.',
//             ], 400);
//         }

//         // Check if payment_id already exists
//         $existingSubscription = DB::table('tbl_subscription_purches')->where('id', $request->payment_id)->first();

//         if ($existingSubscription) {
//             // Update existing subscription
//             $updateResult = DB::table('tbl_subscription_purches')
//                 ->where('id', $request->payment_id)
//                 ->update([
//                     'type' => $request->type,
                  
//                 ]);

//             if (!$updateResult) {
//                 throw new \Exception('Failed to update subscription purchase.');
//             }
//         } else {
//             // Insert new subscription if it does not exist
//             $insertResult = DB::table('tbl_subscription_purches')->insert([
//              'id' => $request->payment_id,
//              'type' => $request->type,
               
//          ]);

//             if (!$insertResult) {
//                 throw new \Exception('Failed to record subscription purchase.');
//             }
//         }

//         DB::commit();

//         return response()->json([
//             'success' => true,
//             'message' => 'Subscription updated successfully.',
//         ], 200);
        
//     } catch (\Exception $e) {
//         DB::rollBack();
//         return response()->json([
//             'success' => false,
//             'message' => $e->getMessage(),
//         ], 500);
//     }
// }





public function updateSubscription(Request $request)
{
    // Validate input
    $validator = Validator::make($request->all(), [
        'id' => 'required|exists:users,id',
        'payment_id' => 'required|string',
        'type' => 'required|in:freeplan,premium_monthly,premium_annual,gym_plan_annual',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => $validator->errors(),
        ], 400);
    }

    DB::beginTransaction();

    try {
        // Update subscription_status in users table
        $updateUserResult = DB::table('users')
            ->where('id', $request->id)
            ->update(['subscription_status' => $request->type]);

        if ($updateUserResult === 0) {
            return response()->json([
                'success' => false,
                'message' => 'No changes were made to the user.',
            ], 400);
        }

        // Check if payment_id already exists
        $existingSubscription = DB::table('tbl_subscription_purches')->where('id', $request->payment_id)->first();

        if ($existingSubscription) {
            // Log existing type for debugging
            Log::info('Existing subscription found', ['existing_type' => $existingSubscription->type]);

            // Update existing subscription
            if ($existingSubscription->type !== $request->type) {
                $updateSubscriptionResult = DB::table('tbl_subscription_purches')
                    ->where('id', $request->payment_id)
                    ->update([
                        'type' => $request->type,
                       
                    ]);

                if ($updateSubscriptionResult === 0) {
                    throw new \Exception('Failed to save subscription purchase.');
                }
            } else {
                Log::info('No changes required for subscription purchase', [
                    'payment_id' => $request->payment_id,
                    'type' => $request->type,
                ]);
            }
        } else {
            // Insert new subscription if it does not exist
            $insertResult = DB::table('tbl_subscription_purches')->insert([
                'id' => $request->payment_id,
                'type' => $request->type,
             
            ]);

            if (!$insertResult) {
                throw new \Exception('Failed to record subscription purchase.');
            }
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Subscription save successfully.',
        ], 200);
        
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
        ], 500);
    }
}


}




