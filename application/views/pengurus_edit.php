<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Edit Data Pengurus</h1>

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

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="<?= base_url('pengurus/update/' . $pengurus->id_pengurus) ?>" method="post" enctype="multipart/form-data">
                <div class="form-group mb-3">
                    <label>Nama Pengurus</label>
                    <input type="text" name="nama_pengguna" class="form-control" value="<?= $pengurus->nama_pengguna ?>" required>
                </div>
                <div class="form-group mb-3">
                    <label>No Telepon</label>
                    <input type="text" name="no_telp" class="form-control" value="<?= $pengurus->no_telp ?>" required>
                </div>
                <div class="form-group mb-3">
                    <label>Ganti Password (Opsional)</label>
                    <input type="password" name="password" class="form-control" placeholder="Isi jika ingin mengganti password">
                </div>
                <div class="form-group mb-4">
                    <label>Foto Pengurus</label><br>
                    <?php if (!empty($pengurus->foto)) : ?>
                        <img src="<?= base_url('uploads/pengurus/' . $pengurus->foto) ?>" class="img-thumbnail mb-2" width="120">
                    <?php endif; ?>
                    <input type="file" name="foto" class="form-control-file">
                </div>
                <button type="submit" class="btn btn-primary w-100">Update</button>
            </form>
        </div>
    </div>
</div>

<!-- Pastikan SweetAlert2 sudah dimuat -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
