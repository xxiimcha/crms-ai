<?php
include('../config/database.php');

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=clinic_report_" . date("Ymd_His") . ".xls");

$reportType = $_GET['report_type'] ?? '';
$fromDate = $_GET['from_date'] ?? '';
$toDate = $_GET['to_date'] ?? '';

if (empty($reportType) || empty($fromDate) || empty($toDate)) {
    echo "Missing required parameters.";
    exit;
}

function outputTable($headers, $rows) {
    echo "<table border='1'>";
    echo "<tr>";
    foreach ($headers as $head) {
        echo "<th>" . htmlspecialchars($head) . "</th>";
    }
    echo "</tr>";
    foreach ($rows as $row) {
        echo "<tr>";
        foreach ($row as $cell) {
            echo "<td>" . htmlspecialchars($cell) . "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
}

switch ($reportType) {
    case 'admissions_report':
        $sql = "SELECT person_id, firstname, lastname, course, diagnosis, status FROM admissions 
                WHERE DATE(created_at) BETWEEN '$fromDate' AND '$toDate'";
        $result = $conn->query($sql);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = [
                $row['person_id'],
                $row['firstname'] . ' ' . $row['lastname'],
                $row['course'],
                $row['diagnosis'],
                $row['status']
            ];
        }
        outputTable(['Person ID', 'Name', 'Course', 'Diagnosis', 'Status'], $data);
        break;

    case 'medical_report':
        $sql = "SELECT student_id, hospitalized, surgeries, allergies, existing_conditions FROM medical_records 
                WHERE DATE(created_at) BETWEEN '$fromDate' AND '$toDate'";
        $result = $conn->query($sql);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = [
                $row['student_id'],
                $row['hospitalized'],
                $row['surgeries'],
                $row['allergies'],
                $row['existing_conditions']
            ];
        }
        outputTable(['Student ID', 'Hospitalized', 'Surgeries', 'Allergies', 'Conditions'], $data);
        break;

    case 'inventory_report':
        $sql = "SELECT name, brand, description, dosage FROM medicines 
                WHERE DATE(created_at) BETWEEN '$fromDate' AND '$toDate'";
        $result = $conn->query($sql);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = [
                $row['name'],
                $row['brand'],
                $row['description'],
                $row['dosage']
            ];
        }
        outputTable(['Name', 'Brand', 'Description', 'Dosage'], $data);
        break;

    default:
        echo "Invalid report type.";
        break;
}
?>
