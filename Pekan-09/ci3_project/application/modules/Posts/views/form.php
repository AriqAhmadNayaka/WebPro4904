<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($title); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="<?php echo site_url('posts'); ?>">Posts HMVC</a>
    </div>
</nav>

<main class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0"><?php echo htmlspecialchars($title); ?></h1>
        <a class="btn btn-outline-secondary" href="<?php echo site_url('posts'); ?>">Kembali</a>
    </div>

    <?php if (validation_errors()): ?>
        <div class="alert alert-danger"><?php echo validation_errors(); ?></div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($this->session->flashdata('error')); ?></div>
    <?php endif; ?>

    <form class="card shadow-sm" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label" for="title">Title</label>
                <input class="form-control" type="text" id="title" name="title" maxlength="255" required value="<?php echo htmlspecialchars(set_value('title', $post ? $post->title : '')); ?>">
            </div>

            <div class="mb-3">
                <label class="form-label" for="author">Author</label>
                <input class="form-control" type="text" id="author" name="author" maxlength="255" required value="<?php echo htmlspecialchars(set_value('author', $post ? $post->author : '')); ?>">
            </div>

            <div class="mb-3">
                <label class="form-label" for="article">Article</label>
                <textarea class="form-control" id="article" name="article" rows="6" required><?php echo htmlspecialchars(set_value('article', $post ? $post->article : '')); ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label" for="image">Image</label>
                <input class="form-control" type="file" id="image" name="image" accept=".jpg,.jpeg,.png,image/jpeg,image/png">
                <div class="form-text">Format jpg, jpeg, png. Maksimal 2 MB.</div>
            </div>

            <?php if ($post && !empty($post->image)): ?>
                <div class="mb-3">
                    <img src="<?php echo base_url('uploads/' . $post->image); ?>" alt="<?php echo htmlspecialchars($post->title); ?>" class="rounded border" style="max-width: 220px;">
                </div>
            <?php endif; ?>
        </div>
        <div class="card-footer bg-white d-flex justify-content-end gap-2">
            <a class="btn btn-outline-secondary" href="<?php echo site_url('posts'); ?>">Batal</a>
            <button class="btn btn-primary" type="submit">Simpan</button>
        </div>
    </form>
</main>
</body>
</html>
