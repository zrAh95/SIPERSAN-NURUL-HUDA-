<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>

    <!-- Tombol Tambah -->
    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalTambah">
        + Tambah Wali Kamar
    </button>

    <!-- Notifikasi Sukses -->
    <?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= $this->session->flashdata('success') ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php endif; ?>

    <!-- Tabel Data -->
    <table class="table table-bordered table-hover" id="datatable">
        <thead class="thead-dark text-center">
            <tr>
                <th>Foto</th>
                <th>Nama Wali</th>
                <th>No WA</th>
                <th>Username</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($walikamar as $w): ?>
            <?php $foto_walikamar = !empty($w->foto_walikamar) ? FCPATH . 'uploads/walikamar/' . $w->foto_walikamar : null; ?>
            <tr class="text-center align-middle">
                <td>
                    <?php if (!empty($w->foto_walikamar) && is_file($foto_walikamar)): ?>
                        <img src="<?= base_url('uploads/walikamar/' . $w->foto_walikamar) ?>"
                             alt="Foto <?= html_escape($w->nama_walikamar ?? 'Wali Kamar') ?>"
                             class="img-thumbnail foto-walikamar">
                    <?php else: ?>
                        <span class="text-muted">-</span>
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($w->nama_walikamar ?? '-') ?></td>
                <td><?= htmlspecialchars($w->no_walikamar ?? '-') ?></td>
                <td><?= htmlspecialchars($w->username ?? '-') ?></td>
                <td>
                    <button class="btn btn-sm btn-info" data-toggle="modal"
                        data-target="#modalEdit<?= $w->id_walikamar ?>">Edit</button>
                    <a href="#" class="btn btn-sm btn-danger btn-hapus"
                        data-url="<?= base_url('walikamar/hapus/'.$w->id_walikamar) ?>">Hapus</a>
                </td>
            </tr>

            <!-- Modal Edit -->
            <div class="modal fade" id="modalEdit<?= $w->id_walikamar ?>" tabindex="-1">
                <div class="modal-dialog">
                    <form method="post" enctype="multipart/form-data"
                          action="<?= base_url('walikamar/edit/'.$w->id_walikamar) ?>">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Wali Kamar</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>

                            <div class="modal-body">
                                <div class="text-center mb-3">
                                    <?php if (!empty($w->foto_walikamar) && is_file($foto_walikamar)): ?>
                                        <img src="<?= base_url('uploads/walikamar/' . $w->foto_walikamar) ?>"
                                             alt="Foto <?= html_escape($w->nama_walikamar ?? 'Wali Kamar') ?>"
                                             class="rounded-circle shadow"
                                             style="width: 100px; height: 100px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle border text-muted"
                                             style="width: 100px; height: 100px;">
                                            Tidak ada foto
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="form-group">
                                    <label>Nama Wali</label>
                                    <input type="text" name="nama_walikamar" class="form-control"
                                        value="<?= html_escape($w->nama_walikamar ?? '') ?>" required>
                                </div>

                                <div class="form-group">
                                    <label>No WA</label>
                                    <input type="text" name="no_walikamar" class="form-control"
                                        value="<?= html_escape($w->no_walikamar ?? '') ?>" required>
                                </div>

                                <div class="form-group">
                                    <label>Username</label>
                                    <input type="text" name="username" class="form-control"
                                        value="<?= html_escape($w->username ?? '') ?>" required>
                                </div>

                                <div class="form-group">
                                    <label>Password (biarkan kosong jika tidak diubah)</label>
                                    <input type="password" name="password" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label>Foto Wali Kamar (kosongkan jika tidak diubah)</label>
                                    <input type="file" name="foto_walikamar" class="form-control">
                                    <small class="text-muted">Foto saat ini: <?= html_escape($w->foto_walikamar ?? '-') ?></small>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-success">Simpan Perubahan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <form method="post" enctype="multipart/form-data" action="<?= base_url('walikamar/tambah') ?>">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Wali Kamar</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Wali</label>
                        <input type="text" name="nama_walikamar" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>No WA</label>
                        <input type="text" name="no_walikamar" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                        <small class="text-muted">Password akan otomatis di-hash saat disimpan.</small>
                    </div>

                    <div class="form-group">
                        <label>Foto Wali Kamar</label>
                        <input type="file" name="foto_walikamar" class="form-control">
                        <small class="text-muted">Opsional, format: JPG / PNG (maks. 2MB).</small>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Script DataTables + SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    $('#datatable').DataTable();

    // SweetAlert flashdata
    <?php if ($this->session->flashdata('success')): ?>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: '<?= $this->session->flashdata("success") ?>'
    });
    <?php endif; ?>

    // SweetAlert konfirmasi hapus
    $('.btn-hapus').on('click', function(e){
        e.preventDefault();
        const href = $(this).data('url');

        Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: "Data ini akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = href;
            }
        });
    });
});
</script>

<style>
    .foto-walikamar {
        width: 60px;
        height: 60px;
        object-fit: cover;
    }
</style>
