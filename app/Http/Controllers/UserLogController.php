<?php

namespace App\Http\Controllers;

use App\Models\UserLog;  // Ensure this is included
use Illuminate\Http\Request;

class UserLogController extends Controller
{
    public function index()
    {
        $userLogs = UserLog::latest()->take(5)->get(); // Without eager loading
    
        return view('admin.dashboard', compact('userLogs'));
    }
}
