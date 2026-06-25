<?php require_once __DIR__ . '/partials/auth_guard.php'; ?>
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <title>Peta Lokasi - WeBandoo+</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
    />

    <link
      rel="stylesheet"
      href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    />

    <link
      rel="stylesheet"
      href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css"
    />

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<link rel="stylesheet" href="https://unpkg.com/leaflet-gesture-handling/dist/leaflet-gesture-handling.min.css">
<script src="https://unpkg.com/leaflet-gesture-handling"></script>

<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
<script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
<link rel="stylesheet" href="assets/css/webandoo-enhancements.css">
<link rel="stylesheet" href="assets/css/webandoo-layout.css">
<script src="https://mcp.figma.com/mcp/html-to-design/capture.js" async></script>

    <style>
      :root {
        --color-primary-green: #4a8645;
        --color-white: #ffffff;
        --color-off-white: #f0f2f5;
        --color-text-dark: #2c3e50;
        --color-text-grey: #7f8c8d;
        --shadow-elevation: 0 8px 30px rgba(0, 0, 0, 0.08);
        --color-red-danger: #e74c3c;
      }

      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Poppins", sans-serif;
      }

body {
    margin: 0;
    padding: 0;
    padding-top: 100px;
    min-height: 100vh;
    background-color: var(--color-off-white);
    display: block; 
}

      .navbar {
        width: 100%;
        background-color: var(--color-text-dark);
        color: var(--color-white);
        padding: 20px 30px;
        box-shadow: var(--shadow-elevation);
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: fixed;
        top: 0;
        z-index: 1100 !important;
      }

      .navbar .logo {
        font-size: 26px;
        font-weight: 700;
        color: var(--color-primary-green);
        text-decoration: none;
      }

      .nav-links {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        list-style: none;
        margin: 0;
        padding: 0;
      }

      .nav-links li {
        margin-left: 0;
      }

      .nav-link {
        display: flex;
        align-items: center;
        padding: 10px 15px;
        color: #bdc3c7;
        text-decoration: none;
        border-radius: 8px;
        transition: background-color 0.3s, color 0.3s;
        font-weight: 500;
      }

      .nav-link i {
        margin-right: 8px;
        font-size: 18px;
      }

      .nav-link:hover {
        background-color: #34495e;
        color: var(--color-white);
      }

      .nav-link.active {
        background-color: var(--color-primary-green);
        color: var(--color-white);
        box-shadow: 0 4px 10px rgba(74, 134, 69, 0.4);
      }

      .navbar-right {
        display: flex;
        align-items: center;
      }

      .avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        margin-left: 15px;
        border: 2px solid var(--color-primary-green);
        overflow: hidden;
        cursor: pointer;
      }

      .avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
      }

      .toggle-btn-leave {
        background-color: var(--color-primary-green);
        color: var(--color-white);
        box-shadow: 0 4px 10px rgba(74, 134, 69, 0.4);
        font-size: 15px;
        cursor: pointer;
        padding: 10px;
        border-radius: 8px;
        transition: background-color 0.3s;
        margin-left: 15px;
        text-decoration: none;
      }

      .toggle-btn-set {
        background-color: var(--color-primary-green);
        color: var(--color-white);
        box-shadow: 0 4px 10px rgba(74, 134, 69, 0.4);
        font-size: 15px;
        cursor: pointer;
        padding: 10px;
        border-radius: 8px;
        transition: background-color 0.3s;
        margin-left: 15px;
        text-decoration: none;
      }

.main-content {
    padding: 20px clamp(72px, 5vw, 120px);
    width: 100%; 
    max-width: none;
    margin: 20px 0; /* kasih jarak wajar */
    text-align: center;
}

.hero-section {
    margin-top: 50px !important;
    width: 100%;
    background: white;
    padding: 40px 20px;
    border-radius: 20px;
    box-shadow: var(--shadow-elevation);
    margin-bottom: 30px;
}

.hero-section h1 {
    font-size: 24px;
    margin-bottom: 20px;
}

.search-container {
    display: flex;
    background: #f8f9fa;
    border-radius: 50px;
    padding: 5px 20px;
    border: 1px solid #ddd;
    max-width: 700px; 
    margin: 0 auto 25px;
    align-items: center;
}

.search-container input {
    flex: 1;
    border: none;
    background: transparent;
    padding: 12px;
    outline: none;
}

.category-buttons {
    display: flex;
    justify-content: center;
    gap: 15px;
    flex-wrap: wrap;
}

.cat-btn {
    display: flex;
    align-items: center;
    gap: 10px;
    background: white;
    border: 1px solid #eee;
    padding: 8px 20px 8px 10px;
    border-radius: 50px;
    cursor: pointer;
    transition: 0.3s;
    font-weight: 500;
}

.cat-icon {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.cat-btn.active {
    background: #eef5ee;
    border-color: var(--color-primary-green);
    color: var(--color-primary-green);
}

.map-insight-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 16px;
    margin: 24px 0;
}

.map-insight-card {
    background: white;
    border-radius: 16px;
    padding: 18px;
    box-shadow: var(--shadow-elevation);
    border: 1px solid #edf0ed;
    text-align: left;
}

.map-insight-card i {
    color: var(--color-primary-green);
    margin-right: 8px;
}

.map-insight-card h3 {
    font-size: 16px;
    margin-bottom: 8px;
    color: var(--color-text-dark);
}

.map-insight-card p {
    font-size: 13px;
    color: var(--color-text-grey);
    line-height: 1.6;
}

.map-insight-card strong {
    color: var(--color-primary-green);
}

.route-action-row {
    margin-top: 22px;
    display: flex;
    justify-content: center;
    gap: 14px;
    flex-wrap: wrap;
}

.marker-pin {
    width: 40px;
    height: 40px;
    border-radius: 50% 50% 50% 0;
    transform: rotate(-45deg);
    display: flex;
    align-items: center;
    justify-content: center;
    border: 3px solid white;
    box-shadow: 0 4px 10px rgba(0,0,0,0.2);
}

.marker-pin i {
    transform: rotate(45deg);
    color: white;
    font-size: 16px;
}

      .header-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 40px;
        background: var(--color-white);
        padding: 20px 30px;
        border-radius: 12px;
        box-shadow: var(--shadow-elevation);
      }

      .header-bar h1 {
        font-size: 30px;
        font-weight: 700;
        color: var(--color-text-dark);
      }

      .search-bar-container {
        display: flex;
        align-items: center;
        margin-bottom: 40px;
        background: var(--color-white);
        padding: 15px 20px;
        border-radius: 100px;
        box-shadow: var(--shadow-elevation);
        max-width: 1000px;
        margin-left: auto;
        margin-right: auto;
      }

      .search-input {
        flex: 1;
        border: none;
        outline: none;
        font-size: 16px;
        color: var(--color-text-dark);
        background: transparent;
        padding: 10px;
        font-family: "Poppins", sans-serif;
      }

      .search-input::placeholder {
        color: var(--color-text-grey);
      }

      .search-btn {
        background-color: var(--color-primary-green);
        color: var(--color-white);
        border: none;
        padding: 10px 15px;
        border-radius: 8px;
        cursor: pointer;
        transition: background-color 0.3s;
        font-size: 16px;
      }

      .search-btn:hover {
        background-color: #3a6b37;
      }

      .search-btn i {
        font-size: 16px;
      }

      .user-profile {
        display: flex;
        align-items: center;
        font-weight: 600;
        color: var(--color-text-grey);
      }

      .user-profile .avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        margin-left: 15px;
        border: 2px solid var(--color-primary-green);
        overflow: hidden;
        cursor: pointer;
      }

      .user-profile .avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
      }

