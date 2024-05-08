<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use App\Helpers\AppHelper;

class AuthController extends Controller
{
    public function mobile_verification(request $request)
    {
        // return json_encode($request->contact);
        $validator = Validator::make($request->all(), [
            'contact' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()->all()], 422);
        }

        $contact = $request->contact;
        $otp = Hash::make('1234');
        $data = User::where('contact', $contact)->get();
        // 	return json_encode($request->contact);
        if ($data->count() == 0) {
            $user = new User();
            $user->contact = $contact;
            $user->password = $otp;
            $user->save();
        } else {
            $user = User::where('contact', $contact)->update(['password' => $otp]);
        }
        $msg = "Use $otp as your OTP for shvetdhardhara account verification. This is confidential. Please, do not share this with anyone. Webixun infoways PVT LTD ";
        // $obj=new  ComponentConfig();
        // $image_data= $obj->send_sms($contact,$msg);

        $response = ['status' => true, 'msg' => 'ok'];
        return json_encode($response);
    }

    public function otp_verification(request $request)
    {
        $validator = Validator::make($request->all(), [
            'contact' => 'required',
            'otp' => 'required|digits:4',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()->all()], 422);
        }

        $user = User::where('contact', $request->contact)->first();
        // return $user[0]->password;
        if ($user) {
            if (Hash::check($request->otp, $user->password)) {
                $token = $user->createToken('api')->accessToken;
                // return $token;
                if ($user->first_name == '') {
                    $response = ['msg' => 'ok', 'token' => $token, 'user_type' => 'register', 'usr' => $user->id];
                } else {
                    $response = ['msg' => 'ok', 'token' => $token, 'user_type' => 'login', 'usr' => $user->id];
                }

                return response($response, 200);
            } else {
                $response = ['msg' => 'Password mismatch'];
                return response($response, 422);
            }
        } else {
            $response = ['msg' => 'User does not exist'];
            return response($response, 422);
        }
        echo json_encode($response);
    }

    public function logout(request $request)
    {
        if (Auth::check()) {
            Auth::user()
                ->token()
                ->revoke();
            $response['status'] = true;
            $response['msg'] = 'Logout Successfull!';
            return json_encode($response);
        } else {
            $response['status'] = false;
            $response['msg'] = 'Failed!';
            return json_encode($response);
        }
    }
}
