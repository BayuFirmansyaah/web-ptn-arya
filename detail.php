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
  <title>Detail Peserta (Admin)</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <!-- Tanam data ke meta agar aman -->
  <meta name="x-peserta-id" content="<?= htmlspecialchars($id, ENT_QUOTES, 'UTF-8') ?>">
  <meta name="x-is-super" content="<?= $isSuper ? '1' : '0' ?>">

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

    body {
      font-family: Arial, Helvetica, sans-serif;
      background: linear-gradient(135deg, var(--bg1) 0%, var(--bg2) 100%);
      margin: 0;
      padding: 18px;
      color: var(--ink);
      min-height: 100vh;
    }

    a {
      text-decoration: none;
      color: var(--accent)
    }

    a:hover {
      text-decoration: underline
    }

    .top {
      max-width: 960px;
      margin: 0 auto 14px;
      display: flex;
      justify-content: space-between;
      align-items: center
    }

    .top .link {
      display: inline-block;
      padding: 8px 12px;
      border: 1px solid rgba(255, 255, 255, 0.2);
      border-radius: 10px;
      background: rgba(255, 255, 255, 0.9);
      backdrop-filter: blur(10px);
      color: var(--ink);
    }

    .top .link:hover {
      background: rgba(255, 255, 255, 1);
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .box {
      max-width: 960px;
      margin: 0 auto;
      background: var(--card);
      border: 1px solid var(--line);
      border-radius: 14px;
      padding: 16px;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    /* Profile header */
    .profile-card {
      display: flex;
      gap: 14px;
      align-items: center;
      background: linear-gradient(135deg, var(--bg1) 0%, var(--bg2) 100%);
      border-radius: 12px;
      padding: 16px;
      color: white;
    }

    .avatar {
      width: 54px;
      height: 54px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      background: rgba(255, 255, 255, 0.2);
      color: white;
      font-weight: 700;
      font-size: 18px;
      user-select: none;
      border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .chips {
      display: flex;
      gap: 8px;
      flex-wrap: wrap;
      margin-top: 6px
    }

    .chip {
      background: rgba(255, 255, 255, 0.2);
      color: white;
      border: 1px solid rgba(255, 255, 255, 0.3);
      border-radius: 999px;
      padding: 4px 10px;
      font-size: 12px;
      backdrop-filter: blur(10px);
    }

    /* Sections */
    .section {
      margin-top: 16px
    }

    .section-head {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 8px
    }

    .section-head h3 {
      margin: 0;
      font-size: 16px;
      color: var(--ink);
    }

    table {
      width: 100%;
      border-collapse: collapse;
      border: 1px solid var(--line);
      border-radius: 10px;
      overflow: hidden
    }

    thead th {
      background: linear-gradient(135deg, var(--bg1) 0%, var(--bg2) 100%);
      font-weight: 600;
      font-size: 13px;
      color: white;
      border-bottom: 1px solid var(--line);
      padding: 10px;
      text-align: left
    }

    tbody td {
      border-bottom: 1px solid var(--line);
      padding: 10px;
      vertical-align: middle
    }

    tbody tr:last-child td {
      border-bottom: none
    }

    tbody tr:hover {
      background: #f8fffe;
    }

    .muted {
      color: var(--muted)
    }

    .empty {
      padding: 12px;
      border: 1px dashed var(--line);
      border-radius: 10px;
      color: var(--muted);
      background: #fafffe
    }

    .btn {
      padding: 8px 12px;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      font-size: 13px;
      transition: all 0.2s ease;
    }

    .btn.primary {
      background: linear-gradient(135deg, var(--accent) 0%, var(--accent-2) 100%);
      color: #fff;
      box-shadow: 0 2px 8px rgba(32, 191, 0, 0.2);
    }

    .btn.primary:hover {
      transform: translateY(-1px);
      box-shadow: 0 4px 16px rgba(32, 191, 0, 0.3);
    }

    .btn.ghost {
      background: #fff;
      border: 1px solid var(--line);
      color: var(--ink);
    }

    .btn.ghost:hover {
      background: #f8fffe;
      border-color: var(--accent);
    }

    .btn.outline {
      background: #fff;
      border: 1px solid var(--accent);
      color: var(--accent);
    }

    .btn.outline:hover {
      background: var(--accent);
      color: white;
    }

    .btn.danger {
      background: #dc2626;
      color: #fff
    }

    .btn.danger:hover {
      background: #b91c1c;
      transform: translateY(-1px);
    }

    .btn[disabled] {
      opacity: .6;
      cursor: not-allowed;
      transform: none !important;
    }

    /* Modal */
    .modal {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, .6);
      align-items: center;
      justify-content: center;
      z-index: 50;
      backdrop-filter: blur(5px);
    }

    .modal .inner {
      background: #fff;
      border-radius: 14px;
      padding: 16px;
      width: min(460px, 92vw);
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
      border: 1px solid var(--line);
    }

    .modal-head {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 10px;
      padding-bottom: 8px;
      border-bottom: 1px solid var(--line);
    }

    .field {
      display: block;
      width: 100%;
      padding: 9px;
      border: 1px solid var(--line);
      border-radius: 10px;
      transition: border-color 0.2s ease;
    }

    .field:focus {
      outline: none;
      border-color: var(--accent);
      box-shadow: 0 0 0 3px rgba(32, 191, 0, 0.1);
    }

    .alert {
      padding: 10px;
      border-radius: 10px;
      background: #f0fdf4;
      margin-top: 10px;
      border: 1px solid #bbf7d0;
      color: var(--accent-2);
    }

    .actions {
      display: flex;
      gap: 8px;
      flex-wrap: wrap
    }

    /* Profile name styling */
    .profile-card #pNama {
      font-weight: 700;
      font-size: 18px;
      color: white;
      text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
    }
  </style>
</head>

<body>

  <div class="top">
    <a class="link" href="dashboard.php">← Kembali</a>
    <a class="link" href="api/logout.php">Keluar</a>
  </div>

  <div class="box">
    <!-- Profile -->
    <div class="profile-card">
      <div class="avatar" id="avatar">?</div>
      <div>
        <div id="pNama" style="font-weight:700;font-size:18px">Memuat...</div>
        <div class="chips" id="chips"></div>
      </div>
    </div>

    <!-- Super Admin form -->
    <?php if ($isSuper): ?>
    <div class="section">
      <div class="section-head">
        <h3>Edit Biodata (Super Admin)</h3>
      </div>
      <div class="actions" style="gap:10px">
        <input id="f_nama" class="field" placeholder="Nama" style="flex:1 1 220px">
        <select id="f_program" class="field" style="flex:1 1 160px">
          <option value="12EXC">12EXC</option>
          <option value="12CI">12CI</option>
        </select>
        <select id="f_gender" class="field" style="flex:1 1 140px">
          <option value="PUTRA">PUTRA</option>
          <option value="PUTRI">PUTRI</option>
        </select>
        <select id="f_ma" class="field" style="flex:1 1 140px">
          <option value="MA01">MA01</option>
          <option value="MA03">MA03</option>
        </select>
        <input id="f_kelas" class="field" placeholder="Kelas (A1/B1/C1/D1 atau A..M)" style="flex:1 1 200px">
        <input id="f_jurusan" class="field" placeholder="Jurusan" style="flex:1 1 200px">
      </div>
      <div class="actions" style="margin-top:8px">
        <button class="btn primary" onclick="saveBiodata()">Simpan</button>
        <div id="saveMsg" class="alert" style="display:none"></div>
      </div>
    </div>
    <?php endif; ?>

    <!-- Sertifikat -->
    <div class="section">
      <div class="section-head">
        <h3>Sertifikat</h3>
        <button class="btn primary" onclick="openModal('sertifikat')">Upload Sertifikat</button>
      </div>
      <div id="emptyS" class="empty" style="display:none">Tidak ada sertifikat.</div>
      <table>
        <thead>
          <tr>
            <th>Nama File</th>
            <th style="width:260px">Aksi</th>
          </tr>
        </thead>
        <tbody id="tbS">
          <tr>
            <td colspan="2" class="muted">Memuat...</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Berkas -->
    <div class="section">
      <div class="section-head">
        <h3>Berkas</h3>
        <button class="btn primary" onclick="openModal('berkas')">Upload Berkas</button>
      </div>
      <div id="emptyB" class="empty" style="display:none">Tidak ada berkas.</div>
      <table>
        <thead>
          <tr>
            <th>Nama File</th>
            <th style="width:260px">Aksi</th>
          </tr>
        </thead>
        <tbody id="tbB">
          <tr>
            <td colspan="2" class="muted">Memuat...</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Modal Upload -->
  <div id="modal" class="modal" role="dialog" aria-modal="true" aria-labelledby="mTitle">
    <div class="inner">
      <div class="modal-head">
        <h3 id="mTitle">Upload</h3>
        <button class="btn ghost" onclick="closeModal()">Tutup</button>
      </div>
      <input type="file" id="file" class="field" />
      <div class="actions" style="margin-top:10px">
        <button id="btnUpload" class="btn primary" onclick="doUpload()">Upload</button>
        <div id="mMsg" class="muted" style="align-self:center"></div>
      </div>
    </div>
  </div>

  <script>
    // Ambil data dari meta
    const id = document.querySelector('meta[name="x-peserta-id"]').content;
    const isSuper = document.querySelector('meta[name="x-is-super"]').content === '1';

    // Elemen
    const pNama = document.getElementById('pNama');
    const chips = document.getElementById('chips');
    const avatar = document.getElementById('avatar');

    const tbS = document.getElementById('tbS');
    const tbB = document.getElementById('tbB');
    const emptyS = document.getElementById('emptyS');
    const emptyB = document.getElementById('emptyB');

    const modal = document.getElementById('modal');
    const mTitle = document.getElementById('mTitle');
    const mMsg = document.getElementById('mMsg');
    const fileInp = document.getElementById('file');
    const btnUpload = document.getElementById('btnUpload');
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
      mTitle.textContent = 'Upload ' + (t === 'sertifikat' ? 'Sertifikat' : 'Berkas');
      mMsg.textContent = '';
      fileInp.value = '';
      modal.style.display = 'flex';
    }

    function closeModal() {
      modal.style.display = 'none';
    }
    window.addEventListener('click', (e) => {
      if (e.target === modal) closeModal();
    });

    async function load() {
      const r = await fetch('api/peserta_detail_admin.php?id=' + encodeURIComponent(id));
      const j = await r.json();
      if (j.error) {
        pNama.textContent = j.error;
        chips.innerHTML = '';
        return;
      }
      const p = j.peserta,
        s = j.sertifikat || [],
        b = j.berkas || [];

      // Header profil
      pNama.textContent = p.nama || '-';
      avatar.textContent = initials(p.nama || '');
      chips.innerHTML = [
        p.program ? `<span class="chip">Program: ${p.program}</span>` : '',
        p.kelas ? `<span class="chip">Kelas: ${p.kelas}</span>` : '',
        p.ma ? `<span class="chip">MA: ${p.ma}</span>` : '',
        p.jurusan ? `<span class="chip">Jurusan: ${p.jurusan}</span>` : ''
      ].filter(Boolean).join('');

      renderFiles(tbS, emptyS, s, 'sertifikat');
      renderFiles(tbB, emptyB, b, 'berkas');

      // isi form super admin jika ada
      if (isSuper) {
        const fn = document.getElementById('f_nama');
        if (fn) fn.value = p.nama || '';
        const fp = document.getElementById('f_program');
        if (fp) fp.value = p.program || '12EXC';
        const fg = document.getElementById('f_gender');
        if (fg) fg.value = p.gender || 'PUTRA';
        const fm = document.getElementById('f_ma');
        if (fm) fm.value = p.ma || 'MA01';
        const fk = document.getElementById('f_kelas');
        if (fk) fk.value = p.kelas || '';
        const fj = document.getElementById('f_jurusan');
        if (fj) fj.value = p.jurusan || '-';
      }
    }

    function renderFiles(tbody, emptyEl, arr, type) {
      tbody.innerHTML = '';
      if (!Array.isArray(arr) || arr.length === 0) {
        emptyEl.style.display = 'block';
        return;
      }
      emptyEl.style.display = 'none';

      for (const f of arr) {
        const url = f.path;
        const tr = document.createElement('tr');
        tr.innerHTML = `
        <td>
          <div style="display:flex;flex-direction:column;gap:4px">
            <div style="font-weight:600">${f.nama_file}</div>
            <a href="${url}" target="_blank" rel="noopener" class="muted" style="font-size:12px">${url}</a>
          </div>
        </td>
        <td>
          <div class="actions">
            <a class="btn ghost" href="${url}" target="_blank" rel="noopener">Lihat</a>
            <a class="btn outline" href="${url}" download>Unduh</a>
            <button class="btn danger" onclick="del('${type}','${f.id}')">Hapus</button>
          </div>
        </td>`;
        tbody.appendChild(tr);
      }
    }

    async function doUpload() {
      const f = fileInp.files[0];
      if (!f) {
        mMsg.textContent = 'Pilih file dulu.';
        return;
      }
      btnUpload.disabled = true;
      mMsg.textContent = 'Mengunggah...';
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
          mMsg.textContent = 'Berhasil upload.';
          closeModal();
          load();
        } else {
          mMsg.textContent = j.error || 'Gagal upload';
        }
      } catch (err) {
        mMsg.textContent = 'Gagal upload';
      } finally {
        btnUpload.disabled = false;
      }
    }

    async function del(type, fileId) {
      if (!confirm('Yakin hapus file ini?')) return;
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
      if (j.ok) load();
      else alert(j.error || 'Gagal hapus');
    }

    async function saveBiodata() {
      if (!isSuper) {
        alert('Hanya super admin.');
        return;
      }
      const body = {
        id,
        nama: document.getElementById('f_nama').value.trim(),
        program: document.getElementById('f_program').value,
        gender: document.getElementById('f_gender').value,
        ma: document.getElementById('f_ma').value,
        kelas: document.getElementById('f_kelas').value.trim(),
        jurusan: document.getElementById('f_jurusan').value.trim()
      };
      const msg = document.getElementById('saveMsg');
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
        msg.style.display = 'block';
        if (j.ok) {
          msg.textContent = 'Tersimpan (' + j.action + ').';
          load();
        } else {
          msg.textContent = j.error || 'Gagal simpan';
        }
      } catch (e) {
        msg.style.display = 'block';
        msg.textContent = 'Gagal simpan';
      }
    }

    load();
  </script>
</body>

</html>
