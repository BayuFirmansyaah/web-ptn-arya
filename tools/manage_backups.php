<?php
// tools/manage_backups.php
require_once __DIR__.'/../lib/auth.php';
require_admin();

$dir = realpath(__DIR__.'/../data');

function human_size($bytes){
  $u=['B','KB','MB','GB']; $i=0;
  while($bytes>=1024 && $i<count($u)-1){ $bytes/=1024; $i++; }
  return round($bytes,1).' '.$u[$i];
}
function list_backups($dir){
  $files = glob($dir.'/peserta.backup.*.json') ?: [];
  $list = [];
  foreach ($files as $f){
    $list[] = [
      'path'=>$f,
      'name'=>basename($f),
      'mtime'=>filemtime($f),
      'size'=>filesize($f),
    ];
  }
  usort($list, fn($a,$b)=>$b['mtime']<=>$a['mtime']); // terbaru dulu
  return $list;
}
function is_valid_backup_name($name){
  return (bool)preg_match('/^peserta\.backup\.\d{8}_\d{6}\.json$/', $name);
}

$msg = null; $err = null;

if ($_SERVER['REQUEST_METHOD']==='POST'){
  $action = $_POST['action'] ?? '';
  if ($action === 'delete_one'){
    $name = basename($_POST['name'] ?? '');
    if (!is_valid_backup_name($name)) { $err='Nama file tidak valid.'; }
    else {
      $path = $dir.DIRECTORY_SEPARATOR.$name;
      if (is_file($path)) { @unlink($path); $msg="Backup $name dihapus."; }
      else { $err='File tidak ditemukan.'; }
    }
  } elseif ($action === 'delete_selected'){
    $files = $_POST['files'] ?? [];
    $n=0;
    foreach ($files as $name){
      $name = basename($name);
      if (!is_valid_backup_name($name)) continue;
      $path = $dir.DIRECTORY_SEPARATOR.$name;
      if (is_file($path)){ @unlink($path); $n++; }
    }
    $msg = "Hapus terpilih: $n file.";
  } elseif ($action === 'keep_latest'){
    $keep = max(0, (int)($_POST['keep'] ?? 10));
    $list = list_backups($dir);
    $n = 0;
    foreach (array_slice($list, $keep) as $it){
      @unlink($it['path']); $n++;
    }
    $msg = "Menyisakan $keep terbaru, terhapus $n file.";
  } elseif ($action === 'delete_older_days'){
    $days = max(1, (int)($_POST['days'] ?? 30));
    $threshold = time() - ($days * 86400);
    $list = list_backups($dir);
    $n=0;
    foreach ($list as $it){
      if ($it['mtime'] < $threshold){ @unlink($it['path']); $n++; }
    }
    $msg = "Hapus yang lebih lama dari $days hari: $n file.";
  }
}

