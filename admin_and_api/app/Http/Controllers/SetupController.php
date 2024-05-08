<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;
use App\Models\Board;
use App\Models\Subject;
use App\Models\Classs;
use App\Models\Exam;
use App\Models\Exam_Subject;
use App\Models\Exam_Subject_Board;
use Validator;
use Illuminate\Support\Facades\File;
use Storage;
use Illuminate\Support\Str;

class SetupController extends Controller
{
    public function show_school()
    {
        $school = School::all();
        return view('Admin/school', compact('school'));
    }

    public function show_schoolpage()
    {
        // to add school - separate page
        return view('Admin/addschool');
    }

    public function show_exam()
    {
        $exam = Exam::all();
        return view('Admin/exam', compact('exam'));
    }

    public function show_exampage()
    {
        // to add exam - separate page
        $subject = Subject::all();
        return view('Admin/addexam', compact('subject'));
    }

    public function edit_exam($id)
    {
        $subject = Subject::all();
        $student = Exam::find($id);
        // $selected_subject = Exam_Subject::where('exam_id', $id)->get('subject_id');
        $data = Exam_Subject::select('exam_subjects.*', 'subjects.subject_name')
            ->join('subjects', 'subjects.id', 'exam_subjects.subject_id')
            // ->select('exam_subjects.*', 'subjects.subject_name')
            // ->where('subjects.id', $id)
            ->get();

        // return $data;
        // exit();
        // $string = implode(',', $selected_subject);
        // $getsubject[0] = Subject::where('id', $selected_subject[0])->get(['subject_name']);

        return view('Admin/editexam', compact('subject'))->with('result', $student);
    }

    public function show_board()
    {
        $board = Board::all();
        return view('Admin/board', compact('board'));
    }

    public function show_boardpage()
    {
        // to add board - separate page
        return view('Admin/addboard');
    }

    public function show_subject()
    {
        $subject = Subject::all();
        return view('Admin/subject', compact('subject'));
    }

    public function show_subjectpage()
    {
        $exam = Exam::all();
        $board = Board::all();
        // to add show_subject - separate page
        return view('Admin/addsubject', compact('exam', 'board'));
    }

    public function show_class()
    {
        $classs = Classs::all();
        return view('Admin/class', compact('classs'));
    }

    public function show_classpage()
    {
        // to add class - separate page
        return view('Admin/addclass');
    }

    // School Section
    public function save_school(Request $request)
    {
        $request->validate([
            'school_name' => 'required',
        ]);

        $data = new School();
        $data->school_name = $request->school_name;
        $data->save();

        if ($data->save()) {
            return redirect('admin/show_school')->with('success', 'School Added Successfully');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Something went wrong');
        }
    }

    public function update_school(Request $request)
    {
        $request->validate([
            'school_name' => 'required',
        ]);

        $update = School::find($request->pid);
        $update->school_name = $request->school_name;
        $update->update();

        if ($update->save()) {
            return redirect()
                ->back()
                ->with('success', 'School Updated Successfully');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Something went wrong');
        }
    }

    public function delete_school($id)
    {
        $query = School::where('id', $id)->delete();
        if ($query) {
            return redirect()
                ->back()
                ->with('success', 'School Deleted Successfully');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Something went wrong');
        }
    }

    //Exam Section
    public function save_exam(Request $request)
    {
        $request->validate([
            'exam_name' => 'required',
            'status' => 'required',
        ]);

        $data = new Exam();
        $data->exam_name = $request->exam_name;
        $data->status = $request->status;
        $data->save();

        // $dataa = new Exam_Subject();
        // $dataa->exam_id = $data->id;
        // $string = implode(',', $request->subject);
        // $dataa->subject_id = $string;
        // if (isset($request->subject)) {
        //     $dataa = [];
        //     $x = 0;
        //     foreach ($request->subject as $da) {
        //         $dataa[$x]['exam_id'] = $data->id;
        //         $dataa[$x]['subject_id'] = $da;
        //         $x++;
        //     }
        //     Exam_Subject::insert($dataa);
        //     // $a = $dataa->save();
        // }

        if ($data->save()) {
            return redirect('admin/show_exam')->with('success', 'Exam Added Successfully');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Something went wrong');
        }
    }

    public function update_exam(Request $request)
    {
        $request->validate([
            'exam_name' => 'required',
        ]);

        $update = Exam::find($request->pid);
        $update->exam_name = $request->exam_name;
        $update->update();

        // $updatee = Exam_Subject::find($request->pid);
        // // $updatee->exam_id = $data->id;
        // $updatee = implode(',', $request->subject);
        // $updatee->subject_id = $string;
        // $updatee->save();

        if ($update->save()) {
            return redirect()
                ->back()
                ->with('success', 'Exam Updated Successfully');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Something went wrong');
        }
    }

    public function delete_exam($id)
    {
        $query = Exam::where('id', $id)->delete();
        if ($query) {
            return redirect()
                ->back()
                ->with('success', 'Exam Deleted Successfully');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Something went wrong');
        }
    }

