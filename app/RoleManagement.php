<?php

use App\Models\User;
use Spatie\Permission\Models\Role;

// Create roles (only needed once)
Role::create(['name' => 'admin']);
Role::create(['name' => 'recruiter']);
Role::create(['name' => 'user']);

// Assign a role to a user
$user = User::find(1); // Replace 1 with the admin user's ID
$user->assignRole('admin');
$user->role = 'admin'; // This will save the numeric value (2) to the database

// Checking if a user has a role
if ($user->hasRole('admin')) {
    // User has admin role
}

// Direct numeric check (optional for backward compatibility)
if ($user->role == 2) {
    // Admin-specific logic
}