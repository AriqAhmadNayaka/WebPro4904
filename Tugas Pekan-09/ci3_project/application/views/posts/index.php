<?php $this->load->view('templates/header'); ?>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2>All Posts</h2>
        <a href="<?php echo site_url('posts/create'); ?>" class="btn btn-success">+ Create New Post</a>
    </div>

    <?php if (empty($posts)): ?>
        <div class="text-center" style="padding: 40px;">
            <p style="font-size: 18px; color: #666;">No posts found. Create your first post!</p>
            <a href="<?php echo site_url('posts/create'); ?>" class="btn btn-primary" style="margin-top: 20px;">Create Post</a>
        </div>
    <?php else: ?>
        <div style="display: grid; gap: 20px;">
            <?php foreach($posts as $post): ?>
                <div class="post-item">
                    <div style="display: flex; justify-content: space-between; align-items: start;">
                        <div style="flex: 1;">
                            <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                            <p style="color: #666; font-size: 14px; margin-bottom: 10px;">
                                <?php echo htmlspecialchars(substr($post['content'], 0, 150)); ?>
                                <?php echo strlen($post['content']) > 150 ? '...' : ''; ?>
                            </p>
                        </div>
                        <div class="post-actions" style="margin-left: 20px; flex-shrink: 0;">
                            <a href="<?php echo site_url('posts/show/'.$post['id']); ?>" class="btn btn-info">View</a>
                            <a href="<?php echo site_url('posts/edit/'.$post['id']); ?>" class="btn btn-warning">Edit</a>
                            <a href="<?php echo site_url('posts/delete/'.$post['id']); ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php $this->load->view('templates/footer'); ?>