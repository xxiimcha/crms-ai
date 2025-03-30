<?php include('../partials/head.php'); ?>

<?php
// Get student ID from URL
$student_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$student = null;
$medical_record = null;

// Fetch student details from the new API
if ($student_id > 0) {
    $api_url = "https://registrar.bcp-sms1.com/api/students.php";
    $response = file_get_contents($api_url);
    $data = json_decode($response, true);

    if ($data && isset($data['users']) && is_array($data['users'])) {
        foreach ($data['users'] as $user) {
            if (isset($user['student_info']['student_id']) && $user['student_info']['student_id'] == $student_id) {
                $info = $user['student_info'];
                $full_name = trim($info['first_name'] . ' ' . $info['middle_name'] . ' ' . $info['last_name']);

                $student = [
                    'name' => $full_name,
                    'year_level' => $info['year_level'],
                    'course_or_strand' => $info['course'],
                    'email' => $info['email']
                ];
                break;
            }
        }
    }
}

// Fetch medical record from local database
include('../config/database.php');
if ($student_id > 0) {
    $medical_query = "SELECT * FROM medical_records WHERE student_id = $student_id";
    $medical_result = mysqli_query($conn, $medical_query);
    if (mysqli_num_rows($medical_result) > 0) {
        $medical_record = mysqli_fetch_assoc($medical_result);
    }
}
?>


<div id="wrapper">
    <?php include('../partials/sidebar.php'); ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php include('../partials/nav.php'); ?>

            <div class="container-fluid">
                <h1 class="h3 mb-4 text-gray-800 text-center">Medical Record Form</h1>

                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-dark text-white">
                        <h6 class="m-0 font-weight-bold">Medical Details for <?= $student['name'] ?? 'Unknown' ?></h6>
                    </div>
                    <div class="card-body">
                        <form id="medicalForm" enctype="multipart/form-data">
                            <input type="hidden" name="student_id" value="<?= $student_id ?>">

                            <!-- Student Information -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Student Name</label>
                                        <input type="text" class="form-control" value="<?= $student['name'] ?? '' ?>" readonly>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Year Level</label>
                                        <input type="text" class="form-control" value="<?= $student['year_level'] ?? '' ?>" readonly>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Course/Strand</label>
                                        <input type="text" class="form-control" value="<?= $student['course_or_strand'] ?? '' ?>" readonly>
                                    </div>
                                </div>
                            </div>

                            <!-- Medical History Section -->
                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="text-primary">Medical History</h5>
                                </div>

                                <!-- Hospitalization -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Have you ever been hospitalized?</label>
                                        <div>
                                            <label><input type="radio" name="hospitalized" value="Yes" <?= isset($medical_record['hospitalized']) && $medical_record['hospitalized'] == 'Yes' ? 'checked' : '' ?> required> Yes</label>
                                            <label class="ml-3"><input type="radio" name="hospitalized" value="No" <?= isset($medical_record['hospitalized']) && $medical_record['hospitalized'] == 'No' ? 'checked' : '' ?>> No</label>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Medications -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Are you currently taking any medications?</label>
                                        <div>
                                            <label><input type="radio" name="medications" value="Yes" <?= isset($medical_record['medications']) && $medical_record['medications'] == 'Yes' ? 'checked' : '' ?> required> Yes</label>
                                            <label class="ml-3"><input type="radio" name="medications" value="No" <?= isset($medical_record['medications']) && $medical_record['medications'] == 'No' ? 'checked' : '' ?>> No</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Surgeries -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Have you had any surgeries?</label>
                                        <div>
                                            <label><input type="radio" name="surgeries" value="Yes" <?= isset($medical_record['surgeries']) && $medical_record['surgeries'] == 'Yes' ? 'checked' : '' ?> required> Yes</label>
                                            <label class="ml-3"><input type="radio" name="surgeries" value="No" <?= isset($medical_record['surgeries']) && $medical_record['surgeries'] == 'No' ? 'checked' : '' ?>> No</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Allergies -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Allergies</label>
                                        <input type="text" name="allergies" class="form-control" value="<?= $medical_record['allergies'] ?? '' ?>" placeholder="List known allergies">
                                    </div>
                                </div>

                                <!-- Existing Conditions -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Existing Conditions</label>
                                        <input type="text" name="existing_conditions" class="form-control" value="<?= $medical_record['existing_conditions'] ?? '' ?>" placeholder="List medical conditions">
                                    </div>
                                </div>

                                <!-- Doctor's Notes -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Doctor's Notes</label>
                                        <textarea name="doctors_notes" class="form-control" rows="3"><?= $medical_record['doctors_notes'] ?? '' ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-dark">
                                    <i class="fas fa-save"></i> Save Medical Record
                                </button>
                                <a href="medical_records.php" class="btn btn-outline-dark">
                                    <i class="fas fa-arrow-left"></i> Back to Records
                                </a>
                            </div>
                        </form>
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

<!-- Toastr and jQuery -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function () {
        $("#medicalForm").submit(function (event) {
            event.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                url: '../controllers/MedicalRecordController.php?action=save_medical_record',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        toastr.success("Medical record saved successfully!");
                        setTimeout(function () {
                            window.location.href = "view.php";
                        }, 2000);
                    } else {
                        toastr.error("Error: " + response.message);
                    }
                },
                error: function () {
                    toastr.error("Failed to save medical record.");
                }
            });
        });
    });
</script>