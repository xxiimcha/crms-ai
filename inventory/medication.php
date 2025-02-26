<?php include('../partials/head.php'); ?>
<?php include('../config/database.php'); ?>

<div id="wrapper">
    <?php include('../partials/sidebar.php'); ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php include('../partials/nav.php'); ?>

            <div class="container-fluid">
                <h1 class="h3 mb-4 text-gray-800">Medical Inventory - Medicines</h1>

                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Medicine Inventory</h6>
                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#medicineModal">
                            <i class="fas fa-plus"></i> Add Medicine
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="medicineTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Stock</th>
                                        <th>Expiry Date</th>
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

<!-- Toastr Notifications -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<!-- Medicine Modal -->
<div class="modal fade" id="medicineModal" tabindex="-1" role="dialog" aria-labelledby="medicineModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="medicineModalLabel">Add Medicine</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="medicineForm">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Category</label>
                        <input type="text" name="category" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Stock Quantity</label>
                        <input type="number" name="stock" class="form-control" min="1" required>
                    </div>
                    <div class="form-group">
                        <label>Expiry Date</label>
                        <input type="date" name="expiry_date" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Medicine</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- DataTables Scripts -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function () {
        function loadMedicineData() {
            $.ajax({
                url: '../controllers/MedicineController.php?action=fetch_medicines',
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        var rows = "";
                        $.each(response.medicines, function (index, medicine) {
                            rows += "<tr>" +
                                "<td>" + medicine.id + "</td>" +
                                "<td>" + medicine.name + "</td>" +
                                "<td>" + medicine.category + "</td>" +
                                "<td>" + medicine.stock + "</td>" +
                                "<td>" + medicine.expiry_date + "</td>" +
                                "<td>" +
                                "<button class='btn btn-sm btn-danger delete-btn' data-id='" + medicine.id + "'>Delete</button>" +
                                "</td>" +
                                "</tr>";
                        });

                        $("#medicineTable tbody").html(rows);
                        $("#medicineTable").DataTable();
                    }
                }
            });
        }

        // Add Medicine
        $("#medicineForm").submit(function (event) {
            event.preventDefault();
            $.ajax({
                url: '../controllers/MedicineController.php?action=add_medicine',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        toastr.success("Medicine added successfully!");
                        $("#medicineModal").modal('hide');
                        $("#medicineForm")[0].reset();
                        loadMedicineData();
                    } else {
                        toastr.error("Error: " + response.message);
                    }
                }
            });
        });

        // Delete Medicine
        $(document).on("click", ".delete-btn", function () {
            var id = $(this).data("id");
            if (confirm("Are you sure you want to delete this medicine?")) {
                $.ajax({
                    url: '../controllers/MedicineController.php?action=delete_medicine',
                    type: 'POST',
                    data: { id: id },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            toastr.success("Medicine deleted successfully!");
                            loadMedicineData();
                        } else {
                            toastr.error("Error: " + response.message);
                        }
                    }
                });
            }
        });

        loadMedicineData();
    });
</script>
