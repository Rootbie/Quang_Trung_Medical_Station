<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Authentication
        $user = User::where(['phone_number' => $request->input('phone_number')])->first();
        if (!$user) {
            return response(['message' => 'User not found !!'], 401);
        }

        if (!Auth::attempt($request->only('phone_number', 'password'))) {
            return response(['message' => 'Wrong password'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('token')->plainTextToken;
        $cookie = cookie('jwt', $token, 60 * 24);

        return response(['message' => 'Login successfully'])->withCookie($cookie);
    }

    public function me()
    {
        return Auth::user();
    }

    public function logout()
    {
        $cookie = Cookie::forget('jwt');
        Auth::user()->tokens()->delete();

        return response(['message' => 'Logout successfully'])->withCookie($cookie);
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string',
            'password' => 'required|string|min:8',
            'password_confirmation' => 'required|same:password'
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->all()], 422);
        }

        // update password
        $user = Auth::user();
        if (!Hash::check($request->input('old_password'), $user->password)) {
            return response(['message' => 'Old password not match'], 400);
        } else {
            $user->password = Hash::make($request->input('password'));
            $user->save();

            $cookie = Cookie::forget('jwt');
            Auth::user()->tokens()->delete();

            return response(['message' => 'Password successfully updated'], 200)->withCookie($cookie);
        }
    }
}
