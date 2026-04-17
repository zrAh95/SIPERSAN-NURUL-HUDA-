<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

    // Ubah ke TRUE bila ingin perangkat langsung menulis tb_perizinan
    private $auto_insert_perizinan = FALSE;

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        if (isset($this->db)) $this->db->db_debug = FALSE; // production
        
        //biar waktunya akurat 
        date_default_timezone_set('Asia/Jakarta');     // PHP ke WIB
        $this->db->query("SET time_zone = '+07:00'");  // Session MySQL ke WIB
    }

    /* =======================
       Util / Helper
       ======================= */
    private function json_no_cache() {
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');
        header('Expires: Mon, 01 Jan 1990 00:00:00 GMT');
    }

    private function sanitize_uid($uid) {
        $uid = strtoupper(trim((string)$uid));
        return preg_replace('/[^0-9A-F]/', '', $uid);
    }

    private function read_uid_file($filename) {
        $path = FCPATH . $filename;
        if (file_exists($path)) return $this->sanitize_uid(@file_get_contents($path));
        return '';
    }

    private function write_uid_file($filename, $uid) {
        $ok = @file_put_contents(FCPATH . $filename, $this->sanitize_uid($uid), LOCK_EX);
        if ($ok === false) log_message('error', "Gagal menulis $filename");
        return $ok !== false;
    }

    private function where_uid_equiv($columnExpr, $uid) {
        $uid  = $this->sanitize_uid($uid);
        $expr = "REPLACE(REPLACE(UPPER(TRIM($columnExpr)),':',''),'-','') = " . $this->db->escape($uid);
        $this->db->where($expr, NULL, FALSE);
    }

    /* =======================
       Buffer UID per mode
       ======================= */
    public function simpan_uid() {
        $uid  = $this->sanitize_uid($this->input->get('uid'));
        $mode = strtoupper(trim($this->input->get('mode')));
        if (!$uid) { echo "❌ UID kosong!"; return; }

        if ($mode === 'DAFTAR') { $this->write_uid_file('uid_daftar.txt', $uid); echo "📥 UID Disimpan untuk DAFTAR"; return; }
        if ($mode === 'KELUAR') { $this->write_uid_file('uid_keluar.txt', $uid); echo "📥 UID Disimpan untuk KELUAR"; return; }
        if ($mode === 'MASUK')  { $this->write_uid_file('uid_masuk.txt',  $uid); echo "📥 UID Disimpan untuk MASUK";  return; }
        echo "⚠️ MODE tidak dikenali.";
    }

    public function reset_uid_daftar() { $this->write_uid_file('uid_daftar.txt', ''); echo "UID dikosongkan"; }
    public function reset_uid_masuk()  { $this->write_uid_file('uid_masuk.txt',  ''); echo "UID masuk dikosongkan"; }
    public function reset_uid_keluar() { $this->write_uid_file('uid_keluar.txt', ''); echo "UID keluar dikosongkan"; }

    /* =======================
       Endpoint umum (tanpa uid.txt)
       ======================= */
    // Hanya untuk kebutuhan khusus: harus pakai ?uid=; tidak ada fallback ke file apa pun
    public function get_uid() {
        $this->json_no_cache();
        $uid = $this->sanitize_uid($this->input->get('uid'));
        if (!$uid) { echo json_encode(['status'=>'error','message'=>'UID kosong']); return; }

        $this->db->from('tb_santri s');
        $this->where_uid_equiv('s.no_kartu', $uid);
        $santri = $this->db->get()->row();
        if (!$santri) { echo json_encode(['status'=>'not_found','message'=>'Santri tidak ditemukan','uid'=>$uid]); return; }

        $walikamar = NULL;
        if (!empty($santri->id_walikamar)) {
            $walikamar = $this->db->get_where('tb_walikamar', ['id_walikamar'=>$santri->id_walikamar])->row();
        }

        echo json_encode([
            'status' => 'success',
            'uid' => $uid,
            'nama_santri' => $santri->nama_santri,
            'alamat' => $santri->alamat,
            'kamar' => $santri->kamar ?? '-',
            'tingkat_sekolah' => $santri->tingkat_sekolah,
            'nama_walikamar' => $walikamar->nama_walikamar ?? '-',
            'no_walikamar'   => $walikamar->no_walikamar ?? '-'
        ]);
    }

    public function get_uid_daftar() {
        $this->json_no_cache();
        $uid = $this->read_uid_file('uid_daftar.txt');
        echo $uid ? json_encode(['uid'=>$uid]) : json_encode([]);
    }

    /* =======================
       Dipanggil perangkat
       ======================= */
    public function perizinan() {
        $this->json_no_cache();

        $uid  = $this->sanitize_uid($this->input->get('uid'));
        $mode = strtoupper(trim($this->input->get('mode')));
        if (!$uid || !$mode) { echo json_encode(['status'=>'INVALID']); return; }

        // Perbarui buffer per-mode
        if     ($mode === 'MASUK')  $this->write_uid_file('uid_masuk.txt',  $uid);
        elseif ($mode === 'KELUAR') $this->write_uid_file('uid_keluar.txt', $uid);
        elseif ($mode === 'DAFTAR') $this->write_uid_file('uid_daftar.txt', $uid);

        // Ambil identitas lengkap (join)
        $this->db->select('s.nama_santri, s.tingkat_sekolah, k.kamar, w.nama_walikamar');
        $this->db->from('tb_santri s');
        $this->db->join('tb_kamar k', 's.id_kamar = k.id_kamar', 'left');
        $this->db->join('tb_walikamar w', 'k.id_walikamar = w.id_walikamar', 'left');
        $this->where_uid_equiv('s.no_kartu', $uid);
        $santri = $this->db->get()->row();

        if (!$santri) { echo json_encode(['status'=>'NOT FOUND']); return; }

        // Opsi insert otomatis
        if ($this->auto_insert_perizinan === TRUE) {
            $data = ['no_kartu'=>$uid, 'mode'=>$mode];
            if ($mode === 'MASUK')  $data['waktu_kembali'] = date('Y-m-d H:i:s');
            if ($mode === 'KELUAR') $data['waktu_keluar']  = date('Y-m-d H:i:s');
            $this->db->insert('tb_perizinan', $data);
        }

        echo json_encode([
            'status'  => 'OK',
            'nama'    => $santri->nama_santri,
            'kamar'   => $santri->kamar ?? '-',
            'tingkat' => $santri->tingkat_sekolah ?? '-',
            'wali'    => $santri->nama_walikamar ?? '-'
        ]);
    }

    /* =======================
       Untuk view (modal)
       ======================= */
    public function get_info_izin_keluar() {
        $this->json_no_cache();

        // bisa pakai ?uid=; jika kosong, pakai buffer per-mode
        $uid = $this->sanitize_uid($this->input->get('uid'));
        if (!$uid) $uid = $this->read_uid_file('uid_keluar.txt');
        if (!$uid) { echo json_encode(['status'=>'error','message'=>'UID kosong']); return; }

        $this->db->select('s.nama_santri, s.tingkat_sekolah, k.kamar, w.nama_walikamar, s.foto');
        $this->db->from('tb_santri s');
        $this->db->join('tb_kamar k', 's.id_kamar = k.id_kamar', 'left');
        $this->db->join('tb_walikamar w', 'k.id_walikamar = w.id_walikamar', 'left');
        $this->where_uid_equiv('s.no_kartu', $uid);
        $r = $this->db->get()->row();

        if (!$r) { echo json_encode(['status'=>'not_found','message'=>'Santri tidak ditemukan','uid'=>$uid]); return; }

        echo json_encode([
            'status'  => 'success',
            'uid'     => $uid, // <— tambahkan uid agar view tidak perlu baca file
            'nama'    => $r->nama_santri,
            'kamar'   => $r->kamar ?? '-',
            'tingkat' => $r->tingkat_sekolah,
            'wali'    => $r->nama_walikamar ?? '-',
            'foto'    => $r->foto ?? ''
        ]);
    }

    public function get_info_izin_masuk() {
        $this->json_no_cache();

        // bisa pakai ?uid=; jika kosong, pakai buffer per-mode
        $uid = $this->sanitize_uid($this->input->get('uid'));
        if (!$uid) $uid = $this->read_uid_file('uid_masuk.txt');
        if (!$uid) { echo json_encode(['status'=>'error','message'=>'UID kosong']); return; }

        $this->db->select('s.nama_santri, s.tingkat_sekolah, k.kamar, w.nama_walikamar, s.foto');
        $this->db->from('tb_santri s');
        $this->db->join('tb_kamar k', 's.id_kamar = k.id_kamar', 'left');
        $this->db->join('tb_walikamar w', 'k.id_walikamar = w.id_walikamar', 'left');
        $this->where_uid_equiv('s.no_kartu', $uid);
        $s = $this->db->get()->row();

        if (!$s) { echo json_encode(['status'=>'not_found','message'=>'Santri tidak ditemukan','uid'=>$uid]); return; }

        echo json_encode([
            'status'  => 'success',
            'uid'     => $uid,
            'nama'    => $s->nama_santri,
            'kamar'   => $s->kamar ?? '-',
            'tingkat' => $s->tingkat_sekolah,
            'wali'    => $s->nama_walikamar ?? '-',
            'foto'    => $s->foto ?? ''
        ]);
    }
}
