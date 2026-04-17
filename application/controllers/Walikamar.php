<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Walikamar extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Mwalikamar');
        $this->load->library('upload');
        $this->load->helper('url');
    }

    public function index() {
        $data['title'] = 'Data Wali Kamar';
        $data['walikamar'] = $this->Mwalikamar->get_all();
        $this->load->view('templates_admin/header', $data);
        $this->load->view('templates_admin/sidebar');
        $this->load->view('walikamar_index', $data);
        $this->load->view('templates_admin/footer');
    }

    public function tambah() {
        $passwordPlain = $this->input->post('password');
        $username = trim((string) $this->input->post('username'));
        $noWaliKamar = trim((string) $this->input->post('no_walikamar'));

        if ($username === '') {
            $username = $noWaliKamar;
        }

        $config['upload_path']   = './uploads/walikamar/';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size']      = 2048;
        $config['file_name']     = 'walikamar_' . time();

        $this->upload->initialize($config);
        $foto = 'no_image.png';

        if (!empty($_FILES['foto_walikamar']['name'])) {
            if ($this->upload->do_upload('foto_walikamar')) {
                $fotoData = $this->upload->data();
                $foto = $fotoData['file_name'];
            }
        }

        $data = [
            'nama_walikamar' => $this->input->post('nama_walikamar'),
            'no_walikamar'   => $noWaliKamar,
            'username'       => $username,
            'password'       => password_hash($passwordPlain, PASSWORD_DEFAULT),
            'foto_walikamar' => $foto,
            'status_kirim'   => 'pending'
        ];

        $this->db->insert('tb_walikamar', $data);
        $this->session->set_flashdata('success', '✅ Akun Wali Kamar berhasil dibuat!');
        redirect('walikamar');
    }

    public function edit($id) {
        $nama       = $this->input->post('nama_walikamar');
        $no_wa      = trim((string) $this->input->post('no_walikamar'));
        $username   = trim((string) $this->input->post('username'));
        $password   = $this->input->post('password');

        if ($username === '') {
            $username = $no_wa;
        }

        $config['upload_path']   = './uploads/walikamar/';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size']      = 2048;
        $config['file_name']     = 'walikamar_' . time();

        $this->upload->initialize($config);
        $foto = null;

        if (!empty($_FILES['foto_walikamar']['name'])) {
            if ($this->upload->do_upload('foto_walikamar')) {
                $fotoData = $this->upload->data();
                $foto = $fotoData['file_name'];

                // hapus foto lama
                $oldData = $this->Mwalikamar->get_by_id($id);
                if ($oldData && $oldData->foto_walikamar != 'no_image.png') {
                    $oldPath = './uploads/walikamar/' . $oldData->foto_walikamar;
                    if (file_exists($oldPath)) unlink($oldPath);
                }
            }
        }

        $data = [
            'nama_walikamar' => $nama,
            'no_walikamar'   => $no_wa,
            'username'       => $username
        ];

        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        if ($foto) {
            $data['foto_walikamar'] = $foto;
        }

        $this->Mwalikamar->update($id, $data);
        $this->session->set_flashdata('success', '✅ Data Wali Kamar berhasil diperbarui.');
        redirect('walikamar');
    }

    public function hapus($id) {
        $data = $this->Mwalikamar->get_by_id($id);
        if ($data && $data->foto_walikamar != 'no_image.png') {
            $path = './uploads/walikamar/' . $data->foto_walikamar;
            if (file_exists($path)) unlink($path);
        }

        $this->Mwalikamar->delete($id);
        $this->session->set_flashdata('success', '🗑️ Wali Kamar berhasil dihapus.');
        redirect('walikamar');
    }
}
