<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function index()
    {
        // Ambil total data utama
        $data['total_santri'] = $this->db->count_all('tb_santri');
        $data['total_kamar'] = $this->db->count_all('tb_kamar');
        $data['total_walikamar'] = $this->db->count_all('tb_walikamar');
        $data['total_pengurus'] = $this->db->count_all('tb_pengurus');

        // Hitung izin keluar hari ini
        $this->db->where('DATE(waktu_keluar)', date('Y-m-d'));
        $data['izin_keluar_hari_ini'] = $this->db->count_all_results('tb_perizinan');

        // Hitung izin masuk hari ini (optional)
        $this->db->where('DATE(waktu_kembali)', date('Y-m-d'));
        $data['izin_masuk_hari_ini'] = $this->db->count_all_results('tb_perizinan');

        // Hitung izin disetujui
        $this->db->where('status', 'disetujui');
        $data['izin_disetujui'] = $this->db->count_all_results('tb_perizinan');

        // Hitung izin belum disetujui
        $this->db->where('status', 'pending');
        $data['izin_belum_disetujui'] = $this->db->count_all_results('tb_perizinan');

        // Hitung pengurus yang online (last_login 5 menit terakhir)
        $this->db->where('last_login >=', date('Y-m-d H:i:s', strtotime('-5 minutes')));
        $data['pengurus_online'] = $this->db->count_all_results('tb_pengurus');

        // Load view
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/sidebar');
        $this->load->view('admin/dashboard', $data);
        $this->load->view('templates_admin/footer');
    }
}
