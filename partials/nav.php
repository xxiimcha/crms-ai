<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
    <ul class="navbar-nav ml-auto">
        <!-- Notifications Dropdown -->
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>
                <span class="badge badge-danger badge-counter" id="notificationCount">0</span>
            </a>
            <!-- Dropdown Menu -->
            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="alertsDropdown">
                <h6 class="dropdown-header">Stock Notifications</h6>
                <div id="notificationList">
                    <a class="dropdown-item text-center small text-gray-500">No new notifications</a>
                </div>
                <a class="dropdown-item text-center small text-primary" href="../inventory/low_stock.php">View All</a>
            </div>
        </li>

        <!-- User Info -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="text-right mr-3">
                    <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                        <?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest'; ?>
                    </span>
                </div>
                <img class="img-profile rounded-circle" src="../assets/img/undraw_profile.svg" width="40" height="40">
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item disabled">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    <?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest'; ?>
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="../controllers/logout.php">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                </a>
            </div>
        </li>
    </ul>
</nav>
<script>
    $(document).ready(function () {
        function loadNotifications() {
            $.ajax({
                url: '../controllers/StockCheckController.php',
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    let notificationList = $("#notificationList");
                    let notificationCount = $("#notificationCount");

                    notificationList.empty(); // Clear existing notifications

                    if (response.notifications.length > 0) {
                        notificationCount.text(response.notifications.length); // Update badge count
                        $.each(response.notifications, function (index, notification) {
                            notificationList.append(
                                `<a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-${notification.type}">
                                            <i class="fas fa-exclamation-triangle text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="font-weight-bold">${notification.message}</span>
                                    </div>
                                </a>`
                            );
                        });
                    } else {
                        notificationList.append(`<a class="dropdown-item text-center small text-gray-500">No new notifications</a>`);
                        notificationCount.text("0"); // Reset badge count
                    }
                }
            });
        }

        loadNotifications(); // Initial load
        setInterval(loadNotifications, 30000); // Refresh every 30 seconds
    });
</script>
