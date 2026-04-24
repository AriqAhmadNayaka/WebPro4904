<?php
session_start();
require 'database.php';
$db = new Database();

if (!isset($_SESSION['login'])) {
    header('Location: login.php');
    exit;
}

// Proses simpan lokasi baru
if (isset($_POST['save_location'])) {
    $nama = mysqli_real_escape_string($db->conn, $_POST['nama']);
    $tipe = $_POST['tipe'];
    $lat = $_POST['lat'];
    $lng = $_POST['lng'];
    $ket = mysqli_real_escape_string($db->conn, $_POST['keterangan']);

    $query = "INSERT INTO locations (nama, tipe, latitude, longitude, keterangan) VALUES ('$nama', '$tipe', '$lat', '$lng', '$ket')";
    mysqli_query($db->conn, $query);
    header("Location: maps.php");
}

// Ambil semua lokasi untuk marker dan tabel
$all_locations = mysqli_query($db->conn, "SELECT * FROM locations ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Peta EcoTaste | Restoran & Limbah</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <style>
        :root { --hijau-primer: #4CAF50; --hijau-gelap: #1B5E20; --hijau-latar: #F4F9F4; --putih: #FFFFFF; }
        
        body { font-family: 'Poppins', sans-serif; margin: 0; background: var(--hijau-latar); color: #333; }
        
        .navbar { 
            display: flex; justify-content: space-between; align-items: center; 
            padding: 15px 5%; background: var(--putih); box-shadow: 0 2px 10px rgba(0,0,0,0.1); 
            position: sticky; top: 0; z-index: 1000;
        }
        .logo { font-size: 24px; font-weight: 800; color: var(--hijau-primer); text-decoration: none; display: flex; align-items: center; gap: 10px; }
        .btn-home { color: var(--hijau-primer); text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px; }

        #map { height: 50vh; width: 100%; border-bottom: 5px solid var(--hijau-primer); }
        
        .container { padding: 20px 5%; }
        
        .form-panel { 
            background: var(--putih); padding: 20px 30px; border-radius: 20px; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.05); border-top: 5px solid var(--hijau-primer);
            margin-bottom: 30px;
        }
        
        .form-row { display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end; }
        .form-group { flex: 1; min-width: 180px; }
        .form-group.large { flex: 2; min-width: 250px; }

        label { display: block; margin-bottom: 8px; font-weight: 600; font-size: 13px; }
        input, select { 
            width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; 
            font-family: inherit; font-size: 14px;
        }

        .btn-save { 
            background: var(--hijau-primer); color: white; border: none; 
            padding: 12px 25px; border-radius: 8px; cursor: pointer; 
            font-weight: 700; transition: 0.3s; height: 42px;
        }
        .btn-save:hover { background: var(--hijau-gelap); }

        /* Style Tabel Lokasi */
        .table-panel {
            background: var(--putih); padding: 25px; border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        }
        .table-panel h4 { margin-top: 0; color: var(--hijau-gelap); border-bottom: 2px solid var(--hijau-latar); padding-bottom: 10px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 14px; }
        th { text-align: left; background: var(--hijau-latar); padding: 12px; color: var(--hijau-gelap); }
        td { padding: 12px; border-bottom: 1px solid #eee; }
        
        .badge { padding: 4px 10px; border-radius: 20px; color: white; font-size: 11px; font-weight: bold; text-transform: uppercase; }
        .badge-res { background: #4CAF50; }
        .badge-lim { background: #FF9800; }

        /* Label Nama Tempat di Peta */
        .map-label {
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid var(--hijau-primer);
            border-radius: 5px;
            padding: 2px 8px;
            font-size: 12px;
            font-weight: 600;
            color: #333;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .leaflet-tooltip-top:before { display: none; }
    </style>
</head>
<body>

<nav class="navbar">
    <a href="homepage.php" class="logo"><i class="fas fa-leaf"></i> EcoTaste</a>
    <a href="homepage.php" class="btn-home"><i class="fas fa-home"></i> Kembali ke Beranda</a>
</nav>

<div id="map"></div>

<div class="container">
    <div class="form-panel">
        <h3><i class="fas fa-plus-circle"></i> Tambah Lokasi Baru</h3>
        <form method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label>Nama Tempat:</label>
                    <input type="text" name="nama" placeholder="Nama tempat..." required>
                </div>
                <div class="form-group">
                    <label>Tipe:</label>
                    <select name="tipe">
                        <option value="restoran">Restoran</option>
                        <option value="limbah">Limbah</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Latitude:</label>
                    <input type="text" name="lat" id="lat" readonly required>
                </div>
                <div class="form-group">
                    <label>Longitude:</label>
                    <input type="text" name="lng" id="lng" readonly required>
                </div>
                <div class="form-group large">
                    <label>Keterangan:</label>
                    <input type="text" name="keterangan" placeholder="Catatan singkat...">
                </div>
                <div class="form-group" style="flex: 0;">
                    <button type="submit" name="save_location" class="btn-save"><i class="fas fa-save"></i> SIMPAN</button>
                </div>
            </div>
        </form>
    </div>

    <div class="table-panel">
        <h4><i class="fas fa-list"></i> Lokasi Terdaftar</h4>
        <table>
            <thead>
                <tr>
                    <th>Nama Tempat</th>
                    <th>Tipe</th>
                    <th>Koordinat</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                mysqli_data_seek($all_locations, 0); 
                while($row = mysqli_fetch_assoc($all_locations)): 
                ?>
                <tr>
                    <td><strong><?= htmlspecialchars($row['nama']) ?></strong></td>
                    <td>
                        <span class="badge <?= $row['tipe'] == 'restoran' ? 'badge-res' : 'badge-lim' ?>">
                            <?= $row['tipe'] ?>
                        </span>
                    </td>
                    <td style="color: #888; font-family: monospace; font-size: 12px;">
                        <?= $row['latitude'] ?>, <?= $row['longitude'] ?>
                    </td>
                    <td><?= htmlspecialchars($row['keterangan']) ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    var map = L.map('map').setView([-6.9175, 107.6191], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

    var currentMarker;
    map.on('click', function(e) {
        document.getElementById('lat').value = e.latlng.lat.toFixed(7);
        document.getElementById('lng').value = e.latlng.lng.toFixed(7);
        if (currentMarker) map.removeLayer(currentMarker);
        currentMarker = L.marker(e.latlng).addTo(map).bindPopup("Lokasi Baru").openPopup();
    });

    <?php 
    mysqli_data_seek($all_locations, 0);
    while($row = mysqli_fetch_assoc($all_locations)): 
    ?>
        var color = "<?= $row['tipe'] == 'restoran' ? '#4CAF50' : '#FF9800' ?>";
        
        // Buat Marker
        var marker = L.circleMarker([<?= $row['latitude'] ?>, <?= $row['longitude'] ?>], {
            radius: 9, 
            fillColor: color, 
            color: "#fff", 
            weight: 2, 
            fillOpacity: 0.9
        }).addTo(map);

        // Tambahkan Label Nama Tempat (Tooltip)
        marker.bindTooltip("<?= htmlspecialchars($row['nama']) ?>", {
            permanent: true, 
            direction: 'top', 
            className: 'map-label',
            offset: [0, -10]
        }).openTooltip();

        // Popup saat diklik
        marker.bindPopup("<b><?= htmlspecialchars($row['nama']) ?></b><br><?= ucfirst($row['tipe']) ?>");
    <?php endwhile; ?>
</script>

</body>
</html>