<?php
date_default_timezone_set('Asia/Jakarta');
class Freelancer_Dashboard extends CI_Controller {

public function __construct()
{
    parent::__construct();

    $this->load->library('session');
    $this->load->helper('url');
    $this->load->model('User_model');
    $this->load->model('Job_model');
    $this->load->model('Notification_model');

    // Memastikan user login dan memiliki role freelancer
    if (!$this->session->userdata('freelancer_id') || $this->session->userdata('role') !== 'freelancer') {
        redirect('signin');  // Jika tidak login atau bukan freelancer, redirect ke halaman login
    }
}

public function index()
{
    $freelancer_id = $this->session->userdata('freelancer_id');
    $user = $this->User_model->get_user_by_id($freelancer_id);

    if (!$user) {
        show_error('User tidak ditemukan!', 404, 'Error');
    }

    $data['user'] = $user['username'];

    // Pekerjaan untuk freelancer (yang tidak berstatus completed)
    $data['pekerjaan'] = $this->Job_model->get_all_jobs($freelancer_id, 'freelancer');

    if (empty($data['pekerjaan'])) {
        $data['message'] = 'Tidak ada pekerjaan yang tersedia saat ini.';
    }

    $this->load->view('Freelancer_Dashboard', $data);
}


public function ambil_pekerjaan()
{
    // Ambil ID freelancer dari session
    $freelancer_id = $this->session->userdata('freelancer_id');

    if (!$freelancer_id) {
        echo json_encode(['status' => 'error', 'message' => 'ID freelancer tidak valid.']);
        return;
    }

    $job_id = $this->input->post('job_id');

    if (!$job_id) {
        echo json_encode(['status' => 'error', 'message' => 'ID pekerjaan tidak valid.']);
        return;
    }

    $job = $this->Job_model->get_job_by_id($job_id);

    if (!$job) {
        echo json_encode(['status' => 'error', 'message' => 'Pekerjaan tidak ditemukan atau ID pekerjaan tidak valid.']);
        return;
    }

    // Periksa apakah pekerjaan sudah ditutup
    if ($job['status'] == 'closed') {
        echo json_encode(['status' => 'error', 'message' => 'Pekerjaan ini sudah ditutup.']);
        return;
    }

    // Tidak merubah status pekerjaan di sini, biarkan tetap 'open'

    // Kirim notifikasi kepada client bahwa freelancer telah mengambil pekerjaan
    $message = "Freelancer '{$freelancer_id}' telah mengambil pekerjaan '{$job['title']}'. Klien perlu mengonfirmasi.";
    $this->send_notification($job['client_id'], $freelancer_id, $job_id, $message);

    echo json_encode(['status' => 'success', 'message' => 'Pekerjaan berhasil diambil!']);
}

public function accept_job($job_id)
{
    // Ambil ID freelancer dari session
    $freelancer_id = $this->session->userdata('freelancer_id');

    // Periksa apakah pekerjaan valid dan sudah dalam status 'open'
    $job = $this->Job_model->get_job_by_id($job_id);
    if (!$job || $job['status'] != 'open') {
        echo json_encode(['status' => 'error', 'message' => 'Pekerjaan tidak tersedia untuk diterima atau sudah ditutup.']);
        return;
    }

    // Update status pekerjaan menjadi 'closed' setelah diterima oleh klien
    $status = 'closed';

    // Perbarui status pekerjaan di database hanya setelah klien menerima
    $update = $this->Job_model->update_job_status($job_id, $status);

    if ($update) {
        // Kirim notifikasi kepada client bahwa pekerjaan diterima freelancer
        $message = "Freelancer '{$freelancer_id}' telah menerima pekerjaan '{$job['title']}' dan mulai dikerjakan.";
        $this->send_notification($job['client_id'], $freelancer_id, $job_id, $message);

        // Kirimkan response sukses
        echo json_encode(['status' => 'success', 'message' => 'Pekerjaan berhasil diterima dan dimulai!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui status pekerjaan.']);
    }
}


private function send_notification($client_id, $freelancer_id, $job_id, $message)
{
    $data = [
        'client_id' => $client_id,
        'freelancer_id' => $freelancer_id,
        'job_id' => $job_id,
        'status' => 'new',  // Status default
        'is_accepted' => 'Menunggu',  // Sesuai nilai default di tabel
        'created_at' => date('Y-m-d H:i:s')
    ];

    // Simpan notifikasi ke database
    $this->insert_notification($data);
}

public function insert_notification($data)
{
    $this->db->insert('notifications', $data);

    if ($this->db->affected_rows() > 0) {
        log_message('debug', 'Notifikasi berhasil disimpan: ' . print_r($data, true));
        return true;
    } else {
        log_message('error', 'Gagal menyimpan notifikasi. Data: ' . print_r($data, true));
        return false;
    }
}

public function inbox() {
    // Ambil client_id dari sesi atau autentikasi
    $freelancer_id = $this->session->userdata('freelancer_id');

    // Cek apakah client_id ada
    if (empty($freelancer_id)) {
        show_error('Freelancer tidak ditemukan atau belum login.', 404);
        return;
    }

    // Ambil data notifikasi berdasarkan client_id
    $data['notifications'] = $this->Notification_model->get_notifications1($freelancer_id);

    // Load tampilan inbox
    $this->load->view('inbox_freelancer', $data);
}

public function update_notification_status()
{
    $notification_id = $this->input->post('notification_id');
    $status = $this->input->post('status');
    $valid_statuses = ['New', 'Completed']; // Hanya status yang valid

    // Cek apakah status valid
    if (!in_array($status, $valid_statuses)) {
        echo json_encode(['status' => 'error', 'message' => 'Status tidak valid']);
        return;
    }

    // Update status notifikasi di database
    $this->db->where('id', $notification_id);
    $update = $this->db->update('notifications', ['status' => $status]);

    if ($update) {
        echo json_encode(['status' => 'success', 'message' => 'Status berhasil diperbarui']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui status']);
    }
}



}
