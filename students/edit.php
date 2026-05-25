<?php
require_once '../config/db.php';
requireLogin();

$user_id = $_SESSION['user_id'];
$user = getUserById($user_id);
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = sanitize($_POST['first_name'] ?? '');
    $middle_initial = sanitize($_POST['middle_initial'] ?? '');
    $last_name = sanitize($_POST['last_name'] ?? '');
    $year_level = sanitize($_POST['year_level'] ?? '');
    $department = sanitize($_POST['department'] ?? '');
    $talent = sanitize($_POST['talent'] ?? '');

    if (empty($first_name) || empty($last_name)) {
        $error = 'First name and last name are required.';
    } else {
        $query = "UPDATE auditionees SET 
                  first_name = '$first_name',
                  middle_initial = '$middle_initial',
                  last_name = '$last_name',
                  year_level = '$year_level',
                  department = '$department',
                  talent = '$talent'
                  WHERE id = $user_id";
        
        if ($conn->query($query)) {
            $success = 'Profile updated successfully!';
            $_SESSION['user_name'] = $first_name . ' ' . $last_name;
            $user = getUserById($user_id);
        } else {
            $error = 'Failed to update profile. Please try again.';
        }
    }
}

// Get unread notifications count
$unread_result = $conn->query("SELECT COUNT(*) as count FROM notifications WHERE auditionee_id = $user_id AND is_read = FALSE");
$unread_count = $unread_result->fetch_assoc()['count'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Etheatro Audition System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="/Etheatro/">
                <i class="fas fa-theater-masks"></i> ETHEATRO
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link notification-badge" href="notification.php">
                            <i class="fas fa-bell"></i>
                            <?php if ($unread_count > 0): ?>
                                <span><?php echo $unread_count; ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../api.php?action=logout">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container py-4">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="text-primary fw-bold mb-4"><i class="fas fa-edit"></i> Edit Profile</h2>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 offset-lg-2">
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

                <!-- Edit Form -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0">Update Your Information</h5>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST">
                            <!-- Row 1 -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" 
                                        value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="middle_initial" class="form-label">Middle Initial</label>
                                    <input type="text" class="form-control" id="middle_initial" name="middle_initial" 
                                        placeholder="D" maxlength="1" value="<?php echo $user['middle_initial'] ?? ''; ?>">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" 
                                        value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
                                </div>
                            </div>

                            <!-- Row 2 -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="year_level" class="form-label">Year Level</label>
                                    <select class="form-control" id="year_level" name="year_level">
                                        <option value="">Select Year Level</option>
                                        <option value="1st Year" <?php echo $user['year_level'] === '1st Year' ? 'selected' : ''; ?>>1st Year</option>
                                        <option value="2nd Year" <?php echo $user['year_level'] === '2nd Year' ? 'selected' : ''; ?>>2nd Year</option>
                                        <option value="3rd Year" <?php echo $user['year_level'] === '3rd Year' ? 'selected' : ''; ?>>3rd Year</option>
                                        <option value="4th Year" <?php echo $user['year_level'] === '4th Year' ? 'selected' : ''; ?>>4th Year</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="department" class="form-label">Department</label>
                                    <select class="form-control" id="department" name="department">
                                        <option value="">Select Department</option>
                                        <option value="BSIT" <?php echo $user['department'] === 'BSIT' ? 'selected' : ''; ?>>BSIT</option>
                                        <option value="BSCS" <?php echo $user['department'] === 'BSCS' ? 'selected' : ''; ?>>BSCS</option>
                                        <option value="BSECX" <?php echo $user['department'] === 'BSECX' ? 'selected' : ''; ?>>BSECX</option>
                                        <option value="BSECE" <?php echo $user['department'] === 'BSECE' ? 'selected' : ''; ?>>BSECE</option>
                                        <option value="BSME" <?php echo $user['department'] === 'BSME' ? 'selected' : ''; ?>>BSME</option>
                                        <option value="BSCE" <?php echo $user['department'] === 'BSCE' ? 'selected' : ''; ?>>BSCE</option>
                                        <option value="Other" <?php echo $user['department'] === 'Other' ? 'selected' : ''; ?>>Other</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Row 3 -->
                            <div class="form-group mb-4">
                                <label for="talent" class="form-label">Talent</label>
                                <input type="text" class="form-control" id="talent" name="talent" 
                                    placeholder="e.g., Singing, Dancing, Acting" value="<?php echo htmlspecialchars($user['talent']); ?>">
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary flex-grow-1">
                                    <i class="fas fa-save"></i> Save Changes
                                </button>
                                <a href="profile.php" class="btn btn-outline-secondary flex-grow-1">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Info Card -->
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-body">
                        <p class="mb-0"><strong>Note:</strong> Some fields like School ID and Email cannot be changed. If you need to update these, please contact administration.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer mt-5">
        <p>&copy; 2024 ETHEATRO Audition System - University of Science and Technology of Southern Philippines</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script>
        document.querySelectorAll('.alert').forEach(alert => {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });
    </script>
</body>
</html>
