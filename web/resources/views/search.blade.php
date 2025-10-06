<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Pencarian Peserta</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <style>
     :root {
     --bg1: #35537A;
     --bg2: #0879CF;
     --bg3: #00BEED;
     --bg4: #343BBF;
     --grad-main: linear-gradient(90deg, var(--bg1), var(--bg2), var(--bg3), var(--bg4));

     --accent: #0879CF;
     --accent-2: #343BBF;
     --ink: #0f172a;
     --muted: #64748b;
     --line: #e5e7eb;
     --card: #ffffff;

     --glow: 0 4px 18px rgba(8, 121, 207, .28);
     --glow-strong: 0 8px 28px rgba(8, 121, 207, .38);
     }

     * { box-sizing: border-box; margin: 0; padding: 0; }

     body {
     font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
     background: var(--grad-main);
     min-height: 100vh;
     color: var(--ink);
     }

     /* ====== Layout ====== */
     .container { max-width: 1200px; margin: 0 auto; padding: 20px; }

     .header {
     background: rgba(255, 255, 255, .95);
     backdrop-filter: blur(10px);
     border-radius: 15px;
     padding: 20px;
     margin-bottom: 30px;
     box-shadow: 0 8px 32px rgba(0, 0, 0, .08);
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
     background: var(--grad-main);
     -webkit-background-clip: text;
     -webkit-text-fill-color: transparent;
     background-clip: text;
     }

     .actions { display: flex; gap: 12px; }
     .actions a {
     padding: 10px 20px;
     border-radius: 25px;
     background: linear-gradient(90deg, var(--accent), var(--accent-2));
     color: #fff;
     text-decoration: none;
     font-weight: 500;
     transition: all .25s ease;
     box-shadow: var(--glow);
     }
     .actions a:hover { transform: translateY(-2px); box-shadow: var(--glow-strong); }

     .search-title {
     text-align: center;
     font-size: 24px;
     font-weight: 600;
     color: var(--ink);
     margin-bottom: 20px;
     }

     /* ====== Tabs (Program) ====== */
     .navbar {
     display: flex;
     background: rgba(255, 255, 255, .9);
     border-radius: 12px;
     overflow: hidden;
     box-shadow: 0 4px 20px rgba(0, 0, 0, .08);
     margin-bottom: 30px;
     }
     .nav-item {
     flex: 1;
     padding: 15px 20px;
     text-align: center;
     cursor: pointer;
     font-weight: 600;
     transition: all .25s ease;
     background: transparent;
     border: none;
     color: var(--muted);
     }
     .nav-item.active {
     background: linear-gradient(90deg, var(--bg2), var(--bg4));
     color: #fff;
     box-shadow: var(--glow);
     }
     .nav-item:hover:not(.active) {
     background: rgba(8, 121, 207, .08);
     color: var(--accent);
     }

     /* ====== Filters ====== */
     .filters-container {
     background: rgba(255, 255, 255, .95);
     backdrop-filter: blur(10px);
     border-radius: 15px;
     padding: 25px;
     margin-bottom: 30px;
     box-shadow: 0 8px 32px rgba(0, 0, 0, .08);
     }
     .filters-title { font-size: 18px; font-weight: 600; margin-bottom: 20px; color: var(--ink); }
     .filters {
     display: flex;
     flex-wrap: nowrap;
     gap: 12px;
     overflow-x: auto;
     -webkit-overflow-scrolling: touch;
     padding-bottom: 6px;
     scrollbar-width: thin;
     }
     .filters::-webkit-scrollbar { height: 8px; }
     .filters::-webkit-scrollbar-thumb { background: #c7d2fe; border-radius: 8px; }
     .input-group { flex: 0 0 auto; min-width: 220px; }

     .input, select {
     width: 100%;
     padding: 12px 16px;
     border: 2px solid var(--line);
     border-radius: 10px;
     background: var(--card);
     font-size: 14px;
     transition: all .2s ease;
     outline: none;
     }
     .input:focus, select:focus {
     border-color: var(--accent);
     box-shadow: 0 0 0 3px rgba(8, 121, 207, .15);
     }

     .filter-actions {
     display: flex;
     gap: 10px;
     justify-content: flex-end;
     flex-wrap: wrap;
     margin-top: 6px;
     }
     .btn {
     padding: 12px 24px;
     border: none;
     border-radius: 10px;
     cursor: pointer;
     font-weight: 500;
     transition: all .2s ease;
     min-width: 100px;
     }
     .btn-reset { background: #f8f9fa; color: var(--muted); border: 2px solid var(--line); }
     .btn-reset:hover { background: var(--line); transform: translateY(-1px); }

     /* ====== Results ====== */
     .results-container {
     background: rgba(255, 255, 255, .95);
     backdrop-filter: blur(10px);
     border-radius: 15px;
     padding: 25px;
     box-shadow: 0 8px 32px rgba(0, 0, 0, .08);
     }
     .results-header {
     display: flex;
     justify-content: space-between;
     align-items: center;
     margin-bottom: 20px;
     padding-bottom: 15px;
     border-bottom: 2px solid #f1f3f4;
     }
     .results-title { font-size: 18px; font-weight: 600; color: var(--ink); }
     .results-count {
     background: linear-gradient(90deg, var(--bg2), var(--bg4));
     color: #fff;
     padding: 6px 12px;
     border-radius: 20px;
     font-size: 12px;
     font-weight: 500;
     }

     /* ====== Card Peserta ====== */
     .card {
     background: var(--card);
     border: 1px solid var(--line);
     border-radius: 12px;
     padding: 16px 20px;
     margin-bottom: 15px;
     transition: all .2s ease;
     box-shadow: 0 2px 8px rgba(0, 0, 0, .05);
     }
     .card:hover {
     transform: translateY(-2px);
     box-shadow: 0 10px 28px rgba(0, 0, 0, .12);
     border-color: rgba(8, 121, 207, .45);
     }
     .card.hidden { display: none; }
     .card-content { display: flex; flex-direction: column; gap: 8px; }

     .card-info h3 {
     font-size: 16px;
     font-weight: 600;
     color: var(--ink);
     margin-bottom: 10px;
     }

     /* Baris meta kiri + tombol kanan */
     .card-row {
     display: flex;
     justify-content: space-between;
     align-items: center;
     gap: 12px;
     }
     .card-meta {
     display: flex;
     flex-wrap: wrap;
     gap: 12px;
     font-size: 14px;
     color: var(--muted);
     }
     .meta-item { display: flex; align-items: center; gap: 6px; white-space: nowrap; }

     /* ====== Tombol Detail (kecil, kalem, pojok kanan) ====== */
     .detail-btn {
     padding: 4px 10px; /* kecil, tidak pajang */
     font-size: 13px;
     background: #fff;
     border: 1px solid var(--accent);
     color: var(--accent);
     border-radius: 12px;
     cursor: pointer;
     transition: all .2s ease;
     margin-left: auto; /* pastikan nempel kanan */
     text-decoration: none;
     }
     .detail-btn:hover { background: var(--accent); color: #fff; }

     /* ====== Alerts / Empty ====== */
     .alert {
     background: rgba(255, 193, 7, .1);
     border: 1px solid rgba(255, 193, 7, .35);
     color: #856404;
     padding: 20px;
     border-radius: 10px;
     text-align: center;
     font-weight: 500;
     }
     .empty-state { text-align: center; padding: 40px 20px; }
     .empty-state h3 { font-size: 18px; color: var(--muted); margin-bottom: 10px; }
     .empty-state p { color: var(--muted); margin-bottom: 20px; }

     /* ====== Responsive ====== */
     @media(max-width: 600px) {
         .filters { flex-wrap: wrap; }
         .card-row { flex-direction: column; align-items: flex-start; gap: 10px; }
         .detail-btn { align-self: flex-end; } /* tetap di kanan saat stack */
         }

    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="topbar">
                <div class="brand">PTN MAI</div>
                <div class="actions">
                    <a href="{{ route('dashboard') }}">📊 Dashboard</a>
                    <a href="{{ route('logout') }}">🚪 Keluar</a>
                </div>
            </div>
            <div class="search-title">Pencarian Data Peserta</div>
        </div>

        <!-- Program tabs -->
        <div class="navbar" role="tablist" aria-label="Pilih Program">
            <button class="nav-item active" id="tab-exc" role="tab" aria-selected="true">🏆 12 EXCELLENT</button>
            <button class="nav-item" id="tab-ci" role="tab" aria-selected="false">⭐ 12 CERDAS ISTIMEWA</button>
        </div>

        <!-- Filters -->
        <div class="filters-container">
            <div class="filters-title">🔍 Filter Pencarian</div>
            <div class="filters">
                <div class="input-group">
                    <input id="searchName" class="input" placeholder="🔍 Cari Nama / ID / Jurusan..." />
                </div>
                <div class="input-group">
                    <select id="gender" class="input">
                        <option value="">👥 PUTRA / PUTRI</option>
                        <option value="PUTRA">👨 PUTRA</option>
                        <option value="PUTRI">👩 PUTRI</option>
                    </select>
                </div>
                <div class="input-group">
                    <select id="ma" class="input">
                        <option value="">🏫 MA</option>
                        <option value="MA01">🏫 MA01</option>
                        <option value="MA03">🏫 MA03</option>
                    </select>
                </div>
                <div class="input-group">
                    <select id="kelas" class="input">
                        <option value="">📚 KELAS</option>
                    </select>
                </div>
                <div class="input-group">
                    <select id="sort" class="input">
                        <option value="name_asc">📝 Nama A–Z</option>
                        <option value="name_desc">📝 Nama Z–A</option>
                        <option value="kelas_asc">📚 Kelas</option>
                        <option value="ma_asc">🏫 MA</option>
                    </select>
                </div>
            </div>
            <div class="filter-actions">
                <button type="button" id="btnReset" class="btn btn-reset">🔄 Reset Filter</button>
            </div>
        </div>

        <div class="results-container">
            <div class="results-header">
                <div class="results-title">📋 Hasil Pencarian</div>
                <div class="results-count" id="resultsCount">{{ count($students) }} peserta</div>
            </div>
            <div id="list">
                @forelse($students as $student)
                <div class="card" 
                         data-program="{{ $student->program ?? '' }}" 
                         data-nama="{{ strtolower($student->nama ?? '') }}" 
                         data-id="{{ strtolower($student->id ?? '') }}" 
                         data-jurusan="{{ strtolower($student->jurusan ?? '') }}" 
                         data-gender="{{ $student->gender ?? '' }}" 
                         data-ma="{{ $student->ma ?? '' }}" 
                         data-kelas="{{ $student->kelas ?? '' }}">
                    <div class="card-content">
                        <div class="card-info">
                            <h3>👤 {{ $student->nama ?? '-' }}</h3>
                            <div class="card-row">
                                <div class="card-meta">
                                    <span class="meta-item">🏆 {{ $student->program ?? '-' }}</span>
                                    <span class="meta-item">📚 Kelas {{ $student->kelas ?? '-' }}</span>
                                    <span class="meta-item">🏫 {{ $student->ma ?? '-' }}</span>
                                    <span class="meta-item">🎓 {{ $student->jurusan ?? '-' }}</span>
                                </div>
                                <a href="{{ route('search.show', ['id' => $student->id]) }}" class="detail-btn">
                                    👁️ Detail
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="empty-state">
                    <h3>🔍 Tidak ada data peserta</h3>
                    <p>Belum ada data peserta yang tersedia</p>
                </div>
                @endforelse
            </div>
            <div id="statusBox"></div>
        </div>
    </div>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // ====== State ======
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
        const resultsCount = document.getElementById('resultsCount');

        const DEBOUNCE = 250;
        let typingTimer;
        const STORE_KEY = 'PUB_FILTERS_V2';

        // Get all cards
        const allCards = document.querySelectorAll('.card');
        
        // Kelas mapping
        const kelasOptions = {
            '12EXC': {
                'PUTRA': ['A1', 'B1', 'OVERSEAS', 'KHOS'],
                'PUTRI': ['C1', 'D1', 'OVERSEAS', 'KHOS']
            },
            '12CI': {
                'PUTRA': ['A', 'B', 'C', 'D', 'OVERSEAS', 'KHOS'],
                'PUTRI': ['E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'OVERSEAS', 'KHOS']
            }
        };

        function setProgram(p) {
            program = p;
            tabExc.classList.toggle('active', p === '12EXC');
            tabCi.classList.toggle('active', p === '12CI');
            tabExc.setAttribute('aria-selected', p === '12EXC' ? 'true' : 'false');
            tabCi.setAttribute('aria-selected', p === '12CI' ? 'true' : 'false');
            updateKelasOptions();
            saveFilters();
            filterCards();
        }
        tabExc.onclick = () => setProgram('12EXC');
        tabCi.onclick = () => setProgram('12CI');

        function updateKelasOptions() {
            const g = elGender.value || '';
            elKelas.innerHTML = "<option value=''>📚 KELAS</option>";
            if (!g) return;
            const ops = (kelasOptions[program] && kelasOptions[program][g]) ? kelasOptions[program][g] : [];
            for (const k of ops) {
                const op = document.createElement('option');
                op.value = k;
                op.textContent = `📚 ${k}`;
                elKelas.appendChild(op);
            }
        }

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
                updateKelasOptions();
                elMA.value = s.ma || '';
                if (s.kelas) {
                    const ok = [...elKelas.options].some(o => o.value === s.kelas);
                    if (ok) elKelas.value = s.kelas;
                }
                elSort.value = s.sort || 'name_asc';
            } catch {}
        }

        function filterCards() {
            const query = elQ.value.toLowerCase().trim();
            const gender = elGender.value;
            const ma = elMA.value;
            const kelas = elKelas.value;
            const sort = elSort.value;

            let visibleCards = [];

            allCards.forEach(card => {
                const cardProgram = card.dataset.program;
                const cardNama = card.dataset.nama;
                const cardId = card.dataset.id;
                const cardJurusan = card.dataset.jurusan;
                const cardGender = card.dataset.gender;
                const cardMA = card.dataset.ma;
                const cardKelas = card.dataset.kelas;

                let show = true;

                // Filter by program
                if (program && cardProgram !== program) {
                    show = false;
                }

                // Filter by search query
                if (query && !cardNama.includes(query) && !cardId.includes(query) && !cardJurusan.includes(query)) {
                    show = false;
                }

                // Filter by gender
                if (gender && cardGender !== gender) {
                    show = false;
                }

                // Filter by MA
                if (ma && cardMA !== ma) {
                    show = false;
                }

                // Filter by kelas
                if (kelas && cardKelas !== kelas) {
                    show = false;
                }

                if (show) {
                    card.classList.remove('hidden');
                    visibleCards.push(card);
                } else {
                    card.classList.add('hidden');
                }
            });

            // Sort visible cards
            if (sort) {
                visibleCards.sort((a, b) => {
                    switch (sort) {
                        case 'name_asc':
                            return a.dataset.nama.localeCompare(b.dataset.nama);
                        case 'name_desc':
                            return b.dataset.nama.localeCompare(a.dataset.nama);
                        case 'kelas_asc':
                            return a.dataset.kelas.localeCompare(b.dataset.kelas);
                        case 'ma_asc':
                            return a.dataset.ma.localeCompare(b.dataset.ma);
                        default:
                            return 0;
                    }
                });

                // Reorder cards in DOM
                visibleCards.forEach(card => {
                    list.appendChild(card);
                });
            }

            // Update results count
            resultsCount.textContent = `${visibleCards.length} peserta`;

            // Show empty state if no results
            const emptyState = document.querySelector('.empty-state');
            if (visibleCards.length === 0 && !emptyState) {
                const empty = document.createElement('div');
                empty.className = 'empty-state';
                empty.innerHTML = `
                    <h3>🔍 Tidak ada data ditemukan</h3>
                    <p>Coba ubah filter pencarian Anda</p>
                    <button class="btn btn-reset" onclick="document.getElementById('btnReset').click()">
                        🔄 Reset Semua Filter
                    </button>
                `;
                list.appendChild(empty);
            } else if (visibleCards.length > 0 && emptyState) {
                emptyState.remove();
            }
        }

        elGender.addEventListener('change', () => {
            updateKelasOptions();
            saveFilters();
            filterCards();
        });
        
        [elMA, elKelas, elSort].forEach(el => {
            el.addEventListener('change', () => {
                saveFilters();
                filterCards();
            });
        });
        
        elQ.addEventListener('input', () => {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(() => {
                saveFilters();
                filterCards();
            }, DEBOUNCE);
        });
        
        btnReset.addEventListener('click', () => {
            elQ.value = '';
            elGender.value = '';
            elMA.value = '';
            elKelas.innerHTML = "<option value=''>📚 KELAS</option>";
            elSort.value = 'name_asc';
            setProgram('12EXC');
            saveFilters();
            filterCards();
        });

        // Initialize
        updateKelasOptions();

        // Initialize
        (function () {
            restoreFilters();
            filterCards();
        })();
    </script>

    {{-- Session notification script --}}
    <script>
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        @elseif (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ session('error') }}',
                confirmButtonText: 'OK',
                confirmButtonColor: '#076bbb'
            });
        @endif
    </script>
</body>

</html>