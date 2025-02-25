<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root'); // Change this if needed
define('DB_PASS', ''); // Change this if needed
define('DB_NAME', 'studentdata');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
