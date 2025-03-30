<!-- Sidebar -->
<ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar" style="background-color: #000; color: #fff;">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="../dashboard/index.php">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-home"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Clinic System</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Home -->
    <li class="nav-item">
        <a class="nav-link" href="../common/dashboard.php">
            <i class="fas fa-home"></i>
            <span>Home</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <!-- Nav Item - Student -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#studentMenu"
            aria-expanded="true" aria-controls="studentMenu">
            <i class="fas fa-user-graduate"></i>
            <span>Student</span>
        </a>
        <div id="studentMenu" class="collapse" aria-labelledby="studentHeading" data-parent="#accordionSidebar">
            <div class="bg-dark py-2 collapse-inner rounded">
                <a class="collapse-item text-white" href="../student/view.php">Student List</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Admission -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#admitMenu"
            aria-expanded="true" aria-controls="admitMenu">
            <i class="fas fa-hospital-user"></i>
            <span>Admission</span>
        </a>
        <div id="admitMenu" class="collapse" aria-labelledby="admitHeading" data-parent="#accordionSidebar">
            <div class="bg-dark py-2 collapse-inner rounded">
                <a class="collapse-item text-white" href="../admission/view.php">Admission Records</a>
                <a class="collapse-item text-white" href="../admission/form.php">New Admission</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Medical -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#medicalMenu"
            aria-expanded="true" aria-controls="medicalMenu">
            <i class="fas fa-briefcase-medical"></i>
            <span>Medical</span>
        </a>
        <div id="medicalMenu" class="collapse" aria-labelledby="medicalHeading" data-parent="#accordionSidebar">
            <div class="bg-dark py-2 collapse-inner rounded">
                <a class="collapse-item text-white" href="../medical/view.php">Medical Records</a>
                <a class="collapse-item text-white" href="../medical/schedule.php">Medical Schedule</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Inventory -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#inventoryMenu"
            aria-expanded="true" aria-controls="inventoryMenu">
            <i class="fas fa-pills"></i>
            <span>Inventory</span>
        </a>
        <div id="inventoryMenu" class="collapse" aria-labelledby="inventoryHeading" data-parent="#accordionSidebar">
            <div class="bg-dark py-2 collapse-inner rounded">
                <a class="collapse-item text-white" href="../inventory/medication.php">Medication Inventory</a>
                <a class="collapse-item text-white" href="../inventory/supplies.php">Medical Supplies</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Reports -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#reportMenu"
            aria-expanded="true" aria-controls="reportMenu">
            <i class="fas fa-chart-line"></i>
            <span>Reports</span>
        </a>
        <div id="reportMenu" class="collapse" aria-labelledby="reportHeading" data-parent="#accordionSidebar">
            <div class="bg-dark py-2 collapse-inner rounded">
                <a class="collapse-item text-white" href="../report">Reports</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Announcements (Visible to staff and admin) -->
    <?php if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'staff'): ?>
    <li class="nav-item">
        <a class="nav-link" href="../announcement">
            <i class="fas fa-bullhorn"></i>
            <span>Announcements</span>
        </a>
    </li>
    <?php endif; ?>

    <!-- Nav Item - User Management (Only visible to admin) -->
    <?php if ($_SESSION['role'] === 'admin'): ?>
    <li class="nav-item">
        <a class="nav-link" href="../user/">
            <i class="fas fa-users-cog"></i>
            <span>User Management</span>
        </a>
    </li>
    <?php endif; ?>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->
