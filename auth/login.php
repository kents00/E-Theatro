<?php
require_once '../config/db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Email and password are required.';
    } else {
        $email_escaped = $conn->real_escape_string($email);
        $result = $conn->query("SELECT * FROM auditionees WHERE email = '$email_escaped'");

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if (verifyPassword($password, $user['password'])) {
                startSession();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['user_email'] = $user['email'];

                if ($user['role'] === 'admin') {
                    header("Location: " . urlFor('admin/dashboard.php'));
                } else {
                    header("Location: " . urlFor('students/dashboard.php'));
                }
                exit();
            } else {
                $error = 'Invalid email or password.';
            }
        } else {
            $error = 'Invalid email or password.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Etheatro Audition System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #003DA5 0%, #002066 100%);
        }
        .login-card {
            width: 100%;
            max-width: 450px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="card border-0 shadow-lg">
                <div class="card-body p-5">
                    <!-- Logo/Title -->
                    <div class="text-center mb-4">
                        <h2 class="text-primary fw-bold">ETHEATRO</h2>
                        <p class="text-muted">Audition System</p>
                    </div>

                    <!-- Error Message -->
                    <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Login Form -->
                    <form method="POST" class="needs-validation">
                        <div class="form-group mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="Enter your email" required>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Enter your password" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold py-2 mb-3">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </button>
                    </form>

                    <!-- Divider -->
                    <hr class="my-4">

                    <!-- Links -->
                    <div class="text-center">
                        <p class="mb-2">Don't have an account?
                            <a href="register.php" class="text-primary fw-bold text-decoration-none">Register here</a>
                        </p>
                        <p class="mb-0">
                            <a href="forgot_password.php" class="text-muted text-decoration-none">Forgot password?</a>
                        </p>
                    </div>

                    <!-- Demo Credentials -->
                    <div class="alert alert-info mt-4 small">
                        <strong>Demo Credentials:</strong><br>
                        Email: <code>admin@example.com</code><br>
                        Password: <code>password123</code>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>
