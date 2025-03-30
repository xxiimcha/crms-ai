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
                <h1 class="h3 mb-4 text-gray-800">Dashboard</h1>

                <!-- Cards -->
                <div class="row" id="dashboard-cards">
                    <!-- Cards will be loaded dynamically via AJAX -->
                </div>

                <!-- Chart for Completed Medical Cases -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Completed Medical Cases per Day</h6>
                            </div>
                            <div class="card-body">
                                <div id="admissionsChart"></div> <!-- Chart Container -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Laboratory Schedule -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Pending Laboratory Schedule</h6>
                            </div>
                            <div class="card-body">
                                <div id="labScheduleContainer">
                                    <p class="text-center">Loading lab schedule...</p>
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

<!-- ApexCharts CDN -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
    $(document).ready(function () {
        loadDashboardData();
        loadLabSchedule();

        /** Load Dashboard Cards + Chart */
        function loadDashboardData() {
            $.ajax({
                url: '../controllers/DashboardController.php?action=all',
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        $("#dashboard-cards").html(response.html);

                        if (response.chartData.labels.length > 0) {
                            updateMedicalChart(response.chartData.labels, response.chartData.data);
                        } else {
                            $("#admissionsChart").html("<p class='text-center'>No chart data available.</p>");
                        }
                    } else {
                        console.error("Failed to load dashboard data.");
                    }
                },
                error: function (xhr, status, error) {
                    console.error("AJAX Error:", error);
                }
            });
        }

        /** Render Chart for Completed Medical Cases */
        function updateMedicalChart(labels, data) {
            $("#admissionsChart").html(""); // Clear chart

            var options = {
                chart: {
                    type: 'line',
                    height: 350,
                    toolbar: { show: false }
                },
                series: [{
                    name: 'Completed Medical Cases',
                    data: data
                }],
                xaxis: {
                    categories: labels,
                    title: { text: 'Date' }
                },
                yaxis: {
                    title: { text: 'Cases' },
                    min: 0
                },
                colors: ['#28a745'],
                stroke: {
                    curve: 'smooth'
                },
                dataLabels: {
                    enabled: false
                }
            };

            var chart = new ApexCharts(document.querySelector("#admissionsChart"), options);
            chart.render();
        }

        /** Load Lab Schedule Table */
        function loadLabSchedule() {
            $.ajax({
                url: '../controllers/DashboardController.php?action=lab_schedule',
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        $("#labScheduleContainer").html(response.html);
                    } else {
                        $("#labScheduleContainer").html("<p class='text-center text-danger'>No pending lab schedules.</p>");
                    }
                },
                error: function () {
                    $("#labScheduleContainer").html("<p class='text-center text-danger'>Failed to load lab schedule.</p>");
                }
            });
        }
    });
</script>
