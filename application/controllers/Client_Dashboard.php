<?php
date_default_timezone_set('Asia/Jakarta');

class Client_Dashboard extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['url', 'file']); // Muat helper URL dan File
        $this->load->model('Job_model'); // Muat model Job_model
        $this->load->library('session'); // Memuat library session
        $this->load->library('form_validation'); // Muat library form_validation
        $this->load->model('Notification_model'); // Memuat model Notification_model
    }
    

    

    public function index()
    {
        // Memastikan user sudah login sebagai client
        if (!$this->session->userdata('freelancer_id') || $this->session->userdata('role') !== 'client') {
            redirect('signin'); // Redirect jika tidak login atau bukan client
        }
    
        // Ambil semua data pekerjaan dari database
        $data['jobs'] = $this->Job_model->get_all_jobs();
    
        // Menambahkan status warna untuk setiap pekerjaan
        foreach ($data['jobs'] as &$job) {
            if ($job['status'] == 'open') {
                $job['status_class'] = 'text-success'; // Hijau untuk open
            } elseif ($job['status'] == 'closed') {
                $job['status_class'] = 'text-danger'; // Merah untuk closed
            } else {
                $job['status_class'] = ''; // Tidak ada warna jika status lainnya
            }
        }
    
        // Ambil daftar gambar dari folder assets/images
        $image_dir = FCPATH . 'assets/images';
        $images = array_diff(scandir($image_dir), ['.', '..']);
        $data['images'] = $images;
    
        // Tampilkan view Client_Dashboard
        $this->load->view('Client_Dashboard', $data);
    }


    public function create_job()
    {
        // Memastikan user sudah login
        if (!$this->session->userdata('freelancer_id')) {
            redirect('signin'); // Redirect jika tidak login
        }
    
        // Validasi input
        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('description', 'Description', 'required');
        $this->form_validation->set_rules('image_url', 'Image URL', 'required');
    
        if ($this->form_validation->run() == FALSE) {
            // Jika validasi gagal, kembali ke halaman form dengan error
            $this->load->view('create_job_view');
        } else {
            // Ambil data dari form
            $title = $this->input->post('title');
            $description = $this->input->post('description');
            $image_url = $this->input->post('image_url');
            $client_id = $this->session->userdata('freelancer_id'); // ID client dari session
    
            // Siapkan data untuk disimpan
            $data = [
                'title' => $title,
                'description' => $description,
                'image_url' => $image_url,
                'client_id' => $client_id,
                'status' => 'open', // Status pekerjaan baru adalah "open"
            ];
    
            // Masukkan data pekerjaan baru ke database
            if ($this->Job_model->add_job($data)) {
                redirect('client_dashboard'); // Redirect setelah sukses
            } else {
                echo "Gagal menambahkan pekerjaan baru.";
            }
        }
    }
    
    

    public function edit_job()
    {
        // Memastikan user sudah login
        if (!$this->session->userdata('freelancer_id')) {
            redirect('signin'); // Redirect jika tidak login
        }
    
        // Ambil data dari form
        $job_id = $this->input->post('id'); // ID pekerjaan yang akan diubah
        $title = $this->input->post('title');
        $description = $this->input->post('description');
        $image_url = $this->input->post('image_url');
        $client_id = $this->session->userdata('freelancer_id'); // ID client dari session
    
        // Pastikan pekerjaan tersebut milik client yang sedang login
        $job = $this->Job_model->get_job_by_id($job_id);
        if ($job['client_id'] != $client_id) {
            // Set flashdata untuk pesan error
            $this->session->set_flashdata('error', 'Anda tidak memiliki izin untuk mengedit pekerjaan ini.');
            redirect('client_dashboard'); // Redirect ke dashboard client
            return;
        }
    
        // Siapkan data untuk diperbarui
        $data = [
            'title' => $title,
            'description' => $description,
            'image_url' => $image_url,
        ];
    
        // Perbarui data pekerjaan di database
        if ($this->Job_model->update_job($job_id, $data)) {
            redirect('client_dashboard'); // Redirect setelah sukses
        } else {
            echo "Gagal memperbarui pekerjaan.";
        }
    }
    
    public function delete_job()
    {
        // Memastikan user sudah login
        if (!$this->session->userdata('freelancer_id')) {
            redirect('signin'); // Redirect jika tidak login
        }
    
        // Ambil ID pekerjaan yang akan dihapus
        $job_id = $this->input->post('id');
        $client_id = $this->session->userdata('freelancer_id'); // ID client dari session
    
        // Pastikan pekerjaan tersebut milik client yang sedang login
        $job = $this->Job_model->get_job_by_id($job_id);
        if ($job['client_id'] != $client_id) {
            // Set flashdata untuk pesan error
            $this->session->set_flashdata('error', 'Anda tidak memiliki izin untuk menghapus pekerjaan ini.');
            redirect('client_dashboard'); // Redirect ke dashboard client
            return;
        }
    
        // Hapus pekerjaan dari database
        if ($this->Job_model->delete_job($job_id)) {
            // Menghapus file gambar yang terkait dengan pekerjaan
            $image_path = FCPATH . 'assets/images/' . $job['image_url'];
            if (file_exists($image_path)) {
                unlink($image_path); // Menghapus file gambar
            }
    
            redirect('client_dashboard'); // Redirect setelah sukses
        } else {
            echo "Gagal menghapus pekerjaan.";
        }
    }

    public function inbox() {
        // Ambil client_id dari sesi atau autentikasi
        $client_id = $this->session->userdata('freelancer_id');
    
        // Cek apakah client_id ada
        if (empty($client_id)) {
            show_error('Client tidak ditemukan atau belum login.', 404);
            return;
        }
    
        // Ambil data notifikasi berdasarkan client_id
        $data['notifications'] = $this->Notification_model->get_notifications2($client_id);
    
        // Load tampilan inbox
        $this->load->view('inbox_client', $data);
    }
    
    public function update_notification_status()
    {
        // Mendapatkan data dari form
        $notification_id = $this->input->post('notification_id');
        $freelancer_id = $this->input->post('freelancer_id');
        $job_id = $this->input->post('job_id');
        $status = $this->input->post('status');
        
        // Pastikan status yang diterima atau tidak diterima terupdate
        $this->db->where('id', $notification_id);
        $this->db->update('notifications', ['is_accepted' => $status]);
    
        // Jika status diterima, update status pekerjaan
        if ($status == 'Diterima') {
            $this->db->where('id', $job_id);
            $this->db->update('jobs', ['status' => 'closed']);
        }
    
        // Feedback dan redirect
        $this->session->set_flashdata('message', 'Status berhasil diperbarui!');
        redirect('client_dashboard/inbox'); // Kembali ke halaman inbox
    }
    
    

    
    public function accept_freelancer($job_id, $freelancer_id)
    {
        // Memastikan user sudah login sebagai client
        if (!$this->session->userdata('freelancer_id') || $this->session->userdata('role') !== 'client') {
            redirect('signin'); 
        }
    
        // Ambil ID client dari session
        $client_id = $this->session->userdata('freelancer_id');
    
        // Validasi bahwa pekerjaan benar milik client yang sedang login
        $job = $this->Job_model->get_job_by_id($job_id);
        if (!$job) {
            $this->session->set_flashdata('error', 'Pekerjaan tidak ditemukan.');
            redirect('client_dashboard');
            return;
        }
    
        if ($job['client_id'] != $client_id) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki izin untuk menerima freelancer ini.');
            redirect('client_dashboard');
            return;
        }
    
        // Update status pekerjaan menjadi 'closed' dan simpan freelancer_id
        $data = [
            'status' => 'closed',
            'freelancer_id' => $freelancer_id
        ];
    
        // Debugging: Log sebelum update
        log_message('debug', 'Mengupdate pekerjaan dengan ID: ' . $job_id . ' dengan status closed.');
    
        if ($this->Job_model->update_job($job_id, $data)) {
            // Log setelah update
            log_message('debug', 'Status pekerjaan berhasil diperbarui menjadi closed untuk job_id: ' . $job_id);
    
            // Notifikasi kepada freelancer
            $message = "Selamat! Anda diterima untuk pekerjaan: '{$job['title']}'";
            $this->Notification_model->send_notification($client_id, $freelancer_id, $job_id, $message);
    
            // Update status notifikasi menjadi 'Diterima'
            $this->Notification_model->update_status($job_id, 'Diterima');  // Pastikan job_id dan notification_id dihubungkan dengan benar
    
            $this->session->set_flashdata('success', 'Freelancer berhasil diterima dan pekerjaan ditutup.');
        } else {
            log_message('error', 'Gagal memperbarui status pekerjaan pada job_id: ' . $job_id);
            $this->session->set_flashdata('error', 'Gagal memperbarui status pekerjaan.');
        }
    
        redirect('client_dashboard');
    }
    
}
?>
