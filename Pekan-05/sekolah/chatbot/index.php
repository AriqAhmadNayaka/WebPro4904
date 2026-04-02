<?php
// session_start() wajib dipanggil paling atas agar $_SESSION bisa diakses
// Sesuai modul 5.4.3: session harus dimulai sebelum digunakan
session_start();

// Jika belum login, redirect ke halaman auth
if (!isset($_SESSION['current_user'])) {
    header('Location: ../../auth.php');
    exit;
}

// require koneksi.php untuk menyimpan riwayat upload ke tabel chat_uploads
require '../../koneksi.php';

// Inisialisasi variabel balasan bot, pesan user, dan info upload
$botReply      = "";
$userMessage   = "";
$uploadInfo    = "";
$uploadError   = "";

// HANDLE POST: Upload file dari chatbot
// Upload file menggunakan POST + enctype multipart/form-data sesuai modul 5.4.9
// POST dipilih karena file tidak boleh dikirim via GET (URL tidak bisa membawa binary data)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['chatFile'])) {

    $userMessage = htmlspecialchars(trim($_POST['message'] ?? ''));

    // Cek apakah file benar-benar dikirim dan tidak error
    if ($_FILES['chatFile']['error'] === UPLOAD_ERR_OK) {

        // Folder penyimpanan file upload chatbot
        $uploadDir = __DIR__ . '/uploads/';

        // Dapatkan ekstensi file dan ubah ke huruf kecil agar perbandingan tidak case-sensitive
        $imageFileType = strtolower(pathinfo(
            path: $_FILES['chatFile']['name'],
            flags: PATHINFO_EXTENSION
        ));

        // Format file yang diizinkan di chatbot: gambar dan PDF dokumen laporan
        $allowedTypes = ['jpg', 'jpeg', 'png', 'pdf'];

        // getimagesize() untuk verifikasi file gambar — hanya berlaku untuk jpg/jpeg/png
        // PDF tidak perlu verifikasi getimagesize karena bukan gambar
        $isImage = in_array($imageFileType, ['jpg', 'jpeg', 'png']);
        $check   = $isImage ? getimagesize(filename: $_FILES['chatFile']['tmp_name']) : true;

        if ($check === false || !in_array(needle: $imageFileType, haystack: $allowedTypes)) {
            $uploadError = "Format file tidak valid. Gunakan JPG, PNG, atau PDF.";
        } else {
            // Buat folder uploads jika belum ada
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

            // Hindari duplikasi nama file dengan penomoran otomatis sesuai modul 5.4.9
            $files         = glob(pattern: $uploadDir . "*.*");
            $highestNumber = 0;
            foreach ($files as $file) {
                $filename = pathinfo(path: $file, flags: PATHINFO_FILENAME);
                if (is_numeric(value: $filename) && (int)$filename > $highestNumber) {
                    $highestNumber = (int)$filename;
                }
            }

            // Nama file baru = angka tertinggi + 1
            $namaFile   = ($highestNumber + 1) . '.' . $imageFileType;
            $targetFile = $uploadDir . $namaFile;

            // move_uploaded_file() memindahkan file dari folder sementara ke folder uploads
            if (move_uploaded_file(from: $_FILES['chatFile']['tmp_name'], to: $targetFile)) {

                // Simpan riwayat upload ke tabel chat_uploads di database
                // INSERT INTO sesuai materi CRUD modul 4 yang sudah dipelajari
                $sql  = "INSERT INTO chat_uploads (nama_file, pesan) VALUES (?, ?)";
                $stmt = $conn->prepare(query: $sql);
                // "ss" = 2 parameter bertipe string
                $stmt->bind_param("ss", $namaFile, $userMessage);
                $stmt->execute();
                $stmt->close();

                $uploadInfo = $namaFile;
            } else {
                $uploadError = "Gagal mengupload file.";
            }
        }

    } elseif ($_FILES['chatFile']['error'] !== UPLOAD_ERR_NO_FILE) {
        $uploadError = "Terjadi error saat upload file.";
    }

    // Proses balasan bot dari pesan teks yang dikirim bersama file
    if (!empty($userMessage)) {
        $msgLower = strtolower($userMessage);
        if (strpos($msgLower, "status") !== false) {
            $botReply = "Saat ini sebagian besar siswa berada pada tahap <em>pelatihan aktif</em> dengan progres positif.";
        } elseif (strpos($msgLower, "laporan") !== false) {
            $botReply = "Laporan perkembangan dapat dilihat pada menu <em>Laporan Perkembangan</em> di dashboard sekolah.";
        } elseif (strpos($msgLower, "rekomendasi") !== false) {
            $botReply = "Berdasarkan data agregat, pelatihan vokasional dengan minat tertinggi adalah keterampilan praktis dan kewirausahaan.";
        } elseif (!empty($uploadInfo)) {
            $botReply = "File berhasil diterima! Aku akan sampaikan ke tim terkait untuk ditindaklanjuti.";
        } else {
            $botReply = "Maaf, aku belum memahami pertanyaan itu.";
        }
    } elseif (!empty($uploadInfo)) {
        $botReply = "File berhasil diterima! Aku akan sampaikan ke tim terkait untuk ditindaklanjuti.";
    }

