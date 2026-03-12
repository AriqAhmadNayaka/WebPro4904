<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login Nuids</title>

    <link rel="stylesheet" href="global.css" />
    <link rel="stylesheet" href="login.css" />
  </head>
  <body>
    <div class="container-login">
      <h2 class="greeting">Hi! Selamat Datang Kembali di Nuids.</h2>

      <div class="acrylic-card">
        <div class="logo-container">
          <img src="logo.png" alt="" width="50px" />
        </div>

        // Form login
        <form id="loginForm" method="POST">
          <div class="form-group">
            <input
              id="loginEmail"
              type="email"
              name="email"
              class="custom-input input-teal"
              placeholder="Email"
              required
            />
          </div>

          <div class="form-group">
            <input
              id="loginPassword"
              type="password"
              name="password"
              class="custom-input input-pink"
              placeholder="Password"
              required
            />
          </div>

          <button type="submit" class="btn-login" id="loginBtn">
            <span class="btn-text">Login</span>
            <div class="loader"></div>
          </button>
        </form>
        <a href="register.php">Belum punya akun? Daftar sekarang</a>
      </div>

      <?php 
      
      // Proses login
      if ($_SERVER['REQUEST_METHOD'] == "POST") {
        // Ambil data dari form
        $email = $_POST['email'];
        $password = $_POST['password'];
        $users = file("users.txt");
        $login_success = false;

        // Cek kecocokan email dan password dengan data di users.txt
        foreach($users as $user){
        // Format data di users.txt: nama|email|password|role
        list($nama,$u_email,$u_password,$role) = explode("|",$user);
        // Trim data untuk menghindari spasi yang tidak diinginkan
        if(trim($u_email) == $email && trim($u_password) == $password){
          // Login berhasil
          $login_success = true;
          $nama_user = $nama;
          break;
        }
      }

        if($login_success){
          echo "Login berhasil! Selamat datang $nama_user";
        }else{
          echo "Email atau password salah.";
        }
      }

      ?>

    </div>

    <script src="main.js"></script>
  </body>
</html>
