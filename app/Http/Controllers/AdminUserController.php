<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\AdminUserController;
use Illuminate\Support\Facades\Storage;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.user.index', compact('users'));
    }

    public function create()
    {
        return view('admin.user.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'address' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'role' => 'required|integer|in:0,1,2', // Ensure the role is valid
        ]);
    
        User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
            'address' => $validatedData['address'],
            'phone_number' => $validatedData['phone_number'],
            'role' => $validatedData['role'],
        ]);
    
        return redirect()->route('admin.user.index')->with('success', 'User created successfully!');
    }

    public function show(User $user)
    {
        return view('admin.user.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.user.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'address' => 'nullable|string',
            'phone_number' => 'nullable|string|max:20',
            'role' => 'required|integer|in:0,1,2',
            'emergency_contact' => 'nullable|string|max:255',
            'bank_account_name' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'duitnow_qr' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'recruiter_request_status' => 'nullable|in:pending,approved,rejected',
        ]);

        // Handle password update
        if ($request->filled('password')) {
            $validatedData['password'] = bcrypt($request->password);
        } else {
            unset($validatedData['password']);
        }

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            $validatedData['profile_picture'] = $request->file('profile_picture')
                ->store('profile-pictures', 'public');
        }

        // Handle DuitNow QR upload
        if ($request->hasFile('duitnow_qr')) {
            // Delete old DuitNow QR if exists
            if ($user->duitnow_qr) {
                Storage::disk('public')->delete($user->duitnow_qr);
            }
            $validatedData['duitnow_qr'] = $request->file('duitnow_qr')
                ->store('duitnow-qr', 'public');
        }

        $user->update($validatedData);

        return redirect()->route('admin.user.index')
            ->with('success', 'User updated successfully!');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.user.index')->with('success', 'User deleted successfully');
    }

    public function resetPassword(User $user)
    {
        $user->update(['password' => bcrypt('password')]);
        return redirect()->route('admin.user.index')->with('success', 'Password reset successfully');
    }

}
