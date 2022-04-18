<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use Firebase\Auth\Token\Exception\InvalidToken;
use Kreait\Firebase\Auth;
use Kreait\Firebase\Exception\InvalidArgumentException;

class NewPasswordController extends Controller
{
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    public function verifyPhone(Request $request)
    {
        $validator = Validator::make($request->only('phone_number'), [
            'phone_number' => array('required', 'size:10', 'regex:/((09|03|07|08|05)+([0-9]{8})\b)/')   
        ]);
        
        if ($validator->fails()) {
            return response(['message' => $validator->errors()->all()], 422);
        }

        $user = User::where(['phone_number' => $request->input('phone_number')])->first();
        if ($user){
            return response(['message' => 'User valid']);
        }

        return response(['message' => 'User not found !!']);
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => array('required', 'string', 'regex:/(^[\w-]*\.[\w-]*\.[\w-]*$)/'),
            'password' => 'required|string|min:8',
            'password_confirmation'=> 'required|same:password'
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->all()], 422);
        }

        // verify token
        $idTokenString = $request->input('token');
        try {
            $verifiedIdToken = $this->auth->verifyIdToken($idTokenString);
        } catch (InvalidToken $e) {
            return response(['message' => "Something wrong"]);
        } catch (InvalidArgumentException $e) {
            return response(['message' => "Something wrong"]);
        }
        
        $phone = $verifiedIdToken->claims()->get('phone_number');
        if ($phone) {
            $phone = substr_replace($phone, "0", 0, 3);
            
            $user = User::where(['phone_number' => $phone])->first();
            
            // update password
            $user->update([
                'password' => Hash::make($request->input('password'))
            ]);
            
            return response(['message' => 'Password reset successfully']);
        }

        return response(['message' => 'User not found!']);
    }
}
