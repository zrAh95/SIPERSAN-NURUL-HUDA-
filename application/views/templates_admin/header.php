<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SIPERSAN NURUL HUDA</title>
    <link rel="icon" type="image/png" href="<?= base_url('assets/img/NURULHUDALOGOR.png') ?>">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Font Awesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

    <!-- Custom fonts & styles -->
    <link href="<?= base_url('assets/vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
    <link href="<?= base_url('assets/css/sb-admin-2.min.css') ?>" rel="stylesheet">

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

    <!-- PDFMake -->
    <script src="<?= base_url('assets/vendor/datatables/pdfmake.min.js') ?>"></script>
    <script src="<?= base_url('assets/vendor/datatables/vfs_fonts.js') ?>"></script>

    <!-- Gaya warna tema -->
    <style>
        .bg-gradient-primary {
            background-color: #73946B!important;
            background-image: none !important;
        }

        .sidebar {
            background-color: #73946B !important;
        }

        .btn-primary {
            background-color: #73946B !important;
            border-color: #73946B !important;
        }

        .text-primary {
            color: #73946B !important;
        }

        .border-left-primary {
            border-left: 0.25rem solid #73946B !important;
        }

        .progress-bar.bg-primary {
            background-color: #73946B!important;
        }
    </style>

    <!-- Styling khusus untuk hasil cetak/print -->
    <style>
        @media print {
            body {
                font-family: Arial, sans-serif;
                font-size: 12px;
                color: #000;
            }

            table.dataTable {
                width: 100% !important;
                border-collapse: collapse !important;
            }

            table.dataTable th,
            table.dataTable td {
                border: 1px solid #000 !important;
                padding: 6px 8px !important;
                text-align: center !important;
            }

            .dataTables_wrapper .dt-buttons {
                display: none;
            }

            .btn,
            .sidebar,
            .navbar,
            .footer,
            .pagination,
            .topbar,
            .dataTables_filter,
            .dataTables_length,
            .dataTables_info {
                display: none !important;
            }

            h1.h3 {
                text-align: center;
                font-size: 18px;
                margin-bottom: 20px;
            }
        }
    </style>
</head>
