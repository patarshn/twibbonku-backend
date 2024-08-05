<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends BaseController
{
    public function register(Request $request)
    {
        $formData = [
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password' => $request->password,
            'username' => $request->username,
        ];
  
        $formData['password'] = bcrypt($request->password);
  
        $user = User::create($formData);        

        return $this->sendCreatedResponse($user, "Success register");
          
    }
  
    public function login(Request $request)
    {
        $credentials = [
            'email'    => $request->email,
            'password' => $request->password
        ];
  
        if (Auth::attempt($credentials)) 
        {
            $token = Auth::user()->createToken('passportToken')->accessToken;
            
            $user = Auth::user();
            $user->jwt = $token;

            return $this->sendSuccessResponse($user, "Success login");
        }
  
        return $this->sendErrorUnauthorizeResponse(null, "Unauthorized", null);
  
    }

    public function getProfile(Request $request)
    {   
        $data = $request->user();

        $user = User::find($data->id);
        return $this->sendSuccessResponse($data, "Success get profile");
    }

    public function updateProfile(Request $request)
    {   
        $data = $request->user();

        $user = User::find($data->id);
        if(!$user) return $this->sendErrorBadParamsResponse(null, "Error user not found", null);
        $user->full_name = $request->full_name;
        $user->username = $request->username;
        $user->bio = $request->bio;
        $user->social_media = $request->social_media;
        

        if(!$user->save()) return $this->sendErrorInternalResponse(null, "Error save data", null);

        return $this->sendSuccessResponse($user, "Success update profile");
    }

    public function updatePassword(Request $request)
    {   
        $data = $request->user();

        $user = User::find($data->id);
        if(!$user) return $this->sendErrorBadParamsResponse(null, "Error user not found", null);
        $user->password = bcrypt($request->password);

        if(!$user->save()) return $this->sendErrorInternalResponse(null, "Error save data", null);

        return $this->sendSuccessResponse($user, "Success change password");
    }

    public function softDeleteProfile(Request $request)
    {   
        $data = $request->user();

        $user = User::find($data->id);
        if(!$user) return $this->sendErrorBadParamsResponse(null, "Error user not found", null);

  
        if (!Hash::check($request->password, $user->password)) return $this->sendErrorUnauthorizeResponse(null, "Unauthorized", null);
     
        if(!$user->delete()) return $this->sendErrorInternalResponse(null, "Error save data", null);

        return $this->sendSuccessResponse($user, "Success delete user");
    }

}
