<?php

namespace App\Http\Controllers;

use App\Models\StudentProfile;
use App\Models\School;
use App\Models\Board;
use App\Models\Exam;
use App\Models\QuestionSet;
use App\Models\RevisionNote;
use App\Models\Country;
use App\Models\User;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class StudentProfileController extends Controller
{
    public function get_student_profile()
    {
        $student_id = auth()->user()->id;

        $student_profile = StudentProfile::where('id', $student_id)->first();

		$time=$student_profile->subscription_expire;
            $current_time=date('Y-m-d H:i:s');
            $diff=date_diff(date_create($current_time),date_create($time));

            if(strtotime($time)<strtotime($current_time))
            {
                $student_profile->subscription_expire=0;
            }
            else
            {
                $student_profile->subscription_expire=$diff->format("%a")+1;
            }

        return response()->json(['status' => true, 'data' => $student_profile]);
    }


    //update studen profile

    public function update_student_profile_api(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'first_name' => 'required|regex:/^[a-zA-Z\s]+$/',
        'last_name' => 'required|regex:/^[a-zA-Z\s]+$/',
        'dob' => 'required'
        ]);


        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()->all()], 422);
        }


        $student_id = auth()->user()->id;
        $student_profile = StudentProfile::where('id', $student_id)->first();
        $student_profile->first_name = $request->first_name;
        $student_profile->last_name = $request->last_name;
        $student_profile->date_of_birth = $request->dob;
        if($student_profile->save()){
            return response()->json(['status' => true, 'data' => $student_profile]);
        }else{
            return response()->json(['status' => false, 'errors' => 'Profile Not Updated'], 422);
        }

    }

    //fetch exams


    public function create_student_profile(Request $request)
    {

          $validator = Validator::make($request->all(), [
            'first_name' => 'required|regex:/^[a-zA-Z\s]+$/',
            'last_name' => 'required|regex:/^[a-zA-Z\s]+$/',
            'country' => 'required',
            // 'c_code' => 'required',
            'contact' => 'required|regex:/^([+]\d{2})?\d{10}$/',
            'email' => 'required',
            // 'date_of_birth' => 'required',
            'school' => 'required',
            // 'board' => 'required',
            // 'exam' => 'required',
            // 'subject' => 'required',
            'password' => 'required|min:8|',
            'confirm_password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()->all()], 422);
        }

        $contact = $request->contact;
        $student = StudentProfile::where('contact', $contact)->first();

        if (!isset($student)) {
            $data = new StudentProfile();
            $data->first_name = $request->first_name;
            $data->last_name = $request->last_name;
            $data->country_name = $request->country;
            // $data->country_code = $request->c_code;
            $data->contact = $request->contact;
            $data->email = $request->email;
            $data->date_of_birth = $request->date_of_birth;
            $data->status='pending';
            $data->school = $request->school;
             $data->board = 1;
             $data->exam = 1;
             $data->subject = 1;
			 $data->type=$request->registration_type;
			 $data->subscription_expire=date('Y-m-d H:i:s', strtotime('+3 days'));
            if ($request->password == $request->confirm_password) {
                $data->password =md5($request->password);
                $data->save();
            } else {
                return response()->json(['status' => false, 'error' => 'Confirm Password does not Match'], 422);
            }

            $token = 'Bearer '.$data->createToken('User')->accessToken;
            // $data->access_token = 'Bearer '.$tokenResult->accessToken;
            if ($data->save()) {
                return response()->json(['status' =>true, 'error' => 'Student Profile Created Successfully', 'token' => $token, 'user_type' => 'register', 'usr' => $data->id], 200);
            } else {
                return response()->json(['status' => false, 'error' => 'Profile Not Created'], 422);
            }
        } else {
            // $token = $student->createToken('api')->accessToken;
            return response()->json(['status' => false, 'error' => 'Student Already Registered, Please Login!'], 200);
        }
    }
    public function createProfile(Request $request)
    {
        
          $validator = Validator::make($request->all(), [
            'first_name' => 'required|regex:/^[a-zA-Z\s]+$/',
            'last_name' => 'required|regex:/^[a-zA-Z\s]+$/',
            'country' => 'required',
            // 'c_code' => 'required',
            'contact' => 'required|regex:/^([+]\d{2})?\d{10}$/',
            'email' => 'required',
            // 'date_of_birth' => 'required',
            'school' => 'required',
            // 'board' => 'required',
            // 'exam' => 'required',
            // 'subject' => 'required',
            'password' => 'required|min:8|',
            'confirm_password' => 'required',
        ]);

        
        if ($validator->fails()) {
            // return $validator->errors()->all();
            return redirect('signup')
                    ->withErrors($validator)
                    ->withInput();
            return redirect()->back()->with(['error' => $validator->errors()->all()]);
        }
        // return $request;
        $contact = $request->contact;
        $student = StudentProfile::where('contact', $contact)->first();

        if (!isset($student)) {
            $data = new StudentProfile();
            $data->first_name = $request->first_name;
            $data->last_name = $request->last_name;
            $data->country_name = $request->country;
            // $data->country_code = $request->c_code;
            $data->contact = $request->contact;
            $data->email = $request->email;
            $data->date_of_birth = $request->date_of_birth;
            $data->status='pending';
            $data->school = $request->school;
             $data->board = 1;
             $data->exam = 1;
             $data->subject = 1;
			 $data->type=$request->registration_type;
			 $data->subscription_expire=date('Y-m-d H:i:s', strtotime('+3 days'));
            if ($request->password == $request->confirm_password) {
                $data->password =md5($request->password);
                $data->save();
            } else {
                return redirect()->back()->with('error', 'Confirm Password does not Match');
            }

            // $token = 'Bearer '.$data->createToken('User')->accessToken;
            // $data->access_token = 'Bearer '.$tokenResult->accessToken;
            if ($data->save()) {
                return redirect()->route('/')->with('success', 'Student Profile Created Successfully');
            } else {
                 return redirect()->back()->with('error', 'Profile Not Created');
                }
        } else {
            return redirect()->back()->with('error', 'Student Already Registered, Please Login!');
        }
    }

    public function login_student(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'contact' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()->all()], 422);
        }
        $email = $request->email;
        $contact = $request->contact;
        $password = md5($request->password);
        // return $password;

        $student = StudentProfile::where('contact', $contact)->orwhere('email',$contact)->first();

		if(!$student)
		{
			$response['status']=false;
			$response['errors']="User not found, please signup first";
			return json_encode($response);
		}
		$time=$student->subscription_expire;
            $current_time=date('Y-m-d H:i:s');
            $diff=date_diff(date_create($current_time),date_create($time));

            if(strtotime($time)<strtotime($current_time))
            {
                $student->subscription_expire=0;
            }
            else
            {
                $student->subscription_expire=$diff->format("%a")+1;
            }

        if (!isset($student)) {
            return response()->json(['status' => false, 'errors' => 'You Are Not Registered , Kindly Register with us !'], 422);
        } else {
            $pass = StudentProfile::where('contact', $contact)->orwhere('email',$contact)->pluck('password');
            $string = str_replace(['["', '"]'], '', $pass);
            // return $string;
            // exit();
            if ($string == $password) {
                if($student->status=='pending'){
                    return response()->json(['status' => false, 'errors' => 'Your Profile is not active. we have sent you an email to activate your account.'], 422);
                }
                $token = 'Bearer '.$student->createToken('User')->accessToken;
                return response()->json(['msg' => 'ok','user'=>$student, 'success' => 'Student Login Successfully', 'token' => $token, 'user_type' => 'login', 'usr' => $student->id], 200);
            } else {
                return response()->json(['status' => false, 'errors' => 'Please Enter Correct Password!'], 422);
            }
        }
    }

    public function login_student2(Request $request)
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
        $data = Subscription::get();
        if (count($data) > 0) {
            return response()->json(['status' => true, 'message' => 'Subscription Panel Data', 'data' => $data], 200);
        } else {
            return response()->json(['status' => false, 'message' => 'Subscription Panel Data List Not Found!'], 200);
        }
    }


    public function show_student_profile()
    {
        $school = School::all();
        $board = Board::all();
        $exam = Exam::all();
        $profile = StudentProfile::all();
        $country = Country::all();
        return view('Admin/student_profile', compact('profile', 'school', 'board', 'exam', 'country'));
    }

    public function show_student_profilepage()
    {
        $school = School::all();
        $board = Board::all();
        $exam = Exam::all();
        // $profile = StudentProfile::all();
        $country = Country::all();
        return view('Admin/addstudent_profile', compact('school', 'board', 'exam', 'country'));
    }

    public function save_student_profile(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'country_name' => 'required',
            'country_code' => 'required',
            'contact' => 'required|min:10|max:10',
            'email' => 'required|regex:/(.+)@(.+)\.(.+)/i',
            'school' => 'required',
            'board' => 'required',
            'exam' => 'required',
            'password' => 'required',
            'confirm_password' => 'required',
            'status' => 'required',
        ]);

        $data = new StudentProfile();
        $data->first_name = $request->first_name;
        $data->last_name = $request->last_name;
        $data->country_name = $request->country_name;
        $data->country_code = $request->country_code;
        $data->contact = $request->contact;
        $data->email = $request->email;
        $data->date_of_birth = $request->date_of_birth;
        $data->school = $request->school;
        $data->board = $request->board;
        $data->exam = $request->exam;
        if ($request->password == $request->confirm_password) {
            $data->password = md5($request->password);
        } else {
            return redirect()
                ->back()
                ->with('error', 'Password does not match!');
        }
        $data->status = $request->status;
        $data->save();

        if ($data->save()) {
            return redirect('admin/show_student_profile')->with('success', 'Student Profile Added Successfully');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Something went wrong');
        }
    }

    public function edit($id)
    {
        $school = School::all();
        $board = Board::all();
        $exam = Exam::all();
        $student = StudentProfile::find($id);
        $country = Country::all();
        return view('Admin.editstudent_profile', compact('school', 'board', 'exam', 'country'))->with('result', $student);
    }

    public function update_student_profile(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'contact' => 'required|min:10|max:10',
            'email' => 'required|regex:/(.+)@(.+)\.(.+)/i',
            'school' => 'required',
            'board' => 'required',
            'exam' => 'required',
        ]);

        $update = StudentProfile::find($request->pid);
        $update->first_name = $request->first_name;
        $update->last_name = $request->last_name;
        $update->country_name = $request->country_name;
        $update->country_code = $request->country_code;
        $update->contact = $request->contact;
        $update->email = $request->email;
        $update->date_of_birth = $request->date_of_birth;
        $update->school = $request->school;
        $update->board = $request->board;
        $update->exam = $request->exam;
        if ($request->password == $request->confirm_password) {
            $update->password = md5($request->password);
        } else {
            return redirect()
                ->back()
                ->with('error', 'Password does not match!');
        }
        $update->status = $request->status;
        $update->update();

        if ($update->save()) {
            return redirect('admin/show_student_profile')->with('success', 'Student Profile Updated Successfully');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Something went wrong');
        }
    }

    public function delete($id)
    {
        $query = StudentProfile::where('id', $id)->delete();
        if ($query) {
            return redirect()
                ->back()
                ->with('success', 'Student Profile Deleted Successfully');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Something went wrong');
        }
    }

    public function save_status(Request $request)
    {
        $update = StudentProfile::find($request->pid);
        $update->status = $request->status;
        $update->update();

        if ($update->save()) {
            return redirect()
                ->back()
                ->with('success', 'Status Changed Successfully');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Something went wrong');
        }
    }

    public function fetchcode(Request $req)
    {
        // echo "dfasdf";
        $id = $req->post('id');
        $country_code = Country::where('name', $id)
            ->orderBy('phonecode', 'asc')
            ->get('phonecode');
        $html = '<option value="" >Select Code</option>';
        foreach ($country_code as $lis) {
            $html .= '<option value="' . $lis->phonecode . '">' . $lis->phonecode . '</option>';
        }
        echo $html;
    }
}
