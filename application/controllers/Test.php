<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {
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
}
