<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Portofolio <?= html_escape($profile->full_name); ?></title>
    <style>
        /* Variabel warna utama untuk tampilan halaman publik portofolio. */
        :root {
            --bg: #f6efe5;
            --panel: #fffaf4;
            --ink: #1f2937;
            --soft: #6b7280;
            --accent: #c2410c;
            --accent-dark: #7c2d12;
            --line: #e7d8c7;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Georgia, "Times New Roman", serif;
            background: linear-gradient(135deg, #f6efe5 0%, #fdf7f2 60%, #efe2d1 100%);
            color: var(--ink);
        }
        a { color: inherit; text-decoration: none; }
        .container { width: min(1100px, calc(100% - 32px)); margin: 0 auto; }
        .hero {
            padding: 48px 0 24px;
        }
        .hero-grid {
            display: grid;
            grid-template-columns: 1.2fr .8fr;
            gap: 24px;
            align-items: center;
        }
        .badge {
            display: inline-block;
            padding: 8px 14px;
            border-radius: 999px;
            background: rgba(194, 65, 12, .12);
            color: var(--accent-dark);
            font-size: 14px;
            margin-bottom: 16px;
        }
        h1 {
            font-size: clamp(36px, 7vw, 68px);
            line-height: 1.05;
            margin: 0 0 12px;
        }
        .lead {
            font-size: 18px;
            line-height: 1.8;
            color: var(--soft);
            margin-bottom: 24px;
        }
        .hero-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }
        .btn {
            display: inline-block;
            padding: 12px 20px;
            border-radius: 12px;
            font-weight: bold;
            transition: .2s ease;
        }
        .btn-primary {
            background: var(--accent);
            color: #fff;
        }
        .btn-secondary {
            border: 1px solid var(--line);
            background: rgba(255,255,255,.7);
        }
        .btn:hover { transform: translateY(-2px); }
        .profile-card, .section-card {
            background: rgba(255,255,255,.68);
            backdrop-filter: blur(6px);
            border: 1px solid rgba(231, 216, 199, .8);
            border-radius: 28px;
            box-shadow: 0 18px 50px rgba(124, 45, 18, .08);
        }
        .profile-card {
            padding: 24px;
            text-align: center;
        }
        .profile-image {
            width: 220px;
            height: 220px;
            border-radius: 28px;
            object-fit: cover;
            display: block;
            margin: 0 auto 20px;
            background: #ead7c2;
        }
        .placeholder {
            display: grid;
            place-items: center;
            font-size: 56px;
            color: var(--accent-dark);
        }
        .section {
            padding: 20px 0 36px;
        }
        .section-card {
            padding: 28px;
        }
        .section-title {
            font-size: 28px;
            margin: 0 0 20px;
        }
        .meta {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }
        .meta-item {
            padding: 16px;
            border-radius: 18px;
            background: var(--panel);
            border: 1px solid var(--line);
        }
        .meta-item span {
            display: block;
            color: var(--soft);
            font-size: 13px;
            margin-bottom: 6px;
        }
        .skills {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 18px;
        }
        .skill {
            padding: 10px 14px;
            border-radius: 999px;
            background: #fff;
            border: 1px solid var(--line);
            font-size: 14px;
        }
        .projects {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 20px;
        }
        .project-card {
            overflow: hidden;
            background: #fff;
            border-radius: 24px;
            border: 1px solid var(--line);
        }
        .project-card img {
            width: 100%;
            height: 210px;
            object-fit: cover;
            display: block;
        }
        .project-body {
            padding: 18px;
        }
        .project-tag {
            color: var(--accent-dark);
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: .08em;
        }
        .project-title {
            margin: 10px 0;
            font-size: 22px;
        }
        .project-desc {
            color: var(--soft);
            line-height: 1.7;
            min-height: 96px;
        }
        .project-link {
            margin-top: 14px;
            display: inline-block;
            color: var(--accent);
            font-weight: bold;
        }
        .empty {
            padding: 24px;
            background: #fff;
            border-radius: 24px;
            border: 1px dashed var(--line);
            color: var(--soft);
        }
        footer {
            padding: 24px 0 48px;
            color: var(--soft);
            text-align: center;
        }
        /* Penyesuaian layout untuk layar tablet dan mobile. */
        @media (max-width: 768px) {
            .hero-grid, .meta {
                grid-template-columns: 1fr;
            }
            .profile-image {
                width: 180px;
                height: 180px;
            }
        }
    </style>
