<?php
session_start(); //untuk memulai session

//untuk mengecek apakah pengguna sudah login
if (!isset($_SESSION['user'])) {
    header("Location: login.php"); //jika belum akan di arahkan ke login page
    exit; 
}

class Database //class untuk mengatur semua yang berhubungan dengan koneksi database
{
    protected $conn; //untuk menyimpan koneksi database, hanya bisa di akses class itu sendiri dan turunan nya

    public function __construct() //method yang otomatis dijalankan saat objek dari class database dibuat
    {
        $this->conn = new mysqli("localhost", "root", "", "web_pro"); //membuat koneksi ke database MySQL

        //mengecek apakah koneksi berhasil atau tidak
        if ($this->conn->connect_error) {
            die("Koneksi gagal: " . $this->conn->connect_error);
        }
    }

    //method ini digunakan untuk menjalankan query SQL
    protected function query(string $sql)
    {
        return $this->conn->query($sql);
    }

    protected function prepare(string $sql)
    {
        return $this->conn->prepare($sql);
    }

    public function __destruct() //method yang akan otomatis dijalankan ketika objek sudah tidak digunakan lagi
    {
        //untuk menutup koneksi database
        if ($this->conn instanceof mysqli) {
            $this->conn->close();
        }
    }
}

class User //class induk yang meyimpan data pengguna
{
    protected $nama;
    protected $alamat;
    protected $notlp;
    //constructor, method yang otomatis dijalankan saat objek dari class user dibuat
    public function __construct(string $nama, string $alamat, string $notlp)
    {
        //atribut dari class user
        $this->nama = $nama;
        $this->alamat = $alamat;
        $this->notlp = $notlp;
    }
    //getter untuk mengambil nilai dari atribut
    public function getNama(): string 
    {
        return $this->nama;
    }

    public function getAlamat(): string
    {
        return $this->alamat;
    }

    public function getNotlp(): string
    {
        return $this->notlp;
    }
}

class Pasien extends User //class turunan dari user
{
    //properti yang dimiliki pasies
    private $gender;
    private $tanggal;
    private $berat;
    private $tinggi;
    private $kanker;

    public function __construct( //constructor dari class pasien
        string $nama, //parameter constructor
        string $alamat,
        string $notlp,
        string $gender,
        string $tanggal,
        string $berat,
        string $tinggi,
        string $kanker
    ) {
        parent::__construct($nama, $alamat, $notlp); //untuk memanggil constructor dari class pasien
        $this->gender = $gender; //untuk mengisi nilai properti dari class pasien
        $this->tanggal = $tanggal;
        $this->berat = $berat;
        $this->tinggi = $tinggi;
        $this->kanker = $kanker;
    }

    //method getter untuk mengambil nilai
    public function getGender(): string
    {
        return $this->gender;
    }

    public function getTanggal(): string
    {
        return $this->tanggal;
    }

    public function getBerat(): string
    {
        return $this->berat;
    }

    public function getTinggi(): string
    {
        return $this->tinggi;
    }

    public function getKanker(): string
    {
        return $this->kanker;
    }
}

class PasienController extends Database //class turunan dari databe 
{
    public function getAll(): mysqli_result //mengambil seluruh data pasien dari database
    {   //perintah untuk mengambil data di tabel user1
        return $this->query("SELECT * FROM user1 WHERE email IS NULL OR email = '' ORDER BY id DESC");
    }

    public function create(Pasien $pasien): bool //menyimpan data pasien ke database
    {
        //untuk menambah data baru ke dalam tabel user1
        $sql = "INSERT INTO user1
            (nama, alamat, notlp, jeniskelamin, tanggallahir, beratbadan, tinggibadan, jeniskanker)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->prepare($sql); //prepared statement untuk menjalankan query

        if (!$stmt) { //mengecek proses prepare statement
            return false;
        }

        $stmt->bind_param( //untuk menghubungkan parameter dengan nilai dari object
            "ssssssss",
            $pasien->getNama(),
            $pasien->getAlamat(),
            $pasien->getNotlp(),
            $pasien->getGender(),
            $pasien->getTanggal(),
            $pasien->getBerat(),
            $pasien->getTinggi(),
            $pasien->getKanker()
        );

        $isSuccess = $stmt->execute(); 
        $stmt->close(); //statement ditutup

        return $isSuccess;
    }
}
//untuk mengalihkan halaman ke dashboard dan membawa pesan
function redirectWithMessage(string $message, string $type = "success"): void
{   //untuk mengirim pesan melalui URL
    header("Location: dashboard.php?message=" . urlencode($message) . "&type=" . urlencode($type));
    exit;
}

