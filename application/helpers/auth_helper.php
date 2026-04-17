<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function cek_pengurus_login() {
    $ci = get_instance();
    if (!$ci->session->userdata('pengurus_login')) {
        redirect('pengurus/login');
    }
}

