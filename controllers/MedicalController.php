<?php
include('../config/database.php');

$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'fetch_medical_records':
        fetchMedicalRecords();
        break;
    case 'fetch_schedules':
        fetch_schedules();
        break;
    case 'get_schedule_details':
        get_schedule_details();
        break;
    case 'complete_schedule':
        complete_schedule();
        break;
    case 'upload_lab_results':
        upload_lab_results();
        break;
    default:
        echo json_encode(["success" => false, "message" => "Invalid action"]);
}


function upload_lab_results() {
    global $conn;

    $schedule_id = mysqli_real_escape_string($conn, $_POST['schedule_id']);
    $upload_dir = "../uploads/lab_results/"; // Directory where results will be stored
    $allowed_extensions = ["pdf", "jpg", "jpeg", "png"];

    // Ensure directory exists
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $upload_fields = ["cbc_result", "xray_result", "urine_result"];
    $updates = [];

    foreach ($upload_fields as $field) {
        if (!empty($_FILES[$field]['name'])) {
            $file_name = basename($_FILES[$field]['name']);
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if (!in_array($file_ext, $allowed_extensions)) {
                echo json_encode(["success" => false, "message" => "Invalid file format for " . strtoupper(str_replace("_result", "", $field)) . ". Allowed: PDF, JPG, PNG"]);
                return;
            }

            // Create a unique filename to prevent overwriting
            $new_file_name = $schedule_id . "_" . $field . "." . $file_ext;
            $target_file = $upload_dir . $new_file_name;

            if (move_uploaded_file($_FILES[$field]["tmp_name"], $target_file)) {
                $updates[] = "$field = '$new_file_name'";
            }
        }
    }

    if (!empty($updates)) {
        $update_query = "UPDATE lab_tests SET " . implode(", ", $updates) . " WHERE id = '$schedule_id'";
        if (mysqli_query($conn, $update_query)) {
            echo json_encode(["success" => true, "message" => "Lab results uploaded successfully!"]);
        } else {
            echo json_encode(["success" => false, "message" => "Database error: " . mysqli_error($conn)]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "No files uploaded."]);
    }
}
function get_schedule_details() {
    global $conn;
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // Fetch schedule details with admission info
    $query = "SELECT lt.id, lt.admission_id, lt.schedule_time AS appointment_date, lt.status, 
                     lt.cbc, lt.xray, lt.urine, lt.cbc_result, lt.xray_result, lt.urine_result, 
                     a.admission_type, a.student_id, a.firstname, a.lastname, a.email
              FROM lab_tests lt
              LEFT JOIN admissions a ON lt.admission_id = a.id
              WHERE lt.id = '$id'
              LIMIT 1";

    $result = mysqli_query($conn, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        $student_id = $row['student_id'];
        $student_info = null;

        // Fetch student details from the API if applicable
        if ($row["admission_type"] === "Student" && !empty($student_id)) {
            $api_url = "https://enrollment.bcp-sms1.com/fetch_students/fetch_students_info_nova.php";
            $student_data = json_decode(file_get_contents($api_url), true);

            if (!empty($student_data)) {
                foreach ($student_data as $student) {
                    if ($student['studentId'] == $student_id) {
                        $student_info = [
                            "name" => $student["name"],
                            "year_level" => $student["level"],
                            "course" => $student["course"],
                            "email" => $student["email"]
                        ];
                        break;
                    }
                }
            }
        }

        // Define upload directory path for results
        $upload_dir = "../uploads/lab_results/";

        // Build the final response object
        $response = [
            "success" => true,
            "schedule" => [
                "id" => $row["id"],
                "name" => $student_info ? $student_info["name"] : trim($row["firstname"] . " " . $row["lastname"]),
                "type" => $row["admission_type"],
                "appointment_date" => date("Y-m-d H:i A", strtotime($row["appointment_date"])),
                "reason" => "Lab Test", // Assuming all schedules are lab tests
                "status" => $row["status"],
                "email" => $student_info ? $student_info["email"] : $row["email"],
                "year_level" => $student_info ? $student_info["year_level"] : "N/A",
                "course" => $student_info ? $student_info["course"] : "N/A",
                "cbc" => $row["cbc"] ? "Yes" : "No",
                "xray" => $row["xray"] ? "Yes" : "No",
                "urine" => $row["urine"] ? "Yes" : "No",
                "cbc_result" => !empty($row["cbc_result"]) ? $upload_dir . $row["cbc_result"] : null,
                "xray_result" => !empty($row["xray_result"]) ? $upload_dir . $row["xray_result"] : null,
                "urine_result" => !empty($row["urine_result"]) ? $upload_dir . $row["urine_result"] : null
            ]
        ];

        echo json_encode($response);
    } else {
        echo json_encode(["success" => false, "message" => "Schedule not found."]);
    }
}

