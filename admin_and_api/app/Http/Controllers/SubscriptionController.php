<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\Country;
use App\Models\School;

class SubscriptionController extends Controller
{
    public function subscription_plan_detail()
    {
        $subscription = Subscription::all();
        return view('Admin/subscription_plan_detail', compact('subscription'));
    }

    public function show_subscription_plan_detail()
    {
        $country = Country::all();
        $school = School::all();
        return view('Admin/add_subscription_plan_detail', compact('country', 'school'));
    }

    public function add_subscription(Request $request)
    {
        $request->validate([
            'plan_for' => 'required',
            'subscription_name' => 'required',
            'subscription_type' => 'required',
            'subscription_price' => 'required',
            // 'subscription_validity' => 'required',
            'sv_month' => 'required',
            'country' => 'required',
            'no_of_board' => 'required',
            'no_of_exam' => 'required',
            'no_of_subject' => 'required',
            'status' => 'required',
        ]);

        $data = new Subscription();
        $data->plan_for = $request->plan_for;
        $data->subscription_name = $request->subscription_name;
        $data->subscription_type = $request->subscription_type;
        $data->subscription_price = $request->subscription_price;
        // $data->subscription_validity = $request->subscription_validity;
        $data->sv_month = $request->sv_month;
        $data->country = $request->country;
        $data->no_of_board = $request->no_of_board;
        $data->no_of_exam = $request->no_of_exam;
        $data->no_of_subject = $request->no_of_subject;
        $data->status = $request->status;
        $data->save();
        if ($data->save()) {
            return redirect('admin/subscription_plan_detail')->with('success', 'Subscription Added Successfully');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Something went wrong');
        }
    }

public function edit($id)
{
    $country = Country::all();
    $school = School::all();
    $student = Subscription::find($id);
    return view('Admin.edit_subscription_plan_detail', compact('school', 'country'))->with('result', $student);
}

    public function save_status_subs(Request $request)
    {
        $update = Subscription::find($request->pid);
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

    public function update_subscription(Request $request)
    {
        $request->validate([
            'plan_for' => 'required',
            'subscription_name' => 'required',
            'subscription_type' => 'required',
            'subscription_price' => 'required',
            // 'subscription_validity' => 'required',
            'sv_month' => 'required',
            'country' => 'required',
            'no_of_board' => 'required',
            'no_of_exam' => 'required',
            'no_of_subject' => 'required',
            'status' => 'required',
        ]);

        $update = Subscription::find($request->pid);
        $update->plan_for = $request->plan_for;
        $update->subscription_name = $request->subscription_name;
        $update->subscription_type = $request->subscription_type;
        $update->subscription_price = $request->subscription_price;
        // $update->subscription_validity = $request->subscription_validity;
        $update->sv_month = $request->sv_month;
        $update->country = $request->country;
        $update->no_of_board = $request->no_of_board;
        $update->no_of_exam = $request->no_of_exam;
        $update->no_of_subject = $request->no_of_subject;
        $update->status = $request->status;
        $update->update();

        if ($update->update()) {
            return redirect('admin/subscription_plan_detail')->with('success', 'Subscription Updated Successfully');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Something went wrong');
        }
    }

    public function delete($id)
    {
        $query = Subscription::where('id', $id)->delete();
        if ($query) {
            return redirect()
                ->back()
                ->with('success', 'Subscription Deleted Successfully');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Something went wrong');
        }
    }
}
