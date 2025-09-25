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
      /* gradient hijau */
      --accent: #20bf00;
      --accent-2: #169300;
      /* tombol */
      --ink: #0f172a;
      --muted: #64748b;
      --line: #e5e7eb;
      --card: #ffffff;
    }

    * {
      box-sizing: border-box
    }

    html,
    body {
      height: 100%
    }

    body {
      margin: 0;
      font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, Arial, Helvetica, sans-serif;
      color: var(--ink);
      background: #f6f7f9
    }

    /* ====== layout ====== */
    .container {
      min-height: 100dvh;
      display: grid;
      grid-template-columns: 1.1fr .9fr
    }

    @media(max-width:980px){ .container{grid-template-columns:1fr} .hero{min-height:46vh} }

    /* ====== HERO (kiri) ====== */
    .hero {
      position: relative;
      overflow: hidden;
      background: linear-gradient(135deg, var(--bg1), var(--bg2));
      display: grid;
      place-items: center;
      color: #fff;
    }

    .slides {
      position: absolute;
      inset: 0
    }

    .slide {
      position: absolute;
      inset: 0;
      opacity: 0;
      transform: scale(1.04);
      transition: opacity .8s ease, transform 1.4s ease;
      background-position: center;
      background-size: cover;
      background-repeat: no-repeat;
      filter: saturate(110%) contrast(1.02);
    }

    .slide::after {
      /* overlay hijau biar konsisten tone */
      content: "";
      position: absolute;
      inset: 0;
      background: linear-gradient(120deg, rgba(0, 0, 0, .15), rgba(0, 0, 0, .35)), radial-gradient(1200px 600px at 10% 10%, rgba(32, 191, 0, .25), transparent 60%);
      mix-blend: multiply;
    }

    .slide.active {
      opacity: 1;
      transform: scale(1)
    }

    .hero-content {
      position: relative;
      z-index: 2;
      width: min(640px, 90%);
      padding: 24px;
    }

    .kicker {
      display: inline-block;
      padding: 6px 10px;
      border-radius: 999px;
      background: rgba(255, 255, 255, .16);
      backdrop-filter: blur(6px);
      font-size: 12px;
      letter-spacing: .4px
    }

    .title {
      margin: 12px 0 10px;
      font-size: 40px;
      line-height: 1.1;
      font-weight: 800;
      letter-spacing: .2px
    }

    .desc {
      margin: 0;
      color: #e6f7e6;
      max-width: 52ch
    }

    .fade {
      opacity: 0;
      transform: translateY(6px);
      transition: .4s ease
    }

    .fade.show {
      opacity: 1;
      transform: none
    }

    .dots {
      position: absolute;
      left: 24px;
      bottom: 20px;
      display: flex;
      gap: 8px;
      z-index: 2
    }

    .dot {
      width: 9px;
      height: 9px;
      border-radius: 999px;
      background: rgba(255, 255, 255, .5);
      border: none;
      cursor: pointer
    }

    .dot.active {
      background: #fff
    }

    /* ====== PANEL LOGIN (kanan) ====== */
    .panel {
      display: grid;
      place-items: center;
      padding: 28px 20px;
      background: linear-gradient(180deg, #f8faf8, #f3f5f4);
    }

    .card {
      position: relative;
      width: min(420px, 92vw);
      background: rgba(255, 255, 255, .9);
      border-radius: 16px;
      padding: 22px 20px 18px;
      box-shadow: 0 22px 42px rgba(0, 0, 0, .12);
      backdrop-filter: blur(8px);
    }

    .card::before {
      /* garis gradyen halus */
      content: "";
      position: absolute;
      inset: 0;
      padding: 1px;
      border-radius: 16px;
      background: linear-gradient(120deg, rgba(32, 191, 0, .45), rgba(38, 134, 94, .45), rgba(32, 191, 0, .45));
      -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
      -webkit-mask-composite: xor;
      mask-composite: exclude;
      pointer-events: none;
    }

    .brand {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 8px
    }

    .badge {
      width: 46px;
      height: 46px;
      border-radius: 12px;
      background: conic-gradient(from 180deg at 50% 50%, rgba(32, 191, 0, .12), rgba(38, 134, 94, .12), rgba(32, 191, 0, .12));
      display: grid;
      place-items: center;
      font-weight: 800;
      color: var(--accent);
      box-shadow: inset 0 0 0 1px rgba(32, 191, 0, .22)
    }

    .heading {
      margin: 0;
      font-size: 20px;
      color: var(--accent)
    }

    .lead {
      margin: 2px 0 12px;
      color: var(--muted);
      font-size: 13.5px
    }

    .form {
      display: grid;
      gap: 12px
    }

    .field {
      position: relative
    }

    .input {
      width: 100%;
      padding: 14px 44px 14px 14px;
      border: 1px solid var(--line);
      border-radius: 12px;
      background: #fff;
      font-size: 14px;
      outline: none;
      transition: border-color .18s, box-shadow .18s, background .18s;
    }

    .input::placeholder {
      color: transparent
    }

    .label {
      position: absolute;
      left: 12px;
      top: 50%;
      transform: translateY(-50%);
      font-size: 14px;
      color: #475569;
      padding: 0 6px;
      background: transparent;
      pointer-events: none;
      transition: .18s;
    }

    .input:focus {
      border-color: rgba(32, 191, 0, .6);
      box-shadow: 0 0 0 6px rgba(32, 191, 0, .12)
    }

    .input:focus+.label,
    .input:not(:placeholder-shown)+.label {
      top: 0;
      transform: translateY(-50%) scale(.92);
      background: #fff;
      color: #0f172a;
      font-weight: 600
    }

    .toggle-pwd {
      position: absolute;
      right: 8px;
      top: 50%;
      transform: translateY(-50%);
      border: none;
      background: transparent;
      padding: 6px 10px;
      border-radius: 10px;
      color: #334155;
      cursor: pointer;
      font-size: 12px
    }

    .toggle-pwd:hover {
      background: #f1f5f9
    }

    .actions {
      display: grid;
      gap: 10px;
      margin-top: 6px
    }

    .btn {
      width: 100%;
      padding: 13px 14px;
      border: none;
      border-radius: 12px;
      cursor: pointer;
      background: linear-gradient(135deg, var(--accent), var(--accent-2));
      color: #fff;
      font-weight: 800;
      letter-spacing: .35px;
      box-shadow: 0 10px 22px rgba(32, 191, 0, .28);
      transition: transform .06s, filter .15s;
    }

    .btn:hover {
      filter: brightness(1.04)
    }

    .btn:active {
      transform: translateY(1px)
    }

    .btn[disabled] {
      opacity: .7;
      cursor: not-allowed;
      box-shadow: none
    }

    .err {
      display: none;
      margin-top: 10px;
      background: #fee2e2;
      border: 1px solid #fecaca;
      color: #7f1d1d;
      padding: 10px;
      border-radius: 12px;
      font-size: 13px
    }

    .foot {
      margin-top: 12px;
      text-align: center;
      font-size: 13px;
      color: #475569
    }

    .foot a {
      color: #2563eb;
      text-decoration: none
    }

    .foot a:hover {
      text-decoration: underline
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
        <p class="desc fade" id="desc">Kelola data peserta, unggah berkas, dan pantau progres—semua lebih cepat & rapi.
        </p>
      </div>

      <div class="dots" id="dots" aria-label="Navigasi slide"></div>
    </section>

    <!-- RIGHT: LOGIN PANEL -->
    <section class="panel">
      <div class="card" role="dialog" aria-labelledby="loginTitle">
        <div class="brand">
          <img class="badge" src="assets/images/logoMAI.png" alt="Logo PTN MAI">
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
            <input id="password" type="password" name="password" class="input" placeholder=" " required
              autocomplete="current-password" />
            <label for="password" class="label">Password</label>
            <button class="toggle-pwd" type="button" id="togglePwd" aria-label="Tampilkan password">Lihat</button>
          </div>

          <div class="actions">
            <button type="submit" id="btn" class="btn"><span id="btnText">MASUK</span></button>
          </div>

          <div id="msg" class="err" role="alert" aria-live="polite"></div>
        </form>

        <div class="foot">Butuh akses? <a href="https://www.instagram.com/aryansyahm._/" target="_blank">Hubungi admin
            utama</a></div>
      </div>
    </section>
  </div>

  <script>
    /* ======== SLIDESHOW: ganti gambar & teks ======== */
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
    // fallback kalau gambar belum ada
    for (const s of SLIDES)
      if (!s.img) s.img = '';

    const slidesWrap = document.getElementById('slides');
    const dotsWrap = document.getElementById('dots');
    const elKick = document.getElementById('kicker');
    const elTitle = document.getElementById('title');
    const elDesc = document.getElementById('desc');

    // Buat elemen slide + dot
    SLIDES.forEach((s, i) => {
      const d = document.createElement('div');
      d.className = 'slide';
      d.style.backgroundImage = s.img ? `url("${s.img}")` : 'linear-gradient(135deg, var(--bg1), var(--bg2))';
      slidesWrap.appendChild(d);

      const dot = document.createElement('button');
      dot.className = 'dot';
      dot.setAttribute('aria-label', 'Slide ' + (i + 1));
      dot.onclick = () => go(i, true);
      dotsWrap.appendChild(dot);
    });

    const slides = Array.from(document.querySelectorAll('.slide'));
    const dots = Array.from(document.querySelectorAll('.dot'));

    let idx = 0,
      timer = null,
      FIRST = true;

    function setCopy(s) {
      // anim out
      [elKick, elTitle, elDesc].forEach(el => el.classList.remove('show'));
      // ganti teks setelah sedikit jeda biar halus
      setTimeout(() => {
        elKick.textContent = s.kicker || 'PTN MAI';
        elTitle.textContent = s.title || 'Selamat Datang! 👋';
        elDesc.textContent = s.desc || 'Kelola data peserta dengan cepat & rapi.';
        [elKick, elTitle, elDesc].forEach(el => el.classList.add('show'));
      }, FIRST ? 0 : 180);
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
      timer = setInterval(next, 5200);
    }
    // init
    go(0);
    restart();

    /* ======== LOGIN (AJAX) ======== */
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

      function showErr(t) {
        msg.textContent = t || 'Login gagal';
        msg.style.display = 'block';
      }

      function setLoading(on) {
        btn.disabled = on;
        btnText.textContent = on ? 'Memproses…' : 'MASUK';
      }

      f.addEventListener('submit', async (e) => {
        e.preventDefault();
        msg.style.display = 'none';
        setLoading(true);
        try {
          const fd = new FormData(f);
          const r = await fetch('api/login.php', {
            method: 'POST',
            body: fd,
            credentials: 'same-origin'
          });
          let j = null;
          try {
            j = await r.json();
          } catch (_) {}
          if (j && j.ok) {
            location.href = 'dashboard.php';
            return;
          }
          if (r.ok) {
            location.href = 'dashboard.php';
            return;
          }
          showErr((j && j.error) || 'Username/Password salah');
        } catch (err) {
          showErr('Tidak bisa menghubungi server.');
        } finally {
          setLoading(false);
        }
      });
    })();
  </script>
</body>

</html>