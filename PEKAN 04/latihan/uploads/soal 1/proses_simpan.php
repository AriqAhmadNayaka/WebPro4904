<?php
include 'koneksi.php';

if (isset($_POST['nama'])) {
    $nama     = mysqli_real_escape_string($conn, $_POST['nama']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $query = "INSERT INTO users (nama, email, password) VALUES ('$nama', '$email', '$password')";

    // Bagian HTML & SweetAlert2 agar tidak polos
    echo "<!DOCTYPE html>
    <html>
    <head>
        <title>Proses Simpan...</title>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <link href='https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600&display=swap' rel='stylesheet'>
        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFB; }
        </style>
    </head>
    <body>";

    if ($conn->query($query) === TRUE) {
        echo "<script>
            Swal.fire({
                title: 'Mantap!',
                text: 'Data kamu sudah tersimpan di WeBandoo+',
                icon: 'success',
                confirmButtonColor: '#4A8645',
                confirmButtonText: 'Oke Sip!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'index.php';
                }
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                title: 'Waduh!',
                text: 'Gagal simpan nih: " . $conn->error . "',
                icon: 'error',
                confirmButtonColor: '#d33'
            }).then(() => {
                window.history.back();
            });
        </script>";
    }

    echo "</body></html>";
}
?>