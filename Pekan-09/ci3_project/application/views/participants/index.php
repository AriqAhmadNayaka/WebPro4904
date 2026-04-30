<?php
// Partial head dipakai ulang agar struktur halaman konsisten.
$this->load->view('partials/head', array('title' => $title));
?>
<div class="app-shell">
    <?php
    // Sidebar berisi menu dashboard, peserta, dan logout.
    $this->load->view('partials/sidebar');
    ?>

    <main class="main-content">
        <header class="page-header">
            <div>
                <p class="eyebrow">CRUD Peserta</p>
                <h1>Kelola peserta pelatihan</h1>
                <p class="header-subtitle">Tombol <strong>Simpan</strong> menangani create/update dan upload foto dalam satu submit.</p>
            </div>
            <button type="button" class="btn-primary" id="openModal">
                <i class="ri-add-circle-line"></i> Tambah Peserta
            </button>
        </header>

        <?php
        // Menampilkan pesan proses CRUD dan upload foto.
        $this->load->view('partials/alerts');
        ?>

        <section class="panel">
            <div class="panel-heading">
                <div>
                    <h2>Data peserta</h2>
                    <p>Cari, tambah, ubah, dan hapus data peserta dari satu halaman.</p>
                </div>
                <input type="text" id="searchPeserta" class="search-box" placeholder="Cari nama peserta...">
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
                            <th>Foto</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="pesertaTable">
                        <?php if ($participants): ?>
                            <?php foreach ($participants as $index => $participant): ?>
                                <tr>
                                    <td><?php echo $index + 1; ?></td>
                                    <td><?php echo html_escape($participant->nama); ?></td>
                                    <td><?php echo (int) $participant->umur; ?></td>
                                    <td><?php echo html_escape($participant->jenis_kelamin); ?></td>
                                    <td><?php echo html_escape($participant->pelatihan); ?></td>
                                    <td>
                                        <img class="thumb" src="<?php echo base_url('uploads/' . rawurlencode($participant->foto)); ?>" alt="Foto peserta">
                                    </td>
                                    <td class="action-cell">
                                        <button
                                            type="button"
                                            class="btn-soft"
                                            data-id="<?php echo $participant->id; ?>"
                                            data-nama="<?php echo html_escape($participant->nama); ?>"
                                            data-umur="<?php echo (int) $participant->umur; ?>"
                                            data-jk="<?php echo html_escape($participant->jenis_kelamin); ?>"
                                            data-pelatihan="<?php echo html_escape($participant->pelatihan); ?>"
                                            data-foto="<?php echo html_escape($participant->foto); ?>"
                                            onclick="editPeserta(this)"
                                        >
                                            Edit
                                        </button>
                                        <a href="<?php echo site_url('peserta/hapus/' . $participant->id); ?>" class="btn-danger" onclick="return confirm('Hapus data peserta ini?');">Hapus</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7">Belum ada data peserta.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</div>

<div class="modal" id="modalForm">
    <div class="modal-content">
        <div class="modal-header">
            <div>
                <p class="eyebrow">Form Peserta</p>
                <h3 id="modalTitle">Tambah Peserta</h3>
            </div>
            <button type="button" class="icon-button" id="closeModal"><i class="ri-close-line"></i></button>
        </div>

        <?php
        // Form multipart dipakai agar data peserta dan file foto bisa dikirim bersama.
        echo form_open_multipart('peserta/simpan', array('id' => 'pesertaForm'));
        ?>
            <input type="hidden" name="id" id="idInput" value="">
            <input type="hidden" name="foto_lama" id="fotoLamaInput" value="">

            <label>Nama Peserta</label>
            <input type="text" name="nama" id="namaInput" required>

            <label>Usia</label>
            <input type="number" name="umur" id="umurInput" min="1" required>

            <label>Jenis Kelamin</label>
            <select name="jenis_kelamin" id="genderInput" required>
                <option value="Laki-laki">Laki-laki</option>
                <option value="Perempuan">Perempuan</option>
            </select>

            <label>Pelatihan</label>
            <select name="pelatihan" id="pelatihanInput" required>
                <?php foreach ($trainings as $training): ?>
                    <option value="<?php echo html_escape($training); ?>"><?php echo html_escape($training); ?></option>
                <?php endforeach; ?>
            </select>

            <label>Upload Foto</label>
            <input type="file" name="foto" id="fotoInput" accept=".jpg,.jpeg,.png,.gif,.webp">
            <p id="fotoInfo" class="foto-info">Foto peserta wajib saat tambah data.</p>

            <div class="modal-buttons">
                <button type="submit" class="btn-primary">Simpan</button>
                <button type="button" class="btn-secondary" id="cancelModal">Batal</button>
            </div>
        <?php echo form_close(); ?>
    </div>
</div>
<?php
// Footer menutup halaman dan memanggil JavaScript peserta.
$this->load->view('partials/footer');
?>
