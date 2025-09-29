<?php
$id = $_GET['id'] ?? '';
if ($id === '') {
  http_response_code(400);
  echo "ID peserta tidak valid";
  exit;
}
?>
<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <title>Detail Peserta</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="x-peserta-id" content="<?= htmlspecialchars($id, ENT_QUOTES, 'UTF-8') ?>">

  <style>
    :root {
      /* === Brand Gradient (Biru) === */
      --g1: #35537A;
      --g2: #0879CF;
      --g3: #00BEED;
      --g4: #343BBF;

      /* Turunan warna (aksen & bayangan) */
      --accent: var(--g2);
      --accent-2: var(--g4);
      --glow: rgba(8, 121, 207, 0.28);

      /* Neutrals */
      --ink: #0f172a;
      --muted: #64748b;
      --line: #e5e7eb;
      --card: #ffffff;

      /* Surface soft */
      --soft: #f8fafc;
    }

    /* Reset */
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    /* ===== Page ===== */
    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      background: linear-gradient(90deg, var(--g1), var(--g2), var(--g3), var(--g4));
      min-height: 100vh;
      color: var(--ink);
      padding: 20px;
    }

    .container {
      max-width: 1000px;
      margin: 0 auto;
    }

    .header {
      margin-bottom: 32px;
    }

    /* ===== Back button (subtle glass) ===== */
    .back-btn {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      color: rgba(255, 255, 255, 0.95);
      text-decoration: none;
      font-weight: 500;
      padding: 12px 20px;
      border-radius: 12px;
      background: rgba(255, 255, 255, 0.10);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.22);
      transition: transform .25s ease, background .25s ease, border-color .25s ease;
    }

    .back-btn:hover {
      background: rgba(255, 255, 255, 0.18);
      transform: translateY(-2px);
    }

    .back-btn:focus-visible {
      outline: 2px solid #fff;
      outline-offset: 2px;
      border-color: #fff;
    }

    /* ===== Cards / Sections ===== */
    .profile-section,
    .files-section {
      background: var(--card);
      border-radius: 20px;
      padding: 32px;
      margin-bottom: 24px;
      box-shadow: 0 20px 40px rgba(16, 24, 40, 0.08);
      border: 1px solid rgba(255, 255, 255, 0.85);
    }

    /* ===== Profile (NYAMPING) ===== */
    .profile-header {
      display: flex;
      flex-direction: row;
      /* force horizontal */
      align-items: center;
      gap: 24px;
      margin-bottom: 24px;
      flex-wrap: nowrap;
      /* cegah turun baris */
    }

    .avatar {
      width: 80px;
      height: 80px;
      border-radius: 20px;
      background: linear-gradient(135deg, var(--g2), var(--g4));
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 28px;
      font-weight: 700;
      box-shadow: 0 8px 32px var(--glow);
      flex-shrink: 0;
      /* jangan mengecil */
    }

    .profile-info {
      flex: 1;
      /* ambil sisa ruang */
      min-width: 0;
      /* agar teks wrap rapi */
    }

    .profile-info h1 {
      font-size: 28px;
      font-weight: 700;
      color: var(--ink);
      margin-bottom: 8px;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }

    .profile-info .subtitle {
      color: var(--muted);
      font-size: 16px;
      margin-bottom: 8px;
    }

    /* ==== DETAIL-ITEM: kini horizontal tepat di bawah nama ==== */
    .profile-details {
      /* semula grid -> jadikan flex rw horizontal */
      display: flex;
      flex-wrap: wrap;
      gap: 12px 16px;
      margin-top: 4px;
    }

    /* Item detail: tetap pakai gaya lama (tidak dihapus) */
    .detail-item {
      background: var(--soft);
      padding: 16px;
      border-radius: 12px;
      border-left: 4px solid var(--g2);
      display: inline-flex;
      align-items: center;
      gap: 10px;
    }

    .detail-label {
      font-size: 12px;
      font-weight: 600;
      color: var(--muted);
      text-transform: uppercase;
      letter-spacing: .5px;
      margin-bottom: 0;
      /* nolkan margin agar rapat horizontal */
    }

    .detail-value {
      font-size: 16px;
      font-weight: 600;
      color: var(--ink);
    }

    /* ===== Section Title ===== */
    .section-title {
      font-size: 24px;
      font-weight: 700;
      color: var(--ink);
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .section-icon {
      width: 32px;
      height: 32px;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #fff;
      font-size: 16px;
      background: linear-gradient(135deg, var(--g2), var(--g4));
      box-shadow: 0 6px 18px var(--glow);
    }

    /* ===== File list ===== */
    .files-grid {
      display: grid;
      gap: 16px;
    }

    .file-card {
      background: var(--soft);
      border: 1px solid var(--line);
      border-radius: 16px;
      padding: 20px;
      transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
    }

    .file-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 12px 32px rgba(16, 24, 40, 0.10);
      border-color: rgba(8, 121, 207, 0.25);
    }

    .file-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      gap: 16px;
      margin-bottom: 16px;
    }

    .file-info {
      flex: 1;
      min-width: 0;
    }

    .file-name {
      font-size: 16px;
      font-weight: 600;
      color: var(--ink);
      margin-bottom: 4px;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }

    .file-path {
      font-size: 14px;
      color: var(--muted);
      word-break: break-all;
    }

    /* ===== Buttons ===== */
    .btn {
      padding: 10px 20px;
      border: none;
      border-radius: 10px;
      font-weight: 600;
      font-size: 14px;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      cursor: pointer;
      transition: transform .2s ease, box-shadow .2s ease, background .2s ease, color .2s ease;
    }

    /* Primary: gradien biru */
    .btn-primary {
      color: #fff;
      background: linear-gradient(90deg, var(--g1), var(--g2), var(--g3), var(--g4));
      box-shadow: 0 4px 16px var(--glow);
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(8, 121, 207, 0.35);
    }

    .btn-primary:focus-visible {
      outline: 2px solid var(--g3);
      outline-offset: 2px;
    }

    /* Secondary: netral dengan hover biru lembut */
    .btn-secondary {
      background: #fff;
      color: var(--ink);
      border: 1px solid var(--line);
    }

    .btn-secondary:hover {
      background: var(--soft);
      transform: translateY(-2px);
      border-color: rgba(8, 121, 207, 0.25);
    }

    .btn-secondary:focus-visible {
      outline: 2px solid var(--g2);
      outline-offset: 2px;
    }

    /* ===== Empty / Loading ===== */
    .empty-state {
      text-align: center;
      padding: 40px 20px;
      color: var(--muted);
      background: var(--soft);
      border-radius: 16px;
      border: 2px dashed var(--line);
    }

    .empty-icon {
      font-size: 48px;
      margin-bottom: 16px;
    }

    .loading {
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 40px;
      color: var(--muted);
    }

    .spinner {
      width: 24px;
      height: 24px;
      border: 2px solid var(--line);
      border-top: 2px solid var(--g2);
      border-radius: 50%;
      animation: spin 1s linear infinite;
      margin-right: 12px;
    }

    @keyframesspin{ 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } } /* dibiarkan sesuai kode asli */

    /* ===== Responsive (profil tetap nyamping) ===== */
    <blade media|%20(max-width%3A%20768px)%20%7B%0D>.profile-header {
      flex-direction: row;
      /* tetap horizontal di mobile */
      align-items: center;
      gap: 16px;
    }

    .profile-info h1 {
      font-size: 22px;
    }

    .file-actions {
      justify-content: center;
    }

    /* baris ini dibiarkan; tidak mempengaruhi karena .profile-details sekarang flex */
    .profile-details {
      /* grid-template-columns: 1fr; */
    }
    }

    /* ===== Small polish: scrollbar ===== */
    * {
      scrollbar-width: thin;
      scrollbar-color: rgba(8, 121, 207, 0.6) rgba(0, 0, 0, 0.06);
    }

    *::-webkit-scrollbar {
      height: 8px;
      width: 8px;
    }

    *::-webkit-scrollbar-thumb {
      background: linear-gradient(180deg, var(--g2), var(--g4));
      border-radius: 8px;
    }

    *::-webkit-scrollbar-track {
      background: rgba(0, 0, 0, 0.06);
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="header">
      <a href="pencarian.php" class="back-btn">
        ← Kembali ke Pencarian
      </a>
    </div>

    <!-- Profile Section -->
    <div class="profile-section">
      <div class="profile-header">
        <div class="avatar" id="avatar">?</div>
        <div class="profile-info">
          <h1 id="pNama">Memuat...</h1>
          <div class="subtitle">Detail Peserta</div>

          <!-- === DIPINDAH KE SINI & JADI HORIZONTAL === -->
          <div class="profile-details" id="profileDetails">
            <div class="loading">
              <div class="spinner"></div>
              Memuat informasi peserta...
            </div>
          </div>
          <!-- =========================================== -->

        </div>
      </div>
    </div>

    <!-- Sertifikat Section -->
    <div class="files-section">
      <h2 class="section-title">
        <div class="section-icon">📜</div>
        Sertifikat
      </h2>
      <div id="sertifikatContent">
        <div class="loading">
          <div class="spinner"></div>
          Memuat sertifikat...
        </div>
      </div>
    </div>

    <!-- Berkas Section -->
    <div class="files-section">
      <h2 class="section-title">
        <div class="section-icon">📁</div>
        Berkas
      </h2>
      <div id="berkasContent">
        <div class="loading">
          <div class="spinner"></div>
          Memuat berkas...
        </div>
      </div>
    </div>
  </div>

  <script>
    const id = document.querySelector('meta[name="x-peserta-id"]').content;

    const pNama = document.getElementById('pNama');
    const avatar = document.getElementById('avatar');
    const profileDetails = document.getElementById('profileDetails');
    const sertifikatContent = document.getElementById('sertifikatContent');
    const berkasContent = document.getElementById('berkasContent');

    function initials(name) {
      if (!name) return '?';
      const parts = name.trim().split(/\s+/);
      const first = (parts[0] || '')[0] || '';
      const last = (parts[parts.length - 1] || '')[0] || '';
      return (first + last).toUpperCase();
    }

    function createProfileDetails(profil) {
      const details = [{
          label: 'Program',
          value: profil.program
        },
        {
          label: 'Kelas',
          value: profil.kelas
        },
        {
          label: 'MA',
          value: profil.ma
        },
        {
          label: 'Jurusan',
          value: profil.jurusan
        }
      ].filter(item => item.value);

      if (details.length === 0) {
        return '<div class="empty-state">Tidak ada informasi detail tersedia</div>';
      }

      return details.map(item => `
        <div class="detail-item">
          <div class="detail-label">${item.label}</div>
          <div class="detail-value">${item.value}</div>
        </div>
      `).join('');
    }

    function createFilesGrid(files, type) {
      if (!Array.isArray(files) || files.length === 0) {
        return `
          <div class="empty-state">
            <div class="empty-icon">${type === 'sertifikat' ? '📜' : '📁'}</div>
            <div>Tidak ada ${type} tersedia</div>
          </div>
        `;
      }

      return `
        <div class="files-grid">
          ${files.map(file => `
            <div class="file-card">
              <div class="file-header">
                <div class="file-info">
                  <div class="file-name">${file.nama_file}</div>
                  <div class="file-path">${file.path}</div>
                </div>
                <div class="file-actions">
                  <a href="${file.path}" target="_blank" rel="noopener" class="btn btn-primary">
                    👁️ Lihat
                  </a>
                  <a href="${file.path}" download class="btn btn-secondary">
                    ⬇️ Unduh
                  </a>
                </div>
              </div>
            </div>
          `).join('')}
        </div>
      `;
    }

    (async () => {
      try {
      const r = await fetch('api/peserta_detail.php?id=' + encodeURIComponent(id));
      const j = await r.json();

      if (j.error) {
        pNama.textContent = j.error;
        profileDetails.innerHTML = '<div class="empty-state">Gagal memuat data</div>';
        sertifikatContent.innerHTML = '<div class="empty-state">Gagal memuat data</div>';
        berkasContent.innerHTML = '<div class="empty-state">Gagal memuat data</div>';
        return;
      }

      const p = j.profil;
      const s = j.sertifikat || [];
      const b = j.berkas || [];

      // Update profile
      pNama.textContent = p.nama || 'Nama tidak tersedia';
      
      // Update avatar - show profile photo if available
      if (p.profile && p.profile.foto_path) {
        avatar.innerHTML = `<img src="${p.profile.foto_path}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 20px;" alt="Profile Photo">`;
      } else {
        avatar.textContent = initials(p.nama || '');
      }
      
      // Detail-item horizontal (tepat di bawah nama)
      profileDetails.innerHTML = createProfileDetails(p);

      // Update files
      sertifikatContent.innerHTML = createFilesGrid(s, 'sertifikat');
      berkasContent.innerHTML = createFilesGrid(b, 'berkas');

      } catch (e) {
      pNama.textContent = 'Gagal memuat data';
      profileDetails.innerHTML = '<div class="empty-state">Terjadi kesalahan saat memuat data</div>';
      sertifikatContent.innerHTML = '<div class="empty-state">Terjadi kesalahan saat memuat data</div>';
      berkasContent.innerHTML = '<div class="empty-state">Terjadi kesalahan saat memuat data</div>';
      }
    })();
  </script>
</body>

</html>