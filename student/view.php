<?php include('../partials/head.php'); ?>

<div id="wrapper">
    <?php include('../partials/sidebar.php'); ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php include('../partials/nav.php'); ?>

            <div class="container-fluid">
                <h1 class="h3 mb-4 text-gray-800">Student List</h1>

                <!-- Selection Modal -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Select Student Category</h6>
                    </div>
                    <div class="card-body text-center">
                        <p>Please select the category of students you want to view:</p>
                        <div class="form-group">
                            <label class="mr-3">
                                <input type="radio" name="student_category" value="shs" class="student-category"> Senior High School (SHS)
                            </label>
                            <label>
                                <input type="radio" name="student_category" value="college" class="student-category"> College
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Student Table (Hidden Initially) -->
                <div class="card shadow mb-4 d-none" id="studentTableContainer">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Student Records</h6>
                        <a href="form.php" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Add Student
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="studentTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Year Level</th>
                                        <th>Course</th>
                                        <th>Email</th>
                                        <th>Status</th>
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
        // Function to load student data based on category (SHS or College)
        function loadStudentData(category) {
            $.ajax({
                url: '../controllers/StudentController.php?action=fetch_students',
                type: 'GET',
                data: { category: category }, // Sending category parameter
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        var rows = "";
                        $.each(response.students, function (index, student) {
                            var statusBadge = student.status === "Active" ? 
                                '<span class="badge badge-success">Active</span>' : 
                                '<span class="badge badge-danger">Inactive</span>';

                            rows += "<tr>" +
                                "<td>" + student.id + "</td>" +
                                "<td>" + student.name + "</td>" +
                                "<td>" + student.year_level + "</td>" +
                                "<td>" + student.course + "</td>" +
                                "<td>" + student.email + "</td>" +
                                "<td>" + statusBadge + "</td>" +
                                "</tr>";
                        });

                        $("#studentTable tbody").html(rows);
                        $("#studentTable").DataTable();
                        $("#studentTableContainer").removeClass("d-none"); // Show the table
                    } else {
                        $("#studentTable tbody").html("<tr><td colspan='6' class='text-center text-danger'>No records found.</td></tr>");
                        $("#studentTableContainer").removeClass("d-none"); // Show the table
                    }
                },
                error: function () {
                    $("#studentTable tbody").html("<tr><td colspan='6' class='text-center text-danger'>Error fetching data.</td></tr>");
                    $("#studentTableContainer").removeClass("d-none"); // Show the table
                }
            });
        }

        // Handle category selection
        $(".student-category").change(function () {
            var category = $(this).val();
            loadStudentData(category);
        });
    });
</script>
