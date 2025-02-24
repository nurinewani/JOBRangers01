<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;

class ProfileController extends Controller
{
    // Display the user profile
    public function show()
    {
        $user = Auth::user();  // Get the currently authenticated user
        return view('profile.show', compact('user'));
    }

    // Show the form for editing the profile
    public function edit()
    {
        $user = Auth::user();  // Get the currently authenticated user
        return view('profile.edit', compact('user'));
    }

    // Update the user's profile data (name, email, phone number, etc.)
    public function update(Request $request)
    {
        try {
            $user = Auth::user();

            // Validate the input
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'phone_number' => 'nullable|string|max:255',
                'address' => 'nullable|string|max:255',
                'bank_account_name' => 'nullable|string|max:255',
                'bank_account_number' => 'nullable|string|max:255',
                'bank_name' => 'nullable|string|max:255',
                'duitnow_qr' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Prepare data for update
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'bank_account_name' => $request->bank_account_name,
                'bank_account_number' => $request->bank_account_number,
                'bank_name' => $request->bank_name,
            ];

            // Handle DuitNow QR image upload if provided
            if ($request->hasFile('duitnow_qr')) {
                // Delete old image if exists
                if ($user->duitnow_qr) {
                    Storage::disk('public')->delete($user->duitnow_qr);
                }
                
                // Store new image
                $data['duitnow_qr'] = $request->file('duitnow_qr')->store('duitnow_qr', 'public');
            }

            // Update user information
            $user->update($data);

            return redirect()->route('profile.show')
                           ->with('success', 'Profile updated successfully!');
        } catch (\Exception $e) {
            return redirect()->route('profile.show')
                           ->with('error', 'Failed to update profile. Please try again.');
        }
    }

    // Update the profile photo
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();  // Get the currently authenticated user

        // Handle file upload
        $path = $request->file('profile_photo')->store('profile_photos', 'public');

        // Update the user's profile photo
        $user->update([
            'profile_photo' => $path,
        ]);

        return redirect()->route('profile.show')->with('success', 'Profile photo updated successfully!');
    }
}

