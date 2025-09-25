<?php
function normalize_program($v){
  $v = strtoupper(trim($v));
  if (in_array($v, ['12EXC','EXC','EXCELLENT'], true)) return '12EXC';
  if (in_array($v, ['12CI','CI','CERDAS ISTIMEWA','CERDAS','ISTIMEWA'], true)) return '12CI';
  return $v ?: '12EXC';
}
function normalize_gender($v){
  $v = strtoupper(trim($v));
  if (in_array($v, ['L','LAKI-LAKI','LAKI','PUTRA'], true)) return 'PUTRA';
  if (in_array($v, ['P','PEREMPUAN','PUTRI'], true)) return 'PUTRI';
  return $v;
}
function normalize_ma($v){
  $v = strtoupper(trim($v));
  if ($v === '' || $v === 'MA' || $v === '0' || $v === 'MA00') return 'MA01';
  $v = str_replace('MA', '', $v);
  $v = preg_replace('/\D+/', '', $v);
  if ($v === '1') $v = '01';
  if ($v === '3') $v = '03';
  if ($v === '01' || $v === '03') return 'MA'.$v;
  return 'MA01';
}
function normalize_kelas($v){
  $v = strtoupper(trim($v));
  return str_replace(' ', '', $v);
}
function kelas_valid($program,$gender,$kelas){
  $program = strtoupper($program);
  $gender  = strtoupper($gender);
  $kelas   = strtoupper($kelas);
  if ($program === '12EXC') {
    if ($gender === 'PUTRA') return in_array($kelas, ['A1','B1'], true);
    if ($gender === 'PUTRI') return in_array($kelas, ['C1','D1'], true);
    return false;
  }
  if ($program === '12CI') {
    if ($gender === 'PUTRA') return in_array($kelas, ['A','B','C','D'], true);
    if ($gender === 'PUTRI') return in_array($kelas, ['E','F','G','H','I','J','K','L','M'], true);
    return false;
  }
  return false;
}