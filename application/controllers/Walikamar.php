<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Walikamar extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Mwalikamar');
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
        $data = [
            'nama_walikamar' => $this->input->post('nama_walikamar'),
            'no_walikamar'   => $this->input->post('no_walikamar')
        ];
        $this->Mwalikamar->insert($data);
        $this->session->set_flashdata('success', '✅ Wali kamar berhasil ditambahkan.');
        redirect('walikamar');
    }

    public function edit($id) {
        $data = [
            'nama_walikamar' => $this->input->post('nama_walikamar'),
            'no_walikamar'   => $this->input->post('no_walikamar')
        ];
        $this->Mwalikamar->update($id, $data);
        $this->session->set_flashdata('success', '✅ Wali kamar berhasil diperbarui.');
        redirect('walikamar');
    }

    public function hapus($id) {
        $this->Mwalikamar->delete($id);
        $this->session->set_flashdata('success', '🗑️ Wali kamar berhasil dihapus.');
        redirect('walikamar');
    }
}
