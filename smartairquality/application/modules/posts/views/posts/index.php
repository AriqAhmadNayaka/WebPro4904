<?php $this->load->view('posts/header'); ?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2>All Posts</h2>
</div>

<?php if (!empty($posts)): ?>
    <table>
        <thead>
            <tr style="background: #667eea; color: white;">
                <th style="padding: 15px;">No</th>
                <th style="padding: 15px;">Image</th>
                <th style="padding: 15px;">Title</th>
                <th style="padding: 15px;">Author</th>
                <th style="padding: 15px;">Article</th>
                <th style="padding: 15px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            <?php foreach ($posts as $post): ?>
                <tr style="border-bottom: 1px solid #e0e0e0;">
                    <td style="padding: 15px; text-align: center;"><?php echo $no++; ?></td>
                    <td style="padding: 15px; text-align: center;">
                        <?php if (!empty($post->image_url)): ?>
                            <img src="<?php echo $post->image_url; ?>" alt="<?php echo htmlspecialchars($post->title); ?>" class="post-image">
                        <?php else: ?>
                            <span style="color: #888;">No image</span>
                        <?php endif; ?>
                    </td>
                    <td style="padding: 15px;"><?php echo htmlspecialchars($post->title); ?></td>
                    <td style="padding: 15px;"><?php echo htmlspecialchars($post->author); ?></td>
                    <td style="padding: 15px;">
                        <div class="article-preview">
                            <?php echo htmlspecialchars(substr($post->article, 0, 100)); ?>
                            <?php echo strlen($post->article) > 100 ? '...' : ''; ?>
                        </div>
                    </td>
                    <td style="padding: 15px; text-align: center;">
                        <a href="<?php echo site_url('posts/show/' . $post->id); ?>" class="btn btn-info">View</a>
                        <a href="<?php echo site_url('posts/edit/' . $post->id); ?>" class="btn btn-warning">Edit</a>
                        <a href="<?php echo site_url('posts/delete/' . $post->id); ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <div class="card text-center">
        <h3 style="margin-bottom: 10px; color: #333;">No posts yet</h3>
        <p style="margin-bottom: 20px; color: #666;">Data post masih kosong, jadi tabel CRUD belum muncul.</p>
        <a href="http://localhost/WebPro4904/smartairquality/index.php/posts/create" class="btn btn-success">Create First Post</a>
    </div>
<?php endif; ?>

<?php $this->load->view('posts/footer'); ?>
