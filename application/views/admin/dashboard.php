<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-download fa-sm text-white-50"></i> Generate Report
        </a>
    </div>

    <!-- Content Row -->
    <div class="row">

        <!-- Total Santri -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Santri</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_santri ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-graduate fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Kamar -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Kamar</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_kamar ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-bed fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Izin Keluar Hari Ini -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Izin Keluar Hari Ini</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $izin_keluar_hari_ini ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-sign-out-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Izin Belum Disetujui -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Izin Belum Disetujui</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $izin_belum_disetujui ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hourglass-half fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik Row -->
    <div class="row">
        <!-- Statistik Pengguna Sistem -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary text-center">Statistik Pengguna Sistem</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="userChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <span class="mr-2">
                            <i class="fas fa-circle text-primary"></i> Santri
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-success"></i> Kamar
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-info"></i> Walikamar
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-danger"></i> Pengurus
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistik Izin Santri -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success text-center">Statistik Izin Santri</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="izinChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <span class="mr-2">
                            <i class="fas fa-circle text-success"></i> Disetujui
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-warning"></i> Pending
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-info"></i> Masuk Hari Ini
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-danger"></i> Keluar Hari Ini
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->
<!-- End of Main Content -->

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {

    // Chart 1 - Pengguna Sistem
    const ctxUser = document.getElementById("userChart");
    new Chart(ctxUser, {
        type: 'doughnut',
        data: {
            labels: ['Santri', 'Kamar', 'Walikamar', 'Pengurus'],
            datasets: [{
                data: [<?= $total_santri ?>, <?= $total_kamar ?>, <?= $total_walikamar ?>, <?= $total_pengurus ?>],
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#e74a3b'],
                hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf', '#be2617'],
                borderColor: "#fff"
            }]
        },
        options: {
            maintainAspectRatio: false,
            tooltips: { backgroundColor: "rgb(255,255,255)", bodyFontColor: "#858796", borderColor: '#dddfeb', borderWidth: 1 },
            legend: { display: false },
            cutoutPercentage: 70,
        }
    });

    // Chart 2 - Izin Santri
    const ctxIzin = document.getElementById("izinChart");
    new Chart(ctxIzin, {
        type: 'doughnut',
        data: {
            labels: ['Disetujui', 'Pending', 'Masuk Hari Ini', 'Keluar Hari Ini'],
            datasets: [{
                data: [
                    <?= $izin_disetujui ?? 0 ?>,
                    <?= $izin_belum_disetujui ?? 0 ?>,
                    <?= $izin_masuk_hari_ini ?? 0 ?>,
                    <?= $izin_keluar_hari_ini ?? 0 ?>
                ],
                backgroundColor: ['#1cc88a', '#f6c23e', '#36b9cc', '#e74a3b'],
                hoverBackgroundColor: ['#17a673', '#dda20a', '#2c9faf', '#be2617'],
                borderColor: "#fff"
            }]
        },
        options: {
            maintainAspectRatio: false,
            tooltips: { backgroundColor: "rgb(255,255,255)", bodyFontColor: "#858796", borderColor: '#dddfeb', borderWidth: 1 },
            legend: { display: false },
            cutoutPercentage: 70,
        }
    });
});
</script>
