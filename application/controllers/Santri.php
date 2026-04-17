<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Santri extends CI_Controller {

    public function __construct() {
        parent::__construct();
        cek_pengurus_login();

        $this->load->model('Santri_model');
        $this->load->model('Walikamar_model');

        date_default_timezone_set('Asia/Jakarta');     
        $this->db->query("SET time_zone = '+07:00'");  
    }

    public function index() {
        $data['title'] = 'Data Santri';

        $this->db->select('tb_santri.*, tb_kamar.kamar, tb_kamar.tingkat, tb_walikamar.nama_walikamar');
        $this->db->from('tb_santri');
        $this->db->join('tb_kamar', 'tb_santri.id_kamar = tb_kamar.id_kamar', 'left');
        $this->db->join('tb_walikamar', 'tb_kamar.id_walikamar = tb_walikamar.id_walikamar', 'left');
        $data['santri'] = $this->db->get()->result();

        $this->load->view('templates_admin/header', $data);
        $this->load->view('templates_admin/sidebar');
        $this->load->view('santri_index', $data);
        $this->load->view('templates_admin/footer');
    }

    public function tambah() {
        $data['title'] = 'Tambah Santri';
        $this->load->model('Mkamar');
        $data['kamar'] = $this->Mkamar->get_all();

        $this->load->view('templates_admin/header', $data);
        $this->load->view('templates_admin/sidebar');
        $this->load->view('santri_tambah', $data);
        $this->load->view('templates_admin/footer');
    }

    public function edit($no_kartu) {
        $data['title'] = 'Edit Santri';
        $data['santri'] = $this->Santri_model->get_by_id($no_kartu);
        $this->load->model('Mkamar');
        $data['kamar'] = $this->Mkamar->get_all();

        $this->load->view('templates_admin/header', $data);
        $this->load->view('templates_admin/sidebar');
        $this->load->view('santri_edit', $data);
        $this->load->view('templates_admin/footer');
    }

    public function perizinan() {
        $this->db->select('p.*, s.nama_santri, s.tingkat_sekolah, k.kamar, w.nama_walikamar, w.no_walikamar, p.status');
        $this->db->from('tb_perizinan p');
        $this->db->join('tb_santri s', 'p.no_kartu = s.no_kartu');
        $this->db->join('tb_kamar k', 's.id_kamar = k.id_kamar', 'left');
        $this->db->join('tb_walikamar w', 'k.id_walikamar = w.id_walikamar', 'left');
        $this->db->order_by('p.tanggal_izin', 'DESC');

        $data['title'] = 'Daftar Perizinan';
        $data['izin'] = $this->db->get()->result();

        $this->load->view('templates_admin/header', $data);
        $this->load->view('templates_admin/sidebar');
        $this->load->view('santri_izin', $data);
        $this->load->view('templates_admin/footer');
    }

    public function perizinan_keluar() {
        $this->db->select('p.*, s.nama_santri, s.tingkat_sekolah, k.kamar, w.nama_walikamar, w.no_walikamar, p.status');
        $this->db->from('tb_perizinan p');
        $this->db->join('tb_santri s', 'p.no_kartu = s.no_kartu');
        $this->db->join('tb_kamar k', 's.id_kamar = k.id_kamar');
        $this->db->join('tb_walikamar w', 'k.id_walikamar = w.id_walikamar');
        $this->db->where('p.mode', 'KELUAR');
        $this->db->order_by('p.tanggal_izin', 'DESC');

        $data['title'] = 'Perizinan Keluar';
        $data['izin']  = $this->db->get()->result();

        $this->load->view('templates_admin/header', $data);
        $this->load->view('templates_admin/sidebar');
        $this->load->view('santri_perizinan_keluar', $data);
        $this->load->view('templates_admin/footer');
    }

    public function perizinan_masuk() {
        $this->db->select('p.*, s.nama_santri, s.tingkat_sekolah, k.kamar, w.nama_walikamar, w.no_walikamar, p.status');
        $this->db->from('tb_perizinan p');
        $this->db->join('tb_santri s', 'p.no_kartu = s.no_kartu');
        $this->db->join('tb_kamar k', 's.id_kamar = k.id_kamar');
        $this->db->join('tb_walikamar w', 'k.id_walikamar = w.id_walikamar');
        $this->db->where('p.mode', 'MASUK');
        $this->db->order_by('p.tanggal_izin', 'DESC');

        $data['title'] = 'Perizinan Masuk';
        $data['izin']  = $this->db->get()->result();

        $this->load->view('templates_admin/header', $data);
        $this->load->view('templates_admin/sidebar');
        $this->load->view('santri_perizinan_masuk', $data);
        $this->load->view('templates_admin/footer');
    }

    public function log_keluar_masuk()
    {
        $data['log'] = $this->Santri_model->get_log_keluar_masuk();

        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/sidebar');
        $this->load->view('santri_log_keluar_masuk', $data);
        $this->load->view('templates_admin/footer');
    }

    public function setujui()
    {
        $id = $this->input->post('id');
    
        $this->db->select('p.*, s.nama_santri, s.id_kamar, s.tingkat_sekolah, k.kamar, w.nama_walikamar, w.no_walikamar, w.chat_id');
        $this->db->from('tb_perizinan p');
        $this->db->join('tb_santri s', 'p.no_kartu = s.no_kartu');
        $this->db->join('tb_kamar k', 's.id_kamar = k.id_kamar');
        $this->db->join('tb_walikamar w', 'k.id_walikamar = w.id_walikamar');
        $this->db->where('p.id_perizinan', $id);
        $izin = $this->db->get()->row();
    
        if (!$izin) { echo json_encode(['status'=>'error','message'=>'Data tidak ditemukan']); return; }
        if (in_array($izin->status, ['disetujui','selesai'])) { echo json_encode(['status'=>'already']); return; }
    
        $now = date('Y-m-d H:i:s');
    
        if ($izin->mode === 'KELUAR') {
            // setujui keluar => simpan jam keluar
            $this->db->update('tb_perizinan', [
                'status'       => 'disetujui',
                'waktu_keluar' => $now
            ], ['id_perizinan' => $id]);
    
            $pesan = "📢 *Santri Izin Keluar*\n".
                     "👤 {$izin->nama_santri}\n".
                     "🏠 Kamar: {$izin->kamar} ({$izin->tingkat_sekolah})\n".
                     "📝 Keperluan: {$izin->keperluan}\n".
                     "📆 Tanggal Izin: ".date('d-m-Y', strtotime($izin->tanggal_izin))."\n".
                     "🕒 Jam Keluar: ".date('H:i', strtotime($now))."\n".
                     "⏳ Kembali Sebelum: ".date('d-m-Y H:i', strtotime($izin->waktu_kembali));
    
            if (!empty($izin->chat_id)) $this->kirim_telegram($izin->chat_id, $pesan);
            if (!empty($izin->no_walikamar)) $this->_kirim_wa($izin->no_walikamar, $pesan);
    
        } else { 
            $this->db->update('tb_perizinan', [
                'status'      => 'selesai',
                'waktu_masuk' => $now
            ], ['id_perizinan' => $id]);
    

            $deadline = $this->db->select('waktu_kembali')
                ->where('no_kartu', $izin->no_kartu)
                ->where('mode', 'KELUAR')
                ->where('status', 'disetujui')
                ->order_by('id_perizinan', 'DESC')
                ->limit(1)
                ->get('tb_perizinan')
                ->row('waktu_kembali');
    
            $terlambat = '';
            if ($deadline && strtotime($now) > strtotime($deadline)) {
                $interval  = (new DateTime($deadline))->diff(new DateTime($now));
                $terlambat = $interval->format('%h jam %i menit');
            }
    
            $pesan = "📥 *Santri Telah Kembali*\n".
                     "👤 {$izin->nama_santri}\n".
                     ($terlambat ? "⚠️ *Terlambat* {$terlambat}\n" : "⏱️ *Tepat waktu*.\n").
                     "🕒 Jam Masuk: ".date('H:i', strtotime($now))." — ".date('d-m-Y');
    
            if (!empty($izin->chat_id)) $this->kirim_telegram($izin->chat_id, $pesan);
            if (!empty($izin->no_walikamar)) $this->_kirim_wa($izin->no_walikamar, $pesan);
        }
    
        echo json_encode(['status' => 'success']);
    }

    public function simpan_izin_keluar()
    {
        $this->output->set_content_type('application/json');
    
        $no_kartu    = trim($this->input->post('no_kartu', TRUE));
        $keperluan   = trim($this->input->post('keperluan', TRUE));
        $waktu_input = trim($this->input->post('waktu_kembali', TRUE)); // datetime-local
    
        if ($no_kartu === '' || $keperluan === '' || $waktu_input === '') {
            echo json_encode(['status' => 'error', 'message' => 'Data belum lengkap.']); return;
        }
    
        $cek = $this->db->get_where('tb_santri', ['no_kartu' => $no_kartu])->row();
        if (!$cek) { echo json_encode(['status' => 'error', 'message' => 'Santri tidak ditemukan.']); return; }
    
        $ts = strtotime(str_replace('T', ' ', $waktu_input));
        if ($ts === false) { echo json_encode(['status' => 'error', 'message' => 'Format tanggal tidak valid.']); return; }
        $deadline = date('Y-m-d H:i:s', $ts);
    
        $data = [
            'no_kartu'      => $no_kartu,
            'tanggal_izin'  => date('Y-m-d'),
            'keperluan'     => $keperluan,
            'waktu_kembali' => $deadline, // DEADLINE
            'status'        => 'pending',
            'mode'          => 'KELUAR',
            'waktu_keluar'  => null
        ];
    
        if (!$this->db->insert('tb_perizinan', $data)) {
            $err = $this->db->error();
            echo json_encode(['status' => 'error', 'message' => ($err['message'] ?? 'DB error')]); return;
        }
    
        echo json_encode(['status' => 'success']);
    }

    public function simpan_izin_masuk() {
        $no_kartu  = $this->input->post('no_kartu');
        $keperluan = $this->input->post('keperluan');

        if (!$no_kartu) {
            $this->session->set_flashdata('error', '❌ UID belum terbaca. Tap kartu dulu!');
            redirect('santri/perizinan_masuk');
        }
        $cek = $this->db->get_where('tb_santri', ['no_kartu'=>$no_kartu])->row();
        if (!$cek) {
            $this->session->set_flashdata('error', '❌ UID tidak dikenali. Santri tidak ditemukan.');
            redirect('santri/perizinan_masuk');
        }

        $data = [
            'no_kartu'      => $no_kartu,
            'tanggal_izin'  => date('Y-m-d'),
            'keperluan'     => $keperluan,
            'status'        => 'pending',
            'mode'          => 'MASUK',
            'waktu_keluar'  => null,
            'waktu_kembali' => null,
            'waktu_masuk'   => null
        ];
        $this->db->insert('tb_perizinan', $data);

        @file_put_contents(FCPATH.'uid_masuk.txt', '');

        $this->session->set_flashdata('success', '✅ Izin masuk berhasil ditambahkan dan menunggu persetujuan.');
        redirect('santri/perizinan_masuk');
    }

    public function hapus_izin($id) {
        $izin = $this->db->get_where('tb_perizinan', ['id_perizinan'=>$id])->row();
        if (!$izin) {
            $this->session->set_flashdata('error', 'Data perizinan tidak ditemukan.');
            redirect('santri'); return;
        }
        $mode = $izin->mode;
        $this->db->where('id_perizinan',$id)->delete('tb_perizinan');
        $this->session->set_flashdata('success','Data perizinan berhasil dihapus.');
        redirect($mode === 'KELUAR' ? 'santri/perizinan_keluar' : 'santri/perizinan_masuk');
    }

    public function reset_uid_keluar() { @file_put_contents(FCPATH.'uid_keluar.txt',''); echo "UID keluar dikosongkan"; }
    public function reset_uid_masuk()  { @file_put_contents(FCPATH.'uid_masuk.txt','');  echo "UID masuk dikosongkan";  }

 
    private function kirim_telegram($chat_id, $pesan) {
        $token = 'ISI_TOKEN_TELEGRAM_MU';
        $url   = "https://api.telegram.org/bot{$token}/sendMessage";

        $data = ['chat_id'=>$chat_id, 'text'=>$pesan, 'parse_mode'=>'Markdown'];
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        curl_close($ch);
    }

    private function _kirim_wa($no_hp, $pesan) {
        $token = 'V8A1HMFMFefeEPe7Zp7r';
        $url   = 'https://api.fonnte.com/send';
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => [
                'target' => $no_hp,
                'message' => $pesan,
                'countryCode' => '62'
            ],
            CURLOPT_HTTPHEADER => ["Authorization: $token"],
        ]);
        curl_exec($curl);
        curl_close($curl);
    }
    
    public function simpan()
    {
        
        $no_kartu       = $this->input->post('no_kartu', true);
        $nama_santri    = $this->input->post('nama_santri', true);
        $tempat_lahir   = $this->input->post('tempat_lahir', true);
        $tanggal_lahir  = $this->input->post('tanggal_lahir', true);
        $alamat         = $this->input->post('alamat', true);
        $id_kamar       = $this->input->post('id_kamar', true);
        $tingkat        = $this->input->post('tingkat_sekolah', true);
    
       
        if (!$no_kartu || !$nama_santri || !$id_kamar || !$tingkat) {
            $this->session->set_flashdata('error', 'Lengkapi data wajib (UID, nama, kamar, tingkat).');
            redirect('santri/tambah'); return;
        }
    
       
        if ($this->Santri_model->cek_no_kartu($no_kartu)) {
            $this->session->set_flashdata('error', 'No Kartu sudah terdaftar.');
            redirect('santri/tambah'); return;
        }
    
        
        $wali = $this->db->select('id_walikamar')->from('tb_kamar')->where('id_kamar', $id_kamar)->get()->row();
        $id_walikamar = $wali ? $wali->id_walikamar : null;
    
       
        $foto = '';
        if (!empty($_FILES['foto']['name'])) {
            $config = [
                'upload_path'   => FCPATH.'uploads/foto_santri/',
                'allowed_types' => 'jpg|jpeg|png',
                'max_size'      => 2048,
                'file_name'     => time().'_'.preg_replace('/\s+/', '_', $_FILES['foto']['name'])
            ];
            $this->load->library('upload', $config);
            if ($this->upload->do_upload('foto')) {
                $foto = $this->upload->data('file_name');
            }
        }
    
       
        $data = [
            'no_kartu'        => $no_kartu,
            'nama_santri'     => $nama_santri,
            'tempat_lahir'    => $tempat_lahir,
            'tanggal_lahir'   => $tanggal_lahir,
            'alamat'          => $alamat,
            'id_kamar'        => $id_kamar,
            'tingkat_sekolah' => $tingkat,
            'id_walikamar'    => $id_walikamar, 
            'foto'            => $foto
        ];
        $this->Santri_model->insert($data);
    
      
        @file_put_contents(FCPATH.'uid_daftar.txt','');
    
        $this->session->set_flashdata('success', 'Santri berhasil ditambahkan.');
        redirect('santri');
    }


    public function update($no_kartu)
    {
        
        $lama = $this->Santri_model->get_by_id($no_kartu);
        if (!$lama) {
            $this->session->set_flashdata('error', 'Data santri tidak ditemukan.');
            redirect('santri'); return;
        }
    
        $nama_santri    = $this->input->post('nama_santri', true);
        $tempat_lahir   = $this->input->post('tempat_lahir', true);
        $tanggal_lahir  = $this->input->post('tanggal_lahir', true);
        $alamat         = $this->input->post('alamat', true);
        $id_kamar       = $this->input->post('id_kamar', true);
        $tingkat        = $this->input->post('tingkat_sekolah', true);
    
        if (!$nama_santri || !$id_kamar || !$tingkat) {
            $this->session->set_flashdata('error', 'Lengkapi data wajib (nama, kamar, tingkat).');
            redirect('santri/edit/'.$no_kartu); return;
        }
    
   
        $wali = $this->db->select('id_walikamar')->from('tb_kamar')->where('id_kamar', $id_kamar)->get()->row();
        $id_walikamar = $wali ? $wali->id_walikamar : null;
    
    
        $foto = $lama->foto;
        if (!empty($_FILES['foto']['name'])) {
            $config = [
                'upload_path'   => FCPATH.'uploads/foto_santri/',
                'allowed_types' => 'jpg|jpeg|png',
                'max_size'      => 2048,
                'file_name'     => time().'_'.preg_replace('/\s+/', '_', $_FILES['foto']['name'])
            ];
            $this->load->library('upload', $config);
            if ($this->upload->do_upload('foto')) {
                $foto = $this->upload->data('file_name');
            }
        }
    
        $data = [
            'nama_santri'     => $nama_santri,
            'tempat_lahir'    => $tempat_lahir,
            'tanggal_lahir'   => $tanggal_lahir,
            'alamat'          => $alamat,
            'id_kamar'        => $id_kamar,
            'tingkat_sekolah' => $tingkat,
            'id_walikamar'    => $id_walikamar,
            'foto'            => $foto
        ];
        $this->Santri_model->update($no_kartu, $data);
    
        $this->session->set_flashdata('success', 'Data santri berhasil diperbarui.');
        redirect('santri');
    }

    public function hapus($no_kartu)
    {
        // buat hapus file foto lama
        $row = $this->Santri_model->get_by_id($no_kartu);
        if ($row && !empty($row->foto)) {
            $path = FCPATH.'uploads/foto_santri/'.$row->foto;
            if (is_file($path)) @unlink($path);
        }
    
        $this->Santri_model->delete($no_kartu);
        $this->session->set_flashdata('success', 'Data santri berhasil dihapus.');
        redirect('santri');
    }

}
