<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'CRUD AJAX'; ?> - CI3 HMVC</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #eef2f7; color: #1f2937; }
        header { background: #263238; color: #fff; padding: 24px; }
        header .wrap, main { max-width: 1180px; margin: 0 auto; }
        header h1 { margin: 0 0 6px; font-size: 28px; }
        header p { margin: 0; color: #d1d5db; }
        nav { background: #fff; border-bottom: 1px solid #dbe3ee; }
        nav .wrap { max-width: 1180px; margin: 0 auto; display: flex; gap: 10px; padding: 12px 24px; flex-wrap: wrap; }
        nav a { color: #2563eb; text-decoration: none; font-weight: 600; padding: 8px 12px; border-radius: 6px; }
        nav a:hover { background: #eff6ff; }
        main { padding: 24px; }
        .panel { background: #fff; border: 1px solid #dbe3ee; border-radius: 8px; padding: 20px; box-shadow: 0 8px 20px rgba(15,23,42,.06); }
        .toolbar { display: flex; justify-content: space-between; align-items: center; gap: 12px; margin-bottom: 16px; flex-wrap: wrap; }
        .btn { border: 0; border-radius: 6px; padding: 9px 14px; cursor: pointer; color: #fff; font-weight: 600; text-decoration: none; display: inline-block; }
        .btn-primary { background: #2563eb; }
        .btn-success { background: #16a34a; }
        .btn-warning { background: #f59e0b; color: #111827; }
        .btn-danger { background: #dc2626; }
        .btn-secondary { background: #64748b; }
        table { width: 100%; border-collapse: collapse; }
        th, td { text-align: left; padding: 12px; border-bottom: 1px solid #e5e7eb; vertical-align: top; }
        th { background: #f8fafc; color: #475569; }
        .actions { display: flex; gap: 6px; flex-wrap: wrap; }
        .preview { max-width: 220px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .thumb { width: 80px; border-radius: 6px; border: 1px solid #e5e7eb; }
        .alert { display: none; padding: 12px 14px; border-radius: 6px; margin-bottom: 14px; }
        .alert.success { background: #dcfce7; color: #166534; }
        .alert.error { background: #fee2e2; color: #991b1b; }
        .modal { display: none; position: fixed; inset: 0; background: rgba(15,23,42,.55); align-items: center; justify-content: center; padding: 20px; }
        .modal.open { display: flex; }
        .modal-card { background: #fff; width: min(680px, 100%); border-radius: 8px; padding: 20px; max-height: 90vh; overflow: auto; }
        .modal-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; }
        .form-group { margin-bottom: 14px; }
        label { display: block; font-weight: 700; margin-bottom: 6px; }
        input[type="text"], textarea, input[type="file"] { width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; }
        textarea { min-height: 130px; resize: vertical; }
        .current-image { max-width: 180px; border-radius: 8px; border: 1px solid #e5e7eb; margin-top: 8px; }
    </style>
</head>
<body>
    <header>
        <div class="wrap">
            <h1>CRUD AJAX HMVC</h1>
            <p>CodeIgniter 3 CRUD tanpa refresh halaman</p>
        </div>
    </header>
    <nav>
        <div class="wrap">
            <a href="<?php echo base_url('posts'); ?>">Posts HMVC</a>
            <a href="<?php echo base_url('crudjs'); ?>">CRUD AJAX</a>
        </div>
    </nav>
    <main>
