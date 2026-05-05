<?php // Header dipanggil agar halaman form tambah tetap punya layout dan style yang sama. ?>
<?php $this->load->view('templates/header'); ?>

<!-- Link ini untuk balik ke daftar post kalau user batal atau cuma mau lihat-lihat dulu. -->
<a href="<?php echo site_url('posts'); ?>" class="back-link btn btn-info">&larr; Back to List</a>

<!-- Card ini membungkus form tambah post supaya tampilannya tidak terlalu polos. -->
<div class="card">
    <h2>Create New Post</h2>
    <hr style="margin: 20px 0; border: none; border-top: 2px solid #e0e0e0;">

    <?php // Form multipart dipakai karena form ini bisa mengirim file gambar juga. ?>
    <?php echo form_open_multipart('posts/store'); ?>

        <!-- Input ini untuk judul post, dibuat wajib karena post tanpa judul agak membingungkan. -->
        <div class="form-group">
            <label for="title">Title <span style="color: red;">*</span></label>
            <input type="text" 
                   name="title" 
                   id="title" 
                   placeholder="Enter post title" 
                   required 
                   value="<?php echo set_value('title'); ?>">
        </div>

        <!-- Input author dipakai untuk mencatat siapa penulisnya. -->
        <div class="form-group">
            <label for="author">Author <span style="color: red;">*</span></label>
            <input type="text" 
                   name="author" 
                   id="author" 
                   placeholder="Enter author name" 
                   required 
                   value="<?php echo set_value('author'); ?>">
        </div>

        <!-- Textarea ini tempat user menulis isi artikel, jadi ukurannya dibuat lebih besar dari input biasa. -->
        <div class="form-group">
            <label for="article">Article <span style="color: red;">*</span></label>
            <textarea name="article" 
                      id="article" 
                      placeholder="Write your article here..." 
                      required><?php echo set_value('article'); ?></textarea>
        </div>

        <!-- Bagian upload gambar dibuat opsional, jadi post tetap bisa dibuat walau tanpa gambar. -->
        <div class="form-group">
            <label for="image">Image</label>
            <input type="file" 
                   name="image" 
                   id="image" 
                   accept="image/jpeg,image/jpg,image/png">
            <small style="display: block; margin-top: 5px; color: #666;">
                Allowed formats: JPG, JPEG, PNG. Maximum size: 2MB
            </small>
        </div>

        <!-- Tombol bawah ini untuk submit data atau membatalkan dan kembali ke daftar post. -->
        <div style="display: flex; gap: 10px; margin-top: 30px;">
            <button type="submit" class="btn btn-success">Create Post</button>
            <a href="<?php echo site_url('posts'); ?>" class="btn btn-danger">Cancel</a>
        </div>

    <?php echo form_close(); ?>
</div>

<?php // Footer ditaruh akhir karena ini penutup layout halaman. ?>
<?php $this->load->view('templates/footer'); ?>