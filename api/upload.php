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

// Security checks
$file = $_FILES['file'];

// Check file size (max 5MB)
$maxSize = 5 * 1024 * 1024;
if ($file['size'] > $maxSize) {
  echo json_encode(['ok'=>false,'error'=>'file terlalu besar (max 5MB)']); exit;
}

// Allowed file types
$allowedTypes = [
  'image/jpeg', 'image/jpg', 'image/png', 'image/gif',
  'application/pdf', 
  'application/msword',
  'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
];

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);

if (!in_array($mimeType, $allowedTypes)) {
  echo json_encode(['ok'=>false,'error'=>'tipe file tidak diizinkan']); exit;
}

// Check file extension
$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx'];
$extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
if (!in_array($extension, $allowedExtensions)) {
  echo json_encode(['ok'=>false,'error'=>'ekstensi file tidak diizinkan']); exit;
}

// Sanitize peserta_id to prevent directory traversal
$pid = preg_replace('/[^a-zA-Z0-9_-]/', '', $pid);
if (empty($pid)) {
  echo json_encode(['ok'=>false,'error'=>'peserta_id tidak valid']); exit;
}

// bikin folder: uploads/<pid>/<type>/
$root = realpath(__DIR__.'/..'); if ($root === false) $root = dirname(__DIR__);
$dir  = $root . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . $pid . DIRECTORY_SEPARATOR . $type;
if (!is_dir($dir) && !@mkdir($dir, 0755, true)) {
  echo json_encode(['ok'=>false,'error'=>'gagal membuat folder upload']); exit;
}

// Generate secure filename
$extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
$basename = preg_replace('/[^\w\-]/', '_', pathinfo($file['name'], PATHINFO_FILENAME));
$basename = substr($basename, 0, 50); // limit length
$name = $basename . '_' . uniqid() . '.' . $extension;
$target = $dir . DIRECTORY_SEPARATOR . $name;

// Additional security: check if file already exists
if (file_exists($target)) {
  $name = $basename . '_' . uniqid() . '_' . time() . '.' . $extension;
  $target = $dir . DIRECTORY_SEPARATOR . $name;
}

if (!move_uploaded_file($file['tmp_name'], $target)) {
  echo json_encode(['ok'=>false,'error'=>'gagal menyimpan file']); exit;
}

// Set secure file permissions
chmod($target, 0644);

// path relatif utk diakses dari web
$rel = 'uploads/'.$pid.'/'.$type.'/'.$name;

// simpan ke json index
$fileJson = ($type==='sertifikat') ? __DIR__.'/../data/sertifikat.json' : __DIR__.'/../data/berkas.json';
$rows = json_read($fileJson);
if (!is_array($rows)) $rows = [];
$rows[] = [
  'id'         => uniqid(substr($type,0,1)),
  'peserta_id' => $pid,
  'nama_file'  => $file['name'], // original name for display
  'saved_name' => $name,         // actual saved filename
  'path'       => $rel,
  'size'       => $file['size'],
  'mime_type'  => $mimeType,
  'ts'         => date('c')
];
json_write($fileJson, $rows);

echo json_encode(['ok'=>true,'path'=>$rel]);