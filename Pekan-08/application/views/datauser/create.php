<?php $title = "Tambah Data - CyberVault"; ?>
<?php $this->load->view('templates/header'); ?>

<div class="form-box" style="margin: 40px auto; max-width:600px;">
    <h2>Tambah Data Pengguna</h2>
    
    <?php if($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="nama" class="input" placeholder="Nama Lengkap" required>
        <input type="email" name="email" class="input" placeholder="Email" required>
        
        <label style="color:#8892b0; font-size:14px; margin:15px 0 8px; display:block;">
            Upload Foto Profil (opsional)
        </label>
        <input type="file" name="foto" class="input" accept="image/*">
        
        <button type="submit" class="btn-login">Simpan Data</button>
    </form>
    
    <div style="text-align:center; margin-top:20px;">
        <a href="<?= site_url('datauser') ?>" style="color:#00d4ff;">← Kembali ke Dashboard</a>
    </div>
</div>

<?php $this->load->view('templates/footer'); ?>