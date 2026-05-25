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
$db = pg_connect($conn_string);

// Check connection
if (!$db) {
    die("Connection failed: " . pg_last_error());
}

// Optional: Set client encoding
pg_set_client_encoding($db, "utf8");

class PgResult {
    private $result;
    public $num_rows;

    public function __construct($result) {
        $this->result = $result;
        $this->num_rows = 0;
        if (pg_result_status($result) === PGSQL_TUPLES_OK) {
            $this->num_rows = pg_num_rows($result);
        }
    }

    public function fetch_assoc() {
        return pg_fetch_assoc($this->result);
    }
}

class PgConnection {
    public $resource;

    public function __construct($resource) {
        $this->resource = $resource;
    }

    public function query($sql) {
        $result = pg_query($this->resource, $sql);
        if (!$result) {
            return false;
        }

        return new PgResult($result);
    }

    public function queryParams($sql, $params) {
        $result = pg_query_params($this->resource, $sql, $params);
        if (!$result) {
            return false;
        }

        return new PgResult($result);
    }

    public function real_escape_string($value) {
        return pg_escape_string($this->resource, $value);
    }
}

// Provide a mysqli-like interface for existing code.
$conn = new PgConnection($db);

// Helper functions
function sanitize($data) {
    global $conn;
    return $conn->real_escape_string(htmlspecialchars($data));
}

function escapeSql($data) {
    global $conn;
    return $conn->real_escape_string($data);
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
    $result = $conn->queryParams("SELECT * FROM auditionees WHERE id = $1", [intval($id)]);
    return $result ? $result->fetch_assoc() : null;
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
