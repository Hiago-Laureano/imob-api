<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth:sanctum")->only(["logout"]);
    }

    public function login(AuthRequest $request){
        $data = $request->validated();
        $firstUser = User::find(1);
        if(! $firstUser){
            $user = User::create(["name" => "Administrator","email" => $data["email"], "password" => Hash::make($data["password"]), "superuser" => true]);
            $token = $user->createToken("auth-token", ["*"])->plainTextToken;
            return response()->json(["message" => "First user created! Use your email and password for future logins", "auth-token" => $token], 200);
        }
        if(Auth::attempt($data)){
            if(! $request->user()->active){
                return response()->json(["message" => "Incorrect login"], 403);
            }
            $token = $request->user()->createToken("auth-token", ["*"])->plainTextToken;
            return response()->json(["message" => "Success", "auth-token" => $token], 200);
        }else{
            return response()->json(["message" => "Incorrect login"], 403);
        }
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return response()->json(["message" => "Success"], 200);
    }
}
