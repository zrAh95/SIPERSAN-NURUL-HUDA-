<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Tambah Data Pengurus</h1>

     <!-- Notifikasi sukses -->
        <?php if ($this->session->flashdata('success')): ?>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '<?= $this->session->flashdata("success") ?>',
                    timer: 3000,
                    showConfirmButton: false
                });
            </script>
        <?php endif; ?>

        <?php if ($this->session->flashdata('error')): ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '<?= $this->session->flashdata("error") ?>',
                    timer: 3000,
                    showConfirmButton: false
                });
            </script>
        <?php endif; ?>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="post" action="<?= base_url('pengurus/simpan') ?>" enctype="multipart/form-data">
                <div class="form-group mb-3">
                    <label for="nama_pengguna">Nama Pengurus</label>
                    <input type="text" name="nama_pengguna" id="nama_pengguna" class="form-control" placeholder="Masukkan Nama Lengkap Anda" required>
                </div>

                <div class="form-group mb-3">
                    <label for="no_telp">No Telepon</label>
                    <input type="text" name="no_telp" id="no_telp" class="form-control" placeholder="Masukkan nomor dengan lengkap" required>
                </div>

                <div class="form-group mb-3">
                    <label for="password">Password Login</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password" required>
                    <small class="form-text text-muted">
                        Password harus terdiri dari minimal 8 karakter, huruf besar, kecil, angka, dan simbol.
                    </small>
                </div>

                <div class="form-group mb-4">
                    <label for="foto">Foto Pengurus (Opsional)</label>
                    <input type="file" name="foto" id="foto" class="form-control-file">
                </div>

                <button type="submit" class="btn btn-success w-100">Simpan</button>
            </form>
        </div>
    </div>
</div>

<!-- Validasi Password -->
<script>
    document.querySelector('form').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\w\s]).{8,}$/;

        if (!regex.test(password)) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Password Tidak Aman!',
                text: 'Gunakan minimal 8 karakter, huruf besar, kecil, angka, dan simbol.',
            });
        }
    });
</script>

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