.map-area {
    height: 600px;
    width: 100%;
    position: relative;
    border-radius: 20px;
    overflow: hidden;
}

#map {
    height: 100% !important;
    width: 100% !important;
}

.location-preview-card {
    position: absolute;
    bottom: 20px;
    right: 20px;
    width: 300px;
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    z-index: 1000;
    display: none; 
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from { transform: translateY(20px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

.preview-img {
    width: 100%;
    height: 150px;
    object-fit: cover;
}

.preview-body {
    padding: 15px;
}

.badge-cat {
    font-size: 10px;
    text-transform: uppercase;
    padding: 3px 8px;
    border-radius: 20px;
    color: white;
    font-weight: bold;
    margin-bottom: 5px;
    display: inline-block;
}

.rating-star { color: #f1c40f; margin-right: 5px; }
.popular-tag {
    background: #f0f2f5;
    font-size: 11px;
    padding: 4px 8px;
    border-radius: 5px;
    display: block;
    margin-top: 10px;
}

#menuModal {
    display: none;
    position: fixed;
    z-index: 2000 !important;
    inset: 0;
    background: rgba(0,0,0,0.9);
    justify-content: center;
    align-items: center;
    padding: 20px;
}

#menuModal img {
    max-width: 90%;
    max-height: 90vh;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(255,255,255,0.2);
}

.btn-menu-view {
    background: #f39c12;
    color: white;
    border: none;
    padding: 8px 12px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 12px;
    margin-top: 10px;
    width: 100%;
    font-weight: 600;
}

.map-review-section {
    margin-top: 28px;
    display: grid;
    grid-template-columns: minmax(280px, 380px) minmax(0, 1fr);
    gap: 22px;
    text-align: left;
}

.map-review-form,
.map-review-list-panel {
    background: white;
    border: 1px solid #edf0ed;
    border-radius: 18px;
    box-shadow: var(--shadow-elevation);
    padding: 22px;
}

.map-review-form {
    display: grid;
    gap: 14px;
}

.map-review-title h2 {
    color: var(--color-text-dark);
    font-size: 22px;
    margin-bottom: 6px;
}

.map-review-title p {
    color: var(--color-text-grey);
    font-size: 13px;
    line-height: 1.6;
}

.map-review-form label {
    display: grid;
    gap: 8px;
    color: var(--color-text-dark);
    font-size: 13px;
    font-weight: 700;
}

.map-review-form input,
.map-review-form select,
.map-review-form textarea,
.map-review-tools input,
.map-review-tools select {
    width: 100%;
    border: 1px solid #dfe6df;
    border-radius: 12px;
    padding: 12px 13px;
    background: white;
    color: var(--color-text-dark);
    font: inherit;
}

.map-review-form textarea {
    resize: vertical;
}

.map-review-actions,
.map-review-list-head,
.map-review-item-foot {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
}

.map-review-actions button,
.map-review-item-foot button {
    border: none;
    border-radius: 12px;
    padding: 11px 14px;
    background: var(--color-primary-green);
    color: white;
    cursor: pointer;
    font: inherit;
    font-weight: 700;
}

.map-review-actions button[type="button"],
.map-review-item-foot button:last-child {
    background: #eef5ee;
    color: var(--color-text-dark);
}

.map-review-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
    gap: 12px;
    margin: 16px 0;
}

.map-review-stats article {
    background: #f7faf7;
    border-radius: 14px;
    padding: 14px;
}

.map-review-stats small,
.map-review-item-head span {
    display: block;
    color: var(--color-text-grey);
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    margin-bottom: 5px;
}

.map-review-stats strong {
    color: var(--color-primary-green);
    font-size: 22px;
}

.map-review-tools {
    display: grid;
    grid-template-columns: minmax(180px, 1fr) minmax(160px, 220px);
    gap: 10px;
}

.map-review-list {
    display: grid;
    gap: 14px;
    margin-top: 16px;
}

.map-review-item,
.review-item-card {
    background: #fbfdfb;
    border: 1px solid #edf0ed;
    border-radius: 16px;
    padding: 16px;
}

.review-item-head {
    display: flex;
    justify-content: space-between;
    gap: 12px;
}

.review-item-head h3 {
    margin: 0;
    color: var(--color-text-dark);
    font-size: 16px;
}

.review-item-head strong {
    color: #d89d0f;
}

.review-item-card p {
    margin: 10px 0 14px;
    color: var(--color-text-grey);
    font-size: 13px;
    line-height: 1.7;
}

.review-item-foot {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    color: var(--color-text-grey);
    font-size: 13px;
}

.review-item-foot button {
    border: none;
    border-radius: 10px;
    padding: 9px 12px;
    background: var(--color-primary-green);
    color: white;
    font: inherit;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
}

.review-item-foot button:last-child {
    background: #eef5ee;
    color: var(--color-text-dark);
}

.review-empty {
    margin-top: 16px;
    padding: 28px;
    border: 1px dashed #cdd8cd;
    border-radius: 16px;
    color: var(--color-text-grey);
    text-align: center;
}

      .leaflet-bar {
        border: none !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
      }

      .leaflet-bar a {
        background-color: #ffffff !important;
        color: #2c3e50 !important;
        border-bottom: 1px solid #eee !important;
      }

      .leaflet-control-custom a {
    width: 34px;
    height: 34px;
    background: white;
    cursor: pointer;
}

.leaflet-popup-content-wrapper {
    border-radius: 15px;
    padding: 5px;
}

.btn-dark {
    width: 100%;
    padding: 8px;
    background: var(--color-primary-green);
    color: white;
    border-radius: 8px;
    margin-top: 10px;
    border: none;
    cursor: pointer;
}

.overlay {
    display: none;
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(5px);
    justify-content: center;
    align-items: center;
}

.modal-content {
    background-color: white;
    padding: 30px;
    border-radius: 20px;
    width: 90%;
    max-width: 400px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    text-align: center;
}

.modal-content h3 {
    margin-bottom: 20px;
    color: var(--color-primary-green);
}

.modal-form-input {
    width: 100%;
    padding: 12px 15px;
    margin-bottom: 15px;
    border: 1px solid #ddd;
    border-radius: 10px;
    font-family: 'Poppins', sans-serif;
    outline: none;
}

.modal-form-input:focus {
    border-color: var(--color-primary-green);
}

      .filter-box {
        background: white;
        padding: 10px 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        box-shadow: var(--shadow-elevation);
      }

      #imageViewer {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.85);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 9999;
      }
      #imageViewer img {
        max-width: 90%;
        max-height: 90%;
        border-radius: 12px;
      }

      .category-buttons {
        display: flex;
        gap: 10px;
        margin-top: 12px;
        flex-wrap: wrap;
      }

      .cat-btn {
        padding: 8px 18px;
        border-radius: 40px;
        border: none;
        background: #ffffff;
        font-weight: 500;
        cursor: pointer;
        box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.08);
        transition: 0.25s;
      }

      .cat-btn:hover {
        background: #e8efe6;
        transform: translateY(-2px);
      }

      .cat-btn.active {
        background: var(--color-primary-green);
        color: white;
        box-shadow: 0px 5px 15px rgba(74, 134, 69, 0.4);
      }

      .btn-dark {
        background-color: var(--color-text-dark);
        color: var(--color-white);
        border: none;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 13px;
        cursor: pointer;
        margin-top: 5px;
      }
      .btn-dark:hover {
        background-color: #34495e;
      }

      #inputRuteBawah {
        color: #ffffff !important;
      }

      #inputRuteBawah::placeholder {
        color: rgba(255, 255, 255, 0.8) !important;
        opacity: 1; 
      }

      #inputRuteBawah::-webkit-input-placeholder {
        color: rgba(255, 255, 255, 0.8) !important;
      }

