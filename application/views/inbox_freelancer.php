<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inbox - Freelancer Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .notification {
            padding: 10px;
            margin-bottom: 15px;
            background-color: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .notification-unread {
            background-color: #e0f7fa; /* Warna untuk notifikasi yang belum dibaca */
        }

        .notification-read {
            background-color: #ffffff; /* Warna untuk notifikasi yang sudah dibaca */
        }

        .notification-message {
            font-size: 1.1rem;
            color: #333;
        }

        .notification-time {
            font-size: 0.8rem;
            color: #888;
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
    <a href="<?= site_url('freelancer_dashboard'); ?>" class="btn btn-secondary mb-3">Kembali ke Dashboard</a>

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
                        <td>
                        <select class="form-select status-dropdown" data-notification-id="<?= $notification['id']; ?>" data-freelancer-id="<?= $notification['freelancer_id']; ?>" data-job-id="<?= $notification['job_id']; ?>" data-accepted="<?= $notification['is_accepted']; ?>">
                            <option value="New" <?= ($notification['status'] == 'New') ? 'selected' : ''; ?>>New</option>
                            <option value="Completed" <?= ($notification['status'] == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                        </select>
                        </td>
                        <td><?= $notification['is_accepted']; ?></td>
                        <td>
                            <button class="btn btn-primary btn-sm update-status-btn" data-notification-id="<?= $notification['id']; ?>">Perbarui Status</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    // Fungsi untuk memperbarui status notifikasi
    $(document).on('click', '.update-status-btn', function() {
        var button = $(this);
        var notificationId = button.data('notification-id');
        var freelancerId = button.closest('tr').find('.status-dropdown').data('freelancer-id');
        var jobId = button.closest('tr').find('.status-dropdown').data('job-id');
        var newStatus = button.closest('tr').find('.status-dropdown').val(); // Ambil status yang dipilih
        var isAccepted = button.closest('tr').find('.status-dropdown').data('accepted'); // Ambil status diterima

        // Pastikan freelancer hanya bisa mengubah status jika proyek diterima
        if (newStatus === 'Completed' && isAccepted !== 'Diterima') {
            alert('Anda hanya bisa mengubah status menjadi "Completed" jika proyek sudah diterima oleh klien.');
            return; // Hentikan eksekusi jika belum diterima
        }

        // Debug: Pastikan status yang dipilih benar
        console.log('Status yang dipilih:', newStatus);

        // Kirim AJAX untuk memperbarui status
        $.ajax({
            url: '<?= site_url("freelancer_dashboard/update_notification_status"); ?>',
            method: 'POST',
            data: {
                notification_id: notificationId,
                freelancer_id: freelancerId,
                job_id: jobId,
                status: newStatus  // Pastikan newStatus adalah salah satu dari 'New' atau 'Completed'
            },
            success: function(response) {
                var data = JSON.parse(response);
                alert(data.message); // Menampilkan pesan keberhasilan atau kegagalan
                if (data.status == 'success') {
                    // Update status di halaman setelah sukses
                    button.closest('tr').find('.status-dropdown').val(newStatus); // Update status dropdown
                }
            },
            error: function() {
                alert('Gagal memperbarui status.');
            }
        });
    });
</script>

</body>
</html>
