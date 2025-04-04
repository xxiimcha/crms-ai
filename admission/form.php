<?php include('../partials/head.php'); ?>
<?php 
include('../config/database.php'); 
include('../config/session_check.php');
require_role(['admin', 'staff']); // Allow both admin and staff access
?>

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
                            
                            <!-- General Information Section -->
                            <div class="card mb-4">
                                <div class="card-header bg-info text-white">General Information</div>
                                <div class="card-body">
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

                                    <!-- Student Fields -->
                                    <div id="studentFields" class="d-none">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Student Number</label>
                                                    <input type="text" name="student_number" id="student_number" class="form-control" placeholder="Enter Student Number">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Section</label>
                                                    <input type="text" name="section" id="student_section" class="form-control" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Year Level</label>
                                                    <input type="text" name="year_level" id="student_year_level" class="form-control" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Last Name</label>
                                                    <input type="text" id="student_lastname" class="form-control" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>First Name</label>
                                                    <input type="text" id="student_firstname" class="form-control" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Middle Name</label>
                                                    <input type="text" id="student_middlename" class="form-control" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Sex</label>
                                                    <input type="text" id="student_sex" class="form-control" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Age</label>
                                                    <input type="text" id="student_age" class="form-control" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Professor Fields -->
                                    <div id="professorFields" class="d-none">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Employee ID</label>
                                                    <input type="text" name="employee_id" id="employee_id" class="form-control" placeholder="Enter Employee ID">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Last Name</label>
                                                    <input type="text" id="professor_lastname" class="form-control" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>First Name</label>
                                                    <input type="text" id="professor_firstname" class="form-control" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Middle Name</label>
                                                    <input type="text" id="professor_middlename" class="form-control" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Sex</label>
                                                    <input type="text" id="professor_sex" class="form-control" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Age</label>
                                                    <input type="text" id="professor_age" class="form-control" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- Medical Information Section -->
                            <div class="card mb-4">
                                <div class="card-header bg-danger text-white">Medical Information</div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Symptoms</label>
                                                <input name="symptoms" id="symptoms" class="form-control" placeholder="Enter symptoms..." required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- AI Diagnosis & Medicine Recommendation -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card mb-4">
                                        <div class="card-header bg-primary text-white">AI Diagnosis</div>
                                        <div class="card-body">
                                            <div class="mt-3">
                                                <label>Possible Diagnosis</label>
                                                <div id="ai_diagnosis_list" class="p-2 border rounded bg-light" style="min-height: 40px;">
                                                    <small class="text-muted">Diagnosis will appear here...</small>
                                                </div>
                                            </div>
                                            <div class="mt-3">
                                                <label>Actual Diagnosis</label>
                                                <input type="text" id="correct_diagnosis" name="correct_diagnosis" class="form-control" placeholder="Enter or edit the correct diagnosis...">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Medicine Recommendation -->
                                <div class="col-md-6">
                                    <div class="card mb-4">
                                        <div class="card-header bg-success text-white">Medicine Recommendation</div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label>Recommended Medicines</label>
                                                <ul id="medicine_list" class="list-group"></ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Laboratory & Procedures -->
                            <div class="card mb-4">
                                <div class="card-header bg-warning text-dark">Laboratory & Procedures</div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="lab_schedule_checkbox">
                                                    <label class="form-check-label" for="lab_schedule_checkbox">Schedule Laboratory Test</label>
                                                </div>
                                            </div>
                                        </div>

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

                                        <div id="lab_date_container" class="d-none">
                                            <div class="form-group">
                                                <label for="schedule_time">Lab Test Schedule</label>
                                                <input type="datetime-local" name="schedule_time" id="schedule_time" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submission Buttons -->
                            <div class="text-right">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Submit Admission
                                </button>
                                <a href="view.php" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Back to List
                                </a>
                            </div>
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../partials/foot.php'); ?>

