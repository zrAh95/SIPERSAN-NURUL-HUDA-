<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>

    <?php if ($this->session->flashdata('success')) : ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')) : ?>
        <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
    <?php endif; ?>

    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalIzinKeluar">+ Tambah Izin Keluar</button>

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
            <th>Deadline Kembali</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
        </thead>
        <tbody>
        <?php $no = 1; foreach ($izin as $row): ?>
            <tr class="text-center" id="izin-<?= $row->id_perizinan ?>">
                <td><?= $no++ ?></td>
                <td><?= $row->nama_santri ?></td>
                <td><?= $row->tanggal_izin ?></td>
                <td><?= $row->keperluan ?></td>
                <td><?= $row->nama_walikamar ?></td>
                <td><?= $row->no_walikamar ?></td>
                <td><?= $row->tingkat_sekolah ?></td>
                <td><?= $row->waktu_kembali ? date('d-m-Y H:i', strtotime($row->waktu_kembali)) : '-' ?></td>
                <td>
                    <?php
                    $st = strtolower($row->status);
                    if ($st === 'pending')       echo '<span class="badge badge-warning">Pending</span>';
                    elseif ($st === 'disetujui') echo '<span class="badge badge-success">Disetujui</span>';
                    elseif ($st === 'selesai')   echo '<span class="badge badge-info">Selesai</span>';
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

<!-- MODAL TAMBAH IZIN KELUAR -->
<div class="modal fade" id="modalIzinKeluar" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form id="formIzinKeluar">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Form Tambah Izin Keluar</h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>

                <div class="modal-body text-center">
                    <div class="d-flex justify-content-center mb-2">
                        <img id="fotoSantriKeluar" src="" alt="Foto Santri" class="rounded shadow-sm"
                             width="130" style="display:none;object-fit:cover;">
                    </div>

                    <div class="alert alert-info text-left" id="notif_keluar" style="display:none"></div>
                    <div class="text-danger mb-2">🔴 MODE KELUAR: Santri mengajukan izin keluar pondok.</div>

                    <img src="<?= base_url('assets/img/tap-rifid.gif') ?>" width="150" class="mb-3"/>

                    <input type="hidden" name="no_kartu" id="uid_izin_keluar">

                    <div class="form-group text-left">
                        <label><strong>Wajib Kembali Sebelum</strong></label>
                        <input type="datetime-local" name="waktu_kembali" class="form-control" required>
                    </div>

                    <div class="form-group text-left">
                        <label><strong>Keperluan</strong></label>
                        <textarea name="keperluan" class="form-control" required></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger btn-block">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // ===== Poll UID (keluar)
    let lastUIDKeluar = "", uidValidKeluar = false;

    setInterval(() => {
        fetch("<?= base_url('uid_keluar.txt') ?>")
            .then(r => r.text())
            .then(uid => {
                uid = uid.trim();
                if (!uid || uid === lastUIDKeluar) return;
                lastUIDKeluar = uid;

                const input = document.getElementById('uid_izin_keluar');
                const notif = document.getElementById('notif_keluar');
                const foto  = document.getElementById('fotoSantriKeluar');

                input.value = uid;

                fetch("<?= base_url('api/get_info_izin_keluar') ?>")
                    .then(r => r.json())
                    .then(data => {
                        if (data.status === 'success') {
                            uidValidKeluar = true;
                            notif.className = "alert alert-success";
                            notif.innerHTML = `✅ <strong>${data.nama}</strong><br>📚 ${data.tingkat}<br>🏠 ${data.kamar}`;
                            if (data.foto) {
                                foto.src = "<?= base_url('uploads/foto_santri/') ?>" + data.foto;
                                foto.style.display = "block";
                            } else foto.style.display = "none";
                        } else {
                            uidValidKeluar = false;
                            notif.className = "alert alert-warning";
                            notif.textContent = "⚠️ UID tidak dikenali.";
                            foto.style.display = "none";
                        }
                        notif.style.display = 'block';
                    });

                $('#modalIzinKeluar').modal('show');
            });
    }, 1000);

    // ===== Submit Keluar (AJAX)
    document.getElementById('formIzinKeluar').addEventListener('submit', function (e) {
        e.preventDefault();
        const uid = document.getElementById('uid_izin_keluar').value.trim();
        if (!uid || !uidValidKeluar) {
            alert("❌ UID tidak valid. Tap kartu terlebih dahulu.");
            return;
        }

        const fd = new FormData(this);
        fetch("<?= base_url('santri/simpan_izin_keluar') ?>", { method: "POST", body: fd })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    fetch("<?= base_url('api/reset_uid_keluar') ?>");
                    $('#modalIzinKeluar').modal('hide');
                    location.reload();
                } else {
                    alert('❌ Gagal menyimpan: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(err => alert('❌ Gagal menyimpan: ' + err));
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
