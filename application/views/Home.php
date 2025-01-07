<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #81BFDA;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 600;
        }

        .nav-link {
            font-size: 1rem;
            font-weight: 500;
        }

        h1, h2 {
            font-weight: 600;
        }

        h1 {
            font-size: 2.5rem;
        }

        h2 {
            font-size: 2rem;
            color: #F5F0CD;
        }

        p {
            color: #F5F0CD;
            max-width: 800px;
            margin: 0 auto;
            line-height: 1.8;
        }

        a.nav-link:hover {
            color: #0056b3;
        }

        .image-container {
            position: relative;
            width: 100%;
            height: 400px;
        }

        .image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg" style="background-color: #B1F0F7;">
    <div class="container">
        <a class="navbar-brand" href="<?= site_url('home'); ?>">FreelanceGo</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
            <a class="nav-link" href="<?= site_url('home'); ?>">Home</a>
            <a class="nav-link" href="<?= site_url('signin'); ?>">Pekerjaan</a>
            <a class="nav-link" href="<?= site_url('about'); ?>">About</a>
            <a class="nav-link" href="<?= site_url('signin'); ?>">Sign In</a>
            <a class="nav-link" href="<?= site_url('signup'); ?>">Sign Up</a>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-12 image-container">
            <!-- Gambar di sini, bisa diganti dengan gambar apapun -->
            <img src="<?= base_url('assets/images/logo_aplikasi.png'); ?>" alt="Ilustrasi Web Service">
        </div>
    </div>
</div>

<div class="container mt-4">
    <div class="text-center">
        <h2>Marketplace Jasa Freelancer</h2>
        <p>
            Aplikasi berbasis web ini dirancang untuk menghubungkan freelancer dengan calon klien yang membutuhkan jasa mereka. 
            Dengan fitur pencarian pekerjaan, manajemen proyek, dan komunikasi yang efisien, platform ini mempermudah freelancer 
            dalam menemukan peluang kerja serta membantu klien menemukan tenaga ahli yang sesuai untuk kebutuhan proyek mereka.
        </p>
    </div>
</div>

<div class="container mt-5">
    <h2 class="text-center">Apa Kata Pengguna?</h2>
    <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active text-center">
                <p>"FreelanceGo mempermudah saya menemukan proyek sesuai keahlian. Sangat membantu!"</p>
                <h5>- John Doe, Freelancer</h5>
            </div>
            <div class="carousel-item text-center">
                <p>"Klien berkualitas dan sistemnya sangat mudah digunakan. Luar biasa!"</p>
                <h5>- Jane Smith, Freelancer</h5>
            </div>
            <div class="carousel-item text-center">
                <p>"Tim FreelanceGo sangat responsif. Saya puas dengan pelayanannya."</p>
                <h5>- Alan Walker, Klien</h5>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
        </button>
    </div>
</div>

<div class="container mt-5 text-center">
    <h2>Bergabunglah Sekarang!</h2>
    <p>Jangan lewatkan kesempatan untuk menemukan proyek atau freelancer terbaik.</p>
    <a href="<?= site_url('signup'); ?>" class="btn btn-primary">Gabung Sekarang</a>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
