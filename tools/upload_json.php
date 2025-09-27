<?php
// tools/upload_json.php
require_once __DIR__.'/../lib/auth.php';
require_admin();
require_super_admin();
require_once __DIR__.'/../lib/jsondb.php';

function norm_program($v){ $v=strtoupper(trim($v)); if(in_array($v,['12EXC','EXC','EXCELLENT'],true))return'12EXC'; if(in_array($v,['12CI','CI','CERDAS ISTIMEWA','CERDAS','ISTIMEWA'],true))return'12CI'; return $v; }
function norm_gender($v){ $v=strtoupper(trim($v)); if(in_array($v,['L','LAKI','LAKI-LAKI','PUTRA'],true))return'PUTRA'; if(in_array($v,['P','PEREMPUAN','PUTRI'],true))return'PUTRI'; return $v; }
function norm_ma($v){ $v=strtoupper(trim($v)); $v=str_replace('MA','',$v); $v=preg_replace('/\D+/','',$v); if($v==='1')$v='01'; if($v==='3')$v='03'; if(in_array($v,['01','03'],true)) return 'MA'.$v; return $v?('MA'.$v):'MA01'; }
function norm_kelas($v){ return strtoupper(trim(str_replace(' ','',$v))); }

$summary=null; $error=null;

if($_SERVER['REQUEST_METHOD']==='POST'){
    if(!isset($_FILES['json']) || $_FILES['json']['error']!==UPLOAD_ERR_OK){
        $error='Upload JSON gagal.';
    }else{
        $raw = file_get_contents($_FILES['json']['tmp_name']);
        $data = json_decode($raw, true);
        if(!is_array($data)){
            $error='Format JSON tidak valid (harus array of objects).';
        }else{
            // Bisa juga data = { "items":[...] }
            if(isset($data['items']) && is_array($data['items'])) $data = $data['items'];

            $clean=[]; $added=0; $fixed=0;
            foreach($data as $r){
                if(!is_array($r)) continue;
                $id      = trim($r['id'] ?? '');
                $nama    = trim($r['nama'] ?? '');
                $program = norm_program($r['program'] ?? '');
                $gender  = norm_gender($r['gender'] ?? '');
                $ma      = norm_ma($r['ma'] ?? '');
                $kelas   = norm_kelas($r['kelas'] ?? '');
                $jurusan = trim($r['jurusan'] ?? '');
                if($nama==='') continue;

                if($id===''){ $id=uniqid('p'); $fixed++; }
                if($jurusan==='') $jurusan='-';

                $clean[] = [
                    'id'=>$id,'nama'=>$nama,'program'=>$program,'kelas'=>$kelas,
                    'ma'=>$ma,'gender'=>$gender,'jurusan'=>$jurusan
                ];
                $added++;
            }

            // backup lama
            $dbPath = __DIR__.'/../data/peserta.json';
            if(file_exists($dbPath)){
                $backup = __DIR__.'/../data/peserta.backup.'.date('Ymd_His').'.json';
@copy($dbPath, $backup);
            }

            json_write($dbPath, $clean);
            $summary = [
                'uploaded_count'=>count($data),
                'saved'=>count($clean),
                'auto_generated_id'=>$fixed,
                'target'=>'data/peserta.json'
            ];
        }
    }
}
?>
<!doctype html>
<html lang="id">

