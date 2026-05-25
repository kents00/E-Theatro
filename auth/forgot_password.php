<?php
require_once '../config/db.php';

$error = '';
$success = '';
$step = 'email'; // email or reset

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($step === 'email' && isset($_POST['email'])) {
        $email = sanitize($_POST['email']);
        
        $result = $conn->query("SELECT id FROM auditionees WHERE email = '$email'");
        
        if ($result && $result->num_rows > 0) {
            // In a real application, you would send a reset link via email
            // For demo purposes, we'll just show a message
            startSession();
            $_SESSION['reset_email'] = $email;
            $success = 'A password reset link has been sent to your email.';
            $step = 'reset';
        } else {
            $error = 'Email not found in our system.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Etheatro Audition System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .forgot-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #003DA5 0%, #002066 100%);
        }
        .forgot-card {
            width: 100%;
            max-width: 450px;
        }
    </style>
</head>
<body>
    <div class="forgot-container">
        <div class="forgot-card">
            <div class="card border-0 shadow-lg">
                <div class="card-body p-5">
                    <!-- Header -->
                    <div class="text-center mb-4">
                        <h2 class="text-primary fw-bold">ETHEATRO</h2>
                        <p class="text-muted">Password Recovery</p>
                    </div>

                    <!-- Success Message -->
                    <?php if ($success): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Error Message -->
                    <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Email Form -->
                    <form method="POST" class="needs-validation">
                        <div class="form-group mb-4">
                            <label for="email" class="form-label">Enter Your Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input type="email" class="form-control" id="email" name="email" 
                                    placeholder="your@email.com" required>
                            </div>
                            <small class="form-text text-muted d-block mt-2">
                                We'll send you a link to reset your password.
                            </small>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold py-2 mb-3">
                            <i class="fas fa-paper-plane"></i> Send Reset Link
                        </button>
                    </form>

                    <!-- Divider -->
                    <hr class="my-4">

                    <!-- Back to Login -->
                    <div class="text-center">
                        <p class="mb-0">
                            <a href="login.php" class="text-primary fw-bold text-decoration-none">
                                <i class="fas fa-arrow-left"></i> Back to Login
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>
