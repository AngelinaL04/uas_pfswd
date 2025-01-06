<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SignInPage extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // Memuat model User_model untuk autentikasi
        $this->load->model('User_model');
        // Memuat library session untuk menyimpan data login
        $this->load->library('session');
        // Memuat helper URL
        $this->load->helper('url');
        // Memuat library form validation untuk validasi input
        $this->load->library('form_validation');
    }

    // Menampilkan halaman login
    public function index()
    {
        $this->load->view('SignInPage');
    }

    // Login user
    public function login()
    {
        // Validasi form input
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('role', 'Role', 'required|in_list[freelancer,client]'); // Validasi role hanya 'freelancer' atau 'client'
        $this->form_validation->set_rules('password', 'Password', 'required'); // Validasi password wajib diisi
        
        if ($this->form_validation->run() == FALSE) {
            // Jika validasi gagal, kembali ke halaman login dengan error
            $this->session->set_flashdata('error', validation_errors());
            redirect('signin');
        }

        // Mengambil data dari form
        $email = $this->input->post('email');
        $role = $this->input->post('role');
        $entered_password = $this->input->post('password');
    
        // Cek apakah email ada di database
        $user = $this->User_model->get_user_by_email($email);
        
        if ($user) {
            // Periksa apakah password yang dimasukkan sesuai dengan hash password di database
            if (password_verify($entered_password, $user['password'])) {
                // Periksa apakah role yang dipilih sesuai dengan role pengguna di database
                if ($user['role'] !== $role) {
                    // Update role di database jika berbeda
                    $this->User_model->update_user_role($user['id'], $role);
                }

                // Simpan informasi user ke session
                $this->session->set_userdata('freelancer_id', $user['id']);
                $this->session->set_userdata('username', $user['username']);
                $this->session->set_userdata('role', $role);

                // Redirect ke halaman berdasarkan role
                if ($role == 'freelancer') {
                    redirect('freelancer_dashboard');  // Redirect ke halaman freelancer dashboard
                } elseif ($role == 'client') {
                    redirect('client_dashboard');  // Redirect ke halaman client dashboard
                }
            } else {
                // Jika password salah
                $this->session->set_flashdata('error', 'Password salah');
                redirect('signin');
            }
        } else {
            // Jika email tidak ditemukan
            $this->session->set_flashdata('error', 'Email tidak terdaftar');
            redirect('signin');
        }
    }
}
?>
