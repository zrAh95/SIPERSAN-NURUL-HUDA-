<!DOCTYPE html>
<html>
<head>
    <title>Laporan Log Keluar & Masuk Santri</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 40px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: center; }
        h3, p { text-align: center; margin: 0; }
        .footer { margin-top: 40px; font-size: 10px; text-align: right; }
    </style>
</head>
<body>

    <h3>LAPORAN LOG KELUAR & MASUK SANTRI</h3>
    <p>Pesantren Nurul Huda</p>
    <p>Periode: <?= $tanggal_mulai ?> sampai <?= $tanggal_selesai ?></p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Santri</th>
                <th>No Kartu</th>
                <th>Kamar</th>
                <th>Tingkat</th>
                <th>Wali Kamar</th>
                <th>Keperluan</th>
                <th>Mode</th>
                <th>Tgl Keluar</th>
                <th>Jam Keluar</th>
                <th>Tgl Masuk</th>
                <th>Jam Masuk</th>
                <th>Status</th>
                <th>Status Keterlambatan</th>
            </tr>
        </thead>
        <tbody>
            <?php $no=1; foreach ($log as $row): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $row->nama_santri ?></td>
                <td><?= $row->no_kartu ?></td>
                <td><?= $row->kamar ?></td>
                <td><?= $row->tingkat_sekolah ?></td>
                <td><?= $row->nama_walikamar ?></td>
                <td><?= $row->keperluan ?></td>
                <td><?= $row->mode ?></td>
                <td><?= $row->tanggal_izin ?></td>
                <td><?= $row->waktu_keluar ? date('H:i:s', strtotime($row->waktu_keluar)) : '-' ?></td>
                <td><?= $row->waktu_kembali ? date('Y-m-d', strtotime($row->waktu_kembali)) : '-' ?></td>
                <td><?= $row->waktu_kembali ? date('H:i:s', strtotime($row->waktu_kembali)) : '-' ?></td>
                <td><?= $row->status ?></td>
                <td><?= $row->status_keterlambatan ?></td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: <?= date('d-m-Y H:i') ?>
    </div>

</body>
</html>
