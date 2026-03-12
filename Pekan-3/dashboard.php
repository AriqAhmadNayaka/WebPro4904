<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LifeTrack - Data Pasien</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #f5f7fa;
        }

        .container {
            width: 100%;
            margin: 0;
            padding: 0;
        }

        .box {
            flex: 1;
            padding: 20px;
            border-radius: 14px;
            border: 1px solid #e5e8ea;
            background: white;
            width: 90%;
            margin: 0 auto;

        }

        .box2 {
            flex: 1;
            margin-top: 20px;
            padding: 20px;
            border-radius: 14px;
            border: 1px solid #e5e8ea;
            background: white;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 23px 100px;
            margin-bottom: 30px;
            box-shadow: 0px 1px 10px 0px rgba(0, 0, 0, 0.2);
            position: sticky;
            top: 0;
            left: 0;
            background-color: white;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 8px
        }

        .bulb {
            width: 16px;
            height: 16px;
            background: #f7c948;
            border-radius: 50%;
        }

        .menu {
            display: flex;
            list-style: none;
            gap: 25px;
        }

        .menu li a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
        }

        .menu li a:hover {
            color: #52a8a0;
            text-direction: none;
        }

        .profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .profile img {
            width: 35px;
            height: 35px;
            border-radius: 50%;
        }

        h1 {
            font-size: 40px;
            margin-bottom: 5px;
            color: #064e3b;
            margin-left: 5%;
        }

        h2 {
            color: #444;
            margin-bottom: 0px;
        }

        label {
            display: block;
            font-size: 14px;
            margin-bottom: 5px;
            margin-top: 20px;
            color: #34495e;
        }

        input,
        select {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 10px;
            background-color: rgba(255, 255, 255, 0.9);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
            outline: none;
            transition: 0.3s;
        }

        button {
            width: 100%;
            margin-top: 20px;
            padding: 10px;
            background-color: #448b84;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            transition: 0.5s ease;
        }

        button:hover {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
            transform: translateY(-1px);
        }

        #patientList {
            list-style: none;
            margin-top: 15px;
        }

        #patientList li {
            background: #f5f7fa;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 10px;
            font-size: 14px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.08);
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 10px;
            font-size: 14px;
            text-align: center;
        }

        table th {
            color: #064e3b;
            font-weight: 600;
        }

        table tr:nth-child(even) {
            background-color: #f9fafb;
        }
    </style>
</head>

<body>

    <div class="container">

        <nav class="navbar">
            <div class="logo">
                <span class="bulb"></span>
                <h2>LifeTrack</h2>
            </div>

            <ul class="menu">
                <li><a href="dokter.html">Beranda</a></li>
                <li><a href="datapasien.html">Data pasien</li>
                <li><a href="#">Jadwal</a></li>
                <li><a href="#">Rekomendasi Kegiatan</a></li>
                <li><a href="#">Nutrisi dan Gizi</a></li>
            </ul>

            <div class="profile">
                <img src="profilorang.jpg" alt="User">
                <span>Andi</span>
            </div>
        </nav>

        <h1>Input Data Pasien</h1>

        <div class="box">

            <label>Nama</label>
            <input type="name" id="name" placeholder="Masukan Nama" required>

            <label>Alamat</label>
            <input type="addres" id="addres" placeholder="Masukan Alamat" required>

            <label>Nomor Telepon</label>
            <input type="number" id="number" placeholder="08xxxxxxxx" pattern="{0-9}{12}" required>

            <label>Jenis Kelamin</label>
            <input type="gender" id="gender" placeholder="Laki-laki/Perempuan" required>

            <label>Tanggal Lahir</label>
            <input type="date" id="date" placeholder="DD/MM/YYYY" required>

            <label>Berat Badan (kg)</label>
            <input type="number" id="weight" placeholder="Contoh: 50kg" required>

            <label>Tinggi Badan (cm)</label>
            <input type="number" id="height" placeholder="Contoh: 170cm" required>

            <label>Jenis Kanker</label>
            <input type="text" id="text" placeholder="Masukan Jenis Kanker" required>

            <div class="input-grup">
                <button onclick="tambah()">Tambah</button>
            </div>

            <div class="box2">
                <h2>Daftar Pasien</h2>
                <table id="patientTable" style="width:100%; border-collapse: collapse; margin-top:15px;">
                    <thead>
                        <tr style="background:#e6f4f1;">
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>No. Telp</th>
                            <th>Jenis Kelamin</th>
                            <th>Tgl Lahir</th>
                            <th>BB (kg)</th>
                            <th>TB (cm)</th>
                            <th>Jenis Kanker</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>

<?php

if(!isset($_SESSION['pasien'])){ //isset digunakan untuk memeriksa apakah suatu variabel telah dideklarasikan (diatur dan nilai nya bukan null)
    $_SESSION['pasien'] = []; //session disini untuk menyimpan data sementara
}

if(isset($_POST['tambah'])){ //lalu disini menggunakan method POST

    $data = [ //ini adalah variabel
        "name" => $_POST['name'], // maksud kode ini => adalah untuk menyimpan nilai
        "address" => $_POST['address'], //dan seterusnya
        "number" => $_POST['number'],
        "gender" => $_POST['gender'],
        "date" => $_POST['date'],
        "weight" => $_POST['weight'],
        "height" => $_POST['height'],
        "jenisKanker" => $_POST['jenisKanker']
    ];

    $_SESSION['pasien'][] = $data; //untuk menambahkan data kedalan session pasien
}
?>

</body>

</html>