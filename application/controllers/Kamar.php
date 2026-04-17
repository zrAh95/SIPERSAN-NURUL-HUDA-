<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kamar extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Mkamar');
        $this->load->model('Mwalikamar');
    }

    public function index() {
        $data['title'] = 'Data Kamar';
        $data['kamar'] = $this->Mkamar->get_all();
        $data['walikamar'] = $this->Mwalikamar->get_all();
        
        $this->load->view('templates_admin/header', $data);
        $this->load->view('templates_admin/sidebar');
        $this->load->view('kamar_index', $data);
        $this->load->view('templates_admin/footer');
    }

    public function tambah() {
        $data = [
            'kamar' => $this->input->post('kamar'),
            'tingkat' => $this->input->post('tingkat'),
            'id_walikamar' => $this->input->post('id_walikamar')
        ];
        $this->Mkamar->insert($data);
        $this->session->set_flashdata('success', '✅ Kamar berhasil ditambahkan.');
        redirect('kamar');
    }

    public function edit($id) {
        $data = [
            'kamar' => $this->input->post('kamar'),
            'tingkat' => $this->input->post('tingkat'),
            'id_walikamar' => $this->input->post('id_walikamar')
        ];
        $this->Mkamar->update($id, $data);
        $this->session->set_flashdata('success', '✅ Data kamar berhasil diperbarui.');
        redirect('kamar');
    }

    public function hapus($id) {
        $this->Mkamar->delete($id);
        $this->session->set_flashdata('success', '🗑️ Kamar berhasil dihapus.');
        redirect('kamar');
    }
}
