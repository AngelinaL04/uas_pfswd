<?php
class Profile extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('User_model');
    }

    public function index() {
        $freelancer_id = $this->session->userdata('freelancer_id');
        
        if ($freelancer_id) {
            // Ambil data pengguna berdasarkan ID
            $data['user'] = $this->User_model->get_user_by_id($freelancer_id);
            $this->load->view('profile', $data);
        } else {
            redirect('signin'); // Jika pengguna belum login, redirect ke halaman signin
        }
    }

    // Fungsi untuk menampilkan form edit profil
    public function edit() {
        $freelancer_id = $this->session->userdata('freelancer_id');
        
        if ($freelancer_id) {
            // Ambil data pengguna
            $data['user'] = $this->User_model->get_user_by_id($freelancer_id);

            // Ambil daftar gambar yang ada di folder 'assets/images'
            $data['images'] = $this->_get_images_from_directory('assets/images');
            $this->load->view('edit_profile', $data);
        } else {
            redirect('signin');
        }
    }

    // Fungsi untuk mendapatkan semua gambar dari folder tertentu
    private function _get_images_from_directory($directory) {
        $images = [];
        $path = FCPATH . $directory;
        
        // Membaca isi direktori
        if (is_dir($path)) {
            $files = scandir($path);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..' && preg_match('/\.(jpg|jpeg|png|gif)$/i', $file)) {
                    $images[] = $file;
                }
            }
        }
        return $images;
    }

    public function update() {
        $freelancer_id = $this->session->userdata('freelancer_id');
        
        if ($freelancer_id) {
            // Ambil data dari form
            $name = $this->input->post('name');
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            
            // Ambil profile_picture yang dipilih
            $profile_picture = $this->input->post('profile_picture'); // Jika gambar dari dropdown
    
            // Jika password diisi, hash password sebelum disimpan
            if (!empty($password)) {
                $password = password_hash($password, PASSWORD_BCRYPT);  // Hash password
            } else {
                $password = null;  // Jika tidak ada password baru, tetap null
            }

            // Lakukan update data pengguna
            $updated = $this->User_model->update_user($freelancer_id, $name, $email, $password, $profile_picture);
    
            if ($updated) {
                $this->session->set_flashdata('success', 'Profil berhasil diperbarui!');
                redirect('profile');
            } else {
                $this->session->set_flashdata('error', 'Terjadi kesalahan saat memperbarui profil.');
            redirect('profile/edit');
            }
        } else {
            redirect('signin');
        }
    }
}

