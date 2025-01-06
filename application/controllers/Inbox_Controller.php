<?php

class Inbox extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Load model dan library yang diperlukan
        $this->load->model('Notification_model');
    }

    public function index() {
        // Pastikan user login dan cek peran
        $user_id = $this->session->userdata('user_id');
        $role = $this->session->userdata('role'); // Misalnya 'client' atau 'freelancer'

        if (!$user_id || !$role) {
            // Jika user belum login atau role tidak ditemukan, redirect ke halaman login
            redirect('login');
        }

        // Cek peran user dan arahkan ke inbox yang sesuai
        if ($role == 'client') {
            // Dapatkan notifikasi untuk client
            $notifications = $this->Notification_model->get_notifications($user_id);
            // Tampilkan inbox client
            $this->load->view('client_inbox', ['notifications' => $notifications]);
        } elseif ($role == 'freelancer') {
            // Dapatkan notifikasi untuk freelancer
            $notifications = $this->Notification_model->get_notifications($user_id);
            // Tampilkan inbox freelancer
            $this->load->view('freelancer_inbox', ['notifications' => $notifications]);
        } else {
            // Jika role tidak dikenali, redirect ke halaman home atau error page
            redirect('home');
        }
    }
}

