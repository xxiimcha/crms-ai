<?php include('../partials/head.php'); ?>
<?php include('../config/database.php'); ?>

<div id="wrapper">
    <?php include('../partials/sidebar.php'); ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php include('../partials/nav.php'); ?>

            <div class="container-fluid">
                <h1 class="h3 mb-4 text-gray-800">Medical Reports</h1>

                <div class="row">
                    <!-- Monthly Inventory Report -->
                    <div class="col-md-6">
                        <div class="card shadow mb-4">
                            <div class="card-header">
                                <h6 class="m-0 font-weight-bold text-primary">Monthly Inventory Report</h6>
                            </div>
                            <div class="card-body">
                                <form id="inventoryReportForm">
                                    <div class="form-group">
                                        <label>Select Month</label>
                                        <input type="month" id="inventoryMonth" name="month" class="form-control" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Generate Report</button>
                                </form>
                                <div id="inventoryReportResult"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Individual Admission Report -->
                    <div class="col-md-6">
                        <div class="card shadow mb-4">
                            <div class="card-header">
                                <h6 class="m-0 font-weight-bold text-primary">Individual Admission Report</h6>
                            </div>
                            <div class="card-body">
                                <form id="patientReportForm">
                                    <div class="form-group">
                                        <label>Enter Student ID</label>
                                        <input type="text" id="studentID" name="student_id" class="form-control" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Generate Report</button>
                                </form>
                                <div id="patientReportResult"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Monthly Admission Cases Report -->
                    <div class="col-md-6">
                        <div class="card shadow mb-4">
                            <div class="card-header">
                                <h6 class="m-0 font-weight-bold text-primary">Monthly Admission Cases</h6>
                            </div>
                            <div class="card-body">
                                <form id="casesReportForm">
                                    <div class="form-group">
                                        <label>Select Month</label>
                                        <input type="month" id="casesMonth" name="month" class="form-control" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Generate Report</button>
                                </form>
                                <div id="casesReportResult"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Monthly Medicine Stock Report -->
                    <div class="col-md-6">
                        <div class="card shadow mb-4">
                            <div class="card-header">
                                <h6 class="m-0 font-weight-bold text-primary">Monthly Medicine Stock Report</h6>
                            </div>
                            <div class="card-body">
                                <form id="medicineStockReportForm">
                                    <div class="form-group">
                                        <label>Select Month</label>
                                        <input type="month" id="medicineStockMonth" name="month" class="form-control" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Generate Report</button>
                                </form>
                                <div id="medicineStockReportResult"></div>
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

<?php include('../partials/foot.php'); ?>

<script>
$(document).ready(function () {
    function fetchReport(formId, resultDiv, action) {
        $(formId).submit(function (e) {
            e.preventDefault();
            let data = $(this).serialize();

            $.ajax({
                url: '../controllers/ReportController.php?action=' + action,
                type: 'POST',
                data: data,
                success: function (response) {
                    $(resultDiv).html(response);
                }
            });
        });
    }

    fetchReport("#inventoryReportForm", "#inventoryReportResult", "inventory_report");
    fetchReport("#patientReportForm", "#patientReportResult", "patient_report");
    fetchReport("#casesReportForm", "#casesReportResult", "cases_report");
    fetchReport("#medicineStockReportForm", "#medicineStockReportResult", "medicine_stock_report");
});
</script>
