<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__.'/../lib/jsondb.php';

$u = trim($_POST['username'] ?? '');
$p = trim($_POST['password'] ?? '');
if ($u==='' || $p===''){ echo json_encode(['ok'=>false,'error'=>'Username / password kosong']); exit; }

$admins = json_read(__DIR__.'/../data/admins.json') ?: [];
$found = null;
foreach ($admins as $a) { if (($a['username'] ?? '') === $u) { $found = $a; break; } }
if (!$found || !password_verify($p, $found['password_hash'] ?? '')) {
  echo json_encode(['ok'=>false,'error'=>'Username atau password salah']); exit;
}
unset($found['password_hash']);
$_SESSION['admin'] = $found;
echo json_encode(['ok'=>true]);