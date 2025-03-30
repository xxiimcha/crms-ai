<?php
session_start();
include('../config/database.php');
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $hashed_password = md5($password); // Note: MD5 is not secure for production apps

    if (empty($email) || empty($password)) {
        echo json_encode(["status" => "error", "message" => "All fields are required."]);
        exit;
    }

    // Query user by email
    $sql = "SELECT id, name, password, role, status FROM users WHERE email = '$email' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);

        // Check if account is inactive (case-insensitive check)
        if (strtolower($user['status']) !== 'active') {
            echo json_encode(["status" => "error", "message" => "Your account is inactive. Contact admin."]);
            exit;
        }

        // Verify password (MD5 comparison)
        if ($hashed_password === $user['password']) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            echo json_encode([
                "status" => "success",
                "message" => "Login successful.",
                "redirect" => "./common/dashboard.php"
            ]);
        } else {
            echo json_encode(["status" => "error", "message" => "Invalid credentials."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "No user found with this email."]);
    }

    mysqli_close($conn);
}
?>
