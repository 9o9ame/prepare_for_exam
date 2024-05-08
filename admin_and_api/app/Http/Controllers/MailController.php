<?php

namespace App\Http\Controllers;

use App\Models\Mail_Resume;
use Illuminate\Http\Request;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Mail\MailNotify;
use Mail;

class MailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show_resume()
    {
        $resume = Mail_Resume::all();
        return view('admin/resume', compact('resume'));
    }

    public function save_resume(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'contact' => 'required',
            'resume' => 'required',
        ]);

        $data = new Mail_Resume();
        $data->name = $request->name;
        $data->email = $request->email;
        $data->contact = $request->contact;
        $file = $request->file('resume');

        if (isset($file)) {
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file_path = 'resume/';
            $data->resume = $file_path . $filename;
            $file->move($file_path, $filename);
        }

        $data->save();

        if ($data->save()) {
            return redirect()
                ->back()
                ->with('success', 'Video Added Successfully');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Something went wrong');
        }
    }


    public function send_mail(Request $request)
    {
        $data = [
            'subject' => 'Hello Friend',
            'body' => 'hii, this is a demo mail!',
        ];
        try {
            Mail::to('deepsingh.webixun@gmail.com')->send(new MailNotify($data));
            return response()->json(['Great Check your mail box']);
        } catch (Exception $th) {
            return response()->json(['Sorry something went wrong']);
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
}
