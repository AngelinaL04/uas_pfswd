<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Pekerjaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* Styling seperti sebelumnya */
        body {
            background-color: #F0F8FF;
            font-family: 'Poppins', sans-serif;
        }

        .navbar {
            background-color: #B1F0F7;
        }

        .navbar-brand, .navbar-nav .nav-link {
            color: #000;
        }

        .navbar-nav .nav-link:hover {
            color: #007BFF;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: bold;
            text-align: center;
            margin-bottom: 50px;
            color: #333;
        }

        .section-intro {
            font-size: 1.5rem;
            text-align: center;
            color: #555;
            margin-bottom: 30px;
        }

        .card-columns {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
        }

        .card {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }

    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="<?= site_url('home'); ?>">FreelanceGo</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php if ($this->session->userdata('user_id')): ?>
                    <li class="nav-item d-flex align-items-center">
                        <span class="nav-link me-2">
                            Selamat datang kembali, <?= htmlspecialchars($this->session->userdata('username') ?? 'Guest'); ?>!
                        </span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('profile'); ?>">
                            <i class="bi bi-person-circle" style="font-size: 30px;"></i> 
                        </a>
                    </li>
                <?php else: ?>
                    <!-- Optionally add sign-in/sign-up links if the user is not logged in -->
                <?php endif; ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Akun Saya
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="<?= site_url('profile'); ?>">Profile</a></li>
                        <li><a class="dropdown-item" href="<?= site_url('freelancer_dashboard/inbox'); ?>">Inbox</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h1 class="section-title">Pekerjaan Freelance Terbaru</h1>
    <p class="section-intro">Temukan berbagai pekerjaan freelance dengan berbagai keahlian yang bisa Anda pilih.</p>

    <div class="card-columns">
    <?php foreach ($pekerjaan as $job): ?>
        <div class="card">
            <img src="<?= base_url('assets/images/' . $job['image_url']); ?>" class="card-img-top" alt="<?= $job['title']; ?>">
            <div class="card-body">
                <h5 class="card-title"><?= $job['title']; ?></h5>
                <p class="card-text"><?= substr($job['description'], 0, 100); ?>...</p>
                <p><strong>Status:</strong> 
                    <?php if ($job['status'] == 'closed'): ?>
                        <span class="badge bg-danger">Closed</span> 
                    <?php elseif ($job['status'] == 'completed'): ?>
                        <span class="badge bg-secondary">Completed</span>
                    <?php else: ?>
                        <span class="badge bg-success">Open</span>
                    <?php endif; ?>
                </p>
                
                <?php if ($job['status'] == 'open'): ?>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#jobDetailsModal" 
                            data-job-id="<?= $job['id']; ?>"
                            data-job-title="<?= $job['title']; ?>"
                            data-job-description="<?= $job['description']; ?>"
                            data-job-client="<?= $job['username']; ?>"
                            data-job-status="<?= $job['status']; ?>">
                        Pelajari Lebih Lanjut
                    </button>
                <?php else: ?>
                    <button class="btn btn-secondary" disabled>Pekerjaan Ditutup</button>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

</div>

<!-- Modal Detail Pekerjaan -->
<div class="modal fade" id="jobDetailsModal" tabindex="-1" aria-labelledby="jobDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="jobDetailsModalLabel">Detail Pekerjaan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h4 id="jobTitle"></h4>
                <p><strong>Deskripsi:</strong> <span id="jobDescription"></span></p>
                <p><strong>Client:</strong> <span id="jobClient"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="takeJobBtn">Ambil Pekerjaan Ini</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Pengambilan Pekerjaan -->
<div class="modal fade" id="confirmTakeJobModal" tabindex="-1" aria-labelledby="confirmTakeJobModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmTakeJobModalLabel">Konfirmasi Pengambilan Pekerjaan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin mengambil pekerjaan ini?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="confirmTakeJobBtn">Ya, Ambil Pekerjaan</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Toast untuk Notifikasi Pengambilan Pekerjaan -->
<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="takeJobToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto">Notifikasi</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            Pekerjaan berhasil diambil! Mohon menunggu konfirmasi dari klien.
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const jobDetailsModal = document.getElementById('jobDetailsModal');
    jobDetailsModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const jobId = button.getAttribute('data-job-id');
        const jobTitle = button.getAttribute('data-job-title');
        const jobDescription = button.getAttribute('data-job-description');
        const jobClient = button.getAttribute('data-job-client');
        const jobStatus = button.getAttribute('data-job-status'); // Ambil status pekerjaan

        // Set data modal
        document.getElementById('jobTitle').textContent = jobTitle;
        document.getElementById('jobDescription').textContent = jobDescription;
        document.getElementById('jobClient').textContent = jobClient;

        const takeJobBtn = document.getElementById('takeJobBtn');
        if (jobStatus == 'open') {
            takeJobBtn.disabled = false; // Tombol bisa diklik jika pekerjaan berstatus 'open'
            takeJobBtn.onclick = function() {
                const confirmTakeJobModal = new bootstrap.Modal(document.getElementById('confirmTakeJobModal'));
                confirmTakeJobModal.show();

                const confirmTakeJobBtn = document.getElementById('confirmTakeJobBtn');
                confirmTakeJobBtn.onclick = function() {
                    const xhr = new XMLHttpRequest();
                    xhr.open("POST", "<?= base_url('index.php/freelancer_dashboard/ambil_pekerjaan'); ?>", true);
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                    const freelancerId = '<?= $this->session->userdata('freelancer_id'); ?>';
                    const jobId = button.getAttribute('data-job-id');
                    
                    xhr.onreadystatechange = function () {
                        if (xhr.readyState == 4 && xhr.status == 200) {
                            const response = JSON.parse(xhr.responseText);
                            if (response.status === 'success') {
                                const toastElement = new bootstrap.Toast(document.getElementById('takeJobToast'));
                                toastElement.show();
                            } else {
                                alert(response.message);
                            }
                        }
                    };
                    xhr.send("job_id=" + jobId + "&freelancer_id=" + freelancerId);
                    confirmTakeJobModal.hide();
                };
            };
        } else {
            takeJobBtn.disabled = true; // Tombol dinonaktifkan jika pekerjaan tidak 'open'
        }
    });
});
</script>

</body>
</html>
