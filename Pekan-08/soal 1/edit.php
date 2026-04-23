<?php
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
header('Location: index.php?c=admin&m=edit&id=' . $id, true, 302);
exit;
