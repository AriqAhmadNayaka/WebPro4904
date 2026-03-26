<?php
// Inisialisasi variabel balasan bot dan pesan user
$botReply    = "";
$userMessage = "";

// Blok ini hanya berjalan jika ada pesan yang dikirim via GET (form chatbot pakai method GET)
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["message"]) && $_GET["message"] !== "") {

    // Bersihkan input dari karakter berbahaya, trim spasi berlebih
    $userMessage = htmlspecialchars(trim($_GET["message"]));

    // Ubah ke huruf kecil agar pencocokan keyword tidak case-sensitive
    $msgLower = strtolower($userMessage);

    // strpos() mengecek apakah keyword tertentu ada dalam pesan
    // Nilai kembalian false berarti tidak ditemukan, !== false berarti ditemukan
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
      <div class="chat bot"><p>Aku adalah asisten sekolah berbasis AI (dummy) yang siap membantu kamu.</p></div>
      <div class="chat bot"><p>Aku bisa memberikan informasi terkait siswa, pelatihan, dan laporan sekolah.</p></div>
      <div class="chat bot"><p>Kamu bisa bertanya soal <strong>status siswa</strong>, <strong>laporan perkembangan</strong>, atau <strong>rekomendasi pelatihan</strong>.</p></div>
      <div class="chat bot"><p>Contoh pertanyaan yang bisa kamu ajukan:</p></div>
      <div class="chat bot">
        <p>• Bagaimana status perkembangan siswa?<br>
           • Di mana melihat laporan pelatihan?<br>
           • Apa rekomendasi vokasional saat ini?
        </p>
      </div>
      <div class="chat bot"><p>Silakan ketik pertanyaanmu di bawah ya ✨</p></div>

      <?php if ($userMessage !== ""): ?>
        <!-- Tampilkan pesan user dan balasan bot jika ada pesan yang dikirim via GET -->
        <div class="chat user"><p><?= $userMessage ?></p></div>
        <div class="chat bot"><p><?= $botReply ?></p></div>
      <?php endif; ?>

    </div>

    <div class="chat-input">
      <!-- Form menggunakan method GET agar pesan terkirim via URL — cocok untuk
           data yang tidak sensitif dan memungkinkan halaman di-refresh tanpa konfirmasi -->
      <form method="GET" action="" id="chatForm" onsubmit="scrollToBottom()">
        <input type="text" name="message" id="userInput"
               placeholder="Tulis pertanyaan di sini..."
               value="" autocomplete="off">
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
  // Jalankan scroll saat halaman pertama kali dimuat
  window.onload = scrollToBottom;
</script>
</body>
</html>