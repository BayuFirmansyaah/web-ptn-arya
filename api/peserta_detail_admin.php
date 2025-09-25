<?php
require __DIR__.'/../lib/auth.php';
require_admin();
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__.'/../lib/jsondb.php';

$id = $_GET['id'] ?? '';
if (!$id) { echo json_encode(['error'=>'param id kosong']); exit; }

// baca peserta.json
$ps = json_read(__DIR__.'/../data/peserta.json');
if (!is_array($ps)) $ps = [];

$found = null;
foreach ($ps as $p){
  if (($p['id'] ?? '') === $id){
    $found = $p; break;
  }
}
if (!$found){ echo json_encode(['error'=>'peserta tidak ditemukan']); exit; }

// (opsional) batasi oleh scope admin biasa
$admin = $_SESSION['admin'] ?? [];
$role  = strtolower($admin['role'] ?? 'admin');
$scope = $admin['scope'] ?? [];
if ($role !== 'super') {
  // contoh validasi ringan: jika scope.program diset, harus cocok
  $scProg = strtoupper($scope['program'] ?? '');
  if ($scProg && strtoupper($found['program'] ?? '') !== $scProg) {
    echo json_encode(['error'=>'tidak diizinkan']); exit;
  }
  // bisa tambahkan validasi scope lain di sini (gender/ma) sesuai kebutuhan
}

// sertifikat & berkas
$sertAll = json_read(__DIR__.'/../data/sertifikat.json'); if(!is_array($sertAll)) $sertAll=[];
$berkAll = json_read(__DIR__.'/../data/berkas.json');     if(!is_array($berkAll)) $berkAll=[];
$sert = array_values(array_filter($sertAll, fn($r)=>(($r['peserta_id']??'')===$id)));
$berk = array_values(array_filter($berkAll, fn($r)=>(($r['peserta_id']??'')===$id)));

echo json_encode(['peserta'=>$found,'sertifikat'=>$sert,'berkas'=>$berk], JSON_UNESCAPED_UNICODE);