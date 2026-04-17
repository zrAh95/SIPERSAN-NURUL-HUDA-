<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengurus extends CI_Controller {

    public function __construct() {
        parent::__construct();

        // ✅ Hindari redirect loop hanya untuk method login & auth & logout
        if (!in_array($this->router->method, ['login', 'auth', 'logout'])) {
            cek_pengurus_login(); 
        }

        $this->load->model('Mpengurus');
    }

    public function index() {
        $data['title'] = 'Data Pengurus';
        $data['pengurus'] = $this->Mpengurus->get_all();

        $this->load->view('templates_admin/header', $data);
        $this->load->view('templates_admin/sidebar');
        $this->load->view('pengurus_index', $data);
        $this->load->view('templates_admin/footer');
    }

    public function tambah() {
        $data['title'] = 'Tambah Data Pengurus';
        $this->load->view('templates_admin/header', $data);
        $this->load->view('templates_admin/sidebar');
        $this->load->view('pengurus_tambah');
        $this->load->view('templates_admin/footer');
    }

    public function simpan() {
		$nama = trim($this->input->post('nama_pengguna', true));
        $telp = trim($this->input->post('no_telp', true));
        $password = $this->input->post('password');
        $foto = isset($_FILES['foto']['name']) ? $_FILES['foto']['name'] : '';
        $nama_foto = null;

        if ($nama === '' || $telp === '' || $password === '') {
            $this->session->set_flashdata('error', 'Nama, nomor telepon, dan password wajib diisi.');
            redirect('pengurus/tambah');
        }

        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\w\s]).{8,}$/', $password)) {
            $this->session->set_flashdata('error', 'Password harus terdiri dari huruf besar, kecil, angka, dan simbol.');
            redirect('pengurus/tambah');
        }

        if ($foto) {
            $config['upload_path'] = './uploads/pengurus/';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size'] = 2048;
            $config['file_name'] = time() . '_' . $foto;

            $this->load->library('upload');
            $this->upload->initialize($config);

            if ($this->upload->do_upload('foto')) {
                $nama_foto = $this->upload->data('file_name');
            } else {
                $this->session->set_flashdata('error', 'Upload gagal: ' . strip_tags($this->upload->display_errors()));
                redirect('pengurus/tambah');
            }
        }

        $data = [
            'nama_pengguna' => $nama,
            'no_telp' => $telp,
            'foto' => $nama_foto,
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ];

        $this->Mpengurus->insert($data);
        $this->session->set_flashdata('success', 'Data pengurus berhasil disimpan.');
        redirect('pengurus');
    }

    public function edit($id) {
        $data['title'] = 'Edit Pengurus';
        $data['pengurus'] = $this->Mpengurus->get_by_id($id);
        $this->load->view('templates_admin/header', $data);
        $this->load->view('templates_admin/sidebar');
        $this->load->view('pengurus_edit', $data);
        $this->load->view('templates_admin/footer');
    }

    public function update($id) {
        $pengurus = $this->Mpengurus->get_by_id($id);

        if (!$pengurus) {
            show_404();
        }

        $nama = trim($this->input->post('nama_pengguna', true));
        $telp = trim($this->input->post('no_telp', true));
        $password = $this->input->post('password');

        $data = [
            'nama_pengguna' => $nama,
            'no_telp' => $telp
        ];

        if ($nama === '' || $telp === '') {
            $this->session->set_flashdata('error', 'Nama dan nomor telepon wajib diisi.');
            redirect('pengurus/edit/' . $id);
        }

        if (!empty($password)) {
            if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\w\s]).{8,}$/', $password)) {
                $this->session->set_flashdata('error', 'Password harus terdiri dari huruf besar, kecil, angka, dan simbol.');
                redirect('pengurus/edit/' . $id);
            }

            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        if (!empty($_FILES['foto']['name'])) {
            $config['upload_path'] = './uploads/pengurus/';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size'] = 2048;
            $config['file_name'] = time() . '_' . $_FILES['foto']['name'];

            $this->load->library('upload');
            $this->upload->initialize($config);

            if ($this->upload->do_upload('foto')) {
                if (!empty($pengurus->foto) && file_exists('./uploads/pengurus/' . $pengurus->foto)) {
                    unlink('./uploads/pengurus/' . $pengurus->foto);
                }

                $upload_data = $this->upload->data();
                $data['foto'] = $upload_data['file_name'];
            } else {
                $this->session->set_flashdata('error', strip_tags($this->upload->display_errors()));
                redirect('pengurus/edit/' . $id);
            }
        }

        $update = $this->Mpengurus->update($id, $data);

        if (!$update['success']) {
            $pesan = 'Data gagal diperbarui.';

            if (!empty($update['error']['message'])) {
                $pesan .= ' Error database: ' . $update['error']['message'];
            }

            $this->session->set_flashdata('error', $pesan);
            redirect('pengurus/edit/' . $id);
        }

        if ((int) $this->session->userdata('id_pengurus') === (int) $id) {
            $session_data = [
                'nama_pengguna' => $data['nama_pengguna']
            ];

            if (isset($data['foto'])) {
                $session_data['foto'] = $data['foto'];
            }

            $this->session->set_userdata($session_data);
        }

        if (!empty($password)) {
            $pesan = 'Data berhasil diperbarui. Password login juga sudah diganti.';
        } elseif (!empty($data['foto'])) {
            $pesan = 'Data berhasil diperbarui. Foto pengurus juga sudah diganti.';
        } else {
            $pesan = 'Data berhasil diperbarui.';
        }

        $this->session->set_flashdata('success', $pesan);
        redirect('pengurus/edit/' . $id);
    }

    public function hapus($id) {
        $pengurus = $this->Mpengurus->get_by_id($id);

        if (!$pengurus) {
            show_404();
        }

        if (!empty($pengurus->foto) && file_exists('./uploads/pengurus/' . $pengurus->foto)) {
            unlink('./uploads/pengurus/' . $pengurus->foto);
        }
        $this->Mpengurus->delete($id);
        $this->session->set_flashdata('success', 'Data berhasil dihapus.');
        redirect('pengurus');
    }

    public function login() {
        $this->load->view('admin/login_pengurus');
    }

    public function auth() {
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        // Gunakan model yang benar
        $pengurus = $this->Mpengurus->login($username, $password);

        if ($pengurus) {
            $this->session->set_userdata([
                'pengurus_login' => true,
                'id_pengurus'    => $pengurus->id_pengurus,
                'nama_pengguna'  => $pengurus->nama_pengguna,
                'foto'           => $pengurus->foto // ⬅️ untuk tampil pengurus nantinya 
            ]);
            // ✅ Untuk check log pengurus yang update
            $this->db->where('id_pengurus', $pengurus->id_pengurus)
            ->update('tb_pengurus', ['last_login' => date('Y-m-d H:i:s')]);
            redirect('dashboard');
        } else {
            $this->session->set_flashdata('error', '❌ Nama pengguna atau password salah.');
            redirect('pengurus/login');
        }
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('loginpengurus');
    }

    public function log_login() {
        $data['title'] = 'Log Login Pengurus';
        $data['pengurus'] = $this->Mpengurus->get_all();

        $this->load->view('templates_admin/header', $data);
        $this->load->view('templates_admin/sidebar');
        $this->load->view('Vpenguruslog', $data);
        $this->load->view('templates_admin/footer');
    }

}
