<?php

require_once __DIR__ . '/../koneksi.php';
require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/Auth.php';
require_once __DIR__ . '/FileUploader.php';
require_once __DIR__ . '/ChildRepository.php';
require_once __DIR__ . '/UserRepository.php';

$database = new Database($conn);
$auth = new Auth();
$childRepository = new ChildRepository($database->getConnection());
$fileUploader = new FileUploader(__DIR__ . '/../uploads');
$userRepository = new UserRepository($database->getConnection());
