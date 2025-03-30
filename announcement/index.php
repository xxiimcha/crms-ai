<?php include('../partials/head.php'); ?>
<?php include('../config/database.php'); ?>

<div id="wrapper">
    <?php include('../partials/sidebar.php'); ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php include('../partials/nav.php'); ?>

            <div class="container-fluid">
                <h1 class="h3 mb-4 text-gray-800"><i class="fas fa-bullhorn"></i> Announcements</h1>

                <div class="card shadow mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Staff Announcements</h6>
                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#announcementModal">
                            <i class="fas fa-plus"></i> Add Announcement
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="announcementTable" width="100%">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Message</th>
                                        <th>Posted By</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data loaded via AJAX -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="announcementModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form id="announcementForm" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-bullhorn"></i> New Announcement</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Message</label>
                    <textarea name="message" class="form-control" rows="4" required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-paper-plane"></i> Post</button>
            </div>
        </form>
    </div>
</div>

<?php include('../partials/foot.php'); ?>

<script>
    $(document).ready(function () {

        // Load announcements
        function loadAnnouncements() {
            $.ajax({
                url: '../controllers/AnnouncementController.php?action=fetch',
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    let rows = '';
                    $.each(response.data, function (i, ann) {
                        rows += `<tr>
                            <td>${ann.id}</td>
                            <td>${ann.title}</td>
                            <td>${ann.message}</td>
                            <td>${ann.posted_by}</td>
                            <td>${ann.created_at}</td>
                        </tr>`;
                    });
                    $('#announcementTable tbody').html(rows);
                    $('#announcementTable').DataTable();
                }
            });
        }

        loadAnnouncements();

        // Add new announcement
        $('#announcementForm').submit(function (e) {
            e.preventDefault();

            $.ajax({
                url: '../controllers/AnnouncementController.php?action=add',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function (res) {
                    if (res.success) {
                        toastr.success('Announcement posted!');
                        $('#announcementModal').modal('hide');
                        $('#announcementForm')[0].reset();
                        $('#announcementTable').DataTable().destroy();
                        loadAnnouncements();
                    } else {
                        toastr.error(res.message);
                    }
                }
            });
        });
    });
</script>
