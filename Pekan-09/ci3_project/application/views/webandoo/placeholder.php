<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?> - WeBandoo+</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f6f3ee; color: #2d3436; margin: 0; }
        .wrap { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 24px; }
        .card { max-width: 640px; background: #fff; padding: 32px; border-radius: 20px; box-shadow: 0 18px 45px rgba(0, 0, 0, 0.08); text-align: center; }
        .btn { display: inline-block; margin-top: 18px; padding: 12px 18px; border-radius: 10px; background: #2f855a; color: #fff; text-decoration: none; }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="card">
            <h1><?php echo htmlspecialchars($title); ?></h1>
            <p>Halaman ini sudah disiapkan di dalam CI3, tetapi konten detailnya belum dipindahkan dari tugas sebelumnya.</p>
            <a class="btn" href="<?php echo site_url('dashboard'); ?>">Kembali ke Dashboard</a>
        </div>
    </div>
</body>
</html>
