<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Form;


class LoanController extends Controller
{
    public function loan()
    {
        $form = Form::all();
        return view('Admin/loan')->with('form', $form);
    }
}
