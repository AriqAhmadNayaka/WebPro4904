<?php $this->load->view('templates/header'); ?>

<a href="<?php echo site_url('posts'); ?>" class="back-link btn btn-info">&larr; Back to List</a>

<div class="card">
    <h2>Edit Post</h2>
    <hr style="margin: 20px 0; border: none; border-top: 2px solid #e0e0e0;">

    <form method="post" action="<?php echo site_url('posts/update/'.$post['id']); ?>">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>
        </div>

        <div class="form-group">
            <label for="content">Content</label>
            <textarea name="content" id="content" required><?php echo htmlspecialchars($post['content']); ?></textarea>
        </div>

        <div style="display: flex; gap: 10px; margin-top: 30px;">
            <button type="submit" class="btn btn-success">Update Post</button>
            <a href="<?php echo site_url('posts'); ?>" class="btn btn-danger">Cancel</a>
        </div>
    </form>
</div>

<?php $this->load->view('templates/footer'); ?>