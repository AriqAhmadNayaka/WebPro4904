<?php
// Head berisi title, font, icon, dan stylesheet utama aplikasi.
$this->load->view('partials/head', array('title' => $title));
?>
<div class="app-shell">
    <?php
    // Sidebar menampilkan menu navigasi utama aplikasi.
    $this->load->view('partials/sidebar');
    ?>

    <main class="main-content">
        <header class="page-header">
            <div>
                <p class="eyebrow">Dashboard</p>
                <h1>Ringkasan aplikasi peserta pelatihan</h1>
                <p class="header-subtitle">Halaman ini hanya muncul setelah login berhasil.</p>
            </div>
            <div class="profile-chip">
                <span class="profile-avatar"><?php echo strtoupper(substr($this->session->userdata('username'), 0, 1)); ?></span>
                <div>
                    <strong><?php echo html_escape($this->session->userdata('username')); ?></strong>
                    <small><?php echo html_escape($this->session->userdata('email')); ?></small>
                </div>
            </div>
        </header>

        <?php
        // Menampilkan notifikasi session jika ada.
        $this->load->view('partials/alerts');
        ?>

        <section class="stats-grid">
            <article class="stat-card">
                <span class="stat-icon"><i class="ri-group-line"></i></span>
                <h2><?php echo $stats['total']; ?></h2>
                <p>Total peserta terdaftar</p>
            </article>
            <article class="stat-card">
                <span class="stat-icon"><i class="ri-women-line"></i></span>
                <h2><?php echo $stats['female']; ?></h2>
                <p>Peserta perempuan</p>
            </article>
            <article class="stat-card">
                <span class="stat-icon"><i class="ri-men-line"></i></span>
                <h2><?php echo $stats['male']; ?></h2>
                <p>Peserta laki-laki</p>
            </article>
            <article class="stat-card">
                <span class="stat-icon"><i class="ri-book-open-line"></i></span>
                <h2><?php echo $stats['training_count']; ?></h2>
                <p>Jenis pelatihan aktif</p>
            </article>
        </section>

        <section class="panel">
            <div class="panel-heading">
                <div>
                    <h2>Peserta terbaru</h2>
                    <p>Ringkasan data dari fitur CRUD peserta.</p>
                </div>
                <a href="<?php echo site_url('peserta'); ?>" class="btn-primary">Kelola Peserta</a>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Usia</th>
                            <th>Jenis Kelamin</th>
                            <th>Pelatihan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($participants): ?>
                            <?php foreach (array_slice($participants, 0, 5) as $index => $participant): ?>
                                <tr>
                                    <td><?php echo $index + 1; ?></td>
                                    <td><?php echo html_escape($participant->nama); ?></td>
                                    <td><?php echo (int) $participant->umur; ?></td>
                                    <td><?php echo html_escape($participant->jenis_kelamin); ?></td>
                                    <td><?php echo html_escape($participant->pelatihan); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5">Belum ada peserta yang tersimpan.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</div>
<?php
// Footer memuat script yang diperlukan di akhir halaman.
$this->load->view('partials/footer');
?>
