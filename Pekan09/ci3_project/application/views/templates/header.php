<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Bagian head ini menyimpan pengaturan dasar halaman seperti charset, responsive, title, dan CSS. -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'Posts Application'; ?> - CI3 CRUD</title>
    <style>
        /* Reset sederhana supaya jarak bawaan browser tidak bikin tampilan beda-beda. */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body ini jadi dasar tampilan halaman, termasuk font dan warna background. */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            line-height: 1.6;
        }

        /* Container dipakai supaya isi halaman tidak terlalu melebar sampai pinggir layar. */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Header dibuat mencolok karena ini bagian paling atas dan identitas aplikasi. */
        header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 0;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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

        /* Navigasi dibuat seperti kotak putih supaya user tahu menu utamanya ada di sini. */
        .nav {
            background: white;
            padding: 15px 0;
            margin-bottom: 30px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .nav ul {
            list-style: none;
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .nav a {
            color: #667eea;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            transition: all 0.3s;
            font-weight: 500;
        }

        .nav a:hover {
            background: #667eea;
            color: white;
        }

        /* Alert dipakai untuk pesan sukses atau error dari session flashdata. */
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

        /* Class tombol dibuat umum dulu, lalu warnanya dibedakan lewat class tambahan. */
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(102, 126, 234, 0.4);
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

        /* Card menjadi pembungkus konten utama supaya halaman terlihat lebih rapi. */
        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-bottom: 20px;
        }

        /* Blok form ini mengatur jarak label, input, textarea, dan file upload. */
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

        /* Tabel dipakai untuk daftar post, jadi dibuat full width dan headernya diberi warna. */
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        table th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }

        table td {
            padding: 15px;
            border-bottom: 1px solid #e0e0e0;
        }

        table tbody tr:hover {
            background-color: #f8f9fa;
        }

        table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Actions mengatur tombol-tombol kecil supaya sejajar dan tidak saling menempel. */
        .actions {
            display: flex;
            gap: 5px;
        }

        .post-item {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
            transition: box-shadow 0.3s ease;
        }

        .post-item:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        }

        .post-item h3 {
            margin-bottom: 10px;
            color: #333;
        }

        .post-item p {
            color: #666;
            margin-bottom: 15px;
        }

        .post-actions {
            display: flex;
            gap: 10px;
        }

        /* Gambar detail post dibuat responsive supaya masih aman kalau ukuran layar lebih kecil. */
        .post-detail-image {
            max-width: 500px;
            width: 100%;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 20px 0;
        }

        .text-center {
            text-align: center;
        }

        .mb-20 {
            margin-bottom: 20px;
        }

        /* Preview artikel dibatasi satu baris, karena kalau panjang semua tabelnya jadi berantakan. */
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

        .post-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
            border: 2px solid #e0e0e0;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <!-- Header halaman ini tampil di semua halaman post sebagai identitas aplikasi. -->
    <header>
        <div class="container">
            <h1>Posts Management</h1>
            <p>CodeIgniter 3 CRUD Application</p>
        </div>
    </header>

    <div class="container">
        <!-- Navigasi sederhana supaya user bisa pindah ke Home atau Posts. -->
        <div class="nav">
            <ul>
                <li><a href="<?php echo site_url(); ?>">Home</a></li>
                <li><a href="<?php echo site_url('posts'); ?>">Posts</a></li>
            </ul>
        </div>

        <!-- Pesan sukses muncul kalau controller mengirim flashdata success. -->
        <?php if($this->session->flashdata('success')): ?>
            <div class="alert alert-success">
                <?php echo $this->session->flashdata('success'); ?>
            </div>
        <?php endif; ?>

        <!-- Pesan error muncul kalau ada validasi gagal atau proses database/upload bermasalah. -->
        <?php if($this->session->flashdata('error')): ?>
            <div class="alert alert-error">
                <?php echo $this->session->flashdata('error'); ?>
            </div>
        <?php endif; ?>