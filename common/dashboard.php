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

                <!-- Medical Schedule Section (Dynamically Loaded) -->
                <!-- <div class="row">
                    <div class="col-lg-12">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Medical Schedule</h6>
                            </div>
                            <div class="card-body">
                                <div id="medicalScheduleContainer">
                                    <p class="text-center">Loading medical schedule...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->

                <!-- Lab Schedule Overview -->
                <!-- <div class="row">
                    <div class="col-lg-12">
                        <div id="labScheduleContainer">
                            <p class="text-center">Loading lab schedule...</p>
                        </div>
                    </div>
                </div>-->

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
        loadDashboardData();
        loadMedicalSchedule();
        loadLabSchedule();

        /** 
         * Function to Load Dashboard Data 
         */
        function loadDashboardData() {
            $.ajax({
                url: '../controllers/DashboardController.php?action=all',
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    console.log("Dashboard Data Response:", response); // Debugging
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
                error: function (xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                }
            });
        }

        /** 
         * Function to Update Medical Cases Chart 
         */
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

        /**
         * Function to Load Medical Schedule
         */
        function loadMedicalSchedule() {
            $.ajax({
                url: '../controllers/DashboardController.php?action=medical_schedule',
                type: 'GET',
                dataType: 'html',
                success: function (response) {
                    $("#medicalScheduleContainer").html(response);
                },
                error: function () {
                    $("#medicalScheduleContainer").html("<p class='text-center text-danger'>Failed to load medical schedule.</p>");
                }
            });
        }

        function loadLabSchedule() {
            $.ajax({
                url: '../controllers/DashboardController.php?action=lab_schedule',
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        $("#labScheduleContainer").html(response.labSchedule);
                    } else {
                        $("#labScheduleContainer").html("<p class='text-center text-danger'>Failed to load lab schedule.</p>");
                    }
                },
                error: function () {
                    $("#labScheduleContainer").html("<p class='text-center text-danger'>Failed to fetch data.</p>");
                }
            });
        }

    });
</script>
