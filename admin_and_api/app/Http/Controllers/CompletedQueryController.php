<?php

namespace App\Http\Controllers;

use App\Models\CompletedQuery;
use App\Models\AllQuery;
use App\Models\ManageStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CompletedQueryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function completedqueries()
    {
        // $queries =AllQuery::where('status','Completed')->get();
        // $status = ManageStatus::all();
        return view('Admin/completedqueries');
    }

    public function fetchbystatus($token)
    {
        // return $token;
        if($token == 'all'){
            $queries = CompletedQuery::all();
        }else{
            $queries = CompletedQuery::where('status', $token)->get();
        }
        $status = ManageStatus::all();
        return view('Admin/completedqueries')-> with('queries', $queries)->with('status', $status)->with('token',$token);
    }



    public function update_change_status(Request $request)
    {

        $request ->validate([
            'status' => 'required',
        ]);
        $query=DB::table('queries')->where('id', $request->id)->update([
            'status' => $request->status,
        ]);
        if($query){
            return redirect()->back()->with('success', 'Status Updated Successfully');
        }else{
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }


}
