<!doctype html>
<html lang="en">
<head>
    <title>Login Pengurus</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Fonts + Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="<?= base_url('assetslogin/css/style.css') ?>">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<section class="ftco-section">
    <div class="container">
        <div class="row justify-content-center mb-4">
            <div class="col-md-6 text-center">
                <h2 class="heading-section"></h2>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-12 col-lg-10">
                <div class="wrap d-md-flex">
                    <div class="img" style="background-image: url(<?= base_url('assetslogin/images/asset-1.jpg') ?>);">
                    </div>
                    <div class="login-wrap p-4 p-md-5">
                        <!-- Logo Bulat -->
                        <div class="text-center mb-3">
                            <img src="<?= base_url('assets/img/NURULHUDALOGOR.png') ?>" 
                                style="width: 100px; height: 100px; border-radius: 50%; object-fit:">
                        </div>

                        <div class="d-flex mb-3">
                            <div class="w-100">
                                <h2 class="mb-4"><b>SIPERSAN NURULHUDA LOGIN</b></h2>
                            </div>
                        </div>

                        <!-- ✅ Form Login -->
                        <form action="<?= base_url('pengurus/auth') ?>" method="post" class="signin-form" id="formLogin">
                            <div class="form-group mb-3">
                                <label class="label" for="username">Username</label>
                                <input type="text" name="username" class="form-control" placeholder="Masukkan Username" required>
                            </div>
                            <div class="form-group mb-3">
                                <label class="label" for="password">Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Masukkan Password" required>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="form-control btn" style="background-color: #28a745; color:white;">Login</button>
                            </div>
                        </form>
                        <p class="text-center text-muted">Tidak bisa akses dan lupa password? <b>Silakan hubungi pengurus utama.</b></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- JS -->
<script src="<?= base_url('assetslogin/js/jquery.min.js') ?>"></script>
<script src="<?= base_url('assetslogin/js/popper.js') ?>"></script>
<script src="<?= base_url('assetslogin/js/bootstrap.min.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?= base_url('assetslogin/js/main.js') ?>"></script>
<script>
    document.getElementById('formLogin').addEventListener('submit', function(e) {
        const username = document.querySelector('[name="username"]').value.trim();
        const password = document.querySelector('[name="password"]').value.trim();
        if (!username || !password) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Oops!',
                text: 'Username dan password harus diisi.',
                confirmButtonColor: '#ffc107'
            });
        }
    });
</script>

<!-- ✅ Flashdata Alert via SweetAlert -->
<?php if ($this->session->flashdata('error')): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal Login',
            text: '<?= $this->session->flashdata("error") ?>',
            confirmButtonColor: '#dc3545'
        });
    </script>
<?php endif; ?>
</body>
</html>
