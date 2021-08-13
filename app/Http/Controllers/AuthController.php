<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     *  The register method for user account registration
     */
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name'  => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:8'
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password'])
        ]);

        if ( $user ) 
        {
            $token = $user->createToken('myAppToken')->plainTextToken;
            
            return response([
                'status' => 201,
                'message' => "Account created successfully",
                'user' => $user,
                'token' => $token
            ], 201);
        } 
        else 
        {
            return response([
                'status' => '400',
                'message' => 'The request was invalid'
            ], 400);
        }
    }

    /**
     *  The login method for user login
     */
    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|email|',
            'password' => 'required'
        ]);

        $user = User::where('email', $fields['email'])->first();

        if ( !$user || !Hash::check($fields['password'], $user->password) ) 
        {
            return response([
                'status' => 401,
                'message' => "Invalid credentials"
            ], 401);      
        } 
        else 
        {
            $token = $user->createToken('myAppToken')->plainTextToken;

            return response([
                'status' => 200,
                'message' => "Logged in successfully",
                'user' => $user,
                'token' => $token
            ], 200);

        }
    }

    /**
     *  The logout method for user logout
     */
    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();

        return response([
            'status' => 200,
            'message' => "Logged out"
        ], 200);
    }
}
