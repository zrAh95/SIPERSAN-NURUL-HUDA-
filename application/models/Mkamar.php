<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mkamar extends CI_Model {
    public function get_all() {
        $this->db->select('k.*, w.nama_walikamar');
        $this->db->from('tb_kamar k');
        $this->db->join('tb_walikamar w', 'k.id_walikamar = w.id_walikamar', 'left');
        return $this->db->get()->result();
    }

    public function insert($data) {
        return $this->db->insert('tb_kamar', $data);
    }

    public function update($id, $data) {
        return $this->db->where('id_kamar', $id)->update('tb_kamar', $data);
    }

    public function delete($id) {
        return $this->db->where('id_kamar', $id)->delete('tb_kamar');
    }

    public function get_all_with_wali() {
        $this->db->select('tb_kamar.*, tb_walikamar.nama_walikamar');
        $this->db->from('tb_kamar');
        $this->db->join('tb_walikamar', 'tb_kamar.id_walikamar = tb_walikamar.id_walikamar', 'left');
        return $this->db->get()->result();
    }

}
