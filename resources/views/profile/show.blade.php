@extends('layouts.user')

@section('content')
    <div class="container mt-5">
        <h2 class="text-center mb-4">{{ Auth::user()->name }}'s Profile</h2>

        <div class="row justify-content-center">
            <!-- Profile Photo -->
            <div class="col-md-4 text-center mb-4">
                <img src="{{asset('admin/dist/img/animoji1.jpg')}}" alt="Profile Photo" class="rounded-circle" width="250" height="250">
            </div>

            <!-- Profile Information -->
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Personal Information</h4>
                        <ul class="list-unstyled">
                            <br>
                            <br>
                            <li><strong>Name:</strong> {{ $user->name }}</li>
                            <li><strong>Email:</strong> {{ $user->email }}</li>
                            <li><strong>Phone Number:</strong> {{ $user->phone_number }}</li>
                            <li><strong>Address:</strong> {{ $user->address }}</li>
                            <li><strong>Bank Name:</strong> {{ $user->bank_name }}</li>
                            <li><strong>Bank Account Name:</strong> {{ $user->bank_account_name }}</li>
                            <li><strong>Bank Account Number:</strong> {{ $user->bank_account_number }}</li>
                            <li>
                                <strong>DuitNow QR:</strong>
                                @if($user->duitnow_qr)
                                    <div class="mt-2">
                                        <img src="{{asset('./img/JRclear.jpg')}}" 
                                             alt="DuitNow QR" 
                                             class="img-thumbnail"
                                             width="150" 
                                             height="150">

                                    </div>
                                @else
                                    <p class="text-muted">No QR code uploaded</p>
                                @endif
                            </li>
                        </ul>
                        
                            <!-- Edit Profile Button -->
                            <div class="text-center mt-4">
                                <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-lg">Edit Profile</a>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
