<?php
include('../config/database.php');

$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'get_courses':
        getCourses();
        break;
    case 'add_student':
        addStudent();
        break;
    default:
        echo json_encode(["success" => false, "message" => "Invalid action"]);
}

function getCourses() {
    global $conn;

    $year_level_id = isset($_GET['year_level_id']) ? (int)$_GET['year_level_id'] : 0;
    $response = ["success" => false, "courses" => []];

    if ($year_level_id > 0) {
        $query = "SELECT id, name, code FROM courses_strands WHERE year_level_id = $year_level_id";
        $result = mysqli_query($conn, $query);

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $response["courses"][] = $row;
            }
            $response["success"] = true;
        }
    }

    echo json_encode($response);
}

function addStudent() {
    global $conn;

    $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
    $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
    $year_level = (int)$_POST['year_level'];
    $course = (int)$_POST['course'];
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $contact_number = mysqli_real_escape_string($conn, $_POST['contact_number']);

    $query = "INSERT INTO students (firstname, lastname, year_level, course, status, email, contact_number) 
              VALUES ('$firstname', '$lastname', '$year_level', '$course', '$status', '$email', '$contact_number')";

    if (mysqli_query($conn, $query)) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to add student."]);
    }
}
?>
