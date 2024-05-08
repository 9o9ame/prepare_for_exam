<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\File;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show_video()
    {
        $video = Video::all();
        return view('Admin/video', compact('video'));
    }

    public function save_video(Request $request)
    {
        $request->validate([
            'video_name' => 'required',
            'video_url' => 'required',
            // 'video' => 'required',
        ]);

        $data = new Video();
        $data->video_name = $request->video_name;
        $data->video_url = $request->video_url;
        $file = $request->file('video');

        if (isset($file)) {
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file_path = 'upload/';
            $file->move($file_path, $filename);
        } else {
            $file_path = 'upload/';
            $filename = '-';
        }
        $data->video = $file_path . $filename;
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

    public function edit($id)
    {
        $video = Video::find($id);
        return view('updatevideo')->with('result', $updatevideo);
    }

    public function update_video(Request $request)
    {
        $request->validate([
            'video_name' => 'required',
            'video_url' => 'required',
            // 'video' => 'required',
        ]);

        $update = Video::find($request->pid);
        $update->video_name = $request->video_name;
        $update->video_url = $request->video_url;

        if ($request->hasFile('video')) {
            $file_path = 'upload/' . $update->video;
            if (File::exists($file_path)) {
                File::delete($file_path);
            }
            $file = $request->file('video');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $path = 'upload/';
            $file->move($path, $filename);
            $update->video = $path . $filename;

            $filename = time() . '.' . $file->getClientOriginalExtension();
        }

        $update->update();

        if ($update->save()) {
            return redirect()
                ->back()
                ->with('success', 'Video Updated Successfully');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Something went wrong');
        }
    }

    public function delete($id)
    {
        $query = Video::where('id', $id)->delete();
        if ($query) {
            return redirect()
                ->back()
                ->with('success', 'Video Deleted Successfully');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Something went wrong');
        }
    }
}
