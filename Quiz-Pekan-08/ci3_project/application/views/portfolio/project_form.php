<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= html_escape($page_title); ?></title>
    <style>
        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f8fafc;
            color: #0f172a;
        }
        .wrap {
            width: min(820px, calc(100% - 32px));
            margin: 32px auto;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 24px;
            padding: 28px;
            box-shadow: 0 20px 40px rgba(15, 23, 42, .06);
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
        }
        input, textarea {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            margin-bottom: 18px;
            font-size: 14px;
        }
        textarea { min-height: 140px; resize: vertical; }
        .btn {
            display: inline-block;
            padding: 12px 18px;
            border-radius: 12px;
            text-decoration: none;
            border: 0;
            cursor: pointer;
            font-weight: bold;
        }
        .btn-primary { background: #0f766e; color: #fff; }
        .btn-light { background: #fff; color: #0f172a; border: 1px solid #cbd5e1; }
        .actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }
        .error-list {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px 16px;
            border-radius: 14px;
            margin-bottom: 18px;
        }
    </style>
</head>
<body>
    <div class="wrap">
        <!-- Form ini dipakai untuk tambah maupun edit project portofolio -->
        <h1 style="margin-top:0;"><?= html_escape($page_title); ?></h1>
        <p style="color:#475569;">Masukkan detail project yang ingin ditampilkan di website portofolio.</p>

        <!-- Error validasi form -->
        <?php if (validation_errors()): ?>
            <div class="error-list"><?= validation_errors(); ?></div>
        <?php endif; ?>
        <!-- Error upload gambar -->
        <?php if (!empty($upload_error)): ?>
            <div class="error-list"><?= html_escape($upload_error); ?></div>
        <?php endif; ?>

        <!-- Multipart dipakai karena form mendukung upload gambar project -->
        <?= form_open_multipart($form_action); ?>
            <!-- Input judul project -->
            <label for="title">Judul Portofolio</label>
            <input type="text" name="title" id="title" value="<?= set_value('title', isset($project->title) ? $project->title : ''); ?>">

            <!-- Input kategori project -->
            <label for="category">Kategori</label>
            <input type="text" name="category" id="category" value="<?= set_value('category', isset($project->category) ? $project->category : ''); ?>" placeholder="Contoh: Website, UI Design, Mobile App">

            <!-- Textarea deskripsi project -->
            <label for="description">Deskripsi</label>
            <textarea name="description" id="description"><?= set_value('description', isset($project->description) ? $project->description : ''); ?></textarea>

            <!-- Input link project jika ada demo/live preview -->
            <label for="project_link">Link Project</label>
            <input type="url" name="project_link" id="project_link" value="<?= set_value('project_link', isset($project->project_link) ? $project->project_link : ''); ?>" placeholder="https://example.com">

            <!-- Upload gambar project -->
            <label for="image">Gambar Project</label>
            <input type="file" name="image" id="image" accept=".jpg,.jpeg,.png,.webp">
            <?php if (!empty($project->image)): ?>
                <p style="color:#475569;">Gambar saat ini: <a href="<?= base_url($project->image); ?>" target="_blank">Lihat gambar</a></p>
            <?php endif; ?>

            <!-- Tombol submit dan kembali -->
            <div class="actions">
                <button class="btn btn-primary" type="submit"><?= html_escape($submit_label); ?></button>
                <a class="btn btn-light" href="<?= site_url('portfolio/admin'); ?>">Kembali</a>
            </div>
        <?= form_close(); ?>
    </div>
</body>
</html>
