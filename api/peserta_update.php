<?php
require __DIR__.'/../lib/auth.php';
require_admin();
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__.'/../lib/jsondb.php';
require_once __DIR__.'/../lib/normalize.php';

// input JSON
$raw = file_get_contents('php://input');
$body = $raw ? json_decode($raw, true) : [];
$id = $body['id'] ?? '';

if (!$id) { echo json_encode(['ok'=>false,'error'=>'id wajib']); exit; }
$existing = require_can_manage_pid($id); // pastikan ada + dalam scope

// field yang boleh diubah
$nama    = trim($body['nama']    ?? $existing['nama']);
$program = normalize_program($body['program'] ?? $existing['program']);
$gender  = normalize_gender($body['gender']  ?? $existing['gender']);
$kelas   = normalize_kelas($body['kelas']    ?? $existing['kelas']);
$ma      = normalize_ma($body['ma']          ?? $existing['ma']);
$jurusan = trim($body['jurusan'] ?? ($existing['jurusan'] ?? '-'));

// kalau admin bukan super, hasil akhirnya tidak boleh keluar scope
if (!is_super_admin()) {
  $sc = admin_scope();
  if ($sc['program'] !== '*' && strtoupper($program) !== strtoupper($sc['program'])) {
    echo json_encode(['ok'=>false,'error'=>'program tidak boleh diubah keluar scope']); exit;
  }
  if ($sc['gender']  !== '*' && strtoupper($gender)  !== strtoupper($sc['gender'])) {
    echo json_encode(['ok'=>false,'error'=>'gender tidak boleh diubah keluar scope']); exit;
  }
}

$path = __DIR__.'/../data/peserta.json';
$arr = json_read($path);
$found = false;
for ($i=0; $i<count($arr); $i++){
  if (($arr[$i]['id'] ?? '') === $id) {
    $arr[$i] = [
      'id'=>$id,
      'nama'=>$nama,
      'program'=>$program,
      'gender'=>$gender,
      'kelas'=>$kelas,
      'ma'=>$ma,
      'jurusan'=>($jurusan===''?'-':$jurusan)
    ];
    $found = true; break;
  }
}
if (!$found){ echo json_encode(['ok'=>false,'error'=>'peserta tidak ditemukan']); exit; }

json_write($path, $arr);
echo json_encode(['ok'=>true,'peserta'=>$arr[$i]]);