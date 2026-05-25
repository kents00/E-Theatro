<?php
require_once './config/db.php';

startSession();

// If already logged in, redirect to appropriate dashboard
if (isLoggedIn()) {
    if ($_SESSION['user_role'] === 'admin') {
        header("Location: " . urlFor('admin/manageregistrants.php'));
    } else {
        header("Location: " . urlFor('students/dashboard.php'));
    }
    exit();
}

// Handle logout
if (isset($_GET['logout'])) {
    logout();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ETHEATRO - Audition System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .hero-animation {
            animation: slideDown 1s ease-out;
        }
        .feature-card {
            transition: all 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-10px);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="<?php echo urlFor(''); ?>">
                <i class="fas fa-theater-masks"></i> ETHEATRO
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="auth/login.php">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero hero-animation">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-3">
                <i class="fas fa-theater-masks"></i> ETHEATRO
            </h1>
            <p class="lead mb-4">
                University of Science and Technology of Southern Philippines<br>
                Audition Management System
            </p>
            <p class="subtitle mb-5 fs-5">
                Discover your talent. Showcase your passion. Join our production!
            </p>
            <div class="d-flex gap-3 justify-content-center flex-wrap">
                <a href="auth/register.php" class="btn btn-primary btn-lg">
                    <i class="fas fa-user-plus"></i> Register Now
                </a>
                <a href="auth/login.php" class="btn btn-secondary btn-lg">
                    <i class="fas fa-sign-in-alt"></i> Login
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center text-primary fw-bold mb-5">
                <i class="fas fa-star"></i> Why Choose ETHEATRO?
            </h2>

            <div class="row g-4">
                <!-- Feature 1 -->
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm feature-card h-100">
                        <div class="card-body text-center p-4">
                            <div style="font-size: 3rem; color: #003DA5; margin-bottom: 20px;">
                                <i class="fas fa-bolt"></i>
                            </div>
                            <h5 class="card-title text-primary fw-bold">Easy Registration</h5>
                            <p class="card-text text-muted">
                                Quick and straightforward sign-up process. Get started in minutes.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Feature 2 -->
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm feature-card h-100">
                        <div class="card-body text-center p-4">
                            <div style="font-size: 3rem; color: #FFD700; margin-bottom: 20px;">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <h5 class="card-title text-primary fw-bold">Real-time Updates</h5>
                            <p class="card-text text-muted">
                                Stay informed with instant notifications about audition schedules and results.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Feature 3 -->
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm feature-card h-100">
                        <div class="card-body text-center p-4">
                            <div style="font-size: 3rem; color: #003DA5; margin-bottom: 20px;">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <h5 class="card-title text-primary fw-bold">Secure System</h5>
                            <p class="card-text text-muted">
                                Your data is protected with secure authentication and encryption.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Feature 4 -->
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm feature-card h-100">
                        <div class="card-body text-center p-4">
                            <div style="font-size: 3rem; color: #FFD700; margin-bottom: 20px;">
                                <i class="fas fa-mobile-alt"></i>
                            </div>
                            <h5 class="card-title text-primary fw-bold">Mobile Friendly</h5>
                            <p class="card-text text-muted">
                                Access the system anytime, anywhere from any device.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Feature 5 -->
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm feature-card h-100">
                        <div class="card-body text-center p-4">
                            <div style="font-size: 3rem; color: #003DA5; margin-bottom: 20px;">
                                <i class="fas fa-comments"></i>
                            </div>
                            <h5 class="card-title text-primary fw-bold">Announcements</h5>
                            <p class="card-text text-muted">
                                Get important updates and announcements directly in your dashboard.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Feature 6 -->
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm feature-card h-100">
                        <div class="card-body text-center p-4">
                            <div style="font-size: 3rem; color: #FFD700; margin-bottom: 20px;">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <h5 class="card-title text-primary fw-bold">Admin Panel</h5>
                            <p class="card-text text-muted">
                                Comprehensive tools for managing auditions and student applications.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section id="about" class="py-5">
        <div class="container">
            <h2 class="text-center text-primary fw-bold mb-5">
                <i class="fas fa-lightbulb"></i> How It Works
            </h2>

            <div class="row align-items-center g-4">
                <!-- Step 1 -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <div style="font-size: 2rem; color: #FFD700; margin-bottom: 15px;">
                                <i class="fas fa-pencil-alt"></i>
                            </div>
                            <h5 class="card-title text-primary fw-bold">Step 1: Register</h5>
                            <p class="card-text text-muted mb-0">
                                Create your account with your school ID, personal details, and talent information.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <div style="font-size: 2rem; color: #003DA5; margin-bottom: 15px;">
                                <i class="fas fa-hourglass-start"></i>
                            </div>
                            <h5 class="card-title text-primary fw-bold">Step 2: Wait for Review</h5>
                            <p class="card-text text-muted mb-0">
                                Our admin team will review your application and approve you for audition.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <div style="font-size: 2rem; color: #FFD700; margin-bottom: 15px;">
                                <i class="fas fa-microphone"></i>
                            </div>
                            <h5 class="card-title text-primary fw-bold">Step 3: Audition</h5>
                            <p class="card-text text-muted mb-0">
                                Perform at your scheduled audition time and showcase your talents.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Step 4 -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <div style="font-size: 2rem; color: #003DA5; margin-bottom: 15px;">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <h5 class="card-title text-primary fw-bold">Step 4: Results</h5>
                            <p class="card-text text-muted mb-0">
                                Receive your audition results and feedback through the system.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5 bg-light">
        <div class="container text-center">
            <h2 class="text-primary fw-bold mb-4">Ready to Audition?</h2>
            <p class="lead text-muted mb-4">
                Join thousands of talented students showcasing their abilities at USTP.
            </p>
            <div class="d-flex gap-3 justify-content-center flex-wrap">
                <a href="auth/register.php" class="btn btn-primary btn-lg">
                    <i class="fas fa-user-plus"></i> Register Now
                </a>
                <a href="auth/login.php" class="btn btn-outline-primary btn-lg">
                    <i class="fas fa-sign-in-alt"></i> Login
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row mb-4">
                <div class="col-md-4 mb-3 mb-md-0">
                    <h6 class="fw-bold mb-3">ETHEATRO</h6>
                    <p class="mb-0">University of Science and Technology of Southern Philippines<br>
                    Audition Management System</p>
                </div>
                <div class="col-md-4 mb-3 mb-md-0">
                    <h6 class="fw-bold mb-3">Quick Links</h6>
                    <ul class="list-unstyled">
                        <li><a href="auth/login.php" class="text-white text-decoration-none">Login</a></li>
                        <li><a href="auth/register.php" class="text-white text-decoration-none">Register</a></li>
                        <li><a href="#features" class="text-white text-decoration-none">Features</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h6 class="fw-bold mb-3">Contact</h6>
                    <p class="mb-0">
                        University of Science and Technology<br>
                        Southern Philippines<br>
                        etheatro@ustp.edu.ph
                    </p>
                </div>
            </div>
            <hr style="border-color: rgba(255,255,255,0.2);">
            <p class="text-center mb-0">&copy; 2024 ETHEATRO Audition System. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script>
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });
    </script>
</body>
</html>
