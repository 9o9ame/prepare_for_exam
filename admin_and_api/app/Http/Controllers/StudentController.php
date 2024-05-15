<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentProfile;
use App\Models\student_txn_log;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        if (session()->has('STUDENT_LOGIN')) {
            $email = session()->get('email');
            $user = StudentProfile::where('email', $email)->first();
        }
        return view('Student.user-profile', compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        if (session()->has('STUDENT_LOGIN')) {
            $email = session()->get('email');
            $user = StudentProfile::where('email', $email)->first();
            $string = str_replace(['["', '"]'], '', $user->password);
            $oldpassword = md5($request->old_password);
        }
        if ($request->form_type == 'profile') {
            $user->update($request->all());
            return response()->json([
                'result' => true,
                'message' => 'Profile Updated successfully'
            ]);
        } elseif ($request->form_type == 'password') {
            if ($user) {
                if ($request->old_password == $request->new_password) {
                    return response()->json([
                        'result' => false,
                        'message' => 'Old password and new password are same'
                    ]);
                }
                if ($string == $oldpassword) {
                    $newPassword = $request->new_password;
                    $confirmPassword = $request->confirm_password;

                    if ($newPassword === $confirmPassword) {
                        $user->password = md5($request->new_password);
                        $user->save();
                        return response()->json([
                            'result' => true,
                            'message' => 'password Updated successfully'
                        ]);
                    } else {
                        return response()->json([
                            'result' => false,
                            'message' => 'Wrong confirm password'
                        ]);
                    }
                } else {
                    return response()->json([
                        'result' => false,
                        'message' => 'Wrong old password'
                    ]);
                }
            } else {
                return response()->json([
                    'result' => false,
                    'message' => 'User not Found'
                ]);
            }
        } else {
            return response()->json([
                'result' => false,
                'message' => 'Something Went Wrong'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function fetchSubscriptionData()
    {
        if (session()->has('STUDENT_LOGIN')) {
            $email = session()->get('email');
            $user = StudentProfile::where('email', $email)->first();
        }

         $data = student_txn_log::with('plan')->where('student_id', $user->id)->get();

        if (count($data) > 0) {
            return view('Student.subsriptionHistory', compact('data'));
        }
    }
}
