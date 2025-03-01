<?php
include('../config/database.php');

$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action == 'inventory_report') {
    generateInventoryReport();
} elseif ($action == 'patient_report') {
    generatePatientReport();
} elseif ($action == 'cases_report') {
    generateCasesReport();
}

function generateInventoryReport() {
    global $conn;
    $month = $_POST['month'];

    $query = "SELECT * FROM medical_supplies WHERE MONTH(created_at) = MONTH(?) AND YEAR(created_at) = YEAR(?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $month, $month);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "<h5>Monthly Inventory Report for " . date("F Y", strtotime($month)) . "</h5>";
    echo "<table class='table table-bordered'><tr><th>ID</th><th>Name</th><th>Stock</th><th>Supplier</th></tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>{$row['id']}</td><td>{$row['name']}</td><td>{$row['stock']}</td><td>{$row['supplier']}</td></tr>";
    }
    echo "</table>";
}

function generatePatientReport() {
    global $conn;
    $patient_name = $_POST['patient_name'];

    $query = "SELECT * FROM admitted_patients WHERE patient_name LIKE ?";
    $stmt = $conn->prepare($query);
    $patient_name = "%$patient_name%";
    $stmt->bind_param("s", $patient_name);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "<h5>Report for Patient: " . htmlspecialchars($_POST['patient_name']) . "</h5>";
    echo "<table class='table table-bordered'><tr><th>ID</th><th>Name</th><th>Sickness</th><th>Date Admitted</th></tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>{$row['id']}</td><td>{$row['patient_name']}</td><td>{$row['sickness']}</td><td>{$row['date_admitted']}</td></tr>";
    }
    echo "</table>";
}

function generateCasesReport() {
    global $conn;
    $month = $_POST['month'];

    $query = "SELECT sickness, COUNT(*) AS total_cases FROM admitted_patients WHERE MONTH(date_admitted) = MONTH(?) AND YEAR(date_admitted) = YEAR(?) GROUP BY sickness";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $month, $month);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "<h5>Monthly Admitted Cases Report for " . date("F Y", strtotime($month)) . "</h5>";
    echo "<table class='table table-bordered'><tr><th>Sickness</th><th>Total Cases</th></tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>{$row['sickness']}</td><td>{$row['total_cases']}</td></tr>";
    }
    echo "</table>";
}
?>