.file-drop-area {
    border: 2px dashed #4a8645;
    padding: 15px;
    text-align: center;
    border-radius: 8px;
    margin-bottom: 10px;
    cursor: pointer;
    background: rgba(255,255,255,0.05);
}
.file-drop-area.active {
    background: rgba(74, 134, 69, 0.2);
    border-style: solid;
}
.file-drop-area p {
    font-size: 11px;
    color: #aaa;
    margin-bottom: 5px;
}

      footer {
        background-color: var(--color-text-dark);
        color: var(--color-white);
        padding: 50px 40px 20px;
        margin-top: auto;
        border-radius: 40px 40px 0 0;
      }

      .footer-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 40px;
        width: 100%;
        max-width: none;
        margin: 0;
      }

      .footer-col h3 {
        color: var(--color-primary-green);
        font-size: 22px;
        margin-bottom: 20px;
        font-weight: 700;
      }

      .footer-col p {
        color: #bdc3c7;
        font-size: 14px;
        line-height: 1.6;
        margin-bottom: 20px;
      }

      .footer-col h4 {
        font-size: 18px;
        margin-bottom: 20px;
        position: relative;
        padding-bottom: 10px;
      }

      .footer-col h4::after {
        content: "";
        position: absolute;
        left: 0;
        bottom: 0;
        width: 50px;
        height: 2px;
        background-color: var(--color-primary-green);
      }

      .footer-col ul {
        list-style: none;
      }

      .footer-col ul li {
        margin-bottom: 12px;
      }

      .footer-col ul li a {
        color: #bdc3c7;
        text-decoration: none;
        transition: 0.3s;
        font-size: 14px;
      }

      .footer-col ul li a:hover {
        color: var(--color-primary-green);
        padding-left: 8px;
      }

      .social-links {
        display: flex;
        gap: 15px;
      }

      .social-links a {
        width: 40px;
        height: 40px;
        background-color: #34495e;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        color: var(--color-white);
        text-decoration: none;
        transition: 0.3s;
      }

      .social-links a:hover {
        background-color: var(--color-primary-green);
        transform: translateY(-3px);
      }

      .footer-bottom {
        text-align: center;
        margin-top: 50px;
        padding-top: 20px;
        border-top: 1px solid #34495e;
        color: #7f8c8d;
        font-size: 13px;
      }

      @media (max-width: 768px) {
        .navbar {
          flex-direction: column;
          padding: 10px;
        }
        .nav-links {
          flex-direction: column;
          margin-top: 10px;
        }
        .nav-links li {
          margin-left: 0;
          margin-bottom: 10px;
        }
        .navbar-right {
          margin-top: 10px;
        }
        .main-content {
          margin-top: 150px;
          padding: 20px;
        }

        .map-review-section,
        .map-review-tools {
          grid-template-columns: 1fr;
        }
      }
    </style>
  </head>
<body>
  <nav class="navbar">
    <a href="home.php" class="logo">WeBandoo+</a>
    <ul class="nav-links">
      <li><a href="home.php" class="nav-link"><i class="fas fa-chart-line"></i> Dashboard</a></li>
      <li><a href="warisan.php" class="nav-link"><i class="fas fa-landmark"></i> Warisan & Cagar Budaya</a></li>
      <li><a href="peta.php" class="nav-link active"><i class="fas fa-map-pin"></i> Peta Lokasi</a></li>
      <li><a href="kuliner.php" class="nav-link"><i class="fas fa-utensils"></i> Kuliner</a></li>
      <li><a href="kafe.php" class="nav-link"><i class="fas fa-coffee"></i> Kafe</a></li>
      <li><a href="event.php" class="nav-link"><i class="fas fa-calendar-day"></i> Event & Jadwal</a></li>
      <li><a href="eduction.php" class="nav-link"><i class="fas fa-book"></i> Belajar</a></li>
    </ul>
    <div class="navbar-right">
      <div class="avatar"><a href="profil.php"><img src="profil.jpeg" alt="Avatar" /></a></div>
      <a href="pengaturan.php" class="toggle-btn-leave"><i class="fas fa-cog"></i></a>
      <a href="keluar.php" class="toggle-btn-set"><i class="fas fa-sign-out-alt"></i> Keluar</a>
    </div>
  </nav>

