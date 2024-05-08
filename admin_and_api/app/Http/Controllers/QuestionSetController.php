<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuestionSet;
use App\Models\Subject;
use App\Models\Board;
use App\Models\question_exam;
use App\Models\question_board;
use App\Models\Exam;
use Validator;
use Illuminate\Support\Facades\File;
class QuestionSetController extends Controller
{
    public function show_question_set()
    {
        $question = QuestionSet::all();
        return view('Admin/question_set', compact('question'));
    }

    public function show_question_setpage()
    {
		$board=Board::all();
		$exam=Exam::all();
        $subject = Subject::all();
        return view('Admin/addquestion_set')->with('subject',$subject)->with('exam',$exam)->with('board',$board);
    }

    public function save_question_set(Request $request)
    {
        $request->validate([
            'subject_code' => 'required',
            'subject' => 'required',
            'topic' => 'required',
            'sub_topic' => 'required',
            'question' => 'required',
            'mark_schema' => 'required',
            'year' => 'required',
            'mark' => 'required',
            // 'image' => 'required|mimes:jpg,jpeg,png,webp,pdf|max:2048',
            'question_type' => 'required',
        ]);

        $data = new QuestionSet();
        $data->subject_code = $request->subject_code;
        $data->subject_id = $request->subject;
        $data->topic = $request->topic;
        $data->sub_topic = $request->sub_topic;
        $data->question = $request->question;
        $data->mark_schema = $request->mark_schema;
        $data->year = $request->year;
        $data->mark = $request->mark;
        $file = $request->file('image');


        if (isset($file)) {
            $filename = $file->getClientOriginalName();
            //return $filename;
            $file->move('Images', $filename);
        } else {
            $filename = '-';
        }
        $data->image = $filename;
        $data->question_type = $request->question_type;
        $data->save();

        if ($data->save()) {
			$question_id=$data->id;
			$board_id=$request->board;
			$board[]=['question_id'=>$question_id,'board_id'=>$board_id];

			$exam_id=$request->exam;
			$exam[]=['question_id'=>$question_id,'exam_id'=>$exam_id];

			question_board::insert($board);
			question_exam::insert($exam);

            return redirect('admin/show_question_set')->with('success', 'Question Set Added Successfully');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Something went wrong');
        }
    }
    public function save_question_set_from_file(Request $request)
    {
        $uploadedFile = $request->file('file');
        $path = $uploadedFile->move(public_path(''), $uploadedFile->getClientOriginalName());
        $parser = new \Smalot\PdfParser\Parser();
        $pdf = $parser->parseFile(public_path($uploadedFile->getClientOriginalName()));
        // dd($pdf->getPages()[1]->getXObjects()['Image18']);
        // $text = $pdf->getText();
        $size = sizeof($pdf->getPages());
        $data = new QuestionSet();
        $question__id = '';
        for($i = 0; $i < $size; $i++){
        $text = $pdf->getPages()[$i]->getText();
        $im = $pdf->getPages()[$i]->getXObjects();
        foreach ($im as $key => $value) {
            if (strpos($key, 'Image') !== false) {
                // Skip processing
                continue;
            }
        $image_name = "image".$key."_".$question__id.".jpg";
        $imageFilename = "Images/image".$key."_".$question__id.".jpg"; // Adjust filename as needed
        $imageData = $value->getContent(); // Binary image data
        // Save the binary image data to a file
        file_put_contents($imageFilename, $imageData);
        $data->image = $image_name;
        }
        $text = explode(" ", $text);
        $subject_code = '';
        $subject = '';
        $year = '';
        $topic = '';
        $sub_topic = '';
        $question_type = '';
        $mark = '';
        $question = '';
        $exam = '';
        $board = '';
        // $mark_schema = '';
        if(!empty($text[0]) && $text[0] !== " "){
            $question__id = '';
            foreach ($text as $key => $value) {
            if(strpos($value, 'Code') !== false){
                $subject_code = $text[$key+1];
            }
            if(strpos($value, 'Subject') !== false){
                $subject = $text[$key+1];
            }
            if(strpos($value, 'Year') !== false){
                $year = $text[$key+2]; //$text[$key+1]." ".
            }
            if(strpos($value, 'Topic') !== false){
                $topic = $text[$key+1];
            }
            if(strpos($value, 'Subtopic') !== false){
                $sub_topic = $text[$key+1];
                if($text[$key+2] != "Marks:"){
                    $sub_topic = $text[$key+1].$text[$key+2];
                }
                $sub_topic = str_replace(" ", "", $sub_topic);
            }
            if(strpos($value, 'Marks') !== false){
                $mark = $text[$key+1];
            }
            if(strpos($value, 'Type') !== false){
                $question_type = $text[$key+1];
            }
            if(strpos($value, 'Exam') !== false){
                $exam = $text[$key+1];
            }
            if(strpos($value, 'Board') !== false){
                $board = $text[$key+1];
            }
            }
             $slicedArray = array_slice($text, 21);
            $stringFromArray = implode(' ', $slicedArray);

            $question = $stringFromArray;
         echo $subject_code."<br>".$subject."<br>".$topic."<br>".$sub_topic."<br>".$question."<br>".$mark."<br>".$year."<br>".$question_type."<br>".$exam."<br>".$board."<br><br>";
         $subject_data = Subject::select('id')->where('subject_name', 'like',$subject)->first();
         $data = new QuestionSet();
         $data->subject_code = $subject_code;
         $data->subject_id = $subject_data->id;
         $data->topic = $topic;
         $data->sub_topic = $sub_topic;
         $data->question = $question;
         $data->question_type = $question_type;
         // $data->mark_schema = $request->mark_schema;
         $data->year = $year;
         $data->mark = $mark;
         // $file = $request->file('image');


         // if (isset($file)) {
         //     $filename = $file->getClientOriginalName();
         //     //return $filename;
         //     $file->move('Images', $filename);
         // } else {
         //     $filename = '-';
         // }
         // $data->image = $filename;
         $data->save();
         // dd($data->id);
         if ($data->save()) {
             $board_data = Board::select('id')->where('board_name', 'like',$board)->first();
             $exam_data = Exam::select('id')->where('exam_name', 'like',$exam)->first();
             $question_id=$data->id;
             $board_id=$board_data->id;
             question_board::create([
                 'question_id' => $question_id,
                 'board_id' => $board_id,
             ]);

             // $board[]=['question_id'=>$question_id,'board_id'=>$board_id];

             $exam_id=$exam_data->id;
             // $exam[]=['question_id'=>$question_id,'exam_id'=>$exam_id];
             question_exam::create([
                 'question_id' => $question_id,
                 'exam_id' => $exam_id,
             ]);
             $question__id = $question_id;

             // question_board::insert($board);
             // question_exam::insert($exam);

             // return redirect('admin/show_question_set')->with('success', 'Question Set Added Successfully');
         } else {
             return redirect()
                 ->back()
                 ->with('error', 'Something went wrong');
         }
        }else{
            $data->save();
        }
        // for($k = 0 ; $k < sizeof($text) ; $k++){
        // echo $text[$k]."<br><br>";
        // }
    }
        $filePath = public_path($uploadedFile->getClientOriginalName());

        if (file_exists($filePath)) {
            unlink($filePath);
        }
        return redirect('admin/show_question_set')->with('success', 'Question Set Added Successfully');
    }

