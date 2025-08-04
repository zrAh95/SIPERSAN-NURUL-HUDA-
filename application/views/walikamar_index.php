<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>

    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalTambah">+ Tambah Wali Kamar</button>

    <table class="table table-bordered table-hover" id="datatable">
        <thead class="thead-dark">
            <tr>
                <th>No</th>
                <th>Nama Wali</th>
                <th>No WA</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach ($walikamar as $w): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $w->nama_walikamar ?></td>
                <td><?= $w->no_walikamar ?></td>
                <td>
                    <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#modalEdit<?= $w->id_walikamar ?>">Edit</button>
                    <a href="#" class="btn btn-sm btn-danger btn-hapus" data-url="<?= base_url('walikamar/hapus/'.$w->id_walikamar) ?>">Hapus</a>
                </td>
            </tr>

            <!-- Modal Edit -->
            <div class="modal fade" id="modalEdit<?= $w->id_walikamar ?>">
                <div class="modal-dialog">
                    <form method="post" action="<?= base_url('walikamar/edit/'.$w->id_walikamar) ?>">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Wali Kamar</h5>
                                <button class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Nama Wali</label>
                                    <input type="text" name="nama_walikamar" class="form-control" value="<?= $w->nama_walikamar ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>No WA</label>
                                    <input type="text" name="no_walikamar" class="form-control" value="<?= $w->no_walikamar ?>" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-success">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <?php endforeach ?>
        </tbody>
    </table>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah">
    <div class="modal-dialog">
        <form method="post" action="<?= base_url('walikamar/tambah') ?>">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Wali Kamar</h5>
                    <button class="close" data-dismiss="modal">&times;</button>
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

    // Notifikasi sukses
    <?php if ($this->session->flashdata('success')): ?>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: '<?= $this->session->flashdata("success") ?>'
    });
    <?php endif ?>

    // SweetAlert hapus
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
