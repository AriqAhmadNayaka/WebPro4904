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
        <a class="btn btn-sm btn-outline-light" href="<?php echo site_url('crudjs'); ?>">CRUD AJAX</a>
    </div>
</nav>

<main class="container py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h1 class="h3 mb-1">Posts HMVC</h1>
            <p class="text-secondary mb-0">CRUD sederhana dengan struktur module, controller, model, dan view.</p>
        </div>
        <a class="btn btn-primary" href="<?php echo site_url('posts/create'); ?>">Tambah Post</a>
    </div>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($this->session->flashdata('success')); ?></div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($this->session->flashdata('error')); ?></div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 72px;">ID</th>
                        <th>Judul</th>
                        <th>Penulis</th>
                        <th>Artikel</th>
                        <th style="width: 120px;">Gambar</th>
                        <th style="width: 170px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($posts)): ?>
                        <tr>
                            <td colspan="6" class="text-center text-secondary py-4">Belum ada data.</td>
                        </tr>
                    <?php endif; ?>

                    <?php foreach ($posts as $post): ?>
                        <tr>
                            <td><?php echo (int) $post->id; ?></td>
                            <td><?php echo htmlspecialchars($post->title); ?></td>
                            <td><?php echo htmlspecialchars($post->author); ?></td>
                            <td><?php echo htmlspecialchars(strlen($post->article) > 90 ? substr($post->article, 0, 90) . '...' : $post->article); ?></td>
                            <td>
                                <?php if (!empty($post->image)): ?>
                                    <img src="<?php echo base_url('uploads/' . $post->image); ?>" alt="<?php echo htmlspecialchars($post->title); ?>" class="rounded object-fit-cover" style="width: 84px; height: 56px;">
                                <?php else: ?>
                                    <span class="text-secondary">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a class="btn btn-sm btn-warning" href="<?php echo site_url('posts/edit/' . $post->id); ?>">Edit</a>
                                    <form action="<?php echo site_url('posts/delete/' . $post->id); ?>" method="post" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                        <button class="btn btn-sm btn-danger" type="submit">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>
</body>
</html>
