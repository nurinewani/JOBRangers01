<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f4f8;
            margin: 0;
        }
        .navbar {
            background-color: #002a44;
            padding: 10px 20px;
        }
        .navbar a {
            color: white;
            margin: 0 15px;
            text-decoration: none;
        }
        .navbar a:hover {
            text-decoration: underline;
        }
        .btn-find-jobs {
            background-color: #ffcc00;
            color: #002a44;
            border: none;
            padding: 8px 15px;
            font-weight: bold;
        }
        .btn-find-jobs:hover {
            background-color: #ffb700;
        }
        .container {
            max-width: auto;
            margin: 20px auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <img src="./img/JR1.png" alt="Company Logo" width="50" height="50">
            <<div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a href="#about" class="nav-link">About JOB Rangers</a></li>
                    <li class="nav-item"><a href="#vision" class="nav-link">Learn More</a></li>
                    <li class="nav-item"><a href="{{ route('login') }}" class="btn btn-warning text-dark"><strong>Login<strong></a></li>
                </ul>
            </div>
        </div>
    </nav>
    <main>
        @yield('content')
    </main>

    <form id="csrf-form" method="POST" style="display: none;">
        @csrf
    </form>

    <script>
        // Keeping the CSRF token accessible for forms
        const csrfForm = document.getElementById('csrf-form');
        const csrfToken = csrfForm.querySelector('input[name="_token"]').value;

        // Example: Adding CSRF token dynamically to forms if needed
        function attachCSRFToken(form) {
            if (!form.querySelector('input[name="_token"]')) {
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;
                form.appendChild(csrfInput);
            }
        }
    </script>
</body>
</html>
