<?php include('../partials/head.php'); ?>
<?php include('../config/database.php'); ?>

<div id="wrapper">
    <?php include('../partials/sidebar.php'); ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php include('../partials/nav.php'); ?>

            <div class="container-fluid">
                <h1 class="h3 mb-4 text-gray-800">Medical History</h1>

                <!-- Navigation Tabs -->
                <ul class="nav nav-tabs" id="medicalTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="shs-tab" data-toggle="tab" href="#shs" role="tab" aria-controls="shs" aria-selected="true">Senior High School</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="college-tab" data-toggle="tab" href="#college" role="tab" aria-controls="college" aria-selected="false">College</a>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content mt-3" id="medicalTabsContent">
                    <!-- SHS Tab -->
                    <div class="tab-pane fade show active" id="shs" role="tabpanel" aria-labelledby="shs-tab">
                        <div class="form-group">
                            <label>Select Strand</label>
                            <select id="shs_course" class="form-control">
                                <option value="">All Strands</option>
                                <?php
                                $query = "SELECT id, name FROM courses_strands WHERE year_level_id IN (SELECT id FROM year_levels WHERE is_college = 0)";
                                $result = mysqli_query($conn, $query);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<option value='{$row['id']}'>{$row['name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="shsMedicalTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Student Name</th>
                                        <th>Year Level</th>
                                        <th>Strand</th>
                                        <th>Symptoms</th>
                                        <th>Medical History</th>
                                        <th>Current Medications</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                    <!-- College Tab -->
                    <div class="tab-pane fade" id="college" role="tabpanel" aria-labelledby="college-tab">
                        <div class="form-group">
                            <label>Select Course</label>
                            <select id="college_course" class="form-control">
                                <option value="">All Courses</option>
                                <?php
                                $query = "SELECT id, name FROM courses_strands WHERE year_level_id IN (SELECT id FROM year_levels WHERE is_college = 1)";
                                $result = mysqli_query($conn, $query);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<option value='{$row['id']}'>{$row['name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="collegeMedicalTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Student Name</th>
                                        <th>Year Level</th>
                                        <th>Course</th>
                                        <th>Symptoms</th>
                                        <th>Medical History</th>
                                        <th>Current Medications</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Copyright &copy; Your Website 2025</span>
                </div>
            </div>
        </footer>
    </div>
</div>

<?php include('../partials/modal.php'); ?>
<?php include('../partials/foot.php'); ?>

<!-- DataTables & jQuery Scripts -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function () {
        function loadMedicalData(yearLevel, courseId, tableId) {
            $.ajax({
                url: '../controllers/MedicalController.php?action=fetch_medical_records',
                type: 'GET',
                data: { year_level: yearLevel, course_id: courseId },
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        var rows = "";
                        $.each(response.records, function (index, record) {
                            rows += "<tr>" +
                                "<td>" + record.student_name + "</td>" +
                                "<td>" + record.year_level + "</td>" +
                                "<td>" + record.course + "</td>" +
                                "<td>" + record.symptoms + "</td>" +
                                "<td>" + record.medical_history + "</td>" +
                                "<td>" + record.current_medications + "</td>" +
                                "</tr>";
                        });
                        $(tableId + " tbody").html(rows);
                        $(tableId).DataTable();
                    } else {
                        $(tableId + " tbody").html("<tr><td colspan='6' class='text-center text-danger'>No records found.</td></tr>");
                    }
                },
                error: function () {
                    $(tableId + " tbody").html("<tr><td colspan='6' class='text-center text-danger'>Error fetching data.</td></tr>");
                }
            });
        }

        // Load data when selecting SHS strand
        $("#shs_course").change(function () {
            var courseId = $(this).val();
            loadMedicalData(0, courseId, "#shsMedicalTable");
        });

        // Load data when selecting College course
        $("#college_course").change(function () {
            var courseId = $(this).val();
            loadMedicalData(1, courseId, "#collegeMedicalTable");
        });

        // Load initial data (All SHS & College)
        loadMedicalData(0, "", "#shsMedicalTable");
        loadMedicalData(1, "", "#collegeMedicalTable");
    });
</script>
