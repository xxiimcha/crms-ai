<?php
// --- Session timeout settings ---
$timeout_duration = 900; // 900 seconds = 15 minutes

if (isset($_SESSION['last_activity'])) {
    if (time() - $_SESSION['last_activity'] > $timeout_duration) {
        // Session has expired
        session_unset();
        session_destroy();
        header("Location: ../index.php?timeout=1");
        exit();
    }
}

// Update last activity time
$_SESSION['last_activity'] = time();

// Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

// Optional: Role-based function
function require_role($allowed_roles = []) {
    if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $allowed_roles)) {
        header("Location: ../common/dashboard.php");
        exit();
    }
}
