<style>
    :root {
    --main-color: #4a6fa5;
    --glass-bg: rgba(255, 255, 255, 0.5);
    --input-bg: rgba(255, 255, 255, 0.85);
}

body {
    margin: 0;
    font-family: Arial, sans-serif;
    background-color: #f8f9fa;
}

.edit-container {
    max-width: 700px;
    margin: 60px auto;
    padding: 30px;
    border-radius: 20px;
    background: var(--glass-bg);
    backdrop-filter: blur(10px);
    box-shadow: 0 8px 32px rgba(0,0,0,0.08);
}

.edit-container h2 {
    text-align: center;
    margin-bottom: 25px;
    color: #333;
}

.edit-form {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.input-group {
    display: flex;
    flex-direction: column;
}

.input-group label {
    font-size: 0.9em;
    font-weight: bold;
    margin-bottom: 5px;
    color: #555;
}

.input-group input {
    padding: 10px;
    border-radius: 10px;
    border: none;
    background: var(--input-bg);
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.input-group input:focus {
    outline: none;
    background: white;
    box-shadow: 0 0 0 3px rgba(74,111,165,0.3);
}

.input-group input[type="file"] {
    background: white;
}

.btn-update {
    grid-column: span 2;
    padding: 12px;
    border-radius: 12px;
    border: none;
    background: rgba(40,167,69,0.85);
    color: white;
    font-weight: bold;
    cursor: pointer;
    transition: 0.3s;
}

.btn-update:hover {
    background: rgba(40,167,69,1);
}

.edit-title {
    font-size: 26px;
    font-weight: bold;
    color: #333;
    text-align: center;
    margin-bottom: 25px;
}

.edit-box {
    max-width: 700px;
    margin: 50px auto;
    padding: 30px;
    border-radius: 20px;
    background: rgba(255, 255, 255, 0.5);
    backdrop-filter: blur(10px);
    box-shadow: 0 8px 32px rgba(0,0,0,0.08);
}

.edit-input {
    width: 100%;
    padding: 12px 15px;
    margin-bottom: 15px;
    border: none;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.85);
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    font-size: 14px;
    transition: 0.3s;
}

.edit-input:focus {
    outline: none;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(74, 111, 165, 0.3);
}

.file-input {
    width: 100%;
    padding: 10px;
    border-radius: 12px;
    background: #fff;
    border: 1px dashed #ccc;
    cursor: pointer;
    margin-bottom: 20px;
}

.btn-update {
    width: 100%;
    padding: 12px;
    border-radius: 12px;
    border: none;
    background: rgba(40, 167, 69, 0.85);
    color: white;
    font-weight: bold;
    font-size: 15px;
    cursor: pointer;
    transition: 0.3s;
}

.btn-update:hover {
    background: rgba(40, 167, 69, 1);
    transform: translateY(-2px);
}

.btn-update:active {
    transform: scale(0.98);
}
</style>

<?php
session_start();
if(!isset($_SESSION['login'])){
    header("Location: login.php");
    exit;
}

include "koneksi.php"; //Menghubungkan file koneksi.php
include "Pasien.php"; //Menghubungkan file Pasien.php

$db = new Koneksi(); //Membuat objek dari class Koneksi untuk akses database
$pasien = new Pasien($db->conn); //Membuat objek Pasien dengan parameter koneksi database

$id = $_GET['id']; //Mengambil nilai id dari URL
$data = $pasien->getById($id); //Mengambil data pasien berdasarkan id dari database
?>

<div class="edit-box">
    <h2 class="edit-title">Edit Data Pasien</h2>

    <form method="POST" enctype="multipart/form-data"> //mengunakan method post agar data dikirim secara aman
        <input type="text" name="nama" class="edit-input" placeholder="Nama"> //Input untuk nama pasien

        <input type="date" name="tanggal" class="edit-input"> //Input untuk tanggal

        <input type="text" name="jk" class="edit-input" placeholder="Jenis Kelamin"> //Input jenis kelamin

        <input type="text" name="berat" class="edit-input" placeholder="Berat"> //Input berat badan

        <input type="text" name="tinggi" class="edit-input" placeholder="Tinggi"> //Input tinggi badan

        <input type="text" name="lingkar" class="edit-input" placeholder="Lingkar Kepala"> //Input lingkar kepala

        <input type="file" name="file" class="file-input"> //Input untuk upload file

        <button class="btn-update">Update Data</button> //Tombol untuk submit/update data
    </form>
</div>