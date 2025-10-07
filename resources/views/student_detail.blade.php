<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <title>{{ $student->nama ?? 'Profil Peserta' }} - Profil Peserta</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Profil peserta {{ $student->nama ?? '' }} - Program {{ $student->program ?? '' }}">
    
    <style>
        :root {
            /* === Brand Gradient (Biru) === */
            --g1: #35537A;
            --g2: #0879CF;
            --g3: #00BEED;
            --g4: #343BBF;

            /* Turunan warna (aksen & bayangan) */
            --accent: var(--g2);
            --accent-2: var(--g4);
            --glow: rgba(8, 121, 207, 0.28);

            /* Neutrals */
            --ink: #0f172a;
            --muted: #64748b;
            --line: #e5e7eb;
            --card: #ffffff;

            /* Surface soft */
            --soft: #f8fafc;
        }

        /* Reset */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            scrollbar-width: thin;
            scrollbar-color: rgba(8, 121, 207, 0.6) rgba(0, 0, 0, 0.06);
        }

        *::-webkit-scrollbar {
            height: 8px;
            width: 8px;
        }

        *::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, var(--g2), var(--g4));
            border-radius: 8px;
        }

        *::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.06);
        }

        /* ===== Page ===== */
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(90deg, var(--g1), var(--g2), var(--g3), var(--g4));
            min-height: 100vh;
            color: var(--ink);
            padding: 20px;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .header {
            margin-bottom: 32px;
        }

        /* ===== Back button (subtle glass) ===== */
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: rgba(255, 255, 255, 0.95);
            text-decoration: none;
            font-weight: 500;
            padding: 12px 20px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.10);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.22);
            transition: transform .25s ease, background .25s ease, border-color .25s ease;
        }

        .back-btn:hover {
            background: rgba(255, 255, 255, 0.18);
            transform: translateY(-2px);
        }

        .back-btn:focus-visible {
            outline: 2px solid #fff;
            outline-offset: 2px;
            border-color: #fff;
        }

        /* ===== Cards / Sections ===== */
        .profile-section,
        .files-section {
            background: var(--card);
            border-radius: 20px;
            padding: 32px;
            margin-bottom: 24px;
            box-shadow: 0 20px 40px rgba(16, 24, 40, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.85);
        }

        /* ===== Profile (NYAMPING) ===== */
        .profile-header {
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 24px;
            margin-bottom: 24px;
            flex-wrap: nowrap;
        }

        .avatar {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            background: linear-gradient(135deg, var(--g2), var(--g4));
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            font-weight: 700;
            box-shadow: 0 8px 32px var(--glow);
            flex-shrink: 0;
            overflow: hidden;
        }

        .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-info {
            flex: 1;
            min-width: 0;
        }

        .profile-info h1 {
            font-size: 28px;
            font-weight: 700;
            color: var(--ink);
            margin-bottom: 8px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .profile-info .subtitle {
            color: var(--muted);
            font-size: 16px;
            margin-bottom: 8px;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
            padding: 6px 12px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 600;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .profile-details {
            display: flex;
            flex-wrap: wrap;
            gap: 12px 16px;
            margin-top: 4px;
        }

        .detail-item {
            background: var(--soft);
            padding: 16px;
            border-radius: 12px;
            border-left: 4px solid var(--g2);
            display: inline-flex;
            flex-direction: column;
            gap: 4px;
        }

        .detail-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: .5px;
            margin-bottom: 0;
        }

        .detail-value {
            font-size: 16px;
            font-weight: 600;
            color: var(--ink);
        }

        /* ===== Section Title ===== */
        .section-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--ink);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .section-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 16px;
            background: linear-gradient(135deg, var(--g2), var(--g4));
            box-shadow: 0 6px 18px var(--glow);
        }

        /* ===== File list ===== */
        .files-grid {
            display: grid;
            gap: 16px;
        }

        .file-card {
            background: var(--soft);
            border: 1px solid var(--line);
            border-radius: 16px;
            padding: 20px;
            transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
        }

        .file-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(16, 24, 40, 0.10);
            border-color: rgba(8, 121, 207, 0.25);
        }

        .file-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
            margin-bottom: 16px;
        }

        .file-info {
            flex: 1;
            min-width: 0;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .file-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--g2), var(--g4));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            flex-shrink: 0;
        }

        .file-details {
            flex: 1;
            min-width: 0;
        }

        .file-name {
            font-size: 16px;
            font-weight: 600;
            color: var(--ink);
            margin-bottom: 4px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .file-meta {
            font-size: 14px;
            color: var(--muted);
        }

        .file-actions {
            display: flex;
            gap: 8px;
            margin-top: 16px;
        }

        /* ===== Buttons ===== */
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: transform .2s ease, box-shadow .2s ease, background .2s ease, color .2s ease;
        }

        .btn-primary {
            color: #fff;
            background: linear-gradient(90deg, var(--g1), var(--g2), var(--g3), var(--g4));
            box-shadow: 0 4px 16px var(--glow);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(8, 121, 207, 0.35);
        }

        .btn-primary:focus-visible {
            outline: 2px solid var(--g3);
            outline-offset: 2px;
        }

        .btn-secondary {
            background: #fff;
            color: var(--ink);
            border: 1px solid var(--line);
        }

        .btn-secondary:hover {
            background: var(--soft);
            transform: translateY(-2px);
            border-color: rgba(8, 121, 207, 0.25);
        }

        .btn-secondary:focus-visible {
            outline: 2px solid var(--g2);
            outline-offset: 2px;
        }

        /* ===== Empty / Loading ===== */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: var(--muted);
            background: var(--soft);
            border-radius: 16px;
            border: 2px dashed var(--line);
        }

        .empty-icon {
            font-size: 48px;
            margin-bottom: 16px;
        }

        .empty-text {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .empty-subtext {
            font-size: 14px;
        }

        /* ===== Responsive ===== */
        @media (max-width: 768px) {
            .container {
                padding: 16px;
            }

            .profile-header {
                flex-direction: row;
                align-items: center;
                gap: 16px;
            }

            .profile-info h1 {
                font-size: 22px;
            }

            .file-actions {
                justify-content: center;
            }

            .file-info {
                flex-direction: column;
                text-align: center;
                gap: 12px;
            }
        }

        /* ===== Animations ===== */
        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header fade-in">
            <h1 style="color: white; text-align: center; margin-bottom: 16px; font-size: 32px;">Profil Peserta</h1>
            <p style="color: rgba(255,255,255,0.8); text-align: center; font-size: 16px;">Informasi lengkap peserta program pelatihan</p>
        </div>

        <!-- Profile Card -->
        <div class="profile-section fade-in">
            <div class="profile-header">
                <div class="avatar">
                    @if($student->profile && file_exists(public_path($student->profile)))
                        <img src="{{ asset($student->profile) }}" alt="Foto {{ $student->nama ?? 'Peserta' }}">
                    @else
                        <?php
                            $nama = $student->nama ?? '';
                            $parts = explode(' ', trim($nama));
                            $first = isset($parts[0]) ? substr($parts[0], 0, 1) : '';
                            $last = count($parts) > 1 ? substr(end($parts), 0, 1) : '';
                            echo strtoupper($first . $last) ?: '👤';
                        ?>
                    @endif
                </div>
                <div class="profile-info">
                    <h1>{{ $student->nama ?? 'Nama tidak tersedia' }}</h1>
                    <div class="subtitle">Program {{ $student->program ?? 'Tidak diketahui' }}</div>
                    <span class="status-badge">
                        <span>✓</span>
                        Peserta Aktif
                    </span>
                </div>
            </div>

            <div class="profile-details">
                <div class="detail-item">
                    <span class="detail-label">Program</span>
                    <span class="detail-value">{{ $student->program ?? '-' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Kelas</span>
                    <span class="detail-value">{{ $student->kelas ?? '-' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Madrasah Aliyah</span>
                    <span class="detail-value">{{ $student->ma ?? '-' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Jurusan</span>
                    <span class="detail-value">{{ $student->jurusan ?? '-' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Gender</span>
                    <span class="detail-value">{{ $student->gender ?? '-' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">ID Peserta</span>
                    <span class="detail-value">#{{ $student->id }}</span>
                </div>
            </div>
        </div>

        <!-- Sertifikat Section -->
        <div class="files-section fade-in">
            <h3 class="section-title">
                <div class="section-icon">🏆</div>
                Sertifikat
            </h3>

            @if(!$sertifikat || count($sertifikat) === 0)
                <div class="empty-state">
                    <div class="empty-icon">📄</div>
                    <div class="empty-text">Belum Ada Sertifikat</div>
                    <div class="empty-subtext">Sertifikat akan ditampilkan setelah diupload</div>
                </div>
            @else
                <div class="files-grid">
                    @foreach($sertifikat as $file)
                        <div class="file-card">
                            <div class="file-info">
                                <div class="file-icon">📋</div>
                                <div class="file-details">
                                    <div class="file-name">{{ $file->nama_file }}</div>
                                    @if($file->created_at)
                                        <div class="file-meta">Diupload: {{ $file->created_at->format('d M Y') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="file-actions">
                                <a class="btn btn-primary" href="{{ asset($file->path) }}" target="_blank" rel="noopener">
                                    👁️ Lihat
                                </a>
                                <a class="btn btn-secondary" href="{{ asset($file->path) }}" download>
                                    💾 Unduh
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Berkas Section -->
        <div class="files-section fade-in">
            <h3 class="section-title">
                <div class="section-icon">📁</div>
                Berkas Pendukung
            </h3>

            @if(!$berkas || count($berkas) === 0)
                <div class="empty-state">
                    <div class="empty-icon">📁</div>
                    <div class="empty-text">Belum Ada Berkas</div>
                    <div class="empty-subtext">Berkas pendukung akan ditampilkan setelah diupload</div>
                </div>
            @else
                <div class="files-grid">
                    @foreach($berkas as $file)
                        <div class="file-card">
                            <div class="file-info">
                                <div class="file-icon">📎</div>
                                <div class="file-details">
                                    <div class="file-name">{{ $file->nama_file }}</div>
                                    @if($file->created_at)
                                        <div class="file-meta">Diupload: {{ $file->created_at->format('d M Y') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="file-actions">
                                <a class="btn btn-primary" href="{{ asset($file->path) }}" target="_blank" rel="noopener">
                                    👁️ Lihat
                                </a>
                                <a class="btn btn-secondary" href="{{ asset($file->path) }}" download>
                                    💾 Unduh
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Footer -->
        <div style="text-align: center; padding: 2rem 1rem; margin-top: 3rem; color: rgba(255,255,255,0.7); font-size: 0.875rem;">
            <p>&copy; {{ date('Y') }} Program Pelatihan - Profil Peserta</p>
        </div>
    </div>

    <script>
        // Simple scroll animation
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe all cards for animation
        document.querySelectorAll('.profile-section, .files-section').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(card);
        });

        // Smooth scroll for better UX
        document.documentElement.style.scrollBehavior = 'smooth';
    </script>
</body>

</html>
