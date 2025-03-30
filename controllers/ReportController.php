<?php
include('../config/database.php');
header('Content-Type: application/json');

$response = ["success" => false, "message" => "Invalid action."];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reportType = $_POST['report_type'] ?? '';
    $from = $_POST['from_date'] ?? '';
    $to = $_POST['to_date'] ?? '';

    if (empty($reportType) || empty($from) || empty($to)) {
        echo json_encode(["success" => false, "message" => "All fields are required."]);
        exit;
    }

    switch ($reportType) {
        case 'admissions_report':
            fetchAdmissionsReport($conn, $from, $to);
            break;

        case 'medical_report':
            fetchMedicalReport($conn, $from, $to);
            break;

        case 'inventory_report':
            fetchInventoryReport($conn, $from, $to);
            break;

        default:
            echo json_encode(["success" => false, "message" => "Invalid report type."]);
            break;
    }
}

function fetchAdmissionsReport($conn, $from, $to) {
    $query = "SELECT id, admission_type, person_id, diagnosis, status, created_at FROM admissions WHERE DATE(created_at) BETWEEN '$from' AND '$to' ORDER BY created_at DESC";
    $result = $conn->query($query);

    $table_body = "";
    while ($row = $result->fetch_assoc()) {
        $table_body .= "<tr>
            <td>{$row['id']}</td>
            <td>{$row['admission_type']}</td>
            <td>{$row['person_id']}</td>
            <td>{$row['diagnosis']}</td>
            <td>{$row['status']}</td>
            <td>{$row['created_at']}</td>
        </tr>";
    }

    echo json_encode([
        "success" => true,
        "table_header" => "<tr><th>ID</th><th>Type</th><th>Person ID</th><th>Diagnosis</th><th>Status</th><th>Date</th></tr>",
        "table_body" => $table_body
    ]);
}

function fetchMedicalReport($conn, $from, $to) {
    $query = "SELECT * FROM medical_records WHERE DATE(created_at) BETWEEN '$from' AND '$to' ORDER BY created_at DESC";
    $result = $conn->query($query);

    $table_body = "";
    while ($row = $result->fetch_assoc()) {
        $table_body .= "<tr>
            <td>{$row['id']}</td>
            <td>{$row['student_id']}</td>
            <td>{$row['hospitalized']}</td>
            <td>{$row['surgeries']}</td>
            <td>{$row['medications']}</td>
            <td>{$row['allergies']}</td>
            <td>{$row['existing_conditions']}</td>
            <td>{$row['created_at']}</td>
        </tr>";
    }

    echo json_encode([
        "success" => true,
        "table_header" => "<tr><th>ID</th><th>Student ID</th><th>Hospitalized</th><th>Surgeries</th><th>Medications</th><th>Allergies</th><th>Conditions</th><th>Date</th></tr>",
        "table_body" => $table_body
    ]);
}

function fetchInventoryReport($conn, $from, $to) {
    $query = "SELECT * FROM medicines WHERE DATE(created_at) BETWEEN '$from' AND '$to' ORDER BY created_at DESC";
    $result = $conn->query($query);

    $table_body = "";
    while ($row = $result->fetch_assoc()) {
        $table_body .= "<tr>
            <td>{$row['id']}</td>
            <td>{$row['name']}</td>
            <td>{$row['brand']}</td>
            <td>{$row['description']}</td>
            <td>{$row['dosage']}</td>
            <td>{$row['created_at']}</td>
        </tr>";
    }

    echo json_encode([
        "success" => true,
        "table_header" => "<tr><th>ID</th><th>Name</th><th>Brand</th><th>Description</th><th>Dosage</th><th>Date Added</th></tr>",
        "table_body" => $table_body
    ]);
}
