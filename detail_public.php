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
      --bg: #f7f8fa;
      --card: #ffffff;
      --line: #e5e7eb;
      --muted: #6b7280;
      --text: #111827;
      --brand: #1d4ed8;
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
      margin: 0 auto 14px
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

    .section {
      margin-top: 16px
    }

    .section h3 {
      margin: 0 0 8px;
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

    .btn.ghost {
      background: #fff;
      border: 1px solid var(--line);
      color: #111
    }

    .btn.outline {
      background: #fff;
      border: 1px solid var(--line)
    }
  </style>
</head>

<body>

  <div class="top">
    <a href="pencarian.php">&larr; Kembali</a>
  </div>

  <div class="box">
    <!-- Profil -->
    <div class="profile-card">
      <div class="avatar" id="avatar">?</div>
      <div>
        <div id="pNama" style="font-weight:700;font-size:18px">Memuat...</div>
        <div class="chips" id="chips"></div>
      </div>
    </div>

    <!-- Sertifikat -->
    <div class="section">
      <h3>Sertifikat</h3>
      <div id="emptyS" class="empty" style="display:none">Tidak ada sertifikat.</div>
      <table>
        <thead>
          <tr>
            <th>Nama File</th>
            <th style="width:220px">Aksi</th>
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
      <h3>Berkas</h3>
      <div id="emptyB" class="empty" style="display:none">Tidak ada berkas.</div>
      <table>
        <thead>
          <tr>
            <th>Nama File</th>
            <th style="width:220px">Aksi</th>
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

  <script>
    const id = document.querySelector('meta[name="x-peserta-id"]').content;

    const pNama = document.getElementById('pNama');
    const chips = document.getElementById('chips');
    const avatar = document.getElementById('avatar');

    const tbS = document.getElementById('tbS');
    const tbB = document.getElementById('tbB');
    const emptyS = document.getElementById('emptyS');
    const emptyB = document.getElementById('emptyB');

    function initials(name) {
      if (!name) return '?';
      const parts = name.trim().split(/\s+/);
      const first = (parts[0] || '')[0] || '';
      const last = (parts[parts.length - 1] || '')[0] || '';
      return (first + last).toUpperCase();
    }

    function renderFiles(tbody, emptyEl, arr) {
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
          <div style="display:flex;gap:8px;flex-wrap:wrap">
            <a class="btn ghost"   href="${url}" target="_blank" rel="noopener">Lihat</a>
            <a class="btn outline" href="${url}" download>Unduh</a>
          </div>
        </td>`;
        tbody.appendChild(tr);
      }
    }

    (async () => {
      try {
        const r = await fetch('api/peserta_detail.php?id=' + encodeURIComponent(id));
        const j = await r.json();
        if (j.error) {
          pNama.textContent = j.error;
          chips.innerHTML = '';
          return;
        }
        const p = j.profil,
          s = j.sertifikat || [],
          b = j.berkas || [];

        pNama.textContent = p.nama || '-';
        avatar.textContent = initials(p.nama || '');
        chips.innerHTML = [
          p.program ? `<span class="chip">Program: ${p.program}</span>` : '',
          p.kelas ? `<span class="chip">Kelas: ${p.kelas}</span>` : '',
          p.ma ? `<span class="chip">MA: ${p.ma}</span>` : '',
          p.jurusan ? `<span class="chip">Jurusan: ${p.jurusan}</span>` : ''
        ].filter(Boolean).join('');

        renderFiles(tbS, emptyS, s);
        renderFiles(tbB, emptyB, b);
      } catch (e) {
        pNama.textContent = 'Gagal memuat data';
        chips.innerHTML = '';
      }
    })();
  </script>
</body>

</html>
