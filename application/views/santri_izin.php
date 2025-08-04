<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>

    <div class="card shadow">
        <div class="card-body">
            <table class="table table-bordered table-hover" id="datatable">
                <thead class="thead-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama Santri</th>
                        <th>Tanggal Izin</th>
                        <th>Mode</th>
                        <th>Keperluan</th>
                        <th>Kamar</th>
                        <th>Tingkat Sekolah</th>
                        <th>Wali Kamar</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($izin as $row): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $row->nama_santri ?></td>
                            <td><?= $row->tanggal_izin ?></td>
                            <td><span class="badge badge-<?= $row->mode == 'MASUK' ? 'info' : 'warning' ?>">
                                <?= $row->mode ?>
                            </span></td>
                            <td><?= $row->keperluan ?? '-' ?></td>
                            <td><?= $row->kamar ?? '-' ?></td>
                            <td><?= $row->tingkat_sekolah ?></td>
                            <td><?= $row->nama_walikamar ?? '-' ?></td>
                            <td><span class="badge badge-<?= 
                                $row->status == 'pending' ? 'secondary' :
                                ($row->status == 'disetujui' ? 'success' : 'dark') ?>">
                                <?= $row->status ?>
                            </span></td>
                            <td>
                                <a href="<?= base_url('santri/hapus_izin/'.$row->id_perizinan) ?>" 
                                    onclick="return confirm('Yakin ingin menghapus data ini?')" 
                                    class="btn btn-sm btn-danger">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#datatable').DataTable();
    });
</script>
