<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'Posts Application'; ?> - CI3 CRUD</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        header {
            background: linear-gradient(135deg, #5cb85c, #8bc34a);
            color: white;
            padding: 30px 0;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        header h1 {
            text-align: center;
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        header p {
            text-align: center;
            font-size: 1.1em;
            opacity: 0.9;
        }

        .nav {
            background: white;
            padding: 15px 0;
            margin-bottom: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border-radius: 8px;
        }

        .nav ul {
            list-style: none;
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .nav a {
            color: #000000;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            transition: all 0.3s;
            font-weight: 500;
        }

        .nav a:hover {
            background: #5cb85c;
            color: white;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-success {
            background-color: #d4edda;
            border-left: 4px solid #28a745;
            color: #155724;
        }

        .alert-error {
            background-color: #f8d7da;
            border-left: 4px solid #dc3545;
            color: #721c24;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
            border: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-primary {
            background: linear-gradient(135deg, #5cb85c, #8bc34a);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 255, 115, 0.4);
        }

        .btn-success {
            background-color: #28a745;
            color: white;
        }

        .btn-success:hover {
            background-color: #218838;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(40, 167, 69, 0.4);
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .btn-warning {
            background-color: #ffc107;
            color: #212529;
        }

        .btn-warning:hover {
            background-color: #e0a800;
        }

        .btn-info {
            background-color: #17a2b8;
            color: white;
        }

        .btn-info:hover {
            background-color: #138496;
        }

        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 30px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        .form-group input[type="text"],
        .form-group textarea,
        .form-group input[type="file"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .form-group input[type="text"]:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
        }

        .form-group textarea {
            min-height: 150px;
            resize: vertical;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .post-image {
            max-width: 100px;
            height: auto;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .post-detail-image {
            max-width: 500px;
            width: 100%;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin: 20px 0;
        }

        .text-center {
            text-align: center;
        }

        .mb-20 {
            margin-bottom: 20px;
        }

        .article-preview {
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        footer {
            background: #333;
            color: white;
            text-align: center;
            padding: 20px 0;
            margin-top: 50px;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>EcoTaste</h1>
            <p>CodeIgniter 3 CRUD Application</p>
        </div>
    </header>

    <div class="container">
        <div class="nav">
            <ul>
                <li><a href="<?php echo base_url(); ?>">Home</a></li>
                <li><a href="<?php echo base_url('posts'); ?>">All Posts</a></li>
                <li><a href="<?php echo base_url('posts/create'); ?>">Tambah Jenis Makanan</a></li>
            </ul>
        </div>

        <!-- <?php if($this->session->flashdata('success')): ?>
            <div class="alert alert-success">
                ✓ <?php echo $this->session->flashdata('success'); ?>
            </div>
        <?php endif; ?> -->

        <!-- <?php if($this->session->flashdata('error')): ?>
            <div class="alert alert-error">
                X <?php echo $this->session->flashdata('error'); ?>
            </div>
        <?php endif; ?> -->

        <!-- Posts Table Section -->
        <div class="card mb-20">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 style="color: #333; margin: 0;"><?php echo isset($title) ? $title : 'All Posts'; ?></h2>
                <a href="<?php echo base_url('posts/create'); ?>" class="btn btn-primary">Create New Post</a>
            </div>

            <?php if (isset($posts) && count($posts) > 0): ?>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr style="background: linear-gradient(135deg, #5cb85c, #8bc34a); color: white;">
                                <th style="padding: 15px 12px; text-align: left;">Image</th>
                                <th style="padding: 15px 12px; text-align: left;">Title</th>
                                <th style="padding: 15px 12px; text-align: left;">Author</th>
                                <th style="padding: 15px 12px; text-align: left;">Article Preview</th>
                                <th style="padding: 15px 12px; text-align: left;">Created</th>
                                <th style="padding: 15px 12px; text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($posts as $post): ?>
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="padding: 15px 12px; width: 80px;">
                                    <?php if (isset($post->image_url) && $post->image_url): ?>
                                        <img src="<?php echo $post->image_url; ?>" alt="Post image" class="post-image">
                                    <?php else: ?>
                                        <div style="width: 60px; height: 60px; background: #f0f0f0; border-radius: 5px; display: flex; align-items: center; justify-content: center; color: #999; font-size: 12px;">No Image</div>
                                    <?php endif; ?>
                                </td>
                                <td style="padding: 15px 12px; font-weight: 600; max-width: 250px;"><?php echo htmlspecialchars($post->title); ?></td>
                                <td style="padding: 15px 12px; color: #666;"><?php echo htmlspecialchars($post->author); ?></td>
                                <td style="padding: 15px 12px;">
                                    <div class="article-preview"><?php echo substr(strip_tags($post->article), 0, 100); ?>...</div>
                                </td>
                                <td style="padding: 15px 12px; color: #888; font-size: 14px; white-space: nowrap;">
                                    <?php echo date('M j, Y', strtotime($post->created_at)); ?>
                                </td>
                                <td style="padding: 15px 12px; text-align: center;">
                                    <div style="display: flex; gap: 8px; justify-content: center;">
                                        <a href="<?php echo base_url('posts/show/' . $post->id); ?>" class="btn btn-info" style="padding: 6px 12px; font-size: 12px;">✏️ Edit</a>
                                        <!-- <a href="<?php echo base_url('posts/edit/' . $post->id); ?>" class="btn btn-warning" style="padding: 6px 12px; font-size: 12px;">✏️ Edit</a> -->
                                        <a href="<?php echo base_url('posts/delete/' . $post->id); ?>" 
                                           class="btn btn-danger" 
                                           style="padding: 6px 12px; font-size: 12px;"
                                           onclick="return confirm('Are you sure you want to delete this post?')">🗑️ Delete</a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div style="text-align: center; padding: 60px 20px; color: #888;">
                    <h3 style="margin-bottom: 10px;">📭 No posts yet</h3>
                    <p style="margin-bottom: 20px;">Get started by creating your first post.</p>
                    <a href="<?php echo base_url('posts/create'); ?>" class="btn btn-primary" style="padding: 12px 30px; font-size: 16px;">Create Your First Post</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>
