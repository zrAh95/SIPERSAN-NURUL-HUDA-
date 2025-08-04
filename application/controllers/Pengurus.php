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
        $nama = $this->input->post('nama_pengguna');
        $telp = $this->input->post('no_telp');
        $password = $this->input->post('password');
        $foto = $_FILES['foto']['name'];
        $nama_foto = null;

        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\w\s]).{8,}$/', $password)) {
            $this->session->set_flashdata('error', 'Password harus terdiri dari huruf besar, kecil, angka, dan simbol.');
            redirect('pengurus/tambah');
        }

        if ($foto) {
            $config['upload_path'] = './uploads/pengurus/';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size'] = 2048;
            $config['file_name'] = time() . '_' . $foto;

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('foto')) {
                $nama_foto = $this->upload->data('file_name');
            } else {
                $this->session->set_flashdata('error', 'Upload gagal: ' . $this->upload->display_errors());
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

        $nama = $this->input->post('nama_pengguna');
        $telp = $this->input->post('no_telp');

        $data = [
            'nama_pengguna' => $nama,
            'no_telp' => $telp
        ];

        if ($_FILES['foto']['name']) {
            $config['upload_path'] = './uploads/pengurus/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['max_size'] = 2048;

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('foto')) {
                if (!empty($pengurus->foto) && file_exists('./uploads/pengurus/' . $pengurus->foto)) {
                    unlink('./uploads/pengurus/' . $pengurus->foto);
                }

                $upload_data = $this->upload->data();
                $data['foto'] = $upload_data['file_name'];
            } else {
                $this->session->set_flashdata('error', $this->upload->display_errors());
                redirect('pengurus/edit/' . $id);
            }
        }

        $this->Mpengurus->update($id, $data);
        $this->session->set_flashdata('success', 'Data berhasil diperbarui.');
        redirect('pengurus');
    }

    public function hapus($id) {
        $pengurus = $this->Mpengurus->get_by_id($id);
        if ($pengurus->foto && file_exists('./uploads/pengurus/' . $pengurus->foto)) {
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