$list = list_backups($dir);
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Kelola Backup Peserta</title>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <style>
    body{font-family:Arial,Helvetica,sans-serif;background:#f6f7f9;margin:30px;color:#111}
    .box{max-width:900px;margin:0 auto;background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:16px}
    h1{font-size:20px;margin:0 0 14px}
    table{width:100%;border-collapse:collapse}
    th,td{padding:10px;border-bottom:1px solid #f1f5f9;font-size:14px}
    th{text-align:left;background:#f8fafc}
    .muted{color:#64748b;font-size:13px}
    .row-actions{display:flex;gap:8px;flex-wrap:wrap;margin:12px 0}
    .btn{display:inline-block;padding:8px 12px;border-radius:8px;border:1px solid #e5e7eb;background:#fff;cursor:pointer;text-decoration:none;color:#111}
    .btn.primary{background:#10b981;color:#fff;border-color:#10b981}
    .btn.danger{background:#ef4444;color:#fff;border-color:#ef4444}
    .info{padding:10px;border-radius:8px;margin:12px 0;background:#eef2ff;border:1px solid #e5e7eb}
    .ok{padding:10px;border-radius:8px;margin:12px 0;background:#ecfdf5;border:1px solid #a7f3d0;color:#065f46}
    .err{padding:10px;border-radius:8px;margin:12px 0;background:#fee2e2;border:1px solid #fecaca;color:#7f1d1d}
    .toplinks{display:flex;justify-content:space-between;align-items:center;gap:8px;margin-bottom:8px}
  </style>
</head>
<body>
<div class="box">
  <div class="toplinks">
    <h1>Kelola Backup Peserta</h1>
    <div>
      <a class="btn" href="import_csv.php">← Kembali ke Import</a>
      <a class="btn" href="../dashboard.php">Dashboard</a>
    </div>
  </div>
  <div class="muted">Folder: <code>data/</code> • Pola file: <code>peserta.backup.YYYYmmdd_HHMMSS.json</code></div>

  <?php if($msg): ?><div class="ok"><?=htmlspecialchars($msg,ENT_QUOTES,'UTF-8')?></div><?php endif; ?>
  <?php if($err): ?><div class="err"><?=htmlspecialchars($err,ENT_QUOTES,'UTF-8')?></div><?php endif; ?>

  <form method="post">
    <div class="row-actions">
      <input type="hidden" name="action" value="keep_latest">
      <label class="muted">Simpan terbaru</label>
      <select name="keep">
        <option value="5">5</option>
        <option value="10" selected>10</option>
        <option value="20">20</option>
        <option value="50">50</option>
      </select>
      <button class="btn primary" type="submit">Terapkan</button>
    </div>
  </form>

  <form method="post" style="margin-top:-6px">
    <div class="row-actions">
      <input type="hidden" name="action" value="delete_older_days">
      <label class="muted">Hapus yang lebih lama dari</label>
      <select name="days">
        <option value="7">7</option>
        <option value="14">14</option>
        <option value="30" selected>30</option>
        <option value="90">90</option>
      </select>
      <span class="muted">hari</span>
      <button class="btn danger" type="submit">Hapus</button>
    </div>
  </form>

  <form method="post">
    <input type="hidden" name="action" value="delete_selected">
    <table>
      <thead>
        <tr>
          <th style="width:36px"><input type="checkbox" id="checkAll" onclick="toggleAll(this)"></th>
          <th>Nama File</th>
          <th>Waktu</th>
          <th>Ukuran</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
      <?php if(!$list): ?>
        <tr><td colspan="5" class="muted">Tidak ada backup.</td></tr>
      <?php else: foreach($list as $it): ?>
        <tr>
          <td><input type="checkbox" name="files[]" value="<?=htmlspecialchars($it['name'],ENT_QUOTES,'UTF-8')?>"></td>
          <td><code><?=htmlspecialchars($it['name'],ENT_QUOTES,'UTF-8')?></code></td>
          <td><?=date('Y-m-d H:i:s', $it['mtime'])?></td>
          <td><?=human_size($it['size'])?></td>
          <td>
            <form method="post" style="display:inline" onsubmit="return confirm('Hapus file ini?')">
              <input type="hidden" name="action" value="delete_one">
              <input type="hidden" name="name" value="<?=htmlspecialchars($it['name'],ENT_QUOTES,'UTF-8')?>">
              <button class="btn danger" type="submit">Hapus</button>
            </form>
          </td>
        </tr>
      <?php endforeach; endif; ?>
      </tbody>
    </table>

    <?php if($list): ?>
      <div class="row-actions">
        <button class="btn danger" type="submit" onclick="return confirm('Hapus file terpilih?')">Hapus Terpilih</button>
      </div>
    <?php endif; ?>
  </form>
</div>

<script>
function toggleAll(cb){
  document.querySelectorAll('input[name="files[]"]').forEach(x=>x.checked = cb.checked);
}
</script>
</body>
</html>
