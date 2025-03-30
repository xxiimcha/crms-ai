<?php
include('../config/database.php');
header('Content-Type: application/json');

$action = $_GET['action'] ?? '';
$response = ["success" => false, "message" => "Invalid action."];

switch ($action) {
    case 'inventory_report':
        generateInventoryReport($conn);
        break;

    case 'patient_report':
        generateIndividualReport($conn);
        break;

    case 'admission_cases':
        generateMonthlyAdmissionReport($conn);
        break;

    case 'medicine_stock':
        generateMedicineStockReport($conn);
        break;

    default:
        echo json_encode($response);
        break;
}

function generateInventoryReport($conn) {
    $month = $_POST['month'] ?? '';

    if (empty($month)) {
        echo json_encode(["success" => false, "message" => "All fields are required."]);
        return;
    }

    $startDate = $month . "-01";
    $endDate = date("Y-m-t", strtotime($startDate));

    $sql = "SELECT * FROM medicines WHERE DATE(created_at) BETWEEN '$startDate' AND '$endDate'";
    $result = mysqli_query($conn, $sql);

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    echo json_encode(["success" => true, "data" => $data]);
}

function generateIndividualReport($conn) {
    $student_id = $_POST['student_id'] ?? '';

    if (empty($student_id)) {
        echo json_encode(["success" => false, "message" => "Student ID is required."]);
        return;
    }

    $sql = "SELECT * FROM admissions WHERE student_id = '$student_id'";
    $result = mysqli_query($conn, $sql);

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    echo json_encode(["success" => true, "data" => $data]);
}

function generateMonthlyAdmissionReport($conn) {
    $month = $_POST['month'] ?? '';

    if (empty($month)) {
        echo json_encode(["success" => false, "message" => "Month is required."]);
        return;
    }

    $startDate = $month . "-01";
    $endDate = date("Y-m-t", strtotime($startDate));

    $sql = "SELECT * FROM admissions WHERE DATE(created_at) BETWEEN '$startDate' AND '$endDate'";
    $result = mysqli_query($conn, $sql);

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    echo json_encode(["success" => true, "data" => $data]);
}

function generateMedicineStockReport($conn) {
    $month = $_POST['month'] ?? '';

    if (empty($month)) {
        echo json_encode(["success" => false, "message" => "Month is required."]);
        return;
    }

    $startDate = $month . "-01";
    $endDate = date("Y-m-t", strtotime($startDate));

    $sql = "SELECT ms.*, m.name AS medicine_name 
            FROM medicine_stocks ms
            LEFT JOIN medicines m ON ms.medicine_id = m.id
            WHERE DATE(ms.created_at) BETWEEN '$startDate' AND '$endDate'";
    $result = mysqli_query($conn, $sql);

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    echo json_encode(["success" => true, "data" => $data]);
}
