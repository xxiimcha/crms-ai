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
    case 'save_admission':
        saveAdmission();
        break;
    default:
        echo json_encode(["success" => false, "message" => "Invalid action"]);
}

/**
 * Fetch all admission records
 */
function fetchAdmissions() {
    global $conn;
    $response = ["success" => false, "admissions" => []];

    $query = "SELECT a.id, 
                     a.admission_type,
                     CASE 
                        WHEN a.admission_type = 'Student' THEN COALESCE(CONCAT(s.firstname, ' ', s.lastname), 'Unknown')
                        ELSE CONCAT(a.firstname, ' ', a.lastname) 
                     END AS name, 
                     COALESCE(s.student_number, a.student_id, 'N/A') AS student_number,
                     COALESCE(yl.name, 'N/A') AS year_level, 
                     COALESCE(cs.name, 'N/A') AS course, 
                     COALESCE(a.email, s.email, 'N/A') AS email, 
                     a.symptoms, 
                     a.diagnosis,
                     a.status 
              FROM admissions a
              LEFT JOIN students s ON s.student_number = a.student_id
              LEFT JOIN year_levels yl ON s.year_level = yl.id
              LEFT JOIN courses_strands cs ON s.course = cs.id
              ORDER BY a.id DESC";

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
 * Fetch student details based on student number
 */
function getStudentDetails() {
    global $conn;
    $student_number = isset($_GET['student_number']) ? mysqli_real_escape_string($conn, $_GET['student_number']) : '';

    if (empty($student_number)) {
        echo json_encode(["success" => false, "message" => "Student number is required."]);
        exit;
    }

    $query = "SELECT s.id, s.firstname, s.lastname, s.email, s.student_number, 
                     COALESCE(yl.name, 'N/A') AS year_level, 
                     COALESCE(cs.name, 'N/A') AS course
              FROM students s
              LEFT JOIN year_levels yl ON s.year_level = yl.id
              LEFT JOIN courses_strands cs ON s.course = cs.id
              WHERE s.student_number = '$student_number' 
              LIMIT 1";

    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $student = mysqli_fetch_assoc($result);
        echo json_encode(["success" => true, "student" => $student]);
    } else {
        echo json_encode(["success" => false, "message" => "Student not found."]);
    }
}

/**
 * Save admission and lab test scheduling (if applicable)
 */
function saveAdmission() {
    global $conn;
    
    $response = ["success" => false, "message" => ""];

    // Capture Admission Form Data
    $admission_type = mysqli_real_escape_string($conn, $_POST['admission_type']);
    $student_id = !empty($_POST['student_id']) ? (int)$_POST['student_id'] : 'NULL';
    $firstname = mysqli_real_escape_string($conn, $_POST['firstname'] ?? '');
    $lastname = mysqli_real_escape_string($conn, $_POST['lastname'] ?? '');
    $email = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
    $symptoms = mysqli_real_escape_string($conn, $_POST['symptoms']);
    $diagnosis = mysqli_real_escape_string($conn, $_POST['correct_diagnosis']);
    $status = "Pending"; // Default status for new admissions

    // Insert into admissions table
    $admissionQuery = "INSERT INTO admissions (admission_type, student_id, firstname, lastname, email, symptoms, diagnosis, status) 
                       VALUES ('$admission_type', $student_id, '$firstname', '$lastname', '$email', '$symptoms', '$diagnosis', '$status')";

    if (mysqli_query($conn, $admissionQuery)) {
        $admission_id = mysqli_insert_id($conn);

        // Check if lab test is scheduled
        if (isset($_POST['lab_schedule_checkbox']) && $_POST['lab_schedule_checkbox'] === 'on') {
            saveLabTest($admission_id);
        }

        $response["success"] = true;
        $response["message"] = "Admission saved successfully!";
    } else {
        $response["message"] = "Error saving admission: " . mysqli_error($conn);
    }

    echo json_encode($response);
}

/**
 * Save Lab Test Details
 */
function saveLabTest($admission_id) {
    global $conn;

    // Lab test fields
    $cbc = isset($_POST['lab_procedures']) && in_array('CBC', $_POST['lab_procedures']) ? 1 : 0;
    $xray = isset($_POST['lab_procedures']) && in_array('X-ray', $_POST['lab_procedures']) ? 1 : 0;
    $urine = isset($_POST['lab_procedures']) && in_array('Urinalysis', $_POST['lab_procedures']) ? 1 : 0;
    $schedule_time = mysqli_real_escape_string($conn, $_POST['schedule_time'] ?? date('Y-m-d H:i:s'));

    // Insert lab test details
    $labTestQuery = "INSERT INTO lab_tests (admission_id, cbc, cbc_result, xray, xray_result, urine, urine_result, schedule_time) 
                     VALUES ($admission_id, $cbc, NULL, $xray, NULL, $urine, NULL, '$schedule_time')";

    if (!mysqli_query($conn, $labTestQuery)) {
        error_log("Error saving lab test: " . mysqli_error($conn));
    }
}
?>
