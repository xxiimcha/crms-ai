<?php
include('../config/database.php');

$response = ["success" => false, "html" => ""];

try {
    // Fetch total students
    $queryStudents = "SELECT COUNT(*) AS total_students FROM students";
    $resultStudents = $conn->query($queryStudents);
    $totalStudents = ($resultStudents->num_rows > 0) ? $resultStudents->fetch_assoc()['total_students'] : 0;

    // Fetch total admissions
    $queryAdmit = "SELECT COUNT(*) AS total_admit FROM admit";
    $resultAdmit = $conn->query($queryAdmit);
    $totalAdmit = ($resultAdmit->num_rows > 0) ? $resultAdmit->fetch_assoc()['total_admit'] : 0;

    // Fetch total medical cases
    $queryMedical = "SELECT COUNT(DISTINCT disease) AS total_medical FROM admit";
    $resultMedical = $conn->query($queryMedical);
    $totalMedical = ($resultMedical->num_rows > 0) ? $resultMedical->fetch_assoc()['total_medical'] : 0;

    // Fetch total medications dispensed
    $queryMedicine = "SELECT COUNT(*) AS total_medicine FROM medicine";
    $resultMedicine = $conn->query($queryMedicine);
    $totalMedicine = ($resultMedicine->num_rows > 0) ? $resultMedicine->fetch_assoc()['total_medicine'] : 0;

    // Generate the Bootstrap card layout dynamically
    $html = '
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Students</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">' . $totalStudents . '</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-graduate fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Admissions</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">' . $totalAdmit . '</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hospital-user fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Medical Cases</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">' . $totalMedical . '</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-briefcase-medical fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Medications Dispensed</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">' . $totalMedicine . '</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-pills fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    ';

    $response["success"] = true;
    $response["html"] = $html;
} catch (Exception $e) {
    $response["html"] = '<div class="col-12"><p class="text-danger">Error: ' . $e->getMessage() . '</p></div>';
}

header('Content-Type: application/json');
echo json_encode($response);
?>
