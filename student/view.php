<?php include('../partials/head.php'); ?>

<div id="wrapper">
    <?php include('../partials/sidebar.php'); ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php include('../partials/nav.php'); ?>

            <div class="container-fluid">
                <h1 class="h3 mb-4 text-gray-800">Student List</h1>

                <!-- Tabs for Category Selection -->
                <ul class="nav nav-tabs" id="studentTabs">
                    <li class="nav-item">
                        <a class="nav-link active" id="shs-tab" data-toggle="tab" href="#shs" role="tab">Senior High School</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="college-tab" data-toggle="tab" href="#college" role="tab">College</a>
                    </li>
                </ul>

                <div class="tab-content mt-3">
                    <!-- SHS Student Table -->
                    <div class="tab-pane fade show active" id="shs" role="tabpanel">
                        <div class="card shadow mb-4">
                            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                                <h6 class="m-0 font-weight-bold">Senior High School Records</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" id="shsTable" width="100%" cellspacing="0">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Year Level</th>
                                                <th>Strand</th>
                                                <th>Email</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- College Student Table -->
                    <div class="tab-pane fade" id="college" role="tabpanel">
                        <div class="card shadow mb-4">
                            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                <h6 class="m-0 font-weight-bold">College Records</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" id="collegeTable" width="100%" cellspacing="0">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Year Level</th>
                                                <th>Course</th>
                                                <th>Email</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
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

<!-- DataTables & Bootstrap Scripts -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.5.3/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function () {
        var shsTable, collegeTable;

        function loadStudentData() {
            $.ajax({
                url: 'https://enrollment.bcp-sms1.com/fetch_students/fetch_students_info_nova.php', // Updated URL
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.length > 0) {
                        var shsRows = "", collegeRows = "";
                        $.each(response, function (index, student) {
                            var statusBadge = '<span class="badge badge-success">Active</span>';

                            var studentRow = "<tr>" +
                                "<td>" + student.studentId + "</td>" +
                                "<td>" + student.name + "</td>" +
                                "<td>" + student.level + "</td>" +
                                "<td>" + student.course + "</td>" +
                                "<td>" + student.email + "</td>" +
                                "<td>" + statusBadge + "</td>" +
                                "<td>" +
                                    "<a href='medical_records.php?id=" + student.studentId + "' class='btn btn-sm btn-warning'>" +
                                        "<i class='fas fa-file-medical'></i> Medical Records" +
                                    "</a>" +
                                "</td>" +
                                "</tr>";

                            // Check if student is SHS or College
                            if (["Grade 11", "Grade 12"].includes(student.level)) {
                                shsRows += studentRow;
                            } else {
                                collegeRows += studentRow;
                            }
                        });

                        if ($.fn.DataTable.isDataTable("#shsTable")) {
                            shsTable.destroy();
                        }
                        if ($.fn.DataTable.isDataTable("#collegeTable")) {
                            collegeTable.destroy();
                        }

                        $("#shsTable tbody").html(shsRows);
                        $("#collegeTable tbody").html(collegeRows);

                        shsTable = $("#shsTable").DataTable();
                        collegeTable = $("#collegeTable").DataTable();
                    } else {
                        $("#shsTable tbody, #collegeTable tbody").html("<tr><td colspan='7' class='text-center text-danger'>No records found.</td></tr>");
                    }
                },
                error: function () {
                    $("#shsTable tbody, #collegeTable tbody").html("<tr><td colspan='7' class='text-center text-danger'>Error fetching data.</td></tr>");
                }
            });
        }

        // Load student data on page load
        loadStudentData();

        // Handle tab switch
        $('#studentTabs a').on('click', function (e) {
            e.preventDefault();
            $(this).tab('show');
        });
    });
</script>
