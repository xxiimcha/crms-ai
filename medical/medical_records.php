<?php include('../partials/head.php'); ?>

<?php
// Get student ID from URL
$student_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$student = null;
$medical_records = [];

// Fetch student details from the external API
if ($student_id > 0) {
    $api_url = "https://enrollment.bcp-sms1.com/fetch_students/fetch_students_info_nova.php";

    // Fetch API data
    $response = file_get_contents($api_url);
    $students = json_decode($response, true);

    // Find the student by ID
    foreach ($students as $s) {
        if ($s['studentId'] == $student_id) {
            $student = [
                'name' => $s['name'],
                'year_level' => $s['level'],
                'course_or_strand' => $s['course'],
                'email' => $s['email']
            ];
            break;
        }
    }
}

// Fetch medical records from the local database
include('../config/database.php');
if ($student_id > 0) {
    $medical_query = "SELECT * FROM medical_records WHERE student_id = $student_id ORDER BY created_at DESC";
    $medical_result = mysqli_query($conn, $medical_query);
    while ($row = mysqli_fetch_assoc($medical_result)) {
        $medical_records[] = $row;
    }
}
?>

<div id="wrapper">
    <?php include('../partials/sidebar.php'); ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php include('../partials/nav.php'); ?>

            <div class="container-fluid">
                <h1 class="h3 mb-4 text-gray-800 text-center">Previous Medical Records</h1>

                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-dark text-white">
                        <h6 class="m-0 font-weight-bold">Medical Details for <?= $student['name'] ?? 'Unknown' ?></h6>
                    </div>
                    <div class="card-body">
                        <!-- Student Information -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="card border-left-primary shadow py-2">
                                    <div class="card-body">
                                        <h6 class="text-muted">Student Name</h6>
                                        <h5 class="font-weight-bold"><?= $student['name'] ?? 'N/A' ?></h5>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card border-left-success shadow py-2">
                                    <div class="card-body">
                                        <h6 class="text-muted">Year Level</h6>
                                        <h5 class="font-weight-bold"><?= $student['year_level'] ?? 'N/A' ?></h5>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card border-left-info shadow py-2">
                                    <div class="card-body">
                                        <h6 class="text-muted">Course/Strand</h6>
                                        <h5 class="font-weight-bold"><?= $student['course_or_strand'] ?? 'N/A' ?></h5>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Medical History -->
                        <h5 class="text-primary mb-3">Medical History</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Date</th>
                                        <th>Hospitalized</th>
                                        <th>Surgeries</th>
                                        <th>Medications</th>
                                        <th>Allergies</th>
                                        <th>Existing Conditions</th>
                                        <th>Doctor's Notes</th>
                                        <th>Medical Report</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($medical_records) > 0) : ?>
                                        <?php foreach ($medical_records as $record) : ?>
                                            <tr>
                                                <td><?= date('M d, Y', strtotime($record['created_at'])) ?></td>
                                                <td><?= ucfirst($record['hospitalized']) ?></td>
                                                <td><?= ucfirst($record['surgeries']) ?></td>
                                                <td><?= ucfirst($record['medications']) ?></td>
                                                <td><?= $record['allergies'] ?: 'None' ?></td>
                                                <td><?= $record['existing_conditions'] ?: 'None' ?></td>
                                                <td><?= $record['doctors_notes'] ?: 'None' ?></td>
                                                <td>
                                                    <?php if (!empty($record['medical_report'])) : ?>
                                                        <a href="../uploads/medical_reports/<?= $record['medical_report'] ?>" target="_blank" class="btn btn-sm btn-info">View</a>
                                                    <?php else : ?>
                                                        <span class="text-muted">No Report</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <tr>
                                            <td colspan="8" class="text-center text-danger">No previous medical records found.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Back Button -->
                        <div class="text-center mt-4">
                            <a href="medical_history.php" class="btn btn-outline-dark">
                                <i class="fas fa-arrow-left"></i> Back to Medical History
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <footer class="sticky-footer bg-dark text-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>&copy; Your Website 2025</span>
                </div>
            </div>
        </footer>
    </div>
</div>

<?php include('../partials/modal.php'); ?>
<?php include('../partials/foot.php'); ?>

<!-- Include Toastr -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    $(document).ready(function () {
        <?php if (isset($_GET['success']) && $_GET['success'] == 'true') : ?>
            toastr.success("Medical record retrieved successfully!");
        <?php elseif (isset($_GET['error'])) : ?>
            toastr.error("<?= htmlspecialchars($_GET['error']) ?>");
        <?php endif; ?>
    });
</script>
