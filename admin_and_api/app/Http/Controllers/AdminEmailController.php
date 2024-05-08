<?php

namespace App\Http\Controllers;

use App\Models\AdminEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminEmailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function adminemail()
    {
        $adminemails = AdminEmail::all();
        return view('Admin/adminemail')->with('adminemail', $adminemails);
    }

    public function save_adminemail(Request $request)
    {
        $request ->validate([
            'name' => 'required',
            'email' => 'required|email',
        ]);

        $query=DB::table('adminemails')->insert([
            'name' => $request->name,
            'email' => $request->email,
        ]);
        if($query){
            return redirect()->back()->with('success', 'Admin Email Added Successfully');
        }else{
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    public function edit($id)
    {
        $adminemails = AdminEmail::find($id);
        return view('admin/editadminemail')->with('adminemail', $adminemails);
    }

    public function update_adminemail(Request $request)
    {
        $request ->validate([
            'name' => 'required',
            'email' => 'required|email',
        ]);

        $query=DB::table('adminemails')->where('id', $request->id)->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);
        if($query){
            return redirect()->back()->with('success', 'Admin Email Updated Successfully');
        }else{
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    public function delete($id)
    {
        $query=DB::table('adminemails')->where('id', $id)->delete();
        if($query){
            return redirect()->back()->with('success', 'Admin Email Deleted Successfully');
        }else{
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }


}
