<?php
// lib/jsondb.php - dengan file locking & write atomik

function json_read($path) {
  if (!file_exists($path)) return [];
  $fp = fopen($path, 'r');
  if (!$fp) return [];
  // shared lock saat baca
  if (flock($fp, LOCK_SH)) {
    $txt = stream_get_contents($fp);
    flock($fp, LOCK_UN);
  } else {
    fclose($fp);
    return [];
  }
  fclose($fp);
  $data = json_decode($txt, true);
  return is_array($data) ? $data : [];
}

function json_write($path, $arr) {
  $dir = dirname($path);
  if (!is_dir($dir)) mkdir($dir, 0777, true);

  // tulis atomik via temp file + exclusive lock
  $tmp = tempnam($dir, 'json_');
  $fp  = fopen($tmp, 'w');
  if (!$fp) return false;

  if (flock($fp, LOCK_EX)) {
    fwrite($fp, json_encode($arr, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    fflush($fp);
    flock($fp, LOCK_UN);
  }
  fclose($fp);
  return rename($tmp, $path);
}
