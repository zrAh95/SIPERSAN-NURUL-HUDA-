<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>

    <?php if ($this->session->flashdata('error')) : ?>
        <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('success')) : ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
    <?php endif; ?>

    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalIzinMasuk">+ Tambah Izin Masuk</button>

    <table class="table table-bordered table-hover" id="datatable">
        <thead class="thead-dark text-center">
        <tr>
            <th>No</th>
            <th>Nama Santri</th>
            <th>Tanggal Izin</th>
            <th>Keperluan</th>
            <th>Wali Kamar</th>
            <th>No WA Wali</th>
            <th>Tingkat Sekolah</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
        </thead>
        <tbody>
        <?php $no = 1; foreach ($izin as $row): $st = strtolower($row->status); ?>
            <tr class="text-center">
                <td><?= $no++ ?></td>
                <td><?= $row->nama_santri ?></td>
                <td><?= $row->tanggal_izin ?></td>
                <td><?= $row->keperluan ?></td>
                <td><?= $row->nama_walikamar ?></td>
                <td><?= $row->no_walikamar ?></td>
                <td><?= $row->tingkat_sekolah ?></td>
                <td>
                    <?php
                    if ($st === 'pending')       echo '<span class="badge badge-warning">Pending</span>';
                    elseif ($st === 'selesai')   echo '<span class="badge badge-info">Selesai</span>';
                    elseif ($st === 'disetujui') echo '<span class="badge badge-success">Disetujui</span>';
                    else                         echo '<span class="badge badge-secondary">-</span>';
                    ?>
                </td>
                <td>
                    <?php if ($st === 'pending'): ?>
                        <button class="btn btn-sm btn-success btn-setujui" data-id="<?= $row->id_perizinan ?>">Setujui</button>
                    <?php endif; ?>
                    <a href="<?= base_url('santri/hapus_izin/' . $row->id_perizinan) ?>"
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- MODAL TAMBAH IZIN MASUK -->
<div class="modal fade" id="modalIzinMasuk" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form id="formIzinMasuk" action="<?= base_url('santri/simpan_izin_masuk') ?>" method="post">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">Form Tambah Izin Masuk</h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body text-center">
                    <div class="d-flex justify-content-center my-3">
                        <img id="fotoSantriMasuk" src="" alt="Foto Santri" width="120"
                             class="rounded shadow-sm" style="display:none;object-fit:cover;">
                    </div>

                    <div class="alert alert-info" id="notif_masuk" style="display:none;"></div>
                    <img src="<?= base_url('assets/img/tap-rifid.gif') ?>" width="150"/>
                    <div class="text-success mb-3">🟢 MODE MASUK: Santri mengajukan izin masuk pondok.</div>

                    <input type="hidden" name="no_kartu" id="uid_izin_masuk">

                    <div class="form-group text-left">
                        <label><strong>Keperluan</strong></label>
                        <textarea name="keperluan" class="form-control" required
                                  placeholder="Contoh: kembali dari rumah, selesai berobat, dsb."></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-info btn-block">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // ===== Poll UID (masuk)
    let lastUIDMasuk = "", uidValidMasuk = false;

    setInterval(() => {
        fetch("<?= base_url('uid_masuk.txt') ?>")
            .then(r => r.text())
            .then(uid => {
                uid = uid.trim();
                if (!uid || uid === lastUIDMasuk) return;
                lastUIDMasuk = uid;

                const input = document.getElementById('uid_izin_masuk');
                const notif = document.getElementById('notif_masuk');
                const img   = document.getElementById('fotoSantriMasuk');

                input.value = uid;

                fetch("<?= base_url('api/get_info_izin_masuk') ?>")
                    .then(r => r.json())
                    .then(data => {
                        if (data.status === 'success') {
                            uidValidMasuk = true;
                            notif.className = "alert alert-success";
                            notif.innerHTML = `✅ <strong>${data.nama}</strong><br>📚 ${data.tingkat}<br>🏠 ${data.kamar}`;
                            if (data.foto) {
                                img.src = "<?= base_url('uploads/foto_santri/') ?>" + data.foto;
                                img.style.display = "block";
                            } else img.style.display = "none";
                        } else {
                            uidValidMasuk = false;
                            notif.className = "alert alert-warning";
                            notif.textContent = "⚠️ UID tidak dikenali.";
                            img.style.display = "none";
                        }
                        notif.style.display = 'block';
                    });

                $('#modalIzinMasuk').modal('show');
            });
    }, 1000);

    // ===== Submit validasi
    document.getElementById('formIzinMasuk').addEventListener('submit', function (e) {
        const uid = document.getElementById('uid_izin_masuk').value.trim();
        if (!uid || !uidValidMasuk) {
            e.preventDefault();
            alert("❌ UID belum dikenali. Tap kartu terlebih dahulu.");
        } else {
            fetch("<?= base_url('api/reset_uid_masuk') ?>");
        }
    });

    // ===== Setujui
    document.querySelectorAll('.btn-setujui').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            fetch('<?= base_url('santri/setujui') ?>', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'id=' + encodeURIComponent(id)
            })
                .then(r => r.json())
                .then(d => {
                    if (d.status === 'success') location.reload();
                    else if (d.status === 'already') alert('Izin ini sudah disetujui/selesai.');
                    else alert('❌ Gagal menyetujui: ' + (d.message || 'Unknown error'));
                })
                .catch(e => alert('❌ Gagal menyetujui: ' + e));
        });
    });

    // Datatable
    $(document).ready(function () {
        $('#datatable').DataTable({
            dom: 'Bfrtip',
            buttons: ['copy', 'excel', 'pdf', 'print'],
            pageLength: 10,
            language: { url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json" }
        });
    });
</script>
