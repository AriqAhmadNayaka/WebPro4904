<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'Posts Application'; ?> - CI3 HMVC</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; line-height: 1.6; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; padding: 30px 0; margin-bottom: 30px; box-shadow: 0 4px 6px rgba(0,0,0,.1); }
        header h1 { text-align: center; font-size: 2.5em; margin-bottom: 10px; }
        header p { text-align: center; font-size: 1.1em; opacity: .9; }
        .nav { background: #fff; padding: 15px 0; margin-bottom: 30px; box-shadow: 0 2px 4px rgba(0,0,0,.1); border-radius: 8px; }
        .nav ul { list-style: none; display: flex; justify-content: center; gap: 20px; flex-wrap: wrap; }
        .nav a { color: #667eea; text-decoration: none; padding: 10px 20px; border-radius: 5px; transition: all .3s; font-weight: 500; }
        .nav a:hover { background: #667eea; color: #fff; }
        .alert { padding: 15px 20px; border-radius: 8px; margin-bottom: 20px; }
        .alert-success { background: #d4edda; border-left: 4px solid #28a745; color: #155724; }
        .alert-error { background: #f8d7da; border-left: 4px solid #dc3545; color: #721c24; }
        .btn { display: inline-block; padding: 10px 20px; text-decoration: none; border-radius: 5px; cursor: pointer; border: none; font-size: 14px; font-weight: 500; transition: all .3s; }
        .btn-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; }
        .btn-success { background: #28a745; color: #fff; }
        .btn-danger { background: #dc3545; color: #fff; }
        .btn-warning { background: #ffc107; color: #212529; }
        .btn-info { background: #17a2b8; color: #fff; }
        .btn:hover { transform: translateY(-1px); filter: brightness(.95); }
        .card { background: #fff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,.1); padding: 30px; margin-bottom: 20px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #333; }
        .form-group input[type="text"], .form-group textarea, .form-group input[type="file"] { width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 6px; font-size: 14px; }
        .form-group textarea { min-height: 150px; resize: vertical; }
        table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,.1); }
        table thead { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; }
        table th, table td { padding: 15px; text-align: left; border-bottom: 1px solid #e0e0e0; }
        table tbody tr:hover { background: #f8f9fa; }
        .actions { display: flex; gap: 5px; flex-wrap: wrap; }
        .post-image { max-width: 100px; height: auto; border-radius: 5px; }
        .post-detail-image { max-width: 500px; width: 100%; height: auto; border-radius: 10px; margin: 20px 0; }
        .text-center { text-align: center; }
        .article-preview { max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .back-link { display: inline-block; margin-bottom: 20px; }
        footer { background: #333; color: #fff; text-align: center; padding: 20px 0; margin-top: 50px; }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>Posts Management</h1>
            <p>CodeIgniter 3 HMVC CRUD Application</p>
        </div>
    </header>
    <div class="container">
        <div class="nav">
            <ul>
                <li><a href="<?php echo base_url('posts'); ?>">Posts HMVC</a></li>
                <li><a href="<?php echo base_url('posts/create'); ?>">Create Post</a></li>
                <li><a href="<?php echo base_url('crudjs'); ?>">CRUD AJAX</a></li>
            </ul>
        </div>
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-error"><?php echo $this->session->flashdata('error'); ?></div>
        <?php endif; ?>
