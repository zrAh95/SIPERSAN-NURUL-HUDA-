<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mpengurus extends CI_Model {

    public function get_all() {
        return $this->db->get('tb_pengurus')->result(); // <– penting: result()
    }

    public function insert($data) {
        return $this->db->insert('tb_pengurus', $data);
    }

    // tambahkan jika perlu nanti:
    public function get_by_id($id) {
        return $this->db->get_where('tb_pengurus', ['id_pengurus' => $id])->row();
    }

    public function update($id, $data) {
        $this->db->where('id_pengurus', $id);
        return $this->db->update('tb_pengurus', $data);
    }

    public function delete($id) {
        $this->db->where('id_pengurus', $id);
        return $this->db->delete('tb_pengurus');
    }

    public function login($username, $password) {
        $pengurus = $this->db->get_where('tb_pengurus', ['nama_pengguna' => $username])->row();
        return ($pengurus && password_verify($password, $pengurus->password)) ? $pengurus : false;
    }

}