<div class="main-content">
    <div class="hero-section">
        <h1>Cari dan Jelajahi Lokasi Pilihanmu</h1>
        
        <div class="search-container">
            <input type="text" id="searchInput" placeholder="Cari warisan, event, atau lokasi...">
            <button class="search-btn" style="background:none; border:none; color:var(--color-primary-green); cursor:pointer;">
                <i class="fas fa-search"></i>
            </button>
        </div>

        <div class="category-wrapper">
            <p style="font-weight: 600; font-size: 14px; margin-bottom: 15px; color: var(--color-text-grey);">Pilih Kategori Lokasi:</p>
            <div class="category-buttons">
                <button class="cat-btn active" data-cat="all">
                    <div class="cat-icon" style="background: #4a8645;"><i class="fas fa-th-large"></i></div> Semua
                </button>
                <button class="cat-btn" data-cat="sejarah">
                    <div class="cat-icon" style="background: #1abc9c;"><i class="fas fa-landmark"></i></div> Situs Bersejarah
                </button>
                <button class="cat-btn" data-cat="ruangpublik">
                    <div class="cat-icon" style="background: #2ecc71;"><i class="fas fa-tree"></i></div> Ruang Publik
                </button>
                <button class="cat-btn" data-cat="kafe">
                    <div class="cat-icon" style="background: #3498db;"><i class="fas fa-coffee"></i></div> Kafe
                </button>
                <button class="cat-btn" data-cat="kuliner">
                    <div class="cat-icon" style="background: #e67e22;"><i class="fas fa-utensils"></i></div> Kuliner
                </button>
                <button class="cat-btn" data-cat="event">
                    <div class="cat-icon" style="background: #9b59b6;"><i class="fas fa-calendar-check"></i></div> Event
                </button>
                <button class="cat-btn" data-cat="hiddengem">
                    <div class="cat-icon" style="background: #f1c40f;"><i class="fas fa-gem"></i></div> Hidden Gem
                </button>
                <button class="cat-btn" data-cat="spotfoto">
                    <div class="cat-icon" style="background: #e84393;"><i class="fas fa-camera-retro"></i></div> Spot Foto
                </button>
            </div>
        </div>
    </div>

    <div class="map-insight-grid">
        <div class="map-insight-card">
            <h3><i class="fas fa-route"></i> Navigasi & Rute</h3>
            <p>Pilih titik di peta untuk melihat rute, jarak, dan estimasi waktu menuju lokasi.</p>
        </div>
        <div class="map-insight-card">
            <h3><i class="fas fa-wallet"></i> Estimasi Biaya</h3>
            <p>Setiap lokasi menampilkan tiket, rentang harga, parkir, dan total perkiraan. Contoh Braga: <strong>Rp55.000</strong>.</p>
        </div>
        <div class="map-insight-card">
            <h3><i class="fas fa-star"></i> Review & Rating</h3>
            <p>Rating membantu pengguna menilai pengalaman orang lain sebelum datang.</p>
        </div>
        <div class="map-insight-card">
            <h3><i class="fas fa-heart"></i> Favorit / Wishlist</h3>
            <p>Simpan lokasi untuk membuat daftar tujuan wisata, kuliner, kafe, dan event.</p>
        </div>
    </div>

    <div class="map-area">
        <div id="map"></div>
        <div id="previewCard" class="location-preview-card"></div>
    </div>

    <div class="route-action-row">
        <button class="search-btn" id="btnBukaModalRute" style="background: #4a8645; color: white; border-radius: 50px; padding: 15px 40px; border:none; cursor:pointer; font-weight:bold; font-size: 16px; box-shadow: 0 4px 15px rgba(74,134,69,0.3);">
            <i class="fas fa-route"></i> Cari Rute Sekarang
        </button>
        <button id="btnHapusRute" onclick="clearRoute()" style="background: #e74c3c; color: white; border-radius: 50px; padding: 15px 40px; border:none; cursor:pointer; font-weight:bold; display:none; margin-left:15px; font-size: 16px;">
            <i class="fas fa-times"></i> Hapus Rute
        </button>
    </div>

    <section class="map-review-section" id="reviewLokasi">
        <form class="map-review-form" data-review-form>
            <input type="hidden" name="reviewId">
            <div class="map-review-title">
                <h2>Review Lokasi</h2>
                <p>Klik marker di peta lalu beri rating untuk tempat yang kamu pilih.</p>
            </div>
            <label>
                Tempat
                <select name="place" data-place-select required></select>
            </label>
            <label>
                Nama Reviewer
                <input type="text" name="name" placeholder="Nama kamu" required>
            </label>
            <label>
                Rating <strong data-rating-label>5/5</strong>
                <input type="range" name="rating" min="1" max="5" value="5">
            </label>
            <label>
                Tanggal Kunjungan
                <input type="date" name="visitDate">
            </label>
            <label>
                Komentar
                <textarea name="comment" rows="4" placeholder="Ceritakan suasana, fasilitas, harga, atau tips sebelum datang..." required></textarea>
            </label>
            <div class="map-review-actions">
                <button type="submit"><i class="fas fa-save"></i> <span data-submit-label>Simpan Review</span></button>
                <button type="button" data-cancel-edit hidden><i class="fas fa-xmark"></i> Batal Edit</button>
            </div>
        </form>

        <section class="map-review-list-panel">
            <div class="map-review-list-head">
                <div class="map-review-title">
                    <h2>Daftar Review</h2>
                    <p>Review tersimpan di browser perangkat ini.</p>
                </div>
                <div class="map-review-tools">
                    <input type="search" data-review-search placeholder="Cari review...">
                    <select data-place-filter></select>
                </div>
            </div>
            <div class="map-review-stats">
                <article>
                    <small>Total Review</small>
                    <strong data-total-reviews>0</strong>
                </article>
                <article>
                    <small>Rata-rata</small>
                    <strong data-average-rating>-</strong>
                </article>
                <article>
                    <small>Terbaik</small>
                    <strong data-best-place>-</strong>
                </article>
            </div>
            <div class="review-empty" data-empty-state hidden>
                <i class="fas fa-comment-slash"></i>
                <p>Belum ada review yang cocok.</p>
            </div>
            <div class="map-review-list" data-review-list></div>
        </section>
    </section>
</div>

<footer>
                <div class="footer-container">
                    <div class="footer-col">
                        <h3>WeBandoo+</h3>
                        <p>Platform digital pelestarian cagar budaya dan warisan sejarah Kota Bandung. Menghubungkan
                            generasi muda dengan akar budaya tanah Pasundan.</p>
                        <div class="social-links">
                            <a href="#"><i class="fab fa-instagram"></i></a>
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="javascript:void(0)" id="adminTrigger" data-admin-only onclick="toggleAdminMode()" title="Mode admin">
                                <i class="fas fa-user-shield"></i>
                            </a>
                        </div>
                    </div>

                    <div class="footer-col">
                        <h4>Navigasi</h4>
                        <ul>
                            <li><a href="home.php">Dashboard Utama</a></li>
                            <li><a href="warisan.php">Daftar Warisan</a></li>
                            <li><a href="peta.php">Eksplorasi Peta</a></li>
                            <li><a href="kuliner.php">Kuliner Bandung</a></li>
                            <li><a href="kafe.php">Kafe & Nongkrong</a></li>
                            <li><a href="event.php">Agenda Budaya</a></li>
                        </ul>
                    </div>

                    <div class="footer-col">
                        <h4>Hubungi Kami</h4>
                        <ul>
                            <li><a href="#"><i class="fas fa-map-marker-alt"></i> Jl. Telekomunikasi No.1</a>
                            </li>
                            <li><a href="#"><i class="fas fa-phone"></i> (022) 123-4567</a></li>
                            <li><a href="#"><i class="fas fa-envelope"></i> info@webandoo.id</a></li>
                        </ul>
                    </div>
                </div>

                <div class="footer-bottom">
                    <p>&copy; 2025 WeBandoo+ Warisan Bandung. All Rights Reserved.</p>
                </div>
            </footer>

  <div id="locationOverlay" class="overlay">
    <div class="modal-content">
      <h3><i class="fas fa-route"></i> Cari Rute</h3>
      <form id="routeForm">
        <input type="text" id="startLoc" class="modal-form-input" placeholder="Lokasi Asal" required />
        <input type="text" id="destLoc" class="modal-form-input" placeholder="Lokasi Tujuan" required />
        <div style="display: flex; gap: 10px">
          <button type="submit" class="toggle-btn-leave" style="flex: 1; margin: 0; border:none;">Cari</button>
          <button type="button" id="closeModalBtn" class="toggle-btn-set" style="flex: 1; background: #6c757d; border:none;">Tutup</button>
        </div>
      </form>
    </div>
  </div>

  <div id="menuModal" onclick="closeMenuModal()">
    <span style="position:absolute; top:20px; right:30px; color:white; font-size:40px; cursor:pointer;">&times;</span>
    <img id="imgMenuFull" src="" alt="Menu Lengkap">
</div>

