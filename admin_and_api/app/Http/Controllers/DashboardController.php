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
use App\Models\QuestionSet;
use App\Models\Testimonial;
use Illuminate\Support\Str;
use App\Models\Subscription;
use App\Models\student_question_read;
use App\Models\student_question_note;
use Illuminate\Http\Request;
use App\Models\PreviousPaper;
use App\Models\StudentProfile;
use Carbon\Carbon;
use App\Models\student_txn_log;
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
        // $user_id = Auth::user()->id;
        // return $user_id;
        $all_exam = Exam::all();
        if (session()->has('STUDENT_LOGIN')) {
            $email = session()->get('email');
            $student = StudentProfile::where('email', $email)->first();
        }
        $data = [];
        $data['total'] = student_question_read::where('student_id', $student->id)->count();
        $data['revisit'] = student_question_read::where('student_id', $student->id)->where('type', 'revisit')->count();
        $data['completed'] = student_question_read::where('student_id', $student->id)->where('type', 'complete')->count();

        $subscriptionExpireDate = Carbon::parse($student->subscription_expire);
        $currentDate = Carbon::now();
        $remainingDays = $currentDate->diffInDays($subscriptionExpireDate);

        $studentExams = Exam::whereIn('id', function ($query) use ($student) {
            $query->select('exam_id')->from('student_question_records')->where('student_id', $student->id);
        })->get(['id', 'exam_name']);

        if (count($studentExams) > 0) {

            foreach ($studentExams as $key => $value) {
                $studentExams[$key]['subjects'] = Subject::whereIn('id', function ($query) use ($student, $value) {
                    $query->select('subject_id')->from('student_question_records')->where('student_id', $student->id)->where('exam_id', $value->id);
                })->get();

                foreach ($studentExams[$key]['subjects'] as $key1 => $value1) {
                    $studentExams[$key]['subjects'][$key1]['boards'] = Board::whereIn('id', function ($query) use ($student, $value, $value1) {
                        $query->select('board_id')->from('student_question_records')->where('student_id', $student->id)->where('exam_id', $value->id)->where('subject_id', $value1->id);
                    })->get();
                }
            }
            // return $studentExams;
            return view('Student/dashboard', compact('all_exam', 'studentExams', 'data'));
        } else {
            return view('Student/dashboard', compact('all_exam', 'studentExams', 'data'));
        }
    }

    public function upload_files(Request $request)
    {
        $data = upload::orderBy('id', 'DESC')->get();

        return view('admin/upload')->with('data', $data);
    }

    public function add_file(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . $file->getClientOriginalName();
            $file->move('upload/task_files/', $filename);
            $filename = 'upload/task_files/' . $filename;
        } else {

            $filename = '';
        }

        $file_name = $request->img_name;

        $upload = new upload();
        $upload->img_name = $file_name;
        $upload->img_src = $filename;

        if ($upload->save()) {

            Session::flash('success', 'File Updated!');
            return redirect('admin/upload');
        } else {
            Session::flash('success', 'File Updated!');
            return redirect('admin/upload');
        }
    }

    public function delete_file(Request $request)
    {
        $id = $request->id;
        $res = upload::where('id', $id)->delete();

        Session::flash('success', 'File Updated!');
        return redirect('admin/upload');
    }
    function studentSubjects($id)
    {
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

        $data = Exam::whereIn('id', function ($query) use ($student_id) {
            $query->select('exam_id')->from('student_question_records')->where('student_id', $student_id);
        })->get(['id', 'exam_name']);

        if (count($data) > 0) {

            foreach ($data as $key => $value) {
                $data[$key]['subjects'] = Subject::whereIn('id', function ($query) use ($student_id, $value) {
                    $query->select('subject_id')->from('student_question_records')->where('student_id', $student_id)->where('exam_id', $value->id);
                })->get();

                foreach ($data[$key]['subjects'] as $key1 => $value1) {
                    $data[$key]['subjects'][$key1]['boards'] = Board::whereIn('id', function ($query) use ($student_id, $value, $value1) {
                        $query->select('board_id')->from('student_question_records')->where('student_id', $student_id)->where('exam_id', $value->id)->where('subject_id', $value1->id);
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
            return view('Student.subjects', compact('examId', 'subjects', 'user'));
        } else {
            $subjects = 'Exam Data List Not Found!';
            return view('Student.subjects', compact('subjects'));
        }
    }

    function updateBoards(Request $request)
    {
        // return $request;
        $examId = $request->exam_id;
        $subjectId = $request->subject_id;
        $subject = Subject::find($subjectId);
        $boards =  $subject->boards()->wherePivot('exam_id', $examId)->get();
        $board_view = '';
        $board_view .= view('Student.boards', compact('boards', 'subjectId', 'examId'));
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


        $cart_lenght = count($board_id);
        $data = [];
        if ($student->board >= $cart_lenght || $student->board == -1) {

            $subject = 0;
            $board = 0;
            $exam = 0;
            foreach ($board_id as $key => $board_id) {
                $data[] = [
                    'student_id' => $student->id,
                    'board_id' => $board_id,
                    'subject_id' => $subject_id[$key],
                    'exam_id' => $exam_id[$key],
                    'status' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
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

            if ($student->board == -1) {
                //  $student->board = $student->board;
            } else {
                $student->board = $student->board - $board;
                $student->subject = $student->subject - $subject;
                $student->exam = $student->exam - $exam;
            }

            // $student->board = $student->board - $cart_lenght;

            $student->save();
            return response()->json(['status' => true, 'message' => 'Board Activated Successfully!'], 200);
        } else {
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


        $txn_id = Str::uuid()->toString();
        $s_id = $request->subscription_id;
        //fetch

        $data = Subscription::find($s_id);
        $data_price = $data->subscription_price;



        $key = 'sk_test_51MI8XrCwZ9p12Xj5IzSGi3Wc8HYPZo1ZuFbceo7BDSSk3vIe6V8nHdyI0dJPZUSNphIv02aLKAC3jVTQY6jVsiAn00f2UDoIWz';

        $stripe = new \Stripe\StripeClient($key);

        $dd = $stripe->products->create(
            [
                'name' => 'Starter Setup',
                'default_price_data' => ['unit_amount' => $data_price * 100, 'currency' => 'usd'],
                'expand' => ['default_price'],
            ]
        );


        $dm = $stripe->checkout->sessions->create([
            //  'success_url' => 'https://app.prepareforexams.com/payment_status/{CHECKOUT_SESSION_ID}',
            // 'success_url' => 'http://localhost:5173/payment_status/{CHECKOUT_SESSION_ID}',
            'success_url' => 'http://localhost:8000/payment_status/{CHECKOUT_SESSION_ID}',
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
            $auth_user = StudentProfile::where('email', $email)->first();
        }

        $student = new student_txn_log();

        $student->student_id = $auth_user->id;
        $student->subscription_id = $s_id;
        $student->txn_id = $dm->id;
        $student->txn_amount = $data_price;
        $student->txn_status = 'failed';
        $student->txn_type = 'stripe';
        $student->no_of_exam = $data->no_of_board;
        $student->no_of_subject = $data->no_of_exam;
        $student->no_of_board = $data->no_of_subject;

        if ($student->save()) {

            $response['status'] = true;
            $response['data'] = $dm;
            $response['url'] = $dm->url;
            return $response;


            $response['data'] = $data;
            $response['status'] = true;
        } else {
            $response['msg'] = "no order craeted";
            $response['status'] = false;
        }

        return $response;
    }
    public function verify_order(Request $request, $txn_id)
    {
        $key = 'sk_test_51MI8XrCwZ9p12Xj5IzSGi3Wc8HYPZo1ZuFbceo7BDSSk3vIe6V8nHdyI0dJPZUSNphIv02aLKAC3jVTQY6jVsiAn00f2UDoIWz';

        $stripe = new \Stripe\StripeClient($key);
        if (session()->has('STUDENT_LOGIN')) {
            $email = session()->get('email');
            $auth_user = StudentProfile::where('email', $email)->first();
        }
        //    return $txn_id = $txn_id;
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

                    $time = $auth_user->subscription_expire;
                    $current_time = date('Y-m-d H:i:s');

                    $subscription = $data->subscription_id;

                    $sd = Subscription::find($subscription);
                    $plan_validity = $sd->sv_month;

                    if (strtotime($time) < strtotime($current_time)) {
                        $auth_user->subscription_expire = date('Y-m-d H:i:s', strtotime('+' . $plan_validity . ' months'));
                    } else {
                        $auth_user->subscription_expire = date('Y-m-d H:i:s', strtotime('+' . $plan_validity . ' months', strtotime($time)));
                    }

                    // $auth_user->save();

                    StudentProfile::where('id', $auth_user->id)->increment('board', $board_id);
                    StudentProfile::where('id', $auth_user->id)->increment('exam', $exam_id);
                    StudentProfile::where('id', $auth_user->id)->increment('subject', $subject_id);
                    // $student=::find($auth_user->id);

                    // $student->increment('board' , );
                    // $student->increment('exam' , $exam_id);
                    // $student->increment('subject' , $subject_id);

                    // $student->save();

                    $data->txn_status = 'success';
                    $data->save();
                    return view('Student.checkout');
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

    function fetchSubjects($id)
    {
        $exam_id = $id;
        if (session()->has('STUDENT_LOGIN')) {
            $email = session()->get('email');
            $user = StudentProfile::where('email', $email)->first();
        }
        $Subjects = Subject::whereIn('id', function ($query) use ($user, $id) {
            $query->select('subject_id')->from('student_question_records')->where('student_id', $user->id)->where('exam_id', $id);
        })->get();

        foreach ($Subjects as $key => $value) {
            $Subjects[$key]['boards'] = Board::whereIn('id', function ($query) use ($user, $id, $value) {
                $query->select('board_id')->from('student_question_records')->where('student_id', $user->id)->where('exam_id', $id)->where('subject_id', $value->id);
            })->get();
        }
        $view_modal = '';
        $view_modal .= view('Student.exam-modal', compact('Subjects', 'exam_id'));
        return response()->json([
            'result' => 'success',
            'view_modal' => $view_modal
        ]);
    }

    public function fetchTopics(Request $request)
    {
        // return $request;
        $validator = Validator::make($request->all(), [
            'exam_id' => 'required',
            'subject_id' => 'required',
            'board_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()->all()], 422);
        }

        $subject_id = $request->subject_id;
        $exam_id = $request->exam_id;
        $board_id = $request->board_id;

        $pre_data = PreviousPaper::where('subject_name', $subject_id)->where('exam_id', $exam_id)->where('board_id', $board_id)->get();

        $data = QuestionSet::where('subject_id', $subject_id)->whereIn('id', function ($q) use ($board_id) {
            $q->select('question_id')
                ->from('question_boards')
                ->where('board_id', $board_id);
        })->whereIn('id', function ($q) use ($exam_id) {
            $q->select('question_id')
                ->from('question_exams')
                ->where('exam_id', $exam_id);
        })->groupBy('topic')->get();

        if (count($data) > 0) {
            foreach ($data as $key => $q_data) {
                $subtopic = $q_data->topic;
                $data[$key]['subtopic'] = QuestionSet::where('subject_id', $subject_id)->whereIn('id', function ($q) use ($board_id) {
                    $q->select('question_id')
                        ->from('question_boards')
                        ->where('board_id', $board_id);
                })->whereIn('id', function ($q) use ($exam_id) {
                    $q->select('question_id')
                        ->from('question_exams')
                        ->where('exam_id', $exam_id);
                })->where('topic', $subtopic)->groupBy('sub_topic')->get();
            }
            // return $data;
            return view('Student.questionPage', compact('data', 'pre_data', 'subject_id', 'exam_id', 'board_id'));
        } else {
            $data = [];
            $pre_data = 'Question Data List Not Found!';
            return view('Student.questionPage', compact('data', 'pre_data'));
        }
    }
    public function question_details()
    {
        $question = QuestionSet::get(['question', 'mark_schema', 'image']);
        if (isset($question) > 0) {
            return view('Student.questionSet', compact('question'));
        } else {
            return view('Student.questionSet', compact('question'));
        }
    }
    public function fetch_question(Request $request)
    {
        // return $request;
        $subject_code = $request->subject_id;
        $exam_id = $request->exam_id;
        $board_id = $request->board_id;
        $topic = $request->topic;
        $subtopic = $request->subtopic;
        $method = $request->question_type;

        if (session()->has('STUDENT_LOGIN')) {
            $email = session()->get('email');
            $auth_user = StudentProfile::where('email', $email)->first();
        }

        $data = QuestionSet::with(['reads' => function ($q) use ($auth_user) {
            $q->where('student_id', '=', $auth_user->id);
        }, 'notes' => function ($q) use ($auth_user) {
            $q->where('student_id', '=', $auth_user->id);
        }])->where('subject_id', $subject_code)->whereIn('id', function ($q) use ($board_id) {
            $q->select('question_id')
                ->from('question_boards')
                ->where('board_id', $board_id);
        })->whereIn('id', function ($q) use ($exam_id) {
            $q->select('question_id')
                ->from('question_exams')
                ->where('exam_id', $exam_id);
        })->where('topic', $topic)->where('sub_topic', $subtopic)->where('question_type', $method)->get();
        // return $data;

        if (count($data) > 0) {
            if ($request->isXmlHttpRequest()) {
                // Handle the AJAX request
                $questionType = $request->input('question_type');

                // Your logic to fetch the question based on the question_type
                $questionDetailContent = '';
                $questionDetailContent .= view('Student.questionDetailContent', compact('data', 'request'));
                // Example response
                return response()->json([
                    'success' => true,
                    'question_view' => $questionDetailContent
                ]);
            } else {
                return view('Student.questionDetail', compact('data', 'request'));
            }
        } else {
            if ($request->isXmlHttpRequest()) {
                // Handle the AJAX request
                $questionType = $request->input('question_type');

                // Your logic to fetch the question based on the question_type
                $questionDetailContent = '';
                $questionDetailContent .= view('Student.questionDetailContent', compact('data', 'request'));
                // Example response
                return response()->json([
                    'success' => true,
                    'question_view' => $questionDetailContent
                ]);
            } else {
                return view('Student.questionDetail', compact('data', 'request'));
            }
        }
    }

    public function questionDetails(Request $request)
    {

        if (session()->has('STUDENT_LOGIN')) {
            $email = session()->get('email');
            $auth_user = StudentProfile::where('email', $email)->first();
        }
        $subject_code = $request->subject_id;
        $exam_id = $request->exam_id;
        $board_id = $request->board_id;
        $topic = $request->topic;
        $subtopic = $request->subtopic;
        $method = $request->question_type;
        $currentQuestionId = $request->question_id;
        $targetQuestionId = null;
        // $data = QuestionSet::where('subject_id', $subject_code)->whereIn('id', function ($q) use ($board_id) {
        //     $q->select('question_id')
        //         ->from('question_boards')
        //         ->where('board_id', $board_id);
        // })->whereIn('id', function ($q) use ($exam_id) {
        //     $q->select('question_id')
        //         ->from('question_exams')
        //         ->where('exam_id', $exam_id);
        // })->where('topic', $topic)->where('sub_topic', $subtopic)->where('question_type', $method)->where('id', '>', $currentQuestionId)
        // ->orderBy('id', 'asc')
        // ->value('id');



        if (isset($request->question_action)) {
            if ($request->question_action == 'next') {
                $targetQuestionId = QuestionSet::where('subject_id', $subject_code)->whereIn('id', function ($q) use ($board_id) {
                    $q->select('question_id')
                        ->from('question_boards')
                        ->where('board_id', $board_id);
                })->whereIn('id', function ($q) use ($exam_id) {
                    $q->select('question_id')
                        ->from('question_exams')
                        ->where('exam_id', $exam_id);
                })->where('topic', $topic)->where('sub_topic', $subtopic)->where('question_type', $method)->where('id', '>', $currentQuestionId)
                    ->orderBy('id', 'asc')
                    ->value('id');
            } else if ($request->question_action == 'previous') {
                $targetQuestionId = QuestionSet::where('subject_id', $subject_code)->whereIn('id', function ($q) use ($board_id) {
                    $q->select('question_id')
                        ->from('question_boards')
                        ->where('board_id', $board_id);
                })->whereIn('id', function ($q) use ($exam_id) {
                    $q->select('question_id')
                        ->from('question_exams')
                        ->where('exam_id', $exam_id);
                })->where('topic', $topic)->where('sub_topic', $subtopic)->where('question_type', $method)->where('id', '<', $currentQuestionId)
                    ->orderBy('id', 'desc')
                    ->value('id');
            }
        }

        $targetQuestionId = $targetQuestionId ?? $currentQuestionId;

        $question = QuestionSet::with(['reads' => function ($q) use ($auth_user) {
            $q->where('student_id', '=', $auth_user->id);
        }, 'notes' => function ($q) use ($auth_user) {
            $q->where('student_id', '=', $auth_user->id);
        }])->find($targetQuestionId);

        $view_card = '';
        $view_card .= view('Student.questionDetailCard', compact('question', 'request'));
        return response()->json([
            'result' => 'success',
            'view_card' => $view_card
        ]);
    }
    public function markAs($id, $mark)
    {
        // return $id;
        if (session()->has('STUDENT_LOGIN')) {
            $email = session()->get('email');
            $auth_user = StudentProfile::where('email', $email)->first();
        }

        student_question_read::where('student_id', $auth_user->id)->where('question_id', $id)->delete();

        // If record does not exist, create it
        $studentQuestionRead = new student_question_read();
        $studentQuestionRead->student_id = $auth_user->id;
        $studentQuestionRead->question_id = $id;
        $studentQuestionRead->type = $mark;
        $studentQuestionRead->save();

        return response()->json([
            'result' => 'success',
            'student_question' => $studentQuestionRead
        ]);
    }
    public function update_question_notes(Request $request)
    {
        // dd('lklkj');
        $validator = Validator::make($request->all(), [

            'question_id' => 'required',
            'notes' => 'required | nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()->all()], 422);
        }

        if (session()->has('STUDENT_LOGIN')) {
            $email = session()->get('email');
            $auth_user = StudentProfile::where('email', $email)->first();
        }
        $student_id = $auth_user->id;
        $question_id = $request->question_id;
        $question_notes = $request->notes;

        //insert or update

        $matchThese = ['question_id' => $question_id, 'student_id' => $student_id];
        // dd($matchThese, $question_notes);

        $one = student_question_note::updateOrCreate($matchThese, ['question_notes' => $question_notes]);
        // return $one;

        if ($one) {
            return response()->json(['status' => true, 'message' => 'Question Notes Updated Successfully!'], 200);
        } else {
            return response()->json(['status' => false, 'message' => 'Question Notes Not Updated!'], 200);
        }
    }
}
