<?php
// Menghubungkan file ini dengan konfigurasi database.
include 'koneksi.php';

// Menyimpan semua data budaya yang diambil dari database ke dalam array.
$data_webandoo = [];

// LOGIKA PROSES
if (isset($_POST['proses_data'])) {
    // Mengambil ID item untuk menentukan proses tambah baru atau edit data.
    $id_input   = $_POST['id_item'] ?? '';
    // Membersihkan input nama agar lebih aman saat ditampilkan kembali.
    $nama       = htmlspecialchars(trim($_POST['nama_item'] ?? ''));
    // Membersihkan input deskripsi dari spasi berlebih dan karakter khusus.
    $deskripsi  = htmlspecialchars(trim($_POST['deskripsi'] ?? ''));
    // Menentukan folder penyimpanan file upload.
    $folder = "uploads/";
    // Membuat folder upload jika belum tersedia.
    if (!is_dir($folder)) mkdir($folder, 0777, true);

    // Menentukan gambar awal, memakai gambar lama atau URL default jika belum ada upload baru.
    $gambar_final = !empty($_POST['gambar_lama']) ? $_POST['gambar_lama'] : 'https://images.unsplash.com/photo-1578321272176-b7bbc067985c?q=80&w=1200';

    // Mengecek apakah user benar-benar mengunggah file gambar baru.
    if (!empty($_FILES['gambar']['name']) && is_uploaded_file($_FILES['gambar']['tmp_name'])) {
        // Membuat nama file yang unik dan aman untuk disimpan di server.
        $nama_file = time() . "_" . preg_replace('/[^A-Za-z0-9.\-_]/', '_', basename($_FILES['gambar']['name']));
        // Menentukan lokasi tujuan file upload.
        $target = $folder . $nama_file;
        // Memindahkan file dari temporary upload ke folder tujuan.
        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target)) {
            // Jika upload berhasil, gunakan gambar baru sebagai gambar utama.
            $gambar_final = $target;
        }
    }

    // Jika ID kosong berarti data baru akan disimpan.
    if ($id_input == "") {
        // Menyiapkan query insert dengan prepared statement agar lebih aman.
        $stmt = mysqli_prepare($conn, "INSERT INTO data_webandoo (nama_item, deskripsi, gambar) VALUES (?, ?, ?)");
        if ($stmt) {
            // Mengikat nilai input ke query insert.
            mysqli_stmt_bind_param($stmt, "sss", $nama, $deskripsi, $gambar_final);
            // Menjalankan proses simpan ke database.
            $simpan = mysqli_stmt_execute($stmt);
            // Menyiapkan pesan hasil proses untuk ditampilkan setelah redirect.
            $msg = $simpan ? "Warisan Berhasil Diarsipkan!" : "Data gagal disimpan: " . mysqli_stmt_error($stmt);
            // Menutup statement agar resource dibersihkan.
            mysqli_stmt_close($stmt);
        } else {
            // Menampilkan error jika query insert gagal dipersiapkan.
            $msg = "Query insert error: " . mysqli_error($conn);
        }
    } else {
        // Jika ID ada, berarti data lama akan diperbarui.
        $stmt = mysqli_prepare($conn, "UPDATE data_webandoo SET nama_item = ?, deskripsi = ?, gambar = ? WHERE id = ?");
        if ($stmt) {
            // Mengikat nilai form ke query update sesuai tipe datanya.
            mysqli_stmt_bind_param($stmt, "sssi", $nama, $deskripsi, $gambar_final, $id_input);
            // Menjalankan proses update data di database.
            $update = mysqli_stmt_execute($stmt);
            // Menyiapkan pesan keberhasilan atau kegagalan update.
            $msg = $update ? "Arsip Diperbarui!" : "Data gagal diperbarui: " . mysqli_stmt_error($stmt);
            // Menutup statement setelah selesai dipakai.
            mysqli_stmt_close($stmt);
        } else {
            // Menampilkan error jika query update gagal dibuat.
            $msg = "Query update error: " . mysqli_error($conn);
        }
    }
    // Mengarahkan ulang ke halaman utama sambil membawa pesan status.
    header("Location: index.php?msg=" . urlencode($msg)); exit();
}

// Mengecek apakah ada permintaan hapus dari parameter URL.
if (isset($_GET['hapus'])) {
    // Menyiapkan query delete berdasarkan ID data.
    $stmt = mysqli_prepare($conn, "DELETE FROM data_webandoo WHERE id = ?");
    if ($stmt) {
        // Mengikat ID yang akan dihapus ke query delete.
        mysqli_stmt_bind_param($stmt, "i", $_GET['hapus']);
        // Menjalankan proses hapus data.
        $hapus = mysqli_stmt_execute($stmt);
        // Menentukan pesan hasil proses hapus.
        $msg = $hapus ? "Terhapus" : "Gagal hapus: " . mysqli_stmt_error($stmt);
        // Menutup statement delete.
        mysqli_stmt_close($stmt);
    } else {
        // Menampilkan error jika query delete gagal dibuat.
        $msg = "Query delete error: " . mysqli_error($conn);
    }
    // Redirect kembali ke halaman utama setelah proses hapus selesai.
    header("Location: index.php?msg=" . urlencode($msg)); exit();
}