<div id="adminModal" class="overlay" style="display: none;">
    <div class="modal-content" style="max-width: 550px; background: #f3f3f3; color: white; border: 1px solid #444;">
        <h3><i class="fas fa-plus-circle"></i> Tambah Lokasi Baru</h3>
        
        <form id="adminLocationForm">
            <input type="hidden" id="newLat">
            <input type="hidden" id="newLng">

            <select id="newCategory" class="modal-form-input" onchange="toggleFormFields()" required>
                <option value="" disabled selected>Pilih Kategori</option>
                <option value="kafe">Kafe</option>
                <option value="kuliner">Kuliner</option>
                <option value="publik">Ruang Publik</option>
                <option value="sejarah">Situs Sejarah</option>
            </select>

            <input type="text" id="newName" class="modal-form-input" placeholder="Nama Lokasi" required>
            <input type="text" id="newAddress" class="modal-form-input" placeholder="Alamat Pasti (Contoh: Jl. Asia Afrika No. 1)" required>

            <div class="file-drop-area" id="dropAreaPlace" style="border-color: #34495e;">
                <p>Foto Tempat: Tarik file ke sini atau klik</p>
                <input type="text" id="newImage" class="modal-form-input" placeholder="Nama file foto atau URL">
            </div>

            <textarea id="newDesc" class="modal-form-input" placeholder="Deskripsi Singkat" required></textarea>

            <div id="fieldBisnis" style="display: none; background: rgba(52, 152, 219, 0.1); padding: 15px; border-radius: 10px; margin-top: 10px;">
                <div style="display: flex; gap: 10px; margin-bottom: 10px;">
                    <input type="number" step="0.1" id="newRating" class="modal-form-input" placeholder="Rating (1-5)" style="flex: 1;">
                    <input type="text" id="newPopular" class="modal-form-input" placeholder="Menu Populer" style="flex: 2;">
                </div>
                <div class="file-drop-area" id="dropAreaMenu" style="border-color: #3498db;">
                    <p>Foto Menu: Tarik file ke sini atau klik</p>
                    <input type="text" id="newMenuImage" class="modal-form-input" placeholder="Nama file menu atau URL">
                </div>
            </div>

            <div id="fieldSejarah" style="display: none; background: rgba(46, 204, 113, 0.1); padding: 15px; border-radius: 10px; margin-top: 10px;">
                <textarea id="newDetails" class="modal-form-input" placeholder="Detail Sejarah / Informasi Lengkap Tempat"></textarea>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="submit" class="toggle-btn-leave" style="flex: 1; border: none; cursor: pointer;">Simpan Lokasi</button>
                <button type="button" onclick="closeAdminModal()" class="toggle-btn-set" style="flex: 1; background: #a01111; border: none; cursor: pointer;">Batal</button>
            </div>
        </form>
    </div>
</div>

  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
  <script src="https://unpkg.com/leaflet-gesture-handling"></script>
  
  <script>
const map = L.map('map', {
    gestureHandling: true 
}).setView([-6.9147, 107.6098], 14);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

let userLatLng = null; 
let routingControl = null;

let locations = [
  { 
    name: "Gedung Sate", 
    category: "sejarah", 
    lat: -6.9025, lng: 107.6188, 
    icon: "fa-landmark", color: "#1abc9c", 
    image: "bdg5.jpg",
    desc: "Ikon sejarah Jawa Barat.",
    address: "Jl. Diponegoro No.22, Citarum",
    ticket: "Rp5.000 museum",
    hours: "08.00 - 16.00",
    cost: "Rp25.000 - Rp60.000",
    rating: "4.8",
    tags: "wisata sejarah budaya gedung sate museum foto keluarga",
    details: "Dibangun tahun 1920, gedung ini memiliki ciri khas ornamen tusuk sate pada menara pusatnya."
  },
  {
    name: "Jalan Braga",
    category: "spotfoto",
    lat: -6.9177, lng: 107.6098,
    icon: "fa-camera-retro", color: "#e84393",
    image: "bdg6.jpg",
    desc: "Koridor klasik dengan bangunan art deco, mural, kafe, dan spot foto malam hari.",
    address: "Jl. Braga, Sumur Bandung",
    ticket: "Gratis",
    hours: "24 jam",
    cost: "Rp30.000 - Rp80.000",
    rating: "4.7",
    tags: "spot foto braga wisata sejarah malam murah art deco nongkrong"
  },
  {
    name: "Kopi Mandja Progo",
    category: "kafe",
    lat: -6.9069, lng: 107.6157,
    icon: "fa-coffee", color: "#3498db",
    image: "KOPI1.jpg",
    desc: "Kafe nyaman untuk nongkrong, kerja ringan, dan menikmati suasana Bandung.",
    address: "Jl. Progo, Bandung",
    rating: "4.6",
    popular: "Kopi susu, pastry, area outdoor",
    menuImage: "KOPI2.png",
    ticket: "Gratis",
    hours: "08.00 - 22.00",
    cost: "Rp25.000 - Rp75.000",
    tags: "kafe cafe nongkrong murah wifi smoking area spot foto kopi"
  },
  {
    name: "Batagor Kingsley",
    category: "kuliner",
    lat: -6.9141, lng: 107.6118,
    icon: "fa-utensils", color: "#e67e22",
    image: "bdg3.jpg",
    desc: "Kuliner Bandung legendaris dengan menu batagor dan siomay.",
    address: "Jl. Veteran No.25, Bandung",
    rating: "4.5",
    popular: "Batagor, siomay, baso tahu",
    menuImage: "bdg3.jpg",
    ticket: "Gratis",
    hours: "09.00 - 20.00",
    cost: "Rp35.000 - Rp70.000",
    tags: "kuliner makanan harga batagor murah keluarga menu"
  },
  {
    name: "Taman Film Bandung",
    category: "ruangpublik",
    lat: -6.9004, lng: 107.6080,
    icon: "fa-tree", color: "#2ecc71",
    image: "bdg4.jpg",
    desc: "Ruang publik untuk bersantai, menonton layar terbuka, dan berkumpul.",
    address: "Jl. Layang Pasupati, Tamansari",
    ticket: "Gratis",
    hours: "06.00 - 22.00",
    cost: "Rp0 - Rp25.000",
    rating: "4.4",
    tags: "ruang publik gratis taman keluarga nongkrong murah"
  },
  {
    name: "Festival Asia Afrika",
    category: "event",
    lat: -6.9210, lng: 107.6096,
    icon: "fa-calendar-check", color: "#9b59b6",
    image: "event1.jpg",
    desc: "Agenda budaya tahunan berisi parade, musik, kuliner, dan pameran komunitas.",
    address: "Kawasan Asia Afrika",
    ticket: "Gratis - Rp50.000",
    hours: "10.00 - 21.00",
    cost: "Rp40.000 - Rp120.000",
    rating: "4.7",
    tags: "event festival konser pameran budaya jadwal bandung"
  },
  {
    name: "Gang Cibadak Malam",
    category: "hiddengem",
    lat: -6.9189, lng: 107.6008,
    icon: "fa-gem", color: "#f1c40f",
    image: "bdg1.jpg",
    desc: "Hidden gem kuliner malam dengan suasana lokal dan pilihan jajanan beragam.",
    address: "Cibadak, Astanaanyar",
    ticket: "Gratis",
    hours: "18.00 - 23.30",
    cost: "Rp20.000 - Rp65.000",
    rating: "4.5",
    tags: "hidden gem tempat lokal belum viral kuliner malam murah"
  }
];
window.webandooMapLocations = locations;

    let markers = [];
    let activeFilter = "all";
    let lastScrollY = window.scrollY;

    function createCustomIcon(iconClass, color) {
      return L.divIcon({
        className: 'custom-div-icon',
        html: `<div class="marker-pin" style="background:${color}"><i class="fas ${iconClass}"></i></div>`,
        iconSize: [40, 40],
        iconAnchor: [20, 40]
      });
    }

