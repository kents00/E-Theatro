<?php
require_once '../config/db.php';
requireLogin();

$user_id = $_SESSION['user_id'];

// Mark notifications as read
if (isset($_GET['mark_read']) && is_numeric($_GET['mark_read'])) {
    $notif_id = intval($_GET['mark_read']);
    $conn->query("UPDATE notifications SET is_read = TRUE WHERE id = $notif_id AND auditionee_id = $user_id");
}

// Get all notifications
$notifications = [];
$query = "SELECT * FROM notifications WHERE auditionee_id = $user_id ORDER BY created_at DESC";
$result = $conn->query($query);
while ($notif = $result->fetch_assoc()) {
    $notifications[] = $notif;
}

// Get unread count
$unread_result = $conn->query("SELECT COUNT(*) as count FROM notifications WHERE auditionee_id = $user_id AND is_read = FALSE");
$unread_count = $unread_result->fetch_assoc()['count'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications - Etheatro Audition System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">
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
                        <a class="nav-link" href="profile.php">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="notification.php">
                            <i class="fas fa-bell"></i> Notifications
                            <?php if ($unread_count > 0): ?>
                                <span class="badge bg-danger ms-2"><?php echo $unread_count; ?></span>
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
    <div class="container py-4 flex-grow-1">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="text-primary fw-bold mb-0"><i class="fas fa-bell"></i> Notifications</h2>
                    <?php if (count($notifications) > 0): ?>
                        <a href="?mark_all=1" class="btn btn-sm btn-outline-primary">Mark all as read</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Notifications List -->
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <?php if (count($notifications) > 0): ?>
                    <?php foreach ($notifications as $notif): ?>
                        <div class="card border-0 shadow-sm mb-3 <?php echo !$notif['is_read'] ? 'bg-light' : ''; ?>">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="badge badge-<?php
                                                switch($notif['type']) {
                                                    case 'status_update': echo 'info'; break;
                                                    case 'announcement': echo 'warning'; break;
                                                    case 'schedule': echo 'success'; break;
                                                    default: echo 'secondary';
                                                }
                                            ?> me-2">
                                                <?php
                                                switch($notif['type']) {
                                                    case 'status_update': echo '<i class="fas fa-sync-alt"></i> Status Update'; break;
                                                    case 'announcement': echo '<i class="fas fa-bullhorn"></i> Announcement'; break;
                                                    case 'schedule': echo '<i class="fas fa-calendar"></i> Schedule'; break;
                                                }
                                                ?>
                                            </span>
                                            <?php if (!$notif['is_read']): ?>
                                                <span class="badge bg-primary">New</span>
                                            <?php endif; ?>
                                        </div>
                                        <p class="mb-2"><?php echo nl2br(htmlspecialchars($notif['message'])); ?></p>
                                        <small class="text-muted">
                                            <i class="fas fa-clock"></i> <?php echo date('M d, Y H:i', strtotime($notif['created_at'])); ?>
                                        </small>
                                    </div>
                                    <?php if (!$notif['is_read']): ?>
                                        <a href="?mark_read=<?php echo $notif['id']; ?>" class="btn btn-sm btn-outline-primary ms-2">
                                            <i class="fas fa-check"></i> Mark as read
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No notifications yet</h5>
                            <p class="text-muted">You'll receive notifications about your audition status and announcements here.</p>
                        </div>
                    </div>
                <?php endif; ?>
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
