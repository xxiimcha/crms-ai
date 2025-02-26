<?php include('../partials/head.php'); ?>
<?php include('../config/database.php'); ?>

<div id="wrapper">
    <?php include('../partials/sidebar.php'); ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php include('../partials/nav.php'); ?>

            <div class="container-fluid">
                <h1 class="h3 mb-4 text-gray-800 text-center">Student Registration</h1>

                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-dark text-white">
                        <h6 class="m-0 font-weight-bold">Fill in the required details</h6>
                    </div>
                    <div class="card-body">
                        <form id="studentForm">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>First Name</label>
                                        <input type="text" name="firstname" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Last Name</label>
                                        <input type="text" name="lastname" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Middle Name</label>
                                        <input type="text" name="middlename" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Student Number</label>
                                        <input type="text" name="student_number" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Birthdate</label>
                                        <input type="date" name="birthdate" id="birthdate" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Age</label>
                                        <input type="text" name="age" id="age" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Address</label>
                                        <input type="text" name="address" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Contact Number</label>
                                        <input type="text" name="contact_number" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Parent's Contact Number</label>
                                        <input type="text" name="parent_contact" class="form-control" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Year Level</label>
                                        <select name="year_level" id="year_level" class="form-control" required>
                                            <option value="">Select Year Level</option>
                                            <?php
                                            $query = "SELECT * FROM year_levels ORDER BY is_college ASC, name ASC";
                                            $result = $conn->query($query);
                                            while ($row = $result->fetch_assoc()) {
                                                echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Course/Strand</label>
                                        <select name="course" id="course" class="form-control" required>
                                            <option value="">Select Course/Strand</option>
                                            <!-- Options will be loaded dynamically via AJAX -->
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select name="status" class="form-control" required>
                                            <option value="Active">Active</option>
                                            <option value="Inactive">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-dark">
                                    <i class="fas fa-save"></i> Save Student
                                </button>
                                <a href="view.php" class="btn btn-outline-dark">
                                    <i class="fas fa-arrow-left"></i> Back to List
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>

        <footer class="sticky-footer bg-dark text-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>&copy; Your Website 2025</span>
                </div>
            </div>
        </footer>
    </div>
</div>

<?php include('../partials/modal.php'); ?>
<?php include('../partials/foot.php'); ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function () {
        // Load courses/strands dynamically based on year level selection
        $("#year_level").change(function () {
            var yearLevelId = $(this).val();
            if (yearLevelId) {
                $.ajax({
                    url: '../controllers/StudentController.php?action=get_courses',
                    type: 'GET',
                    data: { year_level_id: yearLevelId },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            var options = '<option value="">Select Course/Strand</option>';
                            $.each(response.courses, function (index, course) {
                                options += '<option value="' + course.id + '">' + course.code + '</option>';
                            });
                            $("#course").html(options);
                        } else {
                            $("#course").html('<option value="">No courses available</option>');
                        }
                    }
                });
            } else {
                $("#course").html('<option value="">Select Course/Strand</option>');
            }
        });

        // Calculate age from birthdate
        $("#birthdate").change(function () {
            var birthdate = new Date($(this).val());
            var today = new Date();
            var age = today.getFullYear() - birthdate.getFullYear();
            var monthDiff = today.getMonth() - birthdate.getMonth();

            // Adjust age if birthday hasn't occurred yet this year
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthdate.getDate())) {
                age--;
            }

            $("#age").val(age);
        });

        // Submit student form
        $("#studentForm").submit(function (event) {
            event.preventDefault();
            $.ajax({
                url: '../controllers/StudentController.php?action=add_student',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        alert("Student added successfully!");
                        window.location.href = "view.php";
                    } else {
                        alert("Error: " + response.message);
                    }
                },
                error: function () {
                    alert("Failed to save student.");
                }
            });
        });
    });
</script>
