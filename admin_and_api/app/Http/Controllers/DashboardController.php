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
use Illuminate\Support\Str;
use App\Models\Subscription;
use App\Models\student_txn_log;
use Illuminate\Http\Request;
use App\Models\PreviousPaper;
use App\Models\StudentProfile;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\student_question_record;
use Illuminate\Support\Facades\Validator;

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

        $examId = $id; // Assuming $id is the exam ID you want to filter by
        $subjects = Exam::find($examId)->subjects; // Assuming you want the first subject for this example

        // Assuming $subject is not null
        // $subjectId = $subjects->id;

        // Retrieve boards for the specific exam and subjects
    //    return $data = $subjects->boards()->wherePivot('exam_id', $examId)->get();

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
            return view('Student.subjects',compact('examId','subjects','user'));
        } else {
            $subjects = 'Exam Data List Not Found!';
            return view('Student.subjects',compact('subjects'));
        }
    }

    function updateBoards(Request $request) {
        // return $request;
        $examId = $request->exam_id;
        $subjectId = $request->subject_id;
        $subject = Subject::find($subjectId);
        $boards =  $subject->boards()->wherePivot('exam_id', $examId)->get();
        $board_view = '';
        $board_view .= view('Student.boards', compact('boards','subjectId','examId'));
        return response()->json([
            'result' => 'success',
            'board_view' => $board_view
        ]);
    }
    public function activeBoard(Request $request)
    {
        // return $request;
        $validator = Validator::make($request->all(), [
            'board_id' => 'array|required'
        ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'errors' => $validator->errors()->all()], 422);
            }

        $board_id = $request->board_id;
        $subject_id = $request->subject_id;
        $exam_id = $request->exam_id;
        // $student_id = Auth::user()->id;
        // $student = StudentProfile::find($student_id);
        if (session()->has('STUDENT_LOGIN')) {
            $email = session()->get('email');
            $student = StudentProfile::where('email', $email)->first();
        }


        $cart_lenght=count($board_id);
        $data=[];
        if($student->board>=$cart_lenght || $student->board == -1)
        {

			$subject=0;
			$board=0;
			$exam=0;
            foreach($board_id as $key => $board_id)
            {
                $data[]=[
                    'student_id'=>$student->id,
                    'board_id'=>$board_id,
                    'subject_id'=>$subject_id[$key],
                    'exam_id'=>$exam_id[$key],
                    'status'=>1,
                    'created_at'=>now(),
                    'updated_at'=>now(),
                ];
				$subject++;
			$board++;
			$exam++;
            }
            // foreach($board_id as $board_id)
            // {
            //     $data[]=[
            //         'student_id'=>$student_id,
            //         'board_id'=>$board_id['board_id'],
            //         'subject_id'=>$board_id['subject_id'],
            //         'exam_id'=>$board_id['exam_id'],
            //         'status'=>1,
            //         'created_at'=>now(),
            //         'updated_at'=>now(),
            //     ];
			// 	$subject++;
			// $board++;
			// $exam++;
            // }

			// $board=$board-1;
            student_question_record::insert($data);

            if($student->board == -1)
            {
              //  $student->board = $student->board;
            }
            else
            {
                $student->board = $student->board - $board;
				$student->subject = $student->subject - $subject;
				$student->exam = $student->exam - $exam;
            }

            // $student->board = $student->board - $cart_lenght;

            $student->save();
            return response()->json(['status' => true, 'message' => 'Board Activated Successfully!'], 200);
        }
        else
        {
            return response()->json(['status' => false, 'message' => 'credit issue'], 200);
        }

    }
    public function fetch_subscription_panel()
    {
        $subscriptions = Subscription::get();
        if (count($subscriptions) > 0) {
            return view('Student.subsriptionPage', compact('subscriptions'));
        } else {
            return redirect()->back();
        }
    }
    public function create_order_request(Request $request)
    {
        // return 'nn,mn,n';
        $validator = Validator::make($request->all(), [
            'subscription_id' => 'required',
            ]);


            if ($validator->fails()) {
                return response()->json(['status' => false, 'errors' => $validator->errors()->all()], 422);
            }


            $txn_id=Str::uuid()->toString();
            $s_id=$request->subscription_id;
            //fetch

            $data=Subscription::find($s_id);
            $data_price=$data->subscription_price;



            $key='sk_test_51MI8XrCwZ9p12Xj5IzSGi3Wc8HYPZo1ZuFbceo7BDSSk3vIe6V8nHdyI0dJPZUSNphIv02aLKAC3jVTQY6jVsiAn00f2UDoIWz';

            $stripe = new \Stripe\StripeClient($key);

           $dd= $stripe->products->create(
             [
               'name' => 'Starter Setup',
               'default_price_data' => ['unit_amount' => $data_price*100, 'currency' => 'usd'],
               'expand' => ['default_price'],
             ]
           );


           $dm= $stripe->checkout->sessions->create([
            //  'success_url' => 'https://app.prepareforexams.com/payment_status/{CHECKOUT_SESSION_ID}',
            'success_url' => 'http://localhost:5173/payment_status/{CHECKOUT_SESSION_ID}',
            // 'success_url' => 'http://localhost:8000/payment_status/{CHECKOUT_SESSION_ID}',
            // 'success_url' => 'http://localhost:8000/verify_order/{CHECKOUT_SESSION_ID}',
             'line_items' => [
               [
                 'price' => $dd->default_price->id,
                 'quantity' => 1,
               ],
             ],
             'mode' => 'payment',
           ]);


           if (session()->has('STUDENT_LOGIN')) {
            $email = session()->get('email');
            $aut_user = StudentProfile::where('email', $email)->first();
        }

            $student=new student_txn_log();

            $student->student_id= $aut_user->id;
            $student->subscription_id=$s_id;
            $student->txn_id=$dm->id;
            $student->txn_amount=$data_price;
            $student->txn_status='failed';
            $student->txn_type='stripe';
            $student->no_of_exam=$data->no_of_board;
            $student->no_of_subject=$data->no_of_exam;
            $student->no_of_board=$data->no_of_subject;

            if($student->save())
            {

                $response['status']=true;
                $response['data']=$dm;
                $response['url']=$dm->url;
                  return $response;


                $response['data']=$data;
                $response['status']=true;
            }
            else{
                $response['msg']="no order craeted";
                $response['status']=false;
            }

            return $response;

    }
    public function verify_order(Request $request)
    {
        $key = 'sk_test_51MI8XrCwZ9p12Xj5IzSGi3Wc8HYPZo1ZuFbceo7BDSSk3vIe6V8nHdyI0dJPZUSNphIv02aLKAC3jVTQY6jVsiAn00f2UDoIWz';

        $stripe = new \Stripe\StripeClient($key);
        $txn_id = $request->txn_id;
        try {
            $session = $stripe->checkout->sessions->retrieve($txn_id);
            if ($session->payment_status == 'paid') {
                $data = student_txn_log::where('txn_id', $txn_id)->first();

                if ($data->txn_status == 'success') {
                    $response['status'] = true;
                    $response['msg'] = 'order verified';
                } else {
                    $board_id = $data->no_of_board;
                    $exam_id = $data->no_of_exam;
                    $subject_id = $data->no_of_subject;

                    $plan_validity = $data->sv_month;

                    $time = Auth::user()->subscription_expire;
                    $current_time = date('Y-m-d H:i:s');

                    $subscription = $data->subscription_id;

                    $sd = Subscription::find($subscription);
                    $plan_validity = $sd->sv_month;

                    if (strtotime($time) < strtotime($current_time)) {
                        Auth::user()->subscription_expire = date('Y-m-d H:i:s', strtotime('+' . $plan_validity . ' months'));
                    } else {
                        Auth::user()->subscription_expire = date('Y-m-d H:i:s', strtotime('+' . $plan_validity . ' months', strtotime($time)));
                    }

                    Auth::user()->save();

                    StudentProfile::where('id', Auth::user()->id)->increment('board', $board_id);
                    StudentProfile::where('id', Auth::user()->id)->increment('exam', $exam_id);
                    StudentProfile::where('id', Auth::user()->id)->increment('subject', $subject_id);
                    // $student=::find(Auth::user()->id);

                    // $student->increment('board' , );
                    // $student->increment('exam' , $exam_id);
                    // $student->increment('subject' , $subject_id);

                    // $student->save();

                    $data->txn_status = 'success';
                    $data->save();

                    $response['status'] = true;
                    $response['msg'] = 'order verified';
                }
            } else {
                $response['status'] = false;
                $response['msg'] = 'order not verified';
            }
        } catch (\Error $e) {
            $response['status'] = false;
            $response['msg'] = $e->getMessage();
        }


        return json_encode($response);
    }

}
