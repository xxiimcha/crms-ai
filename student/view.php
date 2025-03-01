<?php include('../partials/head.php'); ?>

<div id="wrapper">
    <?php include('../partials/sidebar.php'); ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php include('../partials/nav.php'); ?>

            <div class="container-fluid">
                <h1 class="h3 mb-4 text-gray-800">Student List</h1>

                <!-- Tabs for Category Selection -->
                <ul class="nav nav-tabs" id="studentTabs">
                    <li class="nav-item">
                        <a class="nav-link active" id="shs-tab" data-toggle="tab" href="#shs" role="tab">Senior High School</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="college-tab" data-toggle="tab" href="#college" role="tab">College</a>
                    </li>
                </ul>

                <div class="tab-content mt-3">
                    <!-- SHS Student Table -->
                    <div class="tab-pane fade show active" id="shs" role="tabpanel">
                        <div class="card shadow mb-4">
                            <div class="card-header bg-info text-white">
                                <h6 class="m-0 font-weight-bold">Senior High School Records</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" id="shsTable">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Year Level</th>
                                                <th>Strand</th>
                                                <th>Email</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- College Student Table -->
                    <div class="tab-pane fade" id="college" role="tabpanel">
                        <div class="card shadow mb-4">
                            <div class="card-header bg-primary text-white">
                                <h6 class="m-0 font-weight-bold">College Records</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" id="collegeTable">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Year Level</th>
                                                <th>Course</th>
                                                <th>Email</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
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

<!-- DataTables & Bootstrap Scripts -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.5.3/js/bootstrap.bundle.min.js"></script>

<!-- Include QR Code Library -->
<script src="https://cdn.jsdelivr.net/npm/qrious@4.0.2/dist/qrious.min.js"></script>

<script>
    $(document).ready(function () {
        var shsTable, collegeTable;

        function loadStudentData() {
            $.ajax({
                url: 'https://enrollment.bcp-sms1.com/fetch_students/fetch_students_info_nova.php', 
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.length > 0) {
                        var shsRows = "", collegeRows = "";
                        $.each(response, function (index, student) {
                            var statusBadge = '<span class="badge badge-success">Active</span>';
                            var studentId = student.studentId;
                            var studentName = student.name;
                            var qrData = "http://localhost/crms-ai/medical/medical_records.php?id=" + studentId;

                            // Create View, Add Medical Record, and QR Code Buttons
                            var actions = `
                                <a href='../medical/medical_records.php?id=${studentId}' class='btn btn-sm btn-warning'>
                                    <i class='fas fa-file-medical'></i> View Medical
                                </a>
                                <a href='../medical/add_medical_record.php?id=${studentId}' class='btn btn-sm btn-success'>
                                    <i class='fas fa-plus'></i> Add Medical
                                </a>
                                <button class='btn btn-sm btn-secondary' onclick="generateQrCode('${qrData}', '${studentName}')">
                                    <i class='fas fa-qrcode'></i> QR Code
                                </button>
                            `;

                            var studentRow = "<tr>" +
                                "<td>" + studentId + "</td>" +
                                "<td>" + studentName + "</td>" +
                                "<td>" + student.level + "</td>" +
                                "<td>" + student.course + "</td>" +
                                "<td>" + student.email + "</td>" +
                                "<td>" + statusBadge + "</td>" +
                                "<td>" + actions + "</td>" +
                                "</tr>";

                            if (["Grade 11", "Grade 12"].includes(student.level)) {
                                shsRows += studentRow;
                            } else {
                                collegeRows += studentRow;
                            }
                        });

                        if ($.fn.DataTable.isDataTable("#shsTable")) shsTable.destroy();
                        if ($.fn.DataTable.isDataTable("#collegeTable")) collegeTable.destroy();

                        $("#shsTable tbody").html(shsRows);
                        $("#collegeTable tbody").html(collegeRows);

                        shsTable = $("#shsTable").DataTable();
                        collegeTable = $("#collegeTable").DataTable();
                    }
                }
            });
        }

        loadStudentData();
    });

    // Function to Generate & Download QR Code
    function generateQrCode(url, studentName) {
        var qr = new QRious({
            element: document.getElementById("qrCanvas"),
            value: url,
            size: 200
        });

        $("#qrStudentName").text(studentName);
        $("#qrModal").modal("show");
    }

    function downloadQrCode() {
        var canvas = document.getElementById("qrCanvas");
        var studentName = $("#qrStudentName").text();
        var link = document.createElement("a");
        link.download = studentName + "_QR.png";
        link.href = canvas.toDataURL();
        link.click();
    }

    function printQrCode() {
        var printWindow = window.open('', '_blank');
        var canvas = document.getElementById("qrCanvas");
        var imgData = canvas.toDataURL();
        var studentName = $("#qrStudentName").text();

        printWindow.document.write('<html><head><title>Print QR Code</title></head><body>');
        printWindow.document.write('<h2>' + studentName + '</h2>');
        printWindow.document.write('<img src="' + imgData + '">');
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    }
</script>

<!-- QR Code Modal -->
<div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Medical History QR Code</h5>
            </div>
            <div class="modal-body text-center">
                <canvas id="qrCanvas"></canvas>
                <h5 id="qrStudentName"></h5>
                <button class="btn btn-primary" onclick="downloadQrCode()">Download</button>
                <button class="btn btn-secondary" onclick="printQrCode()">Print</button>
            </div>
        </div>
    </div>
</div>
