<?php
// Koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "cybervault");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Proses hapus data berdasarkan ID
    $query = "DELETE FROM datauser WHERE id = $id";
    
    if (mysqli_query($conn, $query)) {
        // Pop-up notifikasi dan kembali ke halaman datauser.php
        echo "<script>
                alert('Data berhasil dihapus!');
                window.location.href = 'datauser.php';
              </script>";
    } else {
        echo "Gagal menghapus: " . mysqli_error($conn);
    }
} else {
    // Jika mencoba akses langsung tanpa ID, arahkan balik
    header("Location: datauser.php");
}
?>