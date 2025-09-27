<?php
require __DIR__.'/lib/auth.php';
if (is_admin()) { header('Location: dashboard.php'); exit; }
?>
<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8" />
  <title>Admin Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <style>
    :root {
      --bg1: #36642d;
      --bg2: #26865e;
      --accent: #20bf00;
      --accent-2: #169300;
      --ink: #0f172a;
      --muted: #64748b;
      --line: #e5e7eb;
      --card: #ffffff;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    html,
    body {
      height: 100%;
    }

    body {
      font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, Arial, Helvetica, sans-serif;
      color: var(--ink);
      background: #f6f7f9;
      overflow-x: hidden;
    }

    /* ====== LAYOUT RESPONSIVE ====== */
    .container {
      min-height: 100vh;
      min-height: 100dvh;
      display: grid;
      grid-template-columns: 1.2fr 1fr;
      position: relative;
    }

    @media (max-width: 1024px) {
      .container {
        grid-template-columns: 1fr 1fr;
      }
    }

    @media (max-width: 768px) {
      .container {
        grid-template-columns: 1fr;
        grid-template-rows: 40vh 1fr;
      }
      .hero {
        min-height: 40vh;
      }
    }

    @media (max-width: 480px) {
      .container {
        grid-template-rows: 35vh 1fr;
      }
      .hero {
        min-height: 35vh;
      }
    }

    /* ====== HERO SECTION ====== */
    .hero {
      position: relative;
      overflow: hidden;
      background: linear-gradient(135deg, var(--bg1), var(--bg2));
      display: grid;
      place-items: center;
      color: #fff;
      padding: 20px;
    }

    .slides {
      position: absolute;
      inset: 0;
    }

    .slide {
      position: absolute;
      inset: 0;
      opacity: 0;
      transform: scale(1.05);
      transition: opacity 1s ease, transform 1.6s ease;
      background-position: center;
      background-size: cover;
      background-repeat: no-repeat;
      filter: saturate(120%) contrast(1.05);
    }

    .slide::after {
      content: "";
      position: absolute;
      inset: 0;
      background: 
        linear-gradient(120deg, rgba(0, 0, 0, 0.2), rgba(0, 0, 0, 0.4)),
        radial-gradient(1000px 500px at 15% 15%, rgba(32, 191, 0, 0.3), transparent 65%);
      mix-blend-mode: multiply;
    }

    .slide.active {
      opacity: 1;
      transform: scale(1);
    }

    .hero-content {
      position: relative;
      z-index: 2;
      width: min(600px, 95%);
      padding: 20px;
      text-align: center;
    }

    .kicker {
      display: inline-block;
      padding: 8px 16px;
      border-radius: 50px;
      background: rgba(255, 255, 255, 0.2);
      backdrop-filter: blur(10px);
      font-size: 14px;
      font-weight: 600;
      letter-spacing: 0.5px;
      margin-bottom: 16px;
      border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .title {
      font-size: clamp(28px, 5vw, 48px);
      line-height: 1.1;
      font-weight: 900;
      letter-spacing: -0.5px;
      margin-bottom: 16px;
      text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
    }

    .desc {
      color: rgba(255, 255, 255, 0.9);
      font-size: clamp(14px, 2.5vw, 18px);
      line-height: 1.6;
      max-width: 50ch;
      margin: 0 auto;
    }

    .fade {
      opacity: 0;
      transform: translateY(20px);
      transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .fade.show {
      opacity: 1;
      transform: translateY(0);
    }

    .dots {
      position: absolute;
      left: 50%;
      transform: translateX(-50%);
      bottom: 30px;
      display: flex;
      gap: 12px;
      z-index: 2;
    }

    @media (max-width: 768px) {
      .dots {
        bottom: 20px;
      }
    }

    .dot {
      width: 12px;
      height: 12px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.4);
      border: none;
      cursor: pointer;
      transition: all 0.3s ease;
      position: relative;
    }

    .dot:hover {
      background: rgba(255, 255, 255, 0.7);
      transform: scale(1.1);
    }

    .dot.active {
      background: #fff;
      transform: scale(1.2);
    }

    .dot.active::after {
      content: '';
      position: absolute;
      inset: -4px;
      border: 2px solid rgba(255, 255, 255, 0.3);
      border-radius: 50%;
      animation: pulse 2s infinite;
    }

    @keyframes pulse {
      0%, 100% { opacity: 0.3; transform: scale(1); }
      50% { opacity: 0.6; transform: scale(1.1); }
    }

    /* ====== LOGIN PANEL ====== */
    .panel {
      display: grid;
      place-items: center;
      padding: 40px 20px;
      background: linear-gradient(135deg, #f8faf8, #f1f4f2);
      position: relative;
    }

    .panel::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 100px;
      background: linear-gradient(135deg, rgba(32, 191, 0, 0.05), transparent);
      pointer-events: none;
    }

    .card {
      position: relative;
      width: min(440px, 100%);
      background: rgba(255, 255, 255, 0.95);
      border-radius: 24px;
      padding: 40px 32px;
      box-shadow: 
        0 20px 40px rgba(0, 0, 0, 0.1),
        0 8px 20px rgba(0, 0, 0, 0.06);
      backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.2);
      animation: slideInUp 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }

    @keyframes slideInUp {
      from {
        opacity: 0;
        transform: translateY(40px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @media (max-width: 480px) {
      .card {
        padding: 32px 24px;
        border-radius: 20px;
      }
      .panel {
        padding: 20px 16px;
      }
    }

    .card::before {
      content: "";
      position: absolute;
      inset: 0;
      padding: 1px;
      border-radius: 24px;
      background: linear-gradient(135deg, rgba(32, 191, 0, 0.3), rgba(38, 134, 94, 0.3), rgba(32, 191, 0, 0.3));
      -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
      -webkit-mask-composite: xor;
      mask-composite: exclude;
      pointer-events: none;
    }

    .brand {
      display: flex;
      align-items: center;
      gap: 16px;
      margin-bottom: 32px;
    }

    .badge {
      width: 60px;
      height: 60px;
      border-radius: 16px;
      background: linear-gradient(135deg, rgba(32, 191, 0, 0.1), rgba(38, 134, 94, 0.1));
      display: grid;
      place-items: center;
      font-weight: 900;
      color: var(--accent);
      box-shadow: 
        0 8px 20px rgba(32, 191, 0, 0.2),
        inset 0 0 0 1px rgba(32, 191, 0, 0.2);
      transition: transform 0.3s ease;
    }

    .badge:hover {
      transform: scale(1.05) rotate(5deg);
    }

    .badge img {
      width: 40px;
      height: 40px;
      object-fit: contain;
    }

    .heading {
      font-size: 24px;
      font-weight: 800;
      color: var(--ink);
      margin-bottom: 4px;
    }

    .lead {
      color: var(--muted);
      font-size: 15px;
      font-weight: 500;
    }

    .form {
      display: grid;
      gap: 20px;
    }

    .field {
      position: relative;
    }

    .input {
      width: 100%;
      padding: 18px 50px 18px 18px;
      border: 2px solid var(--line);
      border-radius: 16px;
      background: #fff;
      font-size: 16px;
      outline: none;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .input::placeholder {
      color: transparent;
    }

    .label {
      position: absolute;
      left: 18px;
      top: 50%;
      transform: translateY(-50%);
      font-size: 16px;
      color: #64748b;
      padding: 0 6px;
      background: transparent;
      pointer-events: none;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      font-weight: 500;
    }

    .input:focus {
      border-color: var(--accent);
      box-shadow: 0 0 0 4px rgba(32, 191, 0, 0.1), 0 4px 12px rgba(0, 0, 0, 0.1);
      transform: translateY(-1px);
    }

    .input:focus + .label,
    .input:not(:placeholder-shown) + .label {
      top: 0;
      transform: translateY(-50%) scale(0.85);
      background: #fff;
      color: var(--accent);
      font-weight: 600;
    }

    .toggle-pwd {
      position: absolute;
      right: 12px;
      top: 50%;
      transform: translateY(-50%);
      border: none;
      background: transparent;
      padding: 8px 12px;
      border-radius: 12px;
      color: var(--muted);
      cursor: pointer;
      font-size: 14px;
      font-weight: 600;
      transition: all 0.2s ease;
    }

    .toggle-pwd:hover {
      background: #f1f5f9;
      color: var(--accent);
    }

    .actions {
      margin-top: 8px;
    }

    .btn {
      width: 100%;
      padding: 18px 20px;
      border: none;
      border-radius: 16px;
      cursor: pointer;
      background: linear-gradient(135deg, var(--accent), var(--accent-2));
      color: #fff;
      font-weight: 800;
      font-size: 16px;
      letter-spacing: 0.5px;
      box-shadow: 0 12px 24px rgba(32, 191, 0, 0.3);
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
      overflow: hidden;
    }

    .btn::before {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), transparent);
      opacity: 0;
      transition: opacity 0.3s ease;
    }

    .btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 16px 32px rgba(32, 191, 0, 0.4);
    }

    .btn:hover::before {
      opacity: 1;
    }

    .btn:active {
      transform: translateY(0);
    }

    .btn[disabled] {
      opacity: 0.7;
      cursor: not-allowed;
      transform: none;
      box-shadow: 0 4px 8px rgba(32, 191, 0, 0.2);
    }

    .err {
      display: none;
      margin-top: 20px;
      background: linear-gradient(135deg, #fee2e2, #fecaca);
      border: 1px solid #f87171;
      color: #7f1d1d;
      padding: 16px;
      border-radius: 16px;
      font-size: 14px;
      font-weight: 500;
      animation: shake 0.5s ease-in-out;
    }

    @keyframes shake {
      0%, 100% { transform: translateX(0); }
      25% { transform: translateX(-5px); }
      75% { transform: translateX(5px); }
    }

    .foot {
      margin-top: 24px;
      text-align: center;
      font-size: 14px;
      color: var(--muted);
    }

    .foot a {
      color: var(--accent);
      text-decoration: none;
      font-weight: 600;
      transition: all 0.2s ease;
    }

    .foot a:hover {
      color: var(--accent-2);
      text-decoration: underline;
    }

    /* ====== LOADING SPINNER ====== */
    .spinner {
      width: 20px;
      height: 20px;
      border: 2px solid rgba(255, 255, 255, 0.3);
      border-radius: 50%;
      border-top-color: #fff;
      animation: spin 1s linear infinite;
      display: inline-block;
      margin-right: 8px;
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }

    /* ====== ACCESSIBILITY IMPROVEMENTS ====== */
    @media (prefers-reduced-motion: reduce) {
      *, *::before, *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
      }
    }

    /* ====== DARK MODE SUPPORT ====== */
    @media (prefers-color-scheme: dark) {
      body {
        background: #1a1a1a;
      }
      .panel {
        background: linear-gradient(135deg, #2a2a2a, #1f2937);
      }
      .card {
        background: rgba(30, 30, 30, 0.95);
        color: #e5e7eb;
      }
      .heading {
        color: #f9fafb;
      }
      .input {
        background: rgba(55, 65, 81, 0.5);
        border-color: #4b5563;
        color: #f9fafb;
      }
      .input:focus {
        background: rgba(55, 65, 81, 0.7);
      }
      .label {
        color: #9ca3af;
      }
      .input:focus + .label,
      .input:not(:placeholder-shown) + .label {
        background: rgba(30, 30, 30, 0.95);
      }
    }
  </style>
</head>

<body>
  <div class="container">
    <!-- LEFT: HERO -->
    <section class="hero" aria-label="Highlight">
      <div class="slides" id="slides"></div>

      <div class="hero-content">
        <span class="kicker fade" id="kicker">PTN MAI</span>
        <h1 class="title fade" id="title">Selamat Datang! 👋</h1>
        <p class="desc fade" id="desc">Kelola data peserta, unggah berkas, dan pantau progres—semua lebih cepat & rapi.</p>
      </div>

      <div class="dots" id="dots" aria-label="Navigasi slide"></div>
    </section>

    <!-- RIGHT: LOGIN PANEL -->
    <section class="panel">
      <div class="card" role="dialog" aria-labelledby="loginTitle">
        <div class="brand">
          <div class="badge">
            <img src="assets/images/logoMAI.png" alt="Logo PTN MAI">
          </div>
          <div>
            <h2 class="heading" id="loginTitle">ADMIN TIM PTN</h2>
            <p class="lead">Masuk untuk mengelola sistem</p>
          </div>
        </div>

        <form id="f" class="form" method="post" action="api/login.php" novalidate>
          <div class="field">
            <input id="username" name="username" class="input" placeholder=" " required autocomplete="username" />
            <label for="username" class="label">Username</label>
          </div>

          <div class="field">
            <input id="password" type="password" name="password" class="input" placeholder=" " required autocomplete="current-password" />
            <label for="password" class="label">Password</label>
            <button class="toggle-pwd" type="button" id="togglePwd" aria-label="Tampilkan password">Lihat</button>
          </div>

          <div class="actions">
            <button type="submit" id="btn" class="btn">
              <span id="btnText">MASUK</span>
            </button>
          </div>

          <div id="msg" class="err" role="alert" aria-live="polite"></div>
        </form>

        <div class="foot">
          Butuh akses? <a href="https://www.instagram.com/aryansyahm._/" target="_blank" rel="noopener">Hubungi admin utama</a>
        </div>
      </div>
    </section>
  </div>

  <script>
    /* ======== SLIDESHOW ======== */
    const SLIDES = [{
        img: 'assets/images/slide1.png',
        kicker: 'Automasi & Rapi',
        title: 'Kelola Berkas Tanpa Ribet ✨',
        desc: 'Upload sertifikat & dokumen peserta, semuanya terstruktur dan mudah dicari.'
      },
      {
        img: 'assets/images/slide2.png',
        kicker: 'Cepat & Aman',
        title: 'Akses Data Real-Time 🔐',
        desc: 'Data tersimpan lokal, kontrol akses per-admin, dan histori tetap aman.'
      },
      {
        img: 'assets/images/slide3.png',
        kicker: 'Fokus Ke Hasil',
        title: 'Produktif Setiap Hari 🚀',
        desc: 'Filter peserta, edit biodata, dan kelola berkas dalam hitungan detik.'
      }
    ];

    const slidesWrap = document.getElementById('slides');
    const dotsWrap = document.getElementById('dots');
    const elKick = document.getElementById('kicker');
    const elTitle = document.getElementById('title');
    const elDesc = document.getElementById('desc');

    SLIDES.forEach((s, i) => {
      const d = document.createElement('div');
      d.className = 'slide';
      d.style.backgroundImage = s.img ? `url("${s.img}")` : 'linear-gradient(135deg, var(--bg1), var(--bg2))';
      slidesWrap.appendChild(d);

      const dot = document.createElement('button');
      dot.className = 'dot';
      dot.setAttribute('aria-label', `Slide ${i + 1}`);
      dot.onclick = () => go(i, true);
      dotsWrap.appendChild(dot);
    });

    const slides = Array.from(document.querySelectorAll('.slide'));
    const dots = Array.from(document.querySelectorAll('.dot'));

    let idx = 0, timer = null, FIRST = true;

    function setCopy(s) {
      [elKick, elTitle, elDesc].forEach(el => el.classList.remove('show'));
      setTimeout(() => {
        elKick.textContent = s.kicker || 'PTN MAI';
        elTitle.textContent = s.title || 'Selamat Datang! 👋';
        elDesc.textContent = s.desc || 'Kelola data peserta dengan cepat & rapi.';
        [elKick, elTitle, elDesc].forEach(el => el.classList.add('show'));
      }, FIRST ? 0 : 200);
    }

    function go(n, manual = false) {
      FIRST = false;
      slides.forEach((el, i) => el.classList.toggle('active', i === n));
      dots.forEach((el, i) => el.classList.toggle('active', i === n));
      setCopy(SLIDES[n]);
      idx = n;
      if (manual) restart();
    }

    function next() {
      go((idx + 1) % SLIDES.length);
    }

    function restart() {
      if (timer) clearInterval(timer);
      timer = setInterval(next, 5500);
    }

    go(0);
    restart();

    /* ======== LOGIN FUNCTIONALITY ======== */
    (function () {
      const f = document.getElementById('f');
      const btn = document.getElementById('btn');
      const btnText = document.getElementById('btnText');
      const msg = document.getElementById('msg');
      const pwd = document.getElementById('password');
      const togglePwd = document.getElementById('togglePwd');

      togglePwd.addEventListener('click', () => {
        const isText = pwd.type === 'text';
        pwd.type = isText ? 'password' : 'text';
        togglePwd.textContent = isText ? 'Lihat' : 'Sembunyi';
        togglePwd.setAttribute('aria-label', isText ? 'Tampilkan password' : 'Sembunyikan password');
        pwd.focus();
      });

      function showErr(text) {
        msg.textContent = text || 'Login gagal';
        msg.style.display = 'block';
        setTimeout(() => {
          msg.style.display = 'none';
        }, 5000);
      }

      function setLoading(loading) {
        btn.disabled = loading;
        if (loading) {
          btnText.innerHTML = '<span class="spinner"></span>Memproses...';
        } else {
          btnText.textContent = 'MASUK';
        }
      }

      f.addEventListener('submit', async (e) => {
        e.preventDefault();
        msg.style.display = 'none';
        setLoading(true);

        try {
          const response = await fetch('api/login.php', {
            method: 'POST',
            body: new FormData(f),
            credentials: 'same-origin'
          });

          let result = null;
          try {
            result = await response.json();
          } catch (_) {}

          if (result && result.ok) {
            btnText.innerHTML = '<span class="spinner"></span>Berhasil! Mengalihkan...';
            setTimeout(() => {
              location.href = 'dashboard.php';
            }, 1000);
            return;
          }

          if (response.ok) {
            location.href = 'dashboard.php';
            return;
          }

          showErr((result && result.error) || 'Username atau password salah');
        } catch (err) {
          showErr('Tidak dapat terhubung ke server. Periksa koneksi internet Anda.');
        } finally {
          setLoading(false);
        }
      });

      // Auto-focus on username field
      document.getElementById('username').focus();
    })();
  </script>
</body>

</html>