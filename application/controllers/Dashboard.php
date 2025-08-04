<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        cek_pengurus_login(); // ✅ Cek login di sini
    }

    public function index()
    {
        $data['title'] = "Dashboard";
        $data['total_santri'] = $this->db->count_all('tb_santri');
        $data['total_kamar'] = $this->db->count_all('tb_kamar');
        $data['izin_keluar_hari_ini'] = $this->db
            ->where('mode', 'KELUAR')
            ->where('DATE(tanggal_izin)', date('Y-m-d'))
            ->count_all_results('tb_perizinan');
        $data['izin_belum_disetujui'] = $this->db
            ->where('status', 'pending')
            ->count_all_results('tb_perizinan');

        $this->load->view('templates_admin/header', $data);
        $this->load->view('templates_admin/sidebar');
        $this->load->view('admin/dashboard', $data);
        $this->load->view('templates_admin/footer');
    }
}
?>