function legacyRenderMarkers(filter = "all") {
    markers.forEach(m => map.removeLayer(m));
    markers = [];
    locations.forEach(loc => {
        if (filter === "all" || loc.category === filter) {
            const m = L.marker([loc.lat, loc.lng], { icon: createCustomIcon(loc.icon, loc.color) }).addTo(map);
            m.on('click', () => {
                map.flyTo([loc.lat, loc.lng], 16);
                showPreview(loc); 
            });
            markers.push(m);
        }
    });
}

function legacyShowPreview(loc) {
    const card = document.getElementById("previewCard");
    
    let extraContent = "";
    if (loc.category === "sejarah" || loc.category === "ruangpublik" || loc.category === "event" || loc.category === "hiddengem" || loc.category === "spotfoto") {
        extraContent = `<p style="font-size:11px; color:#34495e; margin-top:5px;"><b>Detail:</b> ${loc.details || '-'}</p>`;
    } else {
        extraContent = `
            <div style="font-size:11px; color:#ffce00; margin-top:5px;">
                <i class="fas fa-star"></i> ${loc.rating || '0'} | <b>Populer:</b> ${loc.popular || '-'}
            </div>
            <button class="btn-dark" style="width:100%; padding:8px; background:#111; color:white; border:1px solid #444; border-radius:5px; cursor:pointer; margin: 10px 0 5px 0;" onclick="openMenuModal('${loc.menuImage}')">
                <i class="fas fa-book-open"></i> Lihat Menu & Harga
            </button>`;
    }

    card.innerHTML = `
        <div style="position:relative">
            <button onclick="this.parentElement.parentElement.style.display='none'" style="position:absolute; right:10px; top:10px; background:rgba(0,0,0,0.5); border:none; color:white; border-radius:50%; width:25px; height:25px; cursor:pointer; z-index:10">Ã—</button>
            <img src="${loc.image}" style="width:100%; height:150px; object-fit:cover;">
        </div>
        <div style="padding:15px; background:#ade62a; color:white;">
            <span style="background:${loc.color}; padding:2px 8px; border-radius:10px; font-size:10px;">${loc.category}</span>
            <h3 style="margin:5px 0;">${loc.name}</h3>
            <p style="font-size:12px; color:#ccc;">${loc.desc}</p>
            ${extraContent}
            <button class="btn-dark" style="width:100%; padding:10px; background:#4a8645; color:white; border:none; border-radius:5px; cursor:pointer; margin-top:10px;" onclick="setAsDestination('${loc.name}')">
                <i class="fas fa-directions"></i> Petunjuk Arah
            </button>
        </div>`;
    card.style.display = "block";
}

function showPreview(loc) {
    const card = document.getElementById("previewCard");
    if (!card) return;

    const reviewSummary = window.webandooGetReviewSummary ? window.webandooGetReviewSummary(loc.name) : null;
    const reviewText = reviewSummary && reviewSummary.count
        ? `${reviewSummary.average.toFixed(1)} dari ${reviewSummary.count} review`
        : "Belum ada review";

    const hasMenu = (loc.category === "kafe" || loc.category === "kuliner") && loc.menuImage;
    let dynamicContent = "";
    if (!hasMenu) {
        dynamicContent = `
            <div style="margin-top:10px; border-top: 1px solid #444; padding-top:10px;">
                <p style="font-size:12px; color:#34495e; line-height:1.4; text-align:left;">
                    <b style="color:#34495e;">Detail:</b> ${loc.details || loc.desc || '-'}
                </p>
                <p style="font-size:12px; color:#34495e; line-height:1.4; text-align:left; margin-top:6px;">
                    <b>Tiket:</b> ${loc.ticket || '-'}<br>
                    <b>Jam:</b> ${loc.hours || '-'}<br>
                    <b>Estimasi biaya:</b> ${loc.cost || '-'}
                </p>
            </div>`;
    } else {
        dynamicContent = `
            <div style="margin-top:8px; color:#ffce00; font-size:13px; font-weight:bold;">
                <i class="fas fa-star"></i> ${loc.rating || '0'} 
                <span style="color:#aaa; font-weight:normal; margin-left:5px;">
                    | Populer: ${loc.popular || '-'}
                </span>
            </div>
            <button style="width:100%; margin-top:10px; background:#ade62a; border:1px solid #ffff; color:#34495e; padding:10px; border-radius:8px; cursor:pointer; font-weight:bol" 
                    onclick="openMenuModal('${loc.menuImage}')">
                <i class="fas fa-book-open"></i> Lihat Menu & Harga
            </button>`;
    }

    let adminButtons = "";
    if (adminMode) {
        adminButtons = `
            <button style="width:100%; margin-top:8px; background:#c0392b; color:white; border:none; padding:8px; border-radius:8px; font-size:11px; cursor:pointer;" 
                    onclick="deleteLocation('${loc.name}')">
                <i class="fas fa-trash"></i> Hapus Titik Ini
            </button>`;
    }

    card.innerHTML = `
        <div style="position:relative;">
            <button onclick="document.getElementById('previewCard').style.display='none'" 
                    style="position:absolute; right:10px; top:10px; background:rgba(0,0,0,0.6); color:white; border:none; border-radius:50%; width:30px; height:30px; cursor:pointer; z-index:10;">Ã—</button>
            <img src="${loc.image}" onerror="this.src='https://via.placeholder.com/400x250?text=Gambar+Tidak+Ditemukan'" 
                 style="width:100%; height:180px; object-fit:cover; border-radius: 15px 15px 0 0;">
        </div>
        <div style="padding:20px; background:#ffff; color:white; border-radius: 0 0 15px 15px; text-align:center; border-top:none;">
            <span style="background:${loc.color || '#444'}; padding:3px 12px; border-radius:20px; font-size:10px; text-transform:uppercase; font-weight:bold; color:white;">
                ${loc.category}
            </span>
            <h2 style="margin:12px 0 5px 0; font-size:20px; color:#34495e;">${loc.name}</h2>
            <p style="font-size:11px; color:#34495e; margin-bottom:10px;"><i class="fas fa-map-marker-alt"></i> ${loc.address || 'Alamat tidak tersedia'}</p>
            <p style="font-size:13px; color:#34495e; line-height:1.5;">${loc.desc || 'Tidak ada deskripsi.'}</p>
            <p style="font-size:12px; color:#34495e; line-height:1.5; margin-top:8px;">
                <b>Jam:</b> ${loc.hours || '-'} | <b>Biaya:</b> ${loc.cost || '-'}
            </p>
            
            ${dynamicContent}
            
            <button style="width:100%; margin-top:15px; background:#4a8645; color:white; border:none; padding:12px; border-radius:8px; font-weight:bold; cursor:pointer;" 
                    onclick="setAsDestination('${loc.name}')"> 
                <i class="fas fa-paper-plane"></i> Petunjuk Arah
            </button>

            <button style="width:100%; margin-top:10px; background:#eef5ee; color:#34495e; border:1px solid #dfe6df; padding:11px; border-radius:8px; font-weight:bold; cursor:pointer;" 
                    onclick="window.webandooSelectReviewPlace && window.webandooSelectReviewPlace('${loc.name}')">
                <i class="fas fa-star"></i> Beri Review (${reviewText})
            </button>
            
            ${adminButtons}
        </div>
    `;
    card.style.display = "block";
}

