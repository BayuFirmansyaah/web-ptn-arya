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
$in  = json_decode($raw,true);
if(!is_array($in)){ echo json_encode(['ok'=>false,'error'=>'Body JSON required']); exit; }

$id      = trim($in['id'] ?? '');
$nama    = trim($in['nama'] ?? '');
$program = norm_program($in['program'] ?? '');
$gender  = norm_gender($in['gender'] ?? '');
$ma      = norm_ma($in['ma'] ?? '');
$kelas   = norm_kelas($in['kelas'] ?? '');
$jurusan = trim($in['jurusan'] ?? '-');
$foto    = $in['foto'] ?? '';
$foto_name = trim($in['foto_name'] ?? '');
$foto_type = trim($in['foto_type'] ?? '');

if($nama===''){ echo json_encode(['ok'=>false,'error'=>'Nama wajib diisi']); exit; }
if($id==='') $id = uniqid('p');

// Handle profile photo storage
$profile_data = [];
$profile_dir = __DIR__.'/../data/profiles';
if(!is_dir($profile_dir)) mkdir($profile_dir, 0755, true);

$profile_path = $profile_dir.'/'.$id.'.json';

// Read existing profile data
if(file_exists($profile_path)) {
    $profile_data = json_decode(file_get_contents($profile_path), true) ?: [];
}

// Handle photo upload if provided
if($foto !== '' && $foto_name !== '') {
    // Validate base64 format
    if(strpos($foto, 'data:') === 0) {
        // Extract base64 data
        $foto_parts = explode(',', $foto);
        if(count($foto_parts) === 2) {
            $foto_data = base64_decode($foto_parts[1]);
            
            // Get file extension
            $ext = strtolower(pathinfo($foto_name, PATHINFO_EXTENSION));
            if(!in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
                echo json_encode(['ok'=>false,'error'=>'Format foto tidak didukung']); exit;
            }
            
            // Create uploads directory
            $uploads_dir = __DIR__.'/../uploads/profiles';
            if(!is_dir($uploads_dir)) mkdir($uploads_dir, 0755, true);
            
            // Generate unique filename
            $foto_filename = $id.'_'.time().'.'.$ext;
            $foto_filepath = $uploads_dir.'/'.$foto_filename;
            
            // Save photo file
            if(file_put_contents($foto_filepath, $foto_data)) {
                // Remove old photo if exists
                if(isset($profile_data['foto_path']) && file_exists(__DIR__.'/../'.$profile_data['foto_path'])) {
                    unlink(__DIR__.'/../'.$profile_data['foto_path']);
                }
                
                // Update profile data
                $profile_data['foto_path'] = 'uploads/profiles/'.$foto_filename;
                $profile_data['foto_name'] = $foto_name;
                $profile_data['foto_type'] = $foto_type;
            } else {
                echo json_encode(['ok'=>false,'error'=>'Gagal menyimpan foto']); exit;
            }
        }
    }
}

$profile_data['updated_at'] = time();

// Save profile data
file_put_contents($profile_path, json_encode($profile_data, JSON_PRETTY_PRINT));

$path = __DIR__.'/../data/peserta.json';
$db = json_read($path); if(!is_array($db)) $db=[];

// upsert by id
$idx = null;
foreach($db as $i=>$p){ if(($p['id']??'')===$id){ $idx=$i; break; } }
$row = ['id'=>$id,'nama'=>$nama,'program'=>$program,'kelas'=>$kelas,'ma'=>$ma,'gender'=>$gender,'jurusan'=>$jurusan?:'-', 'profile'=>$profile_data,'created_at'=>time(),'updated_at'=>time()];

if($idx===null){ $db[]=$row; $action='created'; }
else { $db[$idx]=$row; $action='updated'; }

json_write($path,$db);
echo json_encode(['ok'=>true,'action'=>$action,'data'=>$row]);