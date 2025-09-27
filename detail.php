<?php
require __DIR__.'/lib/auth.php';
require_admin();

$isSuper = is_super_admin();
$id = $_GET['id'] ?? '';
if (!$id) { header('Location: dashboard.php'); exit; }
?>
<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8" />
  <title>Detail Peserta</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <!-- Tanam data ke meta agar aman -->
  <meta name="x-peserta-id" content="<?= htmlspecialchars($id, ENT_QUOTES, 'UTF-8') ?>">
  <meta name="x-is-super" content="<?= $isSuper ? '1' : '0' ?>">

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
      --success: #059669;
      --error: #dc2626;
      --warning: #d97706;
      --surface: #f8fafc;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      background: linear-gradient(135deg, var(--bg1) 0%, var(--bg2) 100%);
      color: var(--ink);
      min-height: 100vh;
      line-height: 1.5;
    }

    /* Header Navigation */
    .header {
      padding: 20px;
      max-width: 1200px;
      margin: 0 auto;
    }

    .nav {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border-radius: 16px;
      padding: 12px 20px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .nav-link {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 8px 16px;
      border-radius: 10px;
      text-decoration: none;
      color: var(--ink);
      font-weight: 500;
      transition: all 0.2s ease;
    }

    .nav-link:hover {
      background: var(--surface);
      transform: translateY(-1px);
    }

    .nav-link.primary {
      background: linear-gradient(135deg, var(--accent) 0%, var(--accent-2) 100%);
      color: white;
    }

    .nav-link.primary:hover {
      box-shadow: 0 4px 16px rgba(32, 191, 0, 0.3);
    }

    /* Main Container */
    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 20px 40px;
    }

    /* Profile Card */
    .profile-section {
      background: var(--card);
      border-radius: 20px;
      padding: 24px;
      margin-bottom: 24px;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
      border: 1px solid var(--line);
    }

    .profile-header {
      display: flex;
      align-items: center;
      gap: 20px;
      margin-bottom: 20px;
    }

    .avatar {
      width: 80px;
      height: 80px;
      border-radius: 20px;
      background: linear-gradient(135deg, var(--accent) 0%, var(--accent-2) 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 28px;
      font-weight: 700;
      box-shadow: 0 8px 24px rgba(32, 191, 0, 0.3);
    }

    .profile-info h1 {
      font-size: 28px;
      font-weight: 700;
      color: var(--ink);
      margin-bottom: 8px;
    }

    .profile-status {
      display: inline-block;
      background: rgba(32, 191, 0, 0.1);
      color: var(--accent-2);
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
      border: 1px solid rgba(32, 191, 0, 0.2);
    }

    .profile-details {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 16px;
      background: var(--surface);
      padding: 20px;
      border-radius: 16px;
      border: 1px solid var(--line);
    }

    .detail-item {
      display: flex;
      flex-direction: column;
      gap: 4px;
    }

    .detail-label {
      font-size: 12px;
      font-weight: 600;
      color: var(--muted);
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .detail-value {
      font-size: 14px;
      font-weight: 600;
      color: var(--ink);
    }

    /* Section Cards */
    .section-card {
      background: var(--card);
      border-radius: 20px;
      padding: 24px;
      margin-bottom: 24px;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
      border: 1px solid var(--line);
    }

    .section-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
      padding-bottom: 16px;
      border-bottom: 1px solid var(--line);
    }

    .section-title {
      font-size: 20px;
      font-weight: 700;
      color: var(--ink);
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .section-icon {
      width: 24px;
      height: 24px;
      background: linear-gradient(135deg, var(--accent) 0%, var(--accent-2) 100%);
      border-radius: 6px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 12px;
      font-weight: 700;
    }

    /* Admin Edit Form */
    .edit-form {
      background: linear-gradient(135deg, rgba(32, 191, 0, 0.05) 0%, rgba(22, 147, 0, 0.05) 100%);
      border: 1px solid rgba(32, 191, 0, 0.2);
      border-radius: 16px;
      padding: 20px;
      margin-bottom: 24px;
    }

    .form-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 16px;
      margin-bottom: 16px;
    }

    .form-group {
      display: flex;
      flex-direction: column;
      gap: 6px;
    }

    .form-label {
      font-size: 13px;
      font-weight: 600;
      color: var(--ink);
    }

    .form-field {
      padding: 12px 16px;
      border: 1px solid var(--line);
      border-radius: 12px;
      font-size: 14px;
      transition: all 0.2s ease;
      background: white;
    }

    .form-field:focus {
      outline: none;
      border-color: var(--accent);
      box-shadow: 0 0 0 3px rgba(32, 191, 0, 0.1);
    }

    /* Buttons */
    .btn {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 12px 20px;
      border: none;
      border-radius: 12px;
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.2s ease;
      text-decoration: none;
      justify-content: center;
    }

    .btn-primary {
      background: linear-gradient(135deg, var(--accent) 0%, var(--accent-2) 100%);
      color: white;
      box-shadow: 0 4px 16px rgba(32, 191, 0, 0.3);
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 24px rgba(32, 191, 0, 0.4);
    }

    .btn-secondary {
      background: var(--surface);
      color: var(--ink);
      border: 1px solid var(--line);
    }

    .btn-secondary:hover {
      background: white;
      border-color: var(--accent);
    }

    .btn-danger {
      background: var(--error);
      color: white;
    }

    .btn-danger:hover {
      background: #b91c1c;
      transform: translateY(-1px);
    }

    .btn-outline {
      background: transparent;
      color: var(--accent);
      border: 1px solid var(--accent);
    }

    .btn-outline:hover {
      background: var(--accent);
      color: white;
    }

    .btn:disabled {
      opacity: 0.6;
      cursor: not-allowed;
      transform: none !important;
    }

    /* File Tables */
    .files-container {
      border-radius: 16px;
      overflow: hidden;
      border: 1px solid var(--line);
    }

    .files-table {
      width: 100%;
      border-collapse: collapse;
    }

    .files-table thead th {
      background: linear-gradient(135deg, var(--bg1) 0%, var(--bg2) 100%);
      color: white;
      padding: 16px 20px;
      text-align: left;
      font-weight: 600;
      font-size: 13px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .files-table tbody td {
      padding: 16px 20px;
      border-bottom: 1px solid var(--line);
      vertical-align: middle;
    }

    .files-table tbody tr:last-child td {
      border-bottom: none;
    }

    .files-table tbody tr:hover {
      background: var(--surface);
    }

    .file-info {
      display: flex;
      flex-direction: column;
      gap: 4px;
    }

    .file-name {
      font-weight: 600;
      color: var(--ink);
    }

    .file-path {
      font-size: 12px;
      color: var(--muted);
    }

    .file-actions {
      display: flex;
      gap: 8px;
      flex-wrap: wrap;
    }

    /* Empty State */
    .empty-state {
      text-align: center;
      padding: 40px 20px;
      color: var(--muted);
      background: var(--surface);
      border-radius: 16px;
      border: 2px dashed var(--line);
    }

    .empty-icon {
      font-size: 48px;
      margin-bottom: 12px;
      opacity: 0.5;
    }

    /* Modal */
    .modal {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.7);
      backdrop-filter: blur(8px);
      z-index: 1000;
      align-items: center;
      justify-content: center;
    }

    .modal-content {
      background: var(--card);
      border-radius: 20px;
      padding: 24px;
      width: min(500px, 95vw);
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
      border: 1px solid var(--line);
    }

    .modal-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
      padding-bottom: 16px;
      border-bottom: 1px solid var(--line);
    }

    .modal-title {
      font-size: 18px;
      font-weight: 700;
      color: var(--ink);
    }

    .file-input {
      width: 100%;
      padding: 16px;
      border: 2px dashed var(--line);
      border-radius: 12px;
      text-align: center;
      background: var(--surface);
      margin-bottom: 16px;
      cursor: pointer;
      transition: all 0.2s ease;
    }

    .file-input:hover {
      border-color: var(--accent);
      background: rgba(32, 191, 0, 0.05);
    }

    .file-input input {
      display: none;
    }

    /* Alert Messages */
    .alert {
      padding: 12px 16px;
      border-radius: 12px;
      margin-top: 12px;
      font-weight: 500;
    }

    .alert-success {
      background: rgba(5, 150, 105, 0.1);
      color: var(--success);
      border: 1px solid rgba(5, 150, 105, 0.2);
    }

    .alert-error {
      background: rgba(220, 38, 38, 0.1);
      color: var(--error);
      border: 1px solid rgba(220, 38, 38, 0.2);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .header, .container {
        padding-left: 16px;
        padding-right: 16px;
      }

      .profile-header {
        flex-direction: column;
        text-align: center;
        gap: 16px;
      }

      .profile-details {
        grid-template-columns: 1fr;
      }

      .section-header {
        flex-direction: column;
        gap: 12px;
        align-items: flex-start;
      }

      .nav {
        flex-direction: column;
        gap: 12px;
      }

      .file-actions {
        flex-direction: column;
      }

      .form-grid {
        grid-template-columns: 1fr;
      }
    }

    /* Loading State */
    .loading {
      display: inline-block;
      width: 16px;
      height: 16px;
      border: 2px solid var(--line);
      border-top: 2px solid var(--accent);
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    /* Fade in animation */
    .fade-in {
      animation: fadeIn 0.5s ease-in;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>

<body>
  <!-- Header Navigation -->
  <div class="header">
    <nav class="nav">
      <a href="dashboard.php" class="nav-link">
        ← Kembali ke Dashboard
      </a>
      <a href="api/logout.php" class="nav-link primary">
        Keluar
      </a>
    </nav>
  </div>

  <div class="container">
    <!-- Profile Section -->
    <div class="profile-section fade-in">
      <div class="profile-header">
        <div class="avatar" id="avatar">?</div>
        <div class="profile-info">
          <h1 id="pNama">Memuat data peserta...</h1>
          <span class="profile-status">Peserta Aktif</span>
        </div>
      </div>
      <div class="profile-details" id="profileDetails">
        <div class="detail-item">
          <span class="detail-label">Program</span>
          <span class="detail-value" id="detailProgram">-</span>
        </div>
        <div class="detail-item">
          <span class="detail-label">Kelas</span>
          <span class="detail-value" id="detailKelas">-</span>
        </div>
        <div class="detail-item">
          <span class="detail-label">Madrasah Aliyah</span>
          <span class="detail-value" id="detailMA">-</span>
        </div>
        <div class="detail-item">
          <span class="detail-label">Jurusan</span>
          <span class="detail-value" id="detailJurusan">-</span>
        </div>
        <div class="detail-item">
          <span class="detail-label">Gender</span>
          <span class="detail-value" id="detailGender">-</span>
        </div>
        <div class="detail-item">
          <span class="detail-label">ID Peserta</span>
          <span class="detail-value"><?= htmlspecialchars($id) ?></span>
        </div>
      </div>
    </div>

    <!-- Super Admin Edit Form -->
    <?php if ($isSuper): ?>
    <div class="edit-form fade-in" style="background-color: white !important;">
      <div class="section-header">
        <h2 class="section-title">
          <span class="section-icon">⚙</span>
          Edit Data Peserta
        </h2>
      </div>
      <div class="form-grid">
        <div class="form-group">
          <label class="form-label">Nama Lengkap</label>
          <input id="f_nama" class="form-field" placeholder="Masukkan nama lengkap">
        </div>
        <div class="form-group">
          <label class="form-label">Program</label>
          <select id="f_program" class="form-field">
            <option value="12EXC">12EXC</option>
            <option value="12CI">12CI</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Gender</label>
          <select id="f_gender" class="form-field">
            <option value="PUTRA">PUTRA</option>
            <option value="PUTRI">PUTRI</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Madrasah Aliyah</label>
          <select id="f_ma" class="form-field">
            <option value="MA01">MA01</option>
            <option value="MA03">MA03</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Kelas</label>
          <input id="f_kelas" class="form-field" placeholder="Contoh: A1, B1, C1, atau A-M">
        </div>
        <div class="form-group">
          <label class="form-label">Jurusan</label>
          <input id="f_jurusan" class="form-field" placeholder="Masukkan jurusan">
        </div>
      </div>
      <div style="display: flex; gap: 12px; align-items: center;">
        <button class="btn btn-primary" onclick="saveBiodata()">
          <span id="saveLoading" class="loading" style="display: none;"></span>
          Simpan Perubahan
        </button>
        <div id="saveMsg" class="alert" style="display:none"></div>
      </div>
    </div>
    <?php endif; ?>

    <!-- Sertifikat Section -->
    <div class="section-card fade-in">
      <div class="section-header">
        <h2 class="section-title">
          <span class="section-icon">🏆</span>
          Sertifikat
        </h2>
        <button class="btn btn-primary" onclick="openModal('sertifikat')">
          Upload Sertifikat
        </button>
      </div>
      <div id="emptyS" class="empty-state" style="display:none">
        <div class="empty-icon">📄</div>
        <p>Belum ada sertifikat yang diupload</p>
      </div>
      <div class="files-container" id="containerS">
        <table class="files-table">
          <thead>
            <tr>
              <th>Informasi File</th>
              <th style="width: 300px;">Aksi</th>
            </tr>
          </thead>
          <tbody id="tbS">
            <tr>
              <td colspan="2" style="text-align: center; color: var(--muted);">
                <span class="loading"></span> Memuat data...
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Berkas Section -->
    <div class="section-card fade-in">
      <div class="section-header">
        <h2 class="section-title">
          <span class="section-icon">📋</span>
          Berkas Pendukung
        </h2>
        <button class="btn btn-primary" onclick="openModal('berkas')">
          Upload Berkas
        </button>
      </div>
      <div id="emptyB" class="empty-state" style="display:none">
        <div class="empty-icon">📁</div>
        <p>Belum ada berkas yang diupload</p>
      </div>
      <div class="files-container" id="containerB">
        <table class="files-table">
          <thead>
            <tr>
              <th>Informasi File</th>
              <th style="width: 300px;">Aksi</th>
            </tr>
          </thead>
          <tbody id="tbB">
            <tr>
              <td colspan="2" style="text-align: center; color: var(--muted);">
                <span class="loading"></span> Memuat data...
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Upload Modal -->
  <div id="modal" class="modal" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
    <div class="modal-content">
      <div class="modal-header">
        <h3 id="modalTitle" class="modal-title">Upload File</h3>
        <button class="btn btn-secondary" onclick="closeModal()">Tutup</button>
      </div>
      <div class="file-input" onclick="document.getElementById('file').click()">
        <input type="file" id="file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" />
        <p>📤 Klik untuk memilih file</p>
        <small style="color: var(--muted);">Format: PDF, DOC, DOCX, JPG, PNG (Max: 5MB)</small>
      </div>
      <div style="display: flex; gap: 12px; align-items: center;">
        <button id="btnUpload" class="btn btn-primary" onclick="doUpload()">
          <span id="uploadLoading" class="loading" style="display: none;"></span>
          Upload File
        </button>
        <div id="mMsg" style="flex: 1; color: var(--muted); font-size: 14px;"></div>
      </div>
    </div>
  </div>

  <script>
    // Ambil data dari meta
    const id = document.querySelector('meta[name="x-peserta-id"]').content;
    const isSuper = document.querySelector('meta[name="x-is-super"]').content === '1';

    // Elemen
    const pNama = document.getElementById('pNama');
    const avatar = document.getElementById('avatar');
    const detailProgram = document.getElementById('detailProgram');
    const detailKelas = document.getElementById('detailKelas');
    const detailMA = document.getElementById('detailMA');
    const detailJurusan = document.getElementById('detailJurusan');
    const detailGender = document.getElementById('detailGender');

    const tbS = document.getElementById('tbS');
    const tbB = document.getElementById('tbB');
    const emptyS = document.getElementById('emptyS');
    const emptyB = document.getElementById('emptyB');
    const containerS = document.getElementById('containerS');
    const containerB = document.getElementById('containerB');

    const modal = document.getElementById('modal');
    const modalTitle = document.getElementById('modalTitle');
    const mMsg = document.getElementById('mMsg');
    const fileInp = document.getElementById('file');
    const btnUpload = document.getElementById('btnUpload');
    const uploadLoading = document.getElementById('uploadLoading');
    let currentType = 'sertifikat';

    function initials(name) {
      if (!name) return '?';
      const parts = name.trim().split(/\s+/);
      const first = (parts[0] || '')[0] || '';
      const last = (parts[parts.length - 1] || '')[0] || '';
      return (first + last).toUpperCase();
    }

    function openModal(t) {
      currentType = t;
      modalTitle.textContent = 'Upload ' + (t === 'sertifikat' ? 'Sertifikat' : 'Berkas Pendukung');
      mMsg.textContent = '';
      fileInp.value = '';
      modal.style.display = 'flex';
    }

    function closeModal() {
      modal.style.display = 'none';
    }

    // Close modal when clicking outside
    window.addEventListener('click', (e) => {
      if (e.target === modal) closeModal();
    });

    // Close modal with Escape key
    window.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') closeModal();
    });

    async function load() {
      try {
        const r = await fetch('api/peserta_detail_admin.php?id=' + encodeURIComponent(id));
        const j = await r.json();
        
        if (j.error) {
          pNama.textContent = 'Error: ' + j.error;
          return;
        }
        
        const p = j.peserta || {};
        const s = j.sertifikat || [];
        const b = j.berkas || [];

        // Update profile info
        pNama.textContent = p.nama || 'Nama tidak tersedia';
        avatar.textContent = initials(p.nama || '');
        
        // Update detail fields
        detailProgram.textContent = p.program || '-';
        detailKelas.textContent = p.kelas || '-';
        detailMA.textContent = p.ma || '-';
        detailJurusan.textContent = p.jurusan || '-';
        detailGender.textContent = p.gender || '-';

        renderFiles(tbS, emptyS, containerS, s, 'sertifikat');
        renderFiles(tbB, emptyB, containerB, b, 'berkas');

        // Fill super admin form if applicable
        if (isSuper) {
          const fields = {
            f_nama: p.nama || '',
            f_program: p.program || '12EXC',
            f_gender: p.gender || 'PUTRA',
            f_ma: p.ma || 'MA01',
            f_kelas: p.kelas || '',
            f_jurusan: p.jurusan || ''
          };

          Object.entries(fields).forEach(([id, value]) => {
            const elem = document.getElementById(id);
            if (elem) elem.value = value;
          });
        }
      } catch (error) {
        pNama.textContent = 'Gagal memuat data';
        console.error('Load error:', error);
      }
    }

    function renderFiles(tbody, emptyEl, container, arr, type) {
      tbody.innerHTML = '';
      
      if (!Array.isArray(arr) || arr.length === 0) {
        emptyEl.style.display = 'block';
        container.style.display = 'none';
        return;
      }
      
      emptyEl.style.display = 'none';
      container.style.display = 'block';

      for (const f of arr) {
        const tr = document.createElement('tr');
        const fileSize = f.size ? formatFileSize(f.size) : '';
        const uploadDate = f.created_at ? new Date(f.created_at).toLocaleDateString('id-ID') : '';
        
        tr.innerHTML = `
          <td>
            <div class="file-info">
              <div class="file-name">${escapeHtml(f.nama_file)}</div>
              <div class="file-path">${escapeHtml(f.path)}</div>
              ${fileSize ? `<small style="color: var(--muted);">${fileSize}</small>` : ''}
              ${uploadDate ? `<small style="color: var(--muted);">Diupload: ${uploadDate}</small>` : ''}
            </div>
          </td>
          <td>
            <div class="file-actions">
              <a class="btn btn-secondary" href="${escapeHtml(f.path)}" target="_blank" rel="noopener">
                👁️ Lihat
              </a>
              <a class="btn btn-outline" href="${escapeHtml(f.path)}" download>
                💾 Unduh
              </a>
              <button class="btn btn-danger" onclick="deleteFile('${type}','${f.id}')">
                🗑️ Hapus
              </button>
            </div>
          </td>
        `;
        tbody.appendChild(tr);
      }
    }

    function formatFileSize(bytes) {
      if (bytes === 0) return '0 Bytes';
      const k = 1024;
      const sizes = ['Bytes', 'KB', 'MB', 'GB'];
      const i = Math.floor(Math.log(bytes) / Math.log(k));
      return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    function escapeHtml(text) {
      const div = document.createElement('div');
      div.textContent = text;
      return div.innerHTML;
    }

    async function doUpload() {
      const f = fileInp.files[0];
      if (!f) {
        mMsg.textContent = 'Silakan pilih file terlebih dahulu';
        return;
      }

      // Validate file size (5MB max)
      if (f.size > 5 * 1024 * 1024) {
        mMsg.textContent = 'Ukuran file maksimal 5MB';
        return;
      }

      btnUpload.disabled = true;
      uploadLoading.style.display = 'inline-block';
      mMsg.textContent = 'Sedang mengupload...';

      try {
        const fd = new FormData();
        fd.append('file', f);
        fd.append('peserta_id', id);
        fd.append('type', currentType);
        
        const r = await fetch('api/upload.php', {
          method: 'POST',
          body: fd
        });
        
        const j = await r.json();
        
        if (j.ok) {
          mMsg.textContent = 'File berhasil diupload!';
          setTimeout(() => {
            closeModal();
            load();
          }, 1500);
        } else {
          mMsg.textContent = j.error || 'Gagal mengupload file';
        }
      } catch (error) {
        mMsg.textContent = 'Terjadi kesalahan saat mengupload';
        console.error('Upload error:', error);
      } finally {
        btnUpload.disabled = false;
        uploadLoading.style.display = 'none';
      }
    }

    async function deleteFile(type, fileId) {
      if (!confirm('Apakah Anda yakin ingin menghapus file ini?')) return;

      try {
        const r = await fetch('api/delete_file.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            id: fileId,
            type
          })
        });
        
        const j = await r.json();
        
        if (j.ok) {
          load(); // Reload data
        } else {
          alert(j.error || 'Gagal menghapus file');
        }
      } catch (error) {
        alert('Terjadi kesalahan saat menghapus file');
        console.error('Delete error:', error);
      }
    }

    async function saveBiodata() {
      if (!isSuper) {
        alert('Hanya super admin yang dapat mengedit data');
        return;
      }

      const saveBtn = document.querySelector('.btn[onclick="saveBiodata()"]');
      const saveLoading = document.getElementById('saveLoading');
      const msg = document.getElementById('saveMsg');
      
      const body = {
        id,
        nama: document.getElementById('f_nama').value.trim(),
        program: document.getElementById('f_program').value,
        gender: document.getElementById('f_gender').value,
        ma: document.getElementById('f_ma').value,
        kelas: document.getElementById('f_kelas').value.trim(),
        jurusan: document.getElementById('f_jurusan').value.trim()
      };

      // Validation
      if (!body.nama) {
        showMessage(msg, 'Nama tidak boleh kosong', 'error');
        return;
      }

      saveBtn.disabled = true;
      saveLoading.style.display = 'inline-block';
      msg.style.display = 'none';

      try {
        const r = await fetch('api/peserta_save.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(body)
        });
        
        const j = await r.json();
        
        if (j.ok) {
          showMessage(msg, `Data berhasil ${j.action === 'updated' ? 'diperbarui' : 'disimpan'}!`, 'success');
          load(); // Reload data to reflect changes
        } else {
          showMessage(msg, j.error || 'Gagal menyimpan data', 'error');
        }
      } catch (error) {
        showMessage(msg, 'Terjadi kesalahan saat menyimpan', 'error');
        console.error('Save error:', error);
      } finally {
        saveBtn.disabled = false;
        saveLoading.style.display = 'none';
      }
    }

    function showMessage(element, text, type = 'success') {
      element.textContent = text;
      element.className = `alert alert-${type}`;
      element.style.display = 'block';
      
      // Auto hide after 5 seconds
      setTimeout(() => {
        element.style.display = 'none';
      }, 5000);
    }

    // File input change handler
    fileInp.addEventListener('change', function() {
      const fileName = this.files[0]?.name;
      if (fileName) {
        mMsg.textContent = `File terpilih: ${fileName}`;
      }
    });

    // Initialize page
    load();
  </script>
</body>

</html>
