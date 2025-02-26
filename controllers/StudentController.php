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
    case 'fetch_students':
        fetchStudents();
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
    $middlename = mysqli_real_escape_string($conn, $_POST['middlename']);
    $student_number = mysqli_real_escape_string($conn, $_POST['student_number']);
    $birthdate = mysqli_real_escape_string($conn, $_POST['birthdate']);
    $age = (int)$_POST['age'];
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $contact_number = mysqli_real_escape_string($conn, $_POST['contact_number']);
    $parent_contact = mysqli_real_escape_string($conn, $_POST['parent_contact']);
    $year_level = mysqli_real_escape_string($conn, $_POST['year_level']);
    $course = mysqli_real_escape_string($conn, $_POST['course']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    if (empty($firstname) || empty($lastname) || empty($student_number) || empty($birthdate) || 
        empty($address) || empty($contact_number) || empty($parent_contact) || 
        empty($year_level) || empty($course) || empty($status)) {
        echo json_encode(["success" => false, "message" => "All fields are required."]);
        exit;
    }

    $checkQuery = "SELECT id FROM students WHERE student_number = '$student_number'";
    $checkResult = mysqli_query($conn, $checkQuery);
    if (mysqli_num_rows($checkResult) > 0) {
        echo json_encode(["success" => false, "message" => "Student number already exists."]);
        exit;
    }

    $query = "INSERT INTO students (firstname, lastname, middlename, student_number, birthdate, age, address, contact_number, parent_contact, year_level, course, status) 
              VALUES ('$firstname', '$lastname', '$middlename', '$student_number', '$birthdate', '$age', '$address', '$contact_number', '$parent_contact', '$year_level', '$course', '$status')";

    if (mysqli_query($conn, $query)) {
        echo json_encode(["success" => true, "message" => "Student added successfully!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to add student. Error: " . mysqli_error($conn)]);
    }
}

function fetchStudents() {
    global $conn;
    $response = ["success" => false, "students" => []];

    $query = "SELECT s.id, 
                     CONCAT(s.firstname, ' ', s.lastname) AS name, 
                     yl.name AS year_level, 
                     cs.name AS course, 
                     s.email, 
                     s.status 
              FROM students s
              LEFT JOIN year_levels yl ON s.year_level = yl.id
              LEFT JOIN courses_strands cs ON s.course = cs.id";

    $result = mysqli_query($conn, $query);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $response["students"][] = $row;
        }
        $response["success"] = true;
    } else {
        $response["message"] = "Failed to fetch students: " . mysqli_error($conn);
    }

    echo json_encode($response);
}

?>
