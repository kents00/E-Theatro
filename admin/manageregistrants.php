<?php
require_once '../config/db.php';
requireAdmin();

$success = '';
$error = '';
$action = $_GET['action'] ?? 'list';

// Update user status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $student_id = intval($_POST['student_id']);
    $action_type = $_POST['action'];

    if ($action_type === 'approve_for_audition') {
        $conn->query("UPDATE auditionees SET status = 'Ready to Audition' WHERE id = $student_id AND role = 'student'");
        
        // Create notification
        $message = "Your application has been approved. You are now ready to audition!";
        $message_escaped = $conn->real_escape_string($message);
        $conn->query("INSERT INTO notifications (auditionee_id, message, type) VALUES ($student_id, '$message_escaped', 'status_update')");
        
        $success = 'Student approved for audition!';
    } elseif ($action_type === 'reject') {
        $conn->query("UPDATE auditionees SET status = 'Rejected' WHERE id = $student_id AND role = 'student'");
        
        // Create notification
        $message = "Your application was not successful this round. Thank you for your interest.";
        $message_escaped = $conn->real_escape_string($message);
        $conn->query("INSERT INTO notifications (auditionee_id, message, type) VALUES ($student_id, '$message_escaped', 'status_update')");
        
        $success = 'Student rejected.';
    } elseif ($action_type === 'reset_to_pending') {
        $conn->query("UPDATE auditionees SET status = 'Pending' WHERE id = $student_id AND role = 'student'");
        $success = 'Student status reset to Pending.';
    }
}

// Get all student registrants
$search = $_GET['search'] ?? '';
$status_filter = $_GET['status'] ?? '';

$query = "SELECT * FROM auditionees WHERE role = 'student'";

if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $query .= " AND (first_name LIKE '%$search%' OR last_name LIKE '%$search%' OR school_id LIKE '%$search%' OR email LIKE '%$search%')";
}

if (!empty($status_filter)) {
    $status_filter = $conn->real_escape_string($status_filter);
    $query .= " AND status = '$status_filter'";
}

$query .= " ORDER BY created_at DESC";
$result = $conn->query($query);
$registrants = [];
while ($row = $result->fetch_assoc()) {
    $registrants[] = $row;
}

// Get statistics
$pending_count = $conn->query("SELECT COUNT(*) as count FROM auditionees WHERE role = 'student' AND status = 'Pending'")->fetch_assoc()['count'];
$ready_count = $conn->query("SELECT COUNT(*) as count FROM auditionees WHERE role = 'student' AND status = 'Ready to Audition'")->fetch_assoc()['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Registrants - Etheatro Audition System</title>
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
                        <a class="nav-link" href="/Etheatro/students/dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="manageregistrants.php">Manage Registrants</a>
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
                <h2 class="text-primary fw-bold mb-2"><i class="fas fa-users"></i> Manage Registrants</h2>
                <p class="text-muted mb-0">Review and approve student registrations for auditions</p>
            </div>
        </div>

        <!-- Success/Error Messages -->
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

        <!-- Statistics -->
        <div class="row mb-4">
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <h3><?php echo count($registrants); ?></h3>
                    <p class="mb-0">Total Registrants</p>
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
        </div>

        <!-- Search and Filter -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-6">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" class="form-control" id="search" name="search" 
                            placeholder="Name, School ID, or Email" value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="status" class="form-label">Filter by Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="">All Status</option>
                            <option value="Pending" <?php echo $status_filter === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="Ready to Audition" <?php echo $status_filter === 'Ready to Audition' ? 'selected' : ''; ?>>Ready to Audition</option>
                            <option value="Approved" <?php echo $status_filter === 'Approved' ? 'selected' : ''; ?>>Approved</option>
                            <option value="Rejected" <?php echo $status_filter === 'Rejected' ? 'selected' : ''; ?>>Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Registrants Table -->
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>School ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Department</th>
                            <th>Talent</th>
                            <th>Status</th>
                            <th>Registered</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($registrants) > 0): ?>
                            <?php foreach ($registrants as $registrant): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($registrant['school_id']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($registrant['first_name'] . ' ' . $registrant['last_name']); ?></td>
                                    <td><?php echo htmlspecialchars($registrant['email']); ?></td>
                                    <td><?php echo htmlspecialchars($registrant['department']); ?></td>
                                    <td><?php echo htmlspecialchars($registrant['talent']); ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo strtolower(str_replace(' ', '-', $registrant['status'])); ?>">
                                            <?php echo ucfirst($registrant['status']); ?>
                                        </span>
                                    </td>
                                    <td><small><?php echo date('M d, Y', strtotime($registrant['created_at'])); ?></small></td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <?php if ($registrant['status'] === 'Pending'): ?>
                                                <form method="POST" style="display:inline;">
                                                    <input type="hidden" name="student_id" value="<?php echo $registrant['id']; ?>">
                                                    <input type="hidden" name="action" value="approve_for_audition">
                                                    <button type="submit" class="btn btn-success btn-sm" title="Approve for audition">
                                                        <i class="fas fa-check"></i> Approve
                                                    </button>
                                                </form>
                                                <form method="POST" style="display:inline;">
                                                    <input type="hidden" name="student_id" value="<?php echo $registrant['id']; ?>">
                                                    <input type="hidden" name="action" value="reject">
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to reject this applicant?')" title="Reject">
                                                        <i class="fas fa-times"></i> Reject
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <button class="btn btn-info btn-sm" disabled title="No actions available">
                                                    <i class="fas fa-ban"></i> No Actions
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <p class="text-muted mb-0">No registrants found.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
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
