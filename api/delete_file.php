<?php
require __DIR__.'/../lib/auth.php';
require_admin();
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__.'/../lib/jsondb.php';

$id = $_GET['id'] ?? '';
$type = $_GET['type'] ?? '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (!$id || !$type)) {
  $raw = file_get_contents('php://input');
  if ($raw) { $j=json_decode($raw,true); if(is_array($j)){ $id=$id?:($j['id']??''); $type=$type?:($j['type']??''); } }
}
if (!$id || !in_array($type,['sertifikat','berkas'],true)) {
  echo json_encode(['ok'=>false,'error'=>'param tidak lengkap']); exit;
}

$fileJson = $type==='sertifikat' ? __DIR__.'/../data/sertifikat.json' : __DIR__.'/../data/berkas.json';
$data = json_read($fileJson);

$foundIndex = null; $found=null;
foreach ($data as $i => $row) {
  if (($row['id'] ?? '') === $id) { $foundIndex = $i; $found=$row; break; }
}
if ($foundIndex === null) { echo json_encode(['ok'=>false,'error'=>'data tidak ditemukan']); exit; }

// enforce scope via peserta_id
$pid = $found['peserta_id'] ?? '';
require_can_manage_pid($pid);

$abs = __DIR__ . '/../' . ($found['path'] ?? '');
if ($abs && file_exists($abs)) @unlink($abs);

array_splice($data, $foundIndex, 1);
json_write($fileJson, $data);

echo json_encode(['ok'=>true]);