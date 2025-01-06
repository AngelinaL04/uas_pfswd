<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inbox - Client Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .notification {
            padding: 10px;
            margin-bottom: 15px;
            background-color: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        table th, table td {
            vertical-align: middle;
        }
    </style>
</head>
<body>

<!-- Container -->
<div class="container mt-5">
    <!-- Tombol Kembali -->
    <a href="<?= site_url('client_dashboard'); ?>" class="btn btn-secondary mb-3">Kembali ke Dashboard</a>

    <h2>Inbox Anda</h2>

    <!-- Tabel Notifikasi -->
    <?php if (empty($notifications)): ?>
    <div class="alert alert-info" role="alert">
        Belum ada notifikasi baru.
    </div>
    <?php else: ?>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID Freelancer</th>
                    <th>ID Pekerjaan</th>
                    <th>Status</th>
                    <th>Diterima/Tidak</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($notifications as $notification): ?>
                    <tr>
                        <td><?= $notification['freelancer_id']; ?></td>
                        <td><?= $notification['job_id']; ?></td>
                        <td><?= $notification['status']; ?></td>
                        <td>
                            <!-- Form untuk memperbarui status -->
                            <form action="<?= site_url('client_dashboard/update_notification_status'); ?>" method="POST">
                                <input type="hidden" name="notification_id" value="<?= $notification['id']; ?>">
                                <input type="hidden" name="freelancer_id" value="<?= $notification['freelancer_id']; ?>">
                                <input type="hidden" name="job_id" value="<?= $notification['job_id']; ?>">
                                <select class="form-select" name="status" <?= ($notification['is_accepted'] == 'Diterima') ? 'disabled' : ''; ?>>
                                    <option value="Menunggu" <?= ($notification['is_accepted'] == 'Menunggu') ? 'selected' : ''; ?>>Menunggu</option>
                                    <option value="Diterima" <?= ($notification['is_accepted'] == 'Diterima') ? 'selected' : ''; ?>>Diterima</option>
                                    <option value="Tidak diterima" <?= ($notification['is_accepted'] == 'Tidak diterima') ? 'selected' : ''; ?>>Tidak diterima</option>
                                </select>
                                <!-- Tombol Submit dalam form yang sama -->
                                <button type="submit" class="btn btn-primary btn-sm" <?= ($notification['is_accepted'] == 'Diterima') ? 'disabled' : ''; ?>>Perbarui Status</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
