<?php
require __DIR__.'/lib/auth.php';
require_admin();

$isSuper = function_exists('is_super_admin') ? is_super_admin() : (
  (strtolower($_SESSION['admin']['role'] ?? 'admin') === 'super') ||
  (strtoupper($_SESSION['admin']['scope']['program'] ?? '') === 'ALL')
);
?>
<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8" />
  <title>Dashboard Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <style>
    * {
      box-sizing: border-box
    }

    body {
      font-family: Arial, Helvetica, sans-serif;
      background: #f9f9f9;
      margin: 18px;
      color: #111
    }

    .topbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      max-width: 1000px;
      margin: 0 auto 16px
    }

    .topbar .brand {
      font-weight: 700
    }

    .topbar .actions {
      display: flex;
      gap: 10px;
      flex-wrap: wrap
    }

    .topbar a {
      display: inline-block;
      padding: 8px 12px;
      border: 1px solid #d1d5db;
      border-radius: 8px;
      background: #fff;
      color: #2563eb;
      text-decoration: none;
      font-size: 14px
    }

    .topbar a:hover {
      background: #f3f4f6
    }

    .navbar {
      display: flex;
      border: 1px solid #ccc;
      border-radius: 6px;
      overflow: hidden;
      max-width: 460px;
      margin: 0 auto;
      background: #fff
    }

    .nav-item {
      flex: 1;
      padding: 12px;
      text-align: center;
      cursor: pointer;
      font-weight: bold;
      user-select: none
    }

    .nav-item.active {
      background: #00c04b;
      color: #fff
    }

    .filters {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      margin: 18px auto 10px;
      max-width: 1000px
    }

    .filters .input {
      padding: 10px;
      border-radius: 6px;
      border: 1px solid #ccc;
      background: #fff;
      flex: 1;
      min-width: 140px
    }

    .btn {
      padding: 10px 12px;
      border: 1px solid #d1d5db;
      border-radius: 8px;
      background: #fff;
      cursor: pointer
    }

    .btn:hover {
      background: #f3f4f6
    }

    #list {
      max-width: 1000px;
      margin: 0 auto
    }

    .card {
      border: 1px solid #ddd;
      border-radius: 8px;
      background: #fff;
      padding: 14px;
      margin: 10px auto;
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: .75rem
    }

    .card strong {
      font-size: 15px;
      display: block
    }

    .muted {
      color: #6b7280;
      font-size: 13px;
      margin-top: 2px
    }

    .detail-btn {
      padding: 6px 14px;
      background: #e5e5e5;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-size: 13px
    }

    .detail-btn:hover {
      background: #ccc
    }

    .alert {
      max-width: 1000px;
      margin: 8px auto;
      padding: 10px;
      border-radius: 8px;
      background: #fff3cd;
      border: 1px solid #ffe69c;
      color: #7a5d00
    }

    <blade media|%20(max-width%3A480px)%7B%0D>.filters {
      flex-direction: column
    }

    .filters .input {
      width: 100%
    }
    }
  </style>
</head>

