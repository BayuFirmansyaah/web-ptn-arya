<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <title>Dashboard Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <style>
        :root {
        /* ===== BRAND PALETTE (BIRU) ===== */
        --bg1: #35537A; /* biru tua hangat */
        --bg2: #2C33A8; /* biru indigo untuk background gradasi */
        --grad-main: linear-gradient(90deg, #35537A, #076bbb, #00A9D1, #2C33A8);

        /* Aksen solid (ambil dari titik tengah gradasi) */
        --accent: #076bbb; /* biru utama */
        --accent-2: #2C33A8; /* biru lebih gelap untuk kombinasi gradien */

        /* UI */
        --ink: #0f172a;
        --muted: #64748b;
        --line: #e5e7eb;
        --card: #ffffff;

        /* Shadows disesuaikan warna biru */
        --glow: 0 4px 15px rgba(7, 107, 187, 0.30);
        --glow-strong: 0 6px 20px rgba(7, 107, 187, 0.40);
        }

        /* ===== RESET ===== */
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(135deg, var(--bg1) 0%, var(--bg2) 100%);
        min-height: 100vh;
        color: var(--ink);
        }

        /* ===== LAYOUT ===== */
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }

        .header {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 30px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.10);
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
        background: linear-gradient(45deg, var(--accent), var(--accent-2));
        color: #fff;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: var(--glow);
        }
        .actions a:hover {
        transform: translateY(-2px);
        box-shadow: var(--glow-strong);
        }

        /* ===== NAV / TABS ===== */
        .navbar {
        display: flex;
        background: rgba(255, 255, 255, 0.90);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.10);
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
        color: #fff;
        box-shadow: var(--glow);
        }

        .nav-item:hover:not(.active) {
        background: rgba(7, 107, 187, 0.10);
        color: var(--accent);
        }

        /* ===== FILTERS ===== */
        .filters-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 30px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.10);
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
        box-shadow: 0 0 0 3px rgba(7, 107, 187, 0.15);
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

        /* ===== RESULTS WRAPPER ===== */
        .results-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.10);
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

        /* ===== CARD ===== */
        .card {
        background: var(--card);
        border: 1px solid var(--line);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 15px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        display: block;
        }

        .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        border-color: var(--accent);
        }

        .card.hidden {
        display: none;
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

        /* ===== DETAIL BUTTON ===== */
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
        box-shadow: var(--glow-strong);
        }

        /* ===== ALERTS / STATES ===== */
        .alert {
        background: rgba(255, 193, 7, 0.10);
        border: 1px solid rgba(255, 193, 7, 0.30);
        color: #856404;
        padding: 20px;
        border-radius: 10px;
        text-align: center;
        font-weight: 500;
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

        /* ===== RESPONSIVE ===== */
        @media(max-width: 768px) {
            .container { padding: 15px; }
            .filters { grid-template-columns: 1fr; }
            .topbar { flex-direction: column; gap: 15px; text-align: center; }
            .card-content { flex-direction: column; align-items: flex-start; }
            .filter-actions { justify-content: center; }
            }

            @media(max-width: 480px) {
                .brand { font-size: 24px; }
                .navbar { flex-direction: column; }
                .card-meta { flex-direction: column; gap: 8px; }
                }

    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="topbar">
                <div class="brand">Dashboard Admin</div>
                <div class="actions">
                    <a href="{{ route('dashboard.create') }}" id="btnAddPeserta" class="btn btn-primary">Tambah Peserta</a>
                    <a href="{{ route('logout') }}">Keluar</a>
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
                <input id="searchName" class="input" placeholder="Cari Nama / Jurusan..." />
                <select id="gender" class="input">
                    <option value="">GENDER</option>
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
                </select>
            </div>
            <div class="filter-actions">
                <button type="button" id="btnReset" class="btn btn-reset">Reset</button>
            </div>
        </div>

        <div class="results-container">
            <div class="results-header">
                <div class="results-title">Data Peserta</div>
                <div class="results-count" id="resultsCount">0 peserta</div>
            </div>
            <div id="list">
                @if(isset($students) && count($students) > 0)
                    @foreach($students as $student)
                    <div class="card" 
                         data-program="{{ $student->program == '12CI' ? '12 CERDAS ISTIMEWA' : '12 EXCELLENT' }}"
                         data-name="{{ strtolower($student->nama ?? '') }}"
                         data-jurusan="{{ strtolower($student->jurusan ?? '') }}"
                         data-gender="{{ $student->gender ?? '' }}"
                         data-ma="{{ $student->ma ?? '' }}"
                         data-kelas="{{ $student->kelas ?? '' }}">
                        <div class="card-content">
                            <div class="card-info">
                                <h3>{{ $student->nama ?? '-' }}</h3>
                                <div class="card-meta">
                                    <span>{{ $student->program ?? '-' }}</span>
                                    @if(!empty($student->kelas))
                                    <span>KELAS {{ $student->kelas }}</span>
                                    @endif
                                    @if(!empty($student->ma))
                                    <span>{{ $student->ma }}</span>
                                    @endif
                                    @if(!empty($student->gender))
                                    <span>{{ $student->gender }}</span>
                                    @endif
                                    @if(!empty($student->jurusan) && $student->jurusan !== '-')
                                    <span>{{ $student->jurusan }}</span>
                                    @endif
                                </div>
                            </div>
                            <button class="detail-btn" type="button" onclick="location.href='{{ route('dashboard.detail', ['id' => $student->id]) }}'">Kelola</button>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="empty-state">
                        <h3>Belum ada data peserta</h3>
                        <p>Silakan tambahkan peserta untuk memulai</p>
                        <a href="{{ route('dashboard.create') }}" class="btn detail-btn">Tambah Peserta</a>
                    </div>
                @endif

                <script>
                document.addEventListener('DOMContentLoaded', () => {
                    // Initialize with proper data count
                    const cards = document.querySelectorAll('.card');
                    const totalCards = cards.length;
                    
                    // Update initial count
                    updateResults(totalCards);
                    
                    // If no data, hide empty state from filters
                    if (totalCards === 0) {
                        document.getElementById('emptyState').style.display = 'none';
                    }
                    
                    setProgram('12 EXCELLENT');
                });
                </script>
            </div>
            <div id="emptyState" class="empty-state" style="display: none;">
                <h3>Tidak ada data ditemukan</h3>
                <p>Coba sesuaikan filter pencarian Anda</p>
                <button class="btn btn-reset" onclick="resetFilters()">Reset Filter</button>
            </div>
        </div>
    </div>

    <script>
        // ===============================
        // State & Elements
        // ===============================
        let currentProgram = '12 EXCELLENT';
        const tabExc = document.getElementById('tab-exc');
        const tabCi = document.getElementById('tab-ci');
        const elQ = document.getElementById('searchName');
        const elGender = document.getElementById('gender');
        const elMA = document.getElementById('ma');
        const elKelas = document.getElementById('kelas');
        const elSort = document.getElementById('sort');
        const btnReset = document.getElementById('btnReset');
        const list = document.getElementById('list');
        const resultsCount = document.getElementById('resultsCount');
        const emptyState = document.getElementById('emptyState');

        const DEBOUNCE = 250;
        let typingTimer;

        // ===============================
        // Class Mapping
        // ===============================
        const KELAS_MAP = {
            '12 EXCELLENT': {
                'PUTRA': ['A1', 'B1', 'OVERSEAS', 'KHOS'],
                'PUTRI': ['C1', 'D1', 'OVERSEAS', 'KHOS']
            },
            '12 CERDAS ISTIMEWA': {
                'PUTRA': ['A', 'B', 'C', 'D', 'OVERSEAS', 'KHOS'],
                'PUTRI': ['E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'OVERSEAS', 'KHOS']
            }
        };

        // ===============================
        // Program Switching
        // ===============================
        function setProgram(program) {
            currentProgram = program;
            tabExc.classList.toggle('active', program === '12 EXCELLENT');
            tabCi.classList.toggle('active', program === '12 CERDAS ISTIMEWA');
            tabExc.setAttribute('aria-selected', program === '12 EXCELLENT' ? 'true' : 'false');
            tabCi.setAttribute('aria-selected', program === '12 CERDAS ISTIMEWA' ? 'true' : 'false');
            updateKelasOptions();
            applyFilters();
        }

        function updateKelasOptions() {
            const gender = elGender.value || '';
            elKelas.innerHTML = "<option value=''>KELAS</option>";
            
            if (!gender) return;
            
            const classes = KELAS_MAP[currentProgram]?.[gender] || [];
            classes.forEach(kelas => {
                const option = document.createElement('option');
                option.value = kelas;
                option.textContent = kelas;
                elKelas.appendChild(option);
            });
        }

        // ===============================
        // Filtering & Sorting
        // ===============================
        function applyFilters() {
            const cards = document.querySelectorAll('.card');
            const searchTerm = elQ.value.toLowerCase().trim();
            const selectedGender = elGender.value;
            const selectedMA = elMA.value;
            const selectedKelas = elKelas.value;
            const sortBy = elSort.value;

            let visibleCards = [];

            cards.forEach(card => {
                const cardProgram = card.dataset.program;
                const cardName = card.dataset.name;
                const cardJurusan = card.dataset.jurusan;
                const cardGender = card.dataset.gender;
                const cardMA = card.dataset.ma;
                const cardKelas = card.dataset.kelas;

                let isVisible = true;

                // Filter by program first (must match)
                if (cardProgram !== currentProgram) {
                    isVisible = false;
                } else {
                    // Use OR logic for other filters
                    // If any search criteria is provided, at least one must match
                    const hasSearchTerm = searchTerm && searchTerm !== "";
                    const hasGenderFilter = selectedGender && selectedGender !== "";
                    const hasMAFilter = selectedMA && selectedMA !== "";
                    const hasKelasFilter = selectedKelas && selectedKelas !== "";

                    console.log({
                        hasSearchTerm,
                        hasGenderFilter,
                        hasMAFilter,
                        hasKelasFilter
                    });
                    
                    // If no filters are applied, show all cards for current program
                    if (!hasSearchTerm && !hasGenderFilter && !hasMAFilter && !hasKelasFilter) {
                        isVisible = true;
                    } else {
                        // Check if any filter criteria matches (OR logic)
                        let matchesAny = false;
                        
                        // Check search term (name or jurusan)
                        if (hasSearchTerm) {
                            const searchMatch = cardName.includes(searchTerm) || 
                                            cardJurusan.includes(searchTerm);
                            if (searchMatch) matchesAny = true;
                        }
                        
                        // Check gender
                        if (hasGenderFilter && cardGender === selectedGender) {
                            matchesAny = true;
                        }
                        
                        // Check MA
                        if (hasMAFilter && cardMA === selectedMA) {
                            matchesAny = true;
                        }
                        
                        // Check kelas
                        if (hasKelasFilter && cardKelas === selectedKelas) {
                            matchesAny = true;
                        }
                        
                        isVisible = matchesAny;
                    }
                }

                // Show/hide card
                if (isVisible) {
                    card.classList.remove('hidden');
                    visibleCards.push(card);
                } else {
                    card.classList.add('hidden');
                }
            });

            // Sort visible cards
            if (visibleCards.length > 0) {
            sortCards(visibleCards, sortBy);
            }

            // Update count and empty state
            updateResults(visibleCards.length);
        }

        function sortCards(cards, sortBy) {
            cards.sort((a, b) => {
                const aName = a.querySelector('h3').textContent;
                const bName = b.querySelector('h3').textContent;
                const aKelas = a.dataset.kelas;
                const bKelas = b.dataset.kelas;
                const aMA = a.dataset.ma;
                const bMA = b.dataset.ma;

                switch (sortBy) {
                    case 'name_desc':
                        return bName.localeCompare(aName);
                    case 'kelas_asc':
                        return aKelas.localeCompare(bKelas);
                    case 'ma_asc':
                        return aMA.localeCompare(bMA);
                    case 'name_asc':
                    default:
                        return aName.localeCompare(bName);
                }
            });

            // Reorder DOM elements
            const parent = list;
            cards.forEach(card => {
                parent.appendChild(card);
            });
        }

        function updateResults(count) {
            resultsCount.textContent = `${count} peserta`;
            
            if (count === 0) {
                emptyState.style.display = 'block';
            } else {
                emptyState.style.display = 'none';
            }
        }

        function resetFilters() {
            elQ.value = '';
            elGender.value = '';
            elMA.value = '';
            elKelas.innerHTML = "<option value=''>KELAS</option>";
            elSort.value = 'name_asc';
            setProgram('12 EXCELLENT');
        }

        // ===============================
        // Event Listeners
        // ===============================
        tabExc.onclick = () => setProgram('12 EXCELLENT');
        tabCi.onclick = () => setProgram('12 CERDAS ISTIMEWA');

        tabExc.onkeydown = e => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                setProgram('12 EXCELLENT');
            }
        };

        tabCi.onkeydown = e => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                setProgram('12 CERDAS ISTIMEWA');
            }
        };

        elGender.addEventListener('change', () => {
            updateKelasOptions();
            applyFilters();
        });

        [elMA, elKelas, elSort].forEach(el => {
            el.addEventListener('change', applyFilters);
        });

        elQ.addEventListener('input', () => {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(applyFilters, DEBOUNCE);
        });

        btnReset.addEventListener('click', resetFilters);

        // ===============================
        // Initialize
        // ===============================
        document.addEventListener('DOMContentLoaded', () => {
            setProgram('12 EXCELLENT');
        });
    </script>
</body>

</html>
