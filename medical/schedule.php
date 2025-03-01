<?php include('../partials/head.php'); ?>
<?php include('../config/database.php'); ?>

<div id="wrapper">
    <?php include('../partials/sidebar.php'); ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php include('../partials/nav.php'); ?>

            <div class="container-fluid">
                <h1 class="h3 mb-4 text-gray-800">Medical Schedules</h1>

                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Scheduled Appointments</h6>
                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#scheduleModal">
                            <i class="fas fa-plus"></i> Add Schedule
                        </button>
                    </div>
                    <div class="card-body">
                        <!-- Navigation Tabs -->
                        <ul class="nav nav-tabs" id="scheduleTabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="upcoming-tab" data-toggle="tab" href="#upcoming" role="tab">Upcoming</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="completed-tab" data-toggle="tab" href="#completed" role="tab">Completed</a>
                            </li>
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content mt-3">
                            <!-- Upcoming Schedules -->
                            <div class="tab-pane fade show active" id="upcoming" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="upcomingTable" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Type</th>
                                                <th>Appointment Date</th>
                                                <th>Reason</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Completed Schedules -->
                            <div class="tab-pane fade" id="completed" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="completedTable" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Type</th>
                                                <th>Appointment Date</th>
                                                <th>Reason</th>
                                                <th>Status</th>
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

<!-- DataTables & jQuery -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function () {
        function loadSchedules(status, tableId) {
            $.ajax({
                url: '../controllers/MedicalController.php?action=fetch_schedules',
                type: 'GET',
                data: { status: status },
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        var rows = "";
                        $.each(response.schedules, function (index, schedule) {
                            rows += "<tr>" +
                                "<td>" + schedule.name + "</td>" +
                                "<td>" + schedule.type + "</td>" +
                                "<td>" + schedule.appointment_date + "</td>" +
                                "<td>" + schedule.reason + "</td>" +
                                "<td>" + schedule.status + "</td>" +
                                "</tr>";
                        });
                        $(tableId + " tbody").html(rows);
                        $(tableId).DataTable();
                    } else {
                        $(tableId + " tbody").html("<tr><td colspan='5' class='text-center text-danger'>No records found.</td></tr>");
                    }
                }
            });
        }

        // Load data for Upcoming and Completed schedules
        loadSchedules("Upcoming", "#upcomingTable");
        loadSchedules("Completed", "#completedTable");
    });
</script>
