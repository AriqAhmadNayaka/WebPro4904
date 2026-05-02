<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo isset($title) ? htmlspecialchars($title) : 'CRUD with AJAX'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="<?php echo site_url('crudjs'); ?>">CRUD JS</a>
        <div class="d-flex gap-2">
            <a class="btn btn-sm btn-outline-light" href="<?php echo site_url('crudjs/debug'); ?>">Debug</a>
            <a class="btn btn-sm btn-outline-light" href="<?php echo site_url('crudjs/test'); ?>">Test API</a>
        </div>
    </div>
</nav>
