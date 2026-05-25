<?php
require_once '../config/db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $school_id = sanitize($_POST['school_id'] ?? '');
    $first_name = sanitize($_POST['first_name'] ?? '');
    $middle_initial = sanitize($_POST['middle_initial'] ?? '');
    $last_name = sanitize($_POST['last_name'] ?? '');
    $year_level = sanitize($_POST['year_level'] ?? '');
    $department = sanitize($_POST['department'] ?? '');
    $talent = sanitize($_POST['talent'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validation
    if (empty($school_id) || empty($first_name) || empty($last_name) || empty($email) || 
        empty($password) || empty($confirm_password)) {
        $error = 'All required fields must be filled.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } else {
        // Check if email or school_id already exists
        $email_escaped = $conn->real_escape_string($email);
        $school_id_escaped = $conn->real_escape_string($school_id);
        $check = $conn->query("SELECT id FROM auditionees WHERE email = '$email_escaped' OR school_id = '$school_id_escaped'");
        
        if ($check && $check->num_rows > 0) {
            $error = 'Email or School ID already registered.';
        } else {
            $hashed_password = hashPassword($password);
            
            $school_id_escaped = $conn->real_escape_string($school_id);
            $first_name_escaped = $conn->real_escape_string($first_name);
            $middle_initial_escaped = $conn->real_escape_string($middle_initial);
            $last_name_escaped = $conn->real_escape_string($last_name);
            $year_level_escaped = $conn->real_escape_string($year_level);
            $department_escaped = $conn->real_escape_string($department);
            $talent_escaped = $conn->real_escape_string($talent);
            $email_escaped = $conn->real_escape_string($email);
            $hashed_password_escaped = $conn->real_escape_string($hashed_password);
            
            $query = "INSERT INTO auditionees 
                     (school_id, first_name, middle_initial, last_name, year_level, department, talent, email, password, role, status)
                     VALUES 
                     ('$school_id_escaped', '$first_name_escaped', '$middle_initial_escaped', '$last_name_escaped', '$year_level_escaped', '$department_escaped', '$talent_escaped', '$email_escaped', '$hashed_password_escaped', 'student', 'Pending')";
            
            if ($conn->query($query)) {
                $success = 'Registration successful! You can now login.';
                // Clear form
                $_POST = array();
            } else {
                $error = 'Registration failed. Please try again.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Etheatro Audition System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .register-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #003DA5 0%, #002066 100%);
            padding: 20px 0;
        }
        .register-card {
            width: 100%;
            max-width: 600px;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-card">
            <div class="card border-0 shadow-lg">
                <div class="card-body p-5">
                    <!-- Header -->
                    <div class="text-center mb-4">
                        <h2 class="text-primary fw-bold">ETHEATRO</h2>
                        <p class="text-muted mb-0">Join Our Audition</p>
                    </div>

                    <!-- Success Message -->
                    <?php if ($success): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                            <a href="login.php" class="btn btn-sm btn-primary ms-2">Go to Login</a>
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

                    <!-- Registration Form -->
                    <form method="POST" class="needs-validation">
                        <!-- Row 1 -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="school_id" class="form-label">School ID <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="school_id" name="school_id" 
                                    placeholder="e.g., 2024-001" value="<?php echo $_POST['school_id'] ?? ''; ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" 
                                    placeholder="your@email.com" value="<?php echo $_POST['email'] ?? ''; ?>" required>
                            </div>
                        </div>

                        <!-- Row 2 -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="first_name" name="first_name" 
                                    placeholder="Juan" value="<?php echo $_POST['first_name'] ?? ''; ?>" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="middle_initial" class="form-label">Middle Initial</label>
                                <input type="text" class="form-control" id="middle_initial" name="middle_initial" 
                                    placeholder="D" maxlength="1" value="<?php echo $_POST['middle_initial'] ?? ''; ?>">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="last_name" name="last_name" 
                                    placeholder="Cruz" value="<?php echo $_POST['last_name'] ?? ''; ?>" required>
                            </div>
                        </div>

                        <!-- Row 3 -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="year_level" class="form-label">Year Level <span class="text-danger">*</span></label>
                                <select class="form-control" id="year_level" name="year_level" required>
                                    <option value="">Select Year Level</option>
                                    <option value="1st Year">1st Year</option>
                                    <option value="2nd Year">2nd Year</option>
                                    <option value="3rd Year">3rd Year</option>
                                    <option value="4th Year">4th Year</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="department" class="form-label">Department <span class="text-danger">*</span></label>
                                <select class="form-control" id="department" name="department" required>
                                    <option value="">Select Department</option>
                                    <option value="BSIT">BSIT</option>
                                    <option value="BSCS">BSCS</option>
                                    <option value="BSECX">BSECX</option>
                                    <option value="BSECE">BSECE</option>
                                    <option value="BSME">BSME</option>
                                    <option value="BSCE">BSCE</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>

                        <!-- Row 4 -->
                        <div class="form-group mb-3">
                            <label for="talent" class="form-label">Talent <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="talent" name="talent" 
                                placeholder="e.g., Singing, Dancing, Acting" value="<?php echo $_POST['talent'] ?? ''; ?>" required>
                        </div>

                        <!-- Row 5 -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password" name="password" 
                                    placeholder="Min. 6 characters" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="confirm_password" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                                    placeholder="Re-enter password" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold py-2 mb-3">
                            <i class="fas fa-user-plus"></i> Create Account
                        </button>
                    </form>

                    <!-- Divider -->
                    <hr class="my-4">

                    <!-- Login Link -->
                    <div class="text-center">
                        <p class="mb-0">Already have an account? 
                            <a href="login.php" class="text-primary fw-bold text-decoration-none">Login here</a>
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
