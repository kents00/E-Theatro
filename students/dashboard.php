<?php
require_once '../config/db.php';
requireLogin();

$user_id = $_SESSION['user_id'];
$user = getUserById($user_id);

// Get notifications
$notifications_query = $conn->query("SELECT * FROM notifications WHERE auditionee_id = $user_id ORDER BY created_at DESC LIMIT 5");
$notifications = [];
while ($notif = $notifications_query->fetch_assoc()) {
    $notifications[] = $notif;
}

// Get audition statistics
$pending_count = 0;
$ready_count = 0;
$approved_count = 0;
$rejected_count = 0;

if ($user['role'] === 'admin') {
    $pending_result = $conn->query("SELECT COUNT(*) as count FROM auditionees WHERE role = 'student' AND status = 'Pending'");
    $pending_count = $pending_result->fetch_assoc()['count'];

    $ready_result = $conn->query("SELECT COUNT(*) as count FROM auditionees WHERE role = 'student' AND status = 'Ready to Audition'");
    $ready_count = $ready_result->fetch_assoc()['count'];

    $approved_result = $conn->query("SELECT COUNT(*) as count FROM auditionees WHERE role = 'student' AND status = 'Approved'");
    $approved_count = $approved_result->fetch_assoc()['count'];

    $rejected_result = $conn->query("SELECT COUNT(*) as count FROM auditionees WHERE role = 'student' AND status = 'Rejected'");
    $rejected_count = $rejected_result->fetch_assoc()['count'];
}

// Get announcements
$announcements = [];
$announcements_query = $conn->query("SELECT a.*, b.first_name, b.last_name FROM announcements a
                                    JOIN auditionees b ON a.created_by = b.id
                                    ORDER BY a.created_at DESC LIMIT 3");
while ($ann = $announcements_query->fetch_assoc()) {
    $announcements[] = $ann;
}

// Get unread notifications count
$unread_result = $conn->query("SELECT COUNT(*) as count FROM notifications WHERE auditionee_id = $user_id AND is_read = 0");
$unread_count = $unread_result ? $unread_result->fetch_assoc()['count'] : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Etheatro Audition System</title>
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
                        <a class="nav-link active" href="dashboard.php">Dashboard</a>
                    </li>
                    <?php if ($user['role'] !== 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="profile.php">Profile</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link notification-badge" href="notification.php" title="View Notifications">
                            <i class="fas fa-bell"></i> Notifications
                            <?php if ($unread_count > 0): ?>
                                <span class="badge-count"><?php echo $unread_count; ?></span>
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
    <div class="container-fluid py-4">
        <!-- Welcome Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h1 class="card-title text-primary fw-bold mb-0">
                            <i class="fas fa-wave-hand"></i> Welcome, <?php echo $_SESSION['user_name']; ?>!
                        </h1>
                        <p class="text-muted mb-0">
                            <?php
                            $date = date('F j, Y');
                            if ($user['role'] === 'admin') {
                                echo "Admin Dashboard - Manage Auditions";
                            } else {
                                echo "Your audition status: <strong>" . ucfirst($user['status']) . "</strong>";
                            }
                            ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <?php if ($user['role'] === 'admin'): ?>
            <div class="row mb-4">
                <div class="col-md-6 col-lg-3">
                    <div class="stat-card">
                        <h3><?php echo $pending_count; ?></h3>
                        <p class="mb-0">Pending</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="stat-card">
                        <h3><?php echo $ready_count; ?></h3>
                        <p class="mb-0">Ready to Audition</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="stat-card">
                        <h3><?php echo $approved_count; ?></h3>
                        <p class="mb-0">Approved</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="stat-card">
                        <h3><?php echo $rejected_count; ?></h3>
                        <p class="mb-0">Rejected</p>
                    </div>
                </div>
            </div>

            <!-- Admin Dashboard Links -->
            <div class="row mb-4">
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-users fa-3x text-primary mb-3"></i>
                            <h5 class="card-title">Manage Registrants</h5>
                            <p class="text-muted">Review and manage student registrations</p>
                            <a href="<?php echo urlFor('admin/manageregistrants.php'); ?>" class="btn btn-primary btn-sm">Go to Registrants</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-calendar-alt fa-3x text-primary mb-3"></i>
                            <h5 class="card-title">Manage Auditions</h5>
                            <p class="text-muted">Schedule auditions and set status</p>
                            <a href="<?php echo urlFor('admin/manage_auditionees.php'); ?>" class="btn btn-primary btn-sm">Manage Auditions</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-bullhorn fa-3x text-primary mb-3"></i>
                            <h5 class="card-title">Announcements</h5>
                            <p class="text-muted">Post announcements to students</p>
                            <a href="<?php echo urlFor('admin/announcements.php'); ?>" class="btn btn-primary btn-sm">Post Announcements</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- Student Status Card -->
            <div class="row mb-4">
                <div class="col-md-6 col-lg-4 mx-auto">
                    <div class="profile-card">
                        <div class="profile-avatar">
                            <?php echo strtoupper(substr($user['first_name'], 0, 1)) . strtoupper(substr($user['last_name'], 0, 1)); ?>
                        </div>
                        <div class="profile-name"><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></div>
                        <div class="profile-info">
                            <div class="profile-info-item">
                                <span class="profile-info-label">Status:</span>
                                <span class="badge badge-<?php echo strtolower(str_replace(' ', '-', $user['status'])); ?>">
                                    <?php echo ucfirst($user['status']); ?>
                                </span>
                            </div>
                            <div class="profile-info-item">
                                <span class="profile-info-label">Talent:</span>
                                <span><?php echo $user['talent']; ?></span>
                            </div>
                            <div class="profile-info-item">
                                <span class="profile-info-label">Department:</span>
                                <span><?php echo $user['department']; ?></span>
                            </div>
                            <?php if ($user['audition_date']): ?>
                                <div class="profile-info-item">
                                    <span class="profile-info-label">Audition Date:</span>
                                    <span><?php echo date('M d, Y H:i', strtotime($user['audition_date'])); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <a href="profile.php" class="btn btn-warning w-100 mt-3">View Full Profile</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Announcements Section -->
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-bullhorn"></i> Latest Announcements</h5>
                    </div>
                    <div class="card-body">
                        <?php if (count($announcements) > 0): ?>
                            <?php foreach ($announcements as $announcement): ?>
                                <div class="alert alert-info border-start border-5 border-primary">
                                    <small class="text-muted">
                                        Posted by: <strong><?php echo $announcement['first_name'] . ' ' . $announcement['last_name']; ?></strong>
                                        on <?php echo date('M d, Y H:i', strtotime($announcement['created_at'])); ?>
                                    </small>
                                    <p class="mt-2 mb-0"><?php echo $announcement['message']; ?></p>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted text-center py-3">No announcements yet.</p>
                        <?php endif; ?>
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
        // Auto-hide alerts after 5 seconds
        document.querySelectorAll('.alert').forEach(alert => {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });
    </script>
</body>
</html>
