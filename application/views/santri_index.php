<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>

    <a href="<?= base_url('santri/tambah') ?>" class="btn btn-primary mb-3">
        + Tambah Santri
    </a>

    <table class="table table-bordered table-hover" id="datatable">
        <thead class="thead-dark">
            <tr>
                <th>No</th>
                <th>No Kartu</th>
                <th>Foto</th>
                <th>Nama</th>
                <th>Tempat Lahir</th>
                <th>Tanggal Lahir</th>
                <th>Alamat</th>
                <th>Kamar</th>
                <th>Tingkat Sekolah</th>
                <th>Wali Kamar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach ($santri as $row): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $row->no_kartu ?></td>
                <td>
                    <?php if ($row->foto): ?>
                        <img src="<?= base_url('uploads/foto_santri/' . $row->foto) ?>" width="50">
                    <?php else: ?>
                        Tidak Ada
                    <?php endif; ?>
                </td>
                <td><?= $row->nama_santri ?></td>
                <td><?= $row->tempat_lahir ?></td>
                <td><?= $row->tanggal_lahir ?></td>
                <td><?= $row->alamat ?></td>
                <td><?= $row->kamar ?></td>
                <td><?= $row->tingkat_sekolah ?></td>
                <td><?= $row->nama_walikamar ?></td>
                <td>
                    <a href="<?= base_url('santri/edit/' . $row->no_kartu) ?>" class="btn btn-warning btn-sm">Edit</a>
                    <button class="btn btn-danger btn-sm btn-hapus" data-id="<?= $row->no_kartu ?>">Hapus</button>
                </td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    $('#datatable').DataTable({
        dom: 'Bfrtip',
        buttons: ['copy', 'excel', 'pdf', 'print'],
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
        }
    });

    <?php if ($this->session->flashdata('success')): ?>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '<?= $this->session->flashdata("success") ?>',
        showConfirmButton: false,
        timer: 2500
    });
    <?php endif ?>

    $('.btn-hapus').click(function() {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Yakin ingin menghapus santri ini?',
            text: "Data tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "<?= base_url('santri/hapus/') ?>" + id;
            }
        });
    });
});
</script>
