@extends('layouts.user')

@section('content')
    <div class="container">
        <h2 class="mb-20">Edit Profile</h2>

        <!-- Display success message -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Display error message -->
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Display validation errors -->
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-md-12">
                <!-- Personal Information Form -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <strong>Personal Information</strong>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('profile.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="phone_number" class="form-label">Phone Number</label>
                                <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $user->phone_number) }}" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea name="address" id="address" class="form-control">{{ old('address', $user->address) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="bank_account_name" class="form-label">Bank Account Name</label>
                                <input type="text" name="bank_account_name" id="bank_account_name" value="{{ old('bank_account_name', $user->bank_account_name) }}" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label for="bank_account_number" class="form-label">Bank Account Number</label>
                                <input type="text" name="bank_account_number" id="bank_account_number" value="{{ old('bank_account_number', $user->bank_account_number) }}" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label for="bank_name" class="form-label">Bank Name</label>
                                <input type="text" name="bank_name" id="bank_name" value="{{ old('bank_name', $user->bank_name) }}" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label for="duitnow_qr" class="form-label">DuitNow QR Code</label>
                                <input type="file" name="duitnow_qr" id="duitnow_qr" class="form-control" accept="image/*">
                                @if($user->duitnow_qr)
                                    <div class="mt-2">
                                        <img src="{{ Storage::url($user->duitnow_qr) }}" alt="DuitNow QR Code" class="img-thumbnail" style="max-width: 200px;">
                                    </div>
                                @endif
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Profile Photo Update Form -->
                <div class="card mb-4">
                    <div class="card-header bg-secondary text-white">
                        <strong>Update Profile Photo</strong>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('profile.update.photo') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="profile_photo" class="form-label">Profile Photo</label>
                                <input type="file" name="profile_photo" id="profile_photo" class="form-control" accept="image/*" required>
                            </div>

                            <button type="submit" class="btn btn-primary">Upload Photo</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
