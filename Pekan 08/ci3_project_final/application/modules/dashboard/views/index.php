<?php $this->load->view('template/header', $data); ?>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card primary">
            <div class="stat-icon">
                <i class="fas fa-folder-open"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value" data-count="<?= $total_proyek ?>"><?= $total_proyek ?></div>
                <div class="stat-label">Total Proyek</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card success">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value" data-count="<?= $total_users ?>"><?= $total_users ?></div>
                <div class="stat-label">Total Pengguna</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card warning">
            <div class="stat-icon">
                <i class="fas fa-code-branch"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">CI3</div>
                <div class="stat-label">Framework</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card info">
            <div class="stat-icon">
                <i class="fas fa-layer-group"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">MVC</div>
                <div class="stat-label">Arsitektur</div>
            </div>
        </div>
    </div>
</div>

<!-- Info Card Perbandingan -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title">
            <i class="fas fa-exchange-alt"></i>
            Perbandingan: Pekan8 (Prosedural) vs CI3 (MVC)
        </h5>
    </div>
    <div class="card-body">
        <div class="table-wrapper">
            <table class="table table-bordered" style="font-size:13.5px">
                <thead class="table-light">
                    <tr>
                        <th width="160">Aspek</th>
                        <th>Pekan8 (Prosedural/OOP Manual)</th>
                        <th>CI3 MVC (Proyek Ini)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="fw-600">Koneksi DB</td>
                        <td><code>Database.php</code> Singleton + mysqli manual</td>
                        <td><code>$this->db</code> — CI3 Query Builder otomatis</td>
                    </tr>
                    <tr>
                        <td class="fw-600">Login/Register</td>
                        <td><code>login.php</code> — logika + HTML campur</td>
                        <td>Controller <code>Auth.php</code> + Model <code>Auth_model</code> + View <code>login.php</code></td>
                    </tr>
                    <tr>
                        <td class="fw-600">CRUD Proyek</td>
                        <td><code>index.php</code> — semua aksi + tabel dalam satu file</td>
                        <td>Controller <code>Proyek.php</code> + Model <code>Proyek_model</code> + Views terpisah</td>
                    </tr>
                    <tr>
                        <td class="fw-600">Upload File</td>
                        <td><code>move_uploaded_file()</code> manual</td>
                        <td>CI3 Upload Library (validasi tipe, ukuran)</td>
                    </tr>
                    <tr>
                        <td class="fw-600">Session</td>
                        <td><code>$_SESSION['login']</code> native PHP</td>
                        <td><code>$this->session</code> CI3 Session Library</td>
                    </tr>
                    <tr>
                        <td class="fw-600">Routing</td>
                        <td>Langsung via URL file (<code>index.php, login.php</code>)</td>
                        <td><code>config/routes.php</code> → <code>module/controller/method</code></td>
                    </tr>
                    <tr>
                        <td class="fw-600">Keamanan Input</td>
                        <td><code>mysqli_real_escape_string()</code> manual</td>
                        <td><code>$this->input->post('', TRUE)</code> — XSS filter CI3</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="text-end mt-3">
    <a href="<?= site_url('proyek') ?>" class="btn btn-primary">
        <i class="fas fa-folder-open me-1"></i> Lihat Data Proyek
    </a>
</div>

<?php $this->load->view('template/footer'); ?>