<script>
    $(document).ready(function () {
        $("#admissionForm").submit(function (event) {
            event.preventDefault();
            var formData = new FormData(this);
            
            // Ensure the checkbox value is always sent
            formData.append("lab_schedule_checkbox", $("#lab_schedule_checkbox").is(":checked") ? "on" : "off");

            $.ajax({
                url: '../controllers/AdmissionController.php?action=save_admission',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        alert("Admission successfully saved!");
                        window.location.href = "view.php";
                    } else {
                        alert("Error: " + response.message);
                    }
                },
                error: function () {
                    alert("Failed to save admission.");
                }
            });
        });

        $("#lab_schedule_checkbox").change(function () {
            $("#lab_procedures_container").toggleClass("d-none", !$(this).is(":checked"));
            $("#lab_date_container").toggleClass("d-none", !$(this).is(":checked"));
        });
    });

    $(document).ready(function () {
        // Show the correct fields based on the admission type
        $("#admission_type").change(function () {
            var type = $(this).val();
            if (type === "Student") {
                $("#studentFields").removeClass("d-none");
                $("#manualFields, #professorFields").addClass("d-none");
            } else if (type === "Professor") {
                $("#professorFields").removeClass("d-none");
                $("#manualFields, #studentFields").addClass("d-none");
            } else {
                $("#manualFields").removeClass("d-none");
                $("#studentFields, #professorFields").addClass("d-none");
            }
        });

        // Fetch Student Details
        $("#student_number").on("input", function () {
            var studentNumber = $(this).val().trim();
            if (studentNumber.length > 0) {
                $.ajax({
                    url: "../controllers/proxy.php?type=student",
                    type: "GET",
                    dataType: "json",
                    success: function (response) {
                        if (response.status === "success") {
                            let studentFound = false;
                            response.users.forEach(student => {
                                if (student.student_info.student_number === studentNumber) {
                                    $("#student_firstname").val(student.student_info.first_name);
                                    $("#student_lastname").val(student.student_info.last_name);
                                    $("#student_middlename").val(student.student_info.middle_name);
                                    $("#student_section").val(student.student_info.section);
                                    $("#student_year_level").val(student.student_info.year_level);
                                    $("#student_sex").val(student.student_info.gender);
                                    $("#student_age").val(calculateAge(student.student_info.birth_date));
                                    $("#studentFields").removeClass("d-none");
                                    studentFound = true;
                                }
                            });

                            if (!studentFound) {
                                $("#studentFields input").val("");
                            }
                        }
                    }
                });
            }
        });

        // Fetch Professor Details
        $("#employee_id").on("input", function () {
            var empId = $(this).val().trim();
            if (empId.length > 0) {
                $.ajax({
                    url: "../controllers/proxy.php?type=professor",
                    type: "GET",
                    dataType: "json",
                    success: function (response) {
                        if (response.success) {
                            let professorFound = false;
                            response.data.forEach(professor => {
                                if (professor.user_id === empId) {
                                    $("#professor_firstname").val(professor.firstname);
                                    $("#professor_lastname").val(professor.lastname);
                                    $("#professor_middlename").val(""); // No middle name in API
                                    $("#professor_sex").val(professor.gender);
                                    $("#professor_age").val(professor.age);
                                    professorFound = true;
                                }
                            });

                            if (!professorFound) {
                                $("#professorFields input").val("");
                            }
                        }
                    }
                });
            }
        });

        // Helper function to calculate age from birthdate
        function calculateAge(birthDate) {
            let today = new Date();
            let birth = new Date(birthDate);
            let age = today.getFullYear() - birth.getFullYear();
            let monthDiff = today.getMonth() - birth.getMonth();
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
                age--;
            }
            return age;
        }
    });

    document.addEventListener("DOMContentLoaded", function () {
        var symptomsInput = document.querySelector("#symptoms");
        var tagifySymptoms = new Tagify(symptomsInput, {
            enforceWhitelist: false,
            dropdown: {
                enabled: 1, 
                maxItems: 10,
            }
        });

        function fetchDiagnosis() {
            var symptomsArray = tagifySymptoms.value.map(tag => tag.value).join(" ");

            if (symptomsArray.length === 0) {
                $("#ai_diagnosis_list").html('<small class="text-muted">Diagnosis will appear here...</small>');
                $("#correct_diagnosis_container, #medicine_recommendation_container").addClass("d-none");
                return;
            }

            $.ajax({
                url: "https://crms-ai.onrender.com/predict",
                type: "POST",
                contentType: "application/json",
                data: JSON.stringify({ symptoms: symptomsArray }),
                beforeSend: function () {
                    $("#ai_diagnosis_list").html('<small class="text-muted">Analyzing...</small>');
                },
                success: function (response) {
                    if (response.predictions && response.predictions.length > 0) {
                        var resultHtml = '<ul class="list-group">';
                        var suggestedDiagnosis = response.predictions.map(pred => pred.disease);

                        suggestedDiagnosis.forEach(function (disease) {
                            resultHtml += `<li class="list-group-item suggested-diagnosis">${disease}</li>`;
                        });

                        resultHtml += "</ul>";
                        $("#ai_diagnosis_list").html(resultHtml);
                        $("#correct_diagnosis").val(suggestedDiagnosis[0] || ""); // Pre-fill with the top diagnosis
                        $("#correct_diagnosis_container").removeClass("d-none");
                    } else {
                        $("#ai_diagnosis_list").html('<small class="text-muted">No diagnosis found.</small>');
                        $("#correct_diagnosis_container, #medicine_recommendation_container").addClass("d-none");
                    }
                },
                error: function (xhr) {
                    $("#ai_diagnosis_list").html('<small class="text-danger">Error fetching diagnosis. Please try again.</small>');
                    console.log(xhr.responseText);
                }
            });
        }

        // Auto-trigger diagnosis
        tagifySymptoms.on("add", fetchDiagnosis);
        tagifySymptoms.on("remove", fetchDiagnosis);

        // Clickable AI suggestions to fill input
        $(document).on("click", ".suggested-diagnosis", function () {
            $("#correct_diagnosis").val($(this).text());
            fetchMedicineRecommendations();
        });

        // Trigger medicine recommendations when diagnosis is entered or changed
        $("#correct_diagnosis").on("input", function () {
            fetchMedicineRecommendations();
        });

        function fetchMedicineRecommendations() {
            var selectedDiagnosis = $("#correct_diagnosis").val().trim();
            if (!selectedDiagnosis) {
                $("#medicine_recommendation_container").addClass("d-none");
                return;
            }

            $.ajax({
                url: "https://crms-ai.onrender.com/recommend",
                type: "POST",
                contentType: "application/json",
                data: JSON.stringify({ disease: selectedDiagnosis }),
                success: function (response) {
                    var medicineHtml = response.medicines.map(med => `<li class="list-group-item">${med}</li>`).join("");
                    $("#medicine_list").html(medicineHtml);
                    $("#medicine_recommendation_container").removeClass("d-none");
                }
            });
        }
    });
</script>
