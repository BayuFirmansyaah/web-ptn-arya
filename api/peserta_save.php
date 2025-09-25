<?php
require_once __DIR__.'/../lib/auth.php';
require_admin();
require_super_admin();
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__.'/../lib/jsondb.php';

function norm_program($v){ $v=strtoupper(trim($v)); if(in_array($v,['12EXC','EXC','EXCELLENT'],true))return'12EXC'; if(in_array($v,['12CI','CI','CERDAS ISTIMEWA','CERDAS','ISTIMEWA'],true))return'12CI'; return $v; }
function norm_gender($v){ $v=strtoupper(trim($v)); if(in_array($v,['L','LAKI','LAKI-LAKI','PUTRA'],true))return'PUTRA'; if(in_array($v,['P','PEREMPUAN','PUTRI'],true))return'PUTRI'; return $v; }
function norm_ma($v){ $v=strtoupper(trim($v)); $v=str_replace('MA','',$v); $v=preg_replace('/\D+/','',$v); if($v==='1')$v='01'; if($v==='3')$v='03'; if(in_array($v,['01','03'],true)) return 'MA'.$v; return $v?('MA'.$v):'MA01'; }
function norm_kelas($v){ return strtoupper(trim(str_replace(' ','',$v))); }

$raw = file_get_contents('php://input');
$row['updated_at'] = time();
$in  = json_decode($raw,true);
if(!is_array($in)){ echo json_encode(['ok'=>false,'error'=>'Body JSON required']); exit; }

$id      = trim($in['id'] ?? '');
$nama    = trim($in['nama'] ?? '');
$program = norm_program($in['program'] ?? '');
$gender  = norm_gender($in['gender'] ?? '');
$ma      = norm_ma($in['ma'] ?? '');
$kelas   = norm_kelas($in['kelas'] ?? '');
$jurusan = trim($in['jurusan'] ?? '-');

if($nama===''){ echo json_encode(['ok'=>false,'error'=>'Nama wajib diisi']); exit; }
if($id==='') $id = uniqid('p');

$path = __DIR__.'/../data/peserta.json';
$db = json_read($path); if(!is_array($db)) $db=[];

// upsert by id
$idx = null;
foreach($db as $i=>$p){ if(($p['id']??'')===$id){ $idx=$i; break; } }
$row = ['id'=>$id,'nama'=>$nama,'program'=>$program,'kelas'=>$kelas,'ma'=>$ma,'gender'=>$gender,'jurusan'=>$jurusan?:'-'];

if($idx===null){ $db[]=$row; $action='created'; }
else { $db[$idx]=$row; $action='updated'; }

json_write($path,$db);
echo json_encode(['ok'=>true,'action'=>$action,'data'=>$row]);