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
              ORDER BY created_at ASC"; // Ensure 'date_completed' exists

    $result = $conn->query($query);

    $chartData = ["labels" => [], "data" => []];

    while ($row = $result->fetch_assoc()) {
        $chartData["labels"][] = $row['record_date'];
        $chartData["data"][] = (int) $row['count'];
    }

    return $chartData;
}

function getLabScheduleOverview($conn) {
    /*$query = "SELECT lt.id, lt.admission_id, lt.schedule_time, lt.status, 
                     a.admission_type, a.firstname, a.lastname, lt.cbc_result, lt.xray_result, lt.urine_result
              FROM lab_tests lt
              LEFT JOIN admissions a ON lt.admission_id = a.id
              ORDER BY lt.schedule_time ASC";
    
    $result = $conn->query($query);
    $html = '<h6 class="m-0 font-weight-bold text-primary">Lab Schedule Overview</h6>
             <div class="table-responsive">
                 <table class="table table-bordered">
                     <thead>
                         <tr>
                             <th>Name</th>
                             <th>Type</th>
                             <th>Schedule Time</th>
                             <th>Status</th>
                             <th>Results</th>
                         </tr>
                     </thead>
                     <tbody>';

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $name = trim($row["firstname"] . " " . $row["lastname"]);
            $statusBadge = match ($row["status"]) {
                "Completed" => '<span class="badge badge-success">Completed</span>',
                "Ongoing" => '<span class="badge badge-warning">Ongoing</span>',
                default => '<span class="badge badge-danger">Pending</span>',
            };

            // Build result links if available
            $results = [];
            if (!empty($row["cbc_result"])) $results[] = '<a href="uploads/' . $row["cbc_result"] . '" target="_blank">CBC</a>';
            if (!empty($row["xray_result"])) $results[] = '<a href="uploads/' . $row["xray_result"] . '" target="_blank">X-Ray</a>';
            if (!empty($row["urine_result"])) $results[] = '<a href="uploads/' . $row["urine_result"] . '" target="_blank">Urine</a>';
            
            $resultsDisplay = !empty($results) ? implode(" | ", $results) : "No Results";

            $html .= '<tr>
                        <td>' . $name . '</td>
                        <td>' . $row["admission_type"] . '</td>
                        <td>' . date("Y-m-d H:i A", strtotime($row["schedule_time"])) . '</td>
                        <td>' . $statusBadge . '</td>
                        <td>' . $resultsDisplay . '</td>
                      </tr>';
        }
    } else {
        $html .= '<tr><td colspan="5" class="text-center">No lab schedules available.</td></tr>';
    }

    $html .= '</tbody></table></div>';
    return $html;*/
}

/**
 * Fetch all dashboard data
 */
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
        $response["success"] = true;
        $response["html"] = getLabScheduleOverview($conn);
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
