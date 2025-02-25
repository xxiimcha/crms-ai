<!-- Sidebar -->
<ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar" style="background-color: #000; color: #fff;">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-home"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Clinic System</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Home -->
    <li class="nav-item">
        <a class="nav-link" href="index.html">
            <i class="fas fa-home"></i>
            <span>Home</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Student -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#studentMenu"
            aria-expanded="true" aria-controls="studentMenu">
            <i class="fas fa-user"></i>
            <span>Student</span>
        </a>
        <div id="studentMenu" class="collapse" aria-labelledby="studentHeading" data-parent="#accordionSidebar">
            <div class="bg-dark py-2 collapse-inner rounded">
                <a class="collapse-item text-white" href="student-data.html">Student Data</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Admit -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#admitMenu"
            aria-expanded="true" aria-controls="admitMenu">
            <i class="fas fa-hospital-user"></i>
            <span>Admit</span>
        </a>
        <div id="admitMenu" class="collapse" aria-labelledby="admitHeading" data-parent="#accordionSidebar">
            <div class="bg-dark py-2 collapse-inner rounded">
                <a class="collapse-item text-white" href="admit-data.html">Admit Data</a>
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
                <a class="collapse-item text-white" href="medical.html">Medical</a>
                <a class="collapse-item text-white" href="disease-list.html">Disease List</a>
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
                <a class="collapse-item text-white" href="medication.html">Medication</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Report and Reminder -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#reportMenu"
            aria-expanded="true" aria-controls="reportMenu">
            <i class="fas fa-bell"></i>
            <span>Report and Reminder</span>
        </a>
        <div id="reportMenu" class="collapse" aria-labelledby="reportHeading" data-parent="#accordionSidebar">
            <div class="bg-dark py-2 collapse-inner rounded">
                <a class="collapse-item text-white" href="report.html">Report</a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->
