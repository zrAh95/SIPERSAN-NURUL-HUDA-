<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url('dashboard') ?>">
                <div class="sidebar-brand-icon">
                    <i class="fas fa-school"></i>
                </div>
                <div class="sidebar-brand-text mx-3">SIPERSAN</div>
            </a>

            <hr class="sidebar-divider my-0">

            <li class="nav-item <?= $this->uri->segment(1) == 'dashboard' ? 'active' : '' ?>">
                <a class="nav-link" href="<?= base_url('dashboard') ?>">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <hr class="sidebar-divider">

            <div class="sidebar-heading">Kamar Santri</div>
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseFive">
                    <i class="fa-solid fa-bed"></i>
                    <span>Menu Kamar</span>
                </a>
                <div id="collapseFive" class="collapse" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Menu Kamar :</h6>
                        <a class="collapse-item" href="<?= base_url('kamar') ?>">Data Kamar</a>
                    </div>
                </div>
            </li>

            <hr class="sidebar-divider">
            <div class="sidebar-heading">WaliKamar Santri</div>
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSix">
                    <i class="fas fa-fw fa-user-graduate"></i>
                    <span>Walikamar</span>
                </a>
                <div id="collapseSix" class="collapse" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Menu walikamar :</h6>
                        <a class="collapse-item" href="<?= base_url('walikamar') ?>">Data Walikamar</a>
                    </div>
                </div>
            </li>

            <hr class="sidebar-divider">
            <div class="sidebar-heading">Menu Santri Dan Perizinan</div>
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseOne">
                    <i class="fas fa-fw fa-user-graduate"></i>
                    <span>Santri Dan Perizinan</span>
                </a>
                <div id="collapseOne" class="collapse" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Santri Dan Perizinan :</h6>
                        <a class="collapse-item" href="<?= base_url('santri') ?>">Data Santri</a>
                        <a class="collapse-item" href="<?= base_url('santri/perizinan_keluar') ?>">Perizinan Keluar</a>
                        <a class="collapse-item" href="<?= base_url('santri/perizinan_masuk') ?>">Perizinan Masuk</a>
                        <a class="collapse-item" href="<?= base_url('santri/log_keluar_masuk') ?>">Log Keluar Dan Masuk</a>
                    </div>
                </div>
            </li>

            <hr class="sidebar-divider">
            <div class="sidebar-heading">Pengurus</div>
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo">
                    <i class="fa-solid fa-person-military-pointing"></i>
                    <span>Menu Pengurus</span>
                </a>
                <div id="collapseTwo" class="collapse" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Menu Pengurus :</h6>
                        <a class="collapse-item" href="<?= base_url('pengurus') ?>">Data akun pengurus</a>
                        <a class="collapse-item" href="<?= base_url('pengurus/tambah') ?>">Tambah Data Pengurus</a>
                        <a class="collapse-item" href="<?= base_url('pengurus/log_login') ?>">Log pengurus</a>
                    </div>
                </div>
            </li>

            <hr class="sidebar-divider">

            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>

        <!-- End of Sidebar -->

            <!-- Content Wrapper -->
            <div id="content-wrapper" class="d-flex flex-column">

                <!-- Topbar -->
    <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

        <!-- Sidebar Toggle (Topbar) -->
        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
        </button>

        <!-- Logo Mini + Judul Flat -->
        <div class="d-flex align-items-center" style="margin-bottom:8px;">
            <img src="<?= base_url('assets/img/NURULHUDALOGOR.png') ?>" alt="Logo Ponpes" width="28" height="28" class="mr-2">
            <span class="font-weight-bold text-primary" style="font-size: 1.15rem;">SIPERSAN NURUL HUDA</span>
        </div>

        <div class="ml-auto"></div>

        <ul class="navbar-nav ml-auto">
            <!-- ... Navbar kanan tetap ... -->
            <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                        <?= $this->session->userdata('nama_pengguna'); ?>
                    </span>
                    <img class="img-profile rounded-circle"
                        src="<?= base_url('uploads/pengurus/' . $this->session->userdata('foto')) ?>"
                        style="object-fit: cover; width: 32px; height: 32px;">
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                    aria-labelledby="userDropdown">
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                        Logout
                    </a>
                </div>
            </li>
        </ul>
    </nav>
    <!-- End of Topbar -->
