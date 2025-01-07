<?php
class User_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        // Memuat database
        $this->load->database();
    }

    // Mendapatkan data user berdasarkan ID
    public function get_user_by_id($id)
    {
        $query = $this->db->get_where('users', ['id' => $id]);
        return $query->row_array();  // Mengembalikan data user dalam bentuk array
    }

    // Mendapatkan data user berdasarkan email
    public function get_user_by_email($email)
    {
        $query = $this->db->get_where('users', ['email' => $email]);
        return $query->row_array();  // Mengembalikan data user berdasarkan email
    }

    public function update_user($freelancer_id, $name, $password, $profile_picture) {
        $data = [
            'username' => $name,
            'profile_picture' => $profile_picture
        ];
    
        // Jika password tidak null, masukkan password baru
        if ($password !== null) {
            $data['password'] = $password;
        }
    
        $this->db->where('id', $freelancer_id);
        return $this->db->update('users', $data);
    }
    

    // Cek apakah email sudah terdaftar
    public function check_email_exists($email)
    {
        $query = $this->db->get_where('users', ['email' => $email]);
        return $query->num_rows() > 0;
    }

    // Cek apakah username sudah terdaftar
    public function check_username_exists($username)
    {
        $query = $this->db->get_where('users', ['username' => $username]);
        return $query->num_rows() > 0;
    }

    // Menyimpan user ke database
    public function create_user($data)
    {
        return $this->db->insert('users', $data); // Menambahkan data ke tabel 'users'
    }

    // Menyimpan atau memperbarui role user
    public function update_user_role($freelancer_id, $role)
    {
        $data = ['role' => $role];  // Menyimpan role baru ke database
        $this->db->where('id', $freelancer_id);
        return $this->db->update('users', $data);  // Memperbarui data role di database
    }
}
