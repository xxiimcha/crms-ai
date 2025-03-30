<?php
include('../config/database.php');
session_start(); // Access session variables

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'add':
        addAnnouncement($conn);
        break;

    case 'fetch':
        fetchAnnouncements($conn);
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        break;
}

// Add new announcement
function addAnnouncement($conn) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    $posted_by = $_SESSION['fullname'] ?? 'Admin';

    if (empty($title) || empty($message)) {
        echo json_encode(['success' => false, 'message' => 'Title and message are required.']);
        return;
    }

    $query = "INSERT INTO announcements (title, message, posted_by) 
              VALUES ('$title', '$message', '$posted_by')";

    if (mysqli_query($conn, $query)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . mysqli_error($conn)]);
    }
}

// Fetch all announcements
function fetchAnnouncements($conn) {
    $query = "SELECT * FROM announcements ORDER BY created_at DESC";
    $result = mysqli_query($conn, $query);

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    echo json_encode(['data' => $data]);
}
