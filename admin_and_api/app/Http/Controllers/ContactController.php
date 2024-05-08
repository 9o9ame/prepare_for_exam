<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Enquiry;

class ContactController extends Controller
{
    public function contact()
    {
        $enquiry = Enquiry::all();
        return view('Admin/contact')->with('enquiry', $enquiry);
    }
}
