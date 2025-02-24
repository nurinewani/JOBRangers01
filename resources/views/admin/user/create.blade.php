@extends('layouts.admin')
@section('content')

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="{{ asset('css/app.css') }}"> <!-- Optional: link to your CSS -->
    </head>

<body>
    <div class="container">
        <div class="card">
            <div class="card-header" style="background-color:rgb(0, 37, 68); color: white;">
                <h1>Edit User Details</h1>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.user.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    

                    <div class="row">
                        <!-- Basic Information -->
                        <div class="col-md-6">
                            <h3>Basic Information</h3>
                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input type="text" name="name" id="name" class="form-control" value="{{ $user->name }}" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}" required>
                            </div>
                            <div class="form-group">
                                <label for="password">New Password: (Leave blank to keep current password)</label>
                                <input type="password" name="password" id="password" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="phone_number">Phone Number:</label>
                                <input type="text" name="phone_number" id="phone_number" class="form-control" value="{{ $user->phone_number }}">
                            </div>
                            <div class="form-group">
                                <label for="address">Address:</label>
                                <textarea name="address" id="address" class="form-control" rows="3">{{ $user->address }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="emergency_contact">Emergency Contact:</label>
                                <input type="text" name="emergency_contact" id="emergency_contact" class="form-control" value="{{ $user->emergency_contact }}">
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="col-md-6">
                            <h3>Additional Information</h3>
                            <div class="form-group">
                                <label for="role">Role:</label>
                                <select name="role" id="role" class="form-control" required>
                                    <option value="0" {{ $user->role == 0 ? 'selected' : '' }}>User</option>
                                    <option value="1" {{ $user->role == 1 ? 'selected' : '' }}>Recruiter</option>
                                    <option value="2" {{ $user->role == 2 ? 'selected' : '' }}>Admin</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="profile_picture">Profile Picture:</label>
                                @if($user->profile_picture)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Current Profile Picture" class="img-thumbnail" style="max-width: 150px;">
                                    </div>
                                @endif
                                <input type="file" name="profile_picture" id="profile_picture" class="form-control-file">
                            </div>
                            
                            <!-- Banking Information -->
                            <h4 class="mt-4">Banking Information</h4>
                            <div class="form-group">
                                <label for="bank_name">Bank Name:</label>
                                <input type="text" name="bank_name" id="bank_name" class="form-control" value="{{ $user->bank_name }}">
                            </div>
                            <div class="form-group">
                                <label for="bank_account_name">Bank Account Name:</label>
                                <input type="text" name="bank_account_name" id="bank_account_name" class="form-control" value="{{ $user->bank_account_name }}">
                            </div>
                            <div class="form-group">
                                <label for="bank_account_number">Bank Account Number:</label>
                                <input type="text" name="bank_account_number" id="bank_account_number" class="form-control" value="{{ $user->bank_account_number }}">
                            </div>
                            <div class="form-group">
                                <label for="duitnow_qr">DuitNow QR:</label>
                                @if($user->duitnow_qr)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $user->duitnow_qr) }}" alt="Current DuitNow QR" class="img-thumbnail" style="max-width: 150px;">
                                    </div>
                                @endif
                                <input type="file" name="duitnow_qr" id="duitnow_qr" class="form-control-file">
                            </div>

                            @if($user->role == 0)
                                <div class="form-group">
                                    <label for="recruiter_request_status">Recruiter Request Status:</label>
                                    <select name="recruiter_request_status" id="recruiter_request_status" class="form-control">
                                        <option value="">None</option>
                                        <option value="pending" {{ $user->recruiter_request_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="approved" {{ $user->recruiter_request_status == 'approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="rejected" {{ $user->recruiter_request_status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Update User</button>
                            <a href="{{ route('admin.user.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .card {
            margin-top: 20px;
            margin-bottom: 20px;
        }
        
        .form-group {
            margin-bottom: 1rem;
        }

        h3, h4 {
            margin-bottom: 1.5rem;
            color: #2c3e50;
        }
    </style>
    @endpush
</body>

@endsection
