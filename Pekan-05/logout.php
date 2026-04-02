<?php
session_start();
session_destroy();

// hapus cookie juga
setcookie("username", "", time() - 3600);

header("Location: login2.php");
exit;
?>