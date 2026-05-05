<?php // Header dimuat supaya halaman edit memakai tampilan yang sama seperti halaman lainnya. ?>
<?php $this->load->view('templates/header'); ?>

<!-- Link kembali ini dipakai kalau user tidak jadi mengedit post. -->
<a href="<?php echo site_url('posts'); ?>" class="back-link btn btn-info">&larr; Back to List</a>

<!-- Card ini menjadi wadah form edit supaya form tidak nempel langsung ke background. -->
<div class="card">
    <h2>Edit Post</h2>
    <hr style="margin: 20px 0; border: none; border-top: 2px solid #e0e0e0;">

    <?php // Form diarahkan ke posts/update dengan id post, jadi controller tahu data mana yang diubah. ?>
    <?php echo form_open_multipart('posts/update/' . $post->id); ?>

        <!-- Input judul sudah diisi data lama, tapi tetap bisa diganti oleh user. -->
        <div class="form-group">
            <label for="title">Title <span style="color: red;">*</span></label>
            <input type="text" 
                   name="title" 
                   id="title" 
                   placeholder="Enter post title" 
                   required 
                   value="<?php echo set_value('title', $post->title); ?>">
        </div>

        <!-- Input author juga membawa nilai lama supaya user tidak perlu mengetik ulang dari kosong. -->
        <div class="form-group">
            <label for="author">Author <span style="color: red;">*</span></label>
            <input type="text" 
                   name="author" 
                   id="author" 
                   placeholder="Enter author name" 
                   required 
                   value="<?php echo set_value('author', $post->author); ?>">
        </div>

        <!-- Isi artikel lama ditaruh di textarea agar bisa diedit langsung. -->
        <div class="form-group">
            <label for="article">Article <span style="color: red;">*</span></label>
            <textarea name="article" 
                      id="article" 
                      placeholder="Write your article here..." 
                      required><?php echo set_value('article', $post->article); ?></textarea>
        </div>

        <!-- Bagian gambar ini menampilkan gambar saat ini dan memberi pilihan upload gambar baru. -->
        <div class="form-group">
            <label for="image">Image</label>

            <?php if($post->image_url): ?>
                <!-- Kalau sebelumnya sudah ada gambar, gambar itu ditampilkan supaya user tahu yang sedang dipakai. -->
                <div style="margin-bottom: 10px;">
                    <p style="margin-bottom: 10px; font-weight: 500;">Current Image:</p>
                    <img src="<?php echo $post->image_url; ?>" 
                         alt="Current image" 
                         style="max-width: 200px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                </div>
            <?php endif; ?>

            <input type="file" 
                   name="image" 
                   id="image" 
                   accept="image/jpeg,image/jpg,image/png">
            <small style="display: block; margin-top: 5px; color: #666;">
                Allowed formats: JPG, JPEG, PNG. Maximum size: 2MB
                <?php if($post->image_url): ?>
                    <br>Leave empty to keep current image
                <?php endif; ?>
            </small>
        </div>

        <!-- Tombol ini untuk menyimpan perubahan atau batal kembali ke daftar. -->
        <div style="display: flex; gap: 10px; margin-top: 30px;">
            <button type="submit" class="btn btn-success">Update Post</button>
            <a href="<?php echo site_url('posts'); ?>" class="btn btn-danger">Cancel</a>
        </div>

    <?php echo form_close(); ?>
</div>

<?php // Footer ditaruh terakhir seperti pola view yang lain. ?>
<?php $this->load->view('templates/footer'); ?>