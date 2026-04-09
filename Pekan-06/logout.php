<?php

require_once "session_init.php";
require_once "Class/Auth.php";

$auth = new Auth();
$auth->logout();

header("Location: login.php");
exit;