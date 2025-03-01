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
    case 'get_year_levels':
        getYearLevels();
        break;
    case 'fetch_students':
        fetchStudents();
        break;
    default:
        echo json_encode(["success" => false, "message" => "Invalid action"]);
}

// Fetch courses based on year level ID
function getCourses() {
    global $conn;

    $is_college = isset($_GET['is_college']) ? (int)$_GET['is_college'] : -1; 
    $response = ["success" => false, "courses" => []];

    if ($is_college !== -1) {
        // Fetch courses based on education level (SHS = 0, College = 1)
        $query = "SELECT id, name FROM courses_strands WHERE year_level_id IN 
                 (SELECT id FROM year_levels WHERE is_college = $is_college)";
    } else {
        echo json_encode($response);
        return;
    }

    $result = mysqli_query($conn, $query);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $response["courses"][] = $row;
        }
        $response["success"] = true;
    }

    echo json_encode($response);
}

// Add a new student
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
    $year_level = (int)$_POST['year_level'];
    $course = (int)$_POST['course'];
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    // Validate required fields
    if (empty($firstname) || empty($lastname) || empty($student_number) || empty($birthdate) || 
        empty($address) || empty($contact_number) || empty($parent_contact) || 
        empty($year_level) || empty($course) || empty($status)) {
        echo json_encode(["success" => false, "message" => "All fields are required."]);
        exit;
    }

    // Check if student number already exists
    $checkQuery = "SELECT id FROM students WHERE student_number = '$student_number'";
    $checkResult = mysqli_query($conn, $checkQuery);
    if (mysqli_num_rows($checkResult) > 0) {
        echo json_encode(["success" => false, "message" => "Student number already exists."]);
        exit;
    }

    // Insert new student
    $query = "INSERT INTO students (firstname, lastname, middlename, student_number, birthdate, age, address, contact_number, parent_contact, year_level, course, status) 
              VALUES ('$firstname', '$lastname', '$middlename', '$student_number', '$birthdate', '$age', '$address', '$contact_number', '$parent_contact', '$year_level', '$course', '$status')";

    if (mysqli_query($conn, $query)) {
        echo json_encode(["success" => true, "message" => "Student added successfully!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to add student. Error: " . mysqli_error($conn)]);
    }
}

// Fetch students based on category (SHS or College)
function fetchStudents() {
    global $conn;
    $response = ["success" => false, "students" => []];

    $category = isset($_GET['category']) ? $_GET['category'] : '';

    if ($category === "shs") {
        $query = "SELECT s.id, 
                         CONCAT(s.firstname, ' ', s.lastname) AS name, 
                         yl.name AS year_level, 
                         cs.name AS strand, 
                         s.email, 
                         s.status 
                  FROM students s
                  LEFT JOIN year_levels yl ON s.year_level = yl.id
                  LEFT JOIN courses_strands cs ON s.course = cs.id
                  WHERE yl.is_college = 0"; // Fetch SHS students
    } elseif ($category === "college") {
        $query = "SELECT s.id, 
                         CONCAT(s.firstname, ' ', s.lastname) AS name, 
                         yl.name AS year_level, 
                         cs.name AS course, 
                         s.email, 
                         s.status 
                  FROM students s
                  LEFT JOIN year_levels yl ON s.year_level = yl.id
                  LEFT JOIN courses_strands cs ON s.course = cs.id
                  WHERE yl.is_college = 1"; // Fetch College students
    } else {
        echo json_encode(["success" => false, "message" => "Invalid category."]);
        exit;
    }

    $result = mysqli_query($conn, $query);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Handle cases where course/strand may be NULL
            $row["strand"] = !empty($row["strand"]) ? $row["strand"] : "Not Assigned";
            $row["course"] = !empty($row["course"]) ? $row["course"] : "Not Assigned";

            $response["students"][] = $row;
        }
        $response["success"] = true;
    } else {
        $response["message"] = "Failed to fetch students: " . mysqli_error($conn);
    }

    echo json_encode($response);
}


function getYearLevels() {
    global $conn;

    $is_college = isset($_GET['is_college']) ? (int)$_GET['is_college'] : 0;
    $response = ["success" => false, "year_levels" => []];

    $query = "SELECT id, name FROM year_levels WHERE is_college = $is_college ORDER BY id ASC";
    $result = mysqli_query($conn, $query);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $response["year_levels"][] = $row;
        }
        $response["success"] = true;
    }

    echo json_encode($response);
}
?>
