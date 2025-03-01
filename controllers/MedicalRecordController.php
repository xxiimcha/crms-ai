<?php
include('../config/database.php');

$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'fetch_medical_records':
        fetchMedicalRecords();
        break;
    case 'save_medical_record':
        saveMedicalRecord();
        break;
    case 'delete_medical_record':
        deleteMedicalRecord();
        break;
    default:
        echo json_encode(["success" => false, "message" => "Invalid action"]);
}

/**
 * Fetch student details from external API and medical records from local DB
 */
function fetchMedicalRecords() {
    global $conn;
    $response = ["success" => false, "records" => []];
    $student_id = isset($_GET['student_id']) ? (int)$_GET['student_id'] : 0;

    if ($student_id <= 0) {
        echo json_encode(["success" => false, "message" => "Invalid student ID"]);
        return;
    }

    // Fetch student details from external API
    $api_url = "https://enrollment.bcp-sms1.com/fetch_students/fetch_students_info_nova.php";
    $api_response = file_get_contents($api_url);
    $students = json_decode($api_response, true);

    $student_data = null;
    foreach ($students as $student) {
        if ($student['studentId'] == $student_id) {
            $student_data = [
                'student_id' => $student['studentId'],
                'name' => $student['name'],
                'year_level' => $student['level'],
                'course_or_strand' => $student['course'],
                'email' => $student['email']
            ];
            break;
        }
    }

    if (!$student_data) {
        echo json_encode(["success" => false, "message" => "Student not found in API"]);
        return;
    }

    // Fetch medical record from local database
    $medical_query = "SELECT * FROM medical_records WHERE student_id = $student_id";
    $medical_result = mysqli_query($conn, $medical_query);
    if ($medical_result && mysqli_num_rows($medical_result) > 0) {
        while ($row = mysqli_fetch_assoc($medical_result)) {
            $student_data["medical_records"][] = $row;
        }
    } else {
        $student_data["medical_records"] = [];
    }

    $response["success"] = true;
    $response["records"] = $student_data;
    echo json_encode($response);
}

/**
 * Save Medical Record
 */
function saveMedicalRecord() {
    global $conn;

    // Validate required fields
    if (!isset($_POST['student_id']) || empty($_POST['student_id'])) {
        echo json_encode(["success" => false, "message" => "Student ID is required."]);
        return;
    }

    $student_id = (int)$_POST['student_id'];
    $hospitalized = mysqli_real_escape_string($conn, $_POST['hospitalized']);
    $surgeries = mysqli_real_escape_string($conn, $_POST['surgeries']);
    $medications = mysqli_real_escape_string($conn, $_POST['medications']);
    $allergies = mysqli_real_escape_string($conn, $_POST['allergies']);
    $existing_conditions = mysqli_real_escape_string($conn, $_POST['existing_conditions']);
    $doctors_notes = mysqli_real_escape_string($conn, $_POST['doctors_notes']);
    $medical_report = "";

    // Handle file upload (if provided)
    if (!empty($_FILES["medical_report"]["name"])) {
        $target_dir = "../uploads/medical_reports/";
        $file_name = basename($_FILES["medical_report"]["name"]);
        $target_file = $target_dir . time() . "_" . $file_name;
        $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validate file type
        $allowedTypes = ["pdf", "jpg", "jpeg", "png"];
        if (!in_array($fileType, $allowedTypes)) {
            echo json_encode(["success" => false, "message" => "Invalid file type. Only PDF, JPG, and PNG allowed."]);
            return;
        }

        // Move file to uploads folder
        if (move_uploaded_file($_FILES["medical_report"]["tmp_name"], $target_file)) {
            $medical_report = time() . "_" . $file_name;
        } else {
            echo json_encode(["success" => false, "message" => "Failed to upload file."]);
            return;
        }
    }

    // Insert data into the database
    $query = "INSERT INTO medical_records (student_id, hospitalized, surgeries, medications, allergies, existing_conditions, doctors_notes, medical_report) 
              VALUES ('$student_id', '$hospitalized', '$surgeries', '$medications', '$allergies', '$existing_conditions', '$doctors_notes', '$medical_report')";

    if (mysqli_query($conn, $query)) {
        echo json_encode(["success" => true, "message" => "Medical record saved successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to save record: " . mysqli_error($conn)]);
    }
}

/**
 * Delete Medical Record
 */
function deleteMedicalRecord() {
    global $conn;
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

    if ($id > 0) {
        // Retrieve the medical report file name
        $fileQuery = "SELECT medical_report FROM medical_records WHERE id = $id";
        $fileResult = mysqli_query($conn, $fileQuery);
        $fileData = mysqli_fetch_assoc($fileResult);
        $fileName = $fileData['medical_report'];

        // Delete record from database
        $query = "DELETE FROM medical_records WHERE id = $id";
        if (mysqli_query($conn, $query)) {
            // Delete associated medical report file
            if (!empty($fileName) && file_exists("../uploads/medical_reports/" . $fileName)) {
                unlink("../uploads/medical_reports/" . $fileName);
            }
            echo json_encode(["success" => true, "message" => "Medical record deleted."]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to delete record: " . mysqli_error($conn)]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Invalid record ID."]);
    }
}
?>
