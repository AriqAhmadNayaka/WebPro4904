<?php
//koneksi ke database
$host = "localhost";
$username = "root";
$password = "";
$database = "web_pro";
//membuat koneksi ke MySQL
$conn = new mysqli(hostname: $host, username: $username, password: $password, database: $database);

if ($conn->connect_error) { // mencek koneksi
    die("Koneksi gagal: " . $conn->connect_error);
}
//mencek request method
if ($_SERVER["REQUEST_METHOD"] == "POST") { //jika berhasil data dikirim dari form
    $name = $_POST["name"];
    $addres = $_POST["addres"];
    $number = $_POST["number"];
    $gender = $_POST["gender"];
    $date = $_POST["date"];
    $weight = $_POST["weight"];
    $height = $_POST["height"];
    $jeniskanker = $_POST["text"];

    //mengubah data tabel pasien
    $sql = "UPDATE Data_Pasien SET name=?, addres=?, number=?, gender=?, date=?, weight=?, height=?, jeniskanker=? WHERE id=?";
    $stmt = $conn->prepare(query: $sql);
    $stmt->bind_param(types: "ssssssssi", var: $name, $addres, $number, $gender, $date, $weight, $height, $jeniskanker, $id);

    //eksekusi code
    if ($stmt->execute()) {
        header(header: "Location: dashboard.php"); // Redirect ke halaman utama setelah update
        exit();
    } else {
        echo "Gagal memperbarui data: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