async function deleteLocation(name) {
    const loc = locations.find(l => l.name === name);
    if (!loc) return;

    if (confirm("Apakah Anda yakin ingin menghapus titik '" + name + "'?")) {
        if (loc.id) {
            try {
                await requestLocationApi({
                    method: "DELETE",
                    body: JSON.stringify({ id: loc.id })
                });
            } catch (error) {
                alert(error.message);
                return;
            }
        }

        const index = locations.findIndex(l => l.name === name);
        if (index > -1) {
            locations.splice(index, 1);
            window.webandooMapLocations = locations;
            
            renderMarkers(); 
            
            document.getElementById('previewCard').style.display = 'none';
            alert("Titik berhasil dihapus.");
        }
    }
}

function matchesSmartSearch(loc, searchText) {
    if (!searchText) return true;
    const haystack = [
        loc.name,
        loc.category,
        loc.desc,
        loc.address,
        loc.details,
        loc.popular,
        loc.cost,
        loc.tags
    ].join(" ").toLowerCase();

    const synonyms = {
        "nongkrong": "kafe cafe kopi ruang publik",
        "murah": "gratis rp20 rp25 rp30 rp35 rp40 rp50",
        "sejarah": "sejarah budaya heritage gedung braga asia afrika",
        "foto": "spot foto kamera braga",
        "event": "event festival konser pameran budaya",
        "kuliner": "kuliner makanan batagor jajanan menu",
        "hidden": "hidden gem lokal belum viral"
    };

    const expanded = (synonyms[searchText] || searchText).split(" ");
    return searchText.split(" ").every(word => haystack.includes(word)) ||
        expanded.some(word => word && haystack.includes(word));
}

function renderMarkers(filter = activeFilter, searchText = "") {
    markers.forEach(m => map.removeLayer(m));
    markers = [];

    locations.forEach(loc => {
        if (filter !== "all" && loc.category !== filter) return;
        if (!matchesSmartSearch(loc, searchText)) return;

        const m = L.marker([loc.lat, loc.lng], { 
            icon: createCustomIcon(loc.icon, loc.color) 
        }).addTo(map);

        m.on('click', () => {
            map.flyTo([loc.lat, loc.lng], 16);
            showPreview(loc);
        });
        markers.push(m);
    });
}

async function requestLocationApi(options = {}) {
    const response = await fetch("api/locations.php", {
        headers: { "Content-Type": "application/json" },
        ...options
    });
    const payload = await response.json();

    if (!response.ok || !payload.success) {
        throw new Error(payload.message || "Request lokasi gagal.");
    }

    return payload;
}

async function loadLocationsFromDatabase() {
    try {
        const payload = await requestLocationApi();
        if (Array.isArray(payload.data) && payload.data.length) {
            locations = payload.data;
            window.webandooMapLocations = locations;
            renderMarkers(activeFilter, document.getElementById("searchInput")?.value.toLowerCase() || "");
        }
    } catch (error) {
        console.warn(error.message);
    }
}

let adminMode = false;

function handleFileSelect(file, targetInputId) {
    if (file) {
        document.getElementById(targetInputId).value = file.name;
        alert("File '" + file.name + "' berhasil terbaca. Pastikan file ada di folder project kamu.");
    }
}

function setupDragDrop(areaId, textInputId) {
    const dropArea = document.getElementById(areaId);
    
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, e => e.preventDefault(), false);
    });

    dropArea.addEventListener('dragover', () => dropArea.classList.add('active'), false);
    dropArea.addEventListener('dragleave', () => dropArea.classList.remove('active'), false);

    dropArea.addEventListener('drop', (e) => {
        dropArea.classList.remove('active');
        const file = e.dataTransfer.files[0];
        handleFileSelect(file, textInputId);
    }, false);

    dropArea.addEventListener('click', () => {
        const fileInput = document.createElement('input');
        fileInput.type = 'file';
        fileInput.accept = 'image/*';
        fileInput.onchange = e => handleFileSelect(e.target.files[0], textInputId);
        fileInput.click();
    });
}

function toggleAdminMode() {
    if (!window.webandooIsAdmin) {
        alert("Mode admin hanya tersedia untuk akun dengan role admin.");
        return;
    }

    adminMode = !adminMode;
    
    if (adminMode) {
        alert("MODE ADMIN AKTIF: Silakan klik pada peta untuk menambah titik lokasi baru.");
        map.getContainer().style.cursor = "crosshair";
        document.getElementById("adminTrigger").style.color = "#e74c3c";
    } else {
        alert("Mode Admin Nonaktif.");
        map.getContainer().style.cursor = "";
        document.getElementById("adminTrigger").style.color = "";
    }
}

function closeAdminModal() {
    document.getElementById("adminModal").style.display = "none";
    document.getElementById("adminLocationForm").reset();
}

function toggleFormFields() {
    const cat = document.getElementById("newCategory").value;
    const fieldBisnis = document.getElementById("fieldBisnis");
    const fieldSejarah = document.getElementById("fieldSejarah");

    if (cat === 'kafe' || cat === 'kuliner') {
        fieldBisnis.style.display = 'block';
        fieldSejarah.style.display = 'none';
    } else if (cat === 'publik' || cat === 'sejarah') {
        fieldBisnis.style.display = 'none';
        fieldSejarah.style.display = 'block';
    }
}

function setupDragAndDrop(areaId, inputId) {
    const area = document.getElementById(areaId);
    const input = document.getElementById(inputId);

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eName => {
        area.addEventListener(eName, e => e.preventDefault(), false);
    });

    area.addEventListener('dragover', () => area.classList.add('active'));
    area.addEventListener('dragleave', () => area.classList.remove('active'));

    area.addEventListener('drop', (e) => {
        area.classList.remove('active');
        const file = e.dataTransfer.files[0];
        if (file) input.value = file.name;
    });

    area.addEventListener('click', () => {
        const fileInput = document.createElement('input');
        fileInput.type = 'file';
        fileInput.onchange = e => { input.value = e.target.files[0].name; };
        fileInput.click();
    });
}

map.on('click', function(e) {
    if (!adminMode) return;
    
    document.getElementById("newLat").value = e.latlng.lat;
    document.getElementById("newLng").value = e.latlng.lng;
    document.getElementById("adminModal").style.display = "flex";

    setupDragAndDrop("dropAreaPlace", "newImage");
    setupDragAndDrop("dropAreaMenu", "newMenuImage");
});

