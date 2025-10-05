<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <title>Detail Peserta</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        :root {
            /* === Brand (Blue) === */
            --bg1: #35537A;
            --bg2: #076bbb;
            --accent: #00A9D1;
            --accent-2: #2C33A8;

            --ink: #0f172a;
            --muted: #64748b;
            --line: #e5e7eb;
            --card: #ffffff;
            --success: #059669;
            /* biarkan sesuai kebutuhan status */
            --error: #dc2626;
            --warning: #d97706;
            --surface: #f8fafc;
        }

        /* Reset */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            /* Ganti: gradient halaman */
            background: linear-gradient(90deg, #35537A, #076bbb, #00A9D1, #2C33A8);
            color: var(--ink);
            min-height: 100vh;
            line-height: 1.5;
        }

        /* Header Navigation */
        .header {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 12px 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .nav-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 10px;
            text-decoration: none;
            color: var(--ink);
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .nav-link:hover {
            background: var(--surface);
            transform: translateY(-1px);
        }

        .nav-link.primary {
            /* Ganti: tombol primary biru */
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-2) 100%);
            color: white;
        }

        .nav-link.primary:hover {
            /* Ganti: bayangan biru */
            box-shadow: 0 4px 16px rgba(0, 169, 209, 0.3);
        }

        /* Main Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px 40px;
        }

        /* Profile Card */
        .profile-section {
            background: var(--card);
            border-radius: 20px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--line);
        }

        .profile-header {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 20px;
        }

        .avatar {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            /* Ganti: gradient avatar biru */
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-2) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 28px;
            font-weight: 700;
            /* Ganti: glow biru */
            box-shadow: 0 8px 24px rgba(0, 169, 209, 0.3);
        }

        .profile-info h1 {
            font-size: 28px;
            font-weight: 700;
            color: var(--ink);
            margin-bottom: 8px;
        }

        .profile-status {
            display: inline-block;
            /* Ganti: badge status ke biru */
            background: rgba(0, 169, 209, 0.1);
            color: var(--accent-2);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            border: 1px solid rgba(0, 169, 209, 0.2);
        }

        .profile-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            background: var(--surface);
            padding: 20px;
            border-radius: 16px;
            border: 1px solid var(--line);
        }

        .detail-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .detail-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .detail-value {
            font-size: 14px;
            font-weight: 600;
            color: var(--ink);
        }

        /* Section Cards */
        .section-card {
            background: var(--card);
            border-radius: 20px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--line);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--line);
        }

        .section-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--ink);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-icon {
            width: 24px;
            height: 24px;
            /* Ganti: badge icon biru */
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-2) 100%);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 12px;
            font-weight: 700;
        }

        /* Admin Edit Form */
        .edit-form {
            /* Ganti: soft tint biru */
            background: linear-gradient(135deg, rgba(0, 169, 209, 0.05) 0%, rgba(44, 51, 168, 0.05) 100%);
            border: 1px solid rgba(0, 169, 209, 0.2);
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 24px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 16px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .form-label {
            font-size: 13px;
            font-weight: 600;
            color: var(--ink);
        }

        .form-field {
            padding: 12px 16px;
            border: 1px solid var(--line);
            border-radius: 12px;
            font-size: 14px;
            transition: all 0.2s ease;
            background: white;
        }

        .form-field:focus {
            outline: none;
            border-color: var(--accent);
            /* Ganti: focus ring biru */
            box-shadow: 0 0 0 3px rgba(0, 169, 209, 0.1);
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 20px;
            border: none;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            justify-content: center;
        }

        .btn-primary {
            /* Ganti: tombol utama biru */
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-2) 100%);
            color: white;
            box-shadow: 0 4px 16px rgba(0, 169, 209, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 169, 209, 0.4);
        }

        .btn-secondary {
            background: var(--surface);
            color: var(--ink);
            border: 1px solid var(--line);
        }

        .btn-secondary:hover {
            background: white;
            border-color: var(--accent);
        }

        .btn-danger {
            background: var(--error);
            color: white;
        }

        .btn-danger:hover {
            background: #b91c1c;
            transform: translateY(-1px);
        }

        .btn-outline {
            background: transparent;
            color: var(--accent);
            border: 1px solid var(--accent);
        }

        .btn-outline:hover {
            background: var(--accent);
            color: white;
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
        }

        /* File Tables */
        .files-container {
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid var(--line);
        }

        .files-table {
            width: 100%;
            border-collapse: collapse;
        }

        .files-table thead th {
            /* Ganti: header tabel biru */
            background: linear-gradient(135deg, var(--bg1) 0%, var(--bg2) 100%);
            color: white;
            padding: 16px 20px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .files-table tbody td {
            padding: 16px 20px;
            border-bottom: 1px solid var(--line);
            vertical-align: middle;
        }

        .files-table tbody tr:last-child td {
            border-bottom: none;
        }

        .files-table tbody tr:hover {
            background: var(--surface);
        }

        .file-info {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .file-name {
            font-weight: 600;
            color: var(--ink);
        }

        .file-path {
            font-size: 12px;
            color: var(--muted);
        }

        .file-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: var(--muted);
            background: var(--surface);
            border-radius: 16px;
            border: 2px dashed var(--line);
        }

        .empty-icon {
            font-size: 48px;
            margin-bottom: 12px;
            opacity: 0.5;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(8px);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: var(--card);
            border-radius: 20px;
            padding: 24px;
            width: min(500px, 95vw);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            border: 1px solid var(--line);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--line);
        }

        .modal-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--ink);
        }

        .file-input {
            width: 100%;
            padding: 16px;
            border: 2px dashed var(--line);
            border-radius: 12px;
            text-align: center;
            background: var(--surface);
            margin-bottom: 16px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .file-input:hover {
            border-color: var(--accent);
            /* Ganti: hover tint biru */
            background: rgba(0, 169, 209, 0.05);
        }

        .file-input input {
            display: none;
        }

        /* Alert Messages */
        .alert {
            padding: 12px 16px;
            border-radius: 12px;
            margin-top: 12px;
            font-weight: 500;
        }

        .alert-success {
            background: rgba(5, 150, 105, 0.1);
            color: var(--success);
            border: 1px solid rgba(5, 150, 105, 0.2);
        }

        .alert-error {
            background: rgba(220, 38, 38, 0.1);
            color: var(--error);
            border: 1px solid rgba(220, 38, 38, 0.2);
        }

        /* Responsive Design */
        @media(max-width: 768px) {

            .header,
            .container {
                padding-left: 16px;
                padding-right: 16px;
            }

            .profile-header {
                flex-direction: column;
                text-align: center;
                gap: 16px;
            }

            .profile-details {
                grid-template-columns: 1fr;
            }

            .section-header {
                flex-direction: column;
                gap: 12px;
                align-items: flex-start;
            }

            .nav {
                flex-direction: column;
                gap: 12px;
            }

            .file-actions {
                flex-direction: column;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Loading State */
        .loading {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid var(--line);
            border-top: 2px solid var(--accent);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframesspin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Fade in animation */
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframesfadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
    {{-- import sweetalert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <!-- Header Navigation -->
    <div class="header">
        <nav class="nav">
            <a href="{{ route('dashboard') }}" class="nav-link">
                ← Kembali ke Dashboard
            </a>
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="nav-link primary" style="border: none; background: none;">
                    Keluar
                </button>
            </form>
        </nav>
    </div>

    <div class="container">
        <!-- Profile Section -->
        <div class="profile-section fade-in">
            <div class="profile-header">
                <div class="avatar" id="avatar">
                    @if ($student['profile'] && $student['profile'])
                        <img src="{{ asset('uploads/photos/' . $student['profile']) }}"
                            style="width: 100%; height: 100%; object-fit: cover; border-radius: 20px;"
                            alt="Profile Photo">
                    @else
                        {{ substr($student['nama'], 0, 1) }}{{ substr(explode(' ', $student['nama'])[count(explode(' ', $student['nama'])) - 1], 0, 1) }}
                    @endif
                </div>
                <div class="profile-info">
                    <h1 id="pNama">{{ $student['nama'] }}</h1>
                    <span class="profile-status">Peserta Aktif</span>
                </div>
                {{-- @if (auth()->user()->is_super) --}}
                <div style="margin-left: auto;">
                    <button class="btn btn-danger" onclick="deletePeserta('{{ $student['id'] }}')">
                        🗑️ Hapus Peserta
                    </button>
                </div>
                {{-- @endif --}}
            </div>
            <div class="profile-details" id="profileDetails">
                <div class="detail-item">
                    <span class="detail-label">Program</span>
                    <span class="detail-value" id="detailProgram">{{ $student['program'] ?? '-' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Kelas</span>
                    <span class="detail-value" id="detailKelas">{{ $student['kelas'] ?? '-' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Madrasah Aliyah</span>
                    <span class="detail-value" id="detailMA">{{ $student['ma'] ?? '-' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Jurusan</span>
                    <span class="detail-value" id="detailJurusan">{{ $student['jurusan'] ?? '-' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Gender</span>
                    <span class="detail-value" id="detailGender">{{ $student['gender'] ?? '-' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">ID Peserta</span>
                    <span class="detail-value">{{ $student['id'] }}</span>
                </div>
            </div>
        </div>

        <!-- Super Admin Edit Form -->
        {{-- @if (auth()->user()->is_super) --}}
        <div class="edit-form fade-in" style="background-color: white !important;">
            <div class="section-header">
                <h2 class="section-title">
                    <span class="section-icon">⚙</span>
                    Edit Data Peserta
                </h2>
            </div>
            <form id="editForm" enctype="multipart/form-data">
                @csrf
                <div class="form-grid" style="grid-template-columns: repeat(2, 1fr);">
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap</label>
                        <input id="f_nama" name="nama" class="form-field" value="{{ $student['nama'] }}"
                            placeholder="Masukkan nama lengkap">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Program</label>
                        <select id="f_program" name="program" class="form-field">
                            <option value="12EXC" {{ $student['program'] == '12EXC' ? 'selected' : '' }}>12EXC
                            </option>
                            <option value="12CI" {{ $student['program'] == '12CI' ? 'selected' : '' }}>12CI</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Gender</label>
                        <select id="f_gender" name="gender" class="form-field">
                            <option value="PUTRA" {{ $student['gender'] == 'PUTRA' ? 'selected' : '' }}>PUTRA</option>
                            <option value="PUTRI" {{ $student['gender'] == 'PUTRI' ? 'selected' : '' }}>PUTRI</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Madrasah Aliyah</label>
                        <select id="f_ma" name="ma" class="form-field">
                            <option value="MA01" {{ $student['ma'] == 'MA01' ? 'selected' : '' }}>MA01</option>
                            <option value="MA03" {{ $student['ma'] == 'MA03' ? 'selected' : '' }}>MA03</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kelas</label>
                        <input id="f_kelas" name="kelas" class="form-field" value="{{ $student['kelas'] }}"
                            placeholder="Contoh: A1, B1, C1, atau A-M">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jurusan</label>
                        <input id="f_jurusan" name="jurusan" class="form-field" value="{{ $student['jurusan'] }}"
                            placeholder="Masukkan jurusan">
                    </div>
                </div>
                <div class="form-group" style="margin-top: 16px;">
                    <label class="form-label">Foto Profil</label>
                    <div class="file-input" onclick="document.getElementById('f_foto').click()"
                        style="margin: 0; padding: 12px; text-align: left; cursor: pointer;">
                        <input type="file" id="f_foto" name="foto" accept=".jpg,.jpeg,.png"
                            style="display: none;" />
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div id="fotoPreview"
                                style="width: 60px; height: 60px; border-radius: 8px; background: var(--surface); border: 2px dashed var(--line); display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                @if ($student['profile'] && $student['profile'])
                                    <img src="{{ asset('uploads/photos/' . $student['profile']) }}"
                                        style="width: 100%; height: 100%; object-fit: cover; border-radius: 6px;"
                                        alt="Current Photo">
                                @else
                                    <span style="color: var(--muted); font-size: 24px;">📷</span>
                                @endif
                            </div>
                            <div style="flex: 1;">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <span>📷</span>
                                    <span id="fotoLabel">
                                        @if ($student['profile'] && $student['profile'])
                                            Foto profil saat ini
                                        @else
                                            Pilih foto profil
                                        @endif
                                    </span>
                                </div>
                                <small style="color: var(--muted); display: block; margin-top: 4px;">Format: JPG, PNG
                                    (Max: 2MB)</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="display: flex; gap: 12px; align-items: center; margin-top: 16px;">
                    <button type="button" class="btn btn-primary" onclick="saveBiodata()">
                        <span id="saveLoading" class="loading" style="display: none;"></span>
                        Simpan Perubahan
                    </button>
                    <div id="saveMsg" class="alert" style="display:none"></div>
                </div>
            </form>
        </div>
        {{-- @endif --}}

        <!-- Sertifikat Section -->
        <div class="section-card fade-in">
            <div class="section-header">
                <h2 class="section-title">
                    <span class="section-icon">🏆</span>
                    Sertifikat
                </h2>
                <button class="btn btn-primary" onclick="openModal('sertifikat')">
                    Upload Sertifikat
                </button>
            </div>
            @if ($sertifikat->isEmpty())
                <div id="emptyS" class="empty-state">
                    <div class="empty-icon">📄</div>
                    <p>Belum ada sertifikat yang diupload</p>
                </div>
            @else
                <div class="files-container" id="containerS">
                    <table class="files-table">
                        <thead>
                            <tr>
                                <th>Informasi File</th>
                            </tr>
                        </thead>
                        <tbody id="tbS">
                            @foreach ($sertifikat as $file)
                                <tr>
                                    <td>
                                        <div class="file-info">
                                            <div class="file-name">{{ basename($file->file_path) }}</div>
                                            <div class="file-path">{{ $file->file_path }}</div>
                                            <small style="color: var(--muted);">Diupload:
                                                {{ $file->created_at->format('d/m/Y') }}</small>
                                            <div class="file-actions" style="margin-top: 8px;">
                                                <a class="btn btn-secondary" href="{{ asset($file->file_path) }}"
                                                    target="_blank" rel="noopener">
                                                    👁️ Lihat
                                                </a>
                                                <a class="btn btn-outline" href="{{ asset($file->file_path) }}"
                                                    download>
                                                    💾 Unduh
                                                </a>
                                                <button class="btn btn-danger"
                                                    onclick="deleteFile('sertifikat','{{ $file->id }}')">
                                                    🗑️ Hapus
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Berkas Section -->
        <div class="section-card fade-in">
            <div class="section-header">
                <h2 class="section-title">
                    <span class="section-icon">📋</span>
                    Berkas Pendukung
                </h2>
                <button class="btn btn-primary" onclick="openModal('berkas')">
                    Upload Berkas
                </button>
            </div>
            @if ($berkas->isEmpty())
                <div id="emptyB" class="empty-state">
                    <div class="empty-icon">📁</div>
                    <p>Belum ada berkas yang diupload</p>
                </div>
            @else
                <div class="files-container" id="containerB">
                    <table class="files-table">
                        <thead>
                            <tr>
                                <th>Informasi File</th>
                            </tr>
                        </thead>
                        <tbody id="tbB">
                            @foreach ($berkas as $file)
                                <tr>
                                    <td>
                                        <div class="file-info">
                                            <div class="file-name">{{ basename($file->file_path) }}</div>
                                            <div class="file-path">{{ $file->file_path }}</div>
                                            <small style="color: var(--muted);">Diupload:
                                                {{ $file->created_at->format('d/m/Y') }}</small>
                                            <div class="file-actions" style="margin-top: 8px;">
                                                <a class="btn btn-secondary" href="{{ asset($file->file_path) }}"
                                                    target="_blank" rel="noopener">
                                                    👁️ Lihat
                                                </a>
                                                <a class="btn btn-outline" href="{{ asset($file->file_path) }}"
                                                    download>
                                                    💾 Unduh
                                                </a>
                                                <button class="btn btn-danger"
                                                    onclick="deleteFile('berkas','{{ $file->id }}')">
                                                    🗑️ Hapus
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Upload Modal -->
    <div id="modal" class="modal" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle" class="modal-title">Upload File</h3>
                <button class="btn btn-secondary" onclick="closeModal()">Tutup</button>
            </div>
            <form id="uploadForm" enctype="multipart/form-data">
                @csrf
                <div class="file-input" onclick="document.getElementById('file').click()">
                    <input type="file" id="file" name="file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" />
                    <p>📤 Klik untuk memilih file</p>
                    <small style="color: var(--muted);">Format: PDF, DOC, DOCX, JPG, PNG (Max: 5MB)</small>
                </div>
                <div style="display: flex; gap: 12px; align-items: center;">
                    <button type="button" id="btnUpload" class="btn btn-primary" onclick="doUpload()">
                        <span id="uploadLoading" class="loading" style="display: none;"></span>
                        Upload File
                    </button>
                    <div id="mMsg" style="flex: 1; color: var(--muted); font-size: 14px;"></div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Setup CSRF token for AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        let currentType = 'sertifikat';

        function openModal(type) {
            window.currentType = type;
            document.getElementById('modalTitle').textContent = 'Upload ' + (type === 'sertifikat' ? 'Sertifikat' :
                'Berkas Pendukung');
            document.getElementById('mMsg').textContent = '';
            document.getElementById('file').value = '';
            document.getElementById('modal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('modal').style.display = 'none';
        }

        // Close modal when clicking outside
        window.addEventListener('click', (e) => {
            if (e.target === document.getElementById('modal')) closeModal();
        });

        // Close modal with Escape key
        window.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeModal();
        });

        async function doUpload() {
            const fileInput = document.getElementById('file');
            const file = fileInput.files[0];
            const mMsg = document.getElementById('mMsg');
            const btnUpload = document.getElementById('btnUpload');
            const uploadLoading = document.getElementById('uploadLoading');

            if (!file) {
                mMsg.textContent = 'Silakan pilih file terlebih dahulu';
                return;
            }

            // Validate file size (5MB max)
            if (file.size > 5 * 1024 * 1024) {
                mMsg.textContent = 'Ukuran file maksimal 5MB';
                return;
            }

            btnUpload.disabled = true;
            uploadLoading.style.display = 'inline-block';
            mMsg.textContent = 'Sedang mengupload...';

            try {
                const formData = new FormData();
                formData.append('file', file);
                formData.append('student_id', '{{ $student['id'] }}');
                formData.append('type', window.currentType);
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

                const response = await fetch('{{ route('files.upload') }}', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    showMessage(mMsg, 'File berhasil diupload!', 'success');
                    setTimeout(() => {
                        closeModal();
                        location.reload();
                    }, 1500);
                } else {
                    showMessage(mMsg, result.message || 'Gagal mengupload file', 'error');
                }
            } catch (error) {
                mMsg.textContent = 'Terjadi kesalahan saat mengupload';
                console.error('Upload error:', error);
            } finally {
                btnUpload.disabled = false;
                uploadLoading.style.display = 'none';
            }
        }

        async function deleteFile(type, fileId) {
            if (!confirm('Apakah Anda yakin ingin menghapus file ini?')) return;

            try {
                const response = await fetch('{{ route('files.delete') }}', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        id: fileId,
                        type: type
                    })
                });

                const result = await response.json();

                if (result.success) {
                    location.reload(); // Reload page to reflect changes
                } else {
                    alert(result.message || 'Gagal menghapus file');
                }
            } catch (error) {
                alert('Terjadi kesalahan saat menghapus file');
                console.error('Delete error:', error);
            }
        }

        async function deletePeserta(studentId) {
            const nama = document.getElementById('pNama').textContent;
            if (confirm(
                    `Apakah Anda yakin ingin menghapus peserta "${nama}"?\n\nTindakan ini tidak dapat dibatalkan dan akan menghapus semua data termasuk file yang telah diupload.`
                    )) {
                try {
                    const response = await fetch('{{ route('students.delete') }}', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            id: studentId
                        })
                    });

                    const result = await response.json();

                    if (result.success) {
                        showMessage(null, 'Peserta berhasil dihapus.', 'success');
                        setTimeout(() => {
                            window.location.href = '{{ route('dashboard') }}';
                        }, 2000);
                    } else {
                        showMessage(null, 'Gagal menghapus peserta: ' + (result.message || 'Unknown error'), 'error');
                    }
                } catch (error) {
                    alert('Terjadi kesalahan saat menghapus peserta');
                    console.error('Delete error:', error);
                }
            }
        }

        async function saveBiodata() {
            const saveBtn = document.querySelector('.btn[onclick="saveBiodata()"]');
            const saveLoading = document.getElementById('saveLoading');
            const msg = document.getElementById('saveMsg');

            const nama = document.getElementById('f_nama').value.trim();
            if (!nama) {
                showMessage(msg, 'Nama tidak boleh kosong', 'error');
                return;
            }

            saveBtn.disabled = true;
            saveLoading.style.display = 'inline-block';
            msg.style.display = 'none';

            let id = '{{ $student['id'] }}';
            let currentNama = document.getElementById('pNama').textContent;
            let currentProgram = document.getElementById('detailProgram').textContent;
            let currentKelas = document.getElementById('detailKelas').textContent;
            let currentMA = document.getElementById('detailMA').textContent;
            let currentJurusan = document.getElementById('detailJurusan').textContent;
            let currentGender = document.getElementById('detailGender').textContent;
            if (nama === currentNama &&
                document.getElementById('f_program').value === currentProgram &&
                document.getElementById('f_kelas').value.trim() === currentKelas &&
                document.getElementById('f_ma').value === currentMA &&
                document.getElementById('f_jurusan').value.trim() === currentJurusan &&
                document.getElementById('f_gender').value === currentGender &&
                document.getElementById('f_foto').files.length === 0) {
                showMessage(msg, 'Tidak ada perubahan yang disimpan', 'error');
                saveBtn.disabled = false;
                saveLoading.style.display = 'none';
                return;
            }

            try {
                const formData = new FormData();
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                formData.append('id', id);
                formData.append('nama', nama);
                formData.append('program', document.getElementById('f_program').value);
                formData.append('gender', document.getElementById('f_gender').value);
                formData.append('ma', document.getElementById('f_ma').value);
                formData.append('kelas', document.getElementById('f_kelas').value.trim());
                formData.append('jurusan', document.getElementById('f_jurusan').value.trim());

                const fotoFile = document.getElementById('f_foto').files[0];
                if (fotoFile) {
                    formData.append('foto', fotoFile);
                }

                console.log(formData);

                const response = await fetch('{{ route('students.update') }}', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    showMessage(msg, 'Data berhasil diperbarui!', 'success');

                    // Clear photo input after successful save
                    document.getElementById('f_foto').value = '';

                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    showMessage(msg, result.message || 'Gagal menyimpan data', 'error');
                }
            } catch (error) {
                showMessage(msg, error.message || 'Terjadi kesalahan saat menyimpan', 'error');
                console.error('Save error:', error);
            } finally {
                saveBtn.disabled = false;
                saveLoading.style.display = 'none';
            }
        }

        function showMessage(element, text, type = 'success') {
            // Use SweetAlert instead of basic alert element
            if (type === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: text,
                    timer: 3000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'center'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: text,
                    timer: 5000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'center'
                });
            }
        }

        // Photo preview functionality
        document.getElementById('f_foto').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('fotoPreview');
            const label = document.getElementById('fotoLabel');

            if (file) {
                // Check file size (2MB max)
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file maksimal 2MB');
                    this.value = '';
                    return;
                }

                // Check file type
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Format file harus JPG atau PNG');
                    this.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML =
                        `<img src="${e.target.result}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 6px;">`;
                    label.textContent = file.name;
                };
                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = '<span style="color: var(--muted); font-size: 24px;">📷</span>';
                label.textContent = 'Pilih foto profil';
            }
        });

        // File input change handler for upload modal
        document.getElementById('file').addEventListener('change', function() {
            const fileName = this.files[0]?.name;
            if (fileName) {
                document.getElementById('mMsg').textContent = `File terpilih: ${fileName}`;
            }
        });
    </script>
</body>

</html>
