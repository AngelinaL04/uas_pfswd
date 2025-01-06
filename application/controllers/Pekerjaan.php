<?php 
class Job_model extends CI_Model {

    public function get_job_by_id($job_id)
    {
        return $this->db->get_where('jobs', ['id' => $job_id])->row_array();
    }

    public function update_job_status($job_id, $status)
    {
        $this->db->where('id', $job_id);
        return $this->db->update('jobs', ['status' => $status]);
    }
}

