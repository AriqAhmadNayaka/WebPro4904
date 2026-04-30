<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Profil Portofolio</title>
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
        textarea { min-height: 120px; resize: vertical; }
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
        <!-- Form ini digunakan untuk memperbarui data profil utama -->
        <h1 style="margin-top:0;">Edit Profil Portofolio</h1>
        <p style="color:#475569;">Perbarui data profil utama yang akan tampil di halaman portofolio.</p>

        <!-- Menampilkan error validasi jika ada field yang belum sesuai -->
        <?php if (validation_errors()): ?>
            <div class="error-list"><?= validation_errors(); ?></div>
        <?php endif; ?>
        <!-- Menampilkan error upload jika file gagal diunggah -->
        <?php if (!empty($upload_error)): ?>
            <div class="error-list"><?= html_escape($upload_error); ?></div>
        <?php endif; ?>

        <!-- Form multipart diperlukan karena ada upload file foto profil -->
        <?= form_open_multipart('portfolio/profile/update'); ?>
            <!-- Input nama lengkap -->
            <label for="full_name">Nama Lengkap</label>
            <input type="text" name="full_name" id="full_name" value="<?= set_value('full_name', $profile->full_name); ?>">

            <!-- Input profesi atau jabatan -->
            <label for="profession">Profesi / Jabatan</label>
            <input type="text" name="profession" id="profession" value="<?= set_value('profession', $profile->profession); ?>">

            <!-- Textarea untuk deskripsi singkat tentang pemilik portofolio -->
            <label for="about">Tentang Saya</label>
            <textarea name="about" id="about"><?= set_value('about', $profile->about); ?></textarea>

            <!-- Input kontak email -->
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="<?= set_value('email', $profile->email); ?>">

            <!-- Input nomor telepon -->
            <label for="phone">Nomor Telepon</label>
            <input type="text" name="phone" id="phone" value="<?= set_value('phone', $profile->phone); ?>">

            <!-- Input alamat -->
            <label for="address">Alamat</label>
            <input type="text" name="address" id="address" value="<?= set_value('address', $profile->address); ?>">

            <!-- Input skill yang dipisah dengan tanda koma -->
            <label for="skills">Skill / Keahlian</label>
            <input type="text" name="skills" id="skills" value="<?= set_value('skills', $profile->skills); ?>" placeholder="Pisahkan dengan koma">

            <!-- Upload foto profil -->
            <label for="profile_photo">Foto Profil</label>
            <input type="file" name="profile_photo" id="profile_photo" accept=".jpg,.jpeg,.png,.webp">
            <?php if (!empty($profile->profile_photo)): ?>
                <p style="color:#475569;">Foto saat ini: <a href="<?= base_url($profile->profile_photo); ?>" target="_blank">Lihat foto</a></p>
            <?php endif; ?>

            <!-- Tombol aksi simpan dan kembali -->
            <div class="actions">
                <button class="btn btn-primary" type="submit">Simpan Profil</button>
                <a class="btn btn-light" href="<?= site_url('portfolio/admin'); ?>">Kembali</a>
            </div>
        <?= form_close(); ?>
    </div>
</body>
</html>
