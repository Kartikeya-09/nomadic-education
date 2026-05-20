<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NomadEdu | Education Without Borders</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #fbbf24;
            --primary-hover: #f59e0b;
            --glass: rgba(20, 20, 25, 0.4);
            --glass-heavy: rgba(10, 10, 15, 0.75);
            --border: rgba(255, 255, 255, 0.12);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        
        html { scroll-behavior: smooth; background: #050508; } 
        body { background: transparent; color: #fff; overflow-x: hidden; font-size: 16px; }
        
        /* ── VIDEO BG ── */
        .video-bg-container { position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: -1; }
        .video-bg-container video { width: 100%; height: 100%; object-fit: cover; opacity: 0.85; }
        .video-overlay { position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(5,5,10,0.5) 0%, rgba(5,5,10,0.85) 60%, rgba(5,5,10,0.95) 100%); }
        
        /* ── NAV ── */
        nav { display: flex; justify-content: space-between; align-items: center; padding: 25px 8%; background: rgba(5, 5, 10, 0.3); backdrop-filter: blur(25px); -webkit-backdrop-filter: blur(25px); border-bottom: 1px solid var(--border); position: sticky; top: 0; z-index: 1000; transition: 0.4s; }
        .logo { font-size: 1.8rem; font-weight: 800; color: #fff; text-decoration: none; letter-spacing: -1px; text-shadow: 0 0 10px rgba(255,255,255,0.2); transition: 0.3s; }
        .logo span { color: var(--primary); text-shadow: 0 0 15px var(--primary); transition: 0.3s; }
        
        .nav-links { display: flex; gap: 30px; list-style: none; align-items: center; }
        .nav-links a:not(.btn) { position: relative; color: #cbd5e1; text-decoration: none; font-weight: 600; font-size: 0.95rem; transition: 0.3s; padding-bottom: 4px; }
        .nav-links a:not(.btn):hover { color: #fff; text-shadow: 0 0 10px rgba(255,255,255,0.3); }
        .nav-links a:not(.btn)::after { content: ''; position: absolute; width: 0; height: 2px; bottom: 0; left: 0; background-color: var(--primary); transition: width 0.3s cubic-bezier(0.2, 0.8, 0.2, 1); box-shadow: 0 0 8px var(--primary); }
        .nav-links a:not(.btn):hover::after { width: 100%; }
        
        .btn { padding: 14px 28px; border-radius: 50px; font-weight: 700; cursor: pointer; border: none; transition: all 0.4s cubic-bezier(0.2, 0.8, 0.2, 1); display: inline-flex; align-items: center; justify-content: center; text-decoration: none; font-size: 0.95rem; gap: 8px; }
        .btn-primary { background: #fff; color: #000; box-shadow: 0 0 15px rgba(255, 255, 255, 0.15); }
        .btn-primary:hover { background: var(--primary); transform: translateY(-3px) scale(1.02); box-shadow: 0 15px 30px rgba(251, 191, 36, 0.4), inset 0 0 10px rgba(255, 255, 255, 0.5); }
        .btn-outline { background: transparent; border: 1px solid var(--border); color: #fff; backdrop-filter: blur(5px); }
        .btn-outline:hover { border-color: var(--primary); color: var(--primary); box-shadow: 0 0 15px rgba(251, 191, 36, 0.2); }
        
        /* ── REVEALS ── */
        .reveal { opacity: 0; transform: translateY(60px) scale(0.94); filter: blur(15px); transition: all 1.2s cubic-bezier(0.2, 0.8, 0.2, 1); will-change: transform, opacity, filter; }
        .reveal.up { opacity: 1; transform: translateY(0) scale(1); filter: blur(0); }
        .rd1 { transition-delay: 0.1s; } .rd2 { transition-delay: 0.2s; } .rd3 { transition-delay: 0.3s; }
        
        section { padding: 150px 8%; position: relative; z-index: 2; background: transparent; }
        .section-header { text-align: center; margin-bottom: 80px; }
        .section-header h2 { font-size: clamp(3rem, 6vw, 5rem); font-weight: 800; margin-bottom: 25px; letter-spacing: -2px; line-height: 1.1; }
        .section-header h2 span { background: -webkit-linear-gradient(0deg, #fbbf24, #f59e0b); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .section-header p { color: #94a3b8; font-size: 1.25rem; max-width: 650px; margin: 0 auto; line-height: 1.7; }

        .hero-buffer { height: 75vh; display: flex; align-items: center; justify-content: center; text-align: center; padding: 0 8%; position: relative; z-index: 2; }
        .hero-buffer h1 { font-size: clamp(4rem, 10vw, 8rem); font-weight: 800; line-height: 0.95; letter-spacing: -3px; text-shadow: 0 10px 40px rgba(0,0,0,0.8); }
        .hero-buffer p { font-size: clamp(1.2rem, 2vw, 1.5rem); color: #cbd5e1; margin-top: 30px; max-width: 700px; margin-inline: auto; font-weight: 500; text-shadow: 0 4px 20px rgba(0,0,0,0.8); }

        /* ── PARALLAX JOURNEY (GLOBE) ── */
        #journey { padding: 0; }
        .journey-pin-wrap { height: 600vh; position: relative; }
        .journey-stage { position: sticky; top: 0; height: 100vh; overflow: hidden; display: flex; align-items: center; justify-content: center; }
        #particleCanvas { position: absolute; inset: 0; width: 100%; height: 100%; z-index: 1; pointer-events: none; }
        .j-text-layer { position: absolute; inset: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; pointer-events: none; z-index: 20; }
        .j-headline { font-size: clamp(3.5rem, 8vw, 8rem); font-weight: 800; line-height: 0.95; text-align: center; letter-spacing: -0.04em; color: #fff; text-shadow: 0 10px 30px rgba(0,0,0,0.8); will-change: transform, opacity; }
        .j-headline .gold { color: var(--primary); }
        .j-sub { font-size: 1.4rem; color: rgba(255,255,255,0.9); text-align: center; margin-top: 25px; max-width: 600px; line-height: 1.5; font-weight: 500; text-shadow: 0 4px 20px rgba(0,0,0,0.9); }
        .depth-layer { position: absolute; inset: 0; background: radial-gradient(ellipse 100% 100% at 50% 50%, transparent 0%, rgba(5,5,12,0.8) 100%); z-index: 5; pointer-events: none; }
        #globeWrap { position: absolute; width: min(800px, 95vmin); height: min(800px, 95vmin); left: 50%; top: 50%; transform: translate(-50%, -50%); z-index: 10; will-change: transform; }
        #globeWrap svg { width: 100%; height: 100%; overflow: visible; }

        /* ── STATS ── */
        #stats { padding-top: 0; padding-bottom: 100px; }
        .stats-wrapper { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 40px; border-top: 1px solid var(--border); padding-top: 100px; }
        .stat-card { text-align: center; background: rgba(255,255,255,0.02); padding: 40px; border-radius: 30px; border: 1px solid var(--border); backdrop-filter: blur(15px); }
        .stat-card h3 { font-size: 4.5rem; color: #fff; font-weight: 800; line-height: 1; letter-spacing: -2px; margin-bottom: 15px; text-shadow: 0 4px 20px rgba(0,0,0,0.5); }
        .stat-card p { color: #cbd5e1; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; font-size: 0.85rem; }

        /* ── BENTO GRID ── */
        #bento { background: rgba(5, 5, 10, 0.5); border-top: 1px solid var(--border); border-bottom: 1px solid var(--border); backdrop-filter: blur(10px); }
        .bento-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 24px; max-width: 1400px; margin: 0 auto; }
        .bento-item { background: rgba(20, 20, 25, 0.6); border: 1px solid rgba(255,255,255,0.1); border-radius: 40px; padding: 50px; position: relative; overflow: hidden; display: flex; flex-direction: column; justify-content: flex-end; transition: 0.5s; backdrop-filter: blur(30px); }
        .bento-item:hover { border-color: rgba(251,191,36,0.4); transform: scale(0.98); }
        .bento-item h3 { font-size: 2.2rem; font-weight: 800; margin-bottom: 15px; z-index: 2; letter-spacing: -1px; }
        .bento-item p { color: #cbd5e1; font-size: 1.1rem; line-height: 1.6; z-index: 2; }
        .bento-large { grid-column: span 2; grid-row: span 2; }
        .bento-wide { grid-column: span 2; }
        
        /* ── COURSES WITH TABS ── */
        .course-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 40px; }
        .course-card { background: var(--glass-heavy); border-radius: 32px; border: 1px solid var(--border); overflow: hidden; transition: 0.5s cubic-bezier(0.16, 1, 0.3, 1); cursor: pointer; position: relative; backdrop-filter: blur(25px); box-shadow: 0 20px 40px rgba(0,0,0,0.3); }
        .course-card:hover { transform: translateY(-12px) scale(1.02); border-color: rgba(251,191,36,0.4); box-shadow: 0 30px 60px rgba(0,0,0,0.8); }
        .course-badge { position: absolute; top: 25px; right: 25px; background: #fff; color: #000; padding: 8px 18px; border-radius: 50px; font-weight: 800; font-size: 0.75rem; }
        .course-content { padding: 45px; }
        .course-content h4 { font-size: 1.8rem; margin-bottom: 15px; font-weight: 800; letter-spacing: -1px; }
        .course-content p { color: #cbd5e1; font-size: 1.05rem; line-height: 1.6; margin-bottom: 35px; }
        .course-footer { display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border); padding-top: 25px; color: var(--primary); font-weight: 700; }
        
        .tabs-container { display: flex; justify-content: center; gap: 15px; margin-bottom: 60px; flex-wrap: wrap; }
        .tab-btn { background: rgba(255,255,255,0.05); border: 1px solid var(--border); padding: 14px 30px; border-radius: 50px; color: #cbd5e1; font-size: 1.1rem; font-weight: 600; cursor: pointer; transition: 0.4s; backdrop-filter: blur(10px); }
        .tab-btn.active, .tab-btn:hover { background: #fff; color: #000; border-color: #fff; }
        .tab-content { display: none; animation: fadeUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .tab-content.active { display: block; }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

        /* ── FACULTY ── */
        .faculty-wrapper { width: 100%; overflow: hidden; padding: 40px 0; mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent); -webkit-mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent); }
        .faculty-track { display: flex; width: max-content; animation: scrollLoop 35s linear infinite; }
        .faculty-track:hover { animation-play-state: paused; }
        @keyframes scrollLoop { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }
        .faculty-card { min-width: 350px; margin: 0 20px; background: var(--glass-heavy); border: 1px solid var(--border); border-radius: 40px; padding: 50px 40px; text-align: center; transition: 0.5s; cursor: pointer; backdrop-filter: blur(20px); box-shadow: 0 10px 30px rgba(0,0,0,0.3); }
        .faculty-card:hover { border-color: rgba(251,191,36,0.4); transform: translateY(-15px); background: rgba(30,30,40,0.8); }
        .faculty-img { width: 150px; height: 150px; border-radius: 50%; object-fit: cover; margin: 0 auto 25px; filter: grayscale(100%); transition: 0.5s; border: 2px solid transparent; }
        .faculty-card:hover .faculty-img { filter: grayscale(0%); border-color: var(--primary); box-shadow: 0 10px 30px rgba(251,191,36,0.3); }

        /* ── PORTAL ARCHITECTURE / SUPPORT SECTION ── */
        .app-section { display: grid; grid-template-columns: 1fr 1fr; gap: 80px; align-items: center; background: var(--glass-heavy); border-radius: 50px; padding: 80px; border: 1px solid rgba(255,255,255,0.15); backdrop-filter: blur(30px); box-shadow: 0 30px 80px rgba(0,0,0,0.5); }
        
        .nl-input, .nl-select, .nl-textarea { width: 100%; padding: 16px; border-radius: 14px; border: 1px solid var(--border); background: rgba(0,0,0,0.5); color: white; outline: none; transition: 0.3s; font-size: 1rem; margin-bottom: 15px; }
        .nl-input:focus, .nl-select:focus, .nl-textarea:focus { border-color: var(--primary); box-shadow: 0 0 0 4px rgba(251,191,36,0.1); }
        .nl-select { cursor: pointer; appearance: none; }
        .nl-textarea { height: 120px; resize: vertical; }

        /* ── MODALS ── */
        .modal-backdrop { position: fixed; inset: 0; background: rgba(0,0,0,0.6); backdrop-filter: blur(25px); z-index: 3000; display: none; align-items: center; justify-content: center; opacity: 0; transition: opacity 0.4s ease; }
        .modal-backdrop.show { opacity: 1; }
        .modal-content { background: rgba(15, 15, 20, 0.9); border: 1px solid rgba(255,255,255,0.1); border-radius: 40px; padding: 60px; width: 90%; max-width: 800px; transform: scale(0.9) translateY(30px); transition: all 0.5s cubic-bezier(0.2, 0.8, 0.2, 1); box-shadow: 0 30px 80px rgba(0,0,0,0.8); position: relative; }
        .modal-backdrop.show .modal-content { transform: scale(1) translateY(0); }
        .close-modal { position: absolute; top: 30px; right: 40px; font-size: 2.5rem; cursor: pointer; color: #94a3b8; transition: 0.3s; line-height: 1; }
        .close-modal:hover { color: var(--primary); }

        /* ── TOASTS ── */
        .toast-container { position: fixed; bottom: 40px; left: 40px; z-index: 9999; display: flex; flex-direction: column; gap: 15px; pointer-events: none; }
        .toast { background: rgba(15,15,20,0.95); border: 1px solid rgba(255,255,255,0.15); backdrop-filter: blur(25px); padding: 20px 30px; border-radius: 20px; display: flex; align-items: center; gap: 20px; transform: translateX(-120%); opacity: 0; transition: all 0.7s cubic-bezier(0.2, 0.8, 0.2, 1); box-shadow: 0 20px 50px rgba(0,0,0,0.8); }
        .toast.show { transform: translateX(0); opacity: 1; }
        .toast-icon { width: 12px; height: 12px; background: var(--primary); border-radius: 50%; box-shadow: 0 0 15px var(--primary); }

        /* ── FOOTER ── */
        footer { padding: 100px 8% 40px; border-top: 1px solid rgba(255,255,255,0.15); background: rgba(5,5,10,0.9); backdrop-filter: blur(40px); }
        .footer-grid { display: grid; grid-template-columns: 2fr 1fr 1fr 2fr; gap: 40px; margin-bottom: 40px; }
        .footer-col h4 { font-size: 1.3rem; margin-bottom: 25px; color: #fff; font-weight: 800; }
        .footer-col p { color: #cbd5e1; line-height: 1.6; margin-bottom: 20px; }
        .footer-links { list-style: none; }
        .footer-links li { margin-bottom: 15px; }
        .footer-links a { color: #cbd5e1; text-decoration: none; transition: 0.3s; font-weight: 500; }
        .footer-links a:hover { color: var(--primary); padding-left: 5px; }

        @media (max-width: 1000px) {
            .bento-large, .bento-wide { grid-column: span 1; }
            .app-section, .footer-grid { flex-direction: column; display: flex; padding: 40px 0; }
        }
    </style>
</head>
<body>

<div class="video-bg-container">
    <video autoplay muted loop playsinline>
        <source src="{{ asset('videos/video1.mp4') }}" type="video/mp4">
    </video>
    <div class="video-overlay"></div>
</div>

<nav>
    <a href="{{ url('/') }}" class="logo">Nomad<span>Edu</span></a>
    <ul class="nav-links">
        <li><a href="#scholars">Scholars</a></li>
        <li><a href="#courses">Courses</a></li>
        <li><a href="#teachers">Faculty</a></li>
        <li><a href="#support">Support</a></li>
        
        @auth
            @php $user = auth()->user(); @endphp
            @if($user->hasRole('admin'))
                <li><a href="{{ route('admin.dashboard') }}" class="btn btn-primary">Admin Dash</a></li>
            @elseif($user->hasRole('teacher'))
                <li><a href="{{ route('teacher.dashboard') }}" class="btn btn-primary">Teacher Dash</a></li>
            @elseif($user->hasRole('student'))
                <li><a href="{{ route('student.dashboard') }}" class="btn btn-primary">My Dashboard</a></li>
            @elseif($user->hasRole('parent'))
                <li><a href="{{ route('parent.dashboard') }}" class="btn btn-primary">Parent Dash</a></li>
            @endif
            <li>
                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-outline" style="padding: 10px 20px;">Logout</button>
                </form>
            </li>
        @endauth
        
        @guest
            <li><a href="{{ route('login') }}" class="btn btn-primary">Login</a></li>
        @endguest
    </ul>
</nav>

<div class="hero-buffer reveal">
    <div>
        <h1>The World is<br>Your Classroom.</h1>
        <p>A modern, accessible education platform engineered for remote, migrant, and continuous learners.</p>
    </div>
</div>

<section id="journey">
    <div class="journey-pin-wrap">
        <div class="journey-stage">
            <canvas id="particleCanvas"></canvas>
            <div id="globeWrap">
                <svg id="globeSVG" viewBox="0 0 700 700" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <radialGradient id="oceanGrad" cx="38%" cy="32%" r="70%">
                            <stop offset="0%" stop-color="#0f3460"/><stop offset="50%" stop-color="#061428"/><stop offset="100%" stop-color="#020810"/>
                        </radialGradient>
                        <clipPath id="gc"><circle cx="350" cy="350" r="240"/></clipPath>
                    </defs>
                    <circle id="oceanSphere" cx="350" cy="350" r="240" fill="url(#oceanGrad)" opacity="0"/>
                    <g id="cNA" class="cont" clip-path="url(#gc)" style="transform:translate(-420px,-400px) scale(0.3) rotate(-45deg);opacity:0;">
                        <path d="M258 222 L280 210 L310 215 L338 228 L358 248 L368 275 L358 305 L340 322 L314 318 L288 298 L268 272 L255 245Z" fill="#1a7a4a"/>
                    </g>
                    <g id="cSA" class="cont" clip-path="url(#gc)" style="transform:translate(420px,320px) scale(0.3) rotate(25deg);opacity:0;">
                        <path d="M316 328 L346 320 L368 340 L364 382 L350 418 L328 430 L306 412 L300 376 L308 350Z" fill="#145c38"/>
                    </g>
                    <g id="cEU" class="cont" clip-path="url(#gc)" style="transform:translate(180px,-420px) scale(0.3) rotate(20deg);opacity:0;">
                        <path d="M352 224 L380 218 L408 230 L416 254 L400 270 L374 266 L352 250Z" fill="#22a05a"/>
                    </g>
                    <g id="cAF" class="cont" clip-path="url(#gc)" style="transform:translate(360px,420px) scale(0.3) rotate(-20deg);opacity:0;">
                        <path d="M366 262 L396 254 L422 272 L430 314 L420 358 L400 382 L372 372 L352 342 L348 296Z" fill="#1d8c4e"/>
                    </g>
                    <g id="cAS" class="cont" clip-path="url(#gc)" style="transform:translate(-420px,340px) scale(0.3) rotate(15deg);opacity:0;">
                        <path d="M398 230 L460 222 L498 240 L510 268 L496 294 L458 308 L416 302 L396 276Z" fill="#28b865"/>
                    </g>
                </svg>
            </div>
            <div class="depth-layer"></div>
            <div class="j-text-layer" id="jTextLayer">
                <div class="j-headline" id="jHeadline">Education<br/><span class="gold">Without</span><br/>Borders</div>
                <div class="j-sub" id="jSub">Knowledge is the only force that transcends every wall, every mountain, every ocean.</div>
            </div>
        </div>
    </div>
</section>

<section id="scholars" style="background: rgba(5,5,10,0.5);">
    <div class="section-header reveal">
        <h2>Community <span>Scholars</span></h2>
        <p>Celebrating the dedication and excellence of top performers from Gujjar, Bakarwal, and nomadic tribes.</p>
    </div>
    <div class="bento-grid" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));">
        <div class="bento-item reveal">
            <h3 style="font-size:2rem; color:var(--primary);">Zoya Bakarwal</h3>
            <p style="color:#fff; font-weight:700; margin-bottom: 12px; font-size: 1.1rem;">Grade 12 • 98% Completion</p>
            <p style="color:#cbd5e1; font-size:1.05rem; font-style: italic;">"Studying organic chemistry while migrating across the Pir Panjal range. NomadEdu works flawlessly offline."</p>
        </div>
        <div class="bento-item reveal rd1">
            <h3 style="font-size:2rem; color:var(--primary);">Imran Gujjar</h3>
            <p style="color:#fff; font-weight:700; margin-bottom: 12px; font-size: 1.1rem;">Grade 10 • 100% Quiz Score</p>
            <p style="color:#cbd5e1; font-size:1.05rem; font-style: italic;">"The vernacular audio lessons helped me grasp advanced physics concepts perfectly in my own language."</p>
        </div>
        <div class="bento-item reveal rd2">
            <h3 style="font-size:2rem; color:var(--primary);">Ali Mohammad</h3>
            <p style="color:#fff; font-weight:700; margin-bottom: 12px; font-size: 1.1rem;">Vocational • Top Performer</p>
            <p style="color:#cbd5e1; font-size:1.05rem; font-style: italic;">"I used the Livestock Management course to implement new, sustainable practices for our entire herd."</p>
        </div>
    </div>
</section>

<section id="courses">
    <div class="section-header reveal">
        <h2>Schooling <span>Excellence</span></h2>
        <p>Comprehensive curriculum covering standard academics and practical vocational skills.</p>
    </div>
    
    <div class="tabs-container reveal rd1">
        <button class="tab-btn active" onclick="switchTab('stem', this)">STEM Core</button>
        <button class="tab-btn" onclick="switchTab('humanities', this)">Humanities</button>
        <button class="tab-btn" onclick="switchTab('vocational', this)">Vocational</button>
    </div>

    <div id="tab-stem" class="tab-content active course-grid">
        <div class="course-card" onclick="openCourseModal('Advanced Mathematics', 'Master Calculus and Algebra with step-by-step nomadic context.', 'Dr. Harpreet Singh', 'https://youtu.be/PMc0l9IrEig?si=pMxXeQA9ldE1G_4z')">
            <div class="course-badge">Grade 12</div>
            <div class="course-content"><h4>Advanced Mathematics</h4><p>Calculus, Trigonometry, and Statistics tailored for entrance exams.</p><div class="course-footer"><span>Free Access</span><span>View details →</span></div></div>
        </div>
        <div class="course-card" onclick="openCourseModal('Organic Chemistry', 'Understanding molecular structures and chemical reactions.', 'Amandeep Kaur', 'https://youtu.be/soIba7r34ZM?si=iFJV3avemb1MlwM8')">
            <div class="course-badge">Grade 11</div>
            <div class="course-content"><h4>Organic Chemistry</h4><p>Focus on Carbon compounds, hydrocarbons, and chemical bonds.</p><div class="course-footer"><span>Free Access</span><span>View details →</span></div></div>
        </div>
        <div class="course-card" onclick="openCourseModal('Quantum Physics', 'Forces of nature, Motion, and Electromagnetic waves.', 'Jaswinder Kaur', 'https://youtu.be/2JYSVsXV3Ac?si=QxEAHzTv0W9ULwA1')">
            <div class="course-badge">Grade 12</div>
            <div class="course-content"><h4>Quantum Physics</h4><p>Mechanics, Optics, and Modern Physics foundations.</p><div class="course-footer"><span>Free Access</span><span>View details →</span></div></div>
        </div>
    </div>

    </section>

<section id="teachers">
    <div class="section-header reveal">
        <h2>Distinguished <span>Faculty</span></h2>
        <p>Global experts volunteering to bring world-class education to your digital doorstep.</p>
    </div>
    <div class="faculty-wrapper reveal">
        <div class="faculty-track">
            <div class="faculty-card" onclick="openFacultyModal('Dr. Harpreet Singh', 'Mathematics', '15 Years', '5.0', 'Uses visual storytelling and local dialects to simplify complex calculus.', 'https://i.pravatar.cc/300?img=11')"><img src="https://i.pravatar.cc/300?img=11" class="faculty-img"><h4 style="font-size:1.6rem;">Dr. Harpreet Singh</h4><p style="color:#cbd5e1;margin-top:8px;">Mathematics</p></div>
            <div class="faculty-card" onclick="openFacultyModal('Jaswinder Kaur', 'Physics', '12 Years', '4.9', 'Focuses on real-world mechanics and practical physics applications.', 'https://i.pravatar.cc/300?img=5')"><img src="https://i.pravatar.cc/300?img=5" class="faculty-img"><h4 style="font-size:1.6rem;">Jaswinder Kaur</h4><p style="color:#cbd5e1;margin-top:8px;">Physics</p></div>
            <div class="faculty-card" onclick="openFacultyModal('Amandeep Kaur', 'Chemistry', '10 Years', '4.8', 'Specializes in interactive organic chemistry and sustainable science.', 'https://i.pravatar.cc/300?img=20')"><img src="https://i.pravatar.cc/300?img=20" class="faculty-img"><h4 style="font-size:1.6rem;">Amandeep Kaur</h4><p style="color:#cbd5e1;margin-top:8px;">Chemistry</p></div>
            <div class="faculty-card" onclick="openFacultyModal('Prof. Gurmeet Singh', 'Literature', '20 Years', '5.0', 'Deeply passionate about preserving oral traditions and regional history.', 'https://i.pravatar.cc/300?img=12')"><img src="https://i.pravatar.cc/300?img=12" class="faculty-img"><h4 style="font-size:1.6rem;">Prof. Gurmeet Singh</h4><p style="color:#cbd5e1;margin-top:8px;">Literature</p></div>
            <div class="faculty-card" onclick="openFacultyModal('Navjot Singh', 'Technology', '8 Years', '4.9', 'Bridging the digital divide with basic coding and hardware skills.', 'https://i.pravatar.cc/300?img=33')"><img src="https://i.pravatar.cc/300?img=33" class="faculty-img"><h4 style="font-size:1.6rem;">Navjot Singh</h4><p style="color:#cbd5e1;margin-top:8px;">Technology</p></div>
            
            <div class="faculty-card" onclick="openFacultyModal('Dr. Harpreet Singh', 'Mathematics', '15 Years', '5.0', 'Uses visual storytelling and local dialects to simplify complex calculus.', 'https://i.pravatar.cc/300?img=11')"><img src="https://i.pravatar.cc/300?img=11" class="faculty-img"><h4 style="font-size:1.6rem;">Dr. Harpreet Singh</h4><p style="color:#cbd5e1;margin-top:8px;">Mathematics</p></div>
            <div class="faculty-card" onclick="openFacultyModal('Jaswinder Kaur', 'Physics', '12 Years', '4.9', 'Focuses on real-world mechanics and practical physics applications.', 'https://i.pravatar.cc/300?img=5')"><img src="https://i.pravatar.cc/300?img=5" class="faculty-img"><h4 style="font-size:1.6rem;">Jaswinder Kaur</h4><p style="color:#cbd5e1;margin-top:8px;">Physics</p></div>
            <div class="faculty-card" onclick="openFacultyModal('Amandeep Kaur', 'Chemistry', '10 Years', '4.8', 'Specializes in interactive organic chemistry and sustainable science.', 'https://i.pravatar.cc/300?img=20')"><img src="https://i.pravatar.cc/300?img=20" class="faculty-img"><h4 style="font-size:1.6rem;">Amandeep Kaur</h4><p style="color:#cbd5e1;margin-top:8px;">Chemistry</p></div>
            <div class="faculty-card" onclick="openFacultyModal('Prof. Gurmeet Singh', 'Literature', '20 Years', '5.0', 'Deeply passionate about preserving oral traditions and regional history.', 'https://i.pravatar.cc/300?img=12')"><img src="https://i.pravatar.cc/300?img=12" class="faculty-img"><h4 style="font-size:1.6rem;">Prof. Gurmeet Singh</h4><p style="color:#cbd5e1;margin-top:8px;">Literature</p></div>
            <div class="faculty-card" onclick="openFacultyModal('Navjot Singh', 'Technology', '8 Years', '4.9', 'Bridging the digital divide with basic coding and hardware skills.', 'https://i.pravatar.cc/300?img=33')"><img src="https://i.pravatar.cc/300?img=33" class="faculty-img"><h4 style="font-size:1.6rem;">Navjot Singh</h4><p style="color:#cbd5e1;margin-top:8px;">Technology</p></div>
        </div>
    </div>
</section>

<section id="support" style="background: rgba(20,20,25,0.7); padding-bottom: 80px;">
    <div class="app-section reveal" style="display: grid; grid-template-columns: 1fr 1fr; gap: 80px; align-items: center; border-radius: 50px; padding: 80px; border: 1px solid rgba(255,255,255,0.15); backdrop-filter: blur(30px);">
        <div>
            <h2 style="font-size: 3.5rem; font-weight: 800; margin-bottom: 25px; line-height: 1.1; letter-spacing: -1px;">Need <br><span style="color:var(--primary)">Help?</span></h2>
            <p style="color: #cbd5e1; font-size: 1.2rem; line-height: 1.7; margin-bottom: 40px;">Having trouble logging in? Can't create an account? Drop your query here without logging in, and our technical support team will assist you within 24 hours.</p>
        </div>
        <form onsubmit="event.preventDefault(); popCustomToast('Query submitted! Our team will contact you shortly.'); this.reset();" style="background: rgba(0,0,0,0.4); padding: 40px; border-radius: 30px; border: 1px solid var(--border);">
            <input type="text" class="nl-input" placeholder="Your Name" required>
            <input type="text" class="nl-input" placeholder="Email or Phone Number" required>
            <select class="nl-select" required>
                <option value="" disabled selected>What do you need help with?</option>
                <option value="login">I cannot log in</option>
                <option value="signup">Problem with registration</option>
                <option value="courses">Course access issue</option>
                <option value="other">General technical query</option>
            </select>
            <textarea class="nl-textarea" placeholder="Describe your issue in detail..." required></textarea>
            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 16px; font-size: 1.1rem;">Submit Support Query</button>
        </form>
    </div>
</section>

<footer>
    <div class="footer-grid">
        <div class="footer-col">
            <h4>NomadEdu</h4>
            <p>Empowering remote and migrant communities with an accessible, high-performance web education platform.</p>
        </div>
        <div class="footer-col">
            <h4>Quick Links</h4>
            <ul class="footer-links">
                <li><a href="#scholars">Our Scholars</a></li>
                <li><a href="#courses">Course Catalog</a></li>
                <li><a href="#teachers">Our Faculty</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h4>Get Involved</h4>
            <ul class="footer-links">
                <li><a href="{{ route('register') }}">Join as a Student</a></li>
                <li><a href="#support">Technical Support</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h4>Newsletter</h4>
            <p>Subscribe to get updates on new courses and platform features.</p>
            <div class="nl-form" onsubmit="event.preventDefault(); popCustomToast('Successfully subscribed!');">
                <input type="email" class="nl-input" placeholder="Enter Email" required>
                <button type="submit" class="nl-btn">Subscribe</button>
            </div>
        </div>
    </div>
    <div style="text-align: center; border-top: 1px solid var(--border); padding-top: 20px; color: #94a3b8; font-size: 0.9rem;">
        &copy; {{ date('Y') }} NomadEdu. Education without borders. All rights reserved.
    </div>
</footer>

<div id="courseModal" class="modal-backdrop">
    <div class="modal-content">
        <span class="close-modal" onclick="closeModal('courseModal')">&times;</span>
        <h2 id="m-title" style="font-size:2.8rem; color:var(--primary); margin-bottom:15px; letter-spacing: -1px;">Course Title</h2>
        <p id="m-desc" style="color:#cbd5e1; font-size:1.15rem; line-height:1.6; margin-bottom: 30px;"></p>
        <div style="background: rgba(255,255,255,0.05); padding: 25px; border-radius: 20px; margin-bottom: 30px; border: 1px solid var(--border);">
            <p style="color:#fff; margin-bottom:10px; font-size: 1.1rem;"><strong>Instructor:</strong> <span id="m-instructor" style="color:#cbd5e1;"></span></p>
            <p style="color:#fff; font-size: 1.1rem;"><strong>Enrollment:</strong> <span style="color:var(--primary); font-weight: 700;">Free Access</span></p>
        </div>
        <div style="display: flex; gap: 15px;">
            <button class="btn btn-primary" style="flex: 1; padding: 16px;" onclick="playCourseVideo()">Watch Intro Video</button>
            <button class="btn btn-outline" style="flex: 1; padding: 16px;" onclick="window.location.href='{{ route('register') }}'">Enroll Now</button>
        </div>
    </div>
</div>

<div id="videoModal" class="modal-backdrop">
    <div class="modal-content" style="max-width: 1000px; padding: 20px; background: #000;">
        <span class="close-modal" onclick="closeModal('videoModal')" style="top: -40px; right: 0; color: #fff;">&times;</span>
        <div style="position:relative; padding-top:56.25%; border-radius: 20px; overflow: hidden; background: #111;">
            <iframe id="videoFrame" src="" frameborder="0" allow="autoplay; encrypted-media; picture-in-picture" allowfullscreen style="position:absolute; inset:0; width:100%; height:100%;"></iframe>
        </div>
    </div>
</div>

<div id="facultyModal" class="modal-backdrop">
    <div class="modal-content" style="max-width: 650px;">
        <span class="close-modal" onclick="closeModal('facultyModal')">&times;</span>
        <div style="display:flex; gap:25px; align-items:center; margin-bottom: 30px;">
            <img id="f-img" src="" style="width:120px; height:120px; border-radius:50%; border:3px solid var(--primary); object-fit: cover;">
            <div>
                <h2 id="f-name" style="font-size:2.4rem; color:var(--primary); letter-spacing:-1px; margin-bottom: 5px;">Name</h2>
                <p id="f-subject" style="color:#cbd5e1; font-size:1.2rem; font-weight: 600; text-transform: uppercase; letter-spacing: 2px;"></p>
            </div>
        </div>
        <div style="background: rgba(255,255,255,0.05); padding: 30px; border-radius: 20px; border: 1px solid var(--border);">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div>
                    <p style="color:#94a3b8; font-size: 0.9rem; text-transform: uppercase;">Experience</p>
                    <p id="f-exp" style="color:#fff; font-size: 1.4rem; font-weight: 700;"></p>
                </div>
                <div>
                    <p style="color:#94a3b8; font-size: 0.9rem; text-transform: uppercase;">Student Rating</p>
                    <p style="color:var(--primary); font-size: 1.4rem; font-weight: 700;"><span id="f-rating"></span> ⭐⭐⭐⭐⭐</p>
                </div>
            </div>
            <div style="border-top: 1px solid var(--border); padding-top: 20px;">
                <p style="color:#94a3b8; font-size: 0.9rem; text-transform: uppercase; margin-bottom: 8px;">Teaching Style</p>
                <p id="f-style" style="color:#fff; font-size: 1.1rem; line-height: 1.6; font-style: italic;"></p>
            </div>
        </div>
    </div>
</div>

<div id="toastBox" class="toast-container"></div>

<script>
    /* ── Apple-Level Reveals ── */
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('up');
                revealObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1, rootMargin: "0px 0px -50px 0px" });
    document.querySelectorAll('.reveal').forEach(el => revealObserver.observe(el));

    /* ── Stat Count Up ── */
    let countDone = false;
    const countObs = new IntersectionObserver((entries) => {
        if (entries[0].isIntersecting && !countDone) {
            countDone = true;
            document.querySelectorAll('.count-up').forEach(el => {
                const target = +el.dataset.target;
                let s = null;
                function tick(ts) {
                    if (!s) s = ts;
                    const p = Math.min((ts - s) / 2500, 1);
                    const e = 1 - Math.pow(1 - p, 4); 
                    el.textContent = Math.floor(e * target);
                    if (p < 1) requestAnimationFrame(tick);
                    else el.textContent = target;
                }
                requestAnimationFrame(tick);
            });
        }
    }, { threshold: 0.5 });
    const sw = document.querySelector('.stats-wrapper');
    if (sw) countObs.observe(sw);

    /* ── Tab Switcher ── */
    function switchTab(tabId, btn) {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        btn.classList.add('active');
        document.getElementById('tab-' + tabId).classList.add('active');
    }

    /* ── MODALS (Apple Smoothness) ── */
    let currentVideoUrl = '';

    function openModal(id) {
        const m = document.getElementById(id);
        m.style.display = 'flex';
        void m.offsetWidth; // Force reflow
        m.classList.add('show');
        document.body.style.overflow = 'hidden'; 
    }

    function closeModal(id) {
        const m = document.getElementById(id);
        m.classList.remove('show');
        document.body.style.overflow = '';
        if(id === 'videoModal') document.getElementById('videoFrame').src = ''; 
        setTimeout(() => m.style.display = 'none', 400);
    }

    function openCourseModal(title, desc, instructor, vidUrl) {
        document.getElementById('m-title').innerText = title;
        document.getElementById('m-desc').innerText = desc;
        document.getElementById('m-instructor').innerText = instructor;
        currentVideoUrl = vidUrl;
        openModal('courseModal');
    }

    function playCourseVideo() {
        if(!currentVideoUrl) return;
        closeModal('courseModal');
        setTimeout(() => {
            let embedUrl = currentVideoUrl;
            if(embedUrl.includes('youtu.be/')) embedUrl = embedUrl.replace('youtu.be/', 'www.youtube.com/embed/') + '?autoplay=1';
            else if(embedUrl.includes('watch?v=')) embedUrl = embedUrl.replace('watch?v=', 'embed/') + '?autoplay=1';
            
            document.getElementById('videoFrame').src = embedUrl;
            openModal('videoModal');
        }, 400); 
    }

    function openFacultyModal(name, subject, exp, rating, style, img) {
        document.getElementById('f-name').innerText = name;
        document.getElementById('f-subject').innerText = subject;
        document.getElementById('f-exp').innerText = exp;
        document.getElementById('f-rating').innerText = rating;
        document.getElementById('f-style').innerText = `"${style}"`;
        document.getElementById('f-img').src = img;
        openModal('facultyModal');
    }

    window.onclick = function(e) {
        if (e.target.classList.contains('modal-backdrop')) {
            closeModal(e.target.id);
        }
    }

    /* ── TOASTS (Live System) ── */
    function popCustomToast(msg, isError = false) {
        const toast = document.createElement('div');
        toast.className = 'toast';
        const color = isError ? '#ef4444' : 'var(--primary)';
        toast.innerHTML = `<div class="toast-icon" style="background:${color}; box-shadow: 0 0 15px ${color};"></div><div style="color:#e2e8f0; font-weight:600;">${msg}</div>`;
        document.getElementById('toastBox').appendChild(toast);
        setTimeout(() => toast.classList.add('show'), 50);
        setTimeout(() => { toast.classList.remove('show'); setTimeout(() => toast.remove(), 700); }, 4000);
    }

    const toastNames = ['Zoya', 'Ali', 'Imran', 'Amandeep', 'Navjot'];
    const toastCourses = ['Quantum Physics', 'Tech Skills', 'Organic Chemistry', 'Regional Literature'];
    setInterval(() => {
        if(document.hidden) return; 
        const name = toastNames[Math.floor(Math.random() * toastNames.length)];
        const course = toastCourses[Math.floor(Math.random() * toastCourses.length)];
        popCustomToast(`${name} just enrolled in ${course}`);
    }, 15000);

    /* ── ZERO-LAG PARALLAX ENGINE ── */
    (function(){
        const pinWrap = document.querySelector('.journey-pin-wrap');
        if(!pinWrap) return;
        
        const pCanvas = document.getElementById('particleCanvas');
        const pCtx = pCanvas.getContext('2d', { alpha: false }); 
        let pW, pH, particles=[];
        
        function resizeP(){
            pW = pCanvas.width = pCanvas.offsetWidth;
            pH = pCanvas.height = pCanvas.offsetHeight;
        }
        resizeP();
        window.addEventListener('resize', resizeP);

        for(let i=0; i<120; i++){
            particles.push({
                x: Math.random()*2000-500, y: Math.random()*1200-100,
                z: Math.random(), r: Math.random()*1.5+0.5,
                alpha: Math.random()*0.6+0.2, speed: Math.random()*0.1+0.05,
                angle: Math.random()*Math.PI*2, twinkle: Math.random()*Math.PI*2
            });
        }

        function drawParticles(progress, time){
            pCtx.fillStyle = "#000000"; 
            pCtx.fillRect(0,0,pW,pH);
            
            const cx = pW/2, cy = pH/2;
            
            particles.forEach(p=>{
                p.twinkle += 0.02;
                const alpha = p.alpha * (0.6+0.4*Math.sin(p.twinkle)) * (0.3+progress*0.7);
                const driftX = Math.sin(p.angle + time*p.speed) * 10 * p.z;
                
                const convT = Math.min(1, progress*1.8);
                const sx = (cx + (p.x - cx)*0.12*p.z * (1-convT*0.6) + driftX) | 0;
                const sy = (cy + (p.y - cy)*0.12*p.z * (1-convT*0.4)) | 0;
                const size = (p.r * (0.5 + p.z*0.5) * (0.8+progress*0.4)) | 0;

                pCtx.fillStyle = p.z > 0.7 ? `rgba(251,191,36,${alpha})` : `rgba(200,220,255,${alpha})`;
                pCtx.fillRect(sx, sy, size || 1, size || 1);
            });
        }

        const continents = ['cNA','cSA','cEU','cAF','cAS'];
        const startTransforms = {
            cNA: {tx:-420, ty:-400, s:0.3, r:-45}, cSA: {tx: 420, ty: 320, s:0.3, r: 25},
            cEU: {tx: 180, ty:-420, s:0.3, r: 20}, cAF: {tx: 360, ty: 420, s:0.3, r:-20},
            cAS: {tx:-420, ty: 340, s:0.3, r: 15}
        };
        const finalTransforms = {
            cNA: {tx:-65, ty:-65, s:1, r:0}, cSA: {tx:-28, ty: 60, s:1, r:0},
            cEU: {tx: 20, ty:-84, s:1, r:0}, cAF: {tx: 16, ty: 16, s:1, r:0},
            cAS: {tx: 85, ty:-56, s:1, r:0}
        };

        function easeInOut3(t){ return t<0.5?4*t*t*t:1-Math.pow(-2*t+2,3)/2; }
        function easeOut3(t){ return 1-Math.pow(1-t,3); }
        function lerp(a,b,t){ return a+(b-a)*t; }

        const globeWrap = document.getElementById('globeWrap');
        const oceanSphere = document.getElementById('oceanSphere');
        const jHeadline = document.getElementById('jHeadline');
        const jSub = document.getElementById('jSub');
        
        const headlines = [
            { h:'Education<br/><span class="gold">Without</span><br/>Borders', s:'Knowledge is the only force that transcends every wall.' },
            { h:'Continents<br/><span class="gold">Scattered.</span>',s:'Our learners span six continents.' },
            { h:'One World.<br/><span class="gold">One</span><br/>Classroom.', s:'Watch the world assemble — student by student.' },
            { h:'15,000+<br/><span class="gold">Nomadic</span><br/>Scholars.', s:'A platform built to travel as far as curiosity can reach.' },
            { h:'Your Journey<br/><span class="gold">Starts</span><br/>Now.', s:'Education that moves with you.' }
        ];

        let currentPhase = -1, scrollProgress=0, globeRotY=0;
        
        function applyParallax(progress, time){
            const phase = progress<0.15?0 : progress<0.3?1 : progress<0.55?2 : progress<0.8?3 : 4;
            if(phase !== currentPhase){
                currentPhase = phase;
                jHeadline.style.opacity='0'; jSub.style.opacity='0';
                setTimeout(()=>{
                    jHeadline.innerHTML=headlines[phase].h; jSub.textContent=headlines[phase].s;
                    jHeadline.style.opacity='1'; jSub.style.opacity='1';
                }, 300);
            }

            const globeT = easeInOut3(Math.min(1, progress/0.45));
            globeWrap.style.transform=`translate(-50%,-50%) translateY(${lerp(150, 0, globeT)}px) scale(${lerp(0.18, 1.02, globeT)}) rotate(${globeRotY}deg)`;
            oceanSphere.style.opacity = easeOut3(Math.min(1, Math.max(0, (progress-0.1)/0.32)));

            const assembleT = easeOut3(Math.min(1, Math.max(0, (progress-0.1)/0.44)));
            continents.forEach(id=>{
                const el=document.getElementById(id), s=startTransforms[id], f=finalTransforms[id];
                el.style.transform=`translate(${lerp(s.tx,f.tx,assembleT)}px,${lerp(s.ty,f.ty,assembleT)}px) scale(${lerp(s.s,f.s,assembleT)}) rotate(${lerp(s.r,f.r,assembleT)}deg)`;
                el.style.opacity = Math.min(1, assembleT*1.6);
            });

            jHeadline.style.transform=`translateY(${lerp(-30, 0, easeOut3(Math.min(1, progress/0.3)))}px)`;
            drawParticles(progress, time);
        }

        let lastT=performance.now();
        function loop(time){
            requestAnimationFrame(loop);
            globeRotY += (time-lastT)/1000 * 3.5; lastT=time;
            
            const rect = pinWrap.getBoundingClientRect();
            if(rect.bottom > 0 && rect.top < window.innerHeight){
                applyParallax(scrollProgress, time*0.001);
            }
        }

        window.addEventListener('scroll', ()=>{
            scrollProgress = Math.max(0, Math.min(1, -pinWrap.getBoundingClientRect().top / (pinWrap.offsetHeight-window.innerHeight)));
        }, {passive:true});
        
        applyParallax(0,0);
        requestAnimationFrame(loop);
    })();
</script>
</body>
</html>