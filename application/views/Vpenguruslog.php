<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>

    <table id="dataTable" class="table table-bordered table-hover">
        <thead class="thead-dark">
            <tr>
                <th>No</th>
                <th>Nama Pengguna</th>
                <th>Nomor Telepon</th>
                <th>Last Login</th>
            </tr>
        </thead>
        <tbody>
            <?php $no=1; foreach ($pengurus as $p): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $p->nama_pengguna ?></td>
                <td><?= $p->no_telp ?></td>
                <td>
                    <?= $p->last_login ? date('d-m-Y H:i:s', strtotime($p->last_login)) : '<span class="text-muted">Belum pernah login</span>' ?>
                </td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function () {
        $('#dataTable').DataTable();
    });
</script>

