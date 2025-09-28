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
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, var(--bg1) 0%, var(--bg2) 100%);
      min-height: 100vh;
      color: var(--ink);
    }

    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px;
    }

    .header {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border-radius: 15px;
      padding: 20px;
      margin-bottom: 30px;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .topbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }

    .brand {
      font-size: 28px;
      font-weight: 700;
      background: linear-gradient(45deg, var(--bg1), var(--bg2));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .actions {
      display: flex;
      gap: 12px;
    }

    .actions a {
      padding: 10px 20px;
      border-radius: 25px;
      background: linear-gradient(45deg, var(--accent), var(--accent-2));
      color: white;
      text-decoration: none;
      font-weight: 500;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(32, 191, 0, 0.3);
    }

    .actions a:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(32, 191, 0, 0.4);
    }

    .navbar {
      display: flex;
      background: rgba(255, 255, 255, 0.9);
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      margin-bottom: 30px;
      max-width: 600px;
      margin-left: auto;
      margin-right: auto;
    }

    .nav-item {
      flex: 1;
      padding: 15px 20px;
      text-align: center;
      cursor: pointer;
      font-weight: 600;
      transition: all 0.3s ease;
      background: transparent;
      border: none;
      color: var(--muted);
    }

    .nav-item.active {
      background: linear-gradient(45deg, var(--accent), var(--accent-2));
      color: white;
      box-shadow: 0 4px 15px rgba(32, 191, 0, 0.3);
    }

    .nav-item:hover:not(.active) {
      background: rgba(32, 191, 0, 0.1);
      color: var(--accent);
    }

    .filters-container {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border-radius: 15px;
      padding: 25px;
      margin-bottom: 30px;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .filters-title {
      font-size: 18px;
      font-weight: 600;
      margin-bottom: 20px;
      color: var(--ink);
    }

    .filters {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 15px;
      margin-bottom: 20px;
    }

    .input, select {
      width: 100%;
      padding: 12px 16px;
      border: 2px solid var(--line);
      border-radius: 10px;
      background: var(--card);
      font-size: 14px;
      transition: all 0.3s ease;
      outline: none;
    }

    .input:focus, select:focus {
      border-color: var(--accent);
      box-shadow: 0 0 0 3px rgba(32, 191, 0, 0.1);
    }

    .filter-actions {
      display: flex;
      gap: 10px;
      justify-content: flex-end;
      flex-wrap: wrap;
    }

    .btn {
      padding: 12px 24px;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      font-weight: 500;
      transition: all 0.3s ease;
      min-width: 100px;
    }

    .btn-reset {
      background: #f8f9fa;
      color: var(--muted);
      border: 2px solid var(--line);
    }

    .btn-reset:hover {
      background: var(--line);
      transform: translateY(-1px);
    }

    .results-container {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border-radius: 15px;
      padding: 25px;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .results-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
      padding-bottom: 15px;
      border-bottom: 2px solid #f1f3f4;
    }

    .results-title {
      font-size: 18px;
      font-weight: 600;
      color: var(--ink);
    }

    .results-count {
      background: linear-gradient(45deg, var(--accent), var(--accent-2));
      color: white;
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 500;
    }

    .card {
      background: var(--card);
      border: 1px solid var(--line);
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 15px;
      transition: all 0.3s ease;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .card:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
      border-color: var(--accent);
    }

    .card-content {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 15px;
    }

    .card-info h3 {
      font-size: 16px;
      font-weight: 600;
      color: var(--ink);
      margin-bottom: 8px;
    }

    .card-meta {
      color: var(--muted);
      font-size: 14px;
      display: flex;
      flex-wrap: wrap;
      gap: 15px;
    }

    .detail-btn {
      padding: 10px 20px;
      background: linear-gradient(45deg, var(--accent), var(--accent-2));
      color: white;
      border: none;
      border-radius: 20px;
      cursor: pointer;
      font-weight: 500;
      transition: all 0.3s ease;
      min-width: 80px;
      font-size: 14px;
    }

    .detail-btn:hover {
      transform: translateY(-1px);
      box-shadow: 0 4px 15px rgba(32, 191, 0, 0.4);
    }

    .alert {
      background: rgba(255, 193, 7, 0.1);
      border: 1px solid rgba(255, 193, 7, 0.3);
      color: #856404;
      padding: 20px;
      border-radius: 10px;
      text-align: center;
      font-weight: 500;
    }

    .loading {
      background: rgba(32, 191, 0, 0.1);
      border: 1px solid rgba(32, 191, 0, 0.3);
      color: var(--accent-2);
    }

    .empty-state {
      text-align: center;
      padding: 40px 20px;
    }

    .empty-state h3 {
      font-size: 18px;
      color: var(--muted);
      margin-bottom: 10px;
    }

    .empty-state p {
      color: var(--muted);
      margin-bottom: 20px;
    }

    @media (max-width: 768px) {
      .container {
        padding: 15px;
      }
      
      .filters {
        grid-template-columns: 1fr;
      }
      
      .topbar {
        flex-direction: column;
        gap: 15px;
        text-align: center;
      }
      
      .card-content {
        flex-direction: column;
        align-items: flex-start;
      }
      
      .filter-actions {
        justify-content: center;
      }
    }

    @media (max-width: 480px) {
      .brand {
        font-size: 24px;
      }
      
      .navbar {
        flex-direction: column;
      }
      
      .card-meta {
        flex-direction: column;
        gap: 8px;
      }
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="header">
      <div class="topbar">
        <div class="brand">Dashboard Admin</div>
        <div class="actions">
          <?php if ($isSuper): ?>
           <a href="tools/add_peserta.php" id="btnAddPeserta" class="btn btn-primary">Tambah Peserta</a>
          <?php endif; ?>
          <a href="api/logout.php">Keluar</a>
        </div>
      </div>
    </div>

    <!-- Program tabs -->
    <div class="navbar" role="tablist" aria-label="Pilih Program">
      <div class="nav-item active" id="tab-exc" role="tab" aria-selected="true" tabindex="0">12 EXCELLENT</div>
      <div class="nav-item" id="tab-ci" role="tab" aria-selected="false" tabindex="0">12 CERDAS ISTIMEWA</div>
    </div>

    <!-- Filters -->
    <div class="filters-container">
      <div class="filters-title">Filter Pencarian</div>
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
        <select id="sort" class="input" style="min-width:150px">
          <option value="name_asc">Nama A–Z</option>
          <option value="name_desc">Nama Z–A</option>
          <option value="kelas_asc">Kelas</option>
          <option value="ma_asc">MA</option>
          <option value="updated_desc">Terbaru diubah</option>
        </select>
      </div>
      <div class="filter-actions">
        <button type="button" id="btnReset" class="btn btn-reset">Reset</button>
      </div>
    </div>

    <div class="results-container">
      <div id="list"></div>
      <div id="statusBox"></div>
    </div>
  </div>

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
    elGender.addEventListener('change', () => {
      updateKelas();
      saveFilters();
      loadList();
    });
    [elMA, elKelas, elSort].forEach(el => {
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
      loading.className = 'alert loading';
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
        empty.className = 'empty-state';
        empty.innerHTML = `
          <h3>Tidak ada data ditemukan</h3>
          <p>Coba sesuaikan filter pencarian Anda</p>
          <button class="btn btn-reset" onclick="document.getElementById('btnReset').click()">Reset Filter</button>
        `;
        list.appendChild(empty);
        return;
      }
      for (const d of items) {
        const card = document.createElement('div');
        card.className = 'card';
        card.innerHTML = `
          <div class="card-content">
            <div class="card-info">
              <h3>${d.nama||'-'}</h3>
              <div class="card-meta">
                <span>${d.program||'-'}</span>
                <span>KELAS ${d.kelas||'-'}</span>
                <span>${d.ma||'-'}</span>
                ${d.jurusan ? `<span>${d.jurusan}</span>` : ''}
              </div>
            </div>
            <button class="detail-btn" type="button" onclick="location.href='detail.php?id=${encodeURIComponent(d.id)}'">Kelola</button>
          </div>
        `;
        list.appendChild(card);
      }
    }

    // init
    (function () {
      restoreFilters(); // ini akan memanggil setProgram() → updateKelas()
      loadList(); // Always load data after restoring filters
    })();
  </script>
</body>

</html>