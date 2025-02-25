<?php
include('../config/database.php');

$response = ["success" => false, "html" => "", "chartData" => []];

/**
 * Function to fetch total count from a given table
 */
function getTotalCount($conn, $table, $column = '*', $condition = '') {
    $query = "SELECT COUNT($column) AS total FROM $table $condition";
    $result = $conn->query($query);
    return ($result->num_rows > 0) ? $result->fetch_assoc()['total'] : 0;
}

/**
 * Function to generate a Bootstrap card
 */
function generateCard($title, $count, $icon, $color) {
    return '
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-' . $color . ' shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-' . $color . ' text-uppercase mb-1">' . $title . '</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">' . $count . '</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas ' . $icon . ' fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>';
}

/**
 * Function to fetch Completed Medical Cases per Day for Chart
 */
function getMedicalChartData($conn) {
    $query = "SELECT DATE(date_completed) AS record_date, COUNT(*) AS count 
              FROM medical_records 
              WHERE status = 'Completed' 
              GROUP BY DATE(date_completed) 
              ORDER BY date_completed ASC";

    $result = $conn->query($query);

    $chartData = ["labels" => [], "data" => []];

    while ($row = $result->fetch_assoc()) {
        $chartData["labels"][] = $row['record_date'];
        $chartData["data"][] = (int) $row['count'];
    }

    return $chartData;
}

/**
 * Switch case to handle multiple types of data fetching
 */
$action = $_GET['action'] ?? 'all';  // Default to fetching all data

switch ($action) {
    case 'students':
        $totalStudents = getTotalCount($conn, "students");
        $response["success"] = true;
        $response["html"] = generateCard("Total Students", $totalStudents, "fa-user-graduate", "primary");
        break;

    case 'admissions':
        $totalAdmit = getTotalCount($conn, "admit");
        $response["success"] = true;
        $response["html"] = generateCard("Admissions", $totalAdmit, "fa-hospital-user", "success");
        break;

    case 'medical_cases':
        $totalMedical = getTotalCount($conn, "admit", "DISTINCT disease");
        $response["success"] = true;
        $response["html"] = generateCard("Medical Cases", $totalMedical, "fa-briefcase-medical", "warning");
        break;

    case 'medications':
        $totalMedicine = getTotalCount($conn, "medicine");
        $response["success"] = true;
        $response["html"] = generateCard("Medications Dispensed", $totalMedicine, "fa-pills", "danger");
        break;

    case 'chart':
        $chartData = getMedicalChartData($conn);
        $response["success"] = true;
        $response["chartData"] = $chartData;
        break;

    case 'all':
    default:
        // Fetch all data at once
        $totalStudents = getTotalCount($conn, "students");
        $totalAdmit = getTotalCount($conn, "admit");
        $totalMedical = getTotalCount($conn, "admit", "DISTINCT disease");
        $totalMedicine = getTotalCount($conn, "medicine");

        $html = generateCard("Total Students", $totalStudents, "fa-user-graduate", "primary");
        $html .= generateCard("Admissions", $totalAdmit, "fa-hospital-user", "success");
        $html .= generateCard("Medical Cases", $totalMedical, "fa-briefcase-medical", "warning");
        $html .= generateCard("Medications Dispensed", $totalMedicine, "fa-pills", "danger");

        $chartData = getMedicalChartData($conn);

        $response["success"] = true;
        $response["html"] = $html;
        $response["chartData"] = $chartData;
        break;
}

header('Content-Type: application/json');
echo json_encode($response);
