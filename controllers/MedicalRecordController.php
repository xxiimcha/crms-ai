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
function fetchMedicalRecords() {
    global $conn;
    $response = ["success" => false, "records" => []];

    $student_id = isset($_GET['student_id']) ? (int)$_GET['student_id'] : 0;

    // Ensure student_id is valid
    if ($student_id < 0) {
        $response["message"] = "Invalid student ID.";
        echo json_encode($response);
        return;
    }

    // Query to fetch medical records along with student details
    $student_query = "SELECT 
                    s.id, 
                    CONCAT(s.firstname, ' ', s.lastname) AS name, 
                    yl.name AS year_level, 
                    cs.name AS course_or_strand 
                FROM students s
                LEFT JOIN year_levels yl ON s.year_level = yl.id
                LEFT JOIN courses_strands cs ON s.course = cs.id
                WHERE s.id = $student_id";


    // If student ID is provided, filter by student ID
    if ($student_id > 0) {
        $query .= " WHERE s.id = $student_id";
    }

    $result = mysqli_query($conn, $query);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $response["records"][] = $row;
        }
        $response["success"] = true;
    } else {
        $response["message"] = "Error fetching records: " . mysqli_error($conn);
    }

    echo json_encode($response);
}


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

    // Check if the student exists
    $studentCheckQuery = "SELECT id FROM students WHERE id = $student_id";
    $studentCheckResult = mysqli_query($conn, $studentCheckQuery);

    if (mysqli_num_rows($studentCheckResult) == 0) {
        echo json_encode(["success" => false, "message" => "Invalid student ID."]);
        return;
    }

    // Check if a record already exists for this student
    $checkDuplicate = "SELECT id FROM medical_records WHERE student_id = $student_id";
    $checkResult = mysqli_query($conn, $checkDuplicate);

    if (mysqli_num_rows($checkResult) > 0) {
        echo json_encode(["success" => false, "message" => "Medical record already exists for this student."]);
        return;
    }

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
