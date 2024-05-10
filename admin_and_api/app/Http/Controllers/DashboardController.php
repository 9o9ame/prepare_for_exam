<?php

namespace App\Http\Controllers;

use Session;
use App\Models\Exam;
use App\Models\User;
use App\Models\Admin;
use App\Models\Board;
use App\Models\upload;
use App\Models\Subject;
use App\Models\Mail_Resume;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use App\Models\PreviousPaper;
use App\Models\StudentProfile;
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
            $all_exam = Exam::all();
            if (session()->has('STUDENT_LOGIN')) {
                $email = session()->get('email');
                $user = StudentProfile::where('email', $email)->first();
            }
            return view('Student/dashboard', compact('users', 'paper', 'active_student', 'inactive_student','all_exam'));
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
    function studentSubjects($id) {
        // return $id;
    if (session()->has('STUDENT_LOGIN')) {
        $email = session()->get('email');
        $user = StudentProfile::where('email', $email)->first();
    }
        $student_id = $user->id;

        $data = Exam::whereIn('id',function($query) use($student_id){
            $query->select('exam_id')->from('student_question_records')->where('student_id',$student_id);
        })->get(['id', 'exam_name']);

        if (count($data) > 0) {

            foreach($data as $key=>$value)
            {
                $data[$key]['subjects'] = Subject::whereIn('id',function($query) use($student_id,$value){
                    $query->select('subject_id')->from('student_question_records')->where('student_id',$student_id)->where('exam_id',$value->id);
                })->get();

                foreach ($data[$key]['subjects'] as $key1 => $value1) {
                    $data[$key]['subjects'][$key1]['boards'] = Board::whereIn('id',function($query) use($student_id,$value,$value1){
                        $query->select('board_id')->from('student_question_records')->where('student_id',$student_id)->where('exam_id',$value->id)->where('subject_id',$value1->id);
                    })->get();
                }
                // foreach($data[$key]['board'] as $key1=>$value1)
                // {
                //     $data[$key]['board'][$key1]['subject'] = Board::whereIn('id',function($query) use($student_id,$value,$value1){
                //         $query->select('subject_id')->from('student_question_records')->where('student_id',$student_id)->where('exam_id',$value->id)->where('board_id',$value1->id);
                //     })->get(['id', 'board_name']);
                // }
            }

            // return $data->where('id',$id);
            return view('Student.subjects',compact('data'));
        } else {
            $data = 'Exam Data List Not Found!';
            return view('Student.subjects',compact('data'));
        }
    }
}
