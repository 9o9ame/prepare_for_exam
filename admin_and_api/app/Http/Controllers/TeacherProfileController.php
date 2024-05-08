<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentProfile;
use App\Models\StateCity;
use App\Models\School;
use App\Models\Teacher;
use App\Models\Classs;
use App\Models\Country;
use App\Models\Exam;
use App\Models\Subject;
use App\Models\Board;
use App\Models\Exam_Subject;
use App\Models\QuestionSet;
use App\Models\RevisionNote;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;
use Illuminate\Support\Facades\Storage;
use PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

class TeacherProfileController extends Controller
{
    public function create_teacher_profile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|regex:/^[a-zA-Z\s]+$/',
            'last_name' => 'required|regex:/^[a-zA-Z\s]+$/',
            'country' => 'required',
            'c_code' => 'required',
            'contact' => 'required|regex:/^([+]\d{2})?\d{10}$/',
            'email' => 'required',
            'date_of_birth' => 'required',
            'school' => 'required',
            'board' => 'required',
            'exam' => 'required',
            'subject' => 'required',
            'password' => 'required',
            'confirm_password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()->all()], 422);
        }

        $contact = $request->contact;
        $teacher = Teacher::where('contact', $contact)->first();

        if (!isset($teacher)) {
            $data = new Teacher();
            $data->first_name = $request->first_name;
            $data->last_name = $request->last_name;
            $data->country_name = $request->country;
            $data->country_code = $request->c_code;
            $data->contact = $request->contact;
            $data->email = $request->email;
            $data->date_of_birth = $request->date_of_birth;
            $data->school = $request->school;
            $data->board = $request->board;
            $data->exam = $request->exam;
            $data->subject = $request->subject;
            if ($request->password == $request->confirm_password) {
                $data->password = md5($request->password);
                $data->save();
            } else {
                return response()->json(['status' => true, 'errors' => 'Confirm Password does not Match'], 422);
            }

            $token = $data->createToken('Teacher')->accessToken;

            if ($data->save()) {
                return response()->json(['msg' => 'ok', 'success' => 'Teacher Profile Created Successfully', 'token' => $token, 'user_type' => 'register', 'usr' => $data->id], 200);
            } else {
                return response()->json(['status' => false, 'errors' => 'Profile Not Created'], 422);
            }
        } else {
            // $token = $student->createToken('api')->accessToken;
            return response()->json(['msg' => 'ok', 'success' => 'Teacher Already Registered, Please Login!'], 200);
        }
    }

    // public function login_teacher(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'contact' => 'required|regex:/^([+]\d{2})?\d{10}$/',
    //         'password' => 'required',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['status' => false, 'errors' => $validator->errors()->all()], 422);
    //     }
    //     $contact = $request->contact;
    //     $password = md5($request->password);
    //     // return $password;

    //     $teacher = Teacher::where('contact', $contact)->first();
    //     if (!isset($teacher)) {
    //         return response()->json(['status' => false, 'errors' => 'You Are Not Registered , Kindly Register with us !'], 422);
    //     } else {
    //         $pass = Teacher::where('contact', $contact)->pluck('password');
    //         $string = str_replace(['["', '"]'], '', $pass);
    //         // return $string;
    //         // exit();
    //         if ($string == $password) {
    //             $token = $teacher->createToken('Teacher')->accessToken;
    //             return response()->json(['msg' => 'ok', 'success' => 'Teacher Login Successfully', 'token' => $token, 'user_type' => 'login', 'usr' => $teacher->id], 200);
    //         } else {
    //             return response()->json(['status' => false, 'errors' => 'Please Enter Correct Password!'], 422);
    //         }
    //     }
    // }

    public function login_teacher(Request $request)
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
        $data = Teacher::where('contact', $contact)->get();
        // return $data;
        // exit;
        // 	return json_encode($request->contact);
        if (isset($data)) {
            $user = Teacher::where('contact', $contact)->update(['password' => $otp]);
        }
        $msg = "Use $otp as your OTP for shvetdhardhara account verification. This is confidential. Please, do not share this with anyone. Webixun infoways PVT LTD ";
        // $obj=new  ComponentConfig();
        // $image_data= $obj->send_sms($contact,$msg);

        $response = ['status' => true, 'msg' => 'ok'];
        return json_encode($response);
    }

    public function otp_verification_teacher(request $request)
    {
        $validator = Validator::make($request->all(), [
            'contact' => 'required',
            'otp' => 'required|digits:4',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()->all()], 422);
        }

        $teacher = Teacher::where('contact', $request->contact)->first();
        // return $user[0]->password;
        if ($teacher) {
            if (Hash::check($request->otp, $teacher->password)) {
                $token = $teacher->createToken('Teacher')->accessToken;
                // return $token;
                return response()->json(['msg' => 'ok', 'success' => 'Teacher Login Successfully', 'token' => $token, 'user_type' => 'login', 'usr' => $teacher->id], 200);
            } else {
                $response = ['msg' => 'Password mismatch'];
                return response($response, 422);
            }
        } else {
            return response()->json(['status' => false, 'errors' => 'You Are Not Registered , Kindly Register with us !'], 422);
        }
    }


    public function fetch_exams()
    {
        $data = Exam::get('exam_name');
        if (count($data) > 0) {
            return response()->json(['status' => true, 'message' => 'Exam Data', 'data' => $data], 200);
        } else {
            return response()->json(['status' => false, 'message' => 'Exam Data List Not Found!'], 200);
        }
    }
}
