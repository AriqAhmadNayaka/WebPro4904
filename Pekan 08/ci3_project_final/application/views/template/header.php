<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? $page_title . ' | Manajemen Proyek' : 'Manajemen Proyek' ?></title>
    <meta name="description" content="Sistem Manajemen Proyek dengan CodeIgniter 3 MVC">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>

<!-- Sidebar Overlay (mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- ============ SIDEBAR ============ -->
<nav class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-logo">
            <i class="fas fa-folder-open"></i>
        </div>
        <div class="brand-text">
            <span class="brand-title">ProyekCI3</span>
            <span class="brand-subtitle">CodeIgniter MVC</span>
        </div>
    </div>

    <div class="sidebar-user">
        <div class="user-avatar">
            <i class="fas fa-user-circle"></i>
        </div>
        <div class="user-info">
            <span class="user-name"><?= $this->session->userdata('username') ?></span>
            <span class="user-role badge">User</span>
        </div>
    </div>

    <ul class="sidebar-menu">
        <li class="menu-label">NAVIGASI</li>

        <li class="<?= ($this->uri->segment(1) == 'dashboard') ? 'active' : '' ?>">
            <a href="<?= site_url('dashboard') ?>">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="<?= ($this->uri->segment(1) == 'proyek') ? 'active' : '' ?>">
            <a href="<?= site_url('proyek') ?>">
                <i class="fas fa-folder-open"></i>
                <span>Manajemen Proyek</span>
            </a>
        </li>

        <li class="menu-label">AKUN</li>
        <li>
            <a href="<?= site_url('auth/logout') ?>" class="logout-link" id="btn-logout">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </li>
    </ul>
</nav>

<!-- ============ MAIN WRAPPER ============ -->
<div class="main-wrapper" id="mainWrapper">

    <!-- Topbar -->
    <header class="topbar">
        <div class="topbar-left">
            <button class="btn-toggle-sidebar" id="toggleSidebar">
                <i class="fas fa-bars"></i>
            </button>
            <nav aria-label="breadcrumb" class="d-none d-md-block">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="<?= site_url('dashboard') ?>">Home</a>
                    </li>
                    <?php if (isset($breadcrumb)): ?>
                    <li class="breadcrumb-item active"><?= $breadcrumb ?></li>
                    <?php endif; ?>
                </ol>
            </nav>
        </div>
        <div class="topbar-right">
            <div class="topbar-item dropdown">
                <button class="btn-avatar dropdown-toggle" data-bs-toggle="dropdown" id="user-dropdown">
                    <i class="fas fa-user-circle"></i>
                    <span class="d-none d-md-inline"><?= $this->session->userdata('username') ?></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><h6 class="dropdown-header"><i class="fas fa-user me-1"></i><?= $this->session->userdata('username') ?></h6></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item text-danger" href="<?= site_url('auth/logout') ?>" id="dropdown-logout">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <!-- ============ PAGE CONTENT ============ -->
    <main class="page-content">

        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title"><?= isset($page_title) ? $page_title : '' ?></h1>
                <?php if (isset($page_subtitle)): ?>
                <p class="page-subtitle"><?= $page_subtitle ?></p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Flash Messages -->
        <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <?= $this->session->flashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            <?= $this->session->flashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <!-- Content injected here -->
