
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
    <ul class="navbar-nav ml-auto">
        <!-- User Information Dropdown -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="text-right mr-3">
                    <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                        <?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest'; ?>
                    </span>
                    <br>
                    <small class="text-muted">
                        <?php echo isset($_SESSION['role']) ? ucfirst($_SESSION['role']) : 'Visitor'; ?>
                    </small>
                </div>
                <img class="img-profile rounded-circle" src="../assets/img/undraw_profile.svg" width="40" height="40">
            </a>
            <!-- Dropdown Menu -->
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
<!-- End of Topbar -->