function complete_schedule() {
    global $conn;
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    mysqli_query($conn, "UPDATE lab_tests SET status = 'Completed' WHERE id = '$id'");
    echo json_encode(["success" => true]);
}

function fetch_schedules() {
    global $conn;
    $status = isset($_GET['status']) ? mysqli_real_escape_string($conn, $_GET['status']) : 'Upcoming';
    $response = ["success" => false, "schedules" => []];

    // Ensure the correct status is fetched (map "Upcoming" to "Pending")
    $db_status = $status === "Upcoming" ? "Pending" : $status;

    // Fetch student details from API
    $api_url = "https://enrollment.bcp-sms1.com/fetch_students/fetch_students_info_nova.php";
    $student_data = json_decode(file_get_contents($api_url), true);
    $students = [];

    if (!empty($student_data)) {
        foreach ($student_data as $student) {
            $students[$student['studentId']] = $student;
        }
    }

    // Fetch medical schedules from `lab_tests`
    $query = "SELECT lt.id, lt.admission_id, lt.schedule_time AS appointment_date, 
                     lt.status, a.admission_type, a.student_id, a.firstname, a.lastname
              FROM lab_tests lt
              LEFT JOIN admissions a ON lt.admission_id = a.id
              WHERE lt.status = '$db_status'
              ORDER BY lt.schedule_time ASC";

    $result = mysqli_query($conn, $query);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $student_id = $row["student_id"];
            $student_info = isset($students[$student_id]) ? $students[$student_id] : null;

            // Construct schedule entry
            $response["schedules"][] = [
                "id" => $row["id"],
                "type" => $row["admission_type"],
                "name" => $row["admission_type"] === "Student" && $student_info
                    ? $student_info["name"]
                    : trim($row["firstname"] . " " . $row["lastname"]),
                "appointment_date" => date("Y-m-d H:i A", strtotime($row["appointment_date"])), // Ensure formatted date
                "reason" => "Lab Test", // Default reason
                "status" => $row["status"]
            ];
        }
        $response["success"] = true;
    } else {
        $response["message"] = "Failed to fetch schedules: " . mysqli_error($conn);
    }

    echo json_encode($response);
}

/**
 * Fetch medical records from the database and match them with student details from the API.
 */
function fetchMedicalRecords() {
    global $conn;
    $response = ["success" => false, "records" => []];

    // Fetch all medical records from local DB
    $medicalQuery = "SELECT * FROM medical_records";
    $medicalResult = mysqli_query($conn, $medicalQuery);

    if (!$medicalResult) {
        echo json_encode(["success" => false, "message" => "Database error: " . mysqli_error($conn)]);
        return;
    }

    $medicalRecords = [];
    while ($record = mysqli_fetch_assoc($medicalResult)) {
        $medicalRecords[$record['student_id']] = $record;
    }

    // Fetch student data from new Registrar API
    $api_url = "https://registrar.bcp-sms1.com/api/students.php";
    $apiResponse = file_get_contents($api_url);
    $decoded = json_decode($apiResponse, true);

    if (!$decoded || $decoded['status'] !== 'success' || !isset($decoded['users'])) {
        echo json_encode(["success" => false, "message" => "Failed to retrieve student data from Registrar API."]);
        return;
    }

    $students = $decoded['users'];

    // Match students with medical records
    foreach ($medicalRecords as $studentId => $medicalData) {
        $student = null;

        foreach ($students as $s) {
            if (isset($s['student_info']['student_id']) && $s['student_info']['student_id'] == $studentId) {
                $info = $s['student_info'];
                $student = [
                    "student_id" => $info['student_id'],
                    "student_number" => $info['student_number'],
                    "student_name" => $info['first_name'] . ' ' . $info['last_name'],
                    "year_level" => $info['year_level'],
                    "course" => $info['course'],
                    "email" => $info['email']
                ];
                break;
            }
        }

        if ($student) {
            $response["records"][] = array_merge($student, $medicalData);
        }
    }

    $response["success"] = true;
    echo json_encode($response);
}
