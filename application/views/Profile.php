<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pengguna</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #F0F8FF;
            font-family: 'Poppins', sans-serif;
        }

        .navbar {
            background-color: #B1F0F7;
        }

        .profile-img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
        }

        .profile-info {
            margin-top: 30px;
        }

        .profile-info h2 {
            font-size: 2rem;
            font-weight: bold;
        }

        .profile-info p {
            font-size: 1.2rem;
            color: #555;
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
                <?php if ($this->session->userdata('freelancer_id')): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('profile'); ?>">
                            <i class="bi bi-person-circle" style="font-size: 30px;"></i>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>



<div class="container mt-5 profile-info">
    <h2>Profil Pengguna</h2>
    <div class="card p-4">
        <div class="row">
            <div class="col-md-3 text-center">
                <!-- Gambar profil -->
                <img src="<?= base_url('assets/images/' . ($user['profile_picture'] ?? 'default.png')); ?>" class="profile-img" alt="Foto Profil">
            </div>
            <div class="col-md-9">
                <p><strong>Nama:</strong> <?= htmlspecialchars($user['username']); ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($user['email']); ?></p>
                <p><strong>Deskripsi:</strong> <?= htmlspecialchars($user['description'] ?? 'Belum ada deskripsi'); ?></p>
                <a href="<?= site_url('profile/edit'); ?>" class="btn btn-primary">Edit Profil</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
