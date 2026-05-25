<?php
require_once '../config/db.php';
requireLogin();

$user_id = $_SESSION['user_id'];
$user = getUserById($user_id);

// Get unread notifications count
$unread_result = $conn->query("SELECT COUNT(*) as count FROM notifications WHERE auditionee_id = $user_id AND is_read = FALSE");
$unread_count = $unread_result->fetch_assoc()['count'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archive - Etheatro Audition System</title>
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
                <h2 class="text-primary fw-bold mb-4"><i class="fas fa-archive"></i> Archive</h2>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <!-- Archive Information -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-info-circle"></i> Archive Management</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">Archive functionality allows you to keep a history of your auditions and system interactions. Your current profile information is always accessible from your Dashboard and Profile pages.</p>
                    </div>
                </div>

                <!-- Current Status -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-user-check"></i> Current Application</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="mb-2"><strong>Name:</strong></p>
                                <p class="text-muted"><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-2"><strong>School ID:</strong></p>
                                <p class="text-muted"><?php echo $user['school_id']; ?></p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="mb-2"><strong>Department:</strong></p>
                                <p class="text-muted"><?php echo $user['department']; ?></p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-2"><strong>Talent:</strong></p>
                                <p class="text-muted"><?php echo $user['talent']; ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-2"><strong>Status:</strong></p>
                                <p><span class="badge badge-<?php echo strtolower(str_replace(' ', '-', $user['status'])); ?>">
                                    <?php echo ucfirst($user['status']); ?>
                                </span></p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-2"><strong>Registered:</strong></p>
                                <p class="text-muted"><?php echo date('M d, Y', strtotime($user['created_at'])); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Archive History -->
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-history"></i> Archive History</h5>
                    </div>
                    <div class="card-body text-center py-5">
                        <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No archived items yet. Your audition history will appear here.</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex gap-2 mt-4">
                    <a href="profile.php" class="btn btn-outline-primary flex-grow-1">
                        <i class="fas fa-arrow-left"></i> Back to Profile
                    </a>
                    <a href="dashboard.php" class="btn btn-outline-secondary flex-grow-1">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
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
</body>
</html>
