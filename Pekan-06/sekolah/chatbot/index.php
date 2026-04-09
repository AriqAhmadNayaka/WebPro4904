<?php
session_start();

if (!isset($_SESSION['current_user'])) {
    header('Location: ../../auth.php');
    exit;
}

// ChatbotModel child class dari Database untuk logika chatbot dan upload
require_once 'classes/../ChatbotModel.php';

// Buat objek ChatbotModel dengan path folder uploads chatbot
$chatbot = new ChatbotModel(__DIR__ . '/uploads/');

$botReply = "";
$userMessage = "";
$uploadInfo = "";
$uploadError = "";

// HANDLE POST: Upload file + pesan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['chatFile'])) {

    $userMessage = htmlspecialchars(trim($_POST['message'] ?? ''));

    // Upload file via method uploadFile() dari ChatbotModel
    $hasilUpload = $chatbot->uploadFile($_FILES['chatFile']);

    if ($hasilUpload !== null && str_starts_with((string)$hasilUpload, 'ERROR:')) {
        $uploadError = substr($hasilUpload, 6);
    } elseif ($hasilUpload !== null) {
        // Simpan riwayat upload ke database via method simpanUpload() ChatbotModel
        $chatbot->simpanUpload($hasilUpload, $userMessage);
        $uploadInfo = $hasilUpload;
    }

    // Dapatkan balasan bot via method getBotReply() dari ChatbotModel
    $adaUpload = !empty($uploadInfo);
    if (!empty($userMessage) || $adaUpload) {
        $botReply = $chatbot->getBotReply($userMessage, $adaUpload);
    }

// HANDLE GET: Pesan teks biasa
} elseif ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["message"]) && $_GET["message"] !== "") {
    $userMessage = htmlspecialchars(trim($_GET["message"]));
    $botReply    = $chatbot->getBotReply($userMessage);
}

// Destruktor ChatbotModel otomatis menutup koneksi
unset($chatbot);
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
      <div class="chat bot"><p>Halo! Aku <strong>InkluBot</strong> 👋</p></div>
      <div class="chat bot"><p>Aku bisa menjawab pertanyaan soal <strong>status siswa</strong>, <strong>laporan</strong>, atau <strong>rekomendasi pelatihan</strong>.</p></div>
      <div class="chat bot"><p>Kamu juga bisa mengirim <strong>file gambar atau PDF</strong> beserta pesanmu 📎</p></div>
      <div class="chat bot"><p>Silakan ketik pertanyaanmu di bawah ya!</p></div>

      <?php if ($userMessage !== "" || !empty($uploadInfo) || !empty($uploadError)): ?>
        <div class="chat user">
          <?php if ($userMessage !== ""): ?><p><?= $userMessage ?></p><?php endif; ?>

          <?php if (!empty($uploadInfo)): ?>
            <?php $ext = strtolower(pathinfo($uploadInfo, PATHINFO_EXTENSION)); ?>
            <?php if (in_array($ext, ['jpg', 'jpeg', 'png'])): ?>
              <img src="uploads/<?= htmlspecialchars($uploadInfo) ?>"
                   style="max-width:200px;max-height:150px;border-radius:8px;margin-top:6px;display:block;">
            <?php else: ?>
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
          <!-- Balasan bot dari method getBotReply() ChatbotModel -->
          <div class="chat bot"><p><?= $botReply ?></p></div>
        <?php endif; ?>
      <?php endif; ?>
    </div>

    <!-- Form POST + enctype multipart/form-data untuk upload file -->
    <div class="chat-input">
      <form method="POST" action="" id="chatForm" enctype="multipart/form-data" onsubmit="scrollToBottom()">
        <input type="file" name="chatFile" id="chatFileInput"
               accept="image/*,.pdf" style="display:none;" onchange="showFileName(this)">
        <div style="display:flex;flex-direction:column;flex:1;gap:4px;">
          <div id="fileNameDisplay" style="display:none;font-size:11px;color:#6c63ff;padding:0 4px;">
            <i class="ri-attachment-line"></i> <span id="fileNameText"></span>
            <button type="button" onclick="clearFile()"
                    style="background:none;border:none;color:#ef4444;cursor:pointer;font-size:11px;margin-left:4px;">✕</button>
          </div>
          <input type="text" name="message" id="userInput"
                 placeholder="Tulis pertanyaan atau kirim file..." autocomplete="off">
        </div>
        <button type="button" onclick="document.getElementById('chatFileInput').click()"
                title="Lampirkan gambar atau PDF"
                style="background:none;border:none;font-size:20px;color:#6c63ff;cursor:pointer;padding:0 4px;">
          <i class="ri-attachment-2"></i>
        </button>
        <button type="submit"><i class="ri-send-plane-2-line"></i></button>
      </form>
    </div>
  </div>
</div>

<script>
  function scrollToBottom() {
    const chatBody = document.getElementById("chatBody");
    chatBody.scrollTop = chatBody.scrollHeight;
  }
  window.onload = scrollToBottom;

  function showFileName(input) {
    if (input.files && input.files[0]) {
      document.getElementById('fileNameText').textContent = input.files[0].name;
      document.getElementById('fileNameDisplay').style.display = 'block';
    }
  }

  function clearFile() {
    document.getElementById('chatFileInput').value = '';
    document.getElementById('fileNameDisplay').style.display = 'none';
    document.getElementById('fileNameText').textContent = '';
  }
</script>
</body>
</html>