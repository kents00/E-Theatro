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
    <title>Profile - Etheatro Audition System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo urlFor(''); ?>">
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
                        <a class="nav-link active" href="profile.php">Profile</a>
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
                <h2 class="text-primary fw-bold mb-4"><i class="fas fa-user-circle"></i> My Profile</h2>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <!-- Profile Info Card -->
                <div class="profile-card mb-4">
                    <div class="profile-avatar">
                        <?php echo strtoupper(substr($user['first_name'], 0, 1)) . strtoupper(substr($user['last_name'], 0, 1)); ?>
                    </div>
                    <div class="profile-name"><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></div>

                    <div class="profile-info mt-4">
                        <div class="profile-info-item">
                            <span class="profile-info-label">School ID:</span>
                            <span><?php echo $user['school_id']; ?></span>
                        </div>
                        <div class="profile-info-item">
                            <span class="profile-info-label">Email:</span>
                            <span><?php echo $user['email']; ?></span>
                        </div>
                        <div class="profile-info-item">
                            <span class="profile-info-label">Year Level:</span>
                            <span><?php echo $user['year_level']; ?></span>
                        </div>
                        <div class="profile-info-item">
                            <span class="profile-info-label">Department:</span>
                            <span><?php echo $user['department']; ?></span>
                        </div>
                        <div class="profile-info-item">
                            <span class="profile-info-label">Talent:</span>
                            <span><?php echo $user['talent']; ?></span>
                        </div>
                        <div class="profile-info-item">
                            <span class="profile-info-label">Audition Status:</span>
                            <span class="badge badge-<?php echo strtolower(str_replace(' ', '-', $user['status'])); ?>">
                                <?php echo ucfirst($user['status']); ?>
                            </span>
                        </div>
                        <?php if ($user['audition_date']): ?>
                            <div class="profile-info-item">
                                <span class="profile-info-label">Audition Date:</span>
                                <span><?php echo date('M d, Y H:i', strtotime($user['audition_date'])); ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if ($user['feedback']): ?>
                            <div class="profile-info-item">
                                <span class="profile-info-label">Feedback:</span>
                                <span><?php echo nl2br(htmlspecialchars($user['feedback'])); ?></span>
                            </div>
                        <?php endif; ?>
                        <div class="profile-info-item">
                            <span class="profile-info-label">Registered:</span>
                            <span><?php echo date('M d, Y', strtotime($user['created_at'])); ?></span>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <a href="edit.php" class="btn btn-warning flex-grow-1">
                            <i class="fas fa-edit"></i> Edit Profile
                        </a>
                        <a href="archive.php" class="btn btn-secondary flex-grow-1">
                            <i class="fas fa-archive"></i> Archive
                        </a>
                    </div>
                </div>

                <!-- Additional Info -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-info-circle"></i> About This System</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-3">
                            <strong>What is ETHEATRO?</strong><br>
                            ETHEATRO is the University of Science and Technology of Southern Philippines' official audition system for theatrical and cultural performances.
                        </p>
                        <p class="mb-3">
                            <strong>Audition Status Meanings:</strong>
                        </p>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <span class="badge bg-warning text-dark">Pending</span> - Your application is being reviewed by administrators.
                            </li>
                            <li class="mb-2">
                                <span class="badge bg-info">Ready to Audition</span> - You have been scheduled for an audition.
                            </li>
                            <li class="mb-2">
                                <span class="badge bg-success">Approved</span> - You have been accepted into the production.
                            </li>
                            <li class="mb-2">
                                <span class="badge bg-danger">Rejected</span> - Your application was not successful this round.
                            </li>
                        </ul>
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
</body>
</html>