    public function edit($id)
    {
        $subject = Subject::all();
		$board=Board::all();
		$exam=Exam::all();
        $question = QuestionSet::find($id);
        // return $question->boards;
        return view('Admin.editquestion_set', compact('subject'))->with('result', $question)->with('board',$board)->with('exam',$exam);
    }

    public function update_question_set(Request $request)
    {
        $request->validate([
            'subject_code' => 'required',
            'subject_name' => 'required',
            'topic' => 'required',
            'sub_topic' => 'required',
            'question' => 'required',
            'mark_schema' => 'required',
            'year' => 'required',
            'mark' => 'required',
            // 'image' => 'required|mimes:jpg,jpeg,png,webp,pdf|max:2048',
            'question_type' => 'required',
        ]);

        $update = QuestionSet::find($request->pid);
        $update->subject_code = $request->subject_code;
        $update->subject_id = $request->subject_name;
        $update->topic = $request->topic;
        $update->sub_topic = $request->sub_topic;
        $update->question = $request->question;
        $update->mark_schema = $request->mark_schema;
        $update->year = $request->year;
        $update->mark = $request->mark;
        $file = $request->file('image');

        if (isset($file)) {
            $filename = $file->getClientOriginalName();
            //return $filename;
            $file->move('Images', $filename);
        } else {
            $filename = '-';
        }
        $update->image = $filename;
        $update->question_type = $request->question_type;

        $update->update();

        if ($update->save()) {
			$question_id=$request->pid;
			$board_id=$request->board;

			question_board::where('question_id',$question_id)->delete();
			question_exam::where('question_id',$question_id)->delete();


			$board[]=['question_id'=>$question_id,'board_id'=>$board_id];

			$exam_id=$request->exam;
			$exam[]=['question_id'=>$question_id,'exam_id'=>$exam_id];

			question_board::insert($board);
			question_exam::insert($exam);
            return redirect('admin/show_question_set')->with('success', 'Question Set Updated Successfully');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Something went wrong');
        }
    }

    public function delete($id)
    {
        $query = QuestionSet::where('id', $id)->delete();
        if ($query) {
            return redirect()
                ->back()
                ->with('success', 'Question Set Deleted Successfully');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Something went wrong');
        }
    }
}
