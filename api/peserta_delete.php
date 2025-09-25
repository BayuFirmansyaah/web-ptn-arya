<?php
require_once __DIR__.'/../lib/auth.php';
require_admin();
require_super_admin();
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__.'/../lib/jsondb.php';

$id = trim($_POST['id'] ?? '');
if($id===''){ echo json_encode(['ok'=>false,'error'=>'id kosong']); exit; }

$changed=false;

// hapus dari peserta.json
$pPath = __DIR__.'/../data/peserta.json';
$pArr  = json_read($pPath); if(!is_array($pArr)) $pArr=[];
$before=count($pArr);
$pArr = array_values(array_filter($pArr, fn($r)=>($r['id']??'')!==$id));
$changed = json_write($pPath,$pArr);

// bersihkan sertifikat/berkas yang terkait
foreach (['sertifikat','berkas'] as $t){
  $fPath = __DIR__."/../data/{$t}.json";
  $arr   = json_read($fPath); if(!is_array($arr)) $arr=[];
  $arr   = array_values(array_filter($arr, fn($r)=>($r['peserta_id']??'')!==$id));
  json_write($fPath, $arr);
}

// hapus folder uploads
$dir = __DIR__."/../uploads/$id";
if (is_dir($dir)) {
  $it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
  $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
  foreach($files as $file){ $file->isDir()? rmdir($file->getRealPath()) : @unlink($file->getRealPath()); }
  @rmdir($dir);
}

echo json_encode(['ok'=>true,'deleted_from_peserta'=>($before-count($pArr))]);
