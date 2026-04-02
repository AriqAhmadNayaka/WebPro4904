<?php
$conn = mysqli_connect("localhost", "root", "", "pw_pekan5"); //untuk menghubungkan ke database dengan host localhost, username root, password kosong, dan nama database pw_pekan5

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>