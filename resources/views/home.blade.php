<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mo'men Sarsour — Professional CV & Portfolio</title>
    <meta name="description" content="Mo'men Sarsour - Full Stack Developer | Computer Systems Engineer | Professional CV Builder">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #2f7bff;
            --secondary: #0f172a;
            --accent: #11998e;
            --gradient-1: linear-gradient(135deg, #2f7bff, #11998e);
            --gradient-2: linear-gradient(135deg, #0f172a, #1e3a5f);
            --gradient-3: linear-gradient(135deg, #667eea, #764ba2);
            --gradient-4: linear-gradient(135deg, #f093fb, #f5576c);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f0f2f8;
            color: #1a2035;
            overflow-x: hidden;
        }

        /* ============================================================
           NAVBAR
        ============================================================ */
        .navbar-custom {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(0,0,0,0.06);
            padding: 14px 0;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            transition: all 0.3s;
        }

        .navbar-custom.scrolled {
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        .navbar-custom .brand {
            font-size: 22px;
            font-weight: 800;
            color: #0f172a;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .navbar-custom .brand .logo-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--gradient-1);
            display: inline-block;
        }

        .navbar-custom .brand span {
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .navbar-custom .nav-link {
            font-weight: 600;
            color: #475569;
            transition: all 0.2s;
            padding: 8px 16px;
            border-radius: 10px;
            font-size: 14px;
        }

        .navbar-custom .nav-link:hover {
            color: #0f172a;
            background: rgba(47,123,255,0.06);
        }

        .btn-nav {
            background: var(--gradient-1);
            color: white;
            padding: 10px 28px;
            border-radius: 50px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s;
            border: none;
            font-size: 14px;
            box-shadow: 0 4px 15px rgba(47,123,255,0.3);
        }

        .btn-nav:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(47,123,255,0.4);
            color: white;
        }

        .btn-outline-nav {
            background: transparent;
            border: 2px solid #2f7bff;
            color: #2f7bff;
            padding: 8px 24px;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
            font-size: 14px;
        }

        .btn-outline-nav:hover {
            background: #2f7bff;
            color: white;
        }

        /* ============================================================
           HERO
        ============================================================ */
        .hero-section {
            padding: 130px 0 60px;
            position: relative;
            overflow: hidden;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .hero-section .bg-shapes {
            position: absolute;
            inset: 0;
            z-index: 0;
            overflow: hidden;
        }

        .hero-section .bg-shapes .shape {
            position: absolute;
            border-radius: 50%;
            opacity: 0.06;
        }

        .hero-section .bg-shapes .shape-1 {
            width: 500px;
            height: 500px;
            background: #2f7bff;
            top: -200px;
            right: -100px;
        }

        .hero-section .bg-shapes .shape-2 {
            width: 300px;
            height: 300px;
            background: #11998e;
            bottom: -100px;
            left: -100px;
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .hero-badge {
            display: inline-block;
            background: rgba(47,123,255,0.1);
            color: #2f7bff;
            padding: 6px 20px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 20px;
            border: 1px solid rgba(47,123,255,0.15);
        }

        .hero-name {
            font-size: 52px;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 8px;
        }

        .hero-name .highlight {
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-title {
            font-size: 20px;
            color: #2f7bff;
            font-weight: 600;
            margin-bottom: 16px;
        }

        .hero-bio {
            font-size: 16px;
            color: #475569;
            max-width: 520px;
            line-height: 1.8;
            margin-bottom: 28px;
        }

        .hero-actions {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
            margin-bottom: 32px;
        }

        .btn-hero-primary {
            background: var(--gradient-1);
            color: white;
            padding: 14px 32px;
            border-radius: 50px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s;
            border: none;
            font-size: 15px;
            box-shadow: 0 4px 15px rgba(47,123,255,0.3);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-hero-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(47,123,255,0.4);
            color: white;
        }

        .btn-hero-secondary {
            background: white;
            color: #0f172a;
            padding: 14px 32px;
            border-radius: 50px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s;
            border: 2px solid #e8edf5;
            font-size: 15px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-hero-secondary:hover {
            border-color: #2f7bff;
            transform: translateY(-3px);
        }

        .hero-stats {
            display: flex;
            gap: 40px;
            flex-wrap: wrap;
        }

        .hero-stat .number {
            font-size: 28px;
            font-weight: 800;
            color: #0f172a;
            display: block;
        }

        .hero-stat .label {
            font-size: 13px;
            color: #7a869a;
            font-weight: 500;
        }

        /* ============================================================
           TEMPLATES SHOWCASE
        ============================================================ */
        .templates-section {
            padding: 60px 0 80px;
            background: white;
        }

        .template-showcase-card {
            background: #f8fafc;
            border-radius: 24px;
            overflow: hidden;
            border: 1px solid #e8edf5;
            transition: all 0.4s;
            height: 100%;
            position: relative;
        }

        .template-showcase-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 30px 50px rgba(0,0,0,0.08);
            border-color: rgba(47,123,255,0.15);
        }

        .template-showcase-card .preview {
            height: 220px;
            background: var(--gradient-2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .template-showcase-card .preview .preview-content {
            text-align: center;
            padding: 20px;
        }

        .template-showcase-card .preview .preview-content .name {
            font-size: 20px;
            font-weight: 700;
        }

        .template-showcase-card .preview .preview-content .title {
            font-size: 12px;
            opacity: 0.8;
        }

        .template-showcase-card .preview .preview-content .line {
            width: 40px;
            height: 2px;
            background: rgba(255,255,255,0.3);
            margin: 8px auto;
        }

        .template-showcase-card .preview .preview-content .skills {
            display: flex;
            gap: 4px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .template-showcase-card .preview .preview-content .skills span {
            background: rgba(255,255,255,0.15);
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 9px;
        }

        .template-showcase-card .body {
            padding: 20px;
        }

        .template-showcase-card .body h5 {
            font-weight: 700;
            margin-bottom: 4px;
        }

        .template-showcase-card .body p {
            font-size: 13px;
            color: #7a869a;
            margin-bottom: 12px;
        }

        .template-showcase-card .body .badge-featured {
            background: var(--gradient-1);
            color: white;
            padding: 2px 12px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
        }

        .btn-preview {
            background: transparent;
            border: 1.5px solid #2f7bff;
            color: #2f7bff;
            padding: 6px 20px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-preview:hover {
            background: #2f7bff;
            color: white;
        }

        /* ============================================================
           FEATURES
        ============================================================ */
        .features-section {
            padding: 60px 0;
            background: #f8fafc;
        }

        .feature-card {
            text-align: center;
            padding: 32px 20px;
            background: white;
            border-radius: 24px;
            border: 1px solid #e8edf5;
            transition: all 0.3s;
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.06);
        }

        .feature-card .icon {
            font-size: 40px;
            display: block;
            margin-bottom: 16px;
        }

        .feature-card h5 {
            font-weight: 700;
            margin-bottom: 8px;
        }

        .feature-card p {
            font-size: 13px;
            color: #7a869a;
            margin: 0;
        }

        /* ============================================================
           SECTION TITLES
        ============================================================ */
        .section-title {
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .section-title .highlight {
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .section-subtitle {
            color: #7a869a;
            font-size: 16px;
            margin-bottom: 40px;
        }

        /* ============================================================
           ANIMATIONS
        ============================================================ */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .animate { animation: fadeUp 0.7s ease forwards; opacity: 0; }
        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }
        .delay-4 { animation-delay: 0.4s; }

        .float-animation {
            animation: float 3s ease-in-out infinite;
        }

        /* ============================================================
           FOOTER
        ============================================================ */
        .footer {
            background: #0f172a;
            color: #94a3b8;
            padding: 40px 0;
        }

        .footer a {
            color: #94a3b8;
            text-decoration: none;
            transition: all 0.2s;
            font-size: 14px;
        }

        .footer a:hover {
            color: white;
        }

        /* ============================================================
           RESPONSIVE
        ============================================================ */
        @media (max-width: 992px) {
            .hero-name { font-size: 40px; }
            .hero-section { padding: 110px 0 40px; }
            .section-title { font-size: 26px; }
        }

        @media (max-width: 768px) {
            .hero-name { font-size: 32px; }
            .hero-title { font-size: 18px; }
            .hero-bio { font-size: 15px; }
            .hero-section { padding: 90px 0 30px; text-align: center; }
            .hero-actions { justify-content: center; }
            .hero-stats { justify-content: center; }
            .hero-bio { max-width: 100%; }
            .section-title { font-size: 22px; }
            .template-showcase-card .preview { height: 180px; }
        }
    </style>
</head>
<body>

{{-- Navbar --}}
<nav class="navbar-custom" id="navbar">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between">
            <a href="{{ route('home') }}" class="brand">
                <span class="logo-dot"></span>
                <span>Mo'men</span>
            </a>

            <div class="d-flex align-items-center gap-2">
                <a href="#templates" class="nav-link d-none d-md-inline">Templates</a>
                <a href="#features" class="nav-link d-none d-md-inline">Features</a>
                @guest
                    <a href="{{ route('login') }}" class="btn-outline-nav me-2 d-none d-sm-inline">
                        <i class="bi bi-box-arrow-in-right"></i> Login
                    </a>
                    <a href="{{ route('register') }}" class="btn-nav">
                        <i class="bi bi-person-plus"></i> Get Started
                    </a>
                @else
                    <a href="{{ route('dashboard.index') }}" class="btn-nav">
                        <i class="bi bi-grid-1x2-fill"></i> Dashboard
                    </a>
                @endguest
            </div>
        </div>
    </div>
</nav>

{{-- Hero Section --}}
<section class="hero-section" id="hero">
    <div class="bg-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
    </div>

    <div class="container hero-content">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <div class="animate delay-1">
                    <div class="hero-badge">
                        <i class="bi bi-code-square"></i> Professional CV Builder
                    </div>

                    <h1 class="hero-name">
                        Create Your <span class="highlight">Professional CV</span>
                    </h1>
                    <div class="hero-title">Choose from multiple stunning templates</div>

                    <p class="hero-bio">
                        Build a professional CV that stands out. Choose from 5 different templates,
                        customize colors, and download as PDF. Perfect for job applications and
                        showcasing your skills.
                    </p>

                    <div class="hero-actions">
                        @guest
                            <a href="{{ route('register') }}" class="btn-hero-primary">
                                <i class="bi bi-person-plus"></i> Get Started Free
                            </a>
                            <a href="#templates" class="btn-hero-secondary">
                                <i class="bi bi-eye-fill"></i> View Templates
                            </a>
                        @else
                            <a href="{{ route('dashboard.resume.index') }}" class="btn-hero-primary">
                                <i class="bi bi-pencil-fill"></i> Build Your CV
                            </a>
                        @endguest
                    </div>

                    <div class="hero-stats">
                        <div class="hero-stat">
                            <span class="number">5</span>
                            <span class="label">Templates</span>
                        </div>
                        <div class="hero-stat">
                            <span class="number">12+</span>
                            <span class="label">Designs</span>
                        </div>
                        <div class="hero-stat">
                            <span class="number">100%</span>
                            <span class="label">Free</span>
                        </div>
                        <div class="hero-stat">
                            <span class="number">PDF</span>
                            <span class="label">Download</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5 text-center animate delay-3">
                <div style="background: rgba(255,255,255,0.8); backdrop-filter: blur(20px); border-radius: 24px; padding: 32px; border: 1px solid rgba(255,255,255,0.3); box-shadow: 0 20px 50px rgba(0,0,0,0.08);">
                    <div style="font-size: 14px; color: #7a869a; font-weight: 600; margin-bottom: 16px;">📄 Sample CV Preview</div>
                    <div style="background: var(--gradient-2); border-radius: 16px; padding: 20px; color: white; text-align: left;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 40px; height: 40px; border-radius: 50%; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; font-weight: 700;">MS</div>
                            <div>
                                <div style="font-weight: 700;">Mo'men Sarsour</div>
                                <div style="font-size: 10px; opacity: 0.7;">Full Stack Developer</div>
                            </div>
                        </div>
                        <div style="margin-top: 12px;">
                            <div style="height: 4px; background: rgba(255,255,255,0.2); border-radius: 2px; margin-bottom: 6px; width: 80%;"></div>
                            <div style="height: 4px; background: rgba(255,255,255,0.15); border-radius: 2px; margin-bottom: 6px; width: 60%;"></div>
                            <div style="display: flex; gap: 6px; flex-wrap: wrap; margin-top: 8px;">
                                <span style="background: rgba(255,255,255,0.15); padding: 2px 10px; border-radius: 20px; font-size: 9px;">Laravel</span>
                                <span style="background: rgba(255,255,255,0.15); padding: 2px 10px; border-radius: 20px; font-size: 9px;">PHP</span>
                                <span style="background: rgba(255,255,255,0.15); padding: 2px 10px; border-radius: 20px; font-size: 9px;">MySQL</span>
                            </div>
                        </div>
                    </div>
                    <div style="font-size: 11px; color: #94a3b8; margin-top: 12px;">
                        <i class="bi bi-check-circle-fill" style="color: #22c55e;"></i> Professional design • Multiple formats
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Templates Showcase --}}
<section class="templates-section" id="templates">
    <div class="container">
        <div class="text-center animate delay-1">
            <h2 class="section-title">🎨 <span class="highlight">CV Templates</span></h2>
            <p class="section-subtitle">Choose the perfect design for your professional CV</p>
        </div>

        <div class="row g-4">
            {{-- Template 1: Modern --}}
            <div class="col-lg-4 col-md-6 animate delay-2">
                <div class="template-showcase-card">
                    <div class="preview" style="background: linear-gradient(135deg, #2f7bff, #1a5fcc);">
                        <div class="preview-content">
                            <div class="name">Mo'men Sarsour</div>
                            <div class="title">Full Stack Developer</div>
                            <div class="line"></div>
                            <div class="skills">
                                <span>Laravel</span>
                                <span>PHP</span>
                                <span>MySQL</span>
                                <span>React</span>
                            </div>
                            <div style="margin-top: 12px; font-size: 10px; opacity: 0.6;">📧 momensarsour5@gmail.com</div>
                        </div>
                    </div>
                    <div class="body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5>✨ Modern</h5>
                                <p>Clean & professional design</p>
                            </div>
                            <span class="badge-featured">Featured</span>
                        </div>
                        <a href="{{ route('cv.show', $user->username ?? '') }}" target="_blank" class="btn-preview">
                            <i class="bi bi-eye-fill"></i> Preview
                        </a>
                    </div>
                </div>
            </div>

            {{-- Template 2: Minimal --}}
            <div class="col-lg-4 col-md-6 animate delay-3">
                <div class="template-showcase-card">
                    <div class="preview" style="background: linear-gradient(135deg, #0f172a, #1e293b);">
                        <div class="preview-content">
                            <div class="name">Mo'men Sarsour</div>
                            <div class="title">Full Stack Developer</div>
                            <div class="line"></div>
                            <div class="skills">
                                <span>Laravel</span>
                                <span>PHP</span>
                                <span>MySQL</span>
                            </div>
                            <div style="margin-top: 12px; font-size: 10px; opacity: 0.6;">📧 momensarsour5@gmail.com</div>
                        </div>
                    </div>
                    <div class="body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5>📄 Minimal</h5>
                                <p>Simple & elegant design</p>
                            </div>
                            <span class="badge-featured" style="background: #0f172a;">Clean</span>
                        </div>
                        <a href="{{ route('cv.show', $user->username ?? '') }}?template=minimal" target="_blank" class="btn-preview">
                            <i class="bi bi-eye-fill"></i> Preview
                        </a>
                    </div>
                </div>
            </div>

            {{-- Template 3: Creative --}}
            <div class="col-lg-4 col-md-6 animate delay-4">
                <div class="template-showcase-card">
                    <div class="preview" style="background: linear-gradient(135deg, #f093fb, #f5576c);">
                        <div class="preview-content">
                            <div class="name">Mo'men Sarsour</div>
                            <div class="title">Full Stack Developer</div>
                            <div class="line"></div>
                            <div class="skills">
                                <span>Laravel</span>
                                <span>PHP</span>
                                <span>React</span>
                            </div>
                            <div style="margin-top: 12px; font-size: 10px; opacity: 0.6;">📧 momensarsour5@gmail.com</div>
                        </div>
                    </div>
                    <div class="body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5>🎨 Creative</h5>
                                <p>Bold & colorful design</p>
                            </div>
                            <span class="badge-featured" style="background: #f5576c;">Colorful</span>
                        </div>
                        <a href="{{ route('cv.show', $user->username ?? '') }}?template=creative" target="_blank" class="btn-preview">
                            <i class="bi bi-eye-fill"></i> Preview
                        </a>
                    </div>
                </div>
            </div>

            {{-- Template 4: Professional --}}
            <div class="col-lg-4 col-md-6 animate delay-2">
                <div class="template-showcase-card">
                    <div class="preview" style="background: linear-gradient(135deg, #1e3a5f, #0f172a);">
                        <div class="preview-content">
                            <div class="name">Mo'men Sarsour</div>
                            <div class="title">Full Stack Developer</div>
                            <div class="line"></div>
                            <div class="skills">
                                <span>Laravel</span>
                                <span>PHP</span>
                                <span>MySQL</span>
                                <span>Bootstrap</span>
                            </div>
                            <div style="margin-top: 12px; font-size: 10px; opacity: 0.6;">📧 momensarsour5@gmail.com</div>
                        </div>
                    </div>
                    <div class="body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5>💼 Professional</h5>
                                <p>Classic corporate design</p>
                            </div>
                            <span class="badge-featured" style="background: #1e3a5f;">Corporate</span>
                        </div>
                        <a href="{{ route('cv.show', $user->username ?? '') }}?template=professional" target="_blank" class="btn-preview">
                            <i class="bi bi-eye-fill"></i> Preview
                        </a>
                    </div>
                </div>
            </div>

            {{-- Template 5: Sidebar --}}
            <div class="col-lg-4 col-md-6 animate delay-3">
                <div class="template-showcase-card">
                    <div class="preview" style="background: linear-gradient(135deg, #11998e, #38ef7d);">
                        <div class="preview-content">
                            <div class="name">Mo'men Sarsour</div>
                            <div class="title">Full Stack Developer</div>
                            <div class="line"></div>
                            <div class="skills">
                                <span>Laravel</span>
                                <span>PHP</span>
                                <span>MySQL</span>
                            </div>
                            <div style="margin-top: 12px; font-size: 10px; opacity: 0.6;">📧 momensarsour5@gmail.com</div>
                        </div>
                    </div>
                    <div class="body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5>📊 Sidebar</h5>
                                <p>Two-column layout with sidebar</p>
                            </div>
                            <span class="badge-featured" style="background: #11998e;">Modern</span>
                        </div>
                        <a href="{{ route('cv.show', $user->username ?? '') }}?template=sidebar" target="_blank" class="btn-preview">
                            <i class="bi bi-eye-fill"></i> Preview
                        </a>
                    </div>
                </div>
            </div>

            {{-- Template 6: Classic --}}
            <div class="col-lg-4 col-md-6 animate delay-4">
                <div class="template-showcase-card">
                    <div class="preview" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
                        <div class="preview-content">
                            <div class="name">Mo'men Sarsour</div>
                            <div class="title">Full Stack Developer</div>
                            <div class="line"></div>
                            <div class="skills">
                                <span>Laravel</span>
                                <span>PHP</span>
                                <span>MySQL</span>
                            </div>
                            <div style="margin-top: 12px; font-size: 10px; opacity: 0.6;">📧 momensarsour5@gmail.com</div>
                        </div>
                    </div>
                    <div class="body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5>📜 Classic</h5>
                                <p>Traditional elegant design</p>
                            </div>
                            <span class="badge-featured" style="background: #7c3aed;">Elegant</span>
                        </div>
                        <a href="{{ route('cv.show', $user->username ?? '') }}?template=classic" target="_blank" class="btn-preview">
                            <i class="bi bi-eye-fill"></i> Preview
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Features Section --}}
<section class="features-section" id="features">
    <div class="container">
        <div class="text-center animate delay-1">
            <h2 class="section-title">✨ <span class="highlight">Why Choose Us</span></h2>
            <p class="section-subtitle">Everything you need to create a professional CV</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4 animate delay-2">
                <div class="feature-card">
                    <span class="icon">🎨</span>
                    <h5>5+ Templates</h5>
                    <p>Choose from multiple professional designs to match your style</p>
                </div>
            </div>
            <div class="col-md-4 animate delay-3">
                <div class="feature-card">
                    <span class="icon">🎯</span>
                    <h5>Easy Customization</h5>
                    <p>Customize colors, fonts, and layout to make it truly yours</p>
                </div>
            </div>
            <div class="col-md-4 animate delay-4">
                <div class="feature-card">
                    <span class="icon">📥</span>
                    <h5>PDF Download</h5>
                    <p>Export your CV as PDF with one click, ready for applications</p>
                </div>
            </div>
            <div class="col-md-4 animate delay-2">
                <div class="feature-card">
                    <span class="icon">📱</span>
                    <h5>Responsive Design</h5>
                    <p>Your CV looks great on all devices - desktop, tablet, and mobile</p>
                </div>
            </div>
            <div class="col-md-4 animate delay-3">
                <div class="feature-card">
                    <span class="icon">🔒</span>
                    <h5>Secure & Private</h5>
                    <p>Your data is safe and only visible to you</p>
                </div>
            </div>
            <div class="col-md-4 animate delay-4">
                <div class="feature-card">
                    <span class="icon">⚡</span>
                    <h5>Fast & Free</h5>
                    <p>Create your professional CV instantly, completely free</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- CTA Section --}}
<section style="background: var(--gradient-2); padding: 60px 0; color: white;">
    <div class="container text-center">
        <h2 style="font-size: 36px; font-weight: 800;">Ready to build your <span style="color: #63b3ed;">professional CV</span>?</h2>
        <p style="opacity: 0.8; font-size: 17px; max-width: 500px; margin: 12px auto 32px;">Join thousands of professionals who use our platform</p>
        @guest
            <a href="{{ route('register') }}" class="btn-hero-primary" style="background: white; color: #0f172a; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
                <i class="bi bi-person-plus"></i> Get Started Free
            </a>
            <a href="{{ route('login') }}" style="color: white; opacity: 0.7; text-decoration: none; margin-left: 20px; font-weight: 600;">
                Already have an account? Login
            </a>
        @else
            <a href="{{ route('dashboard.resume.index') }}" class="btn-hero-primary" style="background: white; color: #0f172a; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
                <i class="bi bi-pencil-fill"></i> Build Your CV
            </a>
        @endguest
    </div>
</section>

{{-- Footer --}}
<footer class="footer">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-4">
                <div class="brand" style="color: white; font-size: 20px; font-weight: 800; display: flex; align-items: center; gap: 8px; text-decoration: none;">
                    <span class="logo-dot" style="width:10px;height:10px;border-radius:50%;background:var(--gradient-1);display:inline-block;"></span>
                    <span>Mo'men</span>
                </div>
                <p style="font-size: 13px; margin-top: 8px; opacity: 0.6;">Professional CV Builder</p>
            </div>
            <div class="col-md-4 text-center">
                <div style="display: flex; gap: 16px; justify-content: center; flex-wrap: wrap;">
                    <a href="#hero">Home</a>
                    <a href="#templates">Templates</a>
                    <a href="#features">Features</a>
                    <a href="{{ route('login') }}">Login</a>
                    <a href="{{ route('register') }}">Register</a>
                </div>
            </div>
            <div class="col-md-4 text-md-end">
                <p style="font-size: 13px; opacity: 0.6;">&copy; {{ date('Y') }} Mo'men Sarsour</p>
                <p style="font-size: 12px; opacity: 0.4;">Built with ❤️</p>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Navbar scroll effect
    window.addEventListener('scroll', function() {
        const navbar = document.getElementById('navbar');
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });
</script>
</body>
</html>