document.getElementById("adminLocationForm").addEventListener("submit", async function(e) {
    e.preventDefault();
    
    const cat = document.getElementById("newCategory").value;
    const name = document.getElementById("newName").value;

    let data = {
        name: name,
        category: cat,
        address: document.getElementById("newAddress").value,
        lat: parseFloat(document.getElementById("newLat").value),
        lng: parseFloat(document.getElementById("newLng").value),
        image: document.getElementById("newImage").value || "default.jpg",
        desc: document.getElementById("newDesc").value
    };

    if (cat === 'kafe' || cat === 'kuliner') {
        data.rating = document.getElementById("newRating").value || "0";
        data.popular = document.getElementById("newPopular").value || "-";
        data.menuImage = document.getElementById("newMenuImage").value || "default_menu.jpg";
        data.icon = (cat === 'kafe') ? "fa-coffee" : "fa-utensils";
        data.color = (cat === 'kafe') ? "#3498db" : "#e67e22";
    } else {
        data.details = document.getElementById("newDetails").value || "-";
        data.icon = (cat === 'sejarah') ? "fa-landmark" : "fa-tree";
        data.color = (cat === 'sejarah') ? "#1abc9c" : "#2ecc71";
    }

    try {
        const payload = await requestLocationApi({
            method: "POST",
            body: JSON.stringify(data)
        });
        data.id = payload.id;
    } catch (error) {
        alert(error.message);
        return;
    }

    locations.push(data);
    window.webandooMapLocations = locations;
    renderMarkers(activeFilter, document.getElementById("searchInput")?.value.toLowerCase() || "");
    closeAdminModal();
    alert("âœ… Berhasil menambahkan lokasi: " + name);
});

function openMenuModal(imgSrc) {
    if (!imgSrc) {
        alert("Menu untuk lokasi ini belum tersedia.");
        return;
    }

    const modal = document.getElementById("menuModal");
    const modalImg = document.getElementById("imgMenuFull");
    modalImg.src = imgSrc;
    modal.style.display = "flex";
}

function closeMenuModal() {
    document.getElementById("menuModal").style.display = "none";
}

function drawRoute(destLat, destLng) {
    const target = locations.find(loc => loc.name === name);
    if (target){
      drawRoute(target.lat, target.lng);

      document.getElementById('destLoc').value = target.name;
    }

    if (routingControl !== null) {
        map.removeControl(routingControl);
        routingControl = null;
    }

    routingControl = L.Routing.control({
waypoints: [
    userLatLng ? userLatLng : L.latLng(-6.9147, 107.6098), 
    L.latLng(destLat, destLng)
],
        lineOptions: {
            styles: [{ color: '#4a8645', weight: 6, opacity: 0.8 }]
        },
        createMarker: function() { return null; } 
    }).addTo(map);

    document.getElementById('btnHapusRute').style.display = 'inline-block';
}

function setAsDestination(name) {
    const loc = locations.find(l => l.name === name);
    if (!loc) {
        console.error("Lokasi tidak ditemukan di database!");
        return;
    }

    if (routingControl !== null) {
        map.removeControl(routingControl);
        routingControl = null;
    }

    routingControl = L.Routing.control({
        waypoints: [
            userLatLng ? userLatLng : L.latLng(-6.9147, 107.6098), 
            L.latLng(loc.lat, loc.lng) 
        ],
        lineOptions: {
            styles: [{ color: '#4a8645', weight: 6, opacity: 0.8 }] 
        },
        router: L.Routing.osrmv1({
            serviceUrl: `https://router.project-osrm.org/route/v1`
        }),
        show: true, 
        addWaypoints: false,
        createMarker: function() { return null; } 
    }).addTo(map);

    const btnHapus = document.getElementById("btnHapusRute");
    if (btnHapus) {
        btnHapus.style.display = 'inline-block';
    }

    const destInput = document.getElementById('destLoc');
    if(destInput) destInput.value = name;

    document.getElementById("locationOverlay").style.display = 'none';
    document.getElementById('map').scrollIntoView({ behavior: 'smooth' });
}

function clearRoute() {
    if (routingControl !== null) {
        map.removeControl(routingControl);
        routingControl = null;
    }

    map.eachLayer(function (layer) {
        if (layer._container && layer._container.classList.contains('leaflet-routing-container') || 
            layer.options && layer.options.waypoints) {
            map.removeLayer(layer);
        }
    });

    const routingPanel = document.querySelector('.leaflet-routing-container');
    if (routingPanel) {
        routingPanel.remove();
    }

    const btnHapus = document.getElementById("btnHapusRute");
    if (btnHapus) {
        btnHapus.style.display = 'none';
    }

    map.flyTo([-6.9147, 107.6098], 14);
    
    console.log("Rute berhasil dibersihkan!");
}

    function showCard(loc) {
      const card = document.getElementById("locationCard");
      card.innerHTML = `
        <img src="https://via.placeholder.com/300x150" style="width:100%">
        <div style="padding:15px">
          <h3 style="margin-bottom:5px">${loc.name}</h3>
          <p style="font-size:13px; color:#666">${loc.desc}</p>
          <button onclick="this.parentElement.parentElement.style.display='none'" style="margin-top:10px; cursor:pointer">Tutup</button>
        </div>
      `;
      card.style.display = "block";
    }

    const modal = document.getElementById("locationOverlay");
    document.getElementById("btnBukaModalRute").onclick = () => modal.style.display = "flex";
    document.getElementById("closeModalBtn").onclick = () => modal.style.display = "none";

    document.querySelectorAll(".cat-btn").forEach(btn => {
      btn.addEventListener("click", function() {
        document.querySelectorAll(".cat-btn").forEach(b => b.classList.remove("active"));
        this.classList.add("active");
        activeFilter = this.getAttribute("data-cat");
        renderMarkers(activeFilter, document.getElementById('searchInput').value.toLowerCase());
      });
    });

const locButton = L.control({ position: 'topleft' });

locButton.onAdd = function (map) {
    const div = L.DomUtil.create('div', 'leaflet-bar leaflet-control leaflet-control-custom');
    div.innerHTML = '<a href="#" title="Lokasi Saya" style="display:flex;align-items:center;justify-content:center;"><i class="fas fa-location-arrow"></i></a>';
    div.onclick = function (e) {
        e.preventDefault();
        map.locate({ setView: true, maxZoom: 16 });
    };
    return div;
};
locButton.addTo(map);

map.on('locationfound', function (e) {
    userLatLng = e.latlng;
    L.marker(e.latlng).addTo(map).bindPopup("Kamu di sini!").openPopup();
});

document.getElementById('searchInput').addEventListener('input', function(e) {
    const searchText = e.target.value.toLowerCase();
    renderMarkers(activeFilter, searchText);
});

window.addEventListener("scroll", () => {
    const navbar = document.querySelector(".navbar");
    if (!navbar) return;

    if (window.scrollY > lastScrollY) {
        navbar.classList.add("navbar--hidden");
    } 
    
    if (window.scrollY <= 10) { 
        navbar.classList.remove("navbar--hidden");
    }
    lastScrollY = window.scrollY;
});

    function clearRoute() {
    if (routingControl !== null) {
        map.removeControl(routingControl);
        routingControl = null;
    }
}

    renderMarkers();
    loadLocationsFromDatabase();
    setTimeout(() => { map.invalidateSize(); }, 500);
</script>
<script src="assets/js/webandoo-layout.js"></script>
<script src="assets/js/webandoo-app.js"></script>
<script src="assets/js/review.js"></script>
</body>
</html>


