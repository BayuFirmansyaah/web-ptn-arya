<?php
require_once __DIR__.'/../lib/auth.php';
require_admin();
require_super_admin();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Peserta</title>
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
            max-width: 600px;
            margin: 0 auto;
            background: var(--card);
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        h1 {
            color: var(--bg1);
            margin-bottom: 30px;
            text-align: center;
            font-size: 28px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--ink);
        }

        input, select {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid var(--line);
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        input:focus, select:focus {
            outline: none;
            border-color: var(--accent);
        }

        .btn {
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-2) 100%);
            color: white;
            border: none;
            padding: 14px 30px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            transition: transform 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
        }

        .alert.success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .alert.error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Tambah Peserta</h1>
        
        <div id="alert" class="alert"></div>
        
        <form id="pesertaForm">
            <div class="form-group">
                <label for="nama">Nama *</label>
                <input type="text" id="nama" name="nama" required>
            </div>

            <div class="form-group">
                <label for="program">Program</label>
                <select id="program" name="program">
                    <option value="12EXC">12 EXCELLENT</option>
                    <option value="12CI">12 CERDAS ISTIMEWA</option>
                </select>
            </div>

            <div class="form-group">
                <label for="gender">Gender</label>
                <select id="gender" name="gender">
                    <option value="PUTRA">PUTRA</option>
                    <option value="PUTRI">PUTRI</option>
                </select>
            </div>

            <div class="form-group">
                <label for="kelas">Kelas</label>
                <select id="kelas" name="kelas">
                    <!-- Options will be populated by JavaScript -->
                </select>
            </div>

            <div class="form-group">
                <label for="ma">MA</label>
                <select id="ma" name="ma">
                    <option value="MA01">MA01</option>
                    <option value="MA03">MA03</option>
                </select>
            </div>

            <div class="form-group">
                <label for="jurusan">Jurusan</label>
                <input type="text" id="jurusan" name="jurusan" placeholder="Opsional">
            </div>

            <button type="submit" class="btn" id="submitBtn">Tambah Peserta</button>
        </form>
    </div>

    <script>
        const kelasOptions = {
            '12EXC': {
                'PUTRA': ['A1', 'B1', 'OVERSEAS', 'KHOS'],
                'PUTRI': ['C1', 'D1', 'OVERSEAS', 'KHOS']
            },
            '12CI': {
                'PUTRA': ['A', 'B', 'C', 'D', 'OVERSEAS', 'KHOS'],
                'PUTRI': ['E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'OVERSEAS', 'KHOS']
            }
        };

        function updateKelasOptions() {
            const program = document.getElementById('program').value;
            const gender = document.getElementById('gender').value;
            const kelasSelect = document.getElementById('kelas');
            
            // Clear existing options
            kelasSelect.innerHTML = '';
            
            // Get appropriate class options
            const options = kelasOptions[program][gender];
            
            // Populate new options
            options.forEach(kelas => {
                const option = document.createElement('option');
                option.value = kelas;
                option.textContent = kelas;
                kelasSelect.appendChild(option);
            });
        }

        // Initialize on page load
        updateKelasOptions();

        // Update when program or gender changes
        document.getElementById('program').addEventListener('change', updateKelasOptions);
        document.getElementById('gender').addEventListener('change', updateKelasOptions);

        document.getElementById('pesertaForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitBtn = document.getElementById('submitBtn');
            const alert = document.getElementById('alert');
            
            submitBtn.disabled = true;
            submitBtn.textContent = 'Menyimpan...';
            
            const formData = new FormData(this);
            const data = {
                nama: formData.get('nama'),
                program: formData.get('program'),
                kelas: formData.get('kelas'),
                ma: formData.get('ma'),
                gender: formData.get('gender'),
                jurusan: formData.get('jurusan') || '-'
            };
            
            try {
                const response = await fetch('../api/peserta_save.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (result.ok) {
                    alert.className = 'alert success';
                    alert.textContent = 'Peserta berhasil ditambahkan!';
                    alert.style.display = 'block';
                    this.reset();
                    updateKelasOptions(); // Reset class options
                    
                    setTimeout(() => {
                        window.location.href = '../dashboard.php';
                    }, 1500);
                } else {
                    throw new Error(result.error || 'Terjadi kesalahan');
                }
            } catch (error) {
                alert.className = 'alert error';
                alert.textContent = 'Error: ' + error.message;
                alert.style.display = 'block';
            }
            
            submitBtn.disabled = false;
            submitBtn.textContent = 'Tambah Peserta';
            
            setTimeout(() => {
                alert.style.display = 'none';
            }, 5000);
        });
    </script>
</body>
</html>