<?php $this->load->view('posts/header'); ?>

<a href="http://localhost/WebPro4904/smartairquality" class="back-link btn btn-info">&larr; Back to List</a>

<div class="card">
    <h2>Buat laporan kondisi udara</h2>
    <hr style="margin: 20px 0; border: none; border-top: 2px solid #e0e0e0;">

    <?php echo form_open_multipart('posts/store'); ?>

        <div class="form-group">
            <label for="title">Lokasi stasiun <span style="color: red;">*</span></label>
            <input type="text"
                name="title"
                id="title"
                placeholder="Enter post title"
                required
                value="<?php echo set_value('title'); ?>">
        </div>

        <div class="form-group">
            <label for="author">Waktu pengukuran <span style="color: red;">*</span></label>
            <input type="text"
                name="author"
                id="author"
                placeholder="Enter author name"
                required
                value="<?php echo set_value('author'); ?>">
        </div>

        <div class="form-group">
            <label for="author">Skor ISPU <span style="color: red;">*</span></label>
            <input type="text"
                name="author"
                id="author"
                placeholder="Masukkan skor ISPU"
                required
                value="<?php echo set_value('author'); ?>">
        </div>

        <div class="form-group">
            <label for="article">Deskripsi <span style="color: red;">*</span></label>
            <textarea name="article"
                id="article"
                placeholder="Write your article here..."
                required><?php echo set_value('article'); ?></textarea>
        </div>

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

        <div style="display: flex; gap: 10px; margin-top: 30px;">
            <button type="submit" class="btn btn-success">Buat Laporan</button>
            <a href="<?php echo site_url('posts'); ?>" class="btn btn-danger">Cancel</a>
        </div>

    <?php echo form_close(); ?>
</div>

<?php $this->load->view('posts/footer'); ?>
