<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminHelpdeskController extends Controller
{
    public function index()
    {
        return view('admin.helpdesk.index');
    }
}
