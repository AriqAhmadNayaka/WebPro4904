<style>
    *{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family: Arial, Helvetica, sans-serif;
}

body{
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background:#f5f5f5;
    overflow:hidden;
}

/* BACKGROUND BLOB */
.background .blob{
    position:absolute;
    width:400px;
    height:400px;
    border-radius:50%;
    filter:blur(120px);
    z-index:0;
}

.blob1{
    background:#7a7cff;
    left:-100px;
    top:200px;
}

.blob2{
    background:#ffb6c1;
    right:-120px;
    bottom:-120px;
}

/* CONTAINER */
.container{
    width:380px;
    padding:30px;
    border-radius:20px;
    background:rgba(255,255,255,0.6);
    backdrop-filter:blur(15px);
    box-shadow:0 10px 30px rgba(0,0,0,0.15);
    text-align:center;
    z-index:2;
}

/* TITLE */
h2{
    margin-bottom:20px;
    color:#7aa;
}

/* TEXT */
p{
    margin-bottom:15px;
    color:#555;
}

/* INPUT */
input{
    width:100%;
    padding:15px;
    margin:10px 0;
    border-radius:40px;
    border:3px solid transparent;
    background:
        linear-gradient(white,white) padding-box,
        linear-gradient(45deg,#d9a7c7,#fffcdc,#89f7fe,#66a6ff) border-box;
    outline:none;
    font-size:14px;
}

/* BUTTON */
button{
    width:100%;
    padding:15px;
    border:none;
    border-radius:40px;
    margin-top:10px;
    font-size:18px;
    font-weight:bold;
    cursor:pointer;

    background:linear-gradient(90deg,#7ec8e3,#eec0c6);
    box-shadow:0 8px 15px rgba(0,0,0,0.15);
    color:white;
    transition:0.3s;
}

button:hover{
    transform:scale(1.05);
}

/* LINK */
a{
    display:block;
    margin-top:12px;
    text-decoration:none;
    color:#6bb6d6;
    font-size:14px;
}

/* DATA CARD */
.data-item{
    background:rgba(255,255,255,0.7);
    padding:12px;
    margin:10px 0;
    border-radius:15px;
    text-align:left;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

.data-item b{
    color:#444;
}

.data-item a{
    display:inline;
    margin-right:10px;
    font-size:12px;
    color:#ff7a7a;
}
</style>

<?php
include "koneksi.php";

if (isset($_POST['submit'])) {
    $nama = $_POST['nama'];
    $ket = $_POST['keterangan'];

    mysqli_query($conn, "INSERT INTO data (nama, keterangan) VALUES ('$nama','$ket')");
    header("Location: tampil.php");
}
?>

<form method="post">
    <input type="text" name="nama" placeholder="Nama">
    <input type="text" name="keterangan" placeholder="Keterangan">
    <button name="submit">Simpan</button>
</form>