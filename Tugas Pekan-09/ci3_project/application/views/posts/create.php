<?php $this->load->view('templates/header'); ?>

<a href="<?php echo site_url('posts'); ?>" class="back-link btn btn-info">&larr; Back to List</a>

<div class="card">
    <h2>Create New Post</h2>
    <hr style="margin: 20px 0; border: none; border-top: 2px solid #e0e0e0;">

    <form method="post" action="<?php echo site_url('posts/store'); ?>">
        <div class="form-group">
            <label for="title">Title <span style="color: red;">*</span></label>
            <input type="text" name="title" id="title" placeholder="Enter post title" required>
        </div>

        <div class="form-group">
            <label for="content">Content <span style="color: red;">*</span></label>
            <textarea name="content" id="content" placeholder="Write your content here..." required></textarea>
        </div>

        <div style="display: flex; gap: 10px; margin-top: 30px;">
            <button type="submit" class="btn btn-success">Create Post</button>
            <a href="<?php echo site_url('posts'); ?>" class="btn btn-danger">Cancel</a>
        </div>
    </form>
</div>

<?php $this->load->view('templates/footer'); ?>