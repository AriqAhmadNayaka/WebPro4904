<?php
session_start();
require_once 'koneksi.php';

$pesan = '';
$tipePesan = 'danger';
$usernameTersimpan = $_COOKIE['remembered_username'] ?? '';

if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header('Location: index.php?pesan=' . urlencode('Anda berhasil logout.'));
    exit;
}

if (isset($_GET['pesan'])) {
    $pesan = htmlspecialchars($_GET['pesan'], ENT_QUOTES, 'UTF-8');
    $tipePesan = 'success';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $ingatSaya = isset($_POST['ingat_saya']);
    $usernameTersimpan = $username;

    if ($username === '' || $password === '') {
        $pesan = 'Username dan password wajib diisi.';
    } else {
        $stmt = mysqli_prepare($conn, 'SELECT id, username, password FROM users WHERE username = ? LIMIT 1');

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 's', $username);
            mysqli_stmt_execute($stmt);
            $hasil = mysqli_stmt_get_result($stmt);
            $user = $hasil ? mysqli_fetch_assoc($hasil) : null;
            mysqli_stmt_close($stmt);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = (int) $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['login_time'] = date('Y-m-d H:i:s');

                if ($ingatSaya) {
                    setcookie('remembered_username', $user['username'], time() + (86400 * 30), '/');
                } else {
                    setcookie('remembered_username', '', time() - 3600, '/');
                }

                header('Location: index.php');
                exit;
            }

            $pesan = 'Username atau password salah.';
        } else {
            $pesan = 'Terjadi kesalahan pada sistem login.';
        }
    }
}

$sudahLogin = isset($_SESSION['user_id'], $_SESSION['username']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simulasi Login PHP</title>
    <style>
        :root {
            --panel: #fffaf3;
            --text: #2e2a26;
            --primary: #8b5e34;
            --primary-dark: #69411d;
            --accent: #d7b98f;
            --success: #2d6a4f;
            --danger: #9b2226;
            --border: #e7d7c1;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: Georgia, "Times New Roman", serif;
            color: var(--text);
            background:
                radial-gradient(circle at top left, rgba(215, 185, 143, 0.45), transparent 35%),
                linear-gradient(135deg, #f8f3eb, #eee2d0);
            display: grid;
            place-items: center;
            padding: 24px;
        }

        .card {
            width: min(100%, 430px);
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 24px;
            box-shadow: 0 18px 50px rgba(78, 54, 30, 0.15);
            padding: 32px;
        }

        h1 {
            margin: 0 0 8px;
            font-size: 2rem;
            color: var(--primary-dark);
        }

        p {
            margin: 0 0 20px;
            line-height: 1.6;
        }

        .info-box {
            border-radius: 16px;
            padding: 14px 16px;
            margin-bottom: 18px;
            border: 1px solid transparent;
        }

        .danger {
            background: #fff1f2;
            color: var(--danger);
            border-color: #fecdd3;
        }

        .success {
            background: #edfdf3;
            color: var(--success);
            border-color: #b7e4c7;
        }

        .field {
            margin-bottom: 16px;
        }

        label {
            display: block;
            font-weight: 700;
            margin-bottom: 8px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 12px 14px;
            font-size: 1rem;
            background: #fff;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            outline: 2px solid rgba(139, 94, 52, 0.2);
            border-color: var(--primary);
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 22px;
        }

        button,
        .button-link {
            width: 100%;
            display: inline-block;
            border: 0;
            border-radius: 999px;
            padding: 13px 18px;
            background: var(--primary);
            color: #fff;
            font-size: 1rem;
            text-decoration: none;
            text-align: center;
            cursor: pointer;
            transition: background 0.2s ease, transform 0.2s ease;
        }

        button:hover,
        .button-link:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        .secondary {
            margin-top: 12px;
            background: transparent;
            color: var(--primary-dark);
            border: 1px solid var(--accent);
        }

        .secondary:hover {
            background: #f4e8d8;
        }

        .hint {
            margin-top: 20px;
            padding: 16px;
            border-radius: 16px;
            background: #f8efe3;
            border: 1px dashed var(--accent);
            font-size: 0.95rem;
        }

        .meta {
            margin-top: 18px;
            font-size: 0.92rem;
            color: #6b5b4b;
        }
    </style>
</head>
<body>
    <main class="card">
        <?php if ($sudahLogin): ?>
            <h1>Selamat Datang</h1>
            <p>Anda sudah login dan status ini disimpan di dalam session selama browser aktif.</p>

            <div class="info-box success">
                Login sebagai <strong><?= htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8') ?></strong>.
            </div>

            <div class="meta">
                Waktu login: <?= htmlspecialchars($_SESSION['login_time'], ENT_QUOTES, 'UTF-8') ?><br>
                Cookie username: <?= $usernameTersimpan !== '' ? htmlspecialchars($usernameTersimpan, ENT_QUOTES, 'UTF-8') : 'belum disimpan' ?>
            </div>

            <a class="button-link secondary" href="index.php?logout=1">Logout</a>
        <?php else: ?>
            <h1>Form Login</h1>
            <p>Masukkan username dan password untuk mencoba simulasi login PHP dengan session dan cookie.</p>

            <?php if ($pesan !== ''): ?>
                <div class="info-box <?= $tipePesan ?>">
                    <?= htmlspecialchars($pesan, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <form method="post" action="">
                <div class="field">
                    <label for="username">Username</label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        value="<?= htmlspecialchars($usernameTersimpan, ENT_QUOTES, 'UTF-8') ?>"
                        placeholder="Masukkan username"
                        required
                    >
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Masukkan password"
                        required
                    >
                </div>

                <label class="remember" for="ingat_saya">
                    <input type="checkbox" id="ingat_saya" name="ingat_saya" <?= $usernameTersimpan !== '' ? 'checked' : '' ?>>
                    Ingat username dengan cookie
                </label>

                <button type="submit">Login</button>
            </form>

            <div class="hint">
                Akun uji:<br>
                Username: <strong>admin</strong><br>
                Password: <strong>admin123</strong>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
