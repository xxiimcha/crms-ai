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
                <h1 class="h3 mb-4 text-gray-800"><i class="fas fa-pills"></i> Medical Inventory - Medicines</h1>

                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Medicine Inventory</h6>
                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#medicineModal">
                            <i class="fas fa-plus"></i> Add Medicine
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="medicineTable" width="100%" cellspacing="0">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Brand</th>
                                        <th>Description</th>
                                        <th>Dosage</th>
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

<!-- Medicine Modal -->
<div class="modal fade" id="medicineModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-capsules"></i> Add Medicine</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="medicineForm">
                    <!-- Medicine Details -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Brand</label>
                                <input type="text" name="brand" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="description" class="form-control" rows="2" required></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Dosage</label>
                                <input type="text" name="dosage" class="form-control" required>
                            </div>
                            <!-- Initial Stock Details -->
                            <div class="form-group">
                                <label>Initial Stock Quantity</label>
                                <input type="number" name="stock" class="form-control" min="1" required>
                            </div>
                            <div class="form-group">
                                <label>Expiry Date</label>
                                <input type="date" name="expiry_date" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-save"></i> Save Medicine</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add Stock Modal -->
<div class="modal fade" id="addStockModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-plus-circle"></i> Add Stock</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="addStockForm">
                    <input type="hidden" name="medicine_id" id="stockMedicineId">
                    <div class="form-group">
                        <label>Stock Quantity</label>
                        <input type="number" name="stock" class="form-control" min="1" required>
                    </div>
                    <div class="form-group">
                        <label>Expiry Date</label>
                        <input type="date" name="expiry_date" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-success btn-block"><i class="fas fa-plus"></i> Add Stock</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- View Stock Modal -->
<div class="modal fade" id="viewStockModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-box"></i> View Stock</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Stock ID</th>
                            <th>Quantity</th>
                            <th>Expiry Date</th>
                            <th>Added On</th>
                        </tr>
                    </thead>
                    <tbody id="stockTableBody">
                        <!-- Data will be dynamically loaded -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
    $(document).ready(function () {

        $("#medicineForm").submit(function (event) {
            event.preventDefault(); // Prevent default form submission

            $.ajax({
                url: '../controllers/MedicineController.php?action=add_medicine', // Ensure correct path
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function (response) {
                    console.log("AJAX Success Response:", response); // Debugging Log
                    if (response.success) {
                        toastr.success("Medicine added successfully!");
                        $("#medicineModal").modal('hide');
                        $("#medicineForm")[0].reset();
                        loadMedicineData();
                    } else {
                        toastr.error("Error: " + response.message);
                    }
                },
                error: function (xhr, status, error) {
                    console.log("AJAX Error:", xhr.responseText); // Debugging Log
                    toastr.error("An error occurred. Check the console for details.");
                }
            });
        });
        
        function loadMedicineData() {
            $.ajax({
                url: '../controllers/MedicineController.php?action=fetch_medicines',
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    var rows = "";
                    $.each(response.medicines, function (index, medicine) {
                        rows += "<tr>" +
                            "<td>" + medicine.id + "</td>" +
                            "<td>" + medicine.name + "</td>" +
                            "<td>" + medicine.brand + "</td>" +
                            "<td>" + medicine.description + "</td>" +
                            "<td>" + medicine.dosage + "</td>" +
                            "<td>" +
                            "<button class='btn btn-sm btn-success add-stock-btn' data-id='" + medicine.id + "'>Add Stock</button> " +
                            "<button class='btn btn-sm btn-info view-stock-btn' data-id='" + medicine.id + "'>View Stock</button>" +
                            "</td>" +
                            "</tr>";
                    });
                    $("#medicineTable tbody").html(rows);
                    $("#medicineTable").DataTable();
                }
            });
        }

        $(document).on("click", ".add-stock-btn", function () {
            var id = $(this).data("id");
            $("#stockMedicineId").val(id);
            $("#addStockModal").modal("show");
        });

        $(document).on("click", ".view-stock-btn", function () {
            var id = $(this).data("id");
            $.ajax({
                url: '../controllers/MedicineController.php?action=view_stock&medicine_id=' + id,
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    var rows = "";
                    $.each(response.stocks, function (index, stock) {
                        rows += "<tr>" +
                            "<td>" + stock.id + "</td>" +
                            "<td>" + stock.stock + "</td>" +
                            "<td>" + stock.expiry_date + "</td>" +
                            "<td>" + stock.created_at + "</td>" +
                            "</tr>";
                    });
                    $("#stockTableBody").html(rows);
                    $("#viewStockModal").modal("show");
                }
            });
        });

        loadMedicineData();
    });
</script>
