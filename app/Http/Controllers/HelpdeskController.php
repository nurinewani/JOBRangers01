<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HelpdeskController extends Controller
{
    public function index()
    {
        return view('helpdesk.index');
    }
}
