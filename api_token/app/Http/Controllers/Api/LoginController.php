<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\SessionUser;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class LoginController extends Controller
{
    public function login(Request $request)
    {
        $dataCheckLogin = [
            'email' => $request->email,
            'password' => $request->password
        ];
        //B1 xác thực user có tài khoản
        if (auth()->attempt($dataCheckLogin) ){
            $checkTokenExit = SessionUser::where('user_id',auth()->id())->first();
            if (empty($checkTokenExit)){
                $userSession = SessionUser::create([
                    'token'=> Str::random(40),
                    'refresh_token'=>Str::random(40),
                    'token_expried'=>date('Y-m-d H:i:s', strtotime('+30 day')),
                    'refresh_token_expried'=>date('Y-m-d H:i:s', strtotime('+360 day')),
                    'user_id'=>auth()->id()
                ]);
            }else{
                $userSession = $checkTokenExit;
            }
            return response()->json([
                'code'=> 200,
                'data'=> $userSession,
            ],200);
        }else{
            return response()->json([
                'code'=> 401,
                'message'=> 'userName or Password false?',
            ],401);
        }
    }
    public function refreshToken(Request $request){
        $token = $request->header('token');
        $checkTokenIsValid = SessionUser::where('token',$token)->first();
        if (!empty($checkTokenIsValid)){
            if ($checkTokenIsValid->token_exptied < time() ){
                $checkTokenIsValid->update([
                    'token'=> Str::random(40),
                    'refresh_token'=>Str::random(40),
                    'token_expried'=>date('Y-m-d H:i:s', strtotime('+30 day')),
                    'refresh_token_expried'=>date('Y-m-d H:i:s', strtotime('+360 day'))
                ]);
            }
        }
        $dataSession = SessionUser::find($checkTokenIsValid->id);
        return response()->json([
            'code'=> 200,
            'data'=> $dataSession,
            'message'=> 'refresh token sucess?',
        ],200);
    }
    public function deleteToken(Request $request){
        $token = $request->header('token');
        $checkTokenIsValid = SessionUser::where('token',$token)->first();
        if (!empty($checkTokenIsValid)){
            $checkTokenIsValid->delete();
        }
        return response()->json([
            'code'=> 200,
            'message'=> 'delete token sucess?',
        ],200);
    }
}
