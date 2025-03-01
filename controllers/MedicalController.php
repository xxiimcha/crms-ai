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
    default:
        echo json_encode(["success" => false, "message" => "Invalid action"]);
}

function fetch_schedules() {
    global $conn;
    $status = isset($_GET['status']) ? mysqli_real_escape_string($conn, $_GET['status']) : 'Upcoming';
    $response = ["success" => false, "schedules" => []];

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
                     lt.status, lt.reason, a.admission_type, a.student_id, a.firstname, a.lastname
              FROM lab_tests lt
              LEFT JOIN admissions a ON lt.admission_id = a.id
              WHERE lt.status = '$status'
              ORDER BY lt.schedule_time ASC";

    $result = mysqli_query($conn, $query);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $student_id = $row["student_id"];
            $student_info = isset($students[$student_id]) ? $students[$student_id] : null;

            $response["schedules"][] = [
                "id" => $row["id"],
                "type" => $row["admission_type"],
                "name" => $row["admission_type"] === "Student" && $student_info
                    ? $student_info["name"]
                    : trim($row["firstname"] . " " . $row["lastname"]),
                "appointment_date" => $row["appointment_date"],
                "reason" => $row["reason"] ?? "N/A",
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

    // Fetch all medical records from the local database
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

    // Fetch student details from the external API
    $api_url = "https://enrollment.bcp-sms1.com/fetch_students/fetch_students_info_nova.php";
    $apiResponse = file_get_contents($api_url);
    $students = json_decode($apiResponse, true);

    if (!$students) {
        echo json_encode(["success" => false, "message" => "Failed to retrieve student data from API."]);
        return;
    }

    // Match students with their medical records
    foreach ($medicalRecords as $studentId => $medicalData) {
        $student = null;

        // Find the student details from the API
        foreach ($students as $s) {
            if ($s['studentId'] == $studentId) {
                $student = [
                    "student_id" => $s['studentId'],
                    "student_name" => $s['name'],
                    "year_level" => $s['level'],
                    "course" => $s['course'],
                    "email" => $s['email']
                ];
                break;
            }
        }

        // If student details are found, merge them with the medical record
        if ($student) {
            $response["records"][] = array_merge($student, $medicalData);
        }
    }

    $response["success"] = true;
    echo json_encode($response);
}
