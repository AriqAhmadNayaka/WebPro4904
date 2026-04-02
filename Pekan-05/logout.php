<?php
// Memanggil helper session untuk akses fungsi logout.
include "session_helper.php";

// Menghapus session dan cookie user.
logoutUser();

// Mengarahkan user kembali ke halaman login.
header("Location: auth.php");
// Menghentikan proses file setelah redirect.
exit();
?>
