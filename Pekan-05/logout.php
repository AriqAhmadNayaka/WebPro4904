<?php
session_start();

// Hapus session
session_unset();
session_destroy();

// Hapus cookie
setcookie("user_id", "", time() - 3600, "/");
setcookie("nama", "", time() - 3600, "/");

// Redirect
header("Location: 1login.php");
exit();