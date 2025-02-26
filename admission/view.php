<?php include('../partials/head.php'); ?>

<div id="wrapper">
    <?php include('../partials/sidebar.php'); ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php include('../partials/nav.php'); ?>

            <div class="container-fluid">
                <h1 class="h3 mb-4 text-gray-800">Admission List</h1>

                <!-- Admission Table -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Admission Records</h6>
                        <a href="form.php" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Add Admission
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="admissionTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Year Level</th>
                                        <th>Course</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data will be loaded dynamically via AJAX -->
                                </tbody>
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

<!-- DataTables Scripts -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function () {
        function loadAdmissionData() {
            $.ajax({
                url: '../controllers/AdmissionController.php?action=fetch_admissions',
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        var rows = "";
                        $.each(response.admissions, function (index, admission) {
                            var statusBadge = admission.status === "Accepted" ? 
                                '<span class="badge badge-success">Accepted</span>' : 
                                '<span class="badge badge-danger">Pending</span>';

                            rows += "<tr>" +
                                "<td>" + admission.id + "</td>" +
                                "<td>" + admission.name + "</td>" +
                                "<td>" + admission.year_level + "</td>" +
                                "<td>" + admission.course + "</td>" +
                                "<td>" + admission.email + "</td>" +
                                "<td>" + statusBadge + "</td>" +
                                "</tr>";
                        });

                        $("#admissionTable tbody").html(rows);
                        $("#admissionTable").DataTable();
                    } else {
                        $("#admissionTable tbody").html("<tr><td colspan='6' class='text-center text-danger'>No records found.</td></tr>");
                    }
                },
                error: function () {
                    $("#admissionTable tbody").html("<tr><td colspan='6' class='text-center text-danger'>Error fetching data.</td></tr>");
                }
            });
        }

        // Load admission data immediately when the page loads
        loadAdmissionData();
    });
</script>
