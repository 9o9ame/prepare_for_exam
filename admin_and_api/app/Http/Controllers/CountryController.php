<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\Select_Country;
use Validator;
use Illuminate\Support\Facades\File;

class CountryController extends Controller
{
    public function show_country()
    {
        $country = Country::all();
        // $country1 = Country::all();
        $selected_country = Select_Country::all();
        return view('Admin/country', compact('country', 'selected_country'));
    }

    public function show_countrypage()
    {
        // to add class - separate page
        $country = Country::all();
        return view('Admin/addcountry', compact('country'));
    }

    public function save_country(Request $request)
    {
        $request->validate([
            'country_name' => 'required|unique:selected_countries',
        ]);

        $data = new Select_Country();
        $data->country_name = $request->country_name;
        $data->save();

        if ($data->save()) {
            return redirect('admin/show_country')->with('success', 'Country Added Successfully');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Something went wrong');
        }
    }

    // public function edit_country($id)
    // {
    //     $find_country = Select_Country::find($id);
    // }
    public function update_country(Request $request)
    {
        $request->validate([
            'country_name' => 'required',
        ]);

        $update = Select_Country::find($request->pid);
        $update->country_name = $request->country_name;
        $update->update();

        if ($update->save()) {
            return redirect()
                ->back()
                ->with('success', 'Country Updated Successfully');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Something went wrong');
        }
    }

    public function delete_country($id)
    {
        $query = Select_Country::where('id', $id)->delete();
        if ($query) {
            return redirect()
                ->back()
                ->with('success', 'Country Deleted Successfully');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Something went wrong');
        }
    }
}
