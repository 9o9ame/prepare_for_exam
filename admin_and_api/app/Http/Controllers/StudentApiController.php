<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentProfile;
use App\Models\Subject;
use App\Models\Board;
use App\Models\Exam;
use App\Models\PreviousPaper;
use App\Models\QuestionSet;
use App\Models\question_note;
use App\Models\RevisionNote;
use App\Models\Country;
use App\Models\question_exam;
use App\Models\question_board;
use App\Models\student_question_record;
use App\Models\Subscription;
use App\Models\student_txn_log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\student_question_note;
use App\Models\student_question_read;
use Illuminate\Support\Facades\Validator;
use Mpdf\Mpdf;

class StudentApiController extends Controller
{
    public function fetch_dashboard(Request $request)
    {
        $student_id = Auth::user()->id;
        $data['status'] = true;
        $data['total'] = student_question_read::where('student_id', $student_id)->count();
        $data['revisit'] = student_question_read::where('student_id', $student_id)->where('type', 'revisit')->count();
        $data['completed'] = student_question_read::where('student_id', $student_id)->where('type', 'completed')->count();

        return json_encode($data);
    }

    public function generate_pdf(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()->all()], 422);
        }

        //	return json_encode($request->question_id);
        $data = QuestionSet::whereIn('id', $request->question_id)->get();

        $mpdf = new Mpdf(['mode' => 'utf-8', 'format' => [210, 297], 'margin_left' => 0, 'margin_right' => 0, 'margin_top' => 0.2, 'margin_bottom' => 0, 'margin_header' => 0, 'margin_footer' => 0]);
        //
        //write content
        $mpdf->WriteHTML(view('printBill')->with('data', $data));

        //return the PDF for download

        $name = 'pdf/' . time() . '.pdf';
        $mpdf->Output($name);

        $response['status'] = true;
        $response['pdf'] = $name;

        return json_encode($response);
    }
    public function update_question_status(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'question_status' => 'required',
            'question_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()->all()], 422);
        }

        $student_id = Auth::user()->id;
        $question_id = $request->question_id;

        student_question_read::where('student_id', $student_id)->where('question_id', $question_id)->delete();

        $ques = new student_question_read();

        $ques->student_id = $student_id;
        $ques->question_id = $question_id;
        $ques->type = $request->question_status;
        if ($ques->save()) {
            $response['status'] = true;
            $response['msg'] = "updated";
        } else {
            $response['status'] = false;
            $response['msg'] = "Not updated";
        }
        return json_encode($response);
    }



    public function fetch_question(Request $request)
    {
        $subject_code = $request->subject_id;
        $exam_id = $request->exam_id;
        $board_id = $request->board_id;
        $topic = $request->topic;
        $subtopic = $request->subtopic;
        $method = $request->question_type;

        $student_id = Auth::user()->id;

        $data = QuestionSet::with(['reads' => function ($q) use ($student_id) {
            $q->where('student_id', '=', $student_id);
        }, 'notes' => function ($q) use ($student_id) {
            $q->where('student_id', '=', $student_id);
        }])->where('subject_id', $subject_code)->whereIn('id', function ($q) use ($board_id) {
            $q->select('question_id')
                ->from('question_boards')
                ->where('board_id', $board_id);
        })->whereIn('id', function ($q) use ($exam_id) {
            $q->select('question_id')
                ->from('question_exams')
                ->where('exam_id', $exam_id);
        })->where('topic', $topic)->where('sub_topic', $subtopic)->where('question_type', $method)->get();

        if (count($data) > 0) {
            return response()->json(['status' => true, 'message' => 'Question Data', 'data' => $data], 200);
        } else {
            return response()->json(['status' => false, 'message' => 'Question Data List Not Found!'], 200);
        }
    }

    public function fetch_topics(Request $request)
    {
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

            return response()->json(['status' => true, 'message' => 'Question Data', 'data' => $data, 'pre' => $pre_data], 200);
        } else {
            return response()->json(['status' => false, 'message' => 'Question Data List Not Found!'], 200);
        }
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

        $student_id = Auth::user()->id;
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

    public function change_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required|same:new_password',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()->all()], 422);
        }

        $user = Auth::user();
        $old_password = $request->old_password;
        $new_password = $request->new_password;

        if ($old_password == $new_password) {
            return response()->json(['status' => false, 'message' => 'Old Password and New Password are same!'], 200);
        }

        if ($user->password == md5($old_password)) {
            $user->password = md5($new_password);
            $user->save();

            return response()->json(['status' => true, 'message' => 'Password Changed Successfully!'], 200);
        } else {
            return response()->json(['status' => false, 'message' => 'Old Password Not Match!'], 200);
        }
    }

    public function fetch_exam()
    {
        $data = Exam::get(['id', 'exam_name']);
        if (count($data) > 0) {
            return response()->json(['status' => true, 'message' => 'Exam Data', 'data' => $data], 200);
        } else {
            return response()->json(['status' => false, 'message' => 'Exam Data List Not Found!'], 200);
        }
    }

    public function fetch_user_exam_data()
    {
        $student_id = Auth::user()->id;

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

            return response()->json(['status' => true, 'message' => 'Exam Data', 'data' => $data], 200);
        } else {
            return response()->json(['status' => false, 'message' => 'Exam Data List Not Found!'], 200);
        }
    }

    public function active_board(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'board_id' => 'array|required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()->all()], 422);
        }

        $board_id = $request->board_id;
        $student_id = Auth::user()->id;

        $student = StudentProfile::find($student_id);

        $cart_lenght = count($board_id);
        $data = [];
        if (Auth::user()->board >= $cart_lenght || Auth::user()->board == -1) {

            $subject = 0;
            $board = 0;
            $exam = 0;
            foreach ($board_id as $board_id) {
                $data[] = [
                    'student_id' => $student_id,
                    'board_id' => $board_id['board_id'],
                    'subject_id' => $board_id['subject_id'],
                    'exam_id' => $board_id['exam_id'],
                    'status' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $subject++;
                $board++;
                $exam++;
            }

            $board = $board - 1;
            student_question_record::insert($data);

            if ($student->board == -1) {
                //  $student->board = $student->board;
            } else {
                $student->board = $student->board - $board;
                $student->subject = $student->subject - $subject;
                $student->exam = $student->exam - $exam;
            }

            $student->board = $student->board - $cart_lenght;

            $student->save();
            return response()->json(['status' => true, 'message' => 'Board Activated Successfully!'], 200);
        } else {
            return response()->json(['status' => false, 'message' => 'credit issue'], 200);
        }
    }


    public function fetch_exam_data(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'exam_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()->all()], 422);
        }

        $exam_id = $request->exam_id;
        $data = Exam::with('subjects')->where('id', $exam_id)->get();

        if (count($data) > 0) {
            $exam_id = $request->exam_id;
            foreach ($data as $ekey => $exam) {
                foreach ($exam->subjects as $key => $subject) {
                    $subject_id = $subject->id;

                    $data[$ekey]['subjects'][$key]['boards'] = Board::whereIn('id', function ($q) use ($exam_id, $subject_id) {
                        $q->from('exam_subject_boards')
                            ->selectRaw('board_id')
                            ->where('subject_id', $subject_id)->where('exam_id', $exam_id);
                    })->get();
                }
            }


            $response['status'] = true;
            $response['data'] = $data;
        } else {
            $response['status'] = false;
            $response['msg'] = 'no data found';
        }

        return json_encode($response);
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
            'success_url' => 'https://app.prepareforexams.com/payment_status/{CHECKOUT_SESSION_ID}',
            // 'success_url' => 'http://localhost:5173/payment_status/{CHECKOUT_SESSION_ID}',
            'line_items' => [
                [
                    'price' => $dd->default_price->id,
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
        ]);




        $student = new student_txn_log();

        $student->student_id = Auth::user()->id;
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
            return json_encode($response);


            $response['data'] = $data;
            $response['status'] = true;
        } else {
            $response['msg'] = "no order craeted";
            $response['status'] = false;
        }

        return $response;
    }

    //verify order

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


    public function fetch_subscription_data()
    {
        $student_id = Auth::user()->id;

        $data = student_txn_log::with('plan')->where('student_id', $student_id)->get();

        if (count($data) > 0) {
            $response['status'] = true;
            $response['data'] = $data;
        } else {
            $response['status'] = false;
            $response['msg'] = 'no data found';
        }

        return json_encode($response);
    }
}
