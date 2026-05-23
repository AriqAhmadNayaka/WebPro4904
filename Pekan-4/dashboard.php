<?php
include("Koneksi.php"); //mengambil file koneksi database
session_start(); //memulai session

if(isset($_POST['tambah'])){ //mengecek apakah tombol tambah diklik
echo "test";
    //mengambil data dari HTML//
    $name = $_POST["name"];
    $address = $_POST["address"];
    $number = $_POST["number"];
    $gender = $_POST["gender"];
    $date = $_POST["date"];
    $weight = $_POST["weight"];
    $height = $_POST["height"];
    $jeniskanker = $_POST["text"];

    //query untuk menambahkan data ke tabel user1 yang ada di database//
    $sql = "INSERT INTO user1 (nama, alamat, notlp, jeniskelamin, tanggallahir, beratbadan, tinggibadan, jeniskanker) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql); //Menyiapkan query sebelum diisi data//

    if (!$stmt) {
        die("Prepare gagal: " . $conn->error);
    } //jika query gagal diproses, program berhenti dan menampilkan error

    $stmt->bind_param("ssssssss", $name, $addres, $number, $gender, $date, $weight, $height, $jeniskanker);
    //menghubungkan nilai variabel PHP ke tanda ? di query SQL

    if ($stmt->execute()) { //menjalankan query
        echo "Data berhasil disimpan"; //jika berhasil data di simpan
    } else {
        echo "Execute gagal: " . $stmt->error; //jika gagal tampil error
    }
    //penuptup koneksi
    $stmt->close();
    $conn->close();

    $data = [ //data yang di simpan ke array
        "name" => $_POST['name'],
        "address" => $_POST['addres'],
        "number" => $_POST['number'],
        "gender" => $_POST['gender'],
        "date" => $_POST['date'],
        "weight" => $_POST['weight'],
        "height" => $_POST['height'],
        "jenisKanker" => $_POST['text']
    ];

}
?>

<!DOCTYPE html>
<html lang="id">

<head>
<meta charset="UTF-8">
<title>Dashboard</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<h2>Input Data Pasien</h2>

<form action="" method="POST">
    <input type="text" name="name" placeholder="Nama" required><br>
    <input type="text" name="addres" placeholder="Alamat" required><br>
    <input type="text" name="number" placeholder="No HP" required><br>
    <input type="text" name="gender" placeholder="Gender" required><br>
    <input type="date" name="date" required><br>
    <input type="number" name="weight" placeholder="Berat" required><br>
    <input type="number" name="height" placeholder="Tinggi" required><br>
    <input type="text" name="text" placeholder="Jenis Kanker" required><br>

    <button type="submit" name="tambah">Tambah</button>
    <button onclick="location.href='update.php'">Update Data</button>
    <button onclick="location.href='delete.php'">Hapus Data</button>
</form>

<h2>Data Pasien</h2>

<table border="1">
<tr>
    <th>Nama</th>
    <th>Alamat</th>
    <th>No HP</th>
    <th>Gender</th>
    <th>Tanggal</th>
    <th>BB</th>
    <th>TB</th>
    <th>Kanker</th>
</tr>

<?php foreach($_SESSION['pasien'] as $p){ ?>
<tr>
    <td><?php echo $p['name']; ?></td>
    <td><?php echo $p['address']; ?></td>
    <td><?php echo $p['number']; ?></td>
    <td><?php echo $p['gender']; ?></td>
    <td><?php echo $p['date']; ?></td>
    <td><?php echo $p['weight']; ?></td>
    <td><?php echo $p['height']; ?></td>
    <td><?php echo $p['jenisKanker']; ?></td>
</tr>
<?php } ?>

</table>

</body>
</html>