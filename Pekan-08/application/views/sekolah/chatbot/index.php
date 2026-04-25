<?php
// VIEW: sekolah/chatbot/index.php
// Halaman antarmuka chatbot InkluBot.
//
// Variabel yang diterima dari Chatbot Controller (via array $data):
//   $botReply     : Respons teks dari bot (string HTML, bisa kosong)
//   $userMessage  : Pesan yang dikirim user, sudah di-htmlspecialchars (bisa kosong)
//   $uploadInfo   : Nama file yang berhasil di-upload (bisa kosong jika tidak ada upload)
//   $uploadError  : Pesan error upload jika gagal (bisa kosong)
//   $current_user : Array data user yang login (dari session)
//
// CATATAN PERBAIKAN CI3:
// - Semua kode PHP native di atas view sudah dihapus.
// - Path uploads diperbaiki: base_url('uploads/chatbot/') bukan 'uploads/'.
// - Chatbot berjalan sinkron (satu request = satu respons), bukan AJAX real-time.
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>InkluBot | Asisten Sekolah</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
  <!-- CSS chatbot: base_url() menghasilkan path absolut dari root project CI3 -->
  <link rel="stylesheet" href="<?= base_url('assets/css/chatbot.css') ?>">
</head>
<body>

<div class="chat-container">
  <!-- Tombol kembali ke dashboard (floating di pojok) -->
  <a href="<?= base_url('dashboard') ?>" class="floating-back">
    <i class="ri-arrow-left-line"></i>
  </a>

  <div class="chat-wrapper">
    <!-- Header chatbot -->
    <div class="chat-header">
      <div class="chat-title">
        <i class="ri-robot-line"></i>
        <div>
          <h3>InkluBot</h3>
          <span>Asisten Sekolah (Dummy AI)</span>
        </div>
      </div>
    </div>

    <!-- AREA PERCAKAPAN CHAT                                   -->
    <!-- Bubble chat bot default + bubble user/bot dari request -->
    <div class="chat-body" id="chatBody">
      <!-- Pesan selamat datang dari bot (selalu ditampilkan) -->
      <div class="chat bot"><p>Halo! Aku <strong>InkluBot</strong> 👋</p></div>
      <div class="chat bot"><p>Aku bisa menjawab pertanyaan soal <strong>status siswa</strong>, <strong>laporan</strong>, atau <strong>rekomendasi pelatihan</strong>.</p></div>
      <div class="chat bot"><p>Kamu juga bisa mengirim <strong>file gambar atau PDF</strong> beserta pesanmu 📎</p></div>
      <div class="chat bot"><p>Silakan ketik pertanyaanmu di bawah ya!</p></div>

      <?php if ($userMessage !== "" || !empty($uploadInfo) || !empty($uploadError)): ?>
        <!-- Bubble percakapan user: ditampilkan jika ada pesan atau upload dari request ini -->
        <div class="chat user">
          <!-- Tampilkan pesan teks user jika ada -->
          <?php if ($userMessage !== ""): ?><p><?= $userMessage ?></p><?php endif; ?>

          <?php if (!empty($uploadInfo)): ?>
            <?php $ext = strtolower(pathinfo($uploadInfo, PATHINFO_EXTENSION)); ?>
            <?php if (in_array($ext, ['jpg', 'jpeg', 'png'])): ?>
              <!-- File gambar: tampilkan preview thumbnail -->
              <!-- base_url('uploads/chatbot/') = path ke folder penyimpanan file chatbot -->
              <img src="<?= base_url('uploads/chatbot/' . htmlspecialchars($uploadInfo)) ?>"
                   style="max-width:200px;max-height:150px;border-radius:8px;margin-top:6px;display:block;">
            <?php else: ?>
              <!-- File PDF: tampilkan nama file sebagai link yang bisa diklik -->
              <p style="margin-top:6px;">
                <i class="ri-file-pdf-line"></i>
                <a href="<?= base_url('uploads/chatbot/' . htmlspecialchars($uploadInfo)) ?>" target="_blank" style="color:inherit;">
                  <?= htmlspecialchars($uploadInfo) ?>
                </a>
              </p>
            <?php endif; ?>
          <?php endif; ?>

          <!-- Pesan error upload (tampil jika upload gagal) -->
          <?php if (!empty($uploadError)): ?>
            <p style="color:#fca5a5;font-size:12px;"><?= htmlspecialchars($uploadError) ?></p>
          <?php endif; ?>
        </div>

        <?php if ($botReply): ?>
          <!-- Bubble balasan bot: mengandung HTML (tag <em>, <strong>) sehingga tidak di-escape -->
          <div class="chat bot"><p><?= $botReply ?></p></div>
        <?php endif; ?>
      <?php endif; ?>
    </div>

    <!-- INPUT AREA CHATBOT                                     -->
    <!-- Form POST ke /chatbot dengan enctype multipart untuk   -->
    <!-- mendukung upload file bersamaan dengan pesan teks      -->
    <div class="chat-input">
      <!-- enctype="multipart/form-data" wajib ada agar upload file bisa diproses -->
      <form method="POST" action="<?= base_url('chatbot') ?>" id="chatForm" enctype="multipart/form-data" onsubmit="scrollToBottom()">
        <!-- Input file tersembunyi: diklik via tombol attachment di bawah -->
        <!-- name="chatFile" harus cocok dengan $_FILES['chatFile'] di Chatbot Controller -->
        <input type="file" name="chatFile" id="chatFileInput"
               accept="image/*,.pdf" style="display:none;" onchange="showFileName(this)">
        <div style="display:flex;flex-direction:column;flex:1;gap:4px;">
          <!-- Tampilkan nama file yang sudah dipilih (awalnya tersembunyi) -->
          <div id="fileNameDisplay" style="display:none;font-size:11px;color:#6c63ff;padding:0 4px;">
            <i class="ri-attachment-line"></i> <span id="fileNameText"></span>
            <!-- Tombol hapus pilihan file (clearFile()) -->
            <button type="button" onclick="clearFile()"
                    style="background:none;border:none;color:#ef4444;cursor:pointer;font-size:11px;margin-left:4px;">✕</button>
          </div>
          <!-- Input teks pesan: name="message" harus cocok dengan $this->input->post('message') di controller -->
          <input type="text" name="message" id="userInput"
                 placeholder="Tulis pertanyaan atau kirim file..." autocomplete="off">
        </div>
        <!-- Tombol lampirkan: memicu klik pada input file tersembunyi -->
        <button type="button" onclick="document.getElementById('chatFileInput').click()"
                title="Lampirkan gambar atau PDF"
                style="background:none;border:none;font-size:20px;color:#6c63ff;cursor:pointer;padding:0 4px;">
          <i class="ri-attachment-2"></i>
        </button>
        <!-- Tombol kirim pesan -->
        <button type="submit"><i class="ri-send-plane-2-line"></i></button>
      </form>
    </div>
  </div>
</div>

<script>
  // Scroll area chat ke posisi paling bawah (pesan terbaru)
  function scrollToBottom() {
    const chatBody = document.getElementById("chatBody");
    chatBody.scrollTop = chatBody.scrollHeight;
  }
  // Otomatis scroll ke bawah saat halaman pertama kali dimuat
  window.onload = scrollToBottom;

  // showFileName(): tampilkan nama file yang dipilih di atas input teks
  function showFileName(input) {
    if (input.files && input.files[0]) {
      document.getElementById('fileNameText').textContent = input.files[0].name;
      document.getElementById('fileNameDisplay').style.display = 'block';
    }
  }

  // clearFile(): reset pilihan file dan sembunyikan label nama file
  function clearFile() {
    document.getElementById('chatFileInput').value = '';
    document.getElementById('fileNameDisplay').style.display = 'none';
    document.getElementById('fileNameText').textContent = '';
  }
</script>
</body>
</html>
