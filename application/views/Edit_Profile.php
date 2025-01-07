<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .profile-img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
        }

        .dropdown-item img {
            width: 30px;
            height: 30px;
            object-fit: cover;
            margin-right: 10px;
            border-radius: 50%;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2>Edit Profil Pengguna</h2>

    <!-- Menampilkan flash message -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success">
            <?= $this->session->flashdata('success'); ?>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger">
            <?= $this->session->flashdata('error'); ?>
        </div>
    <?php endif; ?>

    <form action="<?= site_url('profile/update'); ?>" method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Nama</label>
            <input type="text" class="form-control" id="name" name="name" value="<?= $user['username']; ?>" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password Baru</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password baru" required>
        </div>

        <div class="mb-3">
            <label for="confirm_password" class="form-label">Konfirmasi Password</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Konfirmasi password baru" required>
        </div>

        <div class="mb-3">
            <label for="profile_picture" class="form-label">Pilih Foto Profil</label>
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="<?= base_url('assets/images/' . ($user['profile_picture'] ?? 'default.png')); ?>" alt="Foto Profil" class="profile-img">
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <li>
                        <a class="dropdown-item" href="#">
                            <img src="<?= base_url('assets/images/default.png'); ?>" alt="Default" class="profile-img">
                            Default
                        </a>
                    </li>
                    <?php foreach ($images as $image): ?>
                        <li>
                            <a class="dropdown-item" href="#">
                                <img src="<?= base_url('assets/images/' . $image); ?>" alt="<?= $image ?>" class="profile-img">
                                <?= $image ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <input type="hidden" id="profile_picture" name="profile_picture" value="<?= $user['profile_picture'] ?? 'default.png'; ?>">
            </div>
        </div>

        <!-- Tombol Simpan Perubahan -->
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Update nilai input hidden ketika memilih gambar dari dropdown
    document.querySelectorAll('.dropdown-item').forEach(item => {
        item.addEventListener('click', function() {
            const selectedImage = this.querySelector('img').src.split('/').pop(); // Ambil nama file gambar
            document.getElementById('profile_picture').value = selectedImage;
            const previewImage = document.querySelector('.dropdown-toggle img');
            previewImage.src = this.querySelector('img').src; // Update gambar preview
        });
    });
</script>
</body>
</html>
