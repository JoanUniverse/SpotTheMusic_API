<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class TokenControl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->header('Authorization')) { 
            $key = explode(' ',$request->header('Authorization'));
            $token = $key[1];
            $ara = date("Y-m-d H:i:s");
            $user = User::where('token', $token)->first();
            if(!empty($user)){
                if($user->token_valid_fins > $ara){
                    $limit = date("Y-m-d H:i:s",strtotime('+30 minutes'));
                    $user->token_expire = $limit;
                    $user->save();
                    return $next($request);
                } else{
                    return response()->json(['status' => 0, 'error' => 'Time expired'], 401);
                }
            } else {
                return response()->json(['status' => 0, 'error' => 'No authorized'], 401);
            }
        } else {
            return response()->json(['status' => 0, 'error' => 'Token not found'], 401);
        }
    }
}