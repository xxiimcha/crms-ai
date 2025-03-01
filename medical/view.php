<?php include('../partials/head.php'); ?>

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
                    <li class="nav-item">
                        <a class="nav-link" id="all-tab" data-toggle="tab" href="#all" role="tab" aria-controls="all" aria-selected="false">All Records</a>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content mt-3" id="medicalTabsContent">
                    <!-- SHS Tab -->
                    <div class="tab-pane fade show active" id="shs" role="tabpanel" aria-labelledby="shs-tab">
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
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                    <!-- College Tab -->
                    <div class="tab-pane fade" id="college" role="tabpanel" aria-labelledby="college-tab">
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
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                    <!-- All Medical Records Tab -->
                    <div class="tab-pane fade" id="all" role="tabpanel" aria-labelledby="all-tab">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="allMedicalTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Student Name</th>
                                        <th>Year Level</th>
                                        <th>Course/Strand</th>
                                        <th>Symptoms</th>
                                        <th>Medical History</th>
                                        <th>Current Medications</th>
                                        <th>Action</th>
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
        function loadMedicalData(filterLevel, tableId) {
            $.ajax({
                url: 'https://enrollment.bcp-sms1.com/fetch_students/fetch_students_info_nova.php',
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.length > 0) {
                        var rows = "";
                        $.each(response, function (index, student) {
                            var studentId = student.studentId;
                            var studentName = student.name;
                            var yearLevel = student.level;
                            var courseStrand = student.course;

                            $.ajax({
                                url: '../controllers/MedicalController.php?action=fetch_medical_records',
                                type: 'GET',
                                data: { student_id: studentId },
                                dataType: 'json',
                                success: function (medicalResponse) {
                                    if (medicalResponse.success && medicalResponse.records.length > 0) {
                                        var record = medicalResponse.records[0];
                                        var symptoms = record.symptoms || 'N/A';
                                        var medicalHistory = record.medical_history || 'N/A';
                                        var medications = record.current_medications || 'N/A';

                                        // Generate Action Button
                                        var actionButton = "<a href='medical_records.php?id=" + studentId + "' class='btn btn-sm btn-info'>" +
                                                            "<i class='fas fa-eye'></i> View More</a>";

                                        // Filter based on level
                                        if ((filterLevel === "shs" && (yearLevel.includes("Grade 11") || yearLevel.includes("Grade 12"))) || 
                                            (filterLevel === "college" && !yearLevel.includes("Grade")) ||
                                            (filterLevel === "all")) {

                                            rows += "<tr>" +
                                                "<td>" + studentName + "</td>" +
                                                "<td>" + yearLevel + "</td>" +
                                                "<td>" + courseStrand + "</td>" +
                                                "<td>" + symptoms + "</td>" +
                                                "<td>" + medicalHistory + "</td>" +
                                                "<td>" + medications + "</td>" +
                                                "<td>" + actionButton + "</td>" +
                                                "</tr>";
                                        }
                                    }

                                    $(tableId + " tbody").html(rows);
                                    if (!$.fn.DataTable.isDataTable(tableId)) {
                                        $(tableId).DataTable();
                                    }
                                }
                            });
                        });
                    } else {
                        $(tableId + " tbody").html("<tr><td colspan='7' class='text-center text-danger'>No records found.</td></tr>");
                    }
                },
                error: function () {
                    $(tableId + " tbody").html("<tr><td colspan='7' class='text-center text-danger'>Error fetching data.</td></tr>");
                }
            });
        }

        // Load data for each tab
        loadMedicalData("shs", "#shsMedicalTable");
        loadMedicalData("college", "#collegeMedicalTable");
        loadMedicalData("all", "#allMedicalTable");

        // Tab Switch Event
        $('#medicalTabs a').on('click', function (e) {
            e.preventDefault();
            $(this).tab('show');
        });
    });
</script>
