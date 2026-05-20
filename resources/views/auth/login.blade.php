<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentication | NomadEdu</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            /* ── COMPLETELY REMOVED THE BROWN/GOLD ── */
            --primary: #40E0D0; /* Luminous Cyan */
            --primary-hover: #2dd4bf; /* Deeper Cool Teal */
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        
        body, html { 
            height: 100vh; width: 100vw; overflow: hidden; background-color: #000; 
        }

        /* ── AMBIENT LIGHTING BACKGROUND ── */
        .ambient-video {
            position: fixed; top: -10%; left: -10%; width: 120%; height: 120%;
            object-fit: cover; filter: blur(70px); opacity: 0.6; 
            z-index: 0; pointer-events: none;
        }

        /* ── EDGE-TO-EDGE CONTAINER ── */
        .auth-container {
            position: relative; width: 100vw; height: 100vh; 
            z-index: 1; background: transparent; 
            overflow: hidden; display: flex;
        }

        /* ── FORM PANELS (With Alpha Mask Blending) ── */
        .form-panel {
            position: absolute; top: 0; height: 100%; width: 55%;
            display: flex; flex-direction: column; justify-content: center;
            transition: all 0.9s cubic-bezier(0.25, 1, 0.3, 1);
            backdrop-filter: blur(40px); -webkit-backdrop-filter: blur(40px);
        }

        .login-panel { 
            left: 0; opacity: 1; z-index: 3; transform: translateX(0); 
            padding: 0 15% 0 10%;
            /* Cool, dark neutral gradient */
            background: linear-gradient(to right, rgba(5,5,10,0.9) 0%, rgba(5,5,10,0.5) 60%, transparent 100%);
            -webkit-mask-image: linear-gradient(to right, black 0%, black 65%, transparent 100%);
            mask-image: linear-gradient(to right, black 0%, black 65%, transparent 100%);
        }

        .signup-panel { 
            right: 0; opacity: 0; z-index: 2; pointer-events: none; transform: translateX(40px); 
            padding: 0 10% 0 15%;
            background: linear-gradient(to left, rgba(5,5,10,0.9) 0%, rgba(5,5,10,0.5) 60%, transparent 100%);
            -webkit-mask-image: linear-gradient(to left, black 0%, black 65%, transparent 100%);
            mask-image: linear-gradient(to left, black 0%, black 65%, transparent 100%);
        }

        body.is-signup .login-panel { opacity: 0; pointer-events: none; transform: translateX(-40px); z-index: 2; }
        body.is-signup .signup-panel { opacity: 1; pointer-events: auto; transform: translateX(0); z-index: 3; }

        /* ── THE SLIDING VIDEO CARD (Edge-to-Edge Crossfade) ── */
        .video-card {
            position: absolute; top: 0; left: 45%;
            width: 55%; height: 100%;
            overflow: hidden; z-index: 10;
            transition: all 0.9s cubic-bezier(0.25, 1, 0.3, 1);
            
            -webkit-mask-image: linear-gradient(to right, transparent 0%, black 10%, black 90%, transparent 100%);
            mask-image: linear-gradient(to right, transparent 0%, black 10%, black 90%, transparent 100%);
            -webkit-mask-size: 200% 100%;
            mask-size: 200% 100%;
            -webkit-mask-position: 0% 0%; 
            mask-position: 0% 0%;
        }
        
        body.is-signup .video-card { 
            left: 0; 
            -webkit-mask-position: 100% 0%; 
            mask-position: 100% 0%;
        }

        .video-card video { width: 100%; height: 100%; object-fit: cover; display: block; }
        
        .video-overlay {
            position: absolute; inset: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.95) 0%, rgba(0,0,0,0.1) 50%, rgba(0,0,0,0.3) 100%);
        }

        .video-text-container {
            position: absolute; bottom: 80px; left: 15%; right: 15%; z-index: 12;
        }
        .v-text { position: absolute; bottom: 0; left: 0; width: 100%; transition: all 0.6s ease; opacity: 0; pointer-events: none; transform: translateY(20px); }
        .v-text h2 { color: #fff; font-size: 3.2rem; font-weight: 800; line-height: 1.1; margin-bottom: 15px; letter-spacing: -1px; text-shadow: 0 4px 20px rgba(0,0,0,0.8); }
        .v-text p { color: #cbd5e1; font-size: 1.15rem; line-height: 1.6; margin-bottom: 30px; text-shadow: 0 2px 10px rgba(0,0,0,0.8); }
        
        .login-v-text { opacity: 1; transform: translateY(0); pointer-events: auto; }
        body.is-signup .login-v-text { opacity: 0; transform: translateY(-20px); pointer-events: none; }
        body.is-signup .signup-v-text { opacity: 1; transform: translateY(0); pointer-events: auto; }

        /* ── TYPOGRAPHY & FORMS ── */
        .brand-logo { font-size: 1.8rem; font-weight: 800; color: #fff; margin-bottom: 40px; letter-spacing: -1px; text-decoration: none; display: inline-block; text-shadow: 0 0 10px rgba(255,255,255,0.2); }
        /* Cyan Logo Accents */
        .brand-logo span { color: var(--primary); text-shadow: 0 0 15px var(--primary); }
        
        h1 { font-size: 2.8rem; font-weight: 800; color: #fff; margin-bottom: 10px; letter-spacing: -1px; }
        .subtitle { color: #94a3b8; font-size: 1.1rem; margin-bottom: 40px; }

        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; color: #cbd5e1; font-size: 0.9rem; font-weight: 600; margin-bottom: 8px; }
        
        .form-control {
            width: 100%; background: rgba(0,0,0,0.4); border: 1px solid rgba(255,255,255,0.1);
            color: #fff; font-size: 1.05rem; padding: 18px 20px; border-radius: 16px;
            transition: all 0.3s ease; outline: none; appearance: none;
        }
        .form-control:focus {
            border-color: var(--primary); background: rgba(0,0,0,0.6);
            /* Cyan Focus Glow */
            box-shadow: 0 0 0 4px rgba(64, 224, 208, 0.15);
        }
        select.form-control option { background: #111; color: #fff; }

        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }

        /* ── BUTTONS ── */
        .btn {
            width: 100%; padding: 18px; border-radius: 16px; font-weight: 800; font-size: 1.1rem;
            cursor: pointer; border: none; transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1); display: block; text-align: center;
        }
        .btn-primary { background: #fff; color: #000; margin-top: 10px; }
        /* Cyan Hover Glow */
        .btn-primary:hover { background: var(--primary); transform: translateY(-2px); box-shadow: 0 15px 30px rgba(64, 224, 208, 0.3); }
        
        .btn-ghost { background: rgba(255,255,255,0.1); color: #fff; border: 1px solid rgba(255,255,255,0.2); backdrop-filter: blur(10px); }
        .btn-ghost:hover { background: #fff; color: #000; border-color: #fff; }

        .error-box { background: rgba(239, 68, 68, 0.15); color: #fca5a5; border: 1px solid rgba(239, 68, 68, 0.3); padding: 15px; border-radius: 12px; margin-bottom: 20px; font-size: 0.9rem; font-weight: 600; }

        @media (max-width: 1000px) {
            .auth-container { flex-direction: column; overflow-y: auto; }
            .video-card { display: none; }
            .form-panel { 
                width: 100%; padding: 40px 8%; background: rgba(5,5,10,0.8); 
                -webkit-mask-image: none; mask-image: none; 
                opacity: 1; transform: none; pointer-events: auto; 
            }
            .signup-panel { display: none; }
            body.is-signup .login-panel { display: none; }
            body.is-signup .signup-panel { display: flex; transform: none; }
        }
    </style>
</head>
<body class="{{ request()->routeIs('register') || old('form') === 'register' ? 'is-signup' : '' }}">

    <video autoplay loop muted playsinline class="ambient-video">
        <source src="{{ asset('videos/video.mp4') }}" type="video/mp4">
    </video>

    <div class="auth-container">
        
        <div class="form-panel login-panel">
            <a href="{{ url('/') }}" class="brand-logo">Nomad<span>Edu</span></a>
            <h1>Welcome back</h1>
            <p class="subtitle">Please enter your details to continue.</p>

            @if($errors->has('login') && old('form') !== 'register')
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
                <div style="text-align: right; margin-bottom: 25px;">
                    <a href="#" style="color: var(--primary); font-size: 0.95rem; font-weight: 600; text-decoration: none; text-shadow: 0 0 10px rgba(64, 224, 208, 0.2);">Forgot password?</a>
                </div>
                <button type="submit" class="btn btn-primary">Sign in</button>
            </form>
        </div>

        <div class="form-panel signup-panel">
            <a href="{{ url('/') }}" class="brand-logo">Nomad<span>Edu</span></a>
            <h1>Create Account</h1>
            <p class="subtitle">Start your offline learning journey.</p>

            @if($errors->any() && old('form') === 'register')
                <div class="error-box">{{ $errors->first() }}</div>
            @endif

            <form action="{{ route('register') }}" method="POST">
                @csrf
                <input type="hidden" name="form" value="register">
                
                <div class="grid-2">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="name" class="form-control" required placeholder="John Doe" value="{{ old('name') }}">
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <select name="role" class="form-control" required>
                            <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Student</option>
                            <option value="teacher" {{ old('role') == 'teacher' ? 'selected' : '' }}>Teacher</option>
                            <option value="parent" {{ old('role') == 'parent' ? 'selected' : '' }}>Parent</option>
                        </select>
                    </div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label>Email or Phone</label>
                        <input type="text" name="login" class="form-control" required placeholder="student@nomad.com" value="{{ old('login') }}">
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required placeholder="••••••••">
                    </div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label>Community (Optional)</label>
                        <input type="text" name="community_tribe" class="form-control" placeholder="e.g. Gujjar" value="{{ old('community_tribe') }}">
                    </div>
                    <div class="form-group">
                        <label>Camp Location (Optional)</label>
                        <input type="text" name="current_camp_location" class="form-control" placeholder="Current camp" value="{{ old('current_camp_location') }}">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Register</button>
            </form>
        </div>

        <div class="video-card">
            <video autoplay loop muted playsinline>
                <source src="{{ asset('videos/video.mp4') }}" type="video/mp4">
            </video>
            <div class="video-overlay"></div>
            
            <div class="video-text-container">
                <div class="v-text login-v-text">
                    <h2>Bring your<br>ideas to life.</h2>
                    <p>Sign up for free and enjoy access to our entire offline-ready curriculum. Education without borders.</p>
                    <button class="btn btn-ghost" onclick="toggleAuth('register')" style="width: auto; padding: 18px 40px; display: inline-block;">Create an account</button>
                </div>
                
                <div class="v-text signup-v-text">
                    <h2>Welcome back<br>to your journey.</h2>
                    <p>Already have an account? Sign in to sync your offline progress and continue learning.</p>
                    <button class="btn btn-ghost" onclick="toggleAuth('login')" style="width: auto; padding: 18px 40px; display: inline-block;">Sign in instead</button>
                </div>
            </div>
        </div>

    </div>

    <script>
        function toggleAuth(type) {
            if (type === 'register') {
                document.body.classList.add('is-signup');
                window.history.pushState({}, '', '/register');
            } else {
                document.body.classList.remove('is-signup');
                window.history.pushState({}, '', '/login');
            }
        }
    </script>
</body>
</html>