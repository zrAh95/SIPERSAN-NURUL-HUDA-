<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Data Pengurus</h1>

    <a href="<?= base_url('pengurus/tambah') ?>" class="btn btn-primary mb-3">
        + Tambah Pengurus
    </a>

    <table class="table table-bordered" id="datatable">
        <thead class="thead-dark">
            <tr>
                <th>No</th>
                <th>Foto</th>
                <th>Nama</th>
                <th>No Telepon</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach ($pengurus as $p): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td>
                        <?php if (!empty($p->foto)): ?>
                            <img src="<?= base_url('uploads/pengurus/' . $p->foto) ?>" width="100" class="img-thumbnail">
                        <?php else: ?>
                            <span class="text-muted">-</span>
                        <?php endif; ?>
                    </td>
                    <td><?= $p->nama_pengguna ?></td>
                    <td><?= $p->no_telp ?></td>
                    <td>
                        <a href="<?= base_url('pengurus/edit/' . $p->id_pengurus) ?>" class="btn btn-warning btn-sm">Edit</a>
                        <button class="btn btn-danger btn-sm btn-hapus" data-id="<?= $p->id_pengurus ?>">Hapus</button>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>

<!-- JS SweetAlert & DataTables -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function () {
        $('#datatable').DataTable();

        // Flash success
        <?php if ($this->session->flashdata('success')): ?>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '<?= $this->session->flashdata("success") ?>',
            timer: 2000,
            showConfirmButton: false
        });
        <?php endif; ?>

        // Konfirmasi hapus
        document.querySelectorAll('.btn-hapus').forEach(button => {
            button.addEventListener('click', function () {
                const id = this.dataset.id;
                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: "Data tidak bisa dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, hapus'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "<?= base_url('pengurus/hapus/') ?>" + id;
                    }
                });
            });
        });
    });
</script>
