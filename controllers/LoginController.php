<?php
session_start(); // Start session
include('../config/database.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $hashed_password = md5($password); // Hash input password

    if (empty($email) || empty($password)) {
        echo json_encode(["status" => "error", "message" => "All fields are required."]);
        exit;
    }

    // Query to check user in database
    $sql = "SELECT id, name, password, role, status FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        if ($user['status'] !== 'active') {
            echo json_encode(["status" => "error", "message" => "Your account is inactive. Contact admin."]);
            exit;
        }

        if ($hashed_password === $user['password']) { // Compare MD5 hashes
            // Store user data in session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            echo json_encode(["status" => "success", "message" => "Login successful.", "redirect" => "../common/dashboard.php"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Invalid credentials."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "No user found with this email."]);
    }

    mysqli_close($conn);
}
?>
