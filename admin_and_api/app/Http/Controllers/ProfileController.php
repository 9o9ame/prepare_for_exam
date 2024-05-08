<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use DB;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        $profile = Profile::all()->first();
        return view('Admin/profile', compact('profile'));
    }

    public function passwordsave(Request $request)
    {
        $request->validate([
            'oldpassword' => 'required|max:255',
            'newpassword' => 'required|max:255',
            'confirmpassword' => 'required|max:255',
        ]);
        $oldpass = Profile::where('id', 1)->pluck('password');
        $oldpassw = str_replace(['["', '"]'], '', $oldpass);
        $oldpassword = md5($request->oldpassword);
        // return $str;
        if ($oldpassword == $oldpassw) {
            if ($request->newpassword == $request->confirmpassword) {
                $result = DB::table('admins')
                    ->where('id', 1)
                    ->update([
                        'password' => md5($request->newpassword),
                    ]);
                return back()->with('success', 'Password Updated Successfully');
            } else {
                return redirect()
                    ->back()
                    ->with('error', 'New Password does not match!');
            }
        } else {
            return redirect()
                ->back()
                ->with('error', 'Old Password does not match!');
        }
    }
}
