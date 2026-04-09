<?php
include '../config/Database.php';
include '../models/Patient.php';

$db = (new Database())->connect();
$p = new Patient($db);

$p->id = $_POST['id'] ?? null;
$p->nama = $_POST['nama'];
$p->umur = $_POST['umur'];
$p->penyakit = $_POST['penyakit'];

$file = $_FILES['file']['name'];
$tmp = $_FILES['file']['tmp_name'];

if ($file) {
    $file = time() . "_" . $file;
    move_uploaded_file($tmp, "../uploads/" . $file);
    $p->file = $file;
}

if (isset($_POST['simpan'])) {
    $p->save();
    header("Location: ../views/dashboard.php");
}

if (isset($_GET['delete'])) {
    $p->id = $_GET['delete'];
    $p->delete();
    header("Location: ../views/dashboard.php");
}
?>