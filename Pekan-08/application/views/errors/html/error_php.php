<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>PHP Error</title>
    <style>
        body { background: #f8f9fa; font-family: Consolas, Monaco, monospace; padding: 20px; }
        .error { background: white; border: 1px solid #ddd; border-radius: 8px; padding: 20px; max-width: 800px; margin: 0 auto; }
        h1 { color: #e74c3c; }
    </style>
</head>
<body>
    <div class="error">
        <h1>PHP Error</h1>
        <p><strong>Type:</strong> <?= $severity; ?></p>
        <p><strong>Message:</strong> <?= $message; ?></p>
        <p><strong>Filename:</strong> <?= $filepath; ?></p>
        <p><strong>Line Number:</strong> <?= $line; ?></p>
    </div>
</body>
</html>