<?php
//konfig database
$host = "localhost";
$username = "root";
$password = "";
$database = "web_pro";

//membuat koneksi ke database
$conn = new mysqli(hostname: $host, username: $username, password: $password, database: $database);

//cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

//memastikan data dikirim menggunakan method POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];

    //menghapud data dari tabel
    $sql = "DELETE FROM Data_Pasien WHERE id=?";
    $stmt = $conn->prepare(query: $sql);
    $stmt->bind_param(types: "i", var: $id);

    //menjalankan query
    if ($stmt->execute()) {
        header(header: "Location: dashboard.php"); // redirect ke halaman utama
        exit();
    } else {
        echo "Gagal menghapus data: " . $stmt->error;//jika gagal
    }

    $stmt->close();
}

$conn->close();
?>
