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
 * Function to generate Bootstrap cards dynamically
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
 * Function to fetch completed medical cases per day for Chart
 */
function getMedicalChartData($conn) {
    $query = "SELECT DATE(created_at) AS record_date, COUNT(*) AS count 
              FROM admissions 
              WHERE status = 'Completed' 
              GROUP BY DATE(created_at) 
              ORDER BY created_at ASC";

    $result = $conn->query($query);

    $chartData = ["labels" => [], "data" => []];

    while ($row = $result->fetch_assoc()) {
        $chartData["labels"][] = $row['record_date'];
        $chartData["data"][] = (int) $row['count'];
    }

    return $chartData;
}

/**
 * Function to fetch only Pending Laboratory Schedule
 */
function getLabScheduleOverview($conn) {
    $query = "
        SELECT lt.test_name, lt.scheduled_date, a.person_id
        FROM lab_tests lt
        LEFT JOIN admissions a ON lt.admission_id = a.id
        WHERE lt.status = 'Pending'
        ORDER BY lt.scheduled_date ASC
    ";

    $result = $conn->query($query);

    $html = '<div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Person ID</th>
                    <th>Test Name</th>
                    <th>Scheduled Date</th>
                </tr>
            </thead>
            <tbody>';

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $html .= '<tr>
                        <td>' . htmlspecialchars($row["person_id"]) . '</td>
                        <td>' . htmlspecialchars($row["test_name"]) . '</td>
                        <td>' . date("F j, Y - g:i A", strtotime($row["scheduled_date"])) . '</td>
                      </tr>';
        }
    } else {
        $html .= '<tr><td colspan="3" class="text-center">No pending lab schedules.</td></tr>';
    }

    $html .= '</tbody></table></div>';
    return $html;
}


// Determine action
$action = $_GET['action'] ?? 'all';

switch ($action) {
    case 'admissions':
        $totalAdmit = getTotalCount($conn, "admissions");
        $response["success"] = true;
        $response["html"] = generateCard("Admissions", $totalAdmit, "fa-hospital-user", "success");
        break;

    case 'medical_cases':
        $totalMedical = getTotalCount($conn, "admissions", "DISTINCT diagnosis");
        $response["success"] = true;
        $response["html"] = generateCard("Medical Cases", $totalMedical, "fa-briefcase-medical", "warning");
        break;

    case 'medications':
        $totalMedicine = getTotalCount($conn, "medicines");
        $response["success"] = true;
        $response["html"] = generateCard("Medications Dispensed", $totalMedicine, "fa-pills", "danger");
        break;

    case 'chart':
        $chartData = getMedicalChartData($conn);
        $response["success"] = true;
        $response["chartData"] = $chartData;
        break;

    case 'lab_schedule':
        $labSchedule = getLabScheduleOverview($conn);
        $response["success"] = true;
        $response["html"] = $labSchedule;
        break;

    case 'all':
    default:
        $totalAdmit = getTotalCount($conn, "admissions");
        $totalMedical = getTotalCount($conn, "admissions", "DISTINCT diagnosis");
        $totalMedicine = getTotalCount($conn, "medicines");

        $html = generateCard("Admissions", $totalAdmit, "fa-hospital-user", "success");
        $html .= generateCard("Medical Cases", $totalMedical, "fa-briefcase-medical", "warning");
        $html .= generateCard("Medications Dispensed", $totalMedicine, "fa-pills", "danger");

        $chartData = getMedicalChartData($conn);
        $labSchedule = getLabScheduleOverview($conn);

        $response["success"] = true;
        $response["html"] = $html;
        $response["chartData"] = $chartData;
        $response["labSchedule"] = $labSchedule;
        break;
}

header('Content-Type: application/json');
echo json_encode($response);
