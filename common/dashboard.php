<?php include('../partials/head.php');?>

<div id="wrapper">
    <?php include('../partials/sidebar.php'); ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php include('../partials/nav.php'); ?>

            <div class="container-fluid">
                <h1 class="h3 mb-4 text-gray-800">Dashboard</h1>

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

                <!-- Medical Schedule Section -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Medical Schedule</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Year Level</th>
                                                <th>Time</th>
                                                <th>Schedule</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>SHS</td>
                                                <td>8:00 AM - 5:00 PM</td>
                                                <td>Monday</td>
                                                <td><span class="badge badge-success">Finished</span></td>
                                            </tr>
                                            <tr>
                                                <td>First Year</td>
                                                <td>8:00 AM - 5:00 PM</td>
                                                <td>Tuesday</td>
                                                <td><span class="badge badge-warning">Ongoing</span></td>
                                            </tr>
                                            <tr>
                                                <td>Second Year</td>
                                                <td>8:00 AM - 5:00 PM</td>
                                                <td>Wednesday</td>
                                                <td><span class="badge badge-danger">Pending</span></td>
                                            </tr>
                                            <tr>
                                                <td>Third Year</td>
                                                <td>8:00 AM - 5:00 PM</td>
                                                <td>Thursday</td>
                                                <td><span class="badge badge-danger">Pending</span></td>
                                            </tr>
                                            <tr>
                                                <td>Fourth Year</td>
                                                <td>8:00 AM - 5:00 PM</td>
                                                <td>Friday</td>
                                                <td><span class="badge badge-danger">Pending</span></td>
                                            </tr>
                                        </tbody>
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

<script>
    $(document).ready(function () {
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
                            $("#admissionsChart").html("<p class='text-center'>No data available.</p>");
                        }
                    } else {
                        console.error("Error loading data: " + response.error);
                    }
                },
                error: function () {
                    console.error("Failed to fetch data from server.");
                }
            });
        }

        function updateMedicalChart(labels, data) {
            $("#admissionsChart").html(""); // Clear previous chart
            var options = {
                chart: {
                    type: 'line',
                    height: 350
                },
                series: [{
                    name: 'Completed Medical Cases',
                    data: data
                }],
                xaxis: {
                    categories: labels
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

        loadDashboardData();
    });
</script>
