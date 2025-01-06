<?php

class Notification_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // Fungsi untuk mendapatkan notifikasi berdasarkan freelancer_id
    public function get_notifications1($freelancer_id) {
        $this->db->where('freelancer_id', $freelancer_id);
        $query = $this->db->get('notifications');
        return $query->result_array();
    }

    // Fungsi untuk mendapatkan notifikasi berdasarkan client_id
    public function get_notifications2($client_id) {
        // Validasi untuk memastikan client_id adalah angka yang valid
        $client_id = (int) $client_id;

        // Ambil semua data notifikasi yang relevan untuk client tersebut
        $this->db->select('*');
        $this->db->from('notifications');
        $this->db->where('client_id', $client_id); // Filter berdasarkan client_id
        $query = $this->db->get();

        // Mengecek jika ada error dalam query
        if ($this->db->error()['code'] != 0) {
            log_message('error', 'Error SQL: ' . $this->db->last_query());
            return false; // Jika ada error, kembalikan false
        }

        return $query->result_array(); // Mengembalikan hasil query sebagai array
    }

    // Fungsi untuk memperbarui status notifikasi
    public function update_status($notification_id, $status) {
        // Validasi status untuk menghindari input yang tidak valid
        $valid_statuses = ['Menunggu', 'Diterima', 'Tidak diterima'];
        if (!in_array($status, $valid_statuses)) {
            log_message('error', 'Status yang diberikan tidak valid: ' . $status);
            return false;
        }

        // Update status di tabel notifications
        $this->db->where('id', $notification_id);
        $update = $this->db->update('notifications', ['status' => $status]);

        // Mengecek apakah update berhasil
        if (!$update) {
            log_message('error', 'Gagal memperbarui status untuk notification_id: ' . $notification_id);
            return false;
        }

        return true; // Status berhasil diperbarui
    }
    
}
