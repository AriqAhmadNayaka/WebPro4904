<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Orang Tua</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/dashboard-ortu.css') ?>">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
</head>
<body>
<div class="sidebar">
    <h2 class="logo">InkluSkill</h2>

    <a href="<?= site_url('dashboard-ortu') ?>" class="active"><i class="ri-dashboard-line"></i>Dashboard</a>
    <a href="<?= site_url('data-anak') ?>"><i class="ri-user-3-line"></i>Data Anak</a>
</div>

<div class="content">
    <div class="header">
        <div>
            <span class="eyebrow">Dashboard Orang Tua</span>
            <h1><i class="ri-bar-chart-fill"></i> Statistik Anak</h1>
            <p><?= html_escape($userName) ?>, ringkasan perkembangan dan aktivitas anak Anda</p>
        </div>
        <div class="header-badge">
            <i class="ri-heart-pulse-line"></i>
            <span>Pantau progres harian</span>
        </div>
    </div>

    <?php if ($photo !== ''): ?>
        <div class="big-card hero-card hero-photo">
            <div class="hero-copy">
                <span class="hero-chip">Ringkasan</span>
                <h3><?= html_escape($summaryTitle) ?></h3>
                <p><?= html_escape($description) ?></p>
            </div>
            <div class="hero-media">
                <img src="<?= base_url($photo) ?>" alt="Foto Anak">
            </div>
        </div>
    <?php else: ?>
        <div class="big-card hero-card">
            <div class="hero-copy">
                <span class="hero-chip">Ringkasan</span>
                <h3><?= html_escape($summaryTitle) ?></h3>
                <p><?= html_escape($description) ?></p>
            </div>
            <div class="hero-media">
                <div class="hero-avatar"><i class="ri-user-smile-line"></i></div>
            </div>
        </div>
    <?php endif; ?>

    <div class="stat-row">
        <div class="stat-card purple">
            <i class="ri-user-3-line"></i>
            <div>
                <p>Nama Anak</p>
                <h2><?= html_escape($childName) ?></h2>
            </div>
        </div>

        <div class="stat-card green">
            <i class="ri-calendar-check-line"></i>
            <div>
                <p>Jadwal Terdekat</p>
                <h2><?= html_escape($nextSchedule) ?></h2>
            </div>
        </div>

        <div class="stat-card red">
            <i class="ri-bar-chart-box-line"></i>
            <div>
                <p>Progres</p>
                <h2><?= (int) $progress ?>%</h2>
            </div>
        </div>
    </div>

    <div class="big-card">
        <h3>Perkembangan Anak</h3>
        <p class="section-copy">Ringkasan capaian utama untuk memantau perkembangan anak dari waktu ke waktu.</p>

        <div class="progress-item">
            <div class="progress-head">
                <span>Komunikasi</span>
                <strong><?= (int) $communication ?>%</strong>
            </div>
            <div class="progress-bar"><div class="fill" style="width: <?= (int) $communication ?>%"></div></div>
        </div>

        <div class="progress-item">
            <div class="progress-head">
                <span>Kemandirian</span>
                <strong><?= (int) $independence ?>%</strong>
            </div>
            <div class="progress-bar"><div class="fill green-fill" style="width: <?= (int) $independence ?>%"></div></div>
        </div>

        <div class="progress-item">
            <div class="progress-head">
                <span>Keterampilan Vokasional</span>
                <strong><?= (int) $vocational ?>%</strong>
            </div>
            <div class="progress-bar"><div class="fill red-fill" style="width: <?= (int) $vocational ?>%"></div></div>
        </div>
    </div>

    <div class="big-card">
        <h3>Jadwal Mingguan</h3>
        <p class="section-copy">Agenda pelatihan yang akan membantu orang tua memantau aktivitas terdekat.</p>
        <div class="jadwal-list">
            <?php if (empty($schedules)): ?>
                <div class="empty-state">
                    <i class="ri-calendar-close-line"></i>
                    <p>Belum ada jadwal yang tersimpan.</p>
                </div>
            <?php else: ?>
                <?php foreach ($schedules as $schedule): ?>
                    <div class="jadwal-item">
                        <i class="ri-calendar-event-line"></i>
                        <div class="jadwal-text">
                            <b><?= html_escape(isset($schedule['hari']) ? $schedule['hari'] : '-') ?></b><br>
                            <span><?= html_escape(isset($schedule['kegiatan']) ? $schedule['kegiatan'] : '-') ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
