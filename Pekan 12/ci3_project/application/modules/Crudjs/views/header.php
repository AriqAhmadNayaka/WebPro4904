<!DOCTYPE html>
<!-- Menandai awal dokumen HTML. -->
<html lang="en">
<head>
    <!-- Mengatur encoding karakter agar teks tampil benar. -->
    <meta charset="UTF-8">
    <!-- Mengatur tampilan agar responsif di perangkat mobile. -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Menampilkan judul halaman dari controller, jika tidak ada memakai default CRUD with AJAX. -->
    <title><?php echo isset($title) ? $title : 'CRUD with AJAX'; ?> - CI3 CRUD</title>
    <!-- CSS internal untuk styling halaman CRUD AJAX. -->
    <style>
        /* Reset margin, padding, dan box sizing semua elemen. */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        /* Styling dasar body halaman. */
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f5f5f5; line-height: 1.6; }
        /* Container untuk membatasi lebar konten. */
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        /* Header halaman dengan background gradasi. */
        header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px 0; margin-bottom: 30px; box-shadow: 0 4px 6px rgba(0,0,0,.1); }
        /* Styling judul utama di header. */
        header h1 { text-align: center; font-size: 2.5em; margin-bottom: 10px; }
        /* Styling deskripsi singkat di header. */
        header p { text-align: center; opacity: .9; }
        /* Card pembungkus konten utama. */
        .card { background: white; border-radius: 8px; padding: 25px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,.1); }
        /* Styling dasar semua tombol. */
        .btn { padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; text-decoration: none; display: inline-block; margin: 2px; transition: all .3s ease; }
        /* Warna tombol utama. */
        .btn-primary { background: #667eea; color: white; }
        /* Warna tombol sukses/simpan. */
        .btn-success { background: #28a745; color: white; }
        /* Warna tombol peringatan/edit. */
        .btn-warning { background: #ffc107; color: #212529; }
        /* Warna tombol bahaya/delete. */
        .btn-danger { background: #dc3545; color: white; }
        /* Warna tombol sekunder/batal. */
        .btn-secondary { background: #6c757d; color: white; }
        /* Efek saat tombol diarahkan kursor. */
        .btn:hover { opacity: .85; transform: translateY(-1px); }
        /* Jarak bawah setiap grup form. */
        .form-group { margin-bottom: 15px; }
        /* Styling label form. */
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; color: #333; }
        /* Styling input dan textarea form. */
        .form-control { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; }
        /* Styling khusus textarea artikel. */
        textarea.form-control { min-height: 120px; resize: vertical; }
        /* Styling dasar tabel data. */
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        /* Styling sel header dan isi tabel. */
        .table th, .table td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; vertical-align: top; }
        /* Warna header tabel. */
        .table th { background-color: #667eea; color: white; }
        /* Efek hover pada baris tabel. */
        .table tr:hover { background-color: #f8f9fa; }
        /* Ukuran preview gambar di tabel dan modal. */
        .image-preview { max-width: 100px; max-height: 100px; border-radius: 4px; object-fit: cover; }
        /* Styling dasar kotak alert. */
        .alert { padding: 12px 15px; border-radius: 4px; margin-bottom: 15px; display: none; }
        /* Styling alert sukses. */
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        /* Styling alert error. */
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        /* Styling background modal. */
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,.5); }
        /* Styling kotak isi modal. */
        .modal-content { background: white; margin: 5% auto; padding: 25px; border-radius: 8px; width: 90%; max-width: 600px; max-height: 90vh; overflow-y: auto; }
        /* Styling tombol close pada modal. */
        .close { float: right; font-size: 28px; font-weight: bold; cursor: pointer; color: #aaa; }
        /* Warna tombol close saat hover. */
        .close:hover { color: #000; }
        /* Styling teks loading. */
        .loading { text-align: center; padding: 20px; color: #666; }
        /* Membuat tombol aksi edit/delete tetap satu baris. */
        .actions { white-space: nowrap; }
        /* Aturan responsif untuk layar kecil. */
        @media (max-width: 768px) {
            /* Mengecilkan ukuran font tabel pada mobile. */
            .table { font-size: 12px; }
            /* Mengecilkan padding sel tabel pada mobile. */
            .table th, .table td { padding: 8px; }
            /* Mengecilkan judul header pada mobile. */
            header h1 { font-size: 2em; }
            /* Menyesuaikan lebar modal pada mobile. */
            .modal-content { margin: 10% auto; width: 95%; }
        }
    </style>
</head>
<body>
    <!-- Header halaman CRUD with AJAX. -->
    <header>
        <!-- Container agar isi header sejajar dengan konten utama. -->
        <div class="container">
            <!-- Judul utama halaman. -->
            <h1>CRUD with AJAX</h1>
            <!-- Deskripsi singkat teknologi yang digunakan. -->
            <p>CodeIgniter 3 + HMVC + AJAX tanpa refresh halaman</p>
        </div>
    </header>
