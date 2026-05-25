<?php
require_once '../config/db.php';
requireAdmin();

$success = '';
$error = '';

// Update audition details
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $student_id = intval($_POST['student_id']);
    $action = $_POST['action'];

    if ($action === 'schedule') {
        $audition_date = sanitize($_POST['audition_date'] ?? '');

        if (empty($audition_date)) {
            $error = 'Audition date is required.';
        } else {
            $audition_date_escaped = $conn->real_escape_string($audition_date);
            $conn->query("UPDATE auditionees SET audition_date = '$audition_date_escaped', status = 'Ready to Audition' WHERE id = $student_id AND role = 'student'");

            // Create notification
            $message = "Your audition has been scheduled for " . date('M d, Y H:i', strtotime($audition_date));
            $message_escaped = $conn->real_escape_string($message);
            $conn->query("INSERT INTO notifications (auditionee_id, message, type) VALUES ($student_id, '$message_escaped', 'schedule')");

            $success = 'Audition scheduled successfully!';
        }
    } elseif ($action === 'approve') {
        $feedback = sanitize($_POST['feedback'] ?? '');
        $feedback_escaped = $conn->real_escape_string($feedback);
        $conn->query("UPDATE auditionees SET status = 'Approved', feedback = '$feedback_escaped' WHERE id = $student_id AND role = 'student'");

        // Create notification
        $message = "Congratulations! You have been approved for the production.";
        $message_escaped = $conn->real_escape_string($message);
        $conn->query("INSERT INTO notifications (auditionee_id, message, type) VALUES ($student_id, '$message_escaped', 'status_update')");

        $success = 'Student approved!';
    } elseif ($action === 'reject') {
        $feedback = sanitize($_POST['feedback'] ?? '');
        $feedback_escaped = $conn->real_escape_string($feedback);
        $conn->query("UPDATE auditionees SET status = 'Rejected', feedback = '$feedback_escaped' WHERE id = $student_id AND role = 'student'");

        // Create notification
        $message = "Thank you for auditioning. We will consider you for future productions.";
        $message_escaped = $conn->real_escape_string($message);
        $conn->query("INSERT INTO notifications (auditionee_id, message, type) VALUES ($student_id, '$message_escaped', 'status_update')");

        $success = 'Student rejected.';
    }
}

// Get all students ready for audition or in audition process
$students = [];
$query = "SELECT * FROM auditionees WHERE role = 'student' AND status != 'Pending' ORDER BY status, audition_date DESC";
$result = $conn->query($query);
while ($row = $result->fetch_assoc()) {
    $students[] = $row;
}

// Get status counts
$ready_count = $conn->query("SELECT COUNT(*) as count FROM auditionees WHERE role = 'student' AND status = 'Ready to Audition'")->fetch_assoc()['count'];
$approved_count = $conn->query("SELECT COUNT(*) as count FROM auditionees WHERE role = 'student' AND status = 'Approved'")->fetch_assoc()['count'];
$rejected_count = $conn->query("SELECT COUNT(*) as count FROM auditionees WHERE role = 'student' AND status = 'Rejected'")->fetch_assoc()['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Auditions - Etheatro Audition System</title>
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
                        <a class="nav-link active" href="manage_auditionees.php">Manage Auditions</a>
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
                <h2 class="text-primary fw-bold mb-2"><i class="fas fa-calendar-alt"></i> Manage Auditions</h2>
                <p class="text-muted mb-0">Schedule auditions and manage results</p>
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

        <!-- Statistics -->
        <div class="row mb-4">
            <div class="col-md-6 col-lg-4">
                <div class="stat-card">
                    <h3><?php echo $ready_count; ?></h3>
                    <p class="mb-0">Ready to Audition</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="stat-card">
                    <h3><?php echo $approved_count; ?></h3>
                    <p class="mb-0">Approved</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="stat-card">
                    <h3><?php echo $rejected_count; ?></h3>
                    <p class="mb-0">Rejected</p>
                </div>
            </div>
        </div>

        <!-- Auditionees List -->
        <div class="card border-0 shadow-sm">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-list"></i> Audition Schedule</h5>
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Talent</th>
                            <th>Status</th>
                            <th>Audition Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($students) > 0): ?>
                            <?php foreach ($students as $student): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></strong><br>
                                        <small class="text-muted"><?php echo htmlspecialchars($student['school_id']); ?></small>
                                    </td>
                                    <td><?php echo htmlspecialchars($student['talent']); ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo strtolower(str_replace(' ', '-', $student['status'])); ?>">
                                            <?php echo ucfirst($student['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php echo $student['audition_date'] ? date('M d, Y H:i', strtotime($student['audition_date'])) : '<em class="text-muted">Not scheduled</em>'; ?>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#editModal" onclick="editStudent(<?php echo htmlspecialchars(json_encode($student)); ?>)">
                                            <i class="fas fa-edit"></i> Manage
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <p class="text-muted mb-0">No auditionees yet. Approve registrants first.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-edit"></i> Manage Audition</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="auditForm">
                        <input type="hidden" name="student_id" id="studentId">
                        <input type="hidden" name="action" id="formAction">

                        <div id="scheduleSection" style="display:none;">
                            <h6 class="mb-3">Schedule Audition</h6>
                            <div class="form-group mb-3">
                                <label for="auditionDate" class="form-label">Audition Date & Time</label>
                                <input type="datetime-local" class="form-control" id="auditionDate" name="audition_date">
                            </div>
                        </div>

                        <div id="resultSection" style="display:none;">
                            <h6 class="mb-3">Audition Result</h6>
                            <div class="form-group mb-3">
                                <label for="feedback" class="form-label">Feedback</label>
                                <textarea class="form-control" id="feedback" name="feedback" rows="3" placeholder="Provide feedback for the auditionee..."></textarea>
                            </div>
                        </div>

                        <div class="d-flex gap-2" id="actionButtons">
                            <!-- Buttons will be inserted here -->
                        </div>
                    </form>
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
        function editStudent(student) {
            document.getElementById('studentId').value = student.id;
            const scheduleSection = document.getElementById('scheduleSection');
            const resultSection = document.getElementById('resultSection');
            const actionButtons = document.getElementById('actionButtons');

            scheduleSection.style.display = 'none';
            resultSection.style.display = 'none';
            actionButtons.innerHTML = '';

            if (student.status === 'Ready to Audition') {
                resultSection.style.display = 'block';
                actionButtons.innerHTML = `
                    <button type="button" class="btn btn-success flex-grow-1" onclick="submitForm('approve')">
                        <i class="fas fa-check"></i> Approve
                    </button>
                    <button type="button" class="btn btn-danger flex-grow-1" onclick="submitForm('reject')">
                        <i class="fas fa-times"></i> Reject
                    </button>
                `;
            } else if (student.status === 'Pending') {
                scheduleSection.style.display = 'block';
                document.getElementById('auditionDate').value = student.audition_date ? new Date(student.audition_date).toISOString().slice(0, 16) : '';
                actionButtons.innerHTML = `
                    <button type="button" class="btn btn-primary flex-grow-1" onclick="submitForm('schedule')">
                        <i class="fas fa-calendar"></i> Schedule
                    </button>
                `;
            }
        }

        function submitForm(action) {
            document.getElementById('formAction').value = action;
            document.getElementById('auditForm').submit();
        }

        // Auto-hide alerts
        document.querySelectorAll('.alert').forEach(alert => {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });
    </script>
</body>
</html>