<body>

  <div class="topbar">
    <div class="brand">Dashboard Admin</div>
    <div class="actions">
      <?php if ($isSuper): ?>
      <a href="tools/upload_json.php">Upload JSON Peserta</a>
      <?php endif; ?>
      <a href="api/logout.php">Keluar</a>
    </div>
  </div>

  <!-- Program tabs -->
  <div class="navbar" role="tablist" aria-label="Pilih Program">
    <div class="nav-item active" id="tab-exc" role="tab" aria-selected="true" tabindex="0">12 EXCELLENT</div>
    <div class="nav-item" id="tab-ci" role="tab" aria-selected="false" tabindex="0">12 CERDAS ISTIMEWA</div>
  </div>

  <!-- Filters -->
  <div class="filters">
    <input id="searchName" class="input" placeholder="Cari Nama / ID / Jurusan..." />
    <select id="gender" class="input">
      <option value="">PUTRA / PUTRI</option>
      <option value="PUTRA">PUTRA</option>
      <option value="PUTRI">PUTRI</option>
    </select>
    <select id="ma" class="input">
      <option value="">MA</option>
      <option value="MA01">MA 01</option>
      <option value="MA03">MA 03</option>
    </select>
    <select id="kelas" class="input">
      <option value="">KELAS</option>
    </select>

    <!-- NEW: Sort + Reset -->
    <select id="sort" class="input" style="min-width:150px">
      <option value="name_asc">Nama A–Z</option>
      <option value="name_desc">Nama Z–A</option>
      <option value="kelas_asc">Kelas</option>
      <option value="ma_asc">MA</option>
      <option value="updated_desc">Terbaru diubah</option>
    </select>
    <button type="button" id="btnReset" class="btn">Reset</button>
  </div>

  <div id="list"></div>
  <div id="statusBox"></div>

  <script>
    // ===============================
    // State & Elements
    // ===============================
    let program = '12EXC';
    const tabExc = document.getElementById('tab-exc');
    const tabCi = document.getElementById('tab-ci');
    const elQ = document.getElementById('searchName');
    const elGender = document.getElementById('gender');
    const elMA = document.getElementById('ma');
    const elKelas = document.getElementById('kelas');
    const elSort = document.getElementById('sort');
    const btnReset = document.getElementById('btnReset');
    const list = document.getElementById('list');
    const statusBox = document.getElementById('statusBox');

    const DEBOUNCE = 250;
    let typingTimer;
    const STORE_KEY = 'ADM_FILTERS_V2';

    // ===============================
    // Helpers
    // ===============================
    function rangeLetters(a, b) {
      const out = [];
      for (let c = a.charCodeAt(0); c <= b.charCodeAt(0); c++) out.push(String.fromCharCode(c));
      return out;
    }
    const KELAS_MAP = {
      '12EXC': {
        'PUTRA': ['A1', 'B1'],
        'PUTRI': ['C1', 'D1']
      },
      '12CI': {
        'PUTRA': rangeLetters('A', 'D'),
        'PUTRI': rangeLetters('E', 'M')
      }
    };

    function setProgram(p) {
      program = p;
      tabExc.classList.toggle('active', p === '12EXC');
      tabCi.classList.toggle('active', p === '12CI');
      tabExc.setAttribute('aria-selected', p === '12EXC' ? 'true' : 'false');
      tabCi.setAttribute('aria-selected', p === '12CI' ? 'true' : 'false');
      updateKelas();
      saveFilters();
      loadList();
    }
    tabExc.onclick = () => setProgram('12EXC');
    tabCi.onclick = () => setProgram('12CI');
    tabExc.onkeydown = e => {
      if (e.key === 'Enter' || e.key === ' ') setProgram('12EXC');
    };
    tabCi.onkeydown = e => {
      if (e.key === 'Enter' || e.key === ' ') setProgram('12CI');
    };

    function updateKelas() {
      const g = elGender.value || '';
      elKelas.innerHTML = "<option value=''>KELAS</option>";
      if (!g) return;
      const ops = (KELAS_MAP[program] && KELAS_MAP[program][g]) ? KELAS_MAP[program][g] : [];
      for (const k of ops) {
        const opt = document.createElement('option');
        opt.value = k;
        opt.textContent = k;
        elKelas.appendChild(opt);
      }
    }

    // remember filters
    function saveFilters() {
      const data = {
        program,
        q: elQ.value || '',
        gender: elGender.value || '',
        ma: elMA.value || '',
        kelas: elKelas.value || '',
        sort: elSort.value || 'name_asc'
      };
      localStorage.setItem(STORE_KEY, JSON.stringify(data));
    }

    function restoreFilters() {
      try {
        const raw = localStorage.getItem(STORE_KEY);
        if (!raw) return;
        const s = JSON.parse(raw);
        setProgram(s.program || '12EXC');
        elQ.value = s.q || '';
        elGender.value = s.gender || '';
        updateKelas();
        elMA.value = s.ma || '';
        if (s.kelas) {
          const ok = [...elKelas.options].some(o => o.value === s.kelas);
          if (ok) elKelas.value = s.kelas;
        }
        elSort.value = s.sort || 'name_asc';
      } catch {}
    }

    // events
    [elGender, elMA, elKelas, elSort].forEach(el => {
      el.addEventListener('change', () => {
        saveFilters();
        loadList();
      });
    });
    elQ.addEventListener('input', () => {
      clearTimeout(typingTimer);
      typingTimer = setTimeout(() => {
        saveFilters();
        loadList();
      }, DEBOUNCE);
    });
    btnReset.addEventListener('click', () => {
      elQ.value = '';
      elGender.value = '';
      elMA.value = '';
      elKelas.innerHTML = "<option value=''>KELAS</option>";
      elSort.value = 'name_asc';
      setProgram('12EXC');
      saveFilters();
      loadList();
    });

    // cancel in-flight
    let inFlight;
    async function loadList() {
      statusBox.innerHTML = '';
      list.innerHTML = '';
      const loading = document.createElement('div');
      loading.className = 'alert';
      loading.textContent = 'Memuat data...';
      statusBox.appendChild(loading);

      const u = new URL('api/peserta_list_admin.php', location.href);
      u.searchParams.set('program', program);
      if (elGender.value) u.searchParams.set('gender', elGender.value);
      if (elMA.value) u.searchParams.set('ma', elMA.value);
      if (elKelas.value) u.searchParams.set('kelas', elKelas.value);
      if (elQ.value.trim()) u.searchParams.set('q', elQ.value.trim());
      if (elSort.value) u.searchParams.set('sort', elSort.value);
      u.searchParams.set('page', '1');
      u.searchParams.set('limit', '200');

      if (inFlight) inFlight.abort();
      inFlight = new AbortController();

      try {
        const r = await fetch(u, {
          signal: inFlight.signal
        });
        const j = await r.json();
        statusBox.innerHTML = '';
        const rows = Array.isArray(j) ? j : (j.items || []);
        render(rows);
      } catch (e) {
        if (e.name === 'AbortError') return;
        statusBox.innerHTML = '<div class="alert">Gagal memuat data.</div>';
      }
    }

    function render(items) {
      list.innerHTML = '';
      if (!items.length) {
        const empty = document.createElement('div');
        empty.className = 'alert';
        empty.innerHTML =
          'Tidak ada data. <button class="btn" onclick="document.getElementById(\'btnReset\').click()">Hapus semua filter</button>';
        list.appendChild(empty);
        return;
      }
      for (const d of items) {
        const card = document.createElement('div');
        card.className = 'card';
        card.innerHTML = `
          <div class="card-main">
            <strong>${d.nama||'-'}</strong>
            <div class="muted">${d.program||'-'} | KELAS ${d.kelas||'-'} | ${d.ma||'-'}${d.jurusan ? ' | '+d.jurusan : ''}</div>
          </div>
          <button class="detail-btn" type="button" onclick="location.href='detail.php?id=${encodeURIComponent(d.id)}'">Kelola</button>
        `;
        list.appendChild(card);
      }
    }

    // init
    (function () {
      restoreFilters(); // ini akan memanggil setProgram() → updateKelas()
      // kalau tidak ada storage sebelumnya, panggil manual:
      if (!localStorage.getItem(STORE_KEY)) {
        updateKelas();
        loadList();
      }
    })();
  </script>
</body>

</html>