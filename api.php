<?php
require_once './config/db.php';

// Handle logout request
if (isset($_GET['logout']) || isset($_POST['logout'])) {
    logout();
    exit();
}

// Handle other functions if needed
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    
    switch($action) {
        case 'logout':
            logout();
            break;
        
        case 'check_session':
            if (isLoggedIn()) {
                echo json_encode(['status' => 'logged_in', 'user' => $_SESSION['user_name']]);
            } else {
                echo json_encode(['status' => 'not_logged_in']);
            }
            break;
        
        default:
            echo json_encode(['status' => 'unknown_action']);
            break;
    }
    exit();
}

// Default redirect
header("Location: /Etheatro/");
exit();
?>
