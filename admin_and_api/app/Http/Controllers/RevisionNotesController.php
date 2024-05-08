<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RevisionNotes;
use App\Models\Subject;
use App\Models\Classs;
use App\Models\QuestionSet;
use Validator;
use Illuminate\Support\Facades\File;

class RevisionNotesController extends Controller
{
    public function show_revision_notes()
    {
        $revision_notes = RevisionNotes::all();

        return view('Admin/revision_notes', compact('revision_notes'));
    }

    public function show_revision_notespage()
    {
		
        $class = Classs::all();
        $subject_code = QuestionSet::select('subject_code')
            ->distinct()
            ->get();
        $subject_name = QuestionSet::select('subject_id')
            ->distinct()
            ->get();
        $topic = QuestionSet::select('topic')
            ->distinct()
            ->get();
        $sub_topic = QuestionSet::select('sub_topic')
            ->distinct()
            ->get();
        return view('Admin/addrevision_notes', compact('subject_name', 'subject_code', 'topic', 'sub_topic', 'class'));
    }

    public function save_revision_notes(Request $request)
    {
        $request->validate([
            'subject_code' => 'required',
            // 'subject' => 'required',
            'topic' => 'required',
            'sub_topic' => 'required',
            'class' => 'required',
            'image' => 'required',
        ]);

        $data = new RevisionNotes();
        $data->subject_code = $request->subject_code;
        $data->subject = $request->subject;
        $data->topic = $request->topic;
        $data->sub_topic = $request->sub_topic;
        $data->class = $request->class;
        $file = $request->file('image');

        if (isset($file)) {
            $filename = $file->getClientOriginalName();
            //return $filename;
            $file->move('Revision_Notes', $filename);
        } else {
            $filename = '-';
        }
        $data->image = $filename;
        $data->save();
        if ($data->save()) {
            return redirect('admin/show_revision_notes')->with('success', 'Question Set Added Successfully');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Something went wrong');
        }
    }

    public function edit($id)
    {
        $class = Classs::all();
        $subject_code = QuestionSet::select('subject_code')
            ->distinct()
            ->get();
        $subject_name = QuestionSet::select(['subject_id'])
            ->distinct()
            ->get();
        $topic = QuestionSet::select('topic')
            ->distinct()
            ->get();
        $sub_topic = QuestionSet::select('sub_topic')
            ->distinct()
            ->get();
        $question = RevisionNotes::find($id);
        return view('Admin.editrevision_notes', compact('subject_name', 'subject_code', 'topic', 'sub_topic', 'class'))->with('result', $question);
    }
}
