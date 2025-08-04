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
                    <?php $no = 1; foreach ($log as $row): ?>
                        <tr class="text-center">
                            <td><?= $no++ ?></td>
                            <td><?= $row->nama_santri ?></td>
                            <td><?= $row->no_kartu ?></td>
                            <td><?= $row->kamar ?></td>
                            <td><?= $row->tingkat_sekolah ?></td>
                            <td><?= $row->nama_walikamar ?></td>
                            <td><?= $row->keperluan ?></td>
                            <td>
                                <?php if ($row->mode === 'KELUAR'): ?>
                                    <span class="badge badge-danger">KELUAR</span>
                                <?php elseif ($row->mode === 'MASUK'): ?>
                                    <span class="badge badge-success">MASUK</span>
                                <?php endif; ?>
                            </td>
                            <td><?= $row->waktu_keluar ? date('Y-m-d', strtotime($row->waktu_keluar)) : '-' ?></td>
                            <td><?= $row->waktu_keluar ? date('H:i:s', strtotime($row->waktu_keluar)) : '-' ?></td>
                            <td><?= $row->waktu_kembali ? date('Y-m-d', strtotime($row->waktu_kembali)) : '-' ?></td>
                            <td><?= $row->waktu_kembali ? date('H:i:s', strtotime($row->waktu_kembali)) : '-' ?></td>
                            <td>
                                <?php
                                $status = strtolower(trim($row->status));
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
                            <td>
                                <?php
                                    if ($row->mode == 'MASUK' && $row->waktu_kembali) {
                                        if (strtotime($row->waktu_kembali) > strtotime($row->waktu_keluar)) {
                                            echo '<span class="badge badge-warning text-dark">⚠️ Terlambat</span>';
                                        } else {
                                            echo '<span class="badge badge-success">⏱️ Tepat waktu</span>';
                                        }
                                    } else {
                                        echo '<span class="badge badge-secondary">Belum ditentukan</span>';
                                    }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

    <!-- PDFMake untuk export PDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>

<!-- SCRIPT DATATABLE EXPORT -->
<!-- SCRIPT DATATABLE EXPORT -->
<script>
    const tanggalCetak = new Date().toLocaleDateString('id-ID');

    $(document).ready(function () {
        $('#datatable').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'excel',
                {
                    extend: 'pdfHtml5',
                    text: 'PDF',
                    title: 'Laporan Perizinan Keluar dan Masuk Santri',
                    orientation: 'landscape',
                    pageSize: 'A4',
                    customize: function (doc) {
                        doc.content.splice(0, 0, {
                            text: 'SIPERSAN NURUL HUDA\n\n',
                            alignment: 'center',
                            margin: [0, 0, 0, 12],
                            fontSize: 14,
                            bold: true
                        });

                        doc.footer = function(currentPage, pageCount) {
                            return {
                                text: 'Laporan ini dicetak pada tanggal: ' + tanggalCetak,
                                alignment: 'right',
                                margin: [0, 10, 10, 0],
                                fontSize: 9
                            };
                        };
                    }
                },
                {
                    extend: 'print',
                    text: 'Print',
                    title: '',
                    customize: function (win) {
                        $(win.document.body).css('font-size', '10pt').prepend(
                            `<div style="text-align: center; margin-bottom: 20px;">
                                <h2 style="margin: 0;">LAPORAN PERIZINAN KELUAR DAN MASUK SANTRI</h2>
                                <p style="margin: 0;">SIPERSAN NURUL HUDA</p>
                                <hr>
                            </div>`
                        );

                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css({
                                'width': '100%',
                                'border-collapse': 'collapse',
                                'font-size': '10pt'
                            });

                        $(win.document.body).find('table th, table td')
                            .css({
                                'border': '1px solid #000',
                                'padding': '5px'
                            });

                        $(win.document.body).append(
                            `<div style="margin-top: 30px; text-align: right;">
                                <p style="font-size: 10pt;"><em>Laporan ini dicetak pada tanggal: ${tanggalCetak}</em></p>
                            </div>`
                        );
                    }
                }
            ],
            pageLength: 10,
            language: {
                url: "" // bisa dikosongkan jika CORS error
            }
        });
    });
</script>

