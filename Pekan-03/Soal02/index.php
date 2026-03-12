<?php

$file = "data.json";
$data = json_decode(file_get_contents($file), true);

if(!$data){
$data = [];
}

/* Tambah Data */
if(isset($_POST['tambah'])){

$nim = $_POST['nim'];
$nama = $_POST['nama'];
$jurusan = $_POST['jurusan'];

$data[] = [
"nim"=>$nim,
"nama"=>$nama,
"jurusan"=>$jurusan
];

file_put_contents($file, json_encode($data));

header("Location:index.php");
exit();

}
?>

<!DOCTYPE html>
<html>
<head>
<title>Data Mahasiswa</title>
<style>

body{
font-family:Arial;
background:#f4f4f4;
}

.container{
width:70%;
margin:auto;
margin-top:40px;
}

input{
width:100%;
padding:10px;
margin:5px 0;
}

button{
padding:10px;
background:blue;
color:white;
border:none;
}

table{
width:100%;
margin-top:20px;
border-collapse:collapse;
}

th,td{
border:1px solid #ccc;
padding:10px;
text-align:center;
}

th{
background:blue;
color:white;
}

</style>
</head>

<body>

<div class="container">

<h2>Tambah Data Mahasiswa</h2>

<form method="POST">

<input type="text" name="nim" placeholder="NIM" required>

<input type="text" name="nama" placeholder="Nama" required>

<input type="text" name="jurusan" placeholder="Jurusan" required>

<button type="submit" name="tambah">Tambah</button>

</form>

<h2>Daftar Mahasiswa</h2>

<table>

<tr>
<th>No</th>
<th>NIM</th>
<th>Nama</th>
<th>Jurusan</th>
</tr>

<?php

$no = 1;

foreach($data as $mhs){

echo "<tr>
<td>$no</td>
<td>{$mhs['nim']}</td>
<td>{$mhs['nama']}</td>
<td>{$mhs['jurusan']}</td>
</tr>";

$no++;

}

?>

</table>

</div>

</body>
</html>