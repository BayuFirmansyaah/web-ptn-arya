<?php
require __DIR__.'/lib/auth.php'; 
if (is_admin()) { header('Location: dashboard.php'); exit; }
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>PTN MAI - Portal Resmi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />

  <style>
    :root {
      --primary: #28a745;
      --primary-dark: #218838;
      --secondary: #6c757d;
      --accent: #ffc107;
      --dark: #212529;
      --light: #f8f9fa;
      --overlay: rgba(0, 0, 0, 0.5);
      --gradient: linear-gradient(135deg, var(--primary), var(--primary-dark));
      
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

    /* ====== Background System ====== */
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

    /* ====== Navigation ====== */
    .navbar-custom {
      background: rgba(33, 37, 41, 0.95);
      backdrop-filter: blur(10px);
      box-shadow: var(--shadow);
      transition: all 0.3s ease;
    }

    .navbar-brand {
      font-weight: 700;
      font-size: 1.5rem;
      color: var(--primary) !important;
    }

    .nav-link {
      font-weight: 500;
      color: white !important;
      transition: color 0.3s ease;
    }

    .nav-link:hover {
      color: var(--primary) !important;
    }

    /* ====== Hero Section ====== */
    .hero {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      padding: 120px 20px 80px;
      color: white;
    }

    .hero-content {
      max-width: 900px;
      margin: auto;
    }

    .hero-title {
      font-size: clamp(2.5rem, 5vw, 4rem);
      font-weight: 700;
      margin-bottom: 1.5rem;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
      transition: opacity var(--text-fade) ease, transform var(--text-fade) ease;
    }

    .hero-subtitle {
      font-size: clamp(1.1rem, 2.5vw, 1.4rem);
      margin-bottom: 2.5rem;
      opacity: 0.95;
      font-weight: 300;
      text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
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
      transition: all 0.3s ease;
      min-width: 180px;
    }

    .btn-primary-custom {
      background: var(--gradient);
      color: white;
      border: none;
      box-shadow: 0 4px 15px rgba(40, 167, 69, 0.4);
    }

    .btn-primary-custom:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(40, 167, 69, 0.6);
    }

    .btn-outline-custom {
      background: transparent;
      color: white;
      border: 2px solid white;
    }

    .btn-outline-custom:hover {
      background: white;
      color: var(--primary);
      transform: translateY(-2px);
    }

    /* ====== Features Section ====== */
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
      background: white;
      border-radius: var(--border-radius);
      padding: 2.5rem 2rem;
      text-align: center;
      box-shadow: var(--shadow);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      height: 100%;
      border: none;
    }

    .feature-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
    }

    .feature-icon {
      width: 80px;
      height: 80px;
      background: var(--gradient);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 1.5rem;
      color: white;
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

    /* ====== Stats Section ====== */
    .stats {
      padding: 80px 0;
      background: var(--primary);
      color: white;
    }

    .stat-item {
      text-align: center;
      margin-bottom: 2rem;
    }

    .stat-number {
      font-size: 3rem;
      font-weight: 700;
      display: block;
      margin-bottom: 0.5rem;
    }

    .stat-label {
      font-size: 1.1rem;
      opacity: 0.9;
      font-weight: 300;
    }

    /* ====== Team Section ====== */
    .team {
      padding: 100px 0;
      background: white;
    }

    .team-card {
      background: white;
      border-radius: var(--border-radius);
      padding: 2rem;
      text-align: center;
      box-shadow: var(--shadow);
      transition: transform 0.3s ease;
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
      border: 4px solid var(--primary);
      box-shadow: 0 5px 20px rgba(40, 167, 69, 0.3);
    }

    .team-name {
      font-weight: 600;
      font-size: 1.2rem;
      margin-bottom: 0.5rem;
      color: var(--dark);
    }

    .team-role {
      color: var(--secondary);
      margin-bottom: 1rem;
    }

    /* ====== Footer ====== */
    .footer {
      background: var(--dark);
      color: white;
      padding: 60px 0 30px;
    }

    .footer-section {
      margin-bottom: 2rem;
    }

    .footer-title {
      font-weight: 600;
      font-size: 1.2rem;
      margin-bottom: 1.5rem;
      color: var(--primary);
    }

    .footer-links {
      list-style: none;
      padding: 0;
    }

    .footer-links li {
      margin-bottom: 0.8rem;
    }

    .footer-links a {
      color: rgba(255, 255, 255, 0.8);
      text-decoration: none;
      transition: color 0.3s ease;
    }

    .footer-links a:hover {
      color: var(--primary);
    }

    .footer-bottom {
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      padding-top: 2rem;
      text-align: center;
      color: rgba(255, 255, 255, 0.7);
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
      border: 2px solid rgba(255, 255, 255, 0.2);
      transition: transform 0.3s ease;
    }

    .support-logo:hover {
      transform: scale(1.1);
      border-color: var(--primary);
    }

    /* ====== Scroll to Top ====== */
    .scroll-top {
      position: fixed;
      bottom: 20px;
      right: 20px;
      background: var(--primary);
      color: white;
      width: 50px;
      height: 50px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      opacity: 0;
      transform: translateY(100px);
      transition: all 0.3s ease;
      z-index: 1000;
      box-shadow: var(--shadow);
    }

    .scroll-top.visible {
      opacity: 1;
      transform: translateY(0);
    }

    .scroll-top:hover {
      background: var(--primary-dark);
      transform: translateY(-3px);
    }

    /* ====== Responsive ====== */
    @media (max-width: 768px) {
      .hero {
        padding: 100px 20px 60px;
      }
      
      .btn-hero {
        display: block;
        margin: 10px auto;
        width: 100%;
        max-width: 280px;
      }
      
      .features, .team {
        padding: 60px 0;
      }
      
      .stats {
        padding: 60px 0;
      }
    }

    /* ====== Animations ====== */
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .animate-on-scroll {
      opacity: 0;
      transform: translateY(30px);
      transition: all 0.6s ease;
    }

    .animate-on-scroll.animated {
      opacity: 1;
      transform: translateY(0);
    }

    /* ====== Reduced Motion ====== */
    @media (prefers-reduced-motion: reduce) {
      .bg-layer,
      .hero-title,
      .hero-subtitle,
      .animate-on-scroll {
        transition: none !important;
      }
    }
  </style>
</head>

<body>
  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">
    <div class="container">
      <a class="navbar-brand" href="#home">
        <i class="fas fa-graduation-cap me-2"></i>PTN MAI
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
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
        <p id="heroSubtitle" class="hero-subtitle text-animation-in">Portal resmi tim PTN MAI - Membangun masa depan pendidikan Indonesia</p>
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

  <!-- Features Section -->
  <section id="features" class="features">
    <div class="container">
      <div class="section-title animate-on-scroll">
        <h2>Layanan Kami</h2>
        <p>Berbagai layanan terbaik untuk mendukung kebutuhan akademik Anda</p>
      </div>
      <div class="row g-4">
        <div class="col-lg-4 col-md-6">
          <div class="feature-card animate-on-scroll">
            <div class="feature-icon">
              <i class="fas fa-search"></i>
            </div>
            <h4>Pencarian Data</h4>
            <p>Sistem pencarian yang canggih dan mudah digunakan untuk menemukan informasi yang Anda butuhkan dengan cepat dan akurat.</p>
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
          <div class="feature-card animate-on-scroll">
            <div class="feature-icon">
              <i class="fas fa-users"></i>
            </div>
            <h4>Tim Profesional</h4>
            <p>Didukung oleh tim yang berpengalaman dan profesional dalam bidang pendidikan dan teknologi informasi.</p>
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
          <div class="feature-card animate-on-scroll">
            <div class="feature-icon">
              <i class="fas fa-shield-alt"></i>
            </div>
            <h4>Keamanan Data</h4>
            <p>Sistem keamanan berlapis untuk melindungi data dan informasi penting dengan standar keamanan tertinggi.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Stats Section -->
  <section class="stats">
    <div class="container">
      <div class="row text-center">
        <div class="col-lg-3 col-md-6">
          <div class="stat-item animate-on-scroll">
            <span class="stat-number">1000+</span>
            <span class="stat-label">Data Tersedia</span>
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="stat-item animate-on-scroll">
            <span class="stat-number">500+</span>
            <span class="stat-label">Pengguna Aktif</span>
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="stat-item animate-on-scroll">
            <span class="stat-number">24/7</span>
            <span class="stat-label">Dukungan</span>
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="stat-item animate-on-scroll">
            <span class="stat-number">99%</span>
            <span class="stat-label">Kepuasan</span>
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
            <p>Spesialis dalam pengembangan web dan sistem informasi dengan pengalaman lebih dari 5 tahun.</p>
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
          <div class="team-card animate-on-scroll">
            <img src="assets/images/atiqqudin.jpg" alt="M Atiqudin" class="team-image" loading="lazy">
            <h5 class="team-name">M Atiqudin</h5>
            <p class="team-role">Arsipis | Tim PTN</p>
            <p>Ahli dalam pengelolaan arsip dan dokumentasi dengan sistem yang terorganisir dan efisien.</p>
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
            <p class="mb-4">Portal resmi tim PTN MAI yang menyediakan berbagai layanan dan informasi untuk mendukung kebutuhan akademik.</p>
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
            </ul>
          </div>
        </div>
        <div class="col-lg-4 col-md-12">
          <div class="footer-section">
            <h5 class="footer-title">Tim PTN</h5>
            <ul class="footer-links">
              <li><i class="fab fa-instagram me-2"></i>@aryansyah_ptn</li>
              <li><i class="fab fa-instagram me-2"></i>@atiqudin_ptn</li>
              <li><i class="fab fa-instagram me-2"></i>@citra_ptn</li>
              <li><i class="fab fa-instagram me-2"></i>@deni_ptn</li>
              <li><i class="fab fa-instagram me-2"></i>@evi_ptn</li>
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
        <p>&copy; 2025 PTN MAI. All rights reserved. Made with <i class="fas fa-heart text-danger"></i> by Tim PTN</p>
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
    // Configuration
    const CONFIG = {
      slides: [
        {
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
          navbar.style.background = scrolled > 50 
            ? 'rgba(33, 37, 41, 0.98)' 
            : 'rgba(33, 37, 41, 0.95)';
        });
      }
    }

    // Smooth scroll for navigation links
    document.querySelectorAll('a[href^="#"]').forEach(link => {
      link.addEventListener('click', function(e) {
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