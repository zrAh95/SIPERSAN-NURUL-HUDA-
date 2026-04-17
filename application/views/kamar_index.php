<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>

    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalTambah">+ Tambah Kamar</button>

    <table class="table table-bordered table-hover" id="datatable">
        <thead class="thead-dark">
            <tr>
                <th>No</th>
                <th>Nama Kamar</th>
                <th>Tingkat</th>
                <th>Wali Kamar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no=1; foreach ($kamar as $k): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $k->kamar ?></td>
                <td><?= $k->tingkat ?></td>
                <td><?= $k->nama_walikamar ?></td>
                <td>
                    <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#modalEdit<?= $k->id_kamar ?>">Edit</button>
                    <a href="#" class="btn btn-sm btn-danger btn-hapus" data-url="<?= base_url('kamar/hapus/'.$k->id_kamar) ?>">Hapus</a>
                </td>
            </tr>

            <!-- Modal Edit -->
            <div class="modal fade" id="modalEdit<?= $k->id_kamar ?>">
                <div class="modal-dialog">
                    <form method="post" action="<?= base_url('kamar/edit/'.$k->id_kamar) ?>">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Kamar</h5>
                                <button class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Nama Kamar</label>
                                    <input type="text" name="kamar" value="<?= $k->kamar ?>" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Tingkat</label>
                                    <select name="tingkat" class="form-control" required>
                                        <option value="MTs" <?= $k->tingkat == 'MTs' ? 'selected' : '' ?>>MTs</option>
                                        <option value="MA" <?= $k->tingkat == 'MA' ? 'selected' : '' ?>>MA</option>
                                        <option value="SMK" <?= $k->tingkat == 'SMK' ? 'selected' : '' ?>>SMK</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Wali Kamar</label>
                                    <select name="id_walikamar" class="form-control" required>
                                        <?php foreach ($walikamar as $w): ?>
                                            <option value="<?= $w->id_walikamar ?>" <?= $w->id_walikamar == $k->id_walikamar ? 'selected' : '' ?>>
                                                <?= $w->nama_walikamar ?>
                                            </option>
                                        <?php endforeach ?>
                                    </select>
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
        <form method="post" action="<?= base_url('kamar/tambah') ?>">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kamar</h5>
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Kamar</label>
                        <input type="text" name="kamar" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Tingkat</label>
                        <select name="tingkat" class="form-control" required>
                            <option value="MTs">MTs</option>
                            <option value="MA">MA</option>
                            <option value="SMK">SMK</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Wali Kamar</label>
                        <select name="id_walikamar" class="form-control" required>
                            <?php foreach ($walikamar as $w): ?>
                                <option value="<?= $w->id_walikamar ?>"><?= $w->nama_walikamar ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Script DataTable + SweetAlert -->
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

    // SweetAlert Hapus
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
