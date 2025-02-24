<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JOB Rangers - Your Student Job Platform</title>
    <!-- Inter Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Apply Inter font to all elements */
        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: #E2EBF3;
            overflow-x: hidden;
        }

        /* Font weight utilities */
        .font-thin {
            font-weight: 100;
        }
        .font-light {
            font-weight: 300;
        }
        .font-regular {
            font-weight: 400;
        }
        .font-medium {
            font-weight: 500;
        }
        .font-semibold {
            font-weight: 600;
        }
        .font-bold {
            font-weight: 700;
        }
        .font-extrabold {
            font-weight: 800;
        }


        /* Update specific elements to use Inter with appropriate weights */
        .header-section h1 {
            font-family: 'Inter', sans-serif;
            font-weight: 800;
            font-size: 3.5rem;
            margin-bottom: 20px;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
        }

        .navbar-brand {
            font-family: 'Inter', sans-serif;
            font-weight: 700;
            color: white;
        }

        h2 {
            font-family: 'Inter', sans-serif;
            font-weight: 700;
        }

        h4 {
            font-family: 'Inter', sans-serif;
            font-weight: 600;
        }

        .btn {
            font-family: 'Inter', sans-serif;
            font-weight: 600;
        }

        .lead {
            font-family: 'Inter', sans-serif;
            font-weight: 400;
        }

        .header-section {
            background-image: url('./img/landing-page5.png');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 220px 0;
            text-align: center;
            position: relative;
        }
        .header-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
        }
        .header-content {
            position: relative;
            z-index: 1;
        }
        .navbar {
            background-color: #0a1e42;
            padding: 15px;
        }
        .navbar-toggler {
            border-color: rgba(255,255,255,0.5);
        }
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.7%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
        .navbar a {
            color: white;
            text-decoration: none;
            margin-left: 15px;
        }
        .nav-link:hover {
            color: #ffc107 !important;
        }
        .section {
            padding: 60px 0;
            background-color: white;
        }
        .section:nth-child(even) {
            background-color: #f8f9fa;
        }
        .feature-card {
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            height: 100%;
            background-color: white;
            transition: transform 0.3s ease;
            margin-bottom: 20px;
        }
        .feature-card:hover {
            transform: translateY(-5px);
        }
        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: #0a1e42;
        }
        .contact-info {
            background-color: #0a1e42;
            color: white;
            padding: 60px 0;
        }
        .btn-warning {
            padding: 12px 30px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .objective-item {
            margin-bottom: 30px;
            padding: 20px;
            border-left: 4px solid #0a1e42;
            background-color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Mobile Responsive Styles */
        @media (max-width: 768px) {
            .header-section {
                padding: 100px 0;
            }
            
            .header-section h1 {
                font-size: 2.5rem;
            }
            
            .navbar {
                padding: 10px;
            }
            
            .navbar-collapse {
                background-color: #0a1e42;
                padding: 15px;
                border-radius: 8px;
                margin-top: 10px;
            }
            
            .nav-item {
                margin: 8px 0;
            }
            
            .btn-warning {
                padding: 8px 20px;
                display: inline-block;
                margin-top: 10px;
            }
            
            .section {
                padding: 40px 0;
            }
            
            .feature-card {
                margin-bottom: 20px;
            }
            
            .objective-item {
                margin-bottom: 20px;
            }
            
            .contact-info {
                padding: 40px 0;
            }
            
            .contact-info .col-md-4 {
                margin-bottom: 30px;
            }
        }

        /* Small Mobile Devices */
        @media (max-width: 576px) {
            .header-section h1 {
                font-size: 2rem;
            }
            
            .lead {
                font-size: 1rem;
            }
            
            .btn-warning, .btn-outline-light {
                padding: 8px 16px;
                font-size: 0.9rem;
                margin: 5px;
            }
            
            .feature-icon {
                font-size: 2rem;
            }
            
            h2 {
                font-size: 1.8rem;
            }
            
            h4 {
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <img src="./img/JR1.png" alt="Company Logo" width="50" height="50">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a href="#about" class="nav-link">About</a></li>
                    <li class="nav-item"><a href="#objectives" class="nav-link">Objectives</a></li>
                    <li class="nav-item"><a href="#features" class="nav-link">Features</a></li>
                    <li class="nav-item"><a href="#contact" class="nav-link">Contact</a></li>
                    <li class="nav-item"><a href="{{ route('login') }}" class="btn btn-warning text-dark"><strong>Login</strong></a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Header Section -->
    <section class="header-section">
        <div class="container header-content">
            <h1>Welcome to JOB Rangers</h1>
            <p class="lead mb-4">Your Gateway to Student Employment Opportunities</p>
            <div>
                <a href="/register" class="btn btn-warning me-3">Join Now!</a>
                <a href="{{ route('login') }}" class="btn btn-outline-light">Login</a>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="section">
        <div class="container">
            <h2 class="text-center mb-5">About JOB Rangers</h2>
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <p class="lead text-center">
                        JOB Rangers is a dedicated platform designed to bridge the gap between students seeking part-time employment 
                        and recruiters looking for talented individuals. We understand the unique challenges students face in 
                        balancing their academic commitments with work opportunities, and we're here to make that journey easier.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Objectives Section -->
    <section id="objectives" class="section">
        <div class="container">
            <h2 class="text-center mb-5">Our Objectives</h2>
            <div class="row">
                <div class="col-md-6">
                    <div class="objective-item">
                        <h4><i class="fas fa-handshake me-2"></i>Connect Students with Opportunities</h4>
                        <p>Facilitate meaningful connections between students and potential employers.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="objective-item">
                        <h4><i class="fas fa-balance-scale me-2"></i>Promote Work-Study Balance</h4>
                        <p>Help students find flexible work arrangements that complement their studies.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="objective-item">
                        <h4><i class="fas fa-shield-alt me-2"></i>Ensure Safe Employment</h4>
                        <p>Verify and monitor employers to maintain a safe working environment.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="objective-item">
                        <h4><i class="fas fa-graduation-cap me-2"></i>Support Student Growth</h4>
                        <p>Provide opportunities for skill development and professional growth.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="section">
        <div class="container">
            <h2 class="text-center mb-5">Platform Features</h2>
            <div class="row">
                <!-- Student Features -->
                <div class="col-md-4 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <h4>For Students</h4>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check me-2"></i>Browse Part-time Jobs</li>
                            <li><i class="fas fa-check me-2"></i>Easy Application Process</li>
                            <li><i class="fas fa-check me-2"></i>Profile Management</li>
                            <li><i class="fas fa-check me-2"></i>Job History Tracking</li>
                        </ul>
                    </div>
                </div>
                
                <!-- Recruiter Features -->
                <div class="col-md-4 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <h4>For Recruiters</h4>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check me-2"></i>Post Job Opportunities</li>
                            <li><i class="fas fa-check me-2"></i>Manage Applications</li>
                            <li><i class="fas fa-check me-2"></i>Student Database Access</li>
                            <li><i class="fas fa-check me-2"></i>Communication Tools</li>
                        </ul>
                    </div>
                </div>
                
                <!-- Admin Features -->
                <div class="col-md-4 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <h4>For Administrators</h4>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check me-2"></i>User Management</li>
                            <li><i class="fas fa-check me-2"></i>Content Moderation</li>
                            <li><i class="fas fa-check me-2"></i>System Monitoring</li>
                            <li><i class="fas fa-check me-2"></i>Report Generation</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact-info">
        <div class="container">
            <h2 class="text-center mb-5">Contact Us</h2>
            <div class="row">
                <div class="col-md-4 text-center mb-4">
                    <i class="fas fa-envelope fa-2x mb-3"></i>
                    <h4>Email</h4>
                    <p>support@jobrangers.com</p>
                </div>
                <div class="col-md-4 text-center mb-4">
                    <i class="fas fa-phone fa-2x mb-3"></i>
                    <h4>Phone</h4>
                    <p>+60 12-345 6789</p>
                </div>
                <div class="col-md-4 text-center mb-4">
                    <i class="fas fa-map-marker-alt fa-2x mb-3"></i>
                    <h4>Location</h4>
                    <p>Kuala Lumpur, Malaysia</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container text-center">
            <p>&copy; 2024 JOB Rangers. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Close mobile menu when clicking a nav link
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', () => {
                const navbarCollapse = document.querySelector('.navbar-collapse');
                if (navbarCollapse.classList.contains('show')) {
                    navbarCollapse.classList.remove('show');
                }
            });
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    const headerOffset = 80;
                    const elementPosition = target.getBoundingClientRect().top;
                    const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
</body>
</html>