<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <title>Upload JSON Peserta</title>
        <style>
                :root {
                        --bg1: #36642d;
                        --bg2: #26865e;
                        --accent: #20bf00;
                        --accent-2: #169300;
                        --ink: #0f172a;
                        --muted: #64748b;
                        --line: #e5e7eb;
                        --card: #ffffff;
                }

                * {
                        box-sizing: border-box;
                        margin: 0;
                        padding: 0;
                }

                body {
                        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                        background: linear-gradient(135deg, var(--bg1) 0%, var(--bg2) 100%);
                        min-height: 100vh;
                        color: var(--ink);
                        padding: 20px;
                }

                .container {
                        max-width: 800px;
                        margin: 0 auto;
                        padding: 20px 0;
                }

                .card {
                        background: var(--card);
                        border-radius: 16px;
                        padding: 32px;
                        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
                        border: 1px solid rgba(255, 255, 255, 0.1);
                }

                h1 {
                        font-size: 28px;
                        font-weight: 700;
                        margin-bottom: 8px;
                        color: var(--ink);
                        background: linear-gradient(135deg, var(--bg1), var(--bg2));
                        -webkit-background-clip: text;
                        -webkit-text-fill-color: transparent;
                        background-clip: text;
                }

                .subtitle {
                        color: var(--muted);
                        font-size: 14px;
                        margin-bottom: 24px;
                        line-height: 1.5;
                }

                .upload-form {
                        background: #f8fafc;
                        border: 2px dashed var(--line);
                        border-radius: 12px;
                        padding: 24px;
                        margin: 24px 0;
                        text-align: center;
                        transition: all 0.3s ease;
                }

                .upload-form:hover {
                        border-color: var(--accent);
                        background: #f0fdf4;
                }

                .file-input-wrapper {
                        position: relative;
                        display: inline-block;
                        margin-bottom: 16px;
                }

                input[type="file"] {
                        width: 100%;
                        padding: 12px 16px;
                        border: 2px solid var(--line);
                        border-radius: 10px;
                        background: var(--card);
                        font-size: 14px;
                        transition: all 0.3s ease;
                }

                input[type="file"]:focus {
                        outline: none;
                        border-color: var(--accent);
                        box-shadow: 0 0 0 3px rgba(32, 191, 0, 0.1);
                }

                .btn {
                        display: inline-flex;
                        align-items: center;
                        gap: 8px;
                        background: linear-gradient(135deg, var(--accent), var(--accent-2));
                        color: white;
                        border: none;
                        border-radius: 10px;
                        padding: 12px 24px;
                        font-size: 14px;
                        font-weight: 600;
                        cursor: pointer;
                        transition: all 0.3s ease;
                        text-decoration: none;
                }

                .btn:hover {
                        transform: translateY(-2px);
                        box-shadow: 0 8px 25px rgba(32, 191, 0, 0.3);
                }

                .btn:active {
                        transform: translateY(0);
                }

                .example-section {
                        margin: 24px 0;
                }

                details {
                        background: #f8fafc;
                        border: 1px solid var(--line);
                        border-radius: 10px;
                        overflow: hidden;
                }

                summary {
                        padding: 16px;
                        background: #f1f5f9;
                        cursor: pointer;
                        font-weight: 600;
                        color: var(--ink);
                        border-bottom: 1px solid var(--line);
                        transition: background-color 0.3s ease;
                }

                summary:hover {
                        background: #e2e8f0;
                }

                pre {
                        padding: 20px;
                        font-family: 'Fira Code', 'Consolas', monospace;
                        font-size: 12px;
                        line-height: 1.6;
                        background: var(--card);
                        color: var(--ink);
                        overflow-x: auto;
                }

                .alert {
                        padding: 16px 20px;
                        border-radius: 12px;
                        margin: 20px 0;
                        font-weight: 500;
                        border-left: 4px solid;
                }

                .alert.error {
                        background: linear-gradient(135deg, #fef2f2, #fee2e2);
                        border-left-color: #ef4444;
                        color: #991b1b;
                }

                .alert.success {
                        background: linear-gradient(135deg, #f0fdf4, #dcfce7);
                        border-left-color: var(--accent);
                        color: #166534;
                }

                .alert strong {
                        display: block;
                        margin-bottom: 8px;
                        font-size: 16px;
                }

                .stats {
                        display: grid;
                        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                        gap: 16px;
                        margin-top: 12px;
                }

                .stat-item {
                        background: rgba(32, 191, 0, 0.1);
                        padding: 12px;
                        border-radius: 8px;
                        text-align: center;
                }

                .stat-value {
                        font-size: 24px;
                        font-weight: 700;
                        color: var(--accent-2);
                }

                .stat-label {
                        font-size: 12px;
                        color: var(--muted);
                        margin-top: 4px;
                }

                .back-link {
                        display: inline-flex;
                        align-items: center;
                        gap: 8px;
                        color: var(--bg1);
                        text-decoration: none;
                        font-weight: 500;
                        margin-top: 24px;
                        padding: 8px 0;
                        transition: color 0.3s ease;
                }

                .back-link:hover {
                        color: var(--bg2);
                }

                code {
                        background: rgba(32, 191, 0, 0.1);
                        color: var(--accent-2);
                        padding: 2px 6px;
                        border-radius: 4px;
                        font-family: 'Fira Code', 'Consolas', monospace;
                        font-size: 13px;
                }

                @media (max-width: 640px) {
                        .container {
                                padding: 10px 0;
                        }
                        
                        .card {
                                padding: 20px;
                        }
                        
                        h1 {
                                font-size: 24px;
                        }
                        
                        .stats {
                                grid-template-columns: 1fr 1fr;
                        }
                }
        </style>
</head>

<body>
        <div class="container">
                <div class="card">
                        <h1>Upload JSON Peserta</h1>
                        <p class="subtitle">File JSON akan <strong>mengganti</strong> <code>data/peserta.json</code> dengan backup otomatis untuk keamanan data.</p>
                        
                        <form method="post" enctype="multipart/form-data" class="upload-form">
                                <div class="file-input-wrapper">
                                        <input type="file" name="json" accept=".json,application/json" required>
                                </div>
                                <button class="btn" type="submit">
                                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                                        </svg>
                                        Upload & Simpan
                                </button>
                        </form>

                        <div class="example-section">
                                <details>
                                        <summary>📋 Contoh Struktur JSON</summary>
                                        <pre>[
    {
        "id": "p001",
        "nama": "ARYANSYAH", 
        "program": "12EXC",
        "kelas": "B1",
        "ma": "MA01",
        "gender": "PUTRA",
        "jurusan": "FISMAT"
    },
    {
        "nama": "SITI AISYAH",
        "program": "12CI", 
        "kelas": "E",
        "ma": "MA03",
        "gender": "PUTRI",
        "jurusan": "IPS"
        // id kosong = auto generate
    }
]</pre>
                                </details>
                        </div>

                        <?php if($error): ?>
                        <div class="alert error">
                                <strong>❌ Upload Gagal</strong>
                                <?=htmlspecialchars($error,ENT_QUOTES,'UTF-8')?>
                        </div>
                        <?php endif; ?>

                        <?php if($summary): ?>
                        <div class="alert success">
                                <strong>✅ Upload Berhasil</strong>
                                Data peserta berhasil diperbarui ke sistem.
                                
                                <div class="stats">
                                        <div class="stat-item">
                                                <div class="stat-value"><?=$summary['uploaded_count']?></div>
                                                <div class="stat-label">Baris di File</div>
                                        </div>
                                        <div class="stat-item">
                                                <div class="stat-value"><?=$summary['saved']?></div>
                                                <div class="stat-label">Data Tersimpan</div>
                                        </div>
                                        <div class="stat-item">
                                                <div class="stat-value"><?=$summary['auto_generated_id']?></div>
                                                <div class="stat-label">ID Auto Generate</div>
                                        </div>
                                </div>
                                
                                <div style="margin-top: 16px;">
                                        <strong>Target:</strong> <code><?=$summary['target']?></code>
                                </div>
                        </div>
                        <?php endif; ?>

                        <a href="../dashboard.php" class="back-link">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M20,11V13H8L13.5,18.5L12.08,19.92L4.16,12L12.08,4.08L13.5,5.5L8,11H20Z"/>
                                </svg>
                                Kembali ke Dashboard
                        </a>
                </div>
        </div>
</body>

</html>