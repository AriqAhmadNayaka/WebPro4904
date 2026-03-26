<?php 
require 'db.php';

$name = 'User'; // Default jika gagal

// Cek apakah ada data email dan password yang dikirim dari form login
if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    // Cari user di database
    $stmt = $conn->prepare("SELECT name, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        // Cek kecocokan password
        if (password_verify($password, $user['password'])) {
            $name = $user['name']; // Login berhasil, ambil namanya
        } else {
            // Password salah, kembalikan ke login
            echo "<script>alert('Password salah!'); window.location.href='login.php';</script>";
            exit;
        }
    } else {
        // Email tidak ditemukan
        echo "<script>alert('Email tidak terdaftar!'); window.location.href='login.php';</script>";
        exit;
    }
    $stmt->close();
} else {
    // Jika tidak ada POST data (misal: buka home.php langsung dari URL)
    // Sebaiknya ditendang balik ke login agar aman
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Home | Nuids</title>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
    />
    <link rel="stylesheet" href="home.css" />
    <link rel="stylesheet" href="global.css" />
  </head>
  <body>
    <header>
      <div class="container">
      <div class="profile-section">
        <a href="/src/pages/user/profile.html" class="profile-link">
          <div class="avatar">
            <img
              src="https://images.unsplash.com/photo-1740252117044-2af197eea287"
              alt="Avatar"
              onerror="this.src='data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiBzdHJva2U9IiM3NzciIHN0cm9rZS13aWR0aD0iMiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIj48cGF0aCBkPSJNMjAgMjF2LTJhNCA0IDAgMCAwLTQtNEg4YTQgNCAwIDAgMC00IDR2MiIvPjxjaXJjbGUgY3g9IjEyIiBjeT0iNyIgcj0iNCIvPjwvc3ZnPg=='"
            />
          </div>
          <div class="greeting-text">
            <span class="greeting">
              <img src="logo.png" alt="Logo" width="20" />
              <h4>Hello!</h4>
            </span>
            <h3 class="adminName"><?php echo isset($name) ? $name : 'User'; ?></h3>
          </div>
        </a>
      </div>

      <nav>
        <button class="mobile-menu-toggle" aria-label="Toggle menu">
          <span></span>
          <span></span>
          <span></span>
        </button>
        <ul class="nav-menu">
          <li><a href="/src/pages/user/home.html" class="nav-link">Home</a></li>
          <li><a href="/src/pages/user/features.html" class="nav-link">Features</a></li>
          <li><a href="/src/pages/user/about.html" class="nav-link">About Us</a></li>
          <li><a href="/src/pages/user/contact.html" class="nav-link">Contact Us</a></li>
          <li><a href="/src/pages/user/notification.html" class="nav-link"><i class="fa-regular fa-bell"></i></a></li>

          <li>
            <a id="logoutBtn" class="button-logout" href="#">
              <button class="css-button-logout">LogOut</button>
            </a>
          </li>
        </ul>
      </nav>
    </div>
    </header>

    <section class="menu-section">
      <h2 class="home-label">Pintasan Fitur:</h2>
      <div class="menu-container">
        <a
          href="/src/pages/user/(features)/tracking.html"
          style="
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
          "
        >
          <div class="menu-item">
            <div class="icon-box bg-tracking">
              <img
                class="menu-image"
                src="../../../public/images/tracking.png"
                width="55px"
                alt=""
              />
            </div>
            <span class="menu-label">Cek Gizi</span>
          </div>
        </a>

        <a
          href="/src/pages/user/(features)/doctor.html"
          style="
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
          "
        >
          <div class="menu-item">
            <div class="icon-box bg-dokter">
              <img
                class="menu-image"
                src="../../../public/images/doctor.png"
                alt=""
                style="filter: brightness(0%)"
                width="65px"
              />
            </div>
            <span class="menu-label">Dokter Anak</span>
          </div>
        </a>

        <a
          href="/src/pages/user/(features)/hospital.html"
          style="
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
          "
        >
          <div class="menu-item">
            <div class="icon-box bg-rumahsakit">
              <img
                class="menu-image"
                src="../../../public/images/hospital.png"
                alt=""
                style="filter: brightness(0%)"
                width="55px"
              />
            </div>
            <span class="menu-label">Rumah Sakit</span>
          </div>
        </a>

        <a
          href="/src/pages/user/(features)/game.html"
          style="
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
          "
        >
          <div class="menu-item">
            <div class="icon-box bg-game">
              <img
                class="menu-image"
                src="../../../public/images/games.png"
                alt=""
                style="filter: brightness(0%)"
                width="50px"
              />
            </div>
            <span class="menu-label">Game</span>
          </div>
        </a>

        <a
          href="/src/pages/user/(features)/chatbot.html"
          style="
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
          "
        >
          <div class="menu-item">
            <div class="icon-box bg-history">
              <i class="fas fa-robot" style="filter: brightness(0%)"></i>
            </div>
            <span class="menu-label">AI Chatbot</span>
          </div>
        </a>

        <div class="menu-item">
          <a
            href="features.html"
            style="
              text-decoration: none;
              display: flex;
              flex-direction: column;
              align-items: center;
            "
          >
            <div class="icon-box bg-lainnya">
              <img
                class="menu-image"
                src="../../../public/images/more.png"
                alt=""
                style="filter: brightness(0%)"
                width="43px"
              />
            </div>
            <span class="menu-label">Lainnya</span>
          </a>
        </div>
      </div>

      <section class="banner-section">
        <div class="banner-container">
          <div
            id="carousel-1"
            class="carousel"
            role="region"
            aria-labelledby="carousel-1-title"
          >
            <div
              class="slide"
              role="tabpanel"
              aria-labelledby="carousel-1-slide-1-title"
            >
              <div class="slide-content">
                <div class="slide-caption">
                  <h3 id="carousel-1-slide-1-title">Tips MPASI</h3>
                  <p>10 Tips Memulai MPASI yang Benar untuk Bayi 6 Bulan</p>
                </div>

                <img
                  src="https://images.unsplash.com/photo-1515488042361-ee00e0ddd4e4?w=800"
                  alt="Banner Photo 1"
                  width="800"
                  height="350"
                />
              </div>
            </div>

            <div
              class="slide"
              role="tabpanel"
              aria-labelledby="carousel-1-slide-2-title"
            >
              <div class="slide-content">
                <div class="slide-caption">
                  <h3 id="carousel-1-slide-2-title">Pencegahan Stunting</h3>
                  <p>Cara Mencegah dan Mengatasi Stunting pada Anak</p>
                </div>
                <img
                  src="https://images.unsplash.com/photo-1503454537195-1dcabb73ffb9?w=800"
                  alt="Banner Photo 2"
                  width="800"
                  height="350"
                />
              </div>
            </div>

            <div
              class="slide"
              role="tabpanel"
              aria-labelledby="carousel-1-slide-3-title"
            >
              <div class="slide-content">
                <div class="slide-caption">
                  <h3 id="carousel-1-slide-3-title">Imunisasi</h3>
                  <p>Pentingnya Imunisasi Lengkap untuk Anak</p>
                </div>

                <img
                  src="https://images.unsplash.com/photo-1631217868264-e5b90bb7e133?w=800"
                  alt="Banner Photo 3"
                  width="800"
                  height="350"
                  style="object-view-box: inset(0 0 0)"
                />
              </div>
            </div>
          </div>
        </div>
      </section>
    </section>

    <!-- Recipe Section -->
    <section class="recipe-section">
      <div class="section-header">
        <div class="header-content">
          <h2>Resep Makanan Bergizi</h2>
          <p>Temukan resep sehat dan bergizi untuk buah hati Anda</p>
        </div>
      </div>

      <!-- Loading State -->
      <div id="recipeLoading" class="recipe-loading" style="display: none">
        <div class="spinner-small"></div>
        <p>Memuat resep...</p>
      </div>

      <!-- Recipe Cards Grid -->
      <div class="recipe-cards-grid" id="homeRecipeCards">
        <!-- Cards will be inserted here by JavaScript -->
      </div>

      <a href="/src/pages/user/(features)/recipes.html" class="btn-view-all">
        Lihat Semua Resep
        <i class="fas fa-arrow-right"></i>
      </a>
    </section>

    <script src="main.js"></script>
    <script src="Carousel.js"></script>
    <script src="home.js"></script>
    <script src="home-recipes.js"></script>

    <script
      src="https://kit.fontawesome.com/7084b100e8.js"
      crossorigin="anonymous"
    ></script>
  </body>
</html>
