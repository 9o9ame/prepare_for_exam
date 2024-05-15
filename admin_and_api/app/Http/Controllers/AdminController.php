<?php

namespace App\Http\Controllers;

use Session;
use App\Models\Exam;
use App\Models\User;
use App\Models\Admin;
use App\Models\Board;
use App\Models\School;
use App\Models\Country;
use App\Models\ManageStatus;
use Illuminate\Http\Request;
// Use App\User;
use App\Models\StudentProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function  payment_status($session_id)
    {
        $key='sk_test_51MI8XrCwZ9p12Xj5IzSGi3Wc8HYPZo1ZuFbceo7BDSSk3vIe6V8nHdyI0dJPZUSNphIv02aLKAC3jVTQY6jVsiAn00f2UDoIWz';

        $stripe = new \Stripe\StripeClient($key);
        $payment_id=$session_id;
        try {
            $session = $stripe->checkout->sessions->retrieve( $payment_id);
            if($session->payment_status=='paid')
            {

            }

            $customer = $stripe->customers->retrieve($session->customer);
            echo "<h1>Thanks for your order, $customer->name!</h1>";
            http_response_code(200);
          } catch (Error $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
          }
    }


    public function index()
    {
        return view('Admin/login');
    }

    public function signup_page()
    {
        $school = School::all();
        $board = Board::all();
        $exam = Exam::all();
        $country = Country::all();
        return view('Admin/register', compact('school', 'board', 'exam', 'country'));
    }

    public function get_status(Request $request)
    {
        $status = $request->status;
        $data = ManageStatus::where('status', '!=', $status)->get();
        return response()->json(['data' => $data, 'status' => $status]);
    }

    public function auth(Request $request)
    {
        $email = $request->post('email');
        $password = $request->post('password');
        $password = md5($password);
         $result = User::where('email', $email)
            ->where('password', '=', $password)
            ->count();
         $student = StudentProfile::where('email', $email)
            ->where('password', '=', $password)
            ->count();
         $id = Admin::where('email', $email)
            ->where('password', '=', $password)
            ->first();

        if ($result) {
            $id = $id->id;
            //   dd($id);
            Session::put('email', $request->email);
            if ($request->checkbox == 'true') {
                Session::put('password', $request->password);
            }
            $request->session()->put('ADMIN_LOGIN', true);
            $request->session()->put('ADMIN_ID', $id);
            return redirect('admin/dashboard');
        }else if ($student) {
            //   dd($id);
            Session::put('email', $request->email);
            if ($request->checkbox == 'true') {
                Session::put('password', $request->password);
            }
            $request->session()->put('STUDENT_LOGIN', true);
            return redirect('student/dashboard');
        }
         else {
            $request->session()->flash('error', 'Please enter the Valid login details');
            return back();
        }
    }

    // public function auth(Request $request)
    // {
    //     request()->validate([
    //         'email' => 'required',
    //         'password' => 'required',
    //     ]);

    //     $credentials = $request->only('email', 'password');
    //     if (Auth::attempt($credentials)) {
    //         // Authentication passed...
    //         return redirect()->intended('admin/dashboard');
    //     }
    //     return Redirect::to('/')->withError('error', 'Please enter the Valid login details');
    // }

    public function logout()
    {
        Session::flush();
        // Auth::logout();
        return redirect('/');
    }

    public function register_teacher(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'country_name' => 'required',
            'country_code' => 'required',
            'contact' => 'required|min:10|max:10|unique:admins',
            'email' => 'required|regex:/(.+)@(.+)\.(.+)/i|unique:admins',
            'school' => 'required',
            'board' => 'required',
            'exam' => 'required',
            'password' => 'required',
            'confirm_password' => 'required',
        ]);

        $data = new User();
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
        $data->role = $request->role;
        if ($request->password == $request->confirm_password) {
            $data->password = md5($request->password);
        } else {
            return redirect()
                ->back()
                ->with('error', 'Password does not match!');
        }
        $data->save();

        if ($data->save()) {
            return redirect('/')->with('success', 'Teacher Profile Created Successfully , Login Now !');
        } else {
            return redirect('teacher_signup')
                ->back()
                ->with('error', 'Something went wrong');
        }
    }
}
