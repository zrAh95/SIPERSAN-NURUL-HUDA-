<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mwalikamar extends CI_Model {
    public function get_all() {
        return $this->db->get('tb_walikamar')->result();
    }

    public function insert($data) {
        return $this->db->insert('tb_walikamar', $data);
    }

    public function update($id, $data) {
        return $this->db->where('id_walikamar', $id)->update('tb_walikamar', $data);
    }

    public function delete($id) {
        return $this->db->where('id_walikamar', $id)->delete('tb_walikamar');
    }
}
