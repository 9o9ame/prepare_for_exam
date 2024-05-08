<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PreviousPaper;
use App\Models\Subject;
use App\Models\Exam;
use App\Models\Board;

class PreviousPaperController extends Controller
{
    public function show_previous_year_paper()
    {
        $paper = PreviousPaper::with(['exam','board','subject'])->get();
		
        $subject = Subject::all();
        return view('Admin/previous_year_paper', compact('paper', 'subject'));
    }

    public function show_previous_year_paperpage()
    {
		$exam = Exam::all();
		$board = Board::all();
        $subject = Subject::all();
        return view('Admin/addprevious_year_paper', compact('subject'))->with('exam',$exam)->with('board',$board);
    }

    public function save_previous_year_paper(Request $request)
    {
		
        $request->validate([
            'series' => 'required|unique:previous_year_papers',
            'month' => 'required',
            'year' => 'required',
           
            'subject_name' => 'required',
            'link' => 'required',
            'answer_sheet' => 'required',
            'marks' => 'required',
        ]);


        $data = new PreviousPaper();
        $data->series = $request->series;
        $string = implode(',', $request->month);
        // echo $string;
        // exit();
        $data->month = $string;
        $data->year = $request->year;
        $data->subject_name = $request->subject_name;
        $data->link = $request->link;
        $data->answer_sheet = $request->answer_sheet;
        $data->marks = $request->marks;
		$data->exam_id=$request->exam;
		$data->board_id=$request->board;
       

        if ($data->save()) {
            return redirect('admin/show_previous_year_paper')->with('success', 'Previous Year Paper Added Successfully');
        } else {
			return $data;
            return redirect()
                ->back()
                ->with('error', 'Something went wrong');
        }
    }

    public function edit($id)
    {
        $exam = Exam::all();
		$board = Board::all();
        $subject = Subject::all();
        $paper = PreviousPaper::find($id);
        return view('Admin.editprevious_year_paper', compact('subject'))->with('board',$board)->with('exam',$exam)->with('result', $paper);
    }

    public function update_previous_year_paper(Request $request)
    {
		
        $request->validate([
            'series' => 'required',
            'month' => 'required',
            'year' => 'required',
            'subject_name' => 'required',
            'link' => 'required',
            'answer_sheet' => 'required',
            'marks' => 'required',
        ]);

        $update = PreviousPaper::find($request->pid);
        $update->series = $request->series;
        $string = implode(',', $request->month);
        $update->month = $string;
        $update->year = $request->year;
        $update->subject_name = $request->subject_name;
        $update->link = $request->link;
        $update->answer_sheet = $request->answer_sheet;
        $update->marks = $request->marks;
		$update->exam_id=$request->exam;
		$update->board_id=$request->board;

        if ($update->save()) {
            return redirect('admin/show_previous_year_paper')->with('success', 'Previous Year Paper Updated Successfully');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Something went wrong');
        }
    }

    public function delete($id)
    {
        $query = PreviousPaper::where('id', $id)->delete();
        if ($query) {
            return redirect()
                ->back()
                ->with('success', 'Previous Year Paper Deleted Successfully');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Something went wrong');
        }
    }
}
