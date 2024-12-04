<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $rules = [
                'email' => 'required|email',
                'password' => 'required',
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($validator, $code);
            }
            $credentials = $request->only(['email', 'password']);
            $token = Auth::guard('api')->attempt($credentials);
            if (!$token) {
                return response()->json(['message' => 'error']);
            }
            $user = Auth::guard('api')->user();
            $user->token = $token;
            return response()->json(["message" => $user]);

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function register(Request $request)
    {
        try {
            $rules = [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($validator, $code);
            }
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();
            if ($user) {
                return $this->login($request);
            }
        } catch (\Exception $e) {
            return response()->json([
                "message" => "something went wrong",
            ]);
        }
    }


    public function logout(Request $request){
        try{
            JWTAuth::invalidate($request->token);
            return response()->json(["message" => "User logged out successfully"]);
        }
        catch(JWTException $E){
            return response()->json(["message" => $E->getMessage()]);
        } 
    }

    public function refresh(Request $request){
        try{
            $new_token = JWTAuth::refresh($request->token);
            return response()->json(["New token" => $new_token]);
        }
        catch(\Exception $e){
            return response()->json(["message" => "something went wrong"]);
        }
        
    }
}
