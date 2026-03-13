<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register Nuids</title>
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="global.css" />
    <link rel="stylesheet" href="register.css" />
  </head>
  <body>
    <div class="container-register">
      <h2 class="greeting">Hi! Selamat Datang di Nuids.</h2>

      <div class="acrylic-card">
        <div class="logo-container">
          <img src="logo.png" alt="" width="50px" />
        </div>

        // Form Registrasi
        <form id="registerForm" action="" method="POST">
          <div class="form-group">
            <input
              id="regName"
              type="text"
              name="nama"
              class="custom-input input-teal"
              placeholder="Nama Lengkap"
              required
            />
          </div>

          <div class="form-group">
            <input
              id="regEmail"
              type="email"
              name="email"
              class="custom-input input-pink-blue"
              placeholder="Email"
              required
            />
          </div>

          <div class="form-group">
            <input
              id="regPassword"
              type="password"
              name="password"
              class="custom-input input-pink"
              placeholder="Password"
              required
            />
          </div>

          <div class="form-group">
            <input
              id="regConfirmPassword"
              type="password"
              name="confirm_password"
              class="custom-input input-pink"
              placeholder="Konfirmasi Password"
              required
            />
          </div>

          <div class="form-group">
            <div class="role-group">
              <label>Pilih tipe akun:</label>
              <div class="role-options">
                <label>
                  <input type="radio" name="regRole" value="user" checked />
                  Pengguna
                </label>
                <label>
                  <input type="radio" name="regRole" value="doctor" />
                  Dokter
                </label>
              </div>
            </div>
          </div>

          <div
            class="form-group"
            id="certGroup"
            style="display: none; flex-direction: column; align-items: center"
          >
            <div style="width: 85%; text-align: left">
              <label for="regCertFile">Upload sertifikasi (STR/SIP) *</label>
            </div>
            <input
              id="regCertFile"
              type="file"
              class="custom-input"
              accept=".pdf,.jpg,.jpeg,.png"
            />
          </div>

          <button type="submit" class="btn-register" id="registerBtn">
            <span class="btn-text">Register</span>
            <div class="loader"></div>
          </button>
        </form>
        <a href="login.php">Sudah punya akun? Login</a>
      </div>
    <?php 
    
    // Proses form registrasi
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["nama"]) && isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["confirm_password"]) && isset($_POST["regRole"])){
      // Ambil data dari form
      $nama = $_POST["nama"];
      $email = $_POST["email"];
      $password = $_POST["password"];
      $confirm_password = $_POST["confirm_password"];
      $regRole = $_POST["regRole"];

      // Validasi input
      if (empty($nama) || empty($email) || empty($password) || empty($confirm_password)) {
        echo "Semua field harus diisi.";
      } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {// Validasi format email
        echo "Format email tidak valid.";
      } elseif ($password !== $confirm_password) { // Validasi kecocokan password
        echo "Password dan konfirmasi password tidak cocok.";
      } else {
        // Simpan data ke file users.txt (format: nama|email|password|role)
        $data = $nama . "|" . $email . "|" . $password . "|" . $regRole . "\n";

        file_put_contents("users.txt", $data, FILE_APPEND);

        echo "Registrasi berhasil! <br>";
        echo "<a href='login.php'>Login sekarang</a>";
      }
    }

    ?>
    </div>


    <script src="main.js"></script>
    <script>
      document.addEventListener("DOMContentLoaded", function () {
        const roleRadios = document.querySelectorAll('input[name="regRole"]');
        const certGroup = document.getElementById("certGroup");

        if (roleRadios && certGroup) {
          roleRadios.forEach((radio) => {
            radio.addEventListener("change", function () {
              if (this.value === "doctor") {
                certGroup.style.display = "flex";
              } else {
                certGroup.style.display = "none";
                const f = document.getElementById("regCertFile");
                if (f) f.value = "";
              }
            });
          });
        }
      });
    </script>
  </body>
</html>