<?php
// tools/upload_json.php
require_once __DIR__.'/../lib/auth.php';
require_admin();
require_super_admin();
require_once __DIR__.'/../lib/jsondb.php';

function norm_program($v){ $v=strtoupper(trim($v)); if(in_array($v,['12EXC','EXC','EXCELLENT'],true))return'12EXC'; if(in_array($v,['12CI','CI','CERDAS ISTIMEWA','CERDAS','ISTIMEWA'],true))return'12CI'; return $v; }
function norm_gender($v){ $v=strtoupper(trim($v)); if(in_array($v,['L','LAKI','LAKI-LAKI','PUTRA'],true))return'PUTRA'; if(in_array($v,['P','PEREMPUAN','PUTRI'],true))return'PUTRI'; return $v; }
function norm_ma($v){ $v=strtoupper(trim($v)); $v=str_replace('MA','',$v); $v=preg_replace('/\D+/','',$v); if($v==='1')$v='01'; if($v==='3')$v='03'; if(in_array($v,['01','03'],true)) return 'MA'.$v; return $v?('MA'.$v):'MA01'; }
function norm_kelas($v){ return strtoupper(trim(str_replace(' ','',$v))); }

$summary=null; $error=null;

if($_SERVER['REQUEST_METHOD']==='POST'){
  if(!isset($_FILES['json']) || $_FILES['json']['error']!==UPLOAD_ERR_OK){
    $error='Upload JSON gagal.';
  }else{
    $raw = file_get_contents($_FILES['json']['tmp_name']);
    $data = json_decode($raw, true);
    if(!is_array($data)){
      $error='Format JSON tidak valid (harus array of objects).';
    }else{
      // Bisa juga data = { "items":[...] }
      if(isset($data['items']) && is_array($data['items'])) $data = $data['items'];

      $clean=[]; $added=0; $fixed=0;
      foreach($data as $r){
        if(!is_array($r)) continue;
        $id      = trim($r['id'] ?? '');
        $nama    = trim($r['nama'] ?? '');
        $program = norm_program($r['program'] ?? '');
        $gender  = norm_gender($r['gender'] ?? '');
        $ma      = norm_ma($r['ma'] ?? '');
        $kelas   = norm_kelas($r['kelas'] ?? '');
        $jurusan = trim($r['jurusan'] ?? '');
        if($nama==='') continue;

        if($id===''){ $id=uniqid('p'); $fixed++; }
        if($jurusan==='') $jurusan='-';

        $clean[] = [
          'id'=>$id,'nama'=>$nama,'program'=>$program,'kelas'=>$kelas,
          'ma'=>$ma,'gender'=>$gender,'jurusan'=>$jurusan
        ];
        $added++;
      }

      // backup lama
      $dbPath = __DIR__.'/../data/peserta.json';
      if(file_exists($dbPath)){
        $backup = __DIR__.'/../data/peserta.backup.'.date('Ymd_His').'.json';
@copy($dbPath, $backup);
      }

      json_write($dbPath, $clean);
      $summary = [
        'uploaded_count'=>count($data),
        'saved'=>count($clean),
        'auto_generated_id'=>$fixed,
        'target'=>'data/peserta.json'
      ];
    }
  }
}
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Upload JSON Peserta</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f6f7f9;
            margin: 30px
        }

        .box {
            max-width: 720px;
            margin: 0 auto;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 16px
        }

        h1 {
            font-size: 20px;
            margin: 0 0 14px
        }

        .muted {
            color: #6b7280;
            font-size: 13px
        }

        .alert {
            padding: 10px;
            border-radius: 8px;
            margin: 12px 0
        }

        .alert.err {
            background: #fee2e2;
            border: 1px solid #fecaca;
            color: #7f1d1d
        }

        .alert.ok {
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #065f46
        }

        .btn {
            display: inline-block;
            background: #10b981;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 10px 14px;
            cursor: pointer
        }

        input[type=file] {
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            background: #fff
        }

        a {
            color: #2563eb;
            text-decoration: none
        }

        a:hover {
            text-decoration: underline
        }
    </style>
</head>

<body>
    <div class="box">
        <h1>Upload JSON Peserta</h1>
        <div class="muted">File JSON akan <b>mengganti</b> <code>data/peserta.json</code> (dengan backup otomatis).
        </div>
        <form method="post" enctype="multipart/form-data" style="margin-top:14px">
            <input type="file" name="json" accept=".json,application/json" required>
            <button class="btn" type="submit">Upload & Simpan</button>
        </form>
        <details style="margin-top:14px">
            <summary>Contoh struktur JSON</summary>
            <pre>[
  {"id":"p001","nama":"ARYANSYAH","program":"12EXC","kelas":"B1","ma":"MA01","gender":"PUTRA","jurusan":"FISMAT"},
  {"nama":"SITI AISYAH","program":"12CI","kelas":"E","ma":"MA03","gender":"PUTRI","jurusan":"IPS"} // id kosong = auto
]</pre>
        </details>

        <?php if($error): ?>
        <div class="alert err"><?=htmlspecialchars($error,ENT_QUOTES,'UTF-8')?></div>
        <?php endif; ?>

        <?php if($summary): ?>
        <div class="alert ok">
            <div><b>Berhasil.</b></div>
            <div>Baris di file: <?=$summary['uploaded_count']?>, Disimpan: <?=$summary['saved']?>, ID auto:
                <?=$summary['auto_generated_id']?></div>
            <div>File aktif: <code><?=$summary['target']?></code></div>
        </div>
        <?php endif; ?>

        <div style="margin-top:12px"><a href="../dashboard.php">← Kembali ke Dashboard</a></div>
    </div>
</body>

</html>