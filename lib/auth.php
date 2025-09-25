<?php
// lib/auth.php
session_start();

function is_admin() {
  return isset($_SESSION['admin']) && is_array($_SESSION['admin']);
}
function require_admin() {
  if (!is_admin()) { header('Location: login.php'); exit; }
}
function current_admin() {
  return $_SESSION['admin'] ?? null;
}
function is_super_admin() {
  $a = current_admin();
  if (!$a) return false;
  $role  = strtolower($a['role'] ?? '');
  $scopeProgram = strtoupper($a['scope']['program'] ?? '');
  return ($role === 'super') || ($scopeProgram === 'ALL');
}
function require_super_admin() {
  if (!is_super_admin()) { http_response_code(403); echo 'FORBIDDEN'; exit; }
}