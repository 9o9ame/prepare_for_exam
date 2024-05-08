<?php

namespace App\Http\Controller\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentProfile;
use App\Models\StateCity;
use App\Models\School;
use App\Models\User;
use App\Models\Classs;
use App\Models\Country;
use App\Models\Exam;
use App\Models\Subject;
use App\Models\Board;
use App\Models\Exam_Subject;
use App\Models\QuestionSet;
use App\Models\RevisionNote;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
// use Mpdf\Mpdf;
// use Mpdf\Output\Destination;
use Illuminate\Support\Facades\Storage;
// use PDF;
use Carbon\Carbon;

class StudentProfileController extends Controller
{
    public function create_student_profile(Request $request)
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
            'password' => 'required|min:8|',
            'confirm_password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()->all()], 422);
        }

        $contact = $request->contact;
        $student = User::where('contact', $contact)->first();

        if (!isset($student)) {
            $data = new User();
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

            $token = $data->createToken('User')->accessToken;
            // $data->access_token = 'Bearer '.$tokenResult->accessToken;
            if ($data->save()) {
                return response()->json(['msg' => 'ok', 'success' => 'Student Profile Created Successfully', 'token' => $token, 'user_type' => 'register', 'usr' => $data->id], 200);
            } else {
                return response()->json(['status' => false, 'errors' => 'Profile Not Created'], 422);
            }
        } else {
            // $token = $student->createToken('api')->accessToken;
            return response()->json(['msg' => 'ok', 'success' => 'Student Already Registered, Please Login!'], 200);
        }
    }

    // public function login_student(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'contact' => 'required|regex:/^([+]\d{2})?\d{10}$/',
    //         'password' => 'required',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['status' => false, 'errors' => $validator->errors()->all()], 422);
    //     }
    //     $email = $request->email;
    //     $contact = $request->contact;
    //     $password = md5($request->password);
    //     // return $password;

    //     $student = User::where('contact', $contact)->first();
    //     if (!isset($student)) {
    //         return response()->json(['status' => false, 'errors' => 'You Are Not Registered , Kindly Register with us !'], 422);
    //     } else {
    //         $pass = User::where('contact', $contact)->pluck('password');
    //         $string = str_replace(['["', '"]'], '', $pass);
    //         // return $string;
    //         // exit();
    //         if ($string == $password) {
    //             $token = $student->createToken('User')->accessToken;
    //             return response()->json(['msg' => 'ok', 'success' => 'Student Login Successfully', 'token' => $token, 'user_type' => 'login', 'usr' => $student->id], 200);
    //         } else {
    //             return response()->json(['status' => false, 'errors' => 'Please Enter Correct Password!'], 422);
    //         }
    //     }
    // }

    public function login_student(Request $request)
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
        // return $data;
        // exit;
        // 	return json_encode($request->contact);
        if (isset($data)) {
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
                $token = $user->createToken('User')->accessToken;
                // return $token;
                return response()->json(['msg' => 'ok', 'success' => 'Student Login Successfully', 'token' => $token, 'user_type' => 'login', 'usr' => $user->id], 200);
            } else {
                $response = ['msg' => 'Password mismatch'];
                return response($response, 422);
            }
        } else {
            return response()->json(['status' => false, 'errors' => 'You Are Not Registered , Kindly Register with us !'], 422);
        }
    }


    public function fetch_country()
    {
        $country = Country::get(['name', 'phonecode']);
        if (count($country) > 0) {
            return response()->json(['status' => true, 'message' => 'Country Data', 'data' => $country], 200);
        } else {
            return response()->json(['status' => false, 'message' => 'Country Data List Not Found!'], 200);
        }
    }

    public function fetch_exams()
    {
        $data = Exam::get(['id', 'exam_name']);
        if (count($data) > 0) {
            return response()->json(['status' => true, 'message' => 'Exam Data', 'data' => $data], 200);
        } else {
            return response()->json(['status' => false, 'message' => 'Exam Data List Not Found!'], 200);
        }
    }

    public function fetch_subjects(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'exam_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()->all()], 422);
        }
        $exam_id = $request->exam_id;

        $data = [];
        $data = Exam::where('id', $exam_id)
            ->with('subjects')
            ->get();
        if (count($data) > 0) {
            return response()->json(['status' => true, 'message' => 'Subject Data', 'data' => $data], 200);
        } else {
            return response()->json(['status' => false, 'message' => 'Subject Data List Not Found!'], 200);
        }
    }

    public function fetch_boards(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'exam_id' => 'required',
            'subject_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()->all()], 422);
        }
        $exam_id = $request->exam_id;
        $subject_id = $request->subject_id;

        $data = Board::whereIn('id', function ($q) use ($exam_id, $subject_id) {
            $q->from('exam_subject_boards')
                ->selectRaw('board_id')
                ->where('exam_id', $exam_id)
                ->where('subject_id', $subject_id);
        })->get();

        if (count($data) > 0) {
            return response()->json(['status' => true, 'message' => 'Subject Data', 'data' => $data], 200);
        } else {
            return response()->json(['status' => false, 'message' => 'Subject Data List Not Found!'], 200);
        }
    }

    public function fetch_subject()
    {
        $subject = QuestionSet::get(['subject_name', 'topic', 'sub_topic']);
        if (isset($subject) > 0) {
            return response()->json(['status' => true, 'message' => 'Subject Data', 'data' => $subject], 200);
        } else {
            return response()->json(['status' => false, 'message' => 'Subject Data List Not Found!'], 200);
        }
    }

    public function fetch_question(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'topic' => 'required',
            'sub_topic' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()->all()], 422);
        }

        $topic = $request->topic;
        $sub_topic = $request->sub_topic;

        $data = [];
        $data = QuestionSet::where('topic', $topic)
            ->where('sub_topic', $sub_topic)
            ->get(['question', 'question_type', 'mark_status']);
        if (count($data) > 0) {
            foreach ($data as $key => $mm) {
                $data[$key]['Revision Note'] = RevisionNote::where('topic', $topic)
                    ->where('sub_topic', $sub_topic)
                    ->get(['topic', 'sub_topic', 'image']);
            }
            return response()->json(['status' => true, 'message' => 'Question Data List!', 'data' => $data], 200);
        } else {
            return response()->json(['status' => false, 'message' => 'Question Data List Not Found!'], 200);
        }
    }

    public function question_details()
    {
        $question = QuestionSet::get(['question', 'mark_schema', 'image']);
        if (isset($question) > 0) {
            return response()->json(['status' => true, 'message' => 'Question Data', 'data' => $question], 200);
        } else {
            return response()->json(['status' => false, 'message' => 'Question Data List Not Found!'], 200);
        }
    }

    public function fetch_subscription_panel()
    {
        $data = SubscriptionPlan::get();
        if (count($data) > 0) {
            return response()->json(['status' => true, 'message' => 'Subscription Panel Data', 'data' => $data], 200);
        } else {
            return response()->json(['status' => false, 'message' => 'Subscription Panel Data List Not Found!'], 200);
        }
    }
}
