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
                                                <th>Action</th>
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
            </div>
        </div>

        <!-- View Details Modal -->
        <div class="modal fade" id="viewScheduleModal" tabindex="-1" role="dialog" aria-labelledby="viewScheduleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewScheduleModalLabel">View Appointment Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="completeScheduleForm">
                            <input type="hidden" id="schedule_id" name="schedule_id">
                            <div class="form-group">
                                <label>Name:</label>
                                <input type="text" class="form-control" id="view_name" readonly>
                            </div>
                            <div class="form-group">
                                <label>Type:</label>
                                <input type="text" class="form-control" id="view_type" readonly>
                            </div>
                            <div class="form-group">
                                <label>Appointment Date:</label>
                                <input type="text" class="form-control" id="view_date" readonly>
                            </div>
                            <div class="form-group">
                                <label>Reason:</label>
                                <input type="text" class="form-control" id="view_reason" readonly>
                            </div>
                            <div class="form-group">
                                <label>Status:</label>
                                <input type="text" class="form-control" id="view_status" readonly>
                            </div>
                            
                            <!-- File Upload for Lab Results -->
                            <div id="lab_results_section" class="form-group d-none">
                                <label>Upload Lab Results:</label>

                                <div id="cbc_upload" class="d-none">
                                    <label>CBC Result:</label>
                                    <input type="file" class="form-control" name="cbc_result" id="cbc_result">
                                    <a id="cbc_result_link" href="#" target="_blank" class="d-none">View CBC Result</a>
                                </div>

                                <div id="xray_upload" class="d-none">
                                    <label>X-ray Result:</label>
                                    <input type="file" class="form-control" name="xray_result" id="xray_result">
                                    <a id="xray_result_link" href="#" target="_blank" class="d-none">View X-ray Result</a>
                                </div>

                                <div id="urine_upload" class="d-none">
                                    <label>Urinalysis Result:</label>
                                    <input type="file" class="form-control" name="urine_result" id="urine_result">
                                    <a id="urine_result_link" href="#" target="_blank" class="d-none">View Urinalysis Result</a>
                                </div>
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" id="markCompleteBtn" class="btn btn-success">Complete</button>
                        <button type="button" id="saveLabResultsBtn" class="btn btn-primary d-none">Save Results</button>
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

<script>
$(document).ready(function () {
    function loadSchedules(status, tableId) {
        $.ajax({
            url: '../controllers/MedicalController.php?action=fetch_schedules',
            type: 'GET',
            data: { status: status },
            dataType: 'json',
            success: function (response) {
                var table = $(tableId).DataTable();
                table.clear().destroy(); // Destroy previous DataTable instance

                var rows = "";
                if (response.success && response.schedules.length > 0) {
                    $.each(response.schedules, function (index, schedule) {
                        var statusBadge = "";
                        switch (schedule.status) {
                            case "Pending":
                                statusBadge = '<span class="badge badge-warning">Pending</span>';
                                break;
                            case "Completed":
                                statusBadge = '<span class="badge badge-success">Completed</span>';
                                break;
                            case "Cancelled":
                                statusBadge = '<span class="badge badge-danger">Cancelled</span>';
                                break;
                            default:
                                statusBadge = '<span class="badge badge-secondary">' + schedule.status + '</span>';
                        }

                        var actionButtons = `<button class="btn btn-sm btn-info view-details" data-id="${schedule.id}">
                                                <i class="fas fa-eye"></i> View
                                             </button>`;

                        if (schedule.status !== "Completed") {
                            actionButtons += `<button class="btn btn-sm btn-danger cancel-schedule" data-id="${schedule.id}">
                                                 <i class="fas fa-times"></i> Cancel
                                             </button>`;
                        }

                        rows += `<tr>
                                    <td>${schedule.name}</td>
                                    <td>${schedule.type}</td>
                                    <td>${schedule.appointment_date}</td>
                                    <td>${schedule.reason}</td>
                                    <td>${statusBadge}</td>
                                    <td>${actionButtons}</td>
                                </tr>`;
                    });

                    $(tableId + " tbody").html(rows);
                } else {
                    $(tableId + " tbody").html(`<tr><td colspan='6' class='text-center text-danger'>No records found.</td></tr>`);
                }

                $(tableId).DataTable(); // Reinitialize DataTable
            }
        });
    }

    // Load data for Upcoming and Completed schedules
    loadSchedules("Upcoming", "#upcomingTable");
    loadSchedules("Completed", "#completedTable");

    // View Schedule Details
    $(document).on("click", ".view-details", function () {
        var scheduleId = $(this).data("id");

        $.ajax({
            url: '../controllers/MedicalController.php?action=get_schedule_details',
            type: 'GET',
            data: { id: scheduleId },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    $("#schedule_id").val(response.schedule.id);
                    $("#view_name").val(response.schedule.name);
                    $("#view_type").val(response.schedule.type);
                    $("#view_date").val(response.schedule.appointment_date);
                    $("#view_status").val(response.schedule.status);

                    // Hide Complete button if status is Completed
                    if (response.schedule.status === "Completed") {
                        $("#markCompleteBtn").addClass("d-none");
                        $("#lab_results_section, #saveLabResultsBtn").removeClass("d-none");
                    } else {
                        $("#markCompleteBtn").removeClass("d-none");
                        $("#lab_results_section, #saveLabResultsBtn").addClass("d-none");
                    }

                    $("#viewScheduleModal").modal("show");
                } else {
                    alert("Error: " + response.message);
                }
            }
        });
    });

    // Mark as Completed
    $("#markCompleteBtn").click(function () {
        var scheduleId = $("#schedule_id").val();
        $.ajax({
            url: '../controllers/MedicalController.php?action=complete_schedule',
            type: 'POST',
            data: { id: scheduleId },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    alert("Schedule marked as completed!");
                    $("#markCompleteBtn").addClass("d-none"); // Hide Complete button after completion
                    $("#lab_results_section, #saveLabResultsBtn").removeClass("d-none");
                    loadSchedules("Upcoming", "#upcomingTable");
                }
            }
        });
    });

    // Save Lab Results
    $("#saveLabResultsBtn").click(function () {
        var formData = new FormData($("#completeScheduleForm")[0]);
        $.ajax({
            url: '../controllers/MedicalController.php?action=upload_lab_results',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    alert("Lab results uploaded successfully!");
                    $("#viewScheduleModal").modal("hide");
                    loadSchedules("Completed", "#completedTable");
                } else {
                    alert("Error uploading results: " + response.message);
                }
            }
        });
    });
});
</script>
