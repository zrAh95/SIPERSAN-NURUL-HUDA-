<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Log Keluar & Masuk Santri</h1>

    <div class="card">
        <div class="card-header">
            Riwayat Keluar dan Masuk Santri
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover" id="datatable">
                <thead class="thead-dark text-center">
                    <tr>
                        <th>No</th>
                        <th>Nama Santri</th>
                        <th>No Kartu</th>
                        <th>Kamar</th>
                        <th>Tingkat</th>
                        <th>Wali Kamar</th>
                        <th>Keperluan</th>
                        <th>Mode</th>
                        <th>Tanggal Keluar</th>
                        <th>Jam Keluar</th>
                        <th>Tanggal Masuk</th>
                        <th>Jam Masuk</th>
                        <th>Status</th>
                        <th>Status Keterlambatan</th>
                    </tr>
                </thead>
                <tbody>
                <?php $no=1; foreach ($log as $row): ?>
                    <tr class="text-center">
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row->nama_santri ?? '-') ?></td>
                        <td><?= htmlspecialchars($row->no_kartu ?? '-') ?></td>
                        <td><?= htmlspecialchars($row->kamar ?? '-') ?></td>
                        <td><?= htmlspecialchars($row->tingkat_sekolah ?? '-') ?></td>
                        <td><?= htmlspecialchars($row->nama_walikamar ?? '-') ?></td>
                        <td><?= htmlspecialchars($row->keperluan ?? '-') ?></td>

                        <td>
                            <?php if (isset($row->mode) && $row->mode === 'KELUAR'): ?>
                                <span class="badge badge-danger">KELUAR</span>
                            <?php elseif (isset($row->mode) && $row->mode === 'MASUK'): ?>
                                <span class="badge badge-success">MASUK</span>
                            <?php else: ?>
                                <span class="badge badge-secondary">-</span>
                            <?php endif; ?>
                        </td>

                        <!-- Tanggal & Jam Keluar -->
                        <td><?= !empty($row->waktu_keluar) ? date('Y-m-d', strtotime($row->waktu_keluar)) : '-' ?></td>
                        <td><?= !empty($row->waktu_keluar) ? date('H:i:s', strtotime($row->waktu_keluar)) : '-' ?></td>

                        <!-- Tanggal & Jam Masuk (pakai waktu_kembali dari DB) -->
                        <td><?= !empty($row->waktu_kembali) ? date('Y-m-d', strtotime($row->waktu_kembali)) : '-' ?></td>
                        <td><?= !empty($row->waktu_kembali) ? date('H:i:s', strtotime($row->waktu_kembali)) : '-' ?></td>

                        <!-- Status -->
                        <td>
                            <?php
                                $status = strtolower(trim($row->status ?? ''));
                                if ($status === 'pending') {
                                    echo '<span class="badge badge-warning">Pending</span>';
                                } elseif ($status === 'disetujui') {
                                    echo '<span class="badge badge-success">Disetujui</span>';
                                } elseif ($status === 'selesai') {
                                    echo '<span class="badge badge-info">Selesai</span>';
                                } else {
                                    echo '<span class="badge badge-secondary">-</span>';
                                }
                            ?>
                        </td>

                        <!-- Keterlambatan -->
                        <td>
                            <?php
                            if (($row->mode ?? '') === 'MASUK' && !empty($row->waktu_kembali)) {
                                $deadline = $row->deadline_kembali ?? null;
                                if ($deadline) {
                                    if (strtotime($row->waktu_kembali) > strtotime($deadline)) {
                                        echo '<span class="badge badge-warning text-dark">⚠️ Terlambat</span>';
                                    } else {
                                        echo '<span class="badge badge-success">⏱️ Tepat waktu</span>';
                                    }
                                } else {
                                    echo '<span class="badge badge-secondary">Belum ditentukan</span>';
                                }
                            } else {
                                echo '<span class="badge badge-secondary">Belum ditentukan</span>';
                            }
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- PDFMake (opsional) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>

<script>
$(function () {
  $('#datatable').DataTable({
    dom: 'Bfrtip',
    buttons: [
      'copy', 'excel',
      { extend: 'pdfHtml5', text: 'PDF', title: 'Laporan Perizinan Keluar dan Masuk Santri', orientation: 'landscape', pageSize: 'A4' },
      { extend: 'print', text: 'Print', title: '' }
    ],
    pageLength: 10
  });
});
</script>
