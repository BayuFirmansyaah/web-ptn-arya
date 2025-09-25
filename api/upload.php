<?php
require __DIR__.'/../lib/auth.php';
require_admin();
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__.'/../lib/jsondb.php';

$pid  = $_POST['peserta_id'] ?? '';
$type = $_POST['type'] ?? '';
if (!$pid || !in_array($type, ['sertifikat','berkas'], true)) {
  echo json_encode(['ok'=>false,'error'=>'param tidak lengkap']); exit;
}

if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
  $err = $_FILES['file']['error'] ?? -1;
  echo json_encode(['ok'=>false,'error'=>'upload gagal (kode '.$err.')']); exit;
}

// bikin folder: uploads/<pid>/<type>/
$root = realpath(__DIR__.'/..'); if ($root === false) $root = dirname(__DIR__);
$dir  = $root . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . $pid . DIRECTORY_SEPARATOR . $type;
if (!is_dir($dir) && !@mkdir($dir, 0777, true)) {
  echo json_encode(['ok'=>false,'error'=>'gagal membuat folder upload']); exit;
}

// amankan nama file
$name = $_FILES['file']['name'];
$name = preg_replace('/[^\w\-. ]+/', '_', $name);
$target = $dir . DIRECTORY_SEPARATOR . $name;

if (!move_uploaded_file($_FILES['file']['tmp_name'], $target)) {
  echo json_encode(['ok'=>false,'error'=>'gagal menyimpan file']); exit;
}

// path relatif utk diakses dari web
$rel = 'uploads/'.$pid.'/'.$type.'/'.$name;

// simpan ke json index
$fileJson = ($type==='sertifikat') ? __DIR__.'/../data/sertifikat.json' : __DIR__.'/../data/berkas.json';
$rows = json_read($fileJson);
if (!is_array($rows)) $rows = [];
$rows[] = [
  'id'         => uniqid(substr($type,0,1)),
  'peserta_id' => $pid,
  'nama_file'  => $name,
  'path'       => $rel,
  'ts'         => date('c')
];
json_write($fileJson, $rows);

echo json_encode(['ok'=>true,'path'=>$rel]);