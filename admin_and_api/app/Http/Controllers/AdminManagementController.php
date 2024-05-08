<?php

namespace App\Http\Controllers;

use App\Models\AdminManagement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function adminmanagement()
    {
        $admins = AdminManagement::all();
        return view('Admin/adminmanagement')->with('admins', $admins);
    }

    public function save_admin_management(Request $request)
    {
        $request ->validate([
            'name' => 'required',
            'email' => 'required|email',
            'contact' => 'required|min:10|max:10',
            'password' => 'required',
            'role' => 'required',
        ]);

        $query=DB::table('admins')->insert([
            'name' => $request->name,
            'email' => $request->email,
            'contact' => $request->contact,
            'password' => md5($request->password),
            'role' => $request->role,
        ]);
        if($query){
            return redirect()->back()->with('success', 'Admin Added Successfully');
        }else{
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    public function edit($id)
    {
        $admin = AdminManagement::find($id);
        return view('admin/editadminmanagement')->with('data', $admin);
    }

    public function update_admin_management(Request $request)
    {
        $request ->validate([
            'name' => 'required',
            'email' => 'required|email',
            'contact' => 'required|min:10|max:10',
            'password' => 'required',
            'role' => 'required',
        ]);
        $query=DB::table('admins')->where('id', $request->pid)->update([
            'name' => $request->name,
            'email' => $request->email,
            'contact' => $request->contact,
            'password' => md5($request->password),
            'role' => $request->role,
        ]);
        if($query){
            return redirect()->back()->with('success', 'Admin Updated Successfully');
        }else{
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    public function delete($id)
    {
        $query=DB::table('admins')->where('id', $id)->delete();
        if($query){
            return redirect()->back()->with('success', 'Admin Deleted Successfully');
        }else{
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

}
