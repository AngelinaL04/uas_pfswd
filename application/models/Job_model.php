<?php
class Job_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // Ambil semua pekerjaan
    public function get_all_jobs()
    {
        $this->db->select('jobs.*, users.username');
        $this->db->from('jobs');
        $this->db->join('users', 'jobs.client_id = users.id');
        $this->db->order_by('jobs.created_at', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }

    // Tambahkan pekerjaan baru
    public function add_job($data)
    {
        return $this->db->insert('jobs', $data);
    }

    // Fungsi untuk mengupdate status pekerjaan
    public function update_job_status($job_id, $status)
    {
        $this->db->set('status', $status);
        $this->db->where('id', $job_id);
        
        if ($this->db->update('jobs')) {
            return true;
        }

        return false;  // Gagal mengupdate status
    }


    // Ambil pekerjaan berdasarkan ID
    public function get_job_by_id($job_id)
    {
        $this->db->where('id', $job_id);
        $query = $this->db->get('jobs');
        
        if ($query->num_rows() == 0) {
            return false;  // Pekerjaan tidak ditemukan
        }
        
        return $query->row_array();  // Mengembalikan data pekerjaan
    }



    public function update_job($job_id, $data) {
        $this->db->where('id', $job_id);
        return $this->db->update('jobs', $data); // Mengupdate pekerjaan
    }
    

     // Fungsi untuk menghapus pekerjaan
    public function delete_job($job_id)
    {
        $this->db->where('id', $job_id);
         return $this->db->delete('jobs'); // Menghapus data pekerjaan berdasarkan ID
    }
    
}