</head>
<body>
    <!-- Section hero / banner utama portofolio -->
    <section class="hero">
        <div class="container hero-grid">
            <div>
                <span class="badge">Portofolio Pribadi</span>
                <h1><?= html_escape($profile->full_name); ?></h1>
                <p class="lead">
                    <?= html_escape($profile->profession); ?> yang fokus membangun karya digital yang rapi, fungsional, dan nyaman digunakan.
                </p>
                <!-- Tombol aksi menuju daftar portofolio dan dashboard CRUD -->
                <div class="hero-actions">
                    <a class="btn btn-primary" href="#projects">Lihat Portofolio</a>
                    <a class="btn btn-secondary" href="<?= site_url('portfolio/admin'); ?>">Kelola CRUD</a>
                </div>
            </div>
            <div class="profile-card">
                <!-- Foto profil akan tampil jika user sudah mengunggah gambar -->
                <?php if (!empty($profile->profile_photo)): ?>
                    <img class="profile-image" src="<?= base_url($profile->profile_photo); ?>" alt="Foto Profil">
                <?php else: ?>
                    <div class="profile-image placeholder">P</div>
                <?php endif; ?>
                <h2 style="margin:0 0 8px;"><?= html_escape($profile->profession); ?></h2>
                <p style="margin:0;color:var(--soft);"><?= html_escape($profile->email); ?></p>
            </div>
        </div>
    </section>

    <!-- Section informasi profil singkat -->
    <section class="section">
        <div class="container">
            <div class="section-card">
                <h2 class="section-title">Tentang Saya</h2>
                <p style="line-height:1.9;color:var(--soft);margin:0 0 24px;"><?= nl2br(html_escape($profile->about)); ?></p>
                <div class="meta">
                    <div class="meta-item">
                        <span>Email</span>
                        <?= html_escape($profile->email); ?>
                    </div>
                    <div class="meta-item">
                        <span>Telepon</span>
                        <?= html_escape($profile->phone); ?>
                    </div>
                    <div class="meta-item">
                        <span>Alamat</span>
                        <?= html_escape($profile->address); ?>
                    </div>
                    <div class="meta-item">
                        <span>Total Proyek</span>
                        <?= count($projects); ?> proyek
                    </div>
                </div>
                <!-- Skill dipecah dari data string yang dipisah dengan koma -->
                <div class="skills">
                    <?php foreach (array_filter(array_map('trim', explode(',', $profile->skills))) as $skill): ?>
                        <span class="skill"><?= html_escape($skill); ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Section daftar project portofolio -->
    <section class="section" id="projects">
        <div class="container">
            <div class="section-card">
                <h2 class="section-title">Project Portofolio</h2>
                <?php if (!empty($projects)): ?>
                    <div class="projects">
                        <!-- Loop untuk menampilkan semua project -->
                        <?php foreach ($projects as $project): ?>
                            <article class="project-card">
                                <?php if (!empty($project->image)): ?>
                                    <img src="<?= base_url($project->image); ?>" alt="<?= html_escape($project->title); ?>">
                                <?php else: ?>
                                    <div class="profile-image placeholder" style="width:100%;height:210px;border-radius:0;">+</div>
                                <?php endif; ?>
                                <div class="project-body">
                                    <div class="project-tag"><?= html_escape($project->category); ?></div>
                                    <h3 class="project-title"><?= html_escape($project->title); ?></h3>
                                    <div class="project-desc"><?= nl2br(html_escape($project->description)); ?></div>
                                    <!-- Link project hanya tampil jika field link diisi -->
                                    <?php if (!empty($project->project_link)): ?>
                                        <a class="project-link" href="<?= html_escape($project->project_link); ?>" target="_blank" rel="noopener noreferrer">Kunjungi Project</a>
                                    <?php endif; ?>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty">Belum ada proyek portofolio. Tambahkan dari halaman kelola CRUD.</div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Footer sederhana sebagai navigasi tambahan -->
    <footer>
        <div class="container">
            Kelola data portofolio melalui <a href="<?= site_url('portfolio/admin'); ?>"><strong>dashboard CRUD</strong></a>.
        </div>
    </footer>
</body>
</html>
