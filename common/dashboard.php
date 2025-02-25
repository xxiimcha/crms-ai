<?php include('../partials/head.php');?>

<div id="wrapper">

    <?php include('../partials/sidebar.php'); ?>

    <div id="content-wrapper" class="d-flex flex-column">

        <div id="content">

            <?php include('../partials/nav.php'); ?>

            <div class="container-fluid">
                <h1 class="h3 mb-4 text-gray-800">Dashboard</h1>

                <div class="row" id="dashboard-cards">
                    <!-- Cards will be loaded here dynamically via AJAX -->
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

<?php include('../partials/modal.php');?>
<?php include('../partials/foot.php');?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
    $(document).ready(function () {
        function loadDashboardData() {
            $.ajax({
                url: '../controllers/DashboardController.php',
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        $("#dashboard-cards").html(response.html);
                    } else {
                        $("#dashboard-cards").html('<div class="col-12"><p class="text-danger">Error loading data.</p></div>');
                    }
                },
                error: function () {
                    $("#dashboard-cards").html('<div class="col-12"><p class="text-danger">Failed to load data.</p></div>');
                }
            });
        }

        loadDashboardData();
    });

    var options = {
        chart: {
            type: 'line',
            height: 350
        },
        series: [{
            name: 'Admissions',
            data: [30, 45, 50, 60, 80, 100]
        }],
        xaxis: {
            categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"]
        },
        colors: ['#4e73df'],
        stroke: {
            curve: 'smooth'
        },
        dataLabels: {
            enabled: false
        }
    };

    var chart = new ApexCharts(document.querySelector("#admissionsChart"), options);
    chart.render();
</script>
