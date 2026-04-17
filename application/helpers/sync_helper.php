<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function sync_walikamar_foto($id_walikamar, $foto_nama)
{
    $CI =& get_instance();

    // Koneksi ke database wali kamar
    $db2 = $CI->load->database([
        'hostname' => 'localhost',
        'username' => 'root',
        'password' => '',
        'database' => 'db_sipersan_walikamar',
        'dbdriver' => 'mysqli'
    ], TRUE);

    // Update / insert sinkronisasi otomatis
    $cek = $db2->get_where('tb_walikamar', ['id_walikamar' => $id_walikamar])->row();
    if ($cek) {
        $db2->where('id_walikamar', $id_walikamar);
        $db2->update('tb_walikamar', ['foto_walikamar' => $foto_nama]);
    } else {
        $db2->insert('tb_walikamar', [
            'id_walikamar' => $id_walikamar,
            'foto_walikamar' => $foto_nama
        ]);
    }

    log_message('info', 'Sinkronisasi foto wali kamar ID: ' . $id_walikamar . ' -> ' . $foto_nama);
}
