<?php
function get_nama_santri($no_kartu) {
    $CI =& get_instance(); // ambil instance CI
    $CI->load->database(); // pastikan database diload

    $query = $CI->db->get_where('tb_santri', ['no_kartu' => $no_kartu])->row();
    return $query ? $query->nama_santri : '-';
}
