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
      --bg: #f7f8fa;
      --card: #ffffff;
      --line: #e5e7eb;
      --muted: #6b7280;
      --text: #111827;
      --brand: #1d4ed8;
      --ok: #0ea5e9;
      --danger: #e11d48;
    }

    * {
      box-sizing: border-box
    }

    body {
      font-family: Arial, Helvetica, sans-serif;
      background: var(--bg);
      margin: 18px;
      color: var(--text)
    }

    a {
      text-decoration: none;
      color: var(--brand)
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
      border: 1px solid var(--line);
      border-radius: 10px;
      background: #fff
    }

    .top .link:hover {
      background: #f3f4f6
    }

    .box {
      max-width: 960px;
      margin: 0 auto;
      background: var(--card);
      border: 1px solid var(--line);
      border-radius: 14px;
      padding: 16px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, .04)
    }

    /* Profile header */
    .profile-card {
      display: flex;
      gap: 14px;
      align-items: center;
      background: #f3f4f6;
      border-radius: 12px;
      padding: 12px
    }

    .avatar {
      width: 54px;
      height: 54px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #dbeafe;
      color: #1e40af;
      font-weight: 700;
      font-size: 18px;
      user-select: none
    }

    .chips {
      display: flex;
      gap: 8px;
      flex-wrap: wrap;
      margin-top: 6px
    }

    .chip {
      background: #eef2ff;
      color: #3730a3;
      border: 1px solid #c7d2fe;
      border-radius: 999px;
      padding: 4px 10px;
      font-size: 12px
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
      font-size: 16px
    }

    table {
      width: 100%;
      border-collapse: collapse;
      border: 1px solid var(--line);
      border-radius: 10px;
      overflow: hidden
    }

    thead th {
      background: #f8fafc;
      font-weight: 600;
      font-size: 13px;
      color: #111827;
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

    .muted {
      color: var(--muted)
    }

    .empty {
      padding: 12px;
      border: 1px dashed var(--line);
      border-radius: 10px;
      color: var(--muted);
      background: #fafafa
    }

    .btn {
      padding: 8px 12px;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      font-size: 13px
    }

    .btn.primary {
      background: var(--brand);
      color: #fff
    }

    .btn.primary:hover {
      filter: brightness(.95)
    }

    .btn.ghost {
      background: #fff;
      border: 1px solid var(--line);
      color: #111
    }

    .btn.ghost:hover {
      background: #f3f4f6
    }

    .btn.outline {
      background: #fff;
      border: 1px solid var(--line)
    }

    .btn.danger {
      background: var(--danger);
      color: #fff
    }

    .btn[disabled] {
      opacity: .6;
      cursor: not-allowed
    }

    /* Modal */
    .modal {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, .5);
      align-items: center;
      justify-content: center;
      z-index: 50
    }

    .modal .inner {
      background: #fff;
      border-radius: 14px;
      padding: 16px;
      width: min(460px, 92vw);
      box-shadow: 0 10px 30px rgba(0, 0, 0, .2)
    }

    .modal-head {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 10px
    }

    .field {
      display: block;
      width: 100%;
      padding: 9px;
      border: 1px solid var(--line);
      border-radius: 10px
    }

    .alert {
      padding: 10px;
      border-radius: 10px;
      background: #f3f4f6;
      margin-top: 10px
    }

    .actions {
      display: flex;
      gap: 8px;
      flex-wrap: wrap
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
