<?php include('../partials/head.php'); ?>

<div id="wrapper">
    <?php include('../partials/sidebar.php'); ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php include('../partials/nav.php'); ?>

            <div class="container-fluid">
                <h1 class="h3 mb-4 text-gray-800"><i class="fas fa-chart-line"></i> Generate Reports</h1>

                <!-- Filter Form -->
                <div class="card shadow mb-4">
                    <div class="card-header bg-primary text-white">
                        <h6 class="m-0 font-weight-bold">Filter Report</h6>
                    </div>
                    <div class="card-body">
                        <form id="reportFilterForm" class="row g-3">
                            <div class="col-md-3">
                                <label for="reportType">Report Type</label>
                                <select id="reportType" class="form-control" name="report_type" required>
                                    <option value="admissions">Admission Records</option>
                                    <option value="medical">Medical Records</option>
                                    <option value="inventory">Medication Inventory</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="fromDate">From</label>
                                <input type="date" id="fromDate" name="from_date" class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <label for="toDate">To</label>
                                <input type="date" id="toDate" name="to_date" class="form-control" required>
                            </div>
                            <div class="col-md-3 align-self-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-filter"></i> Generate
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Results Tab -->
                <div id="reportResults" class="card shadow mb-4 d-none">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-success">Report Results</h6>
                        <div>
                            <button class="btn btn-sm btn-secondary" onclick="window.print();">
                                <i class="fas fa-print"></i> Print
                            </button>
                        </div>
                    </div>

                    <div class="card-body">
                        <ul class="nav nav-tabs" id="reportTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="data-tab" data-toggle="tab" href="#dataContent" role="tab">Results Table</a>
                            </li>
                        </ul>

                        <div class="tab-content mt-3" id="reportTabContent">
                            <div class="tab-pane fade show active" id="dataContent" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="reportTable">
                                        <thead></thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include('../partials/foot.php'); ?>

<script>
    $(document).ready(function () {
        $('#reportFilterForm').on('submit', function (e) {
            e.preventDefault();

            $.ajax({
                url: '../controllers/ReportController.php',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        $('#reportResults').removeClass('d-none');
                        $('#reportTable thead').html(response.table_header);
                        $('#reportTable tbody').html(response.table_body);
                    } else {
                        $('#reportResults').addClass('d-none');
                        alert(response.message);
                    }
                },
                error: function () {
                    $('#reportResults').addClass('d-none');
                    alert("Something went wrong. Please try again.");
                }
            });
        });
    });
</script>
