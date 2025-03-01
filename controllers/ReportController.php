<?php
include('../config/database.php');

$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action == 'inventory_report') {
    generateInventoryReport();
} elseif ($action == 'patient_report') {
    generatePatientReport();
} elseif ($action == 'cases_report') {
    generateCasesReport();
} elseif ($action == 'medicine_stock_report') {
    generateMedicineStockReport();
}

function generateInventoryReport() {
    global $conn;
    $month = $_POST['month'];
    $monthEscaped = mysqli_real_escape_string($conn, $month);

    $query = "SELECT * FROM medical_supplies WHERE MONTH(created_at) = MONTH('$monthEscaped') AND YEAR(created_at) = YEAR('$monthEscaped')";
    $result = mysqli_query($conn, $query);

    echo "<h5>Inventory Report for " . date("F Y", strtotime($month)) . "</h5>";
    echo "<table class='table table-bordered'><tr><th>ID</th><th>Name</th><th>Stock</th><th>Supplier</th></tr>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr><td>{$row['id']}</td><td>{$row['name']}</td><td>{$row['stock']}</td><td>{$row['supplier']}</td></tr>";
    }
    echo "</table>";
}

function generatePatientReport() {
    global $conn;
    $student_id = $_POST['student_id'];
    $student_idEscaped = mysqli_real_escape_string($conn, $student_id);

    $query = "SELECT * FROM admissions WHERE student_id = '$student_idEscaped'";
    $result = mysqli_query($conn, $query);

    echo "<h5>Admission Report for Student ID: " . htmlspecialchars($student_id) . "</h5>";
    echo "<table class='table table-bordered'><tr><th>ID</th><th>Name</th><th>Sickness</th><th>Date Admitted</th></tr>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr><td>{$row['id']}</td><td>{$row['firstname']} {$row['lastname']}</td><td>{$row['diagnosis']}</td><td>{$row['created_at']}</td></tr>";
    }
    echo "</table>";
}

function generateCasesReport() {
    global $conn;
    $month = $_POST['month'];
    $monthEscaped = mysqli_real_escape_string($conn, $month);

    $query = "SELECT diagnosis, COUNT(*) AS total_cases FROM admissions WHERE MONTH(created_at) = MONTH('$monthEscaped') AND YEAR(created_at) = YEAR('$monthEscaped') GROUP BY diagnosis";
    $result = mysqli_query($conn, $query);

    echo "<h5>Monthly Admitted Cases Report</h5>";
    echo "<table class='table table-bordered'><tr><th>Sickness</th><th>Total Cases</th></tr>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr><td>{$row['diagnosis']}</td><td>{$row['total_cases']}</td></tr>";
    }
    echo "</table>";
}

function generateMedicineStockReport() {
    global $conn;
    $month = $_POST['month'];
    $monthEscaped = mysqli_real_escape_string($conn, $month);

    $query = "SELECT m.name, m.category, ms.quantity, ms.transaction_type, ms.created_at 
              FROM medicine_stocks ms
              JOIN medicines m ON ms.medicine_id = m.id
              WHERE MONTH(ms.created_at) = MONTH('$monthEscaped') AND YEAR(ms.created_at) = YEAR('$monthEscaped')";
    $result = mysqli_query($conn, $query);

    echo "<h5>Medicine Stock Report for " . date("F Y", strtotime($month)) . "</h5>";
    echo "<table class='table table-bordered'><tr><th>Medicine</th><th>Category</th><th>Quantity</th><th>Transaction Type</th><th>Date</th></tr>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr><td>{$row['name']}</td><td>{$row['category']}</td><td>{$row['quantity']}</td><td>{$row['transaction_type']}</td><td>{$row['created_at']}</td></tr>";
    }
    echo "</table>";
}
?>