// Mengambil seluruh data budaya dari database dengan urutan terbaru di atas.
$ambil = mysqli_query($conn, "SELECT * FROM data_webandoo ORDER BY id DESC");
if ($ambil) {
    // Memasukkan setiap baris hasil query ke array yang akan dipakai di tampilan.
    while ($row = mysqli_fetch_assoc($ambil)) {
        $data_webandoo[] = [
            'id' => $row['id'],
            'nama' => $row['nama_item'],
            'desc' => $row['deskripsi'],
            'img' => $row['gambar']
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>We Bandoo | Royal Heritage Gallery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&family=Plus+Jakarta+Sans:wght@300;400;600&display=swap" rel="stylesheet">
    
    <style>
        :root { 
            --royal-gold: #C5A059;
            --deep-indigo: #0A0F1E;
            --glass-white: rgba(255, 255, 255, 0.05);
        }
        
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: var(--deep-indigo);
            background-image: 
                linear-gradient(rgba(10, 15, 30, 0.9), rgba(10, 15, 30, 0.9)),
                url('https://www.toptal.com/designers/subtlepatterns/patterns/double-lined.png');
            color: #fff;
            min-height: 100vh;
            /* Penting untuk partikel */
            overflow-x: hidden; 
        }

        .heritage-title {
            font-family: 'Cinzel', serif;
            font-weight: 700;
            letter-spacing: 5px;
            color: var(--royal-gold);
            text-shadow: 0 0 20px rgba(197, 160, 89, 0.3);
        }

        .royal-panel {
            background: var(--glass-white);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(197, 160, 89, 0.2);
            border-radius: 0 40px 0 40px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.5);
            transition: 0.4s;
        }

        .form-heritage {
            background: rgba(0,0,0,0.3);
            border: 1px solid rgba(197, 160, 89, 0.1);
            border-radius: 12px;
            color: #fff;
            padding: 12px;
        }
        .form-heritage:focus {
            border-color: var(--royal-gold);
            box-shadow: 0 0 10px rgba(197, 160, 89, 0.2);
            outline: none;
            background: rgba(0,0,0,0.5);
        }

        /* Container Kartu agar partikel tidak keluar konteks saat inisialisasi */
        .card-anim-container {
            position: relative;
            transition: 0.3s opacity ease;
        }

        .culture-card {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,0.05);
            transition: 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            background: #0A0F1E; /* Warna solid agar canvas bagus */
        }
        .culture-card:hover {
            transform: translateY(-10px);
            border-color: var(--royal-gold);
        }

        .img-zoom-wrap {
            height: 300px;
            overflow: hidden;
        }
        .img-zoom-wrap img {
            width: 100%; height: 100%; object-fit: cover;
            transition: 1.5s;
        }
        .culture-card:hover .img-zoom-wrap img {
            transform: scale(1.15);
        }

        .card-info {
            position: absolute;
            bottom: 0; left: 0; right: 0;
            padding: 30px;
            background: linear-gradient(to top, rgba(10, 15, 30, 1) 10%, transparent);
        }

        .btn-royal {
            background: linear-gradient(45deg, #C5A059, #8E6E36);
            color: #000;
            font-weight: 700;
            border: none;
            border-radius: 50px;
            padding: 15px;
            letter-spacing: 2px;
            transition: 0.3s;
        }
        .btn-royal:hover {
            box-shadow: 0 0 30px rgba(197, 160, 89, 0.5);
            color: #fff;
            transform: translateY(-2px);
        }

        .upload-heritage {
            border: 2px dashed var(--royal-gold);
            background: rgba(197, 160, 89, 0.05);
            height: 150px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            margin-bottom: 20px;
        }

        /* CSS UNTUK PARTIKEL BERKEPING-KEPING */
        .disintegration-particle {
            position: absolute;
            pointer-events: none;
            z-index: 1000;
            /* Transform-style presreved untuk 3D effect saat terbang */
            transform-style: preserve-3d; 
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="heritage-title display-4">WE BANDOO</h1>
        <div class="mx-auto" style="width: 100px; height: 2px; background: var(--royal-gold); margin: 15px auto;"></div>
        <p class="small tracking-widest opacity-60">NUSANTARA DIGITAL ARCHIVE • VOL. 1</p>
    </div>

    <div class="row g-5">
        <div class="col-lg-4">
            <div class="royal-panel p-4 sticky-top" style="top: 2rem;">
                <h5 class="fw-bold mb-4" id="form-title" style="font-family: 'Cinzel', serif;">Simpan Warisan</h5>
                <form action="" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id_item" id="f-id">
                    <input type="hidden" name="gambar_lama" id="f-old">

                    <div class="upload-heritage" onclick="document.getElementById('file-input').click()">
                        <div id="placeholder-content" class="text-center">
                            <i class="bi bi-plus-circle-dotted fs-1" style="color: var(--royal-gold);"></i>
                            <p class="small mt-2 opacity-50">Upload Visual Budaya</p>
                        </div>
                        <img id="preview-img" style="width:100%; height:100%; object-fit:cover; border-radius:18px; display:none;">
                    </div>
                    <input type="file" name="gambar" id="file-input" class="d-none" onchange="preview(this)">

                    <div class="mb-3">
                        <label class="small mb-1 opacity-50">NAMA BUDAYA</label>
                        <input type="text" name="nama_item" id="f-nama" class="form-heritage w-100" placeholder="Contoh: Wayang Kulit" required>
                    </div>
                    <div class="mb-4">
                        <label class="small mb-1 opacity-50">NARASI SINGKAT</label>
                        <textarea name="deskripsi" id="f-desk" class="form-heritage w-100" rows="3" placeholder="Ceritakan filosofinya..."></textarea>
                    </div>

                    <button type="submit" name="proses_data" id="f-btn" class="btn-royal w-100">ARSIPKAN DATA</button>
                </form>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="row g-4" id="gallery-container">
                <?php foreach ($data_webandoo as $item): ?>
                <div class="col-md-6 mb-4 card-anim-container" id="card-asli-<?= $item['id'] ?>">
                    <div class="culture-card h-100 shadow-lg">
                        <div class="img-zoom-wrap">
                            <img src="<?= $item['img'] ?>" onerror="this.src='https://via.placeholder.com/600x800'">
                        </div>
                        <div class="card-info">
                            <h4 class="fw-bold mb-1" style="font-family: 'Cinzel', serif;"><?= $item['nama'] ?></h4>
                            <p class="small opacity-50 mb-3" style="line-height: 1.6;"><?= substr($item['desc'], 0, 100) ?>...</p>
                            
                            <div class="d-flex gap-2">
                                <button onclick='editMode(<?= (int) $item["id"] ?>, <?= json_encode($item["nama"]) ?>, <?= json_encode($item["desc"]) ?>, <?= json_encode($item["img"]) ?>)' 
                                        class="btn btn-sm btn-outline-warning border-0 rounded-pill px-3">
                                    <i class="bi bi-pencil-square me-1"></i> Edit
                                </button>
                                <button onclick="hapusPecahBerkeping('<?= $item['id'] ?>')" class="btn btn-sm btn-outline-danger border-0 rounded-pill px-3">
                                    <i class="bi bi-trash3 me-1"></i> Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                
                <?php if (empty($data_webandoo)): ?>
                    <div class="col-12 text-center py-5 opacity-30" id="empty-state">
                        <i class="bi bi-journal-richtext display-1"></i>
                        <p class="mt-3">Galeri Warisan Masih Kosong</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Menampilkan preview gambar yang dipilih sebelum form dikirim.
    function preview(input) {
        // Pastikan ada file yang dipilih oleh user.
        if (input.files && input.files[0]) {
            // FileReader dipakai untuk membaca file gambar di browser.
            var reader = new FileReader();
            reader.onload = function(e) {
                // Menampilkan hasil gambar ke elemen preview.
                document.getElementById('preview-img').src = e.target.result;
                document.getElementById('preview-img').style.display = 'block';
                document.getElementById('placeholder-content').style.display = 'none';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Mengisi form dengan data lama agar user bisa mengedit data yang dipilih.
    function editMode(id, nama, desc, img) {
        // Mengisi input tersembunyi dan field form dengan data item.
        document.getElementById('f-id').value = id;
        document.getElementById('f-nama').value = nama;
        document.getElementById('f-desk').value = desc;
        document.getElementById('f-old').value = img;
        
        // Menampilkan gambar lama sebagai preview saat mode edit.
        document.getElementById('preview-img').src = img;
        document.getElementById('preview-img').style.display = 'block';
        document.getElementById('placeholder-content').style.display = 'none';
        
        // Mengubah judul dan teks tombol agar user tahu sedang mengedit data.
        document.getElementById('form-title').innerText = "Edit Warisan";
        document.getElementById('f-btn').innerText = "UPDATE WARISAN";
        // Menggeser halaman ke atas agar form langsung terlihat.
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // --- LOGIKA UTAMA EFEK PECAH BERKEPING-KEPING (DISINTEGRASI) ---
    function hapusPecahBerkeping(id) {
        // Menampilkan dialog konfirmasi sebelum data dihapus.
        Swal.fire({
            title: 'Hapus Permanen?',
            text: "Data akan hancur berkeping-keping dari arsip!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#C5A059',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Hancurkan!',
            cancelButtonText: 'Batal',
            background: '#0A0F1E',
            color: '#fff'
        }).then((result) => {
            if (result.isConfirmed) {
                // Mengambil elemen kartu yang dipilih untuk dibuat efek animasi.
                const cardWrapper = document.getElementById('card-asli-' + id);
                const cardMurni = cardWrapper.querySelector('.culture-card');

                // Gunakan html2canvas untuk mengambil gambar kartu
                html2canvas(cardMurni, {
                    backgroundColor: null, // Transparan
                    useCORS: true, // Izinkan gambar dari domain berbeda (jika ada)
                    scale: 1 // Skala normal
                }).then(canvas => {
                    // 1. Sembunyikan kartu asli
                    cardWrapper.style.opacity = '0';

                    // 2. Dapatkan data gambar dan posisi
                    // Canvas dipakai untuk mengambil warna tiap bagian kartu.
                    const ctx = canvas.getContext('2d');
                    const { width, height } = canvas;
                    // Posisi elemen asli dibutuhkan agar partikel muncul di tempat yang sama.
                    const containerPos = cardWrapper.getBoundingClientRect();

                    // 3. Buat container untuk partikel
                    const particleContainer = document.createElement('div');
                    particleContainer.style.position = 'fixed';
                    particleContainer.style.left = containerPos.left + 'px';
                    particleContainer.style.top = containerPos.top + 'px';
                    particleContainer.style.width = width + 'px';
                    particleContainer.style.height = height + 'px';
                    particleContainer.style.pointerEvents = 'none';
                    particleContainer.style.zIndex = '10000';
                    document.body.appendChild(particleContainer);

                    // 4. Pecah gambar menjadi partikel (kotak kecil)
                    const particleSize = 6; // Ukuran kepingan (makin kecil makin berat tapi detail)
                    const cols = Math.ceil(width / particleSize);
                    const rows = Math.ceil(height / particleSize);

                    for (let r = 0; r < rows; r++) {
                        for (let c = 0; c < cols; c++) {
                            // Ambil data warna dari pixel canvas
                            const x = c * particleSize;
                            const y = r * particleSize;
                            const imgData = ctx.getImageData(x, y, 1, 1).data;
                            
                            // Jangan buat partikel jika transparan
                            if (imgData[3] === 0) continue; 

                            // Buat elemen div partikel
                            const particle = document.createElement('div');
                            particle.className = 'disintegration-particle';
                            particle.style.width = particleSize + 'px';
                            particle.style.height = particleSize + 'px';
                            particle.style.backgroundColor = `rgb(${imgData[0]}, ${imgData[1]}, ${imgData[2]})`;
                            particle.style.left = x + 'px';
                            particle.style.top = y + 'px';

                            // Set nilai acak untuk animasi terbang
                            const destinationX = (Math.random() - 0.5) * 400; // Terbang horizontal acak
                            const destinationY = (Math.random() - 1.5) * 400; // Terbang ke atas acak
                            const rotation = (Math.random() - 0.5) * 720; // Putaran acak
                            const delay = Math.random() * 0.4; // Delay acak biar ga barengan hancurnya

                            // Terapkan animasi CSS via JS
                            particle.style.transition = `all 1.5s cubic-bezier(0.25, 1, 0.5, 1) ${delay}s, opacity 1.2s ease ${delay + 0.3}s`;
                            
                            // Menambahkan partikel ke container sebelum animasi dijalankan.
                            particleContainer.appendChild(particle);

                            // Mulai animasi setelah partikel dirender
                            requestAnimationFrame(() => {
                                particle.style.transform = `translate(${destinationX}px, ${destinationY}px) rotate(${rotation}deg) scale(0)`;
                                particle.style.opacity = '0';
                            });
                        }
                    }

                    // 5. Eksekusi PHP Hapus setelah animasi selesai (sekitar 2.5 detik)
                    setTimeout(() => {
                        window.location.href = '?hapus=' + id;
                    }, 2500);
                });
            }
        });
    }

    // Membaca parameter pesan dari URL untuk menampilkan notifikasi toast.
    const urlParams = new URLSearchParams(window.location.search);
    if(urlParams.get('msg')) {
        // Menampilkan pesan hasil simpan, update, atau hapus data.
        Swal.fire({
            toast: true, position: 'top-end', icon: 'info',
            title: urlParams.get('msg'), showConfirmButton: false, timer: 3000,
            background: '#0A0F1E', color: '#C5A059'
        });
    }
</script>
</body>
</html>
