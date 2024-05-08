<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show_testimonial()
    {
        $testi = Testimonial::all();
        return view('Admin/testimonial', compact('testi'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function save_testimonial(Request $request)
    {
        $request->validate([
            'client_name' => 'required',
            'client_note' => 'required',
        ]);

        $data = new Testimonial();
        $data->client_name = $request->client_name;
        $data->client_note = $request->client_note;
        $data->save();

        if ($data->save()) {
            return redirect()
                ->back()
                ->with('success', 'Testimonial Added Successfully');
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
        $testii = Testimonial::find($id);
        return view('admin/edittestimonial')->with('data', $testii);
    }

    public function update_testimonial(Request $request)
    {
        $request->validate([
            'client_name' => 'required',
            'client_note' => 'required',
        ]);

        $query = Testimonial::where('id', $request->pid)->update([
            'client_name' => $request->client_name,
            'client_note' => $request->client_note,
        ]);
        if ($query) {
            return redirect()
                ->back()
                ->with('success', 'Testimonial Updated Successfully');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Something went wrong');
        }
    }

    public function delete($id)
    {
        $query = Testimonial::where('id', $id)->delete();
        if ($query) {
            return redirect()
                ->back()
                ->with('success', 'Testimonial Deleted Successfully');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Something went wrong');
        }
    }

    public function show(Testimonial $testimonial)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Testimonial  $testimonial
     * @return \Illuminate\Http\Response
     */

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Testimonial  $testimonial
     * @return \Illuminate\Http\Response
     */
}
