<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NomadEdu - Empowering Education</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        
        body, html {
            height: 100vh; width: 100vw; overflow: hidden; background-color: #000;
        }

        /* --- THE CROSSFADE VIDEO TRICK --- */
        .video-container {
            position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: 1;
        }
        
        .bg-video {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;
            transition: opacity 1.5s ease-in-out; /* Slow cinematic fade */
            will-change: opacity;
        }

        /* Video 1: Normal (Left side dark) */
        .video-login { opacity: 1; }
        
        /* Video 2: Mirrored (Right side dark) */
        .video-signup { opacity: 0; transform: scaleX(-1); }

        /* Overlays for readable text */
        .video-overlay-login, .video-overlay-signup {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%; 
            z-index: 2;
            transition: opacity 1.5s ease-in-out;
            will-change: opacity;
        }

        .video-overlay-login {
            background: linear-gradient(to right, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.3) 50%, transparent 100%);
            opacity: 1;
        }

        .video-overlay-signup {
            background: linear-gradient(to left, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.3) 50%, transparent 100%);
            opacity: 0; 
        }

        /* --- APPLE STYLE FADE & FLOAT ANIMATION --- */
        .auth-wrapper {
            position: relative; width: 100vw; height: 100vh;
            z-index: 10;
        }

        .auth-panel {
            position: absolute; top: 0; left: 0; width: 100vw; height: 100vh; 
            display: flex; align-items: center; 
            transition: opacity 1.2s cubic-bezier(0.25, 0.8, 0.25, 1), transform 1.2s cubic-bezier(0.25, 0.8, 0.25, 1);
            will-change: opacity, transform;
        }

        /* Default States */
        .login-panel { 
            justify-content: flex-start; padding-left: 8%; 
            opacity: 1; transform: translateX(0); pointer-events: auto; z-index: 10;
        }
        
        .signup-panel { 
            justify-content: flex-end; padding-right: 8%; 
            opacity: 0; transform: translateX(40px); pointer-events: none; z-index: 5;
        }

        /* --- PREMIUM CARDS --- */
        .auth-card {
            width: 100%; max-width: 440px; min-height: 560px; 
            display: flex; flex-direction: column; justify-content: center;
            background: rgba(20, 20, 20, 0.4);
            backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 24px; padding: 50px 45px; 
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.6);
        }

        .brand-title { font-size: 2.6rem; font-weight: 800; color: #fff; margin-bottom: 5px; letter-spacing: -1px; } 
        .subtitle { font-size: 1.05rem; color: #a1a1aa; margin-bottom: 35px; font-weight: 500; } 

        .form-group { margin-bottom: 22px; } 
        .form-group label { display: block; font-size: 0.9rem; font-weight: 600; color: #e4e4e7; margin-bottom: 8px; }
        
        .form-control {
            width: 100%; padding: 15px 16px; 
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px; font-size: 1.05rem; color: white;
            transition: all 0.3s ease; outline: none;
        }
        
        .form-control:focus {
            border-color: #fbbf24; background: rgba(0, 0, 0, 0.5);
            box-shadow: 0 0 0 4px rgba(251, 191, 36, 0.15);
        }

        .btn-primary {
            width: 100%; background-color: #fbbf24; color: #18181b;
            padding: 16px; border: none; border-radius: 12px; 
            font-size: 1.05rem; font-weight: 800; cursor: pointer;
            transition: transform 0.2s, background-color 0.2s; margin-top: 10px;
        }
        
        .btn-primary:hover { background-color: #f59e0b; transform: scale(1.02); }
        .btn-primary:active { transform: scale(0.98); }

        .error-box {
            background-color: rgba(220, 38, 38, 0.15); color: #fca5a5;
            border: 1px solid rgba(220, 38, 38, 0.3); padding: 12px;
            border-radius: 10px; margin-bottom: 20px; font-size: 0.875rem; font-weight: 500;
        }

        .toggle-link {
            text-align: center; margin-top: auto; padding-top: 25px; font-size: 0.95rem; color: #a1a1aa;
        }
        .toggle-link span { color: #fbbf24; font-weight: 700; cursor: pointer; transition: color 0.2s; }
        .toggle-link span:hover { color: #fcd34d; text-decoration: underline; }

        /* ==========================================
           STATE CLASSES (Toggled via JS)
           ========================================== */
        
        /* 1. Crossfade Videos (The Magic Slow Fade) */
        body.is-signup .video-login { opacity: 0; }
        body.is-signup .video-signup { opacity: 1; }
        
        /* 2. Crossfade Overlays */
        body.is-signup .video-overlay-login { opacity: 0; }
        body.is-signup .video-overlay-signup { opacity: 1; }

        /* 3. Float and Fade Forms */
        body.is-signup .login-panel {
            opacity: 0; transform: translateX(-40px); pointer-events: none; z-index: 5;
        }
        
        body.is-signup .signup-panel {
            opacity: 1; transform: translateX(0); pointer-events: auto; z-index: 10;
        }

    </style>
</head>
<body class="{{ (old('form') == 'register' || ($isSignup ?? false)) ? 'is-signup' : '' }}">

    <div class="video-container">
        <video autoplay loop muted playsinline class="bg-video video-login">
            <source src="{{ asset('videos/video.mp4') }}" type="video/mp4">
        </video>
        
        <video autoplay loop muted playsinline class="bg-video video-signup">
            <source src="{{ asset('videos/video.mp4') }}" type="video/mp4">
        </video>
    </div>
    
    <div class="video-overlay-login"></div>
    <div class="video-overlay-signup"></div>

    <div class="auth-wrapper" id="auth-wrapper">
        
        <div class="auth-panel login-panel">
            <div class="auth-card">
                <div>
                    <h1 class="brand-title">NomadEdu</h1>
                    <p class="subtitle">Welcome back to your journey.</p>

                    @if($errors->has('login'))
                        <div class="error-box">{{ $errors->first('login') }}</div>
                    @endif

                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Email address or Phone</label>
                            <input type="text" name="login" class="form-control" required placeholder="student@nomadedu.com" value="{{ old('login') }}">
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required placeholder="••••••••">
                        </div>
                        <button type="submit" class="btn-primary">Log In</button>
                    </form>
                </div>

                <div class="toggle-link">
                    New to NomadEdu? <span onclick="toggleSignup()">Start learning here</span>
                </div>
            </div>
        </div>

        <div class="auth-panel signup-panel">
            <div class="auth-card">
                <div>
                    <h1 class="brand-title">Join NomadEdu</h1>
                    <p class="subtitle">Education that travels with you.</p>

                    @if($errors->any() && !old('login'))
                        <div class="error-box">{{ $errors->first() }}</div>
                    @endif

                    <form action="{{ route('register') }}" method="POST">
                        @csrf
                        <input type="hidden" name="form" value="register">
                        
                        <div class="form-group" style="margin-bottom: 12px;">
                            <label>Role</label>
                            <select name="role" class="form-control" required>
                                <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Student</option>
                                <option value="teacher" {{ old('role') == 'teacher' ? 'selected' : '' }}>Teacher</option>
                                <option value="parent" {{ old('role') == 'parent' ? 'selected' : '' }}>Parent</option>
                            </select>
                        </div>
                        <div class="form-group" style="margin-bottom: 12px;">
                            <label>Full Name</label>
                            <input type="text" name="name" class="form-control" required placeholder="John Doe" value="{{ old('name') }}">
                        </div>
                        <div class="form-group" style="margin-bottom: 12px;">
                            <label>Email address or Phone</label>
                            <input type="text" name="login" class="form-control" required placeholder="student@nomadedu.com" value="{{ old('login') }}">
                        </div>
                        <div class="form-group" style="margin-bottom: 12px;">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required placeholder="••••••••">
                        </div>
                        <div class="form-group" style="margin-bottom: 12px;">
                            <label>Community / Tribe (Optional)</label>
                            <input type="text" name="community_tribe" class="form-control" placeholder="e.g. Gujjar, Bakarwal" value="{{ old('community_tribe') }}">
                        </div>
                        <div class="form-group">
                            <label>Camp Location (Optional)</label>
                            <input type="text" name="current_camp_location" class="form-control" value="{{ old('current_camp_location') }}">
                        </div>
                        <button type="submit" class="btn-primary">Create Account</button>
                    </form>
                </div>

                <div class="toggle-link">
                    Already have an account? <a href="{{ route('login') }}" style="color: #fbbf24; font-weight: 700; text-decoration: none;">Log in here</a>
                </div>
            </div>
        </div>

    </div>

    <script>
        function toggleSignup() {
            window.location.href = "{{ route('register') }}";
        }
    </script>
</body>
</html>