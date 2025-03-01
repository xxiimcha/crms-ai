<?php
include('../config/database.php');

$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'fetch_medical_records':
        fetchMedicalRecords();
        break;
    default:
        echo json_encode(["success" => false, "message" => "Invalid action"]);
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
