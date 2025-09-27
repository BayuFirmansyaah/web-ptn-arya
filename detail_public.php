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

    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      background: linear-gradient(135deg, var(--bg1) 0%, var(--bg2) 100%);
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

    .back-btn {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      color: rgba(255, 255, 255, 0.9);
      text-decoration: none;
      font-weight: 500;
      padding: 12px 20px;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 12px;
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.2);
      transition: all 0.3s ease;
    }

    .back-btn:hover {
      background: rgba(255, 255, 255, 0.2);
      transform: translateY(-2px);
    }

    .profile-section {
      background: var(--card);
      border-radius: 20px;
      padding: 32px;
      margin-bottom: 24px;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.8);
    }

    .profile-header {
      display: flex;
      align-items: center;
      gap: 24px;
      margin-bottom: 24px;
    }

    .avatar {
      width: 80px;
      height: 80px;
      border-radius: 20px;
      background: linear-gradient(135deg, var(--accent), var(--accent-2));
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 28px;
      font-weight: 700;
      box-shadow: 0 8px 32px rgba(32, 191, 0, 0.3);
    }

    .profile-info h1 {
      font-size: 28px;
      font-weight: 700;
      color: var(--ink);
      margin-bottom: 8px;
    }

    .profile-info .subtitle {
      color: var(--muted);
      font-size: 16px;
    }

    .profile-details {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 16px;
    }

    .detail-item {
      background: #f8fafc;
      padding: 16px;
      border-radius: 12px;
      border-left: 4px solid var(--accent);
    }

    .detail-label {
      font-size: 12px;
      font-weight: 600;
      color: var(--muted);
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-bottom: 4px;
    }

    .detail-value {
      font-size: 16px;
      font-weight: 600;
      color: var(--ink);
    }

    .files-section {
      background: var(--card);
      border-radius: 20px;
      padding: 32px;
      margin-bottom: 24px;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.8);
    }

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
      background: linear-gradient(135deg, var(--accent), var(--accent-2));
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 16px;
    }

    .files-grid {
      display: grid;
      gap: 16px;
    }

    .file-card {
      background: #f8fafc;
      border: 1px solid var(--line);
      border-radius: 16px;
      padding: 20px;
      transition: all 0.3s ease;
    }

    .file-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 12px 32px rgba(0, 0, 0, 0.1);
    }

    .file-header {
      display: flex;
      justify-content: between;
      align-items: flex-start;
      gap: 16px;
      margin-bottom: 16px;
    }

    .file-info {
      flex: 1;
    }

    .file-name {
      font-size: 16px;
      font-weight: 600;
      color: var(--ink);
      margin-bottom: 4px;
    }

    .file-path {
      font-size: 14px;
      color: var(--muted);
      word-break: break-all;
    }

    .file-actions {
      display: flex;
      gap: 12px;
      flex-wrap: wrap;
    }

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
      transition: all 0.3s ease;
    }

    .btn-primary {
      background: linear-gradient(135deg, var(--accent), var(--accent-2));
      color: white;
      box-shadow: 0 4px 16px rgba(32, 191, 0, 0.3);
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(32, 191, 0, 0.4);
    }

    .btn-secondary {
      background: white;
      color: var(--ink);
      border: 1px solid var(--line);
    }

    .btn-secondary:hover {
      background: #f8fafc;
      transform: translateY(-2px);
    }

    .empty-state {
      text-align: center;
      padding: 40px 20px;
      color: var(--muted);
      background: #f8fafc;
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
      border-top: 2px solid var(--accent);
      border-radius: 50%;
      animation: spin 1s linear infinite;
      margin-right: 12px;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    @media (max-width: 768px) {
      .profile-header {
        flex-direction: column;
        text-align: center;
      }
      
      .profile-details {
        grid-template-columns: 1fr;
      }
      
      .file-actions {
        justify-content: center;
      }
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
        </div>
      </div>
      
      <div class="profile-details" id="profileDetails">
        <div class="loading">
          <div class="spinner"></div>
          Memuat informasi peserta...
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
      const details = [
        { label: 'Program', value: profil.program },
        { label: 'Kelas', value: profil.kelas },
        { label: 'MA', value: profil.ma },
        { label: 'Jurusan', value: profil.jurusan }
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
        avatar.textContent = initials(p.nama || '');
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
