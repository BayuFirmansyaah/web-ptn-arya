<?php
// api/peserta_list_admin.php
require_once __DIR__.'/../lib/auth.php';
require_admin();
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__.'/../lib/jsondb.php';

$sc = admin_scope(); // ['program'=>...,'gender'=>...]

$program = $_GET['program'] ?? '';
$gender  = $_GET['gender'] ?? '';
$ma      = $_GET['ma'] ?? '';
$kelas   = $_GET['kelas'] ?? '';
$q       = strtolower(trim($_GET['q'] ?? ''));

// paging (optional)
$page  = max(1, (int)($_GET['page']  ?? 1));
$limit = max(1, min(100, (int)($_GET['limit'] ?? 50)));
$start = ($page - 1) * $limit;

$items = json_read(__DIR__.'/../data/peserta.json');

// TERAPKAN SCOPE: intersect antara filter user & scope admin
$filtered = array_values(array_filter($items, function($d) use($program,$gender,$ma,$kelas,$q,$sc){
  if ($sc['program'] !== '*' && strtoupper($d['program'] ?? '') !== strtoupper($sc['program'])) return false;
  if ($sc['gender']  !== '*' && strtoupper($d['gender']  ?? '') !== strtoupper($sc['gender']))  return false;

  if ($program && ($d['program'] ?? '') !== $program) return false;
  if ($gender && ($d['gender'] ?? '') !== $gender) return false;
  if ($ma && ($d['ma'] ?? '') !== $ma) return false;
  if ($kelas && ($d['kelas'] ?? '') !== $kelas) return false;
  if ($q && strpos(strtolower($d['nama'] ?? ''), $q) === false) return false;
  return true;
}));

$total = count($filtered);
$slice = array_slice($filtered, $start, $limit);

echo json_encode([
  'items'=>$slice,
  'total'=>$total,
  'page'=>$page,
  'limit'=>$limit
], JSON_UNESCAPED_UNICODE);