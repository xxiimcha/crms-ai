<?php
include('../config/database.php');
session_start(); // Start session to get current user
header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'add_user':
        addUser($conn);
        break;

    case 'fetch_users':
        fetchUsers($conn);
        break;

    case 'toggle_status':
        toggleUserStatus($conn);
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        break;
}

// Add new user
function addUser($conn)
{
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $checkQuery = "SELECT id FROM users WHERE email = '$email' LIMIT 1";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        echo json_encode(['success' => false, 'message' => 'Email already exists']);
        return;
    }

    $insertQuery = "INSERT INTO users (name, email, password, role, status, created_at)
                    VALUES ('$name', '$email', '$password', '$role', '$status', NOW())";

    if (mysqli_query($conn, $insertQuery)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add user']);
    }
}

// Fetch all users except the currently logged-in user
function fetchUsers($conn)
{
    $currentUserId = $_SESSION['user_id'] ?? 0;

    $query = "SELECT id, name, email, role, status FROM users WHERE id != $currentUserId ORDER BY id DESC";
    $result = mysqli_query($conn, $query);

    $users = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }

    echo json_encode(['success' => true, 'users' => $users]);
}

// Toggle user status (active/inactive)
function toggleUserStatus($conn)
{
    $id = (int)$_POST['id'];
    $currentStatus = $_POST['status'];

    $newStatus = ($currentStatus === 'active') ? 'inactive' : 'active';

    $updateQuery = "UPDATE users SET status = '$newStatus' WHERE id = $id";

    if (mysqli_query($conn, $updateQuery)) {
        echo json_encode(['success' => true, 'new_status' => $newStatus]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update status']);
    }
}
