<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GLE Academy — Belajar Kapan Saja, di Mana Saja</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700&family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-display { font-family: 'Fraunces', serif; }
        .font-mono-label { font-family: 'IBM Plex Mono', monospace; letter-spacing: 0.08em; }

        .paper-bg {
            background-color: #F6F4EF;
            background-image:
                repeating-linear-gradient(180deg, transparent, transparent 34px, #E4DFD2 35px),
                radial-gradient(#D8D2C0 1px, transparent 1px);
            background-size: 100% 35px, 18px 18px;
        }

        .hero-glow {
            position: absolute;
            inset: 0;
            overflow: hidden;
            pointer-events: none;
            z-index: 0;
        }
        .hero-glow span {
            position: absolute;
            border-radius: 999px;
            filter: blur(60px);
            opacity: 0.35;
        }

        .mark {
            position: relative;
            white-space: nowrap;
        }
        .mark::before {
            content: "";
            position: absolute;
            left: -0.15em;
            right: -0.15em;
            bottom: 0.08em;
            top: 0.42em;
            background: #F2B705;
            transform: rotate(-1deg);
            z-index: -1;
            border-radius: 2px;
        }

        .index-card {
            position: relative;
            background: #FFFDF9;
            border: 1px solid #E4DFD2;
            box-shadow: 0 1px 2px rgba(23,35,63,0.04);
        }
        .index-card::before,
        .index-card::after {
            content: "";
            position: absolute;
            top: 14px;
            width: 10px;
            height: 10px;
            border-radius: 999px;
            background: #F6F4EF;
            border: 1px solid #E4DFD2;
        }
        .index-card::before { left: 14px; }
        .index-card::after { right: 14px; }

        .tape {
            position: absolute;
            top: -10px;
            height: 22px;
            width: 64px;
            opacity: 0.85;
        }

        .course-stack .card {
            position: absolute;
            width: 220px;
            border-radius: 10px;
            border: 1px solid #E4DFD2;
            background: #FFFDF9;
            box-shadow: 0 12px 28px rgba(23,35,63,0.12);
            padding: 16px;
        }
    </style>
</head>
<body class="antialiased text-[#17233F]" style="background:#F6F4EF;">

    <!-- Navbar -->
    <nav class="border-b border-[#E4DFD2] sticky top-0 z-20" style="background:#F6F4EF;">
        <div class="max-w-6xl mx-auto px-6 py-5 flex justify-between items-center">
            <span class="font-display text-xl font-semibold">GLE <span class="text-[#2F6F62]">Academy</span></span>
            <div class="flex items-center gap-6">
                @auth
                    <a href="{{ route('dashboard') }}" class="font-mono-label text-xs uppercase text-[#17233F] hover:text-[#2F6F62]">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="font-mono-label text-xs uppercase text-[#17233F] hover:text-[#2F6F62]">Login</a>
                    <a href="{{ route('register') }}" class="bg-[#17233F] text-white px-5 py-2.5 rounded text-sm font-medium hover:bg-[#2F6F62] transition-colors">Daftar</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero -->
    <section class="paper-bg relative overflow-hidden">
        <div class="hero-glow">
            <span style="width:380px;height:380px;background:#F2B705;top:-120px;left:-80px;"></span>
            <span style="width:320px;height:320px;background:#2F6F62;bottom:-140px;right:-60px;"></span>
        </div>

        <!-- doodles -->
        <svg class="absolute left-[8%] top-[22%] w-8 h-8 text-[#2F6F62] hidden md:block" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 2l1.8 5.6H20l-4.6 3.5L17 17l-5-3.6L7 17l1.6-5.9L4 7.6h6.2z"/></svg>
        <svg class="absolute right-[10%] top-[18%] w-10 h-10 text-[#F2B705] hidden md:block" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="9"/><path d="M9 12l2 2 4-4"/></svg>
        <svg class="absolute right-[6%] bottom-[10%] w-16 h-10 text-[#17233F]/30 hidden md:block" viewBox="0 0 80 40" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 30 Q 20 5, 40 20 T 78 10" stroke-dasharray="4 5"/></svg>

        <div class="max-w-6xl mx-auto px-6 pt-24 pb-32 relative z-10 grid md:grid-cols-2 gap-12 items-center">
            <div>
                <p class="font-mono-label text-xs uppercase text-[#2F6F62] mb-5">Platform Belajar Online</p>
                <h1 class="font-display text-4xl md:text-5xl font-semibold leading-tight mb-6">
                    Belajar Kapan Saja, di <span class="mark">Mana Saja</span>
                </h1>
                <p class="text-[#4B5566] mb-10 max-w-md text-lg leading-relaxed">
                    GLE Academy adalah platform belajar online dengan kelas dari berbagai instruktur, lengkap dengan materi, video, dan quiz interaktif berbasis AI.
                </p>
                <a href="{{ route('register') }}" class="inline-block bg-[#17233F] text-white px-7 py-3.5 rounded font-medium text-base hover:bg-[#2F6F62] transition-colors">
                    Mulai Belajar Sekarang
                </a>
            </div>

            <!-- Ilustrasi tumpukan kartu course -->
            <div class="relative h-72 hidden md:block course-stack">
                <div class="card" style="top:40px; left:20px; transform:rotate(-8deg); border-left:4px solid #F2B705;">
                    <p class="font-mono-label text-[10px] uppercase text-[#2F6F62] mb-2">Web Development</p>
                    <p class="font-display font-semibold text-sm">Belajar Laravel dari Nol</p>
                    <p class="text-xs text-[#8791A6] mt-2">12 lesson &middot; Rp0</p>
                </div>
                <div class="card" style="top:10px; left:140px; transform:rotate(4deg); border-left:4px solid #2F6F62;">
                    <p class="font-mono-label text-[10px] uppercase text-[#2F6F62] mb-2">Desain</p>
                    <p class="font-display font-semibold text-sm">UI/UX untuk Pemula</p>
                    <p class="text-xs text-[#8791A6] mt-2">8 lesson &middot; Rp150rb</p>
                </div>
                <div class="card" style="top:130px; left:70px; transform:rotate(-2deg); border-left:4px solid #17233F;">
                    <p class="font-mono-label text-[10px] uppercase text-[#2F6F62] mb-2">Data</p>
                    <p class="font-display font-semibold text-sm">Statistik Dasar</p>
                    <p class="text-xs text-[#8791A6] mt-2">15 lesson &middot; Rp0</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Fitur -->
    <section class="py-24" style="background:#FFFDF9;">
        <div class="max-w-6xl mx-auto px-6">
            <p class="font-mono-label text-xs uppercase text-[#2F6F62] text-center mb-3">Kenapa GLE Academy</p>
            <h2 class="font-display text-3xl font-semibold text-center mb-14">Semua yang kamu butuhkan untuk belajar</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="index-card rounded-lg p-7 pt-9">
                    <svg class="tape" viewBox="0 0 64 22" style="left:24px; transform:rotate(-4deg);"><rect width="64" height="22" fill="#F2B705"/></svg>
                    <p class="font-mono-label text-[11px] uppercase text-[#2F6F62] mb-3">Materi</p>
                    <h3 class="font-display font-semibold text-lg mb-2">Materi Lengkap</h3>
                    <p class="text-[#4B5566] text-sm leading-relaxed">Belajar dari teks, video YouTube, dan modul terstruktur dari berbagai instruktur berpengalaman.</p>
                </div>
                <div class="index-card rounded-lg p-7 pt-9">
                    <svg class="tape" viewBox="0 0 64 22" style="left:24px; transform:rotate(3deg);"><rect width="64" height="22" fill="#2F6F62"/></svg>
                    <p class="font-mono-label text-[11px] uppercase text-[#2F6F62] mb-3">AI-Powered</p>
                    <h3 class="font-display font-semibold text-lg mb-2">Quiz Bertenaga AI</h3>
                    <p class="text-[#4B5566] text-sm leading-relaxed">Soal quiz dibuat otomatis dari materi menggunakan AI, membantu instruktur menghemat waktu dan siswa menguji pemahaman.</p>
                </div>
                <div class="index-card rounded-lg p-7 pt-9">
                    <svg class="tape" viewBox="0 0 64 22" style="left:24px; transform:rotate(-3deg);"><rect width="64" height="22" fill="#17233F"/></svg>
                    <p class="font-mono-label text-[11px] uppercase text-[#2F6F62] mb-3">Progres</p>
                    <h3 class="font-display font-semibold text-lg mb-2">Progress Tracking</h3>
                    <p class="text-[#4B5566] text-sm leading-relaxed">Pantau perkembangan belajarmu secara real-time di setiap course yang kamu ikuti.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Cara Kerja -->
    <section class="py-24 paper-bg relative overflow-hidden">
        <svg class="absolute left-[4%] bottom-[8%] w-10 h-10 text-[#F2B705] hidden md:block" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 2l1.8 5.6H20l-4.6 3.5L17 17l-5-3.6L7 17l1.6-5.9L4 7.6h6.2z"/></svg>
        <div class="max-w-6xl mx-auto px-6 relative z-10">
            <h2 class="font-display text-3xl font-semibold text-center mb-14">Cara Kerjanya</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <div class="text-center">
                    <div class="w-11 h-11 bg-[#17233F] text-white rounded-full flex items-center justify-center mx-auto mb-4 font-display font-semibold">1</div>
                    <h3 class="font-semibold mb-2">Daftar Akun</h3>
                    <p class="text-[#4B5566] text-sm">Buat akun sebagai siswa dalam hitungan detik.</p>
                </div>
                <div class="text-center">
                    <div class="w-11 h-11 bg-[#17233F] text-white rounded-full flex items-center justify-center mx-auto mb-4 font-display font-semibold">2</div>
                    <h3 class="font-semibold mb-2">Pilih Course</h3>
                    <p class="text-[#4B5566] text-sm">Jelajahi katalog course dan enroll ke yang kamu minati.</p>
                </div>
                <div class="text-center">
                    <div class="w-11 h-11 bg-[#17233F] text-white rounded-full flex items-center justify-center mx-auto mb-4 font-display font-semibold">3</div>
                    <h3 class="font-semibold mb-2">Belajar & Uji Diri</h3>
                    <p class="text-[#4B5566] text-sm">Pelajari materi, kerjakan quiz, dan pantau progress belajarmu.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-20 text-center relative overflow-hidden" style="background:#17233F;">
        <svg class="absolute left-[12%] top-[20%] w-8 h-8 text-[#F2B705]/40 hidden md:block" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 2l1.8 5.6H20l-4.6 3.5L17 17l-5-3.6L7 17l1.6-5.9L4 7.6h6.2z"/></svg>
        <svg class="absolute right-[14%] bottom-[16%] w-8 h-8 text-[#2F6F62]/60 hidden md:block" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 2l1.8 5.6H20l-4.6 3.5L17 17l-5-3.6L7 17l1.6-5.9L4 7.6h6.2z"/></svg>
        <h2 class="font-display text-2xl font-semibold mb-5 text-white relative z-10">Siap Mulai Belajar?</h2>
        <a href="{{ route('register') }}" class="inline-block bg-[#F2B705] text-[#17233F] px-7 py-3.5 rounded font-semibold hover:bg-white transition-colors relative z-10">
            Daftar Gratis
        </a>
    </section>

    <!-- Footer -->
    <footer class="py-8 text-center text-sm font-mono-label" style="background:#111A2E; color:#8791A6;">
        &copy; {{ date('Y') }} GLE ACADEMY — DIBANGUN DENGAN LARAVEL {{ app()->version() }}
    </footer>

</body>
</html>