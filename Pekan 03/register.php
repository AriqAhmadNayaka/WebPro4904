<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Daftar</title>
    <link rel="stylesheet" href="stylelogin.css" />
  </head>
  <body>
    <div class="container">
      <h2>Daftar</h2>

      <!-- Formulir ini digunakan untuk memasukkan data pengguna berupa nama, alamat, jenis kelamin, usia, dan password -->
       <!-- menggunakan kata kunci (name) pada setiap input, tujuan nya agar data  -->
       <!-- Jika tidak ada kata kunci (name), data tidak akan dikirim ke server -->
      <form>
        <input type="text" name="name" placeholder="Nama" required />  
        <input type="text" name="address" placeholder="Alamat" />

        <div class="row">
          <input type="text" name="gender" placeholder="Jenis Kelamin" />
          <input type="number" name="age" placeholder="Usia" />
        </div>

        <div class="row">
          <input type="password" name="password" placeholder="Buat Password" required />
          <input type="password" name="confirm_password" placeholder="Konfirmasi Password" required />
        </div>

        <button type="submit">Buat</button>
        <div class="register-link">
          <span>sudah punya akun? </span>
          <a href="loginlama.html">Login</a>
        </div>
      </form>
    </div>

    <!-- menampilkan data yang di input user dengan php -->
    <!-- menggunakan $_GET untuk mengambil data dari URL -->
    <!-- variabel $name, $address, $gender, $age, $password -->
    <?php
  if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['name']) && isset($_GET['password'])) {
        $name = htmlspecialchars($_GET['name']);
        $address = htmlspecialchars($_GET['address']);
        $gender = htmlspecialchars($_GET['gender']);
        $age = intval($_GET['age']);
        $password = htmlspecialchars($_GET['password']);

      // menampilkan data yang dimasukkan oleh pengguna
      // menggunakan htmlspecialchars untuk mencegah serangan XSS (Cross-Site Scripting) dengan mengubah karakter khusus menjadi entitas HTML
      // menggunakan echo untuk menampilkan data yang dimasukkan oleh pengguna
        echo "<h3>Data yang dimasukkan:</h3>";
        echo "Nama: " . $name . "<br>";
        echo "Alamat: " . $address . "<br>";
        echo "Jenis Kelamin: " . $gender . "<br>";
        echo "Usia: " . $age . "<br>";
        echo "Password: " . $password . "<br>";
    }
    ?>
  </body>
</html>
