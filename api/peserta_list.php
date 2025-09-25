<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__.'/../lib/jsondb.php';

$program = $_GET['program'] ?? '';
$gender  = $_GET['gender'] ?? '';
$ma      = $_GET['ma'] ?? '';
$kelas   = $_GET['kelas'] ?? '';
$q       = strtolower(trim($_GET['q'] ?? ''));
$sort    = $_GET['sort'] ?? 'name_asc';

// paging
$page  = max(1, (int)($_GET['page']  ?? 1));
$limit = max(1, min(1000, (int)($_GET['limit'] ?? 50)));
$start = ($page - 1) * $limit;

$items = json_read(__DIR__.'/../data/peserta.json');
if (!is_array($items)) $items = [];

$filtered = array_values(array_filter($items, function($d) use($program,$gender,$ma,$kelas,$q){
  if ($program && strtoupper($d['program'] ?? '') !== strtoupper($program)) return false;
  if ($gender  && strtoupper($d['gender']  ?? '') !== strtoupper($gender))  return false;
  if ($ma      && strtoupper($d['ma']      ?? '') !== strtoupper($ma))      return false;
  if ($kelas   && strtoupper($d['kelas']   ?? '') !== strtoupper($kelas))   return false;

  if ($q) {
    $hay = strtolower(trim(($d['nama']??'').' '.($d['id']??'').' '.($d['jurusan']??'')));
    if (strpos($hay, $q) === false) return false;
  }
  return true;
}));

// sorting
$lc = fn($v)=>mb_strtolower((string)$v, 'UTF-8');
usort($filtered, function($a,$b) use($sort,$lc){
  switch ($sort) {
    case 'name_desc':   return strcmp($lc($b['nama']??''),  $lc($a['nama']??''));
    case 'kelas_asc':   return strcmp($lc($a['kelas']??''), $lc($b['kelas']??''));
    case 'ma_asc':      return strcmp($lc($a['ma']??''),    $lc($b['ma']??''));
    case 'updated_desc':
      $ua = (int)($a['updated_at'] ?? 0);
      $ub = (int)($b['updated_at'] ?? 0);
      if ($ua === $ub) return strcmp($lc($a['nama']??''), $lc($b['nama']??''));
      return ($ub <=> $ua);
    case 'name_asc':
    default:            return strcmp($lc($a['nama']??''),  $lc($b['nama']??''));
  }
});

$total = count($filtered);
$slice = array_slice($filtered, $start, $limit);

echo json_encode([
  'items' => $slice,
  'total' => $total,
  'page'  => $page,
  'limit' => $limit
], JSON_UNESCAPED_UNICODE);