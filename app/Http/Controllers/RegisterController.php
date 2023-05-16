<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BassController;
use App\User;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends BassController
{
    public function register(Request $request)
    {
        $rules =$this->getRulles();
        $validator=validator($request->all(),$rules);
        if ($validator->fails())
        {
            return $this ->sendError('validator error',$validator->errors());
        }
        $input=$request->all();
        $input['password']= Hash::make($input['password']);
        $user=User::create($input);
        $success['token']=$user->createToken('hasan')->accessToken;
        return $this->sendResponse( $success,'User Register successfully');

    }
    public function login(Request $request)
    {

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $success['token'] = $user->createToken('hasan')->accessToken;
            return $this->sendResponse( $success,'User login successfully');
        } else {
            return $this->sendError('Check your input', ['error' => 'unauthorised']);
        }
    }
    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();
        return response()->json(['message'=>'thank you for using our app came back later']);
    }





    public function getRulles()
    {
        return $rules =[

            'name'=>'required|unique:users,name',
            'email'=>'required|email|unique:users,email',
            'password'=>'required',
        ];
    }
}
