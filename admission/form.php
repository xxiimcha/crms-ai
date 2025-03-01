<?php include('../partials/head.php'); ?>
<?php include('../config/database.php'); ?>

<div id="wrapper">
    <?php include('../partials/sidebar.php'); ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php include('../partials/nav.php'); ?>

            <div class="container-fluid">
                <h1 class="h3 mb-4 text-gray-800">New Admission</h1>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Admission Form</h6>
                    </div>
                    <div class="card-body">
                        <form id="admissionForm">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Admission Type</label>
                                        <select name="admission_type" id="admission_type" class="form-control" required>
                                            <option value="">Select Type</option>
                                            <option value="Student">Student</option>
                                            <option value="Professor">Professor</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Student Admission Fields -->
                            <div id="studentFields" class="d-none">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Student Number</label>
                                            <input type="text" name="student_number" id="student_number" class="form-control" placeholder="Enter Student Number">
                                        </div>
                                    </div>
                                </div>
                                <div id="studentInfo" class="row d-none">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>First Name</label>
                                            <input type="text" id="student_firstname" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Last Name</label>
                                            <input type="text" id="student_lastname" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" id="student_email" class="form-control" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div id="studentAcademicInfo" class="row d-none">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Year Level</label>
                                            <input type="text" id="student_year_level" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Course</label>
                                            <input type="text" id="student_course" class="form-control" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Manual Input Fields for Non-Students -->
                            <div id="manualFields" class="d-none">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>First Name</label>
                                            <input type="text" name="firstname" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Last Name</label>
                                            <input type="text" name="lastname" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" name="email" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Medical Information Section -->
                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="text-primary">Medical Information</h5>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Symptoms</label>
                                        <textarea name="symptoms" class="form-control" placeholder="Describe symptoms..." required></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Medical History</label>
                                        <textarea name="medical_history" class="form-control" placeholder="Mention any medical history..." required></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Current Medications</label>
                                        <textarea name="current_medications" class="form-control" placeholder="List current medications..." required></textarea>
                                    </div>
                                </div>

                                <!-- Laboratory Schedule Checkbox -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="lab_schedule_checkbox">
                                            <label class="form-check-label" for="lab_schedule_checkbox">Schedule Laboratory Test</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Medical Procedures Selection -->
                                <div class="col-md-6 d-none" id="lab_procedures_container">
                                    <div class="form-group">
                                        <label>Select Medical Procedures</label>
                                        <div class="form-check">
                                            <input type="checkbox" name="lab_procedures[]" value="CBC" class="form-check-input">
                                            <label class="form-check-label">CBC</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" name="lab_procedures[]" value="X-ray" class="form-check-input">
                                            <label class="form-check-label">X-ray</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" name="lab_procedures[]" value="Urinalysis" class="form-check-input">
                                            <label class="form-check-label">Urinalysis</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Laboratory Date Picker -->
                                <div class="col-md-6 d-none" id="lab_date_container">
                                    <div class="form-group">
                                        <label>Laboratory Date</label>
                                        <input type="date" name="lab_date" id="lab_date" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Submit Admission
                            </button>
                            <a href="view.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function () {
        $("#admission_type").change(function () {
            var type = $(this).val();
            if (type === "Student") {
                $("#studentFields").removeClass("d-none");
                $("#manualFields").addClass("d-none");
            } else {
                $("#studentFields").addClass("d-none");
                $("#manualFields").removeClass("d-none");
            }
        });

        $("#student_number").on("input", function () {
            var studentNumber = $(this).val().trim();
            if (studentNumber.length > 0) {
                $.ajax({
                    url: '../controllers/AdmissionController.php?action=get_student_details',
                    type: 'GET',
                    data: { student_number: studentNumber },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            $("#student_firstname").val(response.student.firstname);
                            $("#student_lastname").val(response.student.lastname);
                            $("#student_email").val(response.student.email);
                            $("#student_year_level").val(response.student.year_level);
                            $("#student_course").val(response.student.course);
                            $("#studentInfo, #studentAcademicInfo").removeClass("d-none");
                        } else {
                            $("#studentInfo, #studentAcademicInfo").addClass("d-none");
                        }
                    }
                });
            }
        });

        $("#lab_schedule_checkbox").change(function () {
            $("#lab_procedures_container, #lab_date_container").toggleClass("d-none", !$(this).is(":checked"));
        });
    });
</script>
