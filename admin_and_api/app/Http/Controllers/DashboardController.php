<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PreviousPaper;
use App\Models\Testimonial;
use App\Models\Mail_Resume;
use App\Models\Admin;
use App\Models\upload;
use App\Models\StudentProfile;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
            $users = StudentProfile::count();
            $paper = PreviousPaper::count();
            $active_student = StudentProfile::where('status', 'Active')->count();
            $inactive_student = StudentProfile::where('status', 'Inactive')->count();
            // $user_id = Auth::user()->id;
            // return $user_id;
            return view('Admin/dashboard', compact('users', 'paper', 'active_student', 'inactive_student'));
    }
    public function studentDashboard()
    {
            $users = StudentProfile::count();
            $paper = PreviousPaper::count();
            $active_student = StudentProfile::where('status', 'Active')->count();
            $inactive_student = StudentProfile::where('status', 'Inactive')->count();
            // $user_id = Auth::user()->id;
            // return $user_id;
            return view('Student/dashboard', compact('users', 'paper', 'active_student', 'inactive_student'));
    }

	public function upload_files(Request $request)
	{
		$data=upload::orderBy('id','DESC')->get();

		return view('admin/upload')->with('data',$data);
	}

	public function add_file(Request $request)
	{
		if($request->hasFile('file')){
                $file = $request->file('file');
                $filename = time().$file->getClientOriginalName();
                $file->move('upload/task_files/',$filename);
                $filename = 'upload/task_files/'.$filename;
            }else{

                $filename = '';
            }

			$file_name=$request->img_name;

			$upload=new upload();
			$upload->img_name=$file_name;
			$upload->img_src=$filename;

			if($upload->save())
			{

				  Session::flash('success','File Updated!');
            return redirect('admin/upload');
			}
			else
			{
				Session::flash('success','File Updated!');
            return redirect('admin/upload');
			}

	}

	public function delete_file(Request $request)
	{
		$id=$request->id;
		$res=upload::where('id',$id)->delete();

		Session::flash('success','File Updated!');
        return redirect('admin/upload');


	}
}
