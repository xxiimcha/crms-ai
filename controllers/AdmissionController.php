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

    // Fetch admission records from database
    $query = "SELECT id, admission_type, student_id, firstname, lastname, email, symptoms, diagnosis, status 
              FROM admissions 
              ORDER BY id DESC";

    $result = mysqli_query($conn, $query);

    // Fetch student details from API
    $api_url = "https://enrollment.bcp-sms1.com/fetch_students/fetch_students_info_nova.php";
    $student_data = json_decode(file_get_contents($api_url), true);
    $students = [];

    // Store students in associative array (key: studentId)
    if (!empty($student_data)) {
        foreach ($student_data as $student) {
            $students[$student['studentId']] = $student;
        }
    }

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $student_id = $row['student_id'];
            $student_info = isset($students[$student_id]) ? $students[$student_id] : null;

            // Construct response combining admission and student details
            $response["admissions"][] = [
                "id" => $row["id"],
                "admission_type" => $row["admission_type"],
                "name" => $row["admission_type"] === "Student" && $student_info 
                          ? $student_info["name"] 
                          : trim($row["firstname"] . " " . $row["lastname"]),
                "student_number" => $student_info ? $student_info["studentId"] : "N/A",
                "year_level" => $student_info ? $student_info["level"] : "N/A",
                "course" => $student_info ? $student_info["course"] : "N/A",
                "email" => $student_info ? $student_info["email"] : $row["email"],
                "symptoms" => $row["symptoms"],
                "diagnosis" => $row["diagnosis"],
                "status" => $row["status"]
            ];
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
    $student_number = isset($_POST['student_number']) ? mysqli_real_escape_string($conn, $_POST['student_number']) : NULL;
    $firstname = mysqli_real_escape_string($conn, $_POST['firstname'] ?? '');
    $lastname = mysqli_real_escape_string($conn, $_POST['lastname'] ?? '');
    $email = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
    $symptoms = mysqli_real_escape_string($conn, $_POST['symptoms']);
    $diagnosis = mysqli_real_escape_string($conn, $_POST['correct_diagnosis']);
    $status = "Pending"; // Default status for new admissions

    // Insert into admissions table (Now storing student_number instead of student_id)
    $admissionQuery = "INSERT INTO admissions (admission_type, student_id, firstname, lastname, email, symptoms, diagnosis, status) 
                       VALUES ('$admission_type', '$student_number', '$firstname', '$lastname', '$email', '$symptoms', '$diagnosis', '$status')";

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

    // Debugging: Check received form data
    error_log("Received lab procedures: " . print_r($_POST['lab_procedures'], true));

    // Ensure procedures array is received
    $cbc = isset($_POST['lab_procedures']) && is_array($_POST['lab_procedures']) && in_array('CBC', $_POST['lab_procedures']) ? 1 : 0;
    $xray = isset($_POST['lab_procedures']) && is_array($_POST['lab_procedures']) && in_array('X-ray', $_POST['lab_procedures']) ? 1 : 0;
    $urine = isset($_POST['lab_procedures']) && is_array($_POST['lab_procedures']) && in_array('Urinalysis', $_POST['lab_procedures']) ? 1 : 0;
    $schedule_time = mysqli_real_escape_string($conn, $_POST['schedule_time'] ?? date('Y-m-d H:i:s'));

    // Debugging: Log extracted values
    error_log("Lab Procedures - CBC: $cbc, X-ray: $xray, Urinalysis: $urine, Schedule: $schedule_time");

    // Insert lab test details
    $labTestQuery = "INSERT INTO lab_tests (admission_id, cbc, cbc_result, xray, xray_result, urine, urine_result, schedule_time) 
                     VALUES ($admission_id, $cbc, NULL, $xray, NULL, $urine, NULL, '$schedule_time')";

    if (!mysqli_query($conn, $labTestQuery)) {
        error_log("Error saving lab test: " . mysqli_error($conn));
    }
}

?>
