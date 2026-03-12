<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Halaman Masuk</title>
    <link rel="stylesheet" href="stylelogin.css" />
  </head>
  <body>
    <div class="container">
      <h2>Masuk</h2>

      <!-- Formulir ini digunakan untuk memasukkan data pengguna berupa email dan password -->
      <!-- menggunakan method="get" untuk mengirim data ke server melalui URL -->
      <!-- menggunakan kata kunci (name) pada setiap input, tujuan nya agar data  -->
      <form method="get" action="">
        <input type="text" name="email" placeholder="Email" required />
        <input type="password" name="password" placeholder="Password" required/>
        <input type="submit" value="Masuk"/>
      </form>

      <!-- menampilkan data yang di input user dengan php -->
      <!-- menggunakan $_GET untuk mengambil data dari URL -->
      <!-- variabel $email, $password -->
     <?php
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['email']) && isset($_GET['password'])) {
        $email = htmlspecialchars($_GET['email']);
        $password = htmlspecialchars($_GET['password']);

        // menampilkan data yang dimasukkan oleh pengguna
        // menggunakan htmlspecialchars untuk mencegah serangan XSS (Cross-Site Scripting)
        // menggunakan echo untuk menampilkan data yang dimasukkan oleh pengguna
        echo "<h3>Data yang dimasukkan:</h3>";
        echo "Email: " . $email . "<br>";
        echo "Password: " . $password . "<br>";
    }
    ?>
    </div>
  </body>
</html>
