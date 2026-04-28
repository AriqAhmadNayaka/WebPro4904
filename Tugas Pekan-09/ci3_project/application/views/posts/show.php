<?php $this->load->view('templates/header'); ?>

<a href="<?php echo site_url('posts'); ?>" class="back-link btn btn-info">&larr; Back to List</a>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2><?php echo htmlspecialchars($post['title']); ?></h2>
        <div class="actions">
            <a href="<?php echo site_url('posts/edit/'.$post['id']); ?>" class="btn btn-warning">Edit</a>
            <a href="<?php echo site_url('posts/delete/'.$post['id']); ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
        </div>
    </div>

    <hr style="margin: 20px 0; border: none; border-top: 2px solid #e0e0e0;">

    <div style="margin-top: 30px;">
        <h3 style="margin-bottom: 15px; color: #333;">Content</h3>
        <div style="line-height: 1.8; color: #555; white-space: pre-wrap;">
            <?php echo nl2br(htmlspecialchars($post['content'])); ?>
        </div>
    </div>
</div>

<?php $this->load->view('templates/footer'); ?>