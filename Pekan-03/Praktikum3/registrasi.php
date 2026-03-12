<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar - WeBandoo+</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        :root { --color-primary-green: #4A8645; --color-off-white: #f7f7f7; }
        body { min-height: 100vh; display: flex; justify-content: center; align-items: center; background-image: radial-gradient(#9dc599, #4A8645); font-family: "Poppins", sans-serif; }
        .outer-container { display: flex; width: 900px; height: 550px; border-radius: 25px; overflow: hidden; background: #fff; box-shadow: 0 20px 60px rgba(0,0,0,0.3); }
    :root {
        --color-primary-green: #4A8645;
        --color-secondary-brown: #000000; 
        --color-white: #ffffff;
        --color-off-white: #f7f7f7;
        --color-text-dark: #333;
        --shadow-deep: 0 20px 60px rgba(0, 0, 0, 0.3);
    }

    * {
        margin: 0; padding: 0; box-sizing: border-box;
        font-family: "Poppins", sans-serif;
    }

    body {
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        background-image: radial-gradient(#9dc599, #4A8645);
    }

    .outer-container {
        display: flex;
        width: 900px;
        height: 550px;
        border-radius: 25px;
        overflow: hidden;
        box-shadow: var(--shadow-deep);
        background: var(--color-white);
    }

    .image-panel {
        width: 45%;
        position: relative;
        color: var(--color-white);
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
    }
    
    #slideshow-bg {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-size: cover;
        background-position: center;
        filter: brightness(0.95);
        transition: opacity 1s ease-in-out; 
    }

    .image-panel::before { 
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.1));
        z-index: 1;
    }

    .image-content {
        position: relative;
        z-index: 2;
        padding: 30px;
        transition: opacity 0.5s ease-in-out;
    }

    .image-content h3 {
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .image-content p {
        font-size: 14px;
        font-weight: 500;
    }

    .login-container {
        width: 55%;
        padding: 40px;
        background: var(--color-off-white);
        display: flex;
        flex-direction: column;
        justify-content: center;
        color: var(--color-text-dark);
    }

    .logo-header {
        text-align: right;
        margin-bottom: 10px;
    }

    .logo-header span {
        font-size: 18px;
        font-weight: 700;
        color: var(--color-primary-green);
    }

    .greeting h1 {
        font-size: 32px;
        font-weight: 700;
        color: var(--color-text-dark);
        margin-bottom: 5px;
    }

    .greeting p {
        font-size: 15px;
        color: #666;
        margin-bottom: 30px;
    }

    .input-box input {
        width: 100%;
        padding: 12px 15px;
        margin-bottom: 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        outline: none;
        color: var(--color-text-dark);
        font-size: 15px;
        transition: border-color 0.3s, box-shadow 0.3s;
    }

    .input-box input::placeholder {
        color: #aaa;
    }

    .input-box input:focus {
        border-color: var(--color-primary-green);
        box-shadow: 0 0 5px rgba(74, 134, 69, 0.3);
    }

.pass-container {
    position: relative;
    margin-bottom: 15px;
}

.pass-container input[type="password"],
.pass-container input[type="text"] {
    width: 100%;
    padding: 12px 15px; 
    padding-right: 45px;
    border: 1px solid #ddd;
    border-radius: 8px;
    outline: none;
    color: var(--color-text-dark);
    font-size: 15px;
    transition: border-color 0.3s, box-shadow 0.3s;
    line-height: 1.5;
}

.pass-container input:focus {
    border-color: var(--color-primary-green);
    box-shadow: 0 0 5px rgba(74, 134, 69, 0.3);
}

.toggle-pass {
    position: absolute;
    top: 50%;
    right: 15px;
    transform: translateY(-100%);
    cursor: pointer;
    color: #888;
    z-index: 10;
    transition: color 0.3s;
    font-size: 16px;
}

.toggle-pass:hover {
    color: var(--color-primary-green);
}
    .forgot-password {
        text-align: right;
        margin-bottom: 25px;
    }
    .forgot-password a {
        color: var(--color-secondary-brown);
        font-size: 13px;
        text-decoration: none;
        font-weight: 500;
    }

    .btn {
        width: 100%;
        padding: 14px;
        border: none;
        border-radius: 8px;
        background: var(--color-primary-green);
        font-weight: 600;
        cursor: pointer;
        color: var(--color-white);
        text-decoration: none; 
        display: block; 
        transition: 0.3s ease;
        box-shadow: 0 4px 15px rgba(87, 95, 87, 0.5);
        margin-bottom: 20px; 
        text-align: center;
    }

    .btn:hover {
        background: #5e9d57;
        transform: translateY(-2px);
    }

    .register {
        margin-top: 0px; 
        font-size: 14px;
        color: var(--color-text-dark); 
        text-align: center;
    }

    .register a {
        color: var(--color-primary-green) !important;
        font-weight: 600;
        text-decoration: none;
    }

    @media (max-width: 900px) {
        .outer-container {
            width: 90%;
            height: auto;
            flex-direction: column;
            border-radius: 15px;
        }
        .image-panel, .login-container {
            width: 100%;
        }
        .image-panel {
            min-height: 250px;
            border-radius: 15px 15px 0 0;
            justify-content: center;
        }
        .login-container {
            padding: 30px;
            border-radius: 0 0 15px 15px;
        }
        .image-content h3 {
            font-size: 28px;
        }
        .image-content p {
            font-size: 16px;
        }
    }
    </style>
</head>
<body>

<div class="outer-container">
    <div class="image-panel">
        <div id="slideshow-bg"></div>
        <div class="image-content">
            <h3 id="slide-title">Selamat Datang</h3>
            <p id="slide-description">Temukan sejarah dan budaya Kota Kembang.</p>
        </div>
    </div>

    <div class="login-container">
        <div class="logo-header"><span>WeBandoo+</span></div>
        <div class="greeting">
            <h1>Buat Akun</h1>
            <p>Daftarkan diri Anda untuk pengalaman lebih baik.</p>
        </div>

        <form action="proses_register.php" method="POST">
            <div class="input-box"><input type="text" name="nama" placeholder="Nama Lengkap" required></div>
            <div class="input-box"><input type="email" name="email" placeholder="Email" required></div>
            <div class="input-box pass-container">
                <input type="password" name="password" id="password" placeholder="Password" required>
                <i class="fa-regular fa-eye toggle-pass" id="togglePassword"></i>
            </div>
            <button type="submit" class="btn">Daftar Sekarang</button>
        </form>

        <div class="register">
            Sudah punya akun? <a href="login.php">Masuk di sini</a>
        </div>
    </div>
</div>

<script>
    const slides = [ /**/ ];

    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });
</script>
</body>
</html>