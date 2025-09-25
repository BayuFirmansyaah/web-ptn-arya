<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__.'/../lib/jsondb.php';

$id = $_GET['id'] ?? '';
if (!$id) { echo json_encode(['error'=>'id kosong']); exit; }

$peserta = null;
foreach (json_read(__DIR__.'/../data/peserta.json') as $p) {
  if (($p['id'] ?? '') === $id) { $peserta = $p; break; }
}
if (!$peserta) { echo json_encode(['error'=>'peserta tidak ditemukan']); exit; }

$sertifikat = array_values(array_filter(json_read(__DIR__.'/../data/sertifikat.json'), fn($s)=>($s['peserta_id'] ?? '') === $id));
$berkas     = array_values(array_filter(json_read(__DIR__.'/../data/berkas.json'), fn($b)=>($b['peserta_id'] ?? '') === $id));

echo json_encode(['profil'=>$peserta,'sertifikat'=>$sertifikat,'berkas'=>$berkas], JSON_UNESCAPED_UNICODE);