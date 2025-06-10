<?php

namespace App\Http\Middleware;
use DB;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BearerTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the request has the Authorization header
        if ($request->hasHeader('Authorization')) {
            $token = $request->bearerToken();
            
            
           
            $existingTokens = DB::table('login_histories')
    ->where('token',  $token)
   
    ->first();
    

            if(empty($existingTokens )){
                return response()->json([
                'msg' => 'Authorization token is missing',
                'msg_type' => 'failed',
                'code' => 403
            ], 200);
                
            }
            // You can also verify or log the token here if needed
        } else {
            return response()->json([
                'msg' => 'Authorization token is missing',
                'msg_type' => 'failed',
                'code' => 403
            ], 200);
        }
        auth()->loginUsingId($existingTokens->user_id);
        return $next($request);
    }
}
