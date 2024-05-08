<?php

namespace App\Http\Controllers;

use App\Models\Clientlogo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ClientlogoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show_clientlogo()
    {
        $clients = Clientlogo::all();
        return view('Admin/clientlogo', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function save_clientlogo(Request $request)
    {
        $request->validate([
            'logo_name' => 'required',
            'logo_img' => 'required',
        ]);

        $data = new Clientlogo();
        $data->logo_name = $request->logo_name;
        $file = $request->file('logo_img');

        if (isset($file)) {
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file_path = 'Clients_logos/';
            $data->logo_img = $file_path . $filename;
            $file->move($file_path, $filename);
        }

        $data->save();

        if ($data->save()) {
            return redirect()
                ->back()
                ->with('success', 'Client Logo Added Successfully');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Something went wrong');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $client = Clientlogo::find($id);
        return view('admin/editclientlogo')->with('data', $client);
    }

    public function update_clientlogo(Request $request, $id)
    {
        $request->validate([
            'logo_name' => 'required',
            'logo_img' => 'required',
        ]);

        // $query = Clientlogo::where('id', $request->pid)->update([
        //     'logo_name' => $request->logo_name,
        //     'logo_img' => $request->logo_img,
        // ]);

        $update = Clientlogo::find($id);
        $update->logo_name = $request->logo_name;

        if ($request->hasFile('logo_img')) {
            $file_path = 'Clients_logos/' . $update->logo_img;
            if (File::exists($file_path)) {
                File::delete($file_path);
            }
            $file = $request->file('logo_img');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $path = 'Clients_logos/';
            $file->move($path, $filename);
            $update->logo_img = $path . $filename;

            $filename = time() . '.' . $file->getClientOriginalExtension();
        }

        $update->update();

        if ($update->update()) {
            return redirect()
                ->back()
                ->with('success', 'Client Logo Updated Successfully');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Something went wrong');
        }
    }

    public function delete($id)
    {
        $query = Clientlogo::where('id', $id)->delete();
        if ($query) {
            return redirect()
                ->back()
                ->with('success', 'Client Logo Deleted Successfully');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Something went wrong');
        }
    }
}
