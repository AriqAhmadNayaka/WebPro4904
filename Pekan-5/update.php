<?php
include("Koneksi.php"); //
session_start();

// mencek apakah user sudah login
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}

//ambil data lama untuk ditampilkan di form (Proses GET)
$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: dashboard.php");
    exit;
}

$result = $conn->query("SELECT * FROM user1 WHERE id = $id"); //
$data = $result->fetch_assoc();

//proses saat tombol "simpan perubahan" diklik
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_update = $_POST["id"];
    $name = $_POST["name"];
    $addres = $_POST["addres"];
    $number = $_POST["number"];
    $gender = $_POST["gender"];
    $date = $_POST["date"];
    $weight = $_POST["weight"];
    $height = $_POST["height"];
    $jeniskanker = $_POST["text"];

    // untuk memperbarui data yang sudah di update
    $sql = "UPDATE user1 SET nama=?, alamat=?, notlp=?, jeniskelamin=?, tanggallahir=?, beratbadan=?, tinggibadan=?, jeniskanker=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssi", $name, $addres, $number, $gender, $date, $weight, $height, $jeniskanker, $id_update);

    if ($stmt->execute()) {
        // Jika berhasil, lempar balik ke dashboard
        header("Location: dashboard.php?status=update_berhasil");
        exit();
    } else {
        echo "Gagal memperbarui data: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Data Pasien - LifeTrack</title>
    <link rel="stylesheet" href="style.css"> </head>
<body>
    <div class="container">
        <h2 style="color: #0f766e;">Edit Data Pasien</h2>
        <div class="card">
            <form method="POST">
                <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
                
                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" name="name" value="<?php echo $data['nama']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Alamat</label>
                    <input type="text" name="addres" value="<?php echo $data['alamat']; ?>" required>
                </div>

                <div style="margin-top: 20px;">
                    <button type="submit" class="btn-submit">Simpan Perubahan</button>
                    <a href="dashboard.php" style="text-decoration: none; color: gray; margin-left: 10px;">Batal</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
