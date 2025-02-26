<?php
include('../config/database.php');

$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'fetch_admissions':
        fetchAdmissions();
        break;
    case 'get_student_details':
        getStudentDetails();
        break;
    default:
        echo json_encode(["success" => false, "message" => "Invalid action"]);
}

/**
 * Fetches all admission records.
 */
function fetchAdmissions() {
    global $conn;
    $response = ["success" => false, "admissions" => []];

    $query = "SELECT a.id, 
                     a.admission_type,
                     CASE 
                        WHEN a.admission_type = 'Student' THEN CONCAT(s.firstname, ' ', s.lastname)
                        ELSE CONCAT(a.firstname, ' ', a.lastname) 
                     END AS name, 
                     COALESCE(s.student_number, 'N/A') AS student_number,
                     COALESCE(yl.name, 'N/A') AS year_level, 
                     COALESCE(cs.name, 'N/A') AS course, 
                     COALESCE(a.email, s.email, 'N/A') AS email, 
                     a.status 
              FROM admissions a
              LEFT JOIN students s ON a.student_id = s.id
              LEFT JOIN year_levels yl ON s.year_level = yl.id
              LEFT JOIN courses_strands cs ON s.course = cs.id";

    $result = mysqli_query($conn, $query);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $response["admissions"][] = $row;
        }
        $response["success"] = true;
    } else {
        $response["message"] = "Failed to fetch admissions: " . mysqli_error($conn);
    }

    echo json_encode($response);
}

/**
 * Fetches student details based on student number.
 */
function getStudentDetails() {
    global $conn;
    $student_number = isset($_GET['student_number']) ? mysqli_real_escape_string($conn, $_GET['student_number']) : '';

    if (empty($student_number)) {
        echo json_encode(["success" => false, "message" => "Student number is required."]);
        exit;
    }

    $query = "SELECT s.id, s.firstname, s.lastname, s.email, s.student_number, 
                     yl.name AS year_level, cs.name AS course
              FROM students s
              LEFT JOIN year_levels yl ON s.year_level = yl.id
              LEFT JOIN courses_strands cs ON s.course = cs.id
              WHERE s.student_number = '$student_number'";

    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $student = mysqli_fetch_assoc($result);
        echo json_encode(["success" => true, "student" => $student]);
    } else {
        echo json_encode(["success" => false, "message" => "Student not found."]);
    }
}
?>
