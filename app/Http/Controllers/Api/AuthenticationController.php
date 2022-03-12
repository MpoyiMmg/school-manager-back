<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthenticationController extends Controller
{
    public function register(Request $request) {
        $validated = Validator::make($request->all(), [
            "name" => "required|string|max:20",
            "email" => "required|email|unique:users,email",
            "password" => "required|string|min:6|confirmed"
        ]); 

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()], 422);
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json([
            'user' => $user,
            'token' => $user->createToken('tokens')->plainTextToken
        ]);
    }

    public function signin(Request $request) {

        $validated = Validator::make($request->all(), [
            "email" => "required|email",
            "password" => "required|string|min:6"
        ]);


        if($validated->fails()) {
            return response()->json([
                'error' => $validated->errors()
            ], 422);
        }

        if (!Auth::attempt($request->all())) {
            return response()->json([
                'error' => "Credentials not match"
            ], 400);
        }
        return response()->json([
            'token' => auth()->user()->createToken("API Token")->plainTextToken
        ]);
    }

    public function signout() {
        auth()->user()->tokens()->delete();

        return response()->json([
            'message' => 'Tokens revoked'
        ]);
    }
}
