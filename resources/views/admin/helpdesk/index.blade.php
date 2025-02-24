@extends('layouts.admin') <!-- Ensure your main layout file is used -->
@section('content')
<div class="container">
    <div class="helpdesk-info" style="text-align: center;">
        <br>
        <h1>NEED ANY HELP?</h1>
        <p>Please contact the developers for any enquiries and assistance.</p>
        <!-- Contact Hours Section -->
        <div class="mb-4">
            <h5><strong>Contact Hours ; </strong></h5>
            <p>Monday to Friday: <strong>9:00 AM - 6:00 PM</strong></p>
            <p>Saturday: <strong>10:00 AM - 2:00 PM</strong></p>
            <p>Sunday & Public Holidays: <strong>CLOSE</strong></p>
        </div>
    </div>
    
    <div class="row">
        <!-- First Line Card -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">NURIN EWANI BINTI SHAHUDIN</h5>
                    <p class="card-text">
                        <strong>Role: DEVELOPER</strong><br>
                        <strong>Email: nurinewani@gmail.com</strong><br>
                        <strong>Phone Number: +6011-1038 0118</strong>
                    </p>
                </div>
            </div>
        </div>
        <!-- Second Line Card -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">ADMIN TESTER</h5>
                    <p class="card-text">
                        <strong>Role: ADMINISTRATOR</strong><br>
                        <strong>Email: nurinewani@gmail.com</strong><br>
                        <strong>Phone Number: 012-345 6789</strong>
                    </p>
                </div>
            </div>
        </div>
        <!-- Third Line Card -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">ANOTHER USER</h5>
                    <p class="card-text">
                        <strong>Role: SUPPORT STAFF</strong><br>
                        <strong>Email: anotheruser@example.com</strong><br>
                        <strong>Phone Number: 012-345 6789</strong>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