    public function save_status_exam(Request $request)
    {
        $update = Exam::find($request->pid);
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

    //add exam_subject
    public function add_exam_subjects()
    {
        $exam = Exam::all();
        $subject = Subject::all();
        return view('Admin/addexam_subject', compact('exam', 'subject'));
    }

    public function save_examsubjects(Request $request)
    {
        $request->validate([
            'exam' => 'required',
            'subject' => 'required',
        ]);

        if (isset($request->subject)) {
            $dataa = [];
            $x = 0;
            foreach ($request->subject as $da) {
                $dataa[$x]['exam_id'] = $request->exam;
                $dataa[$x]['subject_id'] = $da;
                $x++;
            }
            $data = Exam_Subject::insert($dataa);
            // $a = $dataa->save();
        }

        if ($data) {
            return redirect('admin/show_exam')->with('success', 'Exam - Subject Added Successfully');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Something went wrong');
        }
    }

    //Board Section
    public function save_board(Request $request)
    {
        $request->validate([
            'board_name' => 'required',
        ]);

        $data = new Board();
        $data->board_name = $request->board_name;
        $data->save();

        if ($data->save()) {
            return redirect('admin/show_board')->with('success', 'Board Added Successfully');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Something went wrong');
        }
    }

    public function update_board(Request $request)
    {
        $request->validate([
            'board_name' => 'required',
        ]);

        $update = Board::find($request->pid);
        $update->board_name = $request->board_name;
        $update->update();

        if ($update->save()) {
            return redirect()
                ->back()
                ->with('success', 'Board Updated Successfully');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Something went wrong');
        }
    }

    public function delete_board($id)
    {
        $query = Board::where('id', $id)->delete();
        if ($query) {
            return redirect()
                ->back()
                ->with('success', 'Board Deleted Successfully');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Something went wrong');
        }
    }

    //Subject Section
    public function save_subject(Request $request)
    {
        $request->validate([
            'subject_name' => 'required',
        ]);

        $data = new Subject();
        $data->subject_name = $request->subject_name;
        $data->save();

        // if (isset($request->boards)) {
        //     $dataa = [];
        //     $x = 0;
        //     foreach ($request->boards as $da) {
        //         $dataa[$x]['subject_id'] = $data->id;
        //         $dataa[$x]['exam_id'] = $request->exam;
        //         $dataa[$x]['board_id'] = $da;
        //         $x++;
        //     }
        //     Exam_Subject_Board::insert($dataa);
        //     // $a = $dataa->save();
        // }

        if ($data->save()) {
            return redirect('admin/show_subject')->with('success', 'Subject Added Successfully');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Something went wrong');
        }
    }

    public function update_subject(Request $request)
    {
        $request->validate([
            'subject_name' => 'required',
        ]);

        $update = Subject::find($request->pid);
        $update->subject_name = $request->subject_name;
        $update->update();

        if ($update->save()) {
            return redirect()
                ->back()
                ->with('success', 'Subject Updated Successfully');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Something went wrong');
        }
    }

    public function delete_subject($id)
    {
        $query = Subject::where('id', $id)->delete();
        if ($query) {
            return redirect()
                ->back()
                ->with('success', 'Subject Deleted Successfully');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Something went wrong');
        }
    }

    public function add_exam_subjects_board()
    {
        $exam = Exam::all();
        $subject = Subject::all();
        $board = Board::all();
        return view('Admin/addexam_subject_board', compact('exam', 'subject', 'board'));
    }

    public function save_examsubjectsboard(Request $request)
    {
        $request->validate([
            'exam' => 'required',
            'subject' => 'required',
            'boards' => 'required',
        ]);

        if (isset($request->boards)) {
            $dataa = [];
            $x = 0;
            foreach ($request->boards as $da) {
                $dataa[$x]['subject_id'] = $request->subject;
                $dataa[$x]['exam_id'] = $request->exam;
                $dataa[$x]['board_id'] = $da;
                $x++;
            }
            $data = Exam_Subject_Board::insert($dataa);
            // $a = $dataa->save();
        }

        if ($data) {
            return redirect('admin/show_subject')->with('success', 'Exam, Subject, Boards Connected Successfully');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Something went wrong');
        }
    }

    //Class Section
    public function save_class(Request $request)
    {
        $request->validate([
            'class_name' => 'required',
        ]);

        $data = new Classs();
        $data->class_name = $request->class_name;
        $data->save();

        if ($data->save()) {
            return redirect('admin/show_class')->with('success', 'Class Added Successfully');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Something went wrong');
        }
    }

    public function update_class(Request $request)
    {
        $request->validate([
            'class_name' => 'required',
        ]);

        $update = Classs::find($request->pid);
        $update->class_name = $request->class_name;
        $update->update();

        if ($update->save()) {
            return redirect()
                ->back()
                ->with('success', 'Class Updated Successfully');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Something went wrong');
        }
    }

    public function delete_class($id)
    {
        $query = Classs::where('id', $id)->delete();
        if ($query) {
            return redirect()
                ->back()
                ->with('success', 'Class Deleted Successfully');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Something went wrong');
        }
    }
}
