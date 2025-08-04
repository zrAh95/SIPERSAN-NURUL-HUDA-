<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
    }

    public function simpan_uid()
    {
        $uid = $this->input->get('uid');
        $mode = strtoupper($this->input->get('mode'));

        if (!$uid) {
            echo "❌ UID kosong!";
            return;
        }

        if ($mode === 'DAFTAR') {
            file_put_contents(FCPATH . 'uid_daftar.txt', $uid);
            echo "📥 UID Disimpan untuk DAFTAR";
            return;
        }

        if ($mode === 'KELUAR') {
            file_put_contents(FCPATH . 'uid_keluar.txt', $uid);
            echo "📥 UID Disimpan untuk KELUAR";
            return;
        }

        if ($mode === 'MASUK') {
            file_put_contents(FCPATH . 'uid_masuk.txt', $uid);
            echo "📥 UID Disimpan untuk MASUK";
            return;
        }

        echo "⚠️ MODE tidak dikenali.";
    }

    public function reset_uid_daftar()
    {
        file_put_contents(FCPATH . 'uid_daftar.txt', '');
        echo "UID dikosongkan";
    }

    public function reset_uid()
    {
        file_put_contents(FCPATH . 'uid.txt', '');
        echo "UID dikosongkan";
    }

    public function get_uid()
    {
        $file_path = FCPATH . 'uid.txt';
        $uid = file_exists($file_path) ? trim(file_get_contents($file_path)) : '';

        if (!$uid) {
            echo json_encode(['status' => 'error', 'message' => 'UID kosong']);
            return;
        }

        $santri = $this->db->get_where('tb_santri', ['no_kartu' => $uid])->row();

        if (!$santri) {
            echo json_encode(['status' => 'not_found', 'message' => 'Santri tidak ditemukan']);
            return;
        }

        $walikamar = null;
        if (!empty($santri->id_walikamar)) {
            $walikamar = $this->db->get_where('tb_walikamar', ['id_walikamar' => $santri->id_walikamar])->row();
        }

        echo json_encode([
            'status' => 'success',
            'uid' => $uid,
            'nama_santri' => $santri->nama_santri,
            'alamat' => $santri->alamat,
            'kamar' => $santri->kamar ?? '-',
            'tingkat_sekolah' => $santri->tingkat_sekolah,
            'nama_walikamar' => $walikamar->nama_walikamar ?? '-',
            'no_walikamar' => $walikamar->no_walikamar ?? '-'
        ]);
    }

    public function get_uid_daftar()
    {
        $file_path = FCPATH . 'uid_daftar.txt';
        $uid = file_exists($file_path) ? trim(file_get_contents($file_path)) : '';

        if (!$uid) {
            echo json_encode([]);
            return;
        }

        echo json_encode(['uid' => $uid]);
    }

    public function perizinan()
    {
        $uid = $this->input->get('uid');
        $mode = strtoupper($this->input->get('mode'));

        if (!$uid || !$mode) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'INVALID']);
            return;
        }

        $santri = $this->db->get_where('tb_santri', ['no_kartu' => $uid])->row();
        if ($santri) {
            $data = [
                'no_kartu' => $uid,
                'mode' => $mode,
            ];

            if ($mode == 'MASUK') {
                $data['waktu_kembali'] = date('Y-m-d H:i:s');
            } elseif ($mode == 'KELUAR') {
                $data['waktu_keluar'] = date('Y-m-d H:i:s');
            }

            $this->db->insert('tb_perizinan', $data);

            // Output JSON yang dibutuhkan ESP32
            header('Content-Type: application/json');
            echo json_encode([
                "status" => "OK",
                "nama"   => $santri->nama_santri,
                "kamar"  => $santri->kamar ?? "-",
                "tingkat"=> $santri->tingkat_sekolah ?? "-"
            ]);
        } else {
            header('Content-Type: application/json');
            echo json_encode(["status" => "NOT FOUND"]);
        }
    }


    public function reset_uid_masuk()
    {
        file_put_contents(FCPATH . 'uid_masuk.txt', '');
        echo "UID masuk dikosongkan";
    }

    public function get_info_izin_keluar() {
        header('Content-Type: application/json');

        $uid = file_exists(FCPATH . 'uid_keluar.txt') ? trim(file_get_contents(FCPATH . 'uid_keluar.txt')) : '';

        if (!$uid) {
            echo json_encode(['status' => 'error', 'message' => 'UID kosong']);
            return;
        }

        $this->db->select('s.nama_santri, s.tingkat_sekolah, k.kamar, w.nama_walikamar, s.foto');
        $this->db->from('tb_santri s');
        $this->db->join('tb_kamar k', 's.id_kamar = k.id_kamar', 'left');
        $this->db->join('tb_walikamar w', 'k.id_walikamar = w.id_walikamar', 'left');
        $this->db->where('s.no_kartu', $uid);
        $result = $this->db->get()->row();

        if (!$result) {
            echo json_encode(['status' => 'not_found', 'message' => 'Santri tidak ditemukan']);
            return;
        }

        echo json_encode([
            'status' => 'success',
            'nama' => $result->nama_santri,
            'kamar' => $result->kamar ?? '-',
            'tingkat' => $result->tingkat_sekolah,
            'wali' => $result->nama_walikamar ?? '-',
            'foto' => $result->foto ?? ''
        ]);

    }

    public function get_info_izin_masuk() 
    {
        header('Content-Type: application/json');

        $file_path = FCPATH . 'uid_masuk.txt';
        $uid = file_exists($file_path) ? trim(file_get_contents($file_path)) : '';

        if (!$uid) {
            echo json_encode(['status' => 'error', 'message' => 'UID kosong']);
            return;
        }

        $this->db->select('s.nama_santri, s.tingkat_sekolah, k.kamar, w.nama_walikamar, s.foto');
        $this->db->from('tb_santri s');
        $this->db->join('tb_kamar k', 's.id_kamar = k.id_kamar', 'left');
        $this->db->join('tb_walikamar w', 'k.id_walikamar = w.id_walikamar', 'left');
        $this->db->where('s.no_kartu', $uid);
        $santri = $this->db->get()->row();

        if (!$santri) {
            echo json_encode(['status' => 'not_found', 'message' => 'Santri tidak ditemukan']);
            return;
        }

        echo json_encode([
            'status' => 'success',
            'uid'    => $uid,
            'nama'   => $santri->nama_santri,
            'kamar'  => $santri->kamar,
            'tingkat'=> $santri->tingkat_sekolah,
            'wali'   => $santri->nama_walikamar ?? '-',
            'foto'   => $santri->foto ?? ''
        ]);

    }


    public function debug_uid()
    {
        $file_path = FCPATH . 'uid.txt';
        $uid = file_exists($file_path) ? trim(file_get_contents($file_path)) : '';
        echo "📋 UID dibaca: " . $uid . "<br>";

        $santri = $this->db->get_where('tb_santri', ['no_kartu' => $uid])->row();
        if ($santri) {
            echo "✅ Cocok dengan: " . $santri->nama_santri;
        } else {
            echo "❌ UID tidak ditemukan di tb_santri";
        }
    }
}
