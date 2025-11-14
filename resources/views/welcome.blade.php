<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIOPSUS - Sistem Informasi Operasi Khusus</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #1447e6;
            --primary-dark: #0f3bb8;
            --primary-light: #3a5efc;
            --secondary: #f59e0b;
            --dark: #1a1a1a;
            --gray: #6b7280;
            --light-gray: #f3f4f6;
            --white: #ffffff;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            line-height: 1.6;
            color: var(--dark);
            overflow-x: hidden;
        }

        /* Navigation */
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.05);
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary);
            text-decoration: none;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
            list-style: none;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--dark);
            font-weight: 500;
            transition: color 0.3s ease;
            position: relative;
        }

        .nav-links a:hover {
            color: var(--primary);
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-block;
            border: none;
            cursor: pointer;
            
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white !important ;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(1, 102, 48, 0.3);
        }

        .btn-outline {
            border: 2px solid var(--primary);
            color: var(--primary);
            background: transparent;
        }

        .btn-outline:hover {
            background: var(--primary);
            color: white;
        }

        /* Hero Section */
        .hero {
            padding: 140px 2rem 80px;
            background: linear-gradient(135deg, #f0fdf4 0%, #ffffff 100%);
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 800px;
            height: 800px;
            background: radial-gradient(circle, rgba(1, 102, 48, 0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .hero-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
            position: relative;
            z-index: 1;
        }

        .hero-content h1 {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1.5rem;
            color: var(--dark);
        }

        .hero-content h1 .highlight {
            color: var(--primary);
            position: relative;
            display: inline-block;
        }

        .hero-content p {
            font-size: 1.25rem;
            color: var(--gray);
            margin-bottom: 2rem;
            line-height: 1.8;
        }

        .hero-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .hero-image {
            position: relative;
        }

        .hero-image img {
            width: 100%;
            height: auto;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }

        .floating-card {
            position: absolute;
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            animation: float 3s ease-in-out infinite;
        }

        .floating-card-1 {
            top: 10%;
            right: -10%;
        }

        .floating-card-2 {
            bottom: 10%;
            left: -10%;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        /* Features Section */
        .features {
            padding: 80px 2rem;
            background: white;
        }

        .section-title {
            text-align: center;
            margin-bottom: 4rem;
        }

        .section-title h2 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
            color: var(--dark);
        }

        .section-title p {
            font-size: 1.125rem;
            color: var(--gray);
            max-width: 600px;
            margin: 0 auto;
        }

        .features-grid {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .feature-card {
            padding: 2rem;
            background: var(--light-gray);
            border-radius: 16px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 0;
            background: var(--primary);
            transition: height 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
        }

        .feature-card:hover::before {
            height: 100%;
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            font-size: 1.75rem;
        }

        .feature-card h3 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--dark);
        }

        .feature-card p {
            color: var(--gray);
            line-height: 1.8;
        }

        /* Stats Section */
        .stats {
            padding: 80px 2rem;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
        }

        .stats-grid {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            display: block;
        }

        .stat-label {
            font-size: 1.125rem;
            opacity: 0.9;
        }

        /* CTA Section */
        .cta {
            padding: 100px 2rem;
            background: linear-gradient(135deg, #f0fdf4 0%, #ffffff 100%);
            text-align: center;
        }

        .cta-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .cta h2 {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            color: var(--dark);
        }

        .cta p {
            font-size: 1.25rem;
            color: var(--gray);
            margin-bottom: 2.5rem;
        }

        /* Footer */
        .footer {
            background: var(--primary);
            color: white;
            padding: 3rem 2rem 2rem;
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 3rem;
            margin-bottom: 2rem;
        }

        .footer-about h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .footer-about p {
            color: rgba(255, 255, 255, 0.7);
            line-height: 1.8;
        }

        .footer-links h4 {
            font-size: 1.125rem;
            margin-bottom: 1rem;
        }

        .footer-links ul {
            list-style: none;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            display: block;
            margin-bottom: 0.5rem;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: white;
        }

        .footer-bottom {
            max-width: 1200px;
            margin: 0 auto;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
            color: rgba(255, 255, 255, 0.6);
        }

        /* Responsive */
        @media (max-width: 968px) {
            .hero-container {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .hero-content h1 {
                font-size: 2.5rem;
            }

            .hero-buttons {
                justify-content: center;
            }

            .hero-image {
                order: -1;
            }

            .floating-card {
                display: none;
            }

            .nav-links {
                display: none;
            }

            .footer-container {
                grid-template-columns: 1fr;
                text-align: center;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="/" class="logo">
                <span>SIOPSUS</span>
            </a>
            <ul class="nav-links">
                @auth
                    <li><a href="{{ url('/admin') }}">Dashboard</a></li>
                @else
                    <li><a href="#features">Fitur</a></li>
                    <li><a href="#about">Tentang</a></li>
                    <li><a href="{{ url('/admin') }}" class="btn btn-primary">Masuk</a></li>
                @endauth
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-container">
            <div class="hero-content">
                <h1>
                    Sistem Informasi
                    <span class="highlight">Operasi Khusus</span>
                </h1>
                <p>
                    Platform modern untuk mengelola dan memonitor operasi pajak kendaraan bermotor secara efisien, 
                    transparan, dan real-time.
                </p>
                <div class="hero-buttons">
                    @auth
                        <a href="{{ url('/admin') }}" class="btn btn-primary">Buka Dashboard</a>
                    @else
                        <a href="{{ url('/admin') }}" class="btn btn-primary">Mulai Sekarang</a>
                        <a href="#features" class="btn btn-outline">Pelajari Lebih Lanjut</a>
                    @endauth
                </div>
            </div>
            <div class="hero-image">
                <svg width="500" height="400" viewBox="0 0 500 400" style="width: 100%; height: auto;">
                    <!-- Dashboard illustration -->
                    <rect x="50" y="50" width="400" height="300" rx="10" fill="#f3f4f6" stroke="#016630" stroke-width="2"/>
                    <rect x="70" y="70" width="180" height="100" rx="8" fill="#ffffff" stroke="#e5e7eb" stroke-width="2"/>
                    <rect x="270" y="70" width="160" height="100" rx="8" fill="#ffffff" stroke="#e5e7eb" stroke-width="2"/>
                    <rect x="70" y="190" width="360" height="140" rx="8" fill="#ffffff" stroke="#e5e7eb" stroke-width="2"/>
                    <!-- Accent elements -->
                    <circle cx="100" cy="100" r="15" fill="#016630" opacity="0.2"/>
                    <circle cx="300" cy="100" r="15" fill="#f59e0b" opacity="0.2"/>
                    <rect x="80" y="125" width="80" height="8" rx="4" fill="#016630"/>
                    <rect x="280" y="125" width="60" height="8" rx="4" fill="#f59e0b"/>
                    <!-- Table lines -->
                    <line x1="80" y1="220" x2="420" y2="220" stroke="#e5e7eb" stroke-width="2"/>
                    <line x1="80" y1="250" x2="420" y2="250" stroke="#e5e7eb" stroke-width="2"/>
                    <line x1="80" y1="280" x2="420" y2="280" stroke="#e5e7eb" stroke-width="2"/>
                    <line x1="80" y1="310" x2="420" y2="310" stroke="#e5e7eb" stroke-width="2"/>
                </svg>
                
                <div class="floating-card floating-card-1">
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">✅</div>
                    <div style="font-weight: 600;">1,234+</div>
                    <div style="font-size: 0.875rem; color: var(--gray);">Kendaraan Terdata</div>
                </div>
                
                <div class="floating-card floating-card-2">
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">📊</div>
                    <div style="font-weight: 600;">Real-time</div>
                    <div style="font-size: 0.875rem; color: var(--gray);">Laporan Lengkap</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="section-title">
            <h2>Fitur Unggulan</h2>
            <p>Sistem yang lengkap dan mudah digunakan untuk mendukung operasi pajak kendaraan Anda</p>
        </div>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">📋</div>
                <h3>Manajemen Data</h3>
                <p>Kelola data kendaraan, pajak, dan denda dengan sistem yang terorganisir dan mudah diakses kapan saja.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📊</div>
                <h3>Laporan Real-time</h3>
                <p>Pantau statistik operasi secara langsung dengan dashboard interaktif dan export PDF profesional.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🔍</div>
                <h3>Filter & Pencarian</h3>
                <p>Temukan data yang Anda butuhkan dengan cepat menggunakan filter berdasarkan tanggal, status, dan jenis kendaraan.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📈</div>
                <h3>Analisis Potensi</h3>
                <p>Hitung potensi pajak dan denda secara otomatis dengan sistem kalkulasi yang akurat dan transparan.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📄</div>
                <h3>Export Excel & PDF</h3>
                <p>Ekspor laporan dalam format Excel atau PDF dengan layout profesional siap cetak atau presentasi.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🔒</div>
                <h3>Keamanan Data</h3>
                <p>Data Anda terlindungi dengan sistem autentikasi dan enkripsi standar industri untuk keamanan maksimal.</p>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats">
        <div class="stats-grid">
            <div class="stat-item">
                <span class="stat-number">1000+</span>
                <span class="stat-label">Data Kendaraan</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">24/7</span>
                <span class="stat-label">Akses Real-time</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">100%</span>
                <span class="stat-label">Akurasi Data</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">⚡</span>
                <span class="stat-label">Proses Cepat</span>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta" id="about">
        <div class="cta-container">
            <h2>Siap Memulai?</h2>
            <p>
                Bergabunglah dengan SIOPSUS dan optimalkan pengelolaan operasi pajak kendaraan Anda 
                dengan sistem yang modern, efisien, dan terpercaya.
            </p>
            @auth
                <a href="{{ url('/admin') }}" class="btn btn-primary">Buka Dashboard</a>
            @else
                <a href="{{ url('/admin') }}" class="btn btn-primary">Masuk Sekarang</a>
            @endauth
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-about">
                <h3>SIOPSUS</h3>
                <p>
                    Sistem Informasi Operasi Khusus untuk pengelolaan dan monitoring 
                    pajak kendaraan bermotor secara efisien dan transparan.
                </p>
            </div>
            <div class="footer-links">
                <h4>Fitur</h4>
                <ul>
                    <li><a href="#features">Manajemen Data</a></li>
                    <li><a href="#features">Laporan Real-time</a></li>
                    <li><a href="#features">Export PDF/Excel</a></li>
                </ul>
            </div>
            <div class="footer-links">
                <h4>Tentang</h4>
                <ul>
                    <li><a href="#about">Tentang Kami</a></li>
                    <li><a href="#features">Fitur</a></li>
                    <li><a href="{{ url('/admin') }}">Login</a></li>
                </ul>
            </div>
            <div class="footer-links">
                <h4>Kontak</h4>
                <ul>
                    <li><a href="mailto:info@siopsus.id">info@siopsus.id</a></li>
                    <li><a href="tel:+62">+62 xxx xxxx xxxx</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} SIOPSUS. Sistem Informasi Operasi Khusus. All rights reserved.</p>
        </div>
    </footer>
</body>

