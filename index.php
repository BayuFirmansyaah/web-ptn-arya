<?php
require __DIR__.'/lib/auth.php'; 
if (is_admin()) { header('Location: dashboard.php'); exit; }

// jika tidak ada data json di ./data * maka copykan dari file init /*.json
$dataDir = __DIR__.'/data';
if (!is_dir($dataDir)) { mkdir($dataDir, 0755, true); }
foreach (['peserta','sertifikat','berkas', 'admins'] as $f) {
  $fPath = "$dataDir/{$f}.json";
  if (!file_exists($fPath)) {
    copy(__DIR__."/init/{$f}.json", $fPath);
  }
}

?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>PTN MAI - Portal Resmi</title>
  <link rel="website icon" href="assets/images/logodvtgpt.png" type="png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
    rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />

  <style>
    :root {
      /* Palette & brand */
      --primary: #0879CF;
      /* biru utama (solid) */
      --primary-dark: #35537A;
      /* biru tua (hover/kontras) */
      --secondary: #6c757d;
      --accent: #ffc107;
      --dark: #212529;
      --light: #f8f9fa;
      --overlay: rgba(0, 0, 0, .5);
      --gradient: linear-gradient(90deg, #35537A, #0879CF, #00BEED, #343BBF);

      /* Motion & UI */
      --fade-duration: 2000ms;
      --text-fade: 900ms;
      --slide-interval: 4000ms;
      --shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
      --border-radius: 12px;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    html {
      scroll-behavior: smooth;
    }

    body {
      font-family: 'Poppins', sans-serif;
      line-height: 1.6;
      color: var(--dark);
      overflow-x: hidden;
    }

    /* ===== Background System ===== */
    .bg-stage {
      position: fixed;
      inset: 0;
      z-index: -2;
      overflow: hidden;
    }

    .bg-layer {
      position: absolute;
      inset: 0;
      background-position: center;
      background-size: cover;
      background-repeat: no-repeat;
      opacity: 0;
      transition: opacity var(--fade-duration) ease, transform var(--fade-duration) ease;
      transform: scale(1.1);
      will-change: opacity, transform;
    }

    .bg-layer.active {
      opacity: 1;
      transform: scale(1);
    }

    .bg-overlay {
      position: fixed;
      inset: 0;
      background: var(--overlay);
      z-index: -1;
    }

    /* ===== Navigation ===== */
    .navbar-custom {
      background: transparent;
      /* transparan full */
      backdrop-filter: none;
      /* hilangin blur */
      box-shadow: none;
      /* nggak ada shadow */
      transition: all .3s ease;
    }



    .navbar-brand {
      font-weight: 700;
      font-size: 1.5rem;
      color: #fff !important;
      /* biar teks default putih */
    }

    .navbar-brand .brand-text {
      background: var(--gradient);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .nav-link {
      font-weight: 500;
      color: #fff !important;
      transition: color .3s ease;
    }

    .nav-link:hover {
      color: #00BEED !important;
    }

    /* ===== Hero ===== */
    .hero {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      padding: 120px 20px 80px;
      color: #fff;
    }

    .hero-content {
      max-width: 900px;
      margin: auto;
    }

    .hero-title {
      font-size: clamp(2.5rem, 5vw, 4rem);
      font-weight: 700;
      margin-bottom: 1.5rem;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, .5);
      transition: opacity var(--text-fade) ease, transform var(--text-fade) ease;
    }

    .hero-subtitle {
      font-size: clamp(1.1rem, 2.5vw, 1.4rem);
      margin-bottom: 2.5rem;
      opacity: .95;
      font-weight: 300;
      text-shadow: 1px 1px 2px rgba(0, 0, 0, .5);
      transition: opacity var(--text-fade) ease, transform var(--text-fade) ease;
    }

    .text-animation-out {
      opacity: 0;
      transform: translateY(20px);
    }

    .text-animation-in {
      opacity: 1;
      transform: translateY(0);
    }

    .btn-hero {
      padding: 14px 28px;
      margin: 0 8px 16px;
      border-radius: var(--border-radius);
      font-weight: 600;
      font-size: 1.1rem;
      text-decoration: none;
      display: inline-block;
      transition: all .3s ease;
      min-width: 180px;
    }

    .btn-primary-custom {
      background: var(--gradient);
      color: #fff;
      border: none;
      box-shadow: 0 4px 15px rgba(8, 121, 207, .35);
    }

    .btn-primary-custom:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(8, 121, 207, .55);
    }

    .btn-outline-custom {
      background: transparent;
      color: #fff;
      border: 2px solid #fff;
    }

    .btn-outline-custom:hover {
      background: #fff;
      color: var(--primary);
      transform: translateY(-2px);
    }

    /* ===== Features ===== */
    .features {
      padding: 100px 0;
      background: var(--light);
    }

    .section-title {
      text-align: center;
      margin-bottom: 4rem;
    }

    .section-title h2 {
      font-size: clamp(2rem, 4vw, 3rem);
      font-weight: 700;
      color: var(--dark);
      margin-bottom: 1rem;
    }

    .section-title p {
      font-size: 1.2rem;
      color: var(--secondary);
      max-width: 600px;
      margin: 0 auto;
    }

    .feature-card {
      background: #fff;
      border-radius: var(--border-radius);
      padding: 2.5rem 2rem;
      text-align: center;
      box-shadow: var(--shadow);
      transition: transform .3s ease, box-shadow .3s ease;
      height: 100%;
      border: none;
    }

    .feature-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 20px 50px rgba(0, 0, 0, .15);
    }

    .feature-icon {
      width: 80px;
      height: 80px;
      margin: 0 auto 1.5rem;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      background: var(--gradient);
      color: #fff;
      font-size: 2rem;
    }

    .feature-card h4 {
      font-weight: 600;
      margin-bottom: 1rem;
      color: var(--dark);
    }

    .feature-card p {
      color: var(--secondary);
      line-height: 1.7;
    }

    /* ===== Stats ===== */
    .stats {
      padding: 80px 0;
      background: var(--gradient);
      color: #fff;
    }

    .stat-item {
      text-align: center;
      margin-bottom: 2rem;
    }

    .stat-number {
      font-size: 3rem;
      font-weight: 700;
      display: block;
      margin-bottom: .5rem;
    }

    .stat-label {
      font-size: 1.1rem;
      opacity: .9;
      font-weight: 300;
    }

    /* ===== Team ===== */
    .team {
      padding: 100px 0;
      background: #fff;
    }

    .team-card {
      background: #fff;
      border-radius: var(--border-radius);
      padding: 2rem;
      text-align: center;
      box-shadow: var(--shadow);
      transition: transform .3s ease;
      border: none;
      height: 100%;
    }

    .team-card:hover {
      transform: translateY(-5px);
    }

    .team-image {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      object-fit: cover;
      margin: 0 auto 1.5rem;
      border: 4px solid #0879CF;
      box-shadow: 0 5px 20px rgba(8, 121, 207, .30);
    }

    .team-name {
      font-weight: 600;
      font-size: 1.2rem;
      margin-bottom: .5rem;
      color: var(--dark);
    }

    .team-role {
      color: var(--secondary);
      margin-bottom: 1rem;
    }

    /* ===== Footer ===== */
    .footer {
      background: var(--dark);
      color: #fff;
      padding: 60px 0 30px;
    }

    .footer-section {
      margin-bottom: 2rem;
    }

    .footer-title {
      font-weight: 600;
      font-size: 1.2rem;
      margin-bottom: 1.5rem;
      color: #00BEED;
      /* aksen dari gradien */
    }

    .footer-links {
      list-style: none;
      padding: 0;
    }

    .footer-links li {
      margin-bottom: .8rem;
    }

    .footer-links a {
      color: rgba(255, 255, 255, .8);
      text-decoration: none;
      transition: color .3s ease;
    }

    .footer-links a:hover {
      color: #00BEED;
    }

    .footer-bottom {
      border-top: 1px solid rgba(255, 255, 255, .1);
      padding-top: 2rem;
      text-align: center;
      color: rgba(255, 255, 255, .7);
    }

    .support-logos {
      display: flex;
      justify-content: center;
      gap: 1rem;
      margin: 2rem 0;
      flex-wrap: wrap;
    }

    .support-logo {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid rgba(255, 255, 255, .2);
      transition: transform .3s ease, border-color .3s ease;
    }

    .support-logo:hover {
      transform: scale(1.1);
      border-color: #00BEED;
    }

    /* footer team grid */
    .footer-team {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: .5rem 2rem;
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .footer-team li {
      display: flex;
      align-items: center;
      gap: .5rem;
      margin-bottom: .3rem;
    }

    .footer-team a {
      color: rgba(255, 255, 255, .8);
      text-decoration: none;
      transition: color .3s ease;
    }

    .footer-team a:hover {
      color: #00BEED;
    }

    /* ===== Scroll to Top ===== */
    .scroll-top {
      position: fixed;
      bottom: 20px;
      right: 20px;
      background: var(--gradient);
      color: #fff;
      width: 50px;
      height: 50px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      opacity: 0;
      transform: translateY(100px);
      transition: all .3s ease;
      z-index: 1000;
      box-shadow: var(--shadow);
    }

    .scroll-top.visible {
      opacity: 1;
      transform: translateY(0);
    }

    .scroll-top:hover {
      background: linear-gradient(90deg, #35537A, #076bbb, #00A9D1, #2C33A8);
      transform: translateY(-3px);
    }

    /* ===== Animations on Scroll ===== */
    .animate-on-scroll {
      opacity: 0;
      transform: translateY(30px);
      transition: all .6s ease;
    }

    .animate-on-scroll.animated {
      opacity: 1;
      transform: translateY(0);
    }

    /* ===== Reduced Motion ===== */
    <blade media|(prefers-reduced-motion%3A%20reduce)%7B%0D>.bg-layer,
    .hero-title,
    .hero-subtitle,
    .animate-on-scroll {
      transition: none !important;
    }

    /* ===== Responsive ===== */
    <blade media|(max-width%3A%20768px)%7B%0D>.hero {
      padding: 100px 20px 60px;
    }

    .btn-hero {
      display: block;
      margin: 10px auto;
      width: 100%;
      max-width: 280px;
    }

    .features,
    .team {
      padding: 60px 0;
    }

    .stats {
      padding: 60px 0;
    }


    <blade media|(max-width%3A%20576px)%7B%0D>.footer-team {
      grid-template-columns: 1fr;
    }


    /* ===== Devthore Link (tetap stylish) ===== */
    .devthore-link {
      position: relative;
      font-size: 17px;
      font-weight: 800;
      text-decoration: none;
      background: linear-gradient(90deg, #35537A, #0879CF, #00BEED, #343BBF);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      color: transparent;
      transition: opacity .3s ease;
    }

    .devthore-link:hover {
      opacity: .8;
    }

    .devthore-link::after {
      content: "";
      position: absolute;
      left: 0;
      bottom: -3px;
      width: 0%;
      height: 2px;
      background: linear-gradient(90deg, #B7FF00, #CFA408, #C2ED00, #99FF00);
      transition: width .4s ease;
    }

    .devthore-link:hover::after {
      width: 100%;
    }
  </style>
</head>

<body>
  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">
    <div class="container">
      <!-- Logo brand -->
      <a class="navbar-brand d-flex align-items-center" href="#home">
        <img src="assets/images/logodvtgpt.png" alt="PTN MAI Logo" class="me-2"
          style="height:40px; width:auto; object-fit:contain;">
        <span class="fw-bold brand-text">PTN MAI</span>
      </a>

      <!-- Toggler -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Menu -->
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="#home">Beranda</a></li>
          <li class="nav-item"><a class="nav-link" href="#features">Layanan</a></li>
          <li class="nav-item"><a class="nav-link" href="#team">Tim</a></li>
          <li class="nav-item"><a class="nav-link" href="#contact">Kontak</a></li>
        </ul>
      </div>
    </div>
  </nav>


  <!-- Background System -->
  <div class="bg-stage" aria-hidden="true">
    <div class="bg-layer" id="bgLayerA"></div>
    <div class="bg-layer" id="bgLayerB"></div>
  </div>
  <div class="bg-overlay" aria-hidden="true"></div>

  <!-- Hero Section -->
  <section id="home" class="hero">
    <div class="container">
      <div class="hero-content">
        <h1 id="heroTitle" class="hero-title text-animation-in">Selamat Datang di PTN MAI</h1>
        <p id="heroSubtitle" class="hero-subtitle text-animation-in">Portal resmi tim PTN MAI - Membangun masa depan
          pendidikan Indonesia</p>
        <div class="hero-actions">
          <a href="pencarian.php" class="btn-hero btn-primary-custom">
            <i class="fas fa-search me-2"></i>Mulai Pencarian
          </a>
          <a href="login.php" class="btn-hero btn-outline-custom">
            <i class="fas fa-sign-in-alt me-2"></i>Login Admin
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- Informasi Portal -->
  <section id="informasi" class="features">
    <div class="container">
      <div class="section-title animate-on-scroll">
        <h2>Informasi di Portal</h2>
        <p>Direktori & rekap data siswa untuk kebutuhan verifikasi dan pemetaan ke PTN. Ini pusat informasi, bukan
          layanan.</p>
      </div>

      <div class="row g-4">
        <!-- Pencarian & Filter -->
        <div class="col-lg-4 col-md-6">
          <div class="feature-card animate-on-scroll">
            <div class="feature-icon">
              <i class="fas fa-magnifying-glass"></i>
            </div>
            <h4>Pencarian & Filter</h4>
            <p>Temukan data siswa dengan cepat menggunakan kata kunci, filter sekolah/jurusan, dan penyaring lainnya.
            </p>
          </div>
        </div>

        <!-- Rekap & Statistik -->
        <div class="col-lg-4 col-md-6">
          <div class="feature-card animate-on-scroll">
            <div class="feature-icon">
              <i class="fas fa-chart-bar"></i>
            </div>
            <h4>Rekap & Statistik</h4>
            <p>Ringkasan per sekolah/jurusan: jumlah siswa, minat prodi/PTN, dan status pendaftaran/kelulusan*.</p>
          </div>
        </div>

        <!-- Privasi & Kepatuhan -->
        <div class="col-lg-4 col-md-6">
          <div class="feature-card animate-on-scroll">
            <div class="feature-icon">
              <i class="fas fa-user-shield"></i>
            </div>
            <h4>Privasi & Kepatuhan</h4>
            <p>Informasi sensitif disaring sesuai otorisasi. Akses tercatat untuk menjaga keamanan dan akuntabilitas.
            </p>
          </div>
        </div>
      </div>

      <p class="mt-3 text-muted small">* Bergantung pada ketersediaan data.</p>
    </div>
  </section>


  <!-- Stats Section -->
  <section class="stats" aria-label="Statistik Portal PTN">
    <div class="container">
      <div class="row text-center">
        <div class="col-lg-3 col-md-6">
          <div class="stat-item animate-on-scroll">
            <span class="stat-number" data-duration="1800">300+</span>
            <span class="stat-label">Data Siswa Terdaftar</span>
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="stat-item animate-on-scroll">
            <span class="stat-number" data-duration="2000">200+</span>
            <span class="stat-label">Sertifikat Terunggah</span>
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="stat-item animate-on-scroll">
            <span class="stat-number" data-duration="3000">20+</span>
            <span class="stat-label">Kelas Terdata</span>
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="stat-item animate-on-scroll">
            <span class="stat-number" data-duration="3500">10+</span>
            <span class="stat-label">Guru Terverifikasi</span>
          </div>
        </div>
      </div>
    </div>
  </section>


  <!-- Team Section -->
  <section id="team" class="team">
    <div class="container">
      <div class="section-title animate-on-scroll">
        <h2>Tim Kami</h2>
        <p>Berkenalan dengan tim profesional yang siap melayani Anda</p>
      </div>
      <div class="row g-4 justify-content-center">
        <div class="col-lg-4 col-md-6">
          <div class="team-card animate-on-scroll">
            <img src="assets/images/gw.jpg" alt="Aryansyah M" class="team-image" loading="lazy">
            <h5 class="team-name">Aryansyah M</h5>
            <p class="team-role">Web Developer | Tim PTN</p>
            <p>Saya berfokus pada pengembangan web dan sistem informasi, serta terus meningkatkan keterampilan melalui
              pengalaman belajar.</p>
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
          <div class="team-card animate-on-scroll">
            <img src="assets/images/atiqqudin.jpg" alt="M Atiqudin" class="team-image" loading="lazy">
            <h5 class="team-name">M Atiqudin</h5>
            <p class="team-role">Arsipis | Tim PTN</p>
            <p>Saya bertanggung jawab atas pengelolaan arsip serta dokumentasi, memastikan semua data tersusun dengan
              sistematis.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer id="contact" class="footer">
    <div class="container">
      <div class="row">
        <div class="col-lg-4 col-md-6">
          <div class="footer-section">
            <h5 class="footer-title">
              <i class="fas fa-graduation-cap me-2"></i>PTN MAI
            </h5>
            <p class="mb-4">Web resmi Tim PTN Madrasa Alyah Amanatuk Ummah pertama dibuat oleh generasi <a
                href="https://www.instagram.com/devthore_exc/" class="devthore-link">DEVTHORE</a>
              yang
              memberi informasi data dan berkas-berkas
              siswa kelas
              12EXC maupun
              12CI</p>
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
          <div class="footer-section">
            <h5 class="footer-title">Kontak Resmi</h5>
            <ul class="footer-links">
              <li>
                <a href="https://wa.me/6281336762765" target="_blank" rel="noopener">
                  <i class="fab fa-whatsapp me-2"></i>WA Pak Aziz
                </a>
              </li>
              <li>
                <a href="https://wa.me/6285334788543" target="_blank" rel="noopener">
                  <i class="fab fa-whatsapp me-2"></i>WA Pak Annas
                </a>
              </li>
              <li>
                <a href="https://wa.me/6281365055005" target="_blank" rel="noopener">
                  <i class="fab fa-whatsapp me-2"></i>WA Pak Pikih
                </a>
              </li>
              <li>
                <a href="https://wa.me/6281334695496" target="_blank" rel="noopener">
                  <i class="fab fa-whatsapp me-2"></i>WA Bu Iffa
                </a>
              </li>
              <li>
                <a href="https://wa.me/6285655086068" target="_blank" rel="noopener">
                  <i class="fab fa-whatsapp me-2"></i>WA Pak Eris
                </a>
              </li>
            </ul>
          </div>
        </div>
        <div class="col-lg-4 col-md-12">
          <div class="footer-section">
            <h5 class="footer-title">Tim PTN</h5>
            <ul class="footer-team">
              <li><i class="fab fa-instagram"></i><a target="_blank"
                  href="https://www.instagram.com/aryansyahm._/">@aryansyah._</a></li>
              <li><i class="fab fa-instagram"></i><a target="_blank"
                  href="https://www.instagram.com/ardiardiansyah._/">@ardiardiansyah._</a></li>
              <li><i class="fab fa-instagram"></i><a target="_blank"
                  href="https://www.instagram.com/cahle_vi/">@cahle_vi</a></li>
              <li><i class="fab fa-instagram"></i><a target="_blank"
                  href="https://www.instagram.com/daff.mandd_/">@daff.mandd_</a></li>
              <li><i class="fab fa-instagram"></i><a target="_blank"
                  href="https://www.instagram.com/carvalho_zf/">@carvalho_zf</a></li>
              <li><i class="fab fa-instagram"></i><a target="_blank"
                  href="https://www.instagram.com/iftnswnzn/">@iftnswnzn</a></li>
              <li><i class="fab fa-instagram"></i><a target="_blank"
                  href="https://www.instagram.com/hy.firaa/">@hy.firaa</a></li>
              <li><i class="fab fa-instagram"></i><a target="_blank"
                  href="https://www.instagram.com/sidnizhraa_/">@sidnizhraa_</a></li>
              <li><i class="fab fa-instagram"></i><a target="_blank"
                  href="https://www.instagram.com/virile.all/">@virile.all</a></li>
              <li><i class="fab fa-instagram"></i><a target="_blank"
                  href="https://www.instagram.com/anndyni_/">@anndyni_</a></li>
              <li><i class="fab fa-instagram"></i><a target="_blank"
                  href="https://www.instagram.com/hsnazkyh/">@hsnazkyh</a></li>
              <li><i class="fab fa-instagram"></i><a target="_blank"
                  href="https://www.instagram.com/lyntnaa.frd/">@lyntnaa.frd</a></li>
              <li><i class="fab fa-instagram"></i><a target="_blank"
                  href="https://www.instagram.com/njwan_alif/">@njwan_alif</a></li>
            </ul>
          </div>
        </div>
      </div>

      <div class="text-center mt-5">
        <h6 class="footer-title">Didukung Oleh:</h6>
        <div class="support-logos">
          <img src="assets/images/logo MA remove bg.png" alt="Support Logo 1" class="support-logo" />
          <img src="assets/images/logodvt.jpg" alt="Support Logo 2" class="support-logo" />
          <img src="assets/images/logoMAI.png" alt="Support Logo 3" class="support-logo" />
        </div>
      </div>

      <div class="footer-bottom">
        <p>&copy; 2025 PTN MAI. All rights reserved. Made with <i class="fas fa-heart text-danger"></i> by <a
            target="_blank" href="https://www.instagram.com/aryansyahm._/">aryansyahm._</a>
        </p>
      </div>
    </div>
  </footer>

  <!-- Scroll to Top Button -->
  <div class="scroll-top" id="scrollTop">
    <i class="fas fa-arrow-up"></i>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Counter roll untuk .stat-number (mis. "300+")
    (function () {
      const easeOutCubic = t => 1 - Math.pow(1 - t, 3);
      const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

      // Ambil semua elemen stat-number & simpan targetnya
      const counters = Array.from(document.querySelectorAll('.stat-number')).map(el => {
        const raw = (el.textContent || '').trim();
        const m = raw.match(/^\s*([0-9]+)(.*)?$/); // ambil angka & sisa (mis. "+")
        const target = m ? parseInt(m[1], 10) : 0;
        const suffix = m && m[2] ? m[2] : '';
        return {
          el,
          target,
          suffix,
          done: false
        };
      });

      // Kalau user pilih reduced motion, set langsung & selesai
      if (prefersReduced) {
        counters.forEach(c => c.el.textContent = c.target.toLocaleString('id-ID') + c.suffix);
        return;
      }

      // Observer: mulai animasi saat terlihat
      const io = new IntersectionObserver(entries => {
        entries.forEach(entry => {
          if (!entry.isIntersecting) return;
          const el = entry.target;
          const c = counters.find(x => x.el === el);
          if (!c || c.done) return;
          animateCounter(c);
          c.done = true;
          io.unobserve(el);
        });
      }, {
        threshold: 0.35
      });

      counters.forEach(c => io.observe(c.el));

      function animateCounter({
        el,
        target,
        suffix
      }) {
        const duration = parseInt(el.dataset.duration || '1200', 10); // ms (bisa override via data-duration)
        const start = performance.now();
        const startVal = 0;

        function frame(now) {
          const t = Math.min(1, (now - start) / duration);
          const eased = easeOutCubic(t);
          const val = Math.round(startVal + (target - startVal) * eased);
          el.textContent = val.toLocaleString('id-ID') + suffix;
          if (t < 1) requestAnimationFrame(frame);
        }
        requestAnimationFrame(frame);
      }
    })();
    // Configuration
    const CONFIG = {
      slides: [{
          img: "assets/images/AU.jpg",
          title: "Selamat Datang di PTN MAI",
          subtitle: "Portal resmi tim PTN MAI - Membangun masa depan pendidikan Indonesia"
        },
        {
          img: "assets/images/yai.jpg",
          title: "Inovasi & Teknologi",
          subtitle: "Menghadirkan solusi teknologi terdepan untuk dunia pendidikan"
        },
        {
          img: "assets/images/tari2.jpeg",
          title: "Bersama Menuju Prestasi",
          subtitle: "Kolaborasi dan dedikasi untuk mencapai excellence dalam pendidikan"
        }
      ],
      slideInterval: 4000,
      textFadeDelay: 200
    };

    // DOM Elements
    const elements = {
      bgA: document.getElementById('bgLayerA'),
      bgB: document.getElementById('bgLayerB'),
      title: document.getElementById('heroTitle'),
      subtitle: document.getElementById('heroSubtitle'),
      scrollTop: document.getElementById('scrollTop')
    };

    // Slideshow System
    class SlideShow {
      constructor() {
        this.currentIndex = 0;
        this.isLayerA = true;
        this.init();
      }

      async init() {
        await this.preloadImages();
        this.showInitialSlide();
        this.startSlideshow();
      }

      preloadImages() {
        return Promise.all(CONFIG.slides.map(slide =>
          new Promise((resolve, reject) => {
            const img = new Image();
            img.onload = resolve;
            img.onerror = reject;
            img.src = slide.img;
          })
        )).catch(error => console.warn('Image preload error:', error));
      }

      showInitialSlide() {
        const firstSlide = CONFIG.slides[0];
        elements.bgA.style.backgroundImage = `url('${firstSlide.img}')`;
        elements.bgA.classList.add('active');
        this.updateText(0);
      }

      updateText(index) {
        const slide = CONFIG.slides[index];

        // Fade out
        elements.title.classList.remove('text-animation-in');
        elements.subtitle.classList.remove('text-animation-in');
        elements.title.classList.add('text-animation-out');
        elements.subtitle.classList.add('text-animation-out');

        setTimeout(() => {
          // Update content
          elements.title.textContent = slide.title;
          elements.subtitle.textContent = slide.subtitle;

          // Fade in
          elements.title.classList.remove('text-animation-out');
          elements.subtitle.classList.remove('text-animation-out');
          elements.title.classList.add('text-animation-in');
          elements.subtitle.classList.add('text-animation-in');
        }, CONFIG.textFadeDelay);
      }

      nextSlide() {
        const nextIndex = (this.currentIndex + 1) % CONFIG.slides.length;
        const nextSlide = CONFIG.slides[nextIndex];

        if (this.isLayerA) {
          elements.bgB.style.backgroundImage = `url('${nextSlide.img}')`;
          elements.bgB.classList.add('active');
          elements.bgA.classList.remove('active');
        } else {
          elements.bgA.style.backgroundImage = `url('${nextSlide.img}')`;
          elements.bgA.classList.add('active');
          elements.bgB.classList.remove('active');
        }

        this.updateText(nextIndex);
        this.isLayerA = !this.isLayerA;
        this.currentIndex = nextIndex;
      }

      startSlideshow() {
        setInterval(() => this.nextSlide(), CONFIG.slideInterval);
      }
    }

    // Scroll Animations
    class ScrollAnimations {
      constructor() {
        this.init();
      }

      init() {
        this.setupScrollObserver();
        this.setupScrollToTop();
        this.setupNavbarScroll();
      }

      setupScrollObserver() {
        const observerOptions = {
          threshold: 0.1,
          rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
          entries.forEach(entry => {
            if (entry.isIntersecting) {
              entry.target.classList.add('animated');
            }
          });
        }, observerOptions);

        document.querySelectorAll('.animate-on-scroll').forEach(el => {
          observer.observe(el);
        });
      }

      setupScrollToTop() {
        window.addEventListener('scroll', () => {
          const scrolled = window.pageYOffset;
          const shouldShow = scrolled > 300;

          elements.scrollTop.classList.toggle('visible', shouldShow);
        });

        elements.scrollTop.addEventListener('click', () => {
          window.scrollTo({
            top: 0,
            behavior: 'smooth'
          });
        });
      }

      setupNavbarScroll() {
        const navbar = document.querySelector('.navbar-custom');
        window.addEventListener('scroll', () => {
          const scrolled = window.pageYOffset;
          navbar.style.background = scrolled > 50 ?
            'rgba(33, 37, 41, 0.98)' :
            'rgba(33, 37, 41, 0.95)';
        });
      }
    }

    // Smooth scroll for navigation links
    document.querySelectorAll('a[href^="#"]').forEach(link => {
      link.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
          target.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });
        }
      });
    });

    // Initialize
    document.addEventListener('DOMContentLoaded', () => {
      new SlideShow();
      new ScrollAnimations();
    });
  </script>
</body>

</html>