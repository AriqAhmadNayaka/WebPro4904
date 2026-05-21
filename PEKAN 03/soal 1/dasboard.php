<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard WeBandoo+</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<style>
:root {
    --green: #4A8645;
    --brown: #8B4513;
    --dark: #2c3e50;
    --light: #f0f2f5;
    --white: #fff;
    --shadow: 0 15px 40px rgba(0,0,0,.12);
}

* { box-sizing: border-box; margin: 0; padding: 0; font-family: Poppins; }

body {
    background: var(--light);
    color: var(--dark);
}

/* ================= NAVBAR (ASLI) ================= */
.navbar {
    position: fixed;
    top: 0;
    width: 100%;
    background: var(--dark);
    padding: 20px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    z-index: 100;
}
.logo { color: var(--green); font-size: 26px; font-weight: 700; text-decoration: none; }
.nav-links { display: flex; list-style: none; }
.nav-links li { margin-left: 20px; }
.nav-link {
    color: #ccc;
    text-decoration: none;
    padding: 10px 15px;
    border-radius: 8px;
}
.nav-link.active, .nav-link:hover {
    background: var(--green);
    color: white;
}
.navbar-right { display: flex; align-items: center; }
.avatar { width: 55px; height: 55px; border-radius: 50%; overflow: hidden; border: 2px solid var(--green); margin-left: 10px; }
.avatar img { width: 100%; height: 100%; object-fit: cover; }
.toggle-btn-leave,.toggle-btn-set {
    background: var(--green);
    color: white;
    padding: 10px;
    border-radius: 8px;
    margin-left: 10px;
    text-decoration: none;
}
/* ================================================= */

.main {
    margin-top: 120px;
    padding: 40px;
}

/* HERO */
.hero {
    background:
      linear-gradient(135deg, rgba(74,134,69,.9), rgba(44,62,80,.9)),
      url("https://images.unsplash.com/photo-1581089781785-603411fa81e5");
    background-size: cover;
    color: white;
    padding: 70px;
    border-radius: 24px;
    box-shadow: var(--shadow);
    margin-bottom: 40px;
}
.hero h1 { font-size: 38px; }
.hero p { opacity: .9; margin-top: 10px; }

/* QUICK ACTION */
.actions {
    display: grid;
    grid-template-columns: repeat(auto-fit,minmax(200px,1fr));
    gap: 20px;
    margin-bottom: 40px;
}
.action {
    background: white;
    padding: 25px;
    border-radius: 18px;
    box-shadow: var(--shadow);
    text-align: center;
    transition: .3s;
}
.action i {
    font-size: 32px;
    color: var(--green);
    margin-bottom: 10px;
}
.action:hover {
    transform: translateY(-8px);
    box-shadow: 0 25px 60px rgba(74,134,69,.3);
}

/* STATS */
.stats {
    display: grid;
    grid-template-columns: repeat(auto-fit,minmax(220px,1fr));
    gap: 25px;
    margin-bottom: 50px;
}
.stat {
    background: white;
    padding: 30px;
    border-radius: 20px;
    box-shadow: var(--shadow);
    position: relative;
}
.stat i {
    font-size: 26px;
    color: var(--green);
}
.stat h2 { font-size: 42px; margin: 10px 0; }
.bar {
    height: 6px;
    background: #eee;
    border-radius: 10px;
    overflow: hidden;
}
.bar span {
    display: block;
    height: 100%;
    width: 0;
    background: var(--green);
}

/* TIMELINE */
.timeline {
    background: white;
    padding: 30px;
    border-radius: 20px;
    box-shadow: var(--shadow);
}
.event {
    display: flex;
    gap: 15px;
    margin-bottom: 20px;
}
.event i {
    font-size: 22px;
    color: var(--brown);
}
.event:last-child { margin-bottom: 0; }
</style>
</head>

<body>

<nav class="navbar">
    <a href="#" class="logo">WeBandoo+</a>
    <ul class="nav-links">
        <li><a class="nav-link active">Dashboard</a></li>
        <li><a class="nav-link">Warisan</a></li>
        <li><a class="nav-link">Peta</a></li>
        <li><a class="nav-link">Event</a></li>
        <li><a class="nav-link">Belajar</a></li>
    </ul>
    <div class="navbar-right">
        <div class="avatar"><img src="https://i.pravatar.cc/100"></div>
        <a class="toggle-btn-leave"><i class="fas fa-cog"></i></a>
        <a class="toggle-btn-set"><i class="fas fa-sign-out-alt"></i></a>
    </div>
</nav>

<div class="main">

<section class="hero">
    <h1>Warisan Bandung dalam Genggaman</h1>
    <p>Platform interaktif pelestarian budaya & sejarah kota Bandung</p>
</section>

<section class="actions">
    <div class="action"><i class="fas fa-map"></i><p>Jelajahi Peta</p></div>
    <div class="action"><i class="fas fa-calendar"></i><p>Event Hari Ini</p></div>
    <div class="action"><i class="fas fa-book"></i><p>Belajar Sejarah</p></div>
    <div class="action"><i class="fas fa-camera"></i><p>Laporkan Situs</p></div>
</section>

<section class="stats">
    <div class="stat">
        <i class="fas fa-landmark"></i>
        <h2 data="124">0</h2>
        <div class="bar"><span data-bar="80"></span></div>
        <p>Situs Bersejarah</p>
    </div>
    <div class="stat">
        <i class="fas fa-music"></i>
        <h2 data="38">0</h2>
        <div class="bar"><span data-bar="60"></span></div>
        <p>Event Budaya</p>
    </div>
    <div class="stat">
        <i class="fas fa-graduation-cap"></i>
        <h2 data="92">0</h2>
        <div class="bar"><span data-bar="90"></span></div>
        <p>Konten Edukasi</p>
    </div>
</section>

<section class="timeline">
    <div class="event"><i class="fas fa-clock"></i><div><b>Gedung Sate</b><br>Update informasi sejarah</div></div>
    <div class="event"><i class="fas fa-users"></i><div><b>Festival Angklung</b><br>Pendaftaran dibuka</div></div>
    <div class="event"><i class="fas fa-map-marked-alt"></i><div><b>Peta Heritage</b><br>5 lokasi baru ditambahkan</div></div>
</section>

</div>

<script>
document.querySelectorAll(".stat h2").forEach(el=>{
    let target = el.getAttribute("data");
    let n = 0;
    let i = setInterval(()=>{
        n++;
        el.innerText = n;
        if(n==target) clearInterval(i);
    },20);
});
document.querySelectorAll(".bar span").forEach(bar=>{
    bar.style.width = bar.dataset.bar+"%";
});
</script>

</body>
</html>