$controller = new PasienController(); //untuk membuat objek dari class PasienController agar bisa di gunakan
$message = $_GET["message"] ?? "";
$messageType = $_GET["type"] ?? "success";
//mencek form yang dikirim menggunakan metode POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $pasien = new Pasien( //form dimasukkan ke dalam object Pasien
        trim($_POST["name"] ?? ""),
        trim($_POST["addres"] ?? ""),
        trim($_POST["number"] ?? ""),
        trim($_POST["gender"] ?? ""),
        trim($_POST["date"] ?? ""),
        trim($_POST["weight"] ?? ""),
        trim($_POST["height"] ?? ""),
        trim($_POST["text"] ?? "")
    );

    $saved = $controller->create($pasien); //menyimpan data ke database
    redirectWithMessage( //redirect dengan pesan
        $saved ? "Data pasien berhasil disimpan." : "Data pasien gagal disimpan.",
        $saved ? "success" : "error"
    );
}

$data = $controller->getAll(); //mengambil data untuk ditampilkan
//menyimpan
$loggedInUser = $_SESSION["user"]["email"] ?? $_SESSION["user"]["nama"] ?? "Pengguna";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - LifeTrack</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #f5f7fa;
            color: #1f2937;
        }

        .container {
            width: 100%;
            min-height: 100vh;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 23px 100px;
            margin-bottom: 30px;
            box-shadow: 0 1px 10px 0 rgba(0, 0, 0, 0.2);
            position: sticky;
            top: 0;
            left: 0;
            background-color: white;
            z-index: 10;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 8px;
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
        }

        .profile {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
        }

        .profile-badge {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: #d1fae5;
            color: #065f46;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 700;
        }

        .page-header {
            width: 90%;
            margin: 0 auto 20px;
        }

        h1 {
            font-size: 40px;
            margin-bottom: 5px;
            color: #064e3b;
        }

        .subtitle {
            color: #6b7280;
        }

        .box,
        .box2 {
            width: 90%;
            margin: 0 auto 20px;
            padding: 20px;
            border-radius: 14px;
            border: 1px solid #e5e8ea;
            background: white;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }

        label {
            display: block;
            font-size: 14px;
            margin-bottom: 5px;
            color: #34495e;
            font-weight: 600;
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

        input:focus,
        select:focus {
            box-shadow: 0 0 0 2px rgba(68, 139, 132, 0.25);
        }

        .button-row {
            display: flex;
            gap: 12px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        button,
        .secondary-link,
        .action-link {
            padding: 10px 18px;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: 0.3s ease;
        }

        button {
            background-color: #448b84;
            color: white;
        }

        button:hover,
        .secondary-link:hover,
        .action-link:hover {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.18);
            transform: translateY(-1px);
        }

        .secondary-link {
            background: #e5e7eb;
            color: #374151;
        }

        .action-link.edit {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .action-link.delete {
            background: #fee2e2;
            color: #dc2626;
        }

        .notice {
            width: 90%;
            margin: 0 auto 20px;
            padding: 14px 18px;
            border-radius: 12px;
            font-weight: 500;
        }

        .notice.success {
            background: #d1fae5;
            color: #065f46;
        }

        .notice.error {
            background: #fee2e2;
            color: #991b1b;
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            gap: 12px;
            flex-wrap: wrap;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 10px;
            font-size: 14px;
            text-align: center;
            vertical-align: middle;
        }

        table th {
            color: #064e3b;
            font-weight: 600;
            background: #e6f4f1;
        }

        table tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .empty-state {
            text-align: center;
            color: #6b7280;
            padding: 24px 0;
        }

        @media (max-width: 900px) {
            .navbar {
                padding: 20px;
                flex-direction: column;
                gap: 14px;
            }

            .menu {
                flex-wrap: wrap;
                justify-content: center;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }
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
                <li><a href="dashboard.php">Beranda</a></li>
                <li><a href="dashboard.php">Data Pasien</a></li>
                <li><a href="#">Jadwal</a></li>
                <li><a href="#">Rekomendasi Kegiatan</a></li>
                <li><a href="#">Nutrisi dan Gizi</a></li>
            </ul>

            <div class="profile">
                <div class="profile-badge"><?= strtoupper(substr($loggedInUser, 0, 1)); ?></div>
                <span><?= htmlspecialchars($loggedInUser); ?></span>
            </div>
        </nav>

        <div class="page-header">
            <h1>Input Data Pasien</h1>
            <p class="subtitle">Dashboard utama untuk tambah data pasien dan melihat seluruh data.</p>
        </div>

        <?php if ($message !== ""): ?>
            <div class="notice <?= htmlspecialchars($messageType); ?>">
                <?= htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <div class="box">
            <form method="POST">
                <div class="form-grid">
                    <div>
                        <label>Nama</label>
                        <input type="text" name="name" placeholder="Masukkan nama" required>
                    </div>

                    <div>
                        <label>Alamat</label>
                        <input type="text" name="addres" placeholder="Masukkan alamat" required>
                    </div>

                    <div>
                        <label>Nomor Telepon</label>
                        <input type="text" name="number" placeholder="08xxxxxxxxxx" maxlength="12" required>
                    </div>

                    <div>
                        <label>Jenis Kelamin</label>
                        <select name="gender" required>
                            <option value="">Pilih jenis kelamin</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>

                    <div>
                        <label>Tanggal Lahir</label>
                        <input type="date" name="date" required>
                    </div>

                    <div>
                        <label>Berat Badan (kg)</label>
                        <input type="number" name="weight" placeholder="Contoh: 50" required>
                    </div>

                    <div>
                        <label>Tinggi Badan (cm)</label>
                        <input type="number" name="height" placeholder="Contoh: 170" required>
                    </div>

                    <div>
                        <label>Jenis Kanker</label>
                        <input type="text" name="text" placeholder="Masukkan jenis kanker" required>
                    </div>
                </div>

                <div class="button-row">
                    <button type="submit">Simpan</button>
                </div>
            </form>
        </div>

        <div class="box2">
            <div class="table-header">
                <h2>Daftar Pasien</h2>
                <span><?= $data->num_rows; ?> data ditemukan</span>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>No. Telp</th>
                        <th>Jenis Kelamin</th>
                        <th>Tgl Lahir</th>
                        <th>BB (kg)</th>
                        <th>TB (cm)</th>
                        <th>Jenis Kanker</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($data->num_rows > 0): ?>
                        <?php while ($p = $data->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($p["nama"]); ?></td>
                                <td><?= htmlspecialchars($p["alamat"]); ?></td>
                                <td><?= htmlspecialchars($p["notlp"]); ?></td>
                                <td><?= htmlspecialchars($p["jeniskelamin"]); ?></td>
                                <td><?= htmlspecialchars($p["tanggallahir"]); ?></td>
                                <td><?= htmlspecialchars($p["beratbadan"]); ?></td>
                                <td><?= htmlspecialchars($p["tinggibadan"]); ?></td>
                                <td><?= htmlspecialchars($p["jeniskanker"]); ?></td>
                                <td>
                                    <div class="button-row" style="margin-top: 0; justify-content: center;">
                                        <a class="action-link edit" href="edit.php?id=<?= (int) $p["id"]; ?>">Edit</a>
                                        <a class="action-link delete" href="delete.php?id=<?= (int) $p["id"]; ?>" onclick="return confirm('Yakin ingin menghapus data ini?');">Hapus</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="empty-state">Belum ada data pasien yang tersimpan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
