<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard WeBandoo - Warisan Bandung (V2)</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-gesture-handling/dist/leaflet-gesture-handling.min.css">

    <style>
        :root {
            --color-primary-green: #4A8645;
            --color-secondary-brown: #8B4513;
            --color-white: #ffffff;
            --color-off-white: #f0f2f5;
            --color-text-dark: #2c3e50;
            --color-text-grey: #7f8c8d;
            --shadow-elevation: 0 8px 30px rgba(0, 0, 0, 0.08);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        body {
            min-height: 100vh;
            background-color: var(--color-off-white);
            color: var(--color-text-dark);
            display: flex;
            flex-direction: column;
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
            z-index: 100;
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .navbar--hidden {
            transform: translateY(-100%);
        }

        .navbar .logo {
            font-size: 26px;
            font-weight: 700;
            color: var(--color-primary-green);
            text-decoration: none;
        }

        .nav-links {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .nav-links li {
            margin-left: 20px;
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


        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: none;
            z-index: 99;
        }

        .overlay.active {
            display: block;
        }

        .main-content {
            flex-grow: 1;
            margin-top: 15px;
            padding: 2px 40px;
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

        .hero-banner {
            width: 100%;
            height: 500px;
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            margin-bottom: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .hero-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to right, rgba(0, 0, 0, 0.8) 0%, rgba(0, 0, 0, 0.4) 40%, transparent 100%),
                linear-gradient(to top, rgba(0, 0, 0, 0.8) 0%, transparent 50%);
        }

        .hero-content {
            position: absolute;
            bottom: 50px;
            left: 50px;
            color: white;
            max-width: 500px;
            z-index: 5;
            display: flex;
            flex-direction: column;
            transform: none;
        }

        .hero-content .badge {
            background: var(--color-primary-green);
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 11px;
            width: fit-content;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 12px;
        }

        .hero-content h1 {
            font-size: 42px;
            margin: 15px 0;
            line-height: 1.2;
        }

        .hero-content p {
            font-size: 16px;
            margin-bottom: 25px;
            color: #e0e0e0;
        }

        .hero-buttons {
            display: flex;
            gap: 15px;
            margin-top: 20px;
            position: relative;
            z-index: 100 !important;
        }

        .hero-slider {
            width: 100%;
            height: 500px;
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            margin-bottom: 40px;
            background: #000;
        }

        .hero-item {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            visibility: hidden;
            transition: opacity 1s ease-in-out, transform 1.2s ease-in-out;
            transform: scale(1.05);
        }

        .hero-item.active {
            opacity: 1;
            visibility: visible;
            transform: scale(1);
        }

        .slider-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0, 0, 0, 0.3);
            color: white;
            border: none;
            padding: 20px 15px;
            cursor: pointer;
            z-index: 10;
            transition: 0.3s;
            font-size: 20px;
        }

        .slider-btn:hover {
            background: rgba(0, 0, 0, 0.7);
        }

        .prev {
            left: 0;
            border-radius: 0 8px 8px 0;
        }

        .next {
            right: 0;
            border-radius: 8px 0 0 8px;
        }

        .slider-dots {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
            z-index: 10;
        }

        .dot {
            width: 12px;
            height: 12px;
            background: rgba(255, 255, 255, 0.4);
            border-radius: 50%;
            cursor: pointer;
            transition: 0.3s;
        }

        .dot.active {
            background: var(--color-primary-green);
            width: 30px;
            border-radius: 10px;
        }

        .hero-item.active .hero-content {
            animation: fadeInUp 1s ease forwards;
            animation-delay: 0.3s;
            opacity: 0;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .badge-location {
            background-color: var(--color-primary-green);
        }

        .badge-event {
            background-color: #e74c3c;
        }

        .event-date {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(255, 255, 255, 0.95);
            padding: 6px 15px;
            border-radius: 8px;
            width: fit-content;
            margin-bottom: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .event-date .day {
            font-size: 20px;
            font-weight: 700;
            color: var(--color-text-dark);
        }

        .event-date .month {
            font-size: 12px;
            color: var(--color-primary-green);
            font-weight: 600;
            text-transform: uppercase;
        }

        .hero-item.active .badge-event {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(231, 76, 60, 0.7);
            }

            70% {
                transform: scale(1.05);
                box-shadow: 0 0 0 10px rgba(231, 76, 60, 0);
            }

            100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(231, 76, 60, 0);
            }
        }

        .btn-play,
        .btn-list {
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-play {
            background-color: white;
            color: black;
        }

        .btn-play:hover {
            background-color: #e0e0e0;
        }

        .btn-list {
            background-color: rgba(109, 109, 110, 0.7);
            color: white;
        }

        .btn-list:hover {
            background-color: rgba(109, 109, 110, 0.4);
        }

        @media (max-width: 768px) {
            .hero-banner {
                height: 400px;
            }

            .hero-content {
                left: 20px;
                right: 20px;
            }

            .hero-content h1 {
                font-size: 28px;
            }
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        .link-card-wrapper {
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .info-card {
            background-color: var(--color-white);
            padding: 30px;
            border-radius: 15px;
            box-shadow: var(--shadow-elevation);
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease;
            min-height: 180px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .info-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(74, 134, 69, 0.15);
            border-bottom: 3px solid var(--color-primary-green);
        }

        .list-item {
            animation: fadeIn 0.5s ease forwards;
            opacity: 0;
        }

        .dashboard-map-wrapper {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            height: 450px;
            background: white;
            padding: 15px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        .map-widget {
            border-radius: 15px;
            z-index: 1;
        }

        .map-sidebar {
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .sidebar-info {
            margin-bottom: 15px;
        }

        .sidebar-info h5 {
            font-size: 16px;
            margin: 0;
        }

        .sidebar-info p {
            font-size: 12px;
            color: var(--color-text-grey);
        }

        .nearby-list {
            flex: 1;
            overflow-y: auto;
            padding-right: 5px;
        }

        .nearby-card {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px;
            border-radius: 12px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 1px solid #f0f0f0;
        }

        .nearby-card:hover {
            background: #f9f9f9;
            transform: translateY(-2px);
            border-color: var(--color-primary-green);
        }

        .nearby-card img {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            object-fit: cover;
        }

        .nearby-card .details strong {
            display: block;
            font-size: 14px;
        }

        .nearby-card .details span {
            font-size: 11px;
            color: #2ecc71;
            font-weight: 500;
        }

        .view-full-map {
            color: var(--color-primary-green);
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }

        .custom-div-icon {
            background: none;
            border: none;
        }

        .marker-pin {
            width: 30px;
            height: 30px;
            border-radius: 50% 50% 50% 0;
            position: absolute;
            transform: rotate(-45deg);
            left: 50%;
            top: 50%;
            margin: -15px 0 0 -15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .marker-pin i {
            transform: rotate(45deg);
            font-size: 14px;
        }

        @media (max-width: 992px) {
            .dashboard-map-wrapper {
                grid-template-columns: 1fr;
                height: auto;
            }

            .map-widget {
                height: 300px;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .category-container {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            overflow-x: auto;
            padding-bottom: 10px;
        }

        .chip {
            padding: 8px 20px;
            background: var(--color-white);
            border-radius: 50px;
            white-space: nowrap;
            cursor: pointer;
            font-size: 14px;
            box-shadow: var(--shadow-elevation);
            transition: 0.3s;
        }

        .chip:hover,
        .chip.active {
            background: var(--color-primary-green);
            color: white;
        }

        .card-icon {
            background-color: rgba(74, 134, 69, 0.1);
            color: var(--color-primary-green);
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 22px;
            margin-bottom: 15px;
        }

        .card-icon.brown {
            background-color: rgba(139, 69, 19, 0.1);
            color: var(--color-secondary-brown);
        }

        .info-card h4 {
            font-size: 15px;
            color: var(--color-text-grey);
            margin-bottom: 8px;
            font-weight: 500;
        }

        .info-card p.data {
            font-size: 38px;
            font-weight: 700;
            color: var(--color-text-dark);
        }

        .info-card small {
            display: block;
            margin-top: 5px;
            font-weight: 600;
            font-size: 13px;
        }

        .main-section h2 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 25px;
            color: var(--color-text-dark);
        }

        .btn-details {
            text-decoration: none;
            color: var(--color-primary-green);
            font-weight: 600;
            font-size: 14px;
            transition: color 0.3s;
        }

        .btn-details:hover {
            color: var(--color-secondary-brown);
        }

        .horizontal-scroll-wrapper {
            display: flex;
            gap: 20px;
            overflow-x: auto;
            padding-bottom: 15px;
            scrollbar-width: none;
        }

        .horizontal-scroll-wrapper::-webkit-scrollbar {
            display: none;
        }

        .event-card-mini {
            min-width: 260px;
            background: white;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            border: 1px solid #f0f0f0;
            transition: 0.3s;
        }

        .event-card-mini:hover {
            transform: translateY(-5px);
        }

        .card-image {
            position: relative;
            height: 130px;
        }

        .card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .date-tag {
            position: absolute;
            top: 10px;
            left: 10px;
            background: white;
            padding: 5px 10px;
            border-radius: 10px;
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            line-height: 1;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .date-tag span {
            font-size: 10px;
            color: #e74c3c;
        }

        .card-details {
            padding: 15px;
        }

        .card-details h4 {
            margin: 0;
            font-size: 15px;
            color: #333;
        }

        .card-details p {
            margin: 5px 0 0 0;
            font-size: 12px;
            color: #888;
        }

        .learning-card {
            min-width: 300px;
            background: white;
            padding: 20px;
            border-radius: 18px;
            display: flex;
            gap: 15px;
            align-items: center;
            border: 1px solid #f0f0f0;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .learning-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .learning-info h5 {
            margin: 0;
            font-size: 15px;
        }

        .learning-info p {
            margin: 4px 0 10px 0;
            font-size: 12px;
            color: #777;
        }

        .progress-container {
            width: 100%;
            height: 6px;
            background: #eee;
            border-radius: 10px;
            margin-bottom: 4px;
        }

        .progress-bar {
            height: 100%;
            background: #27ae60;
            border-radius: 10px;
        }

        .view-all-btn {
            text-decoration: none;
            color: #27ae60;
            font-size: 13px;
            font-weight: bold;
        }

        footer {
            background-color: var(--color-text-dark);
            color: var(--color-white);
            padding: 50px 40px 20px;
            margin-top: 50px;
            border-radius: 40px 40px 0 0;
        }

        .footer-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            max-width: 1200px;
            margin: 0 auto;

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
            content: '';
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

            .main-content {
                margin-top: 250%;
                padding: 20px;
                padding-top: 20px;
            }

            .navbar {
                flex-direction: column;
                padding: 10px 15px;
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

            .iklan {
                grid-template-columns: 1fr;
                height: auto;
            }

            .search-bar-container {
                max-width: 100%;
                padding: 10px 15px;
            }

            .search-input {
                font-size: 14px;
            }

            .search-btn {
                padding: 8px 12px;
                font-size: 14px;
            }

            footer {
                padding: 40px 20px 20px;
            }
        }
    </style>
</head>

<body>

    <nav class="navbar">
        <a href="home page.html" class="logo">WeBandoo+</a>
        <ul class="nav-links">
            <li><a href="home page.html" class="nav-link active"><i class="fas fa-chart-line"></i> Dashboard</a></li>
            <li><a href="warisan.html" class="nav-link"><i class="fas fa-landmark"></i> Warisan & Cagar Budaya</a></li>
            <li><a href="peta.html" class="nav-link"><i class="fas fa-map-pin"></i> Peta Lokasi</a></li>
            <li><a href="event.html" class="nav-link"><i class="fas fa-calendar-day"></i> Event & Jadwal</a></li>
            <li><a href="eduction.html" class="nav-link"><i class="fas fa-book"></i> Belajar</a></li>
        </ul>
        <div class="navbar-right">
            <div class="avatar">
                <a href="profil.html">
                    <img id="navAvatar" src="Raihan.jpg" alt="Avatar">
                </a>
            </div>
            <a href="pengaturan.html" class="toggle-btn-leave"><i class="fas fa-cog"></i></a>
            <a href="keluar.html" class="toggle-btn-set"><i class="fas fa-sign-out-alt"></i> Keluar</a>
        </div>
    </nav>

    <div class="main-content">
        <div class="header-bar">
            <div>
                <h1>Halo Bintang, Selamat Datang di WeBandoo!</h1>
                <p style="color: var(--color-text-grey); font-size: 14px;">Eksplorasi 124 lokasi bersejarah hari ini.
                </p>
            </div>
            <div id="weather-widget" style="text-align: right;">
                <span id="current-time" style="font-weight: 600; display: block;">12:00 PM</span>
                <span style="font-size: 13px; color: var(--color-primary-green);"><i class="fas fa-cloud-sun"></i>
                    Bandung, 28°C</span>
            </div>
        </div>

        <div class="hero-slider">
            <div class="slider-container">
                <div class="hero-item active">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/4/4c/Gedung_Sate_Oktober_2024_-_Rahmatdenas.jpg/500px-Gedung_Sate_Oktober_2024_-_Rahmatdenas.jpg" alt="Gedung Sate" class="hero-img">
                    <div class="hero-overlay"></div>
                    <div class="hero-content">
                        <span class="badge badge-location"><i class="fas fa-map-marker-alt"></i> Destinasi Ikonik</span>
                        <h1>Gedung Sate</h1>
                        <p>Jelajahi keindahan arsitektur klasik Bandung. Buka setiap hari untuk kunjungan edukasi.</p>
                        <div class="hero-buttons">
                            <a href="warisan.html" class="btn-play">Detail Lokasi</a>
                        </div>
                    </div>
                </div>

                <div class="hero-item">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQpvd79T2xY_UHnnT2T0G3iy3gbmaV328YD8Q&s" alt="Festival Angklung" class="hero-img">
                    <div class="hero-overlay"></div>
                    <div class="hero-content">
                        <span class="badge badge-event"><i class="fas fa-calendar-check"></i> Event Mendatang</span>
                        <div class="event-date" style="margin: 15px 0;">
                            <span class="day">20</span>
                            <span class="month">Februari</span>
                        </div>
                        <h1>Festival Angklung 2026</h1>
                        <p>Akan diadakan di Kopi Mandja Progo pada tanggal 20 Februari 2026.</p>
                        <div class="hero-buttons">
                            <a href="event.html?id=festival-angklung" class="btn-play">
                                <i class="fas fa-paper-plane"></i> Daftar Sekarang
                            </a>

                            <a href="javascript:void(0)" class="btn-list" onclick="addToCalendar()">
                                <i class="fas fa-calendar-plus"></i> Simpan Kalender
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <button class="slider-btn prev" onclick="changeSlide(-1)"><i class="fas fa-chevron-left"></i></button>
            <button class="slider-btn next" onclick="changeSlide(1)"><i class="fas fa-chevron-right"></i></button>

            <div class="slider-dots">
                <span class="dot active" onclick="currentSlide(0)"></span>
                <span class="dot" onclick="currentSlide(1)"></span>
            </div>
        </div>
        <div class="slider-dots">
            <span class="dot active" onclick="currentSlide(0)"></span>
            <span class="dot" onclick="currentSlide(1)"></span>
        </div>
    </div>

    <div class="main-content">
        <div class="dashboard-grid">
            <a href="peta.html" class="link-card-wrapper">
                <div class="info-card">
                    <div class="card-icon"><i class="fas fa-chess-rook"></i></div>
                    <h4>Total Lokasi Wisata</h4>
                    <p class="data">124</p>
                    <small style="color: var(--color-primary-green);">+5 Objek Baru dalam 6 bulan terakhir</small>
                </div>
            </a>

            <a href="warisan.html" class="link-card-wrapper">
                <div class="info-card">
                    <div class="card-icon"><i class="fas fa-star"></i></div>
                    <h4>Rekomendasi Populer</h4>
                    <p class="data" style="font-size: 32px;">Gedung Sate</p>
                    <small style="color: var(--color-primary-green);">Waktu Kunjungan Terbaik: Sore Hari</small>
                </div>
            </a>

            <a href="event.html" class="link-card-wrapper">
                <div class="info-card">
                    <div class="card-icon brown"><i class="fas fa-ticket-alt"></i></div>
                    <h4>Event Warisan Mendatang</h4>
                    <p class="data">7</p>
                    <small style="color: var(--color-secondary-brown);">2 Event skala besar minggu ini</small>
                </div>
            </a>
        </div>
    </div>

    <div class="main-content" style="margin-top: 30px;">
        <div class="section-header"
            style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <h3><i class="fas fa-map-marked-alt"></i> Eksplorasi Sekitarmu</h3>
            <a href="peta.html" class="view-full-map">Lihat Peta Full Screen <i
                    class="fas fa-external-link-alt"></i></a>
        </div>

        <div class="dashboard-map-wrapper">
            <div id="map" class="map-widget"></div>

            <div class="map-sidebar">
                <div class="sidebar-info">
                    <h5>Lokasi Terpopuler</h5>
                    <p>Berdasarkan kunjungan minggu ini</p>
                </div>

                <div class="nearby-list">
                    <div class="nearby-card" onclick="focusToMap(-6.9175, 107.6191)">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/4/4c/Gedung_Sate_Oktober_2024_-_Rahmatdenas.jpg/500px-Gedung_Sate_Oktober_2024_-_Rahmatdenas.jpg" alt="Gedung Sate">
                        <div class="details">
                            <strong>Gedung Sate</strong>
                            <span><i class="fas fa-map-marker-alt"></i> 1.2 km dari Anda</span>
                        </div>
                    </div>

                    <div class="nearby-card" onclick="focusToMap(-6.9211, 107.6110)">
                        <img src="https://www.beritainspiratif.com/assets/uploads/2024/05/1udeh6pptd10e_mid.jpg" alt="Jalan Beraga">
                        <div class="details">
                            <strong>Jalan Braga</strong>
                            <span><i class="fas fa-map-marker-alt"></i> 0.5 km dari Anda</span>
                        </div>
                    </div>

                    <div class="nearby-card" onclick="focusToMap(-6.9211, 107.6110)">
                        <img src="https://www.shutterstock.com/image-photo/bandung-indonesia-18-october-2023-600nw-2381176429.jpg" alt="Jalan Asia Afrika">
                        <div class="details">
                            <strong>Jalan Asia Afrika</strong>
                            <span><i class="fas fa-map-marked-alt"></i> 1 km dari Anda </span>
                        </div>
                    </div>

                    <div class="nearby-card" onclick="focusToMap(-6.9175, 107.6191)">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/4/40/Stadion_Siliwangi_%2826968401584%29.jpg/1200px-Stadion_Siliwangi_%2826968401584%29.jpg" alt="Stadion siliwangi bandung">
                         <div class="details">
                            <strong>Stadion Siliwangi Bandung</strong>
                            <span><i class="fas fa-map-marked-alt"></i> 2 km dari Anda </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="main-content">
        <div class="dashboard-bottom-section" style="margin-top: 40px; padding: 0 20px;">

            <div class="section-wrapper" style="margin-bottom: 40px;">
                <div class="section-header"
                    style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h3 style="font-size: 1.2rem; color: #2d3436;"><i class="fas fa-calendar-day"
                            style="color: #27ae60; margin-right: 10px;"></i>Agenda Budaya Terdekat</h3>
                    <a href="event.html" class="view-all-btn">Lihat Semua</a>
                </div>

                <div class="horizontal-scroll-wrapper">
                    <div class="event-card-mini">
                        <div class="card-image">
                            <img src="https://cdn.inisumedang.com/wp-content/uploads/2022/08/IMG-20220808-WA0049.jpg" alt="Event">
                             <div class="date-tag">24 <br> <span>MAR</span></div>
                        </div>
                        <div class="card-details">
                            <h4>Bandung Lautan Api</h4>
                            <p><i class="fas fa-map-marker-alt"></i> Lapangan Tegallega</p>
                        </div>
                    </div>

                    <div class="event-card-mini">
                        <div class="card-image">
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQNC87MB0Ejb5ObtFyaRhDEjjYn-gedj5uAfg&s" alt="Event">
                            <div class="date-tag">10 <br> <span>FEB</span></div>
                        </div>
                        <div class="card-details">
                            <h4>Workshop Angklung</h4>
                            <p><i class="fas fa-map-marker-alt"></i> Saung Angklung Udjo</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="section-wrapper">
                <div class="section-header"
                    style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h3 style="font-size: 1.2rem; color: #2d3436;"><i class="fas fa-graduation-cap"
                            style="color: #3498db; margin-right: 10px;"></i>Materi Pembelajaran Baru</h3>
                    <a href="eduction.html" class="view-all-btn">Mulai Belajar</a>
                </div>

                <div class="horizontal-scroll-wrapper">
                    <div class="learning-card">
                        <div class="learning-icon" style="background: #e1f5fe;"><i class="fas fa-book-open"
                                style="color: #03a9f4;"></i></div>
                        <div class="learning-info">
                            <h5>Arsitektur Art Deco</h5>
                            <p>Mengenal gaya bangunan tua di Bandung.</p>
                            <div class="progress-container">
                                <div class="progress-bar" style="width: 70%;"></div>
                            </div>
                            <small>70% Selesai</small>
                        </div>
                    </div>

                    <div class="learning-card">
                        <div class="learning-icon" style="background: #fff3e0;"><i class="fas fa-palette"
                                style="color: #ff9800;"></i></div>
                        <div class="learning-info">
                            <h5>Sejarah Wayang Golek</h5>
                            <p>Tokoh dan filosofi di balik layar.</p>
                            <div class="progress-container">
                                <div class="progress-bar" style="width: 20%;"></div>
                            </div>
                            <small>20% Selesai</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="main-content">
        <style>
            /* Efek saat tombol diarahkan kursor (hover) */
            .contribution-banner button:hover {
                transform: scale(1.05);
                background-color: #f0f0f0 !important;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            }

            /* Efek saat tombol ditekan */
            .contribution-banner button:active {
                transform: scale(0.95);
            }

            /* Menyembunyikan input file asli */
            #file-upload {
                display: none;
            }
        </style>

        <div class="contribution-banner"
            style="background: linear-gradient(135deg, #27ae60, #2ecc71); border-radius: 20px; padding: 30px; margin-top: 30px; color: white; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <h2 style="margin: 0;">Bantu Lestarikan Bandung!</h2>
                <p style="opacity: 0.9; margin-top: 5px;">Punya foto jadul atau info gedung bersejarah? Bagikan di sini.
                </p>
            </div>

            <input type="file" id="file-upload" accept="image/*"
                onchange="alert('File terpilih: ' + this.files[0].name)">

            <button onclick="document.getElementById('file-upload').click()"
                style="background: white; color: #27ae60; border: none; padding: 12px 25px; border-radius: 10px; font-weight: bold; cursor: pointer; transition: 0.3s;">
                <i class="fas fa-upload"></i> Unggah Cerita
            </button>
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
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>

                <div class="footer-col">
                    <h4>Navigasi</h4>
                    <ul>
                        <li><a href="home page.html">Dashboard Utama</a></li>
                        <li><a href="warisan.html">Daftar Warisan</a></li>
                        <li><a href="peta.html">Eksplorasi Peta</a></li>
                        <li><a href="event.html">Agenda Budaya</a></li>
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

        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script src="https://unpkg.com/leaflet-gesture-handling"></script>

        <script>
            function adjustMainContentMargin() {
                const navbar = document.querySelector('.navbar');
                const mainContent = document.querySelector('.main-content');

                if (navbar && mainContent) {
                    const navbarHeight = navbar.getBoundingClientRect().height;
                    mainContent.style.paddingTop = (navbarHeight + 10) + 'px';
                }
            }

            window.addEventListener('load', adjustMainContentMargin);
            window.addEventListener('resize', adjustMainContentMargin);

            const locations = [
                {
                    name: "Gedung Sate",
                    category: "sejarah",
                    lat: -6.9025, lng: 107.6188,
                    icon: "fa-landmark", color: "#1abc9c",
                    desc: "Ikon sejarah Jawa Barat."
                },
                {
                    name: "Jalan Braga",
                    category: "sejarah",
                    lat: -6.9211, lng: 107.6110,
                    icon: "fa-camera", color: "#e67e22",
                    desc: "Kawasan ikonik dengan arsitektur klasik."
                }
            ];

            let map;
            let currentSlideIndex = 0;
            let slideInterval;
            let lastScrollY = window.scrollY;

            function createCustomIcon(iconClass, color) {
                return L.divIcon({
                    className: 'custom-div-icon',
                    html: `<div class="marker-pin" style="background:${color}"><i class="fas ${iconClass}"></i></div>`,
                    iconSize: [40, 40],
                    iconAnchor: [20, 40]
                });
            }

            function renderDashboardMarkers(mapObj) {
                locations.forEach(loc => {
                    const m = L.marker([loc.lat, loc.lng], {
                        icon: createCustomIcon(loc.icon, loc.color)
                    }).addTo(mapObj);

                    m.bindPopup(`<b>${loc.name}</b><br>${loc.desc}`);

                    m.on('click', () => {
                        mapObj.flyTo([loc.lat, loc.lng], 16);
                    });
                });
            }

            function focusToMap(lat, lng) {
                if (map) {
                    map.flyTo([lat, lng], 16);
                }
            }

            function updateClock() {
                const now = new Date();
                const options = { hour: '2-digit', minute: '2-digit', second: '2-digit' };
                const timeElement = document.getElementById('current-time');
                if (timeElement) {
                    timeElement.innerText = now.toLocaleTimeString('id-ID', options);
                }
            }

            function showSlide(index) {
                const slides = document.querySelectorAll('.hero-item');
                const dots = document.querySelectorAll('.dot');

                if (slides.length === 0) return;

                if (index >= slides.length) currentSlideIndex = 0;
                else if (index < 0) currentSlideIndex = slides.length - 1;
                else currentSlideIndex = index;

                slides.forEach((slide, i) => {
                    slide.classList.remove('active');
                    if (i === currentSlideIndex) slide.classList.add('active');
                });

                dots.forEach((dot, i) => {
                    dot.classList.remove('active');
                    if (i === currentSlideIndex) dot.classList.add('active');
                });
            }

            function startAutoPlay() {
                slideInterval = setInterval(() => {
                    showSlide(currentSlideIndex + 1);
                }, 5000);
            }

            function resetAutoPlay() {
                clearInterval(slideInterval);
                startAutoPlay();
            }

            function animateNumbers() {
                const counters = document.querySelectorAll('.data');
                counters.forEach(counter => {
                    const target = +counter.getAttribute('data-target');
                    if (!target) return;
                    let current = 0;
                    const increment = Math.ceil(target / 60);

                    const update = () => {
                        current += increment;
                        if (current < target) {
                            counter.innerText = current;
                            requestAnimationFrame(update);
                        } else {
                            counter.innerText = target;
                        }
                    };
                    update();
                });
            }

            document.addEventListener('DOMContentLoaded', () => {
                const mapContainer = document.getElementById('map');
                if (mapContainer) {
                    map = L.map('map').setView([-6.9175, 107.6191], 13);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '© OpenStreetMap'
                    }).addTo(map);

                    renderDashboardMarkers(map);

                    setTimeout(() => { map.invalidateSize(); }, 500);
                }

                showSlide(0);
                startAutoPlay();

                setInterval(updateClock, 1000);
                updateClock();
                animateNumbers();
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
             window.addEventListener('DOMContentLoaded', () => {
    const savedPhoto = localStorage.getItem('userPhoto');
    const navAvatar = document.getElementById('navAvatar');

    if (savedPhoto && navAvatar) {
        navAvatar.src = savedPhoto;
    }
});

            

            function changeSlide(step) {
                showSlide(currentSlideIndex + step);
                resetAutoPlay();
            }

            function currentSlide(index) {
                showSlide(index);
                resetAutoPlay();
            }

            function showImage(src) {
                const viewerImg = document.getElementById("viewerImg");
                const imageViewer = document.getElementById("imageViewer");
                if (viewerImg && imageViewer) {
                    viewerImg.src = src;
                    imageViewer.style.display = "flex";
                }
            }

            function closeImage() {
                const imageViewer = document.getElementById("imageViewer");
                if (imageViewer) imageViewer.style.display = "none";
            }
        </script>
        <div class="main-content">
            <style>
                .contribution-banner button:hover {
                    transform: scale(1.05);
                    background-color: #f0f0f0 !important;
                    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
                }

                .contribution-banner button:active {
                    transform: scale(0.95);
                }

                #file-upload {
                    display: none;
                }
            </style>
        </div>

</html>