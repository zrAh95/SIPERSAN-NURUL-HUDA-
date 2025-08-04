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

}
