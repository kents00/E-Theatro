<?php
require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
// Allow running in environments where .env is not present (e.g., Render env vars).
$dotenv->safeLoad();