// HANDLE GET: Pesan teks biasa tanpa file
} elseif ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["message"]) && $_GET["message"] !== "") {

    $userMessage = htmlspecialchars(trim($_GET["message"]));
    $msgLower    = strtolower($userMessage);

    // strpos() mengecek apakah keyword tertentu ada dalam pesan
    if (strpos($msgLower, "status") !== false) {
        $botReply = "Saat ini sebagian besar siswa berada pada tahap <em>pelatihan aktif</em> dengan progres positif.";
    } elseif (strpos($msgLower, "laporan") !== false) {
        $botReply = "Laporan perkembangan dapat dilihat pada menu <em>Laporan Perkembangan</em> di dashboard sekolah.";
    } elseif (strpos($msgLower, "rekomendasi") !== false) {
        $botReply = "Berdasarkan data agregat, pelatihan vokasional dengan minat tertinggi adalah keterampilan praktis dan kewirausahaan.";
    } else {
        $botReply = "Maaf, aku belum memahami pertanyaan itu.";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>InkluBot | Asisten Sekolah</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="chat-container">

  <!-- Tombol kembali ke dashboard, menggunakan <a> karena ini navigasi biasa (GET) -->
  <a href="../dashboard/dashboardSekolah.php" class="floating-back">
    <i class="ri-arrow-left-line"></i>
  </a>

  <div class="chat-wrapper">

    <div class="chat-header">
      <div class="chat-title">
        <i class="ri-robot-line"></i>
        <div>
          <h3>InkluBot</h3>
          <span>Asisten Sekolah (Dummy AI)</span>
        </div>
      </div>
    </div>

    <div class="chat-body" id="chatBody">

      <!-- Pesan selamat datang dari bot yang selalu tampil saat halaman dibuka -->
      <div class="chat bot"><p>Halo! Aku <strong>InkluBot</strong> 👋</p></div>
      <div class="chat bot"><p>Aku bisa menjawab pertanyaan soal <strong>status siswa</strong>, <strong>laporan</strong>, atau <strong>rekomendasi pelatihan</strong>.</p></div>
      <div class="chat bot"><p>Kamu juga bisa mengirim <strong>file gambar atau PDF</strong> beserta pesanmu 📎</p></div>
      <div class="chat bot"><p>Silakan ketik pertanyaanmu di bawah ya ✨</p></div>

      <?php if ($userMessage !== "" || !empty($uploadInfo) || !empty($uploadError)): ?>

        <!-- Tampilkan pesan dan file yang dikirim user -->
        <div class="chat user">
          <?php if ($userMessage !== ""): ?>
            <p><?= $userMessage ?></p>
          <?php endif; ?>

          <?php if (!empty($uploadInfo)): ?>
            <!-- Tampilkan preview jika file yang diupload adalah gambar -->
            <?php $ext = strtolower(pathinfo($uploadInfo, PATHINFO_EXTENSION)); ?>
            <?php if (in_array($ext, ['jpg', 'jpeg', 'png'])): ?>
              <img src="uploads/<?= htmlspecialchars($uploadInfo) ?>"
                   style="max-width:200px;max-height:150px;border-radius:8px;margin-top:6px;display:block;">
            <?php else: ?>
              <!-- Tampilkan ikon PDF jika bukan gambar -->
              <p style="margin-top:6px;">
                <i class="ri-file-pdf-line"></i>
                <a href="uploads/<?= htmlspecialchars($uploadInfo) ?>" target="_blank" style="color:inherit;">
                  <?= htmlspecialchars($uploadInfo) ?>
                </a>
              </p>
            <?php endif; ?>
          <?php endif; ?>

          <?php if (!empty($uploadError)): ?>
            <p style="color:#fca5a5;font-size:12px;"><?= htmlspecialchars($uploadError) ?></p>
          <?php endif; ?>
        </div>

        <?php if ($botReply): ?>
          <div class="chat bot"><p><?= $botReply ?></p></div>
        <?php endif; ?>

      <?php endif; ?>

    </div>

    <!-- Form chatbot menggunakan POST + enctype multipart/form-data agar bisa kirim file
         sesuai materi modul 5.4.9 — enctype ini wajib ada saat form mengirim file -->
    <div class="chat-input">
      <form method="POST" action="" id="chatForm" enctype="multipart/form-data" onsubmit="scrollToBottom()">

        <!-- Tombol lampir file — tersembunyi, dipicu oleh ikon klip di bawah -->
        <input type="file" name="chatFile" id="chatFileInput"
               accept="image/*,.pdf" style="display:none;"
               onchange="showFileName(this)">

        <div style="display:flex;flex-direction:column;flex:1;gap:4px;">
          <!-- Nama file yang dipilih (muncul di atas input teks jika ada file) -->
          <div id="fileNameDisplay" style="display:none;font-size:11px;color:#6c63ff;padding:0 4px;">
            <i class="ri-attachment-line"></i> <span id="fileNameText"></span>
            <button type="button" onclick="clearFile()"
                    style="background:none;border:none;color:#ef4444;cursor:pointer;font-size:11px;margin-left:4px;">✕</button>
          </div>
          <input type="text" name="message" id="userInput"
                 placeholder="Tulis pertanyaan atau kirim file..."
                 autocomplete="off">
        </div>

        <!-- Tombol lampirkan file (ikon klip) -->
        <button type="button" onclick="document.getElementById('chatFileInput').click()"
                title="Lampirkan gambar atau PDF"
                style="background:none;border:none;font-size:20px;color:#6c63ff;cursor:pointer;padding:0 4px;">
          <i class="ri-attachment-2"></i>
        </button>

        <!-- Tombol kirim pesan -->
        <button type="submit">
          <i class="ri-send-plane-2-line"></i>
        </button>
      </form>
    </div>

  </div>
</div>

<script>
  // Scroll otomatis ke bawah agar pesan terbaru selalu terlihat
  function scrollToBottom() {
    const chatBody = document.getElementById("chatBody");
    chatBody.scrollTop = chatBody.scrollHeight;
  }
  window.onload = scrollToBottom;

  // Tampilkan nama file yang dipilih di atas input teks
  function showFileName(input) {
    if (input.files && input.files[0]) {
      document.getElementById('fileNameText').textContent = input.files[0].name;
      document.getElementById('fileNameDisplay').style.display = 'block';
    }
  }

  // Hapus file yang sudah dipilih dari input
  function clearFile() {
    document.getElementById('chatFileInput').value = '';
    document.getElementById('fileNameDisplay').style.display = 'none';
    document.getElementById('fileNameText').textContent = '';
  }
</script>
</body>
</html>