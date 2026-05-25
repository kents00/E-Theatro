<?php
require_once '../config/db.php';
requireAdmin();

$admin_id = $_SESSION['user_id'];
$success = '';
$error = '';

// Post announcement
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $message = sanitize($_POST['message']);

    if (empty($message)) {
        $error = 'Message cannot be empty.';
    } else {
        $message_escaped = $conn->real_escape_string($message);
        $query = "INSERT INTO announcements (message, created_by) VALUES ('$message_escaped', $admin_id)";

        if ($conn->query($query)) {
            $success = 'Announcement posted successfully!';
            $_POST['message'] = '';

            // Create notifications for all students
            $students_query = $conn->query("SELECT id FROM auditionees WHERE role = 'student'");
            while ($student = $students_query->fetch_assoc()) {
                $conn->query("INSERT INTO notifications (auditionee_id, message, type) VALUES ({$student['id']}, '$message_escaped', 'announcement')");
            }
        } else {
            $error = 'Failed to post announcement.';
        }
    }
}

// Delete announcement
if (isset($_GET['delete'])) {
    $ann_id = intval($_GET['delete']);
    $conn->query("DELETE FROM announcements WHERE id = $ann_id AND created_by = $admin_id");
    header("Location: announcements.php");
    exit();
}

// Get all announcements
$announcements = [];
$query = "SELECT a.*, b.first_name, b.last_name FROM announcements a
          JOIN auditionees b ON a.created_by = b.id
          ORDER BY a.created_at DESC";
$result = $conn->query($query);
while ($row = $result->fetch_assoc()) {
    $announcements[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements - Etheatro Audition System</title>
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
                        <a class="nav-link" href="<?php echo urlFor('students/dashboard.php'); ?>">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manageregistrants.php">Manage Registrants</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_auditionees.php">Manage Auditions</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="announcements.php">Announcements</a>
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
                <h2 class="text-primary fw-bold mb-2"><i class="fas fa-bullhorn"></i> Announcements</h2>
                <p class="text-muted mb-0">Post updates and notifications for all auditionees</p>
            </div>
        </div>

        <!-- Messages -->
        <?php if ($success): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Post New Announcement -->
        <div class="row mb-4">
            <div class="col-lg-8 offset-lg-2">
                <div class="card border-0 shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-pen-fancy"></i> Post New Announcement</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="form-group mb-3">
                                <label for="message" class="form-label">Message</label>
                                <textarea class="form-control" id="message" name="message" rows="5"
                                    placeholder="Enter your announcement here..." required></textarea>
                                <small class="form-text text-muted d-block mt-2">
                                    This message will be sent to all registered auditionees.
                                </small>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Post Announcement
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Announcements List -->
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <div class="card border-0 shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-list"></i> All Announcements (<?php echo count($announcements); ?>)</h5>
                    </div>
                    <div class="card-body">
                        <?php if (count($announcements) > 0): ?>
                            <?php foreach ($announcements as $announcement): ?>
                                <div class="alert alert-info border-start border-5 border-primary mb-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <p class="mb-2"><?php echo nl2br(htmlspecialchars($announcement['message'])); ?></p>
                                            <small class="text-muted">
                                                <i class="fas fa-user"></i> Posted by: <strong><?php echo htmlspecialchars($announcement['first_name'] . ' ' . $announcement['last_name']); ?></strong><br>
                                                <i class="fas fa-clock"></i> <?php echo date('M d, Y H:i', strtotime($announcement['created_at'])); ?>
                                            </small>
                                        </div>
                                        <a href="?delete=<?php echo $announcement['id']; ?>" class="btn btn-sm btn-danger ms-2" onclick="return confirm('Delete this announcement?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted text-center py-4 mb-0">No announcements yet.</p>
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
        document.querySelectorAll('.alert').forEach(alert => {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });
    </script>
</body>
</html>
