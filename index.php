<?php
require __DIR__.'/lib/auth.php'; 
if (is_admin()) { header('Location: dashboard.php'); exit; }
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>PTN MAI</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />

  <style>
    :root {
      --brand: #28a745;
      --brand-dark: #218838;
      --overlay: rgba(0, 0, 0, .52);
      --fade: 2000ms;
      /* durasi crossfade bg */
      --text-fade: 900ms;
      /* durasi fade teks */
      --slide-gap: 3200ms;
      /* waktu tiap slide tampil */
    }

    html,
    body {
      height: 100%
    }

    body {
      margin: 0;
      font-family: "Poppins", sans-serif;
      color: #fff;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      overflow-x: hidden;
      background: #000;
    }

    /* ====== Background engine (double buffer) ====== */
    .bg-stage {
      position: fixed;
      inset: 0;
      z-index: -2;
      pointer-events: none;
      overflow: hidden;
    }

    .bg-layer {
      position: absolute;
      inset: 0;
      background-position: center;
      background-size: cover;
      background-repeat: no-repeat;
      opacity: 0;
      will-change: opacity, transform;
      transition: opacity var(--fade) ease, transform var(--fade) ease;
      transform: scale(1.04);
      /* start a bit zoomed-in for Ken Burns out */
    }

    .bg-layer.active {
      opacity: 1;
      transform: scale(1);
      /* zoom out subtle -> elegan */
    }

    .bg-overlay {
      position: fixed;
      inset: 0;
      background: var(--overlay);
      z-index: -1;
    }

    /* ====== Hero ====== */
    .welcome {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      padding: 80px 16px;
    }

    .welcome .inner {
      max-width: 980px;
      margin: auto;
    }

    #title,
    #subtitle {
      transition: opacity var(--text-fade) ease, transform var(--text-fade) ease;
      will-change: opacity, transform;
    }

    .text-out {
      opacity: 0;
      transform: translateY(8px)
    }

    .text-in {
      opacity: 1;
      transform: translateY(0)
    }

    .btn-cta {
      min-width: 200px;
      padding: 12px 18px;
      border-radius: 12px;
      font-weight: 600
    }

    .btn-primary-ptn {
      background: var(--brand);
      border: 0
    }

    .btn-primary-ptn:hover {
      background: var(--brand-dark)
    }

    .btn-ghost {
      border: 2px solid #fff;
      color: #fff;
      background: transparent
    }

    .btn-ghost:hover {
      background: rgba(255, 255, 255, .12)
    }

    /* ====== Team (optional, tetap ada) ====== */
    .team {
      padding: 60px 16px;
      background: rgba(0, 0, 0, .45)
    }

    .card-team {
      background: var(--brand);
      border: 0;
      border-radius: 16px;
      color: #fff;
      box-shadow: 0 10px 24px rgba(0, 0, 0, .28)
    }

    .team-img {
      width: 110px;
      height: 110px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid #fff;
      margin: auto
    }

    /* ====== Footer ====== */
    footer {
      background: #aaaaaa;
      color: white;
      padding: 40px 20px;
      margin-top: auto;
    }

    .footer-col {
      margin-bottom: 20px;
    }

    footer h5 {
      font-weight: 600;
      margin-bottom: 15px;
    }

    footer ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    footer ul li {
      margin-bottom: 8px;
    }

    footer a {
      color: #fff;
      text-decoration: none;
    }

    footer a:hover {
      color: #ddd;
    }

    /* Reduced motion support */
    <blade media|%20(prefers-reduced-motion%3A%20reduce)%7B%0D>.bg-layer,
    #title,
    #subtitle {
      transition: none !important;
    }
  </style>
</head>

