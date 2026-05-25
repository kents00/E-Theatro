<?php
require_once '../config/db.php';
requireAdmin();

$admin_id = $_SESSION['user_id'];

// Get statistics
$pending_count = $conn->query("SELECT COUNT(*) as count FROM auditionees WHERE role = 'student' AND status = 'Pending'")->fetch_assoc()['count'];
$ready_count = $conn->query("SELECT COUNT(*) as count FROM auditionees WHERE role = 'student' AND status = 'Ready to Audition'")->fetch_assoc()['count'];
$approved_count = $conn->query("SELECT COUNT(*) as count FROM auditionees WHERE role = 'student' AND status = 'Approved'")->fetch_assoc()['count'];
$rejected_count = $conn->query("SELECT COUNT(*) as count FROM auditionees WHERE role = 'student' AND status = 'Rejected'")->fetch_assoc()['count'];
$total_count = $pending_count + $ready_count + $approved_count + $rejected_count;

// Get recent registrations
$recent_registrations = [];
$reg_result = $conn->query("SELECT id, first_name, last_name, email, status, created_at FROM auditionees WHERE role = 'student' ORDER BY created_at DESC LIMIT 5");
while ($row = $reg_result->fetch_assoc()) {
    $recent_registrations[] = $row;
}

// Get recent announcements
$recent_announcements = [];
$ann_result = $conn->query("SELECT a.*, b.first_name, b.last_name FROM announcements a
                           JOIN auditionees b ON a.created_by = b.id
                           ORDER BY a.created_at DESC LIMIT 3");
while ($row = $ann_result->fetch_assoc()) {
    $recent_announcements[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Etheatro Audition System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .stat-card h3 {
            font-size: 2.5rem;
        }
        .recent-item {
            border-left: 4px solid #FFD700;
            padding-left: 15px;
        }
    </style>
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
                    <li class="nav-item">
                        <a class="nav-link" href="manageregistrants.php">Manage Registrants</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_auditionees.php">Manage Auditions</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="announcements.php">Announcements</a>
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
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="text-primary fw-bold mb-2">
                    <i class="fas fa-tachometer-alt"></i> Admin Dashboard
                </h1>
                <p class="text-muted mb-0">Welcome back, <?php echo $_SESSION['user_name']; ?>! Here's an overview of the audition system.</p>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <h3><?php echo $total_count; ?></h3>
                    <p class="mb-0">Total Registrations</p>
                </div>
            </div>
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
                    <h3><?php echo $approved_count + $rejected_count; ?></h3>
                    <p class="mb-0">Completed</p>
                </div>
            </div>
        </div>

        <!-- Status Breakdown -->
        <div class="row mb-4">
            <div class="col-lg-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div style="font-size: 2rem; color: #FFC107; margin-bottom: 10px;">
                            <i class="fas fa-hourglass-start"></i>
                        </div>
                        <h6 class="text-muted">Approved</h6>
                        <h3 class="text-success"><?php echo $approved_count; ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div style="font-size: 2rem; color: #DC3545; margin-bottom: 10px;">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <h6 class="text-muted">Rejected</h6>
                        <h3 class="text-danger"><?php echo $rejected_count; ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="mb-3 fw-bold"><i class="fas fa-chart-pie"></i> Status Distribution</h6>
                        <div class="progress mb-2" style="height: 25px;">
                            <div class="progress-bar" role="progressbar" style="width: <?php echo $total_count > 0 ? ($pending_count / $total_count * 100) : 0; ?>%; background-color: #FFC107;" title="Pending">
                                Pending: <?php echo $pending_count; ?>
                            </div>
                            <div class="progress-bar" role="progressbar" style="width: <?php echo $total_count > 0 ? ($ready_count / $total_count * 100) : 0; ?>%; background-color: #0DCAF0;" title="Ready">
                                Ready: <?php echo $ready_count; ?>
                            </div>
                            <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $total_count > 0 ? ($approved_count / $total_count * 100) : 0; ?>%" title="Approved">
                                Approved: <?php echo $approved_count; ?>
                            </div>
                            <div class="progress-bar bg-danger" role="progressbar" style="width: <?php echo $total_count > 0 ? ($rejected_count / $total_count * 100) : 0; ?>%" title="Rejected">
                                Rejected: <?php echo $rejected_count; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mb-4">
            <div class="col-12">
                <h5 class="text-primary fw-bold mb-3"><i class="fas fa-bolt"></i> Quick Actions</h5>
            </div>
            <div class="col-md-6 col-lg-3">
                <a href="manageregistrants.php" class="card border-0 shadow-sm text-decoration-none h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-users fa-2x text-primary mb-3"></i>
                        <h6>Manage Registrants</h6>
                        <p class="text-muted small mb-0"><?php echo $pending_count; ?> pending approval</p>
                    </div>
                </a>
            </div>
            <div class="col-md-6 col-lg-3">
                <a href="manage_auditionees.php" class="card border-0 shadow-sm text-decoration-none h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-calendar-alt fa-2x text-primary mb-3"></i>
                        <h6>Manage Auditions</h6>
                        <p class="text-muted small mb-0"><?php echo $ready_count; ?> ready to audition</p>
                    </div>
                </a>
            </div>
            <div class="col-md-6 col-lg-3">
                <a href="announcements.php" class="card border-0 shadow-sm text-decoration-none h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-bullhorn fa-2x text-primary mb-3"></i>
                        <h6>Post Announcements</h6>
                        <p class="text-muted small mb-0">Broadcast to all students</p>
                    </div>
                </a>
            </div>
            <div class="col-md-6 col-lg-3">
                <a href="<?php echo urlFor(''); ?>" class="card border-0 shadow-sm text-decoration-none h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-home fa-2x text-primary mb-3"></i>
                        <h6>System Home</h6>
                        <p class="text-muted small mb-0">Back to main page</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="row">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-user-plus"></i> Recent Registrations</h5>
                    </div>
                    <div class="card-body">
                        <?php if (count($recent_registrations) > 0): ?>
                            <?php foreach ($recent_registrations as $reg): ?>
                                <div class="recent-item mb-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <strong><?php echo htmlspecialchars($reg['first_name'] . ' ' . $reg['last_name']); ?></strong><br>
                                            <small class="text-muted"><?php echo htmlspecialchars($reg['email']); ?></small><br>
                                            <small class="text-muted"><?php echo date('M d, Y H:i', strtotime($reg['created_at'])); ?></small>
                                        </div>
                                        <span class="badge badge-<?php echo strtolower(str_replace(' ', '-', $reg['status'])); ?>">
                                            <?php echo ucfirst($reg['status']); ?>
                                        </span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted text-center py-3">No recent registrations</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-bullhorn"></i> Recent Announcements</h5>
                    </div>
                    <div class="card-body">
                        <?php if (count($recent_announcements) > 0): ?>
                            <?php foreach ($recent_announcements as $ann): ?>
                                <div class="recent-item mb-3">
                                    <div>
                                        <p class="mb-2"><?php echo nl2br(htmlspecialchars(substr($ann['message'], 0, 100))); ?>...</p>
                                        <small class="text-muted">
                                            <i class="fas fa-user"></i> Posted by: <?php echo htmlspecialchars($ann['first_name'] . ' ' . $ann['last_name']); ?><br>
                                            <i class="fas fa-clock"></i> <?php echo date('M d, Y H:i', strtotime($ann['created_at'])); ?>
                                        </small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted text-center py-3">No announcements yet</p>
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
</body>
</html>
