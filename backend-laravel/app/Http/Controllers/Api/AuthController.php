<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
class AuthController extends Controller
{
    public function register(Request $request){
        $validated = $request->validate([
            "name" => "required|string|max:255",
            "phone"=> "required|string|max:20 ",
            "email"=> "required|email",
            "password"=>"required|string|min:8",
            "dob"=>"required|date",
            "address"=>"required|string|max:255",
            "profile_image"=>"nullable|string|max:255",
        ]);

        $user=User::create($validated);

        $token=$user->createToken($user->name)->plainTextToken;

        return response()->json([
            "message" => "User created Successfully",
            "user" =>$user,
            "token" => $token,
        ],201);
    }

    

    public function login(Request $request){
        $validated = $request->validate([
            "email" => "required|email",
            "password" => "required|string|min:8",
        ]);

        $user=User::where("email",$validated["email"])->first();
        if(!$user || !Hash::check($validated["password"],$user->password)){
            return response()->json([
                "message" => "Email or password is incorrect",
            ],401);
        }

        $token=$user->createToken($user->name)->plainTextToken;

        return response()->json([
            "message" => "Login successful",
            "user" => $user,
            "token" => $token,
        ],200);
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            "message" => "Logout successful",
        ],200);
    }
}
