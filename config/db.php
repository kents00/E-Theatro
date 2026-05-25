<?php
// db.php - Database Connection

require_once __DIR__ . '/../bootstrap.php';

// Parse the database URL from environment variables
$dbUrl = parse_url($_ENV['DATABASE_URL']);

$host = $dbUrl['host'];
$username = $dbUrl['user'];
$password = $dbUrl['pass'];
$database = ltrim($dbUrl['path'], '/');
$port = $dbUrl['port'];

// Create a PostgreSQL connection string
$conn_string = "host={$host} port={$port} dbname={$database} user={$username} password={$password}";

// Establish a connection to the database
$conn = pg_connect($conn_string);

// Check connection
if (!$conn) {
    die("Connection failed: " . pg_last_error());
}

// Optional: Set client encoding
pg_set_client_encoding($conn, "utf8");

// Helper functions
function sanitize($data) {
    global $conn;
    // Use pg_escape_string for PostgreSQL
    return pg_escape_string($conn, htmlspecialchars($data));
}

function escapeSql($data) {
    global $conn;
    return pg_escape_string($conn, $data);
}

function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

// Session management
function startSession() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function isLoggedIn() {
    startSession();
    return isset($_SESSION['user_id']) && isset($_SESSION['user_role']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: /Etheatro/auth/login.php");
        exit();
    }
}

function requireAdmin() {
    requireLogin();
    if ($_SESSION['user_role'] !== 'admin') {
        header("Location: /Etheatro/");
        exit();
    }
}

function getUserById($id) {
    global $conn;
    $result = pg_query_params($conn, "SELECT * FROM auditionees WHERE id = $1", [intval($id)]);
    return pg_fetch_assoc($result);
}

function logout() {
    startSession();
    session_destroy();
    unset($_SESSION);
    setcookie(session_name(), '', 0, '/');
    header("Location: /Etheatro/");
    exit();
}

// Check if logout is requested
if (isset($_GET['logout']) || isset($_POST['logout'])) {
    logout();
}
?>