<body>
  <!-- Background -->
  <div class="bg-stage" aria-hidden="true">
    <div class="bg-layer" id="bgA"></div>
    <div class="bg-layer" id="bgB"></div>
  </div>
  <div class="bg-overlay" aria-hidden="true"></div>

  <!-- Hero -->
  <section class="welcome">
    <div class="container">
      <div class="inner">
        <h1 id="title" class="display-4 fw-bold mb-2 text-in">Selamat Datang di Web PTN MAI</h1>
        <p id="subtitle" class="lead mb-4 text-in">Portal resmi tim PTN MAI</p>
        <div class="d-flex gap-3 flex-wrap justify-content-center">
          <a href="pencarian.php" class="btn btn-cta btn-primary-ptn">Masuk</a>
          <a href="login.php" class="btn btn-cta btn-ghost">Login Admin</a>
        </div>
      </div>
    </div>
  </section>

  <!-- Team -->
  <section class="team">
    <div class="container">
      <h2 class="display-6 fw-bold text-center mb-4">Tim Kami</h2>
      <div class="row g-4 justify-content-center">
        <div class="col-md-4 col-sm-6">
          <div class="card card-team p-4 text-center h-100">
            <img src="assets/images/gw.jpg" alt="Aryansyah M" class="team-img mb-3" loading="lazy">
            <h6 class="fw-bold mb-1">Aryansyah M</h6>
            <div>Web Developer | Tim PTN</div>
          </div>
        </div>
        <div class="col-md-4 col-sm-6">
          <div class="card card-team p-4 text-center h-100">
            <img src="assets/images/atiqqudin.jpg" alt="M Atiqudin" class="team-img mb-3" loading="lazy">
            <h6 class="fw-bold mb-1">M Atiqudin</h6>
            <div>Arsipis | Tim PTN</div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- FOOTER -->
  <footer class="bg-dark text-white py-3">
    <div class="container d-flex justify-content-between align-items-start flex-wrap">
      <!-- Contact Us -->
      <div>
        <h6 class="fw-bold">Contact Us</h6>
        <ul class="list-unstyled mb-0 small">
          <li>
            <a href="https://wa.me/6281336762765" class="text-decoration-underline link-primary" target="_blank"
              rel="noopener">WA Pak Aziz</a>
          </li>
          <li>
            <a href="https://wa.me/6285334788543" class="text-decoration-underline link-primary" target="_blank"
              rel="noopener">WA Pak Annas</a>
          </li>
        </ul>
      </div>

      <!-- Contact Tim PTN -->
      <div class="mt-3 mt-md-0">
        <h6 class="fw-bold">Contact Tim PTN</h6>
        <ul class="list-unstyled mb-0 small">
          <li>📸 @aryansyah_ptn</li>
          <li>📸 @atiqudin_ptn</li>
          <li>📸 @citra_ptn</li>
          <li>📸 @deni_ptn</li>
          <li>📸 @evi_ptn</li>
        </ul>
      </div>
    </div>

    <!-- Support By -->
    <div class="text-center mt-4">
      <p class="mb-2 fw-bold small">Support By:</p>
      <img src="assets/images/logo MA remove bg.png" alt="Support Logo" class="rounded-circle shadow-sm"
        style="width: 70px; height: 70px; object-fit: cover; border: 2px solid #fff;" />
      <img src="assets/images/logodvt.jpg" alt="Support Logo" class="rounded-circle shadow-sm"
        style="width: 70px; height: 70px; object-fit: cover; border: 2px solid #fff;" />
      <img src="assets/images/logoMAI.png" alt="Support Logo" class="rounded-circle shadow-sm"
        style="width: 70px; height: 70px; object-fit: cover; border: 2px solid #fff;" />
    </div>

    <div class="text-center mt-3 small">
      &copy; 2025 PTN MAI. All rights reserved.
    </div>
  </footer>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const SLIDES = [{
        img: "assets/images/AU.jpg",
        title: "Selamat Datang di Web PTN MAI",
        subtitle: "Portal resmi tim PTN MAI"
      },
      {
        img: "assets/images/yai.jpg",
        title: "Inovasi & Teknologi",
        subtitle: "Membangun masa depan bersama PTN"
      },
      {
        img: "assets/images/tari2.jpeg",
        title: "Bersama Jadi Hebat",
        subtitle: "Kolaborasi untuk prestasi"
      }
    ];

    // Timing
    const HOLD = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--slide-gap')) ||
      6000; // waktu tampil per slide
    const FADEMS = 1200; // harus sama dgn --fade

    // Elements
    const bgA = document.getElementById('bgA');
    const bgB = document.getElementById('bgB');
    const titleEl = document.getElementById('title');
    const subEl = document.getElementById('subtitle');

    // Preload semua gambar -> hilangkan jeda saat pertama kali reach slide mana pun
    function preloadAll(slides) {
      return Promise.all(slides.map(s => new Promise((res, rej) => {
        const im = new Image();
        im.onload = () => res();
        im.onerror = rej;
        im.src = s.img;
      })));
    }

    // Helper anim teks sinkron
    function swapText(to) {
      // fade out
      titleEl.classList.remove('text-in');
      subEl.classList.remove('text-in');
      titleEl.classList.add('text-out');
      subEl.classList.add('text-out');

      setTimeout(() => {
        titleEl.textContent = SLIDES[to].title;
        subEl.textContent = SLIDES[to].subtitle;

        // fade in
        titleEl.classList.remove('text-out');
        subEl.classList.remove('text-out');
        titleEl.classList.add('text-in');
        subEl.classList.add('text-in');
      }, 180); // sedikit lebih cepat dari bg agar terasa responsive
    }

    // Engine: double buffer switch
    let current = 0;
    let onA = true; // layer aktif yang sedang kelihatan

    function showInitial() {
      // tampilkan slide 0 langsung
      bgA.style.backgroundImage = `url('${SLIDES[0].img}')`;
      bgA.classList.add('active');
      titleEl.textContent = SLIDES[0].title;
      subEl.textContent = SLIDES[0].subtitle;
    }

    function nextSlide() {
      const next = (current + 1) % SLIDES.length;
      const nextUrl = SLIDES[next].img;

      if (onA) {
        bgB.style.backgroundImage = `url('${nextUrl}')`;
        // trigger crossfade
        bgB.classList.add('active');
        bgA.classList.remove('active');
      } else {
        bgA.style.backgroundImage = `url('${nextUrl}')`;
        bgA.classList.add('active');
        bgB.classList.remove('active');
      }

      // teks sinkron
      swapText(next);

      // toggle buffer
      onA = !onA;
      current = next;
    }

    // Start slideshow setelah preload 100% -> no delay di slide mana pun
    (async function start() {
      try {
        await preloadAll(SLIDES);
      } catch (e) {
        console.warn("Ada gambar gagal preload, lanjut saja:", e);
      }
      showInitial();

      // interval siklik: HOLD tiap slide
      setInterval(nextSlide, HOLD);
    })();
  </script>
</body>

</html>