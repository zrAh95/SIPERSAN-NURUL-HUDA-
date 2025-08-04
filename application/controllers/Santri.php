<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Santri extends CI_Controller {

    public function __construct() {
        parent::__construct();
        cek_pengurus_login();
        $this->load->model('Santri_model');
        $this->load->model('Walikamar_model');
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

    public function masuk() {
    $this->db->select('p.*, s.nama_santri, s.id_kamar, k.kamar, k.tingkat, w.nama_walikamar, w.no_walikamar');
    $this->db->from('tb_perizinan p');
    $this->db->join('tb_santri s', 'p.no_kartu = s.no_kartu');
    $this->db->join('tb_kamar k', 's.id_kamar = k.id_kamar');
    $this->db->join('tb_walikamar w', 'k.id_walikamar = w.id_walikamar');
    $this->db->where('p.status', 'disetujui');
    $data['izin_masuk'] = $this->db->get()->result();

    foreach ($data['izin_masuk'] as $izin) {
        // Logika masuk (bisa disesuaikan triggernya, misal: tombol setujui masuk)

        // Validasi keterlambatan
        $waktu_kembali = new DateTime($izin->waktu_kembali);
        $waktu_sekarang = new DateTime();

        $telat = '';
        if ($waktu_sekarang > $waktu_kembali) {
            $interval = $waktu_kembali->diff($waktu_sekarang);
            $telat = $interval->format('%h jam %i menit');
        }

        // Kirim pesan WhatsApp
        $pesan = "Santri a/n {$izin->nama_santri} telah kembali ke pondok.";
        if ($telat) {
            $pesan .= "\n⚠️ Terlambat kembali: {$telat}";
        }

        $this->kirim_wa($izin->no_walikamar, $pesan);

        // Update status jadi selesai + isi waktu masuk
        $this->db->where('id_perizinan', $izin->id_perizinan);
        $this->db->update('tb_perizinan', [
            'status' => 'selesai',
            'waktu_masuk' => $waktu_sekarang->format('Y-m-d H:i:s')
        ]);
    }

        // Load view (kalau ada)
        $data['title'] = 'Perizinan Masuk';
        $this->load->view('templates_admin/header', $data);
        $this->load->view('templates_admin/sidebar');
        $this->load->view('izin_masuk', $data); // pastikan view ini ada
        $this->load->view('templates_admin/footer');
    }


    public function keluar() {
        $this->db->select('p.*, s.nama_santri, s.kamar, s.tingkat_sekolah, w.no_walikamar, w.nama_walikamar');
        $this->db->from('tb_perizinan p');
        $this->db->join('tb_santri s', 'p.no_kartu = s.no_kartu');
        $this->db->join('tb_walikamar w', 's.id_walikamar = w.id_walikamar');
        $this->db->where('p.mode', 'KELUAR');
        // Hapus filter berdasarkan hari ini agar semua data tampil
        $this->db->order_by('p.waktu_keluar', 'DESC');
        $data['log'] = $this->db->get()->result();

        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/sidebar');
        $this->load->view('santri_log_keluar_masuk', $data);
        $this->load->view('templates_admin/footer');
    }

    public function perizinan()
    {
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
        $this->load->view('santri_izin', $data); // view ini harus ada
        $this->load->view('templates_admin/footer');
    }

    /// controller untuk menu perizinan 

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

        if (!$izin) {
            echo json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan']);
            return;
        }

        if (in_array($izin->status, ['disetujui', 'selesai'])) {
            echo json_encode(['status' => 'already']);
            return;
        }

        $now = date('Y-m-d H:i:s');

        if ($izin->mode === 'KELUAR') {
            $this->db->update('tb_perizinan', [
                'status' => 'disetujui',
                'waktu_keluar' => $now
            ], ['id_perizinan' => $id]);

            $pesan = "📢 *Santri Izin Keluar*\n";
            $pesan .= "👤 {$izin->nama_santri}\n";
            $pesan .= "🏠 Kamar: {$izin->kamar} ({$izin->tingkat_sekolah})\n";
            $pesan .= "📝 Keperluan: {$izin->keperluan}\n";
            $pesan .= "📆 Tanggal Izin: " . date('d-m-Y', strtotime($izin->tanggal_izin)) . "\n";
            $pesan .= "🕒 Jam Keluar: " . date('H:i', strtotime($now)) . "\n";
            $pesan .= "⏳ Kembali Sebelum: " . date('d-m-Y H:i', strtotime($izin->waktu_kembali));

            if (!empty($izin->chat_id)) {
                $this->kirim_telegram($izin->chat_id, $pesan);
            }
            if (!empty($izin->no_walikamar)) {
                $this->_kirim_wa($izin->no_walikamar, $pesan);
            }

        } elseif ($izin->mode === 'MASUK') {
            $this->db->update('tb_perizinan', [
                'status' => 'selesai',
                'waktu_kembali' => $now
            ], ['id_perizinan' => $id]);

            $terlambat = '';
            if (!empty($izin->waktu_kembali) && strtotime($now) > strtotime($izin->waktu_kembali)) {
                $interval = (new DateTime($izin->waktu_kembali))->diff(new DateTime($now));
                $terlambat = $interval->format('%h jam %i menit');
            }

            $pesan = "📥 *Santri Telah Kembali*\n";
            $pesan .= "👤 {$izin->nama_santri}\n";
            $pesan .= $terlambat
                ? "⚠️ *Terlambat kembali* selama *{$terlambat}*\n"
                : "⏱️ *Tepat waktu*.\n";
            $pesan .= "🕒 Jam Masuk: " . date('H:i', strtotime($now)) . " — " . date('d-m-Y');

            if (!empty($izin->chat_id)) {
                $this->kirim_telegram($izin->chat_id, $pesan);
            }
            if (!empty($izin->no_walikamar)) {
                $this->_kirim_wa($izin->no_walikamar, $pesan);
            }
        }

        echo json_encode(['status' => 'success']);
    }


    public function update($no_kartu) {
        $this->load->library('upload');
    
        $config['upload_path']   = './uploads/foto_santri/';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size']      = 2048;
        $this->upload->initialize($config);

        $foto = $this->Santri_model->get_by_id($no_kartu)->foto;

        if ($this->upload->do_upload('foto')) {
            // Jika berhasil upload foto baru, replace
            $foto_data = $this->upload->data();
            $foto = $foto_data['file_name'];
        }

        $data = [
                'nama_santri' => $this->input->post('nama_santri'),
                'tempat_lahir' => $this->input->post('tempat_lahir'),
                'tanggal_lahir' => $this->input->post('tanggal_lahir'),
                'alamat' => $this->input->post('alamat'),
                'id_kamar' => $this->input->post('id_kamar'),
                'tingkat_sekolah' => $this->input->post('tingkat_sekolah'),
                'id_walikamar' => $this->input->post('id_walikamar'),
                'foto'            => $foto 
            ];
        $this->Santri_model->update($no_kartu, $data);
        $this->session->set_flashdata('success', 'Data santri berhasil diperbarui.');
        redirect('santri');
    }

    public function hapus($no_kartu) {
        $this->Santri_model->delete($no_kartu);
        redirect('santri');
    }
    // untuk simpan data kartu santri
    public function simpan()
    {
        $no_kartu = $this->input->post('no_kartu');

        if ($this->Santri_model->cek_no_kartu($no_kartu)) {
            $this->session->set_flashdata('error', 'No Kartu sudah terdaftar!');
            redirect('santri/tambah');
        }

        // UPLOAD FOTO
        $foto = '';
        if ($_FILES['foto']['name']) {
            $config['upload_path'] = './uploads/foto_santri/';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['file_name'] = time() . '_' . $_FILES['foto']['name'];
            $this->load->library('upload', $config);
            if ($this->upload->do_upload('foto')) {
                $foto = $this->upload->data('file_name');
            }
        }

        $data = [
            'no_kartu' => $no_kartu,
            'nama_santri' => $this->input->post('nama_santri'),
            'tempat_lahir' => $this->input->post('tempat_lahir'),
            'tanggal_lahir' => $this->input->post('tanggal_lahir'),
            'alamat' => $this->input->post('alamat'),
            'id_kamar' => $this->input->post('id_kamar'),
            'tingkat_sekolah' => $this->input->post('tingkat_sekolah'),
            'id_walikamar' => $this->input->post('id_walikamar'),
            'foto' => $foto
        ];

        $this->Santri_model->insert($data);
        file_put_contents(FCPATH . 'uid_daftar.txt', '');
        $this->session->set_flashdata('success', 'Santri berhasil ditambahkan.');
        redirect('santri');
    }


    /// untuk dibagian perizinan 

    public function hapus_izin($id)
    {
        $izin = $this->db->get_where('tb_perizinan', ['id_perizinan' => $id])->row();

        if (!$izin) {
            $this->session->set_flashdata('error', 'Data perizinan tidak ditemukan.');
            redirect('santri'); // fallback
            return;
        }

        $mode = $izin->mode;

        $this->db->where('id_perizinan', $id)->delete('tb_perizinan');
        $this->session->set_flashdata('success', 'Data perizinan berhasil dihapus.');

        if ($mode === 'KELUAR') {
            redirect('santri/perizinan_keluar');
        } else {
            redirect('santri/perizinan_masuk');
        }
    }

    // untuk bagian perizinan dengan modal notifikasi dan simpan perizinan
    public function simpan_izin()
    {
        $mode = strtoupper($this->input->post('mode'));
        $no_kartu = $this->input->post('no_kartu');

        $data = [
            'no_kartu'       => $no_kartu,
            'tanggal_izin'   => date('Y-m-d'),
            'keperluan'      => $this->input->post('keperluan'),
            'status'         => 'pending',
            'mode'           => $mode,
            'waktu_keluar'   => null,
            'waktu_kembali'  => null
        ];

        $this->db->insert('tb_perizinan', $data);
        $this->session->set_flashdata('success', 'Izin berhasil ditambahkan (pending).');
        redirect('santri/perizinan');
    }

    public function proses_masuk()
    {
        $uid = file_get_contents(FCPATH . 'uid_masuk.txt');

        if (!$uid) {
            echo json_encode(['status' => 'error', 'message' => 'UID tidak terbaca']);
            return;
        }

        $izin = $this->db->where('no_kartu', $uid)
                        ->where('status', 'disetujui')
                        ->order_by('id_perizinan', 'desc')
                        ->get('tb_perizinan')
                        ->row();

        if (!$izin) {
            echo json_encode(['status' => 'error', 'message' => 'Tidak ditemukan izin disetujui']);
            return;
        }

        // Ambil data wali kamar santri
        $this->db->select('s.nama_santri, k.kamar, s.tingkat_sekolah, w.nama_walikamar, w.no_walikamar, w.chat_id');
        $this->db->from('tb_santri s');
        $this->db->join('tb_kamar k', 's.id_kamar = k.id_kamar');
        $this->db->join('tb_walikamar w', 'k.id_walikamar = w.id_walikamar');
        $this->db->where('s.no_kartu', $izin->no_kartu);
        $santri = $this->db->get()->row();

        $terlambat = '';
        if (strtotime(date('Y-m-d H:i:s')) > strtotime($izin->waktu_kembali)) {
            $interval = (new DateTime($izin->waktu_kembali))->diff(new DateTime());
            $terlambat = $interval->format('%h jam %i menit');
        }

        $pesan = "📥 *Santri Telah Kembali*\n";
        $pesan .= "👤 {$santri->nama_santri}\n";
        $pesan .= $terlambat
            ? "⚠️ *Terlambat kembali* selama *{$terlambat}*\n"
            : "⏱️ *Tepat waktu*.\n";
        $pesan .= "🕒 Jam Masuk: " . date('H:i') . " — " . date('d-m-Y');

        if (!empty($santri->chat_id)) {
            $this->kirim_telegram($santri->chat_id, $pesan);
        }
        if (!empty($santri->no_walikamar)) {
            $this->_kirim_wa($santri->no_walikamar, $pesan);
        }

        $this->db->where('id_perizinan', $izin->id_perizinan)->update('tb_perizinan', [
            'status' => 'selesai',
            'waktu_kembali' => date('Y-m-d H:i:s'),
            'mode' => 'MASUK'
        ]);

        file_put_contents(FCPATH . 'uid_masuk.txt', '');

        echo json_encode(['status' => 'success', 'message' => 'Santri berhasil masuk kembali']);
    }


    public function reset_uid_keluar()
    {
        file_put_contents(FCPATH . 'uid_keluar.txt', '');
        echo "UID keluar dikosongkan";
    }

    public function reset_uid_masuk()
    {
        file_put_contents(FCPATH . 'uid_masuk.txt', '');
        echo "UID masuk dikosongkan";
    }

    public function perizinan_keluar()
    {
        $this->db->select('p.*, s.nama_santri, s.tingkat_sekolah, k.kamar, w.nama_walikamar, w.no_walikamar, p.status');
        $this->db->from('tb_perizinan p');
        $this->db->join('tb_santri s', 'p.no_kartu = s.no_kartu');
        $this->db->join('tb_kamar k', 's.id_kamar = k.id_kamar');
        $this->db->join('tb_walikamar w', 'k.id_walikamar = w.id_walikamar');
        $this->db->where('p.mode', 'KELUAR');
        $this->db->order_by('p.tanggal_izin', 'DESC');
        $data['title'] = 'Perizinan Keluar';
        $data['izin'] = $this->db->get()->result();

        $this->load->view('templates_admin/header', $data);
        $this->load->view('templates_admin/sidebar');
        $this->load->view('santri_perizinan_keluar', $data);
        $this->load->view('templates_admin/footer');
    }

    public function perizinan_masuk()
    {
        $this->db->select('p.*, s.nama_santri, s.tingkat_sekolah, k.kamar, w.nama_walikamar, w.no_walikamar, p.status');
        $this->db->from('tb_perizinan p');
        $this->db->join('tb_santri s', 'p.no_kartu = s.no_kartu');
        $this->db->join('tb_kamar k', 's.id_kamar = k.id_kamar');
        $this->db->join('tb_walikamar w', 'k.id_walikamar = w.id_walikamar');
        $this->db->where('p.mode', 'MASUK');
        $this->db->order_by('p.tanggal_izin', 'DESC');
        $data['title'] = 'Perizinan Masuk';
        $data['izin'] = $this->db->get()->result();

        $this->load->view('templates_admin/header', $data);
        $this->load->view('templates_admin/sidebar');
        $this->load->view('santri_perizinan_masuk', $data);
        $this->load->view('templates_admin/footer');
    }

    public function log_keluar_masuk()
    {
        $this->db->select('p.*, s.nama_santri, s.no_kartu, s.tingkat_sekolah, k.kamar, w.nama_walikamar, w.no_walikamar');
        $this->db->from('tb_perizinan p');
        $this->db->join('tb_santri s', 'p.no_kartu = s.no_kartu');
        $this->db->join('tb_kamar k', 's.id_kamar = k.id_kamar');
        $this->db->join('tb_walikamar w', 'k.id_walikamar = w.id_walikamar');
        $this->db->order_by('p.id_perizinan', 'DESC'); // ✅ lebih akurat

        $data['log'] = $this->db->get()->result();

        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/sidebar');
        $this->load->view('santri_log_keluar_masuk', $data);
        $this->load->view('templates_admin/footer');
    }

    public function simpan_izin_keluar()
    {
        $no_kartu = $this->input->post('no_kartu');
        $keperluan = $this->input->post('keperluan');
        $waktu_kembali = $this->input->post('waktu_kembali');

        // Validasi UID
        if (!$no_kartu || $no_kartu === '') {
            echo 'error: UID kosong';
            return;
        }

        // Validasi santri
        $cek = $this->db->get_where('tb_santri', ['no_kartu' => $no_kartu])->row();
        if (!$cek) {
            echo 'error: Santri tidak ditemukan';
            return;
        }

        // Validasi waktu kembali harus diisi
        if (!$waktu_kembali || $waktu_kembali === '') {
            echo 'error: Waktu kembali wajib diisi';
            return;
        }

        $data = [
            'no_kartu'       => $no_kartu,
            'tanggal_izin'   => date('Y-m-d'),
            'keperluan'      => $keperluan,
            'waktu_kembali'  => $waktu_kembali, // <--- Wajib disimpan!
            'status'         => 'pending',
            'mode'           => 'KELUAR',
            'waktu_keluar'   => null
        ];

        $this->db->insert('tb_perizinan', $data);
        echo 'success';
    }

    public function simpan_izin_masuk()
    {
        $no_kartu = $this->input->post('no_kartu');
        $keperluan = $this->input->post('keperluan');

        // Validasi UID kosong
        if (!$no_kartu || $no_kartu === '') {
            $this->session->set_flashdata('error', '❌ UID belum terbaca. Tap kartu dulu!');
            redirect('santri/perizinan_masuk');
        }

        // Validasi santri
        $cek = $this->db->get_where('tb_santri', ['no_kartu' => $no_kartu])->row();
        if (!$cek) {
            $this->session->set_flashdata('error', '❌ UID tidak dikenali. Santri tidak ditemukan.');
            redirect('santri/perizinan_masuk');
        }

        $data = [
            'no_kartu'       => $no_kartu,
            'tanggal_izin'   => date('Y-m-d'),
            'keperluan'      => $keperluan,
            'status'         => 'pending', // ✅ ditandai sebagai pengajuan dulu
            'mode'           => 'MASUK',
            'waktu_keluar'   => null,
            'waktu_kembali'  => null // ✅ belum diisi, nanti pas admin setujui baru diisi
        ];

        $this->db->insert('tb_perizinan', $data);
        file_put_contents(FCPATH . 'uid_masuk.txt', '');

        $this->session->set_flashdata('success', '✅ Izin masuk berhasil ditambahkan dan menunggu persetujuan.');
        redirect('santri/perizinan_masuk');
    }
    
    private function kirim_telegram($chat_id, $pesan)
    {
        $token = '7560091761:AAHGtCKA5Jyv9Uu1ImIvVmkek3EC0oiANBo'; // Ganti dengan token asli
        $url = "https://api.telegram.org/bot{$token}/sendMessage";

        $data = [
            'chat_id' => $chat_id,
            'text' => $pesan,
            'parse_mode' => 'Markdown'
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        curl_close($ch);
    }

    public function kirim_wa()
    {
        $this->load->database(); // pastikan database aktif
        $walikamar = $this->db->get('tb_walikamar')->result();

        foreach ($walikamar as $wali) {
            if ($wali->no_walikamar) {
                $pesan = "📢 Ini pesan uji dari sistem notifikasi WhatsApp ke {$wali->nama_walikamar}";
                $this->_kirim_wa($wali->no_walikamar, $pesan);
            }
        }

        echo "✅ Pesan WA berhasil dikirim ke semua wali kamar.";
    }

    private function _kirim_wa($no_hp, $pesan)
    {
        $token = 'V8A1HMFMFefeEPe7Zp7r'; // 🔁 Ganti dengan token dari Fonnte (di halaman Device > Token)
        $url = 'https://api.fonnte.com/send';

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
            CURLOPT_HTTPHEADER => [
                "Authorization: $token"
            ],
        ]);

        $response = curl_exec($curl);
        curl_close($curl);
    }

    public function cek_santri_kabur()
    {
        $this->load->database();

        // Ambil semua perizinan keluar yang sudah disetujui tapi belum kembali
        $izin = $this->db->where('status', 'disetujui')
                        ->where('mode', 'KELUAR')
                        ->where('waktu_kembali <', date('Y-m-d H:i:s'))
                        ->get('tb_perizinan')->result();

        foreach ($izin as $row) {
            // Dapatkan data lengkap wali kamar
            $this->db->select('s.nama_santri, k.kamar, s.tingkat_sekolah, w.nama_walikamar, w.no_walikamar, w.chat_id');
            $this->db->from('tb_santri s');
            $this->db->join('tb_kamar k', 's.id_kamar = k.id_kamar');
            $this->db->join('tb_walikamar w', 'k.id_walikamar = w.id_walikamar');
            $this->db->where('s.no_kartu', $row->no_kartu);
            $santri = $this->db->get()->row();

            if (!$santri) continue;

            // Hitung keterlambatan
            $waktu_kembali = new DateTime($row->waktu_kembali);
            $sekarang = new DateTime();
            $terlambat = $waktu_kembali->diff($sekarang)->format('%h jam %i menit');

            // Buat pesan
            $pesan = "🚨 *Santri Belum Kembali!*\n";
            $pesan .= "👤 {$santri->nama_santri}\n";
            $pesan .= "🏠 Kamar: {$santri->kamar} ({$santri->tingkat_sekolah})\n";
            $pesan .= "⏳ Seharusnya kembali: " . date('d-m-Y H:i', strtotime($row->waktu_kembali)) . "\n";
            $pesan .= "⚠️ Terlambat *{$terlambat}*\n";
            $pesan .= "📍 Status: *Belum kembali ke pondok!*";

            if (!empty($santri->chat_id)) {
                $this->kirim_telegram($santri->chat_id, $pesan);
            }

            if (!empty($santri->no_walikamar)) {
                $this->_kirim_wa($santri->no_walikamar, $pesan);
            }

            // Optional: Update status jadi terlambat_kembali
            $this->db->where('id_perizinan', $row->id_perizinan)->update('tb_perizinan', [
                'status' => 'terlambat_kembali'
            ]);
        }

        echo "✅ Cek selesai.";
    }

    public function cetak_log_keluar_masuk()
    {
        $tanggal_mulai = $this->input->get('mulai') ?? date('Y-m-01');
        $tanggal_selesai = $this->input->get('sampai') ?? date('Y-m-d');

        $log = $this->Mlog->get_filtered($tanggal_mulai, $tanggal_selesai); // pastikan Mlog kamu punya ini

        $data = [
            'log' => $log,
            'tanggal_mulai' => $tanggal_mulai,
            'tanggal_selesai' => $tanggal_selesai
        ];

        $this->load->view('santri_log_keluar_masuk_cetak', $data);
    }


}
