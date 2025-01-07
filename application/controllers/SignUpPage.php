<?php
class SignUpPage extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // Memuat helper URL
        $this->load->helper('url');
        // Memuat model untuk berinteraksi dengan database
        $this->load->model('User_model');
        // Memuat library session untuk flashdata
        $this->load->library('session');
    }

    // Menampilkan halaman signup
    public function index()
    {
        // Menampilkan form signup (view SignUpPage.php)
        $this->load->view('SignUpPage');
    }

    // Memproses data signup
    public function register()
    {
        // Mengambil data dari form input
        $username = $this->input->post('username');
        $email = $this->input->post('email');
        $password = $this->input->post('password');
        $role = $this->input->post('role');

        // Cek apakah email sudah terdaftar
        if ($this->User_model->check_email_exists($email)) {
            $this->session->set_flashdata('error', 'Email sudah terdaftar');
            redirect('SignUpPage');
        }

        // Cek apakah username sudah terdaftar
        if ($this->User_model->check_username_exists($username)) {
            $this->session->set_flashdata('error', 'Username sudah terdaftar');
            redirect('SignUpPage');
        }

        // Data yang akan disimpan ke database
        $user_data = [
            'username' => $username,
            'email' => $email,
            'password' => $password,  // Simpan password apa adanya tanpa enkripsi
            'role' => $role,  // Role 'client' atau 'freelancer'
        ];

        // Menyimpan data ke database
        if ($this->User_model->create_user($user_data)) {
            $this->session->set_flashdata('success', 'Signup berhasil! Silakan login.');
            redirect('signin');  // Mengarahkan ke halaman login setelah berhasil signup
        } else {
            $this->session->set_flashdata('error', 'Terjadi kesalahan, coba lagi.');
            redirect('signup');  // Kembali ke halaman signup jika gagal
        }
    }
}
