<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);
    

        $admin = Admin::where('username', $request->username)
            ->orWhere('email', $request->username)
            ->first();
    
        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }
    
        $token = $admin->createToken('admin-token')->plainTextToken;
    
        return response()->json([
            'token' => $token,
            'admin' => [
                'id'       => $admin->id,
                'username' => $admin->username,
                'email'    => $admin->email
            ]
        ]);
    }

  
    public function showRegister()
    {
        return view('auth.register');
    }


    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:admins',
            'email'    => 'nullable|email|unique:admins',
            'password' => 'required|confirmed|min:6',
        ]);

        $admin = Admin::create([
            'username' => $request->username,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);


        $token = $admin->createToken('admin-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'admin' => [
                'id'       => $admin->id,
                'username' => $admin->username,
                'email'    => $admin->email
            ]
        ]);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }


    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out']);
    }
}
