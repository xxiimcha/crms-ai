<?php include('../partials/head.php'); ?>
<?php include('../config/database.php'); ?>

<div id="wrapper">
    <?php include('../partials/sidebar.php'); ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php include('../partials/nav.php'); ?>

            <div class="container-fluid">
                <h1 class="h3 mb-4 text-gray-800">Medical Inventory - Supplies</h1>

                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Medical Supplies</h6>
                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#supplyModal">
                            <i class="fas fa-plus"></i> Add Supply
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="supplyTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Stock</th>
                                        <th>Supplier</th>
                                        <th>Actions</th>
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
        function loadSupplyData() {
            $.ajax({
                url: '../controllers/SupplyController.php?action=fetch_supplies',
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        var rows = "";
                        $.each(response.supplies, function (index, supply) {
                            rows += "<tr>" +
                                "<td>" + supply.id + "</td>" +
                                "<td>" + supply.name + "</td>" +
                                "<td>" + supply.stock + "</td>" +
                                "<td>" + supply.supplier + "</td>" +
                                "<td><button class='btn btn-sm btn-danger'>Delete</button></td>" +
                                "</tr>";
                        });

                        $("#supplyTable tbody").html(rows);
                        $("#supplyTable").DataTable();
                    }
                }
            });
        }

        loadSupplyData();
    });
</script>
