<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Js;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $user = User::where('email', $request->input('email'))->first();
        if ($user && Hash::check($request->input('password'), $user->password)) {
            $apikey = base64_encode(Str::random(40));
            $user["token"] = $apikey;
            $token_expire = date('Y-m-d H:i:s' , strtotime('+150 minutes'));
            $user->token_expire = $token_expire;
            $user->save();
            return response()->json(['status' => 1, 'message' => 'Status OK',  'id_user' => $user['id_user'], 'username' => $user['username'], 'token' => $user->token]);
        } else {
            return response()->json(['status' => 0, 'message' => 'fail'], 401);
        }
    }

    public function logout(Request $request)
    {
        $key = explode(' ',$request->header('Authorization'));
        $token=$key[1];
        $user = User::where('token',$token)->first();
        if ($user["token"] == $token) {
            $user->token = null;
            $user->save();
            return response()->json(['status' => 1, 'message' => 'Token deleted'], 200);
        } else {
            return response()->json(['status' => 0, 'message' => 'Fail.']);
        }
    }
}