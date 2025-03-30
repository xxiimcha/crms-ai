<?php include('../partials/head.php'); ?>
<?php include('../config/database.php'); ?>

<div id="wrapper">
    <?php include('../partials/sidebar.php'); ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php include('../partials/nav.php'); ?>

            <div class="container-fluid">
                <h1 class="h3 mb-4 text-gray-800"><i class="fas fa-users"></i> User Management</h1>

                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">User List</h6>
                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#userModal">
                            <i class="fas fa-user-plus"></i> Add User
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="userTable" width="100%" cellspacing="0">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Full Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Status</th>
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
                    <span>&copy; Clinic System 2025</span>
                </div>
            </div>
        </footer>
    </div>
</div>

<?php include('../partials/modal.php'); ?>
<?php include('../partials/foot.php'); ?>

<!-- User Modal -->
<div class="modal fade" id="userModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-user-plus"></i> Add User</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="userForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Full Name</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Email Address</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Role</label>
                                <select name="role" class="form-control" required>
                                    <option value="">Select Role</option>
                                    <option value="admin">Admin</option>
                                    <option value="staff">Staff</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control" required>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-save"></i> Save User</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
    $(document).ready(function () {

        // Submit New User Form
        $("#userForm").submit(function (event) {
            event.preventDefault();

            $.ajax({
                url: '../controllers/UserController.php?action=add_user',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        toastr.success("User added successfully!");
                        $("#userModal").modal('hide');
                        $("#userForm")[0].reset();
                        loadUserData();
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function (xhr) {
                    toastr.error("Error occurred while adding user.");
                    console.error(xhr.responseText);
                }
            });
        });

        // Load User Data
        function loadUserData() {
            $.ajax({
                url: '../controllers/UserController.php?action=fetch_users',
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    const table = $("#userTable").DataTable();
                    table.clear().destroy(); // destroy before injecting new content

                    let rows = "";
                    $.each(response.users, function (index, user) {
                        rows += `<tr>
                            <td>${user.id}</td>
                            <td>${user.name}</td>
                            <td>${user.email}</td>
                            <td>${user.role}</td>
                            <td>
                                <span class="badge badge-${user.status === 'active' ? 'success' : 'secondary'} toggle-status-badge"
                                    style="cursor: pointer;"
                                    data-id="${user.id}" 
                                    data-status="${user.status}">
                                    ${user.status.charAt(0).toUpperCase() + user.status.slice(1)}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning edit-user-btn" data-id="${user.id}">Edit</button>
                            </td>
                        </tr>`;
                    });

                    $("#userTable tbody").html(rows);
                    $("#userTable").DataTable(); // re-initialize DataTable
                }
            });
        }

        // Load users on page load
        loadUserData();
    });

    // Toggle status when clicking on badge
    $(document).on("click", ".toggle-status-badge", function () {
        const id = $(this).data("id");
        const status = $(this).data("status");

        $.ajax({
            url: '../controllers/UserController.php?action=toggle_status',
            type: 'POST',
            data: { id: id, status: status },
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    toastr.success("User status updated.");
                    $('#userTable').DataTable().destroy();
                    loadUserData();
                } else {
                    toastr.error(res.message);
                }
            }
        });
    });

</script>
