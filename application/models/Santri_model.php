<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Santri_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // JOIN tb_santri + tb_walikamar
    public function get_all() {
        $this->db->select('tb_santri.*, tb_walikamar.nama_walikamar');
        $this->db->from('tb_santri');
        $this->db->join('tb_walikamar', 'tb_walikamar.id_walikamar = tb_santri.id_walikamar', 'left');
        return $this->db->get()->result();
    }

    public function insert($data) {
        return $this->db->insert('tb_santri', $data);
    }
    public function get_by_id($no_kartu) {
    $this->db->select('tb_santri.*, tb_walikamar.nama_walikamar');
    $this->db->from('tb_santri');
    $this->db->join('tb_walikamar', 'tb_walikamar.id_walikamar = tb_santri.id_walikamar', 'left');
    $this->db->where('tb_santri.no_kartu', $no_kartu);
    return $this->db->get()->row();
}

    public function update($no_kartu, $data) {
    $this->db->where('no_kartu', $no_kartu);
    return $this->db->update('tb_santri', $data);
}

    public function delete($no_kartu) {
    return $this->db->delete('tb_santri', ['no_kartu' => $no_kartu]);
}

    public function cek_no_kartu($no_kartu) {
    return $this->db->get_where('tb_santri', ['no_kartu' => $no_kartu])->row();
}
public function get_log_keluar_masuk() {
    $this->db->select("
        p.id_perizinan,
        s.nama_santri,
        s.no_kartu,
        s.tingkat_sekolah,
        k.kamar,
        w.nama_walikamar,
        p.keperluan,
        p.mode,
        p.tanggal_izin,
        p.waktu_keluar,
        p.waktu_kembali AS waktu_masuk,
        p.status,
        (
            SELECT pk.waktu_kembali 
            FROM tb_perizinan pk
            WHERE pk.no_kartu = p.no_kartu
              AND pk.mode = 'KELUAR'
              AND pk.status = 'disetujui'
            ORDER BY pk.id_perizinan DESC
            LIMIT 1
        ) AS deadline_kembali
    ", FALSE);

    $this->db->from('tb_perizinan p');
    $this->db->join('tb_santri s', 'p.no_kartu = s.no_kartu');
    $this->db->join('tb_kamar k', 's.id_kamar = k.id_kamar', 'left');
    $this->db->join('tb_walikamar w', 's.id_walikamar = w.id_walikamar', 'left');
    $this->db->order_by('p.id_perizinan', 'DESC');

    return $this->db->get()->result();
}

}
