<?php
// api/peserta_list_admin.php
require_once __DIR__.'/../lib/auth.php';
require_admin();
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__.'/../lib/jsondb.php';

$admin = $_SESSION['admin'] ?? [];
$role  = strtoupper($admin['role'] ?? 'ADMIN');
$scope = $admin['scope'] ?? [];
$scProgram = strtoupper($scope['program'] ?? '');
$scGender  = strtoupper($scope['gender'] ?? '');
$isSuper   = ($role === 'SUPER') || ($scProgram === 'ALL');

$program = strtoupper(trim($_GET['program'] ?? ''));
$gender  = strtoupper(trim($_GET['gender'] ?? ''));
$ma      = strtoupper(trim($_GET['ma'] ?? ''));
$kelas   = strtoupper(trim($_GET['kelas'] ?? ''));
$q       = strtolower(trim($_GET['q'] ?? ''));

$page  = max(1, (int)($_GET['page']  ?? 1));
$limit = max(1, min(100, (int)($_GET['limit'] ?? 50)));
$start = ($page - 1) * $limit;

$items = json_read(__DIR__.'/../data/peserta.json');
if (!is_array($items)) $items = [];

$filtered = array_values(array_filter($items, function($d) use($isSuper,$scProgram,$scGender,$program,$gender,$ma,$kelas,$q){
  $P = strtoupper($d['program'] ?? '');
  $G = strtoupper($d['gender'] ?? '');
  $M = strtoupper($d['ma'] ?? '');
  $K = strtoupper($d['kelas'] ?? '');
  $N = strtolower($d['nama'] ?? '');

  // Scope admin non-super
  if (!$isSuper) {
    if ($scProgram && $P !== $scProgram) return false;
    if ($scGender  && $G !== $scGender)   return false;
  }

  // Filter dari UI
  if ($program && $P !== $program) return false;
  if ($gender && $G !== $gender)   return false;
  if ($ma && $M !== $ma)           return false;
  if ($kelas && $K !== $kelas)     return false;
  if ($q && strpos($N, $q) === false) return false;

  return true;
}));

$total = count($filtered);
$slice = array_slice($filtered, $start, $limit);

echo json_encode([
  'items' => $slice,
  'total' => $total,
  'page'  => $page,
  'limit' => $limit
], JSON_UNESCAPED_UNICODE);