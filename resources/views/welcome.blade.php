<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NomadEdu | Transcending Boundaries</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #fbbf24;
            --primary-hover: #f59e0b;
            --glass: rgba(255, 255, 255, 0.08);
            --border: rgba(255, 255, 255, 0.12);
            --bg-dark: #050505;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; scroll-behavior: smooth; }
        body { background: var(--bg-dark); color: #fff; overflow-x: hidden; }

        /* --- RESTORED DARK CINEMATIC VIDEO BACKGROUND --- */
        .video-bg-container { position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: -1; }
        .video-bg-container video { width: 100%; height: 100%; object-fit: cover; }
        .video-overlay {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(rgba(0,0,0,0.6) 0%, rgba(0,0,0,0.8) 100%);
        }

        /* --- NAVIGATION --- */
        nav {
            display: flex; justify-content: space-between; align-items: center;
            padding: 20px 8%; background: rgba(0, 0, 0, 0.5); backdrop-filter: blur(25px);
            border-bottom: 1px solid var(--border); position: sticky; top: 0; z-index: 1000;
        }
        .logo { font-size: 1.8rem; font-weight: 800; color: var(--primary); text-decoration: none; }
        .nav-links { display: flex; gap: 30px; list-style: none; align-items: center; }
        .nav-links a { color: #fff; text-decoration: none; font-weight: 600; font-size: 0.95rem; opacity: 0.8; transition: 0.3s; }
        .nav-links a:hover { opacity: 1; color: var(--primary); }

        /* --- SECTIONS --- */
        section { padding: 80px 8%; }
        .section-header { text-align: center; margin-bottom: 60px; }
        .section-header h2 { font-size: 3rem; font-weight: 800; margin-bottom: 15px; }
        .section-header h2 span { color: var(--primary); }
        .section-header p { color: #a1a1aa; font-size: 1.1rem; max-width: 700px; margin: 0 auto; }

        /* --- STATISTICS SECTION --- */
        .stats-wrapper { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-top: -40px; }
        .stat-card { background: var(--glass); padding: 40px; border-radius: 24px; text-align: center; border: 1px solid var(--border); backdrop-filter: blur(10px); }
        .stat-card h3 { font-size: 3.5rem; color: var(--primary); margin-bottom: 5px; }
        .stat-card p { color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; }

        /* --- COURSE CARDS --- */
        .course-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; }
        .course-card {
            background: var(--glass); border-radius: 28px; border: 1px solid var(--border);
            overflow: hidden; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            cursor: pointer; position: relative; backdrop-filter: blur(10px);
        }
        .course-card:hover { transform: translateY(-12px); border-color: var(--primary); background: rgba(255,255,255,0.12); }
        .course-badge { position: absolute; top: 20px; right: 20px; background: var(--primary); color: #000; padding: 5px 15px; border-radius: 50px; font-weight: 800; font-size: 0.75rem; }
        .course-content { padding: 30px; }
        .course-content h4 { font-size: 1.4rem; margin-bottom: 12px; }
        .course-content p { color: #94a3b8; font-size: 0.95rem; line-height: 1.6; margin-bottom: 25px; }
        .course-footer { display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border); padding-top: 20px; }
        .course-price { font-size: 1.3rem; font-weight: 800; color: var(--primary); }

        /* --- INFINITE FACULTY LOOP --- */
        .faculty-wrapper {
            width: 100%; overflow: hidden; padding: 40px 0; position: relative;
            mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
            -webkit-mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
        }
        .faculty-track { display: flex; width: max-content; animation: scrollLoop 20s linear infinite; }
        .faculty-track:hover { animation-play-state: paused; }
        @keyframes scrollLoop { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }
        .faculty-card {
            min-width: 300px; margin: 0 15px; background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 25px; padding: 30px; text-align: center; transition: 0.3s; cursor: pointer;
        }
        .faculty-card:hover { border-color: var(--primary); transform: translateY(-10px); }
        .faculty-img { width: 120px; height: 120px; border-radius: 50%; object-fit: cover; margin: 0 auto 15px; border: 3px solid var(--primary); }
        .faculty-card h4 { font-size: 1.3rem; color: #fff; }
        .faculty-card p { color: var(--primary); font-size: 0.9rem; text-transform: uppercase; }

        /* --- TESTIMONIALS --- */
        .testimonial-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; }
        .testi-card { background: var(--glass); padding: 40px; border-radius: 24px; border: 1px solid var(--border); backdrop-filter: blur(10px); transition: 0.3s; }
        .testi-card:hover { border-color: var(--primary); transform: translateY(-5px); }
        .testi-text { font-size: 1.05rem; font-style: italic; color: #cbd5e1; margin-bottom: 30px; line-height: 1.6; }
        .testi-author { display: flex; align-items: center; gap: 15px; }
        .testi-author img { width: 50px; height: 50px; border-radius: 50%; border: 2px solid var(--primary); }
        .testi-author h5 { color: #fff; font-size: 1.1rem; }
        .testi-author span { color: var(--primary); font-size: 0.85rem; }

        /* --- CONTACT FORM --- */
        .contact-container {
            display: grid; grid-template-columns: 1fr 1fr; gap: 50px;
            background: var(--glass); padding: 50px; border-radius: 30px;
            border: 1px solid var(--border); backdrop-filter: blur(15px);
        }
        .contact-info h3 { font-size: 2rem; margin-bottom: 20px; color: var(--primary); }
        .contact-info p { color: #94a3b8; line-height: 1.7; margin-bottom: 30px; }
        .form-group { margin-bottom: 20px; }
        .form-control {
            width: 100%; padding: 15px; background: rgba(0,0,0,0.4);
            border: 1px solid var(--border); border-radius: 12px; color: white;
            outline: none; transition: 0.3s; font-family: inherit;
        }
        .form-control:focus { border-color: var(--primary); background: rgba(0,0,0,0.6); }
        textarea.form-control { resize: vertical; height: 120px; }

        /* --- FOOTER --- */
        footer { background: #020202; padding: 60px 8% 30px; border-top: 1px solid var(--border); margin-top: 50px; }
        .footer-grid { display: grid; grid-template-columns: 2fr 1fr 1fr 2fr; gap: 40px; margin-bottom: 40px; }
        .footer-col h4 { font-size: 1.3rem; margin-bottom: 20px; color: var(--primary); }
        .footer-col p { color: #94a3b8; line-height: 1.6; margin-bottom: 20px; }
        .footer-links { list-style: none; }
        .footer-links li { margin-bottom: 12px; }
        .footer-links a { color: #94a3b8; text-decoration: none; transition: 0.3s; }
        .footer-links a:hover { color: var(--primary); padding-left: 5px; }
        
        .social-icons { display: flex; gap: 15px; }
        .social-icons a {
            display: flex; align-items: center; justify-content: center;
            width: 45px; height: 45px; border-radius: 50%; background: var(--glass);
            color: white; transition: 0.3s; text-decoration: none; border: 1px solid var(--border);
        }
        .social-icons a:hover { background: var(--primary); color: #000; transform: translateY(-3px); }
        .social-icons svg { width: 20px; height: 20px; fill: currentColor; }
        
        .nl-form { display: flex; gap: 10px; margin-top: 15px; }
        .nl-input { flex: 1; padding: 12px; border-radius: 8px; border: 1px solid var(--border); background: #111; color: white; outline: none; }
        .nl-btn { padding: 12px 20px; border-radius: 8px; background: var(--primary); color: #000; font-weight: bold; border: none; cursor: pointer; }

        /* --- MODAL CSS --- */
        .modal { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); backdrop-filter: blur(10px); display: none; justify-content: center; align-items: center; z-index: 2000; }
        .modal-content { background: #111; width: 90%; max-width: 800px; padding: 50px; border-radius: 32px; border: 1px solid var(--border); position: relative; }
        .close-modal { position: absolute; top: 30px; right: 30px; font-size: 2rem; cursor: pointer; color: var(--primary); transition: 0.3s; }
        .close-modal:hover { color: #fff; }
        .modal-body h2 { font-size: 2.5rem; margin-bottom: 20px; color: var(--primary); }
        .modal-info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-top: 30px; }
        .info-item h5 { color: #94a3b8; text-transform: uppercase; font-size: 0.8rem; margin-bottom: 8px; }
        .faculty-modal-grid { display: grid; grid-template-columns: 1fr 2fr; gap: 30px; margin-top: 20px; }
        .fac-detail-box { background: rgba(255,255,255,0.03); padding: 20px; border-radius: 15px; border: 1px solid var(--border); }
        .fac-detail-box h6 { color: var(--primary); text-transform: uppercase; margin-bottom: 10px; font-size: 0.8rem; }

        .btn { padding: 14px 28px; border-radius: 12px; font-weight: 700; cursor: pointer; border: none; transition: 0.3s; display: inline-block; }
        .btn-primary { background: var(--primary); color: #000; }
        .btn-primary:hover { background: var(--primary-hover); transform: scale(1.05); }

        @media (max-width: 768px) {
            .contact-container, .footer-grid { grid-template-columns: 1fr; }
            .faculty-modal-grid { grid-template-columns: 1fr; text-align: center; }
        }
    </style>
</head>
<body>

    <div class="video-bg-container">
        <video autoplay muted loop playsinline><source src="{{ asset('videos/video1.mp4') }}" type="video/mp4"></video>
        <div class="video-overlay"></div>
    </div>

    <nav>
        <a href="#" class="logo">NomadEdu</a>
        <ul class="nav-links">
            <li><a href="#stats">Impact</a></li>
            <li><a href="#courses">Courses</a></li>
            <li><a href="#teachers">Faculty</a></li>
            <li><a href="#reviews">Reviews</a></li>
            <li><a href="#feedback">Feedback</a></li>
            <li><a href="#contact">Contact</a></li>
            
            @auth
            <li><a href="{{ route('dashboard') }}" style="color: var(--primary); font-weight: 800; background: rgba(251,191,36,0.1); padding: 8px 15px; border-radius: 8px;">My Dashboard</a></li>
            <li>
                <form action="{{ route('logout') }}" method="POST">@csrf
                    <button type="submit" class="btn btn-primary" style="padding: 10px 20px;">Logout</button>
                </form>
            </li>
            @endauth
            @guest
            <li><a href="{{ route('login') }}" class="btn btn-primary" style="padding: 10px 20px; color: #000;">Login</a></li>
            @endguest
        </ul>
    </nav>

    <section id="stats">
        <div class="stats-wrapper">
            <div class="stat-card"><h3>15K+</h3><p>Total Students</p></div>
            <div class="stat-card"><h3>120+</h3><p>Teachers</p></div>
            <div class="stat-card"><h3>500+</h3><p>Lessons</p></div>
            <div class="stat-card"><h3>30K+</h3><p>Downloads</p></div>
        </div>
    </section>

    <section id="courses">
        <div class="section-header">
            <h2>Schooling <span>Excellence</span></h2>
            <p>Comprehensive curriculum optimized for remote and nomadic learners.</p>
        </div>
        <div class="course-grid">
            <div class="course-card" onclick="openCourse('Advanced Mathematics', 'Master Calculus and Algebra with step-by-step nomadic context.', 'Prof. K. Sharma', '12 Weeks', 'Access to global university entrance exams.', 'Free')">
                <div class="course-badge">Grade 12</div>
                <div class="course-content"><h4>Advanced Mathematics</h4><p>In-depth exploration of Calculus, Trigonometry, and Statistics.</p><div class="course-footer"><span class="course-price">Free</span><span style="color:var(--primary)">View Syllabus →</span></div></div>
            </div>
            <div class="course-card" onclick="openCourse('Organic Chemistry', 'Understanding molecular structures and chemical reactions.', 'Dr. Anjali Rao', '10 Weeks', 'Perfect for medical and engineering aspirants.', 'Free')">
                <div class="course-badge">Grade 11</div>
                <div class="course-content"><h4>Organic Chemistry</h4><p>Focus on Carbon compounds, hydrocarbons, and basics.</p><div class="course-footer"><span class="course-price">Free</span><span style="color:var(--primary)">View Syllabus →</span></div></div>
            </div>
            <div class="course-card" onclick="openCourse('Quantum Physics', 'Forces of nature, Motion, and Electromagnetic waves.', 'Sir Isaac J.', '14 Weeks', 'Build a strong foundation for physical sciences.', 'Free')">
                <div class="course-badge">Grade 12</div>
                <div class="course-content"><h4>Quantum Physics</h4><p>Comprehensive study of Mechanics, Optics, and Modern Physics.</p><div class="course-footer"><span class="course-price">Free</span><span style="color:var(--primary)">View Syllabus →</span></div></div>
            </div>
            <div class="course-card" onclick="openCourse('Human Biology', 'Anatomy, Physiology and Genetic Engineering basics.', 'Dr. S. Malhotra', '12 Weeks', 'Direct path to Medical entrance preparation.', 'Free')">
                <div class="course-badge">Grade 12</div>
                <div class="course-content"><h4>Human Biology</h4><p>Study of human systems, genetics, and biotechnology.</p><div class="course-footer"><span class="course-price">Free</span><span style="color:var(--primary)">View Syllabus →</span></div></div>
            </div>

            <div class="course-card" onclick="openCourse('Standard Mathematics', 'Focusing on Trigonometry and Linear Algebra.', 'Prof. Sharma', '10 Weeks', 'Foundation for Architecture & Engineering.', 'Free')">
                <div class="course-badge">Grade 10</div>
                <div class="course-content"><h4>Standard Mathematics</h4><p>Mastering numbers and logic for modern challenges.</p><div class="course-footer"><span class="course-price">Free</span><span style="color:var(--primary)">View Syllabus →</span></div></div>
            </div>
            <div class="course-card" onclick="openCourse('Home Science & Nutrition', 'Learn community health and sustainable living.', 'Dr. Neha', '8 Weeks', 'Empowering community health leaders.', 'Free')">
                <div class="course-badge">Lifestyle</div>
                <div class="course-content"><h4>Home Science</h4><p>Sustainable living and nutritional excellence.</p><div class="course-footer"><span class="course-price">Free</span><span style="color:var(--primary)">View Syllabus →</span></div></div>
            </div>
            <div class="course-card" onclick="openCourse('Social Sciences', 'Understanding history, geography, and civic duties.', 'Prof. Gupta', '10 Weeks', 'Preparation for civil services and law.', 'Free')">
                <div class="course-badge">Core</div>
                <div class="course-content"><h4>Social Sciences</h4><p>A deep dive into the structures of human society.</p><div class="course-footer"><span class="course-price">Free</span><span style="color:var(--primary)">View Syllabus →</span></div></div>
            </div>
            <div class="course-card" onclick="openCourse('Digital Skill Development', 'Computer basics, coding fundamentals, and online safety.', 'Michael Chang', '6 Weeks', 'Essential for the modern digital economy.', 'Free')">
                <div class="course-badge">Skills</div>
                <div class="course-content"><h4>Tech Skills</h4><p>Bridging the digital divide for nomadic communities.</p><div class="course-footer"><span class="course-price">Free</span><span style="color:var(--primary)">View Syllabus →</span></div></div>
            </div>

            <div class="course-card" onclick="openCourse('Regional Literature', 'Preserving oral traditions and written literature.', 'Prof. James Wilson', '8 Weeks', 'Cultural preservation and arts careers.', 'Free')">
                <div class="course-badge">Arts</div>
                <div class="course-content"><h4>Regional Literature</h4><p>Studying local dialects, history, and storytelling.</p><div class="course-footer"><span class="course-price">Free</span><span style="color:var(--primary)">View Syllabus →</span></div></div>
            </div>
            <div class="course-card" onclick="openCourse('Livestock Management', 'Veterinary basics and modern herding techniques.', 'Dr. Singh', '8 Weeks', 'Improving nomadic economic stability.', 'Free')">
                <div class="course-badge">Vocational</div>
                <div class="course-content"><h4>Livestock Mgmt</h4><p>Scientific approaches to traditional herding.</p><div class="course-footer"><span class="course-price">Free</span><span style="color:var(--primary)">View Syllabus →</span></div></div>
            </div>
            <div class="course-card" onclick="openCourse('Basic Economics', 'Financial literacy, savings, and market basics.', 'Anita Desai', '6 Weeks', 'Personal financial independence.', 'Free')">
                <div class="course-badge">Core</div>
                <div class="course-content"><h4>Economics</h4><p>Understanding money management and trade.</p><div class="course-footer"><span class="course-price">Free</span><span style="color:var(--primary)">View Syllabus →</span></div></div>
            </div>
            <div class="course-card" onclick="openCourse('Environmental Science', 'Climate change, conservation, and geography.', 'Dr. R. Kumar', '8 Weeks', 'Environmental awareness and geography basics.', 'Free')">
                <div class="course-badge">Science</div>
                <div class="course-content"><h4>Environment</h4><p>Protecting the landscapes you travel through.</p><div class="course-footer"><span class="course-price">Free</span><span style="color:var(--primary)">View Syllabus →</span></div></div>
            </div>
        </div>
    </section>

    <section id="teachers">
        <div class="section-header">
            <h2>Our Distinguished <span>Faculty</span></h2>
            <p>Global experts bringing world-class education to your doorstep.</p>
        </div>
        <div class="faculty-wrapper">
            <div class="faculty-track">
                <div class="faculty-card" onclick="openFaculty('Dr. Robert Fox', 'Senior Math Expert', '15+ Years', 'Stanford', '12k+', 'https://i.pravatar.cc/300?img=11')">
                    <img src="https://i.pravatar.cc/300?img=11" class="faculty-img">
                    <h4>Dr. Robert Fox</h4><p>Mathematics</p>
                </div>
                <div class="faculty-card" onclick="openFaculty('Sarah Jenkins', 'Physics Specialist', '10 Years', 'MIT', '8k+', 'https://i.pravatar.cc/300?img=5')">
                    <img src="https://i.pravatar.cc/300?img=5" class="faculty-img">
                    <h4>Sarah Jenkins</h4><p>Physics</p>
                </div>
                <div class="faculty-card" onclick="openFaculty('Dr. Anjali Rao', 'Chemistry Dean', '12 Years', 'IIT', '15k+', 'https://i.pravatar.cc/300?img=20')">
                    <img src="https://i.pravatar.cc/300?img=20" class="faculty-img">
                    <h4>Dr. Anjali Rao</h4><p>Chemistry</p>
                </div>
                <div class="faculty-card" onclick="openFaculty('Prof. James Wilson', 'Literature Head', '20+ Years', 'Oxford', '20k+', 'https://i.pravatar.cc/300?img=12')">
                    <img src="https://i.pravatar.cc/300?img=12" class="faculty-img">
                    <h4>Prof. Wilson</h4><p>Literature</p>
                </div>
                <div class="faculty-card" onclick="openFaculty('Michael Chang', 'Tech Specialist', '8 Years', 'Stanford', '5k+', 'https://i.pravatar.cc/300?img=3')">
                    <img src="https://i.pravatar.cc/300?img=3" class="faculty-img">
                    <h4>Michael Chang</h4><p>Technology</p>
                </div>

                <div class="faculty-card" onclick="openFaculty('Dr. Robert Fox', 'Senior Math Expert', '15+ Years', 'Stanford', '12k+', 'https://i.pravatar.cc/300?img=11')">
                    <img src="https://i.pravatar.cc/300?img=11" class="faculty-img">
                    <h4>Dr. Robert Fox</h4><p>Mathematics</p>
                </div>
                <div class="faculty-card" onclick="openFaculty('Sarah Jenkins', 'Physics Specialist', '10 Years', 'MIT', '8k+', 'https://i.pravatar.cc/300?img=5')">
                    <img src="https://i.pravatar.cc/300?img=5" class="faculty-img">
                    <h4>Sarah Jenkins</h4><p>Physics</p>
                </div>
                <div class="faculty-card" onclick="openFaculty('Dr. Anjali Rao', 'Chemistry Dean', '12 Years', 'IIT', '15k+', 'https://i.pravatar.cc/300?img=20')">
                    <img src="https://i.pravatar.cc/300?img=20" class="faculty-img">
                    <h4>Dr. Anjali Rao</h4><p>Chemistry</p>
                </div>
                <div class="faculty-card" onclick="openFaculty('Prof. James Wilson', 'Literature Head', '20+ Years', 'Oxford', '20k+', 'https://i.pravatar.cc/300?img=12')">
                    <img src="https://i.pravatar.cc/300?img=12" class="faculty-img">
                    <h4>Prof. Wilson</h4><p>Literature</p>
                </div>
                <div class="faculty-card" onclick="openFaculty('Michael Chang', 'Tech Specialist', '8 Years', 'Stanford', '5k+', 'https://i.pravatar.cc/300?img=3')">
                    <img src="https://i.pravatar.cc/300?img=3" class="faculty-img">
                    <h4>Michael Chang</h4><p>Technology</p>
                </div>
            </div>
        </div>
    </section>

    <section id="reviews">
        <div class="section-header">
            <h2>Student <span>Success Stories</span></h2>
            <p>Real experiences from our nomadic scholars learning from the peaks to the plains.</p>
        </div>
        <div class="testimonial-grid">
            <div class="testi-card">
                <p class="testi-text">"NomadEdu completely changed how I learn. Even when my family migrates with our herds, I can download modules and study offline in the mountains."</p>
                <div class="testi-author">
                    <img src="https://i.pravatar.cc/150?img=33" alt="Student">
                    <div><h5>Jaspreet Singh</h5><span>Grade 12 Student</span></div>
                </div>
            </div>
            <div class="testi-card">
                <p class="testi-text">"The audio lessons in our regional language make it so easy to understand complex science topics. I listen to them while working."</p>
                <div class="testi-author">
                    <img src="https://i.pravatar.cc/150?img=47" alt="Student">
                    <div><h5>Aisha Bibi</h5><span>Grade 10 Student</span></div>
                </div>
            </div>
            <div class="testi-card">
                <p class="testi-text">"Thanks to the low-bandwidth video player, I don't need a strong 5G connection to attend my math classes. It works perfectly on 3G."</p>
                <div class="testi-author">
                    <img src="https://i.pravatar.cc/150?img=11" alt="Student">
                    <div><h5>Rahul Chauhan</h5><span>Tech Skills Learner</span></div>
                </div>
            </div>
        </div>
    </section>

    <section id="feedback">
        <div class="section-header">
            <h2>Share Your <span>Feedback</span></h2>
            <p>Help us improve NomadEdu with your experience, ideas, and suggestions.</p>
        </div>
        <div class="contact-container">
            <div class="contact-info">
                <h3>We listen to every voice.</h3>
                <p>Your feedback helps us refine lessons, improve performance, and build the best learning experience for nomadic students.</p>
                <p>✅ Tell us what you loved</p>
                <p>✅ Point out what can be better</p>
                <p>✅ Suggest new courses or features</p>
            </div>
            <form class="contact-form">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Your Name" required>
                </div>
                <div class="form-group">
                    <input type="email" class="form-control" placeholder="Email Address" required>
                </div>
                <div class="form-group">
                    <select class="form-control" required>
                        <option value="" disabled selected>Rate Your Experience</option>
                        <option value="5">Excellent</option>
                        <option value="4">Good</option>
                        <option value="3">Average</option>
                        <option value="2">Needs Improvement</option>
                        <option value="1">Poor</option>
                    </select>
                </div>
                <div class="form-group">
                    <textarea class="form-control" placeholder="Your Feedback..." required></textarea>
                </div>
                <button type="button" class="btn btn-primary" style="width: 100%;">Submit Feedback</button>
            </form>
        </div>
    </section>

    <section id="contact">
        <div class="section-header">
            <h2>Get In <span>Touch</span></h2>
            <p>Have questions about enrollment or our curriculum? Drop us a message.</p>
        </div>
        <div class="contact-container">
            <div class="contact-info">
                <h3>Let's bridge the education gap together.</h3>
                <p>Whether you are a student, a volunteer educator, or an NGO wanting to partner with us, we are here to answer your queries.</p>
                <p>📍 <strong>HQ:</strong> NomadEdu Hub, Himalayan Foothills, India</p>
                <p>📧 <strong>Email:</strong> support@nomadedu.com</p>
                <p>📞 <strong>Phone:</strong> +91 98765 43210</p>
            </div>
            <form class="contact-form">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Your Name" required>
                </div>
                <div class="form-group">
                    <input type="email" class="form-control" placeholder="Email Address" required>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Subject" required>
                </div>
                <div class="form-group">
                    <textarea class="form-control" placeholder="Your Message..." required></textarea>
                </div>
                <button type="button" class="btn btn-primary" style="width: 100%;">Send Message</button>
            </form>
        </div>
    </section>

    <footer>
        <div class="footer-grid">
            <div class="footer-col">
                <h4>NomadEdu</h4>
                <p>Empowering nomadic and migrant communities with accessible, low-bandwidth, offline-ready education that travels with you.</p>
                <div class="social-icons">
                    <a href="https://facebook.com" target="_blank"><svg viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg></a>
                    <a href="https://instagram.com" target="_blank"><svg viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg></a>
                    <a href="https://twitter.com" target="_blank"><svg viewBox="0 0 24 24"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5 2.8 11.5 2.8 11.5c.8.1 1.6.1 2.3-.1-3-2.1-3.1-6.1-3.1-6.1.9.5 2 .8 3 .8C3.5 4.8 5.6 1.8 8.1 3c2.7 3.3 6.6 5.4 10.9 5.6 1.2-5.7 8-7 3-3.1z"></path></svg></a>
                    <a href="https://youtube.com" target="_blank"><svg viewBox="0 0 24 24"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33 2.78 2.78 0 0 0 1.94 2c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.33 29 29 0 0 0-.46-5.33z"></path><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"></polygon></svg></a>
                    <a href="https://telegram.org" target="_blank"><svg viewBox="0 0 24 24"><polygon points="2 12 9 15 13 21 15 14 22 3"></polygon><line x1="15" y1="14" x2="9" y2="15"></line></svg></a>
                </div>
            </div>
            <div class="footer-col">
                <h4>Quick Links</h4>
                <ul class="footer-links">
                    <li><a href="#courses">All Courses</a></li>
                    <li><a href="#teachers">Our Faculty</a></li>
                    <li><a href="#reviews">Student Reviews</a></li>
                    <li><a href="#contact">Contact Support</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Resources</h4>
                <ul class="footer-links">
                    <li><a href="#">Offline Player App</a></li>
                    <li><a href="#">Download PDFs</a></li>
                    <li><a href="#">Volunteer with us</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Newsletter</h4>
                <p>Subscribe to get updates on new courses and features.</p>
                <div class="nl-form">
                    <input type="email" class="nl-input" placeholder="Enter Email">
                    <button class="nl-btn">Subscribe</button>
                </div>
            </div>
        </div>
        <div style="text-align: center; border-top: 1px solid var(--border); padding-top: 20px; color: #94a3b8; font-size: 0.9rem;">
            &copy; 2026 NomadEdu. Education without borders. All rights reserved.
        </div>
    </footer>

    <div id="courseModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeModal('courseModal')">&times;</span>
            <div class="modal-body">
                <h2 id="m-title">Course Title</h2>
                <p id="m-desc" style="color:#94a3b8; font-size:1.1rem; line-height:1.7;"></p>
                <div class="modal-info-grid">
                    <div class="info-item"><h5>Instructor</h5><p id="m-instructor">Name</p></div>
                    <div class="info-item"><h5>Duration</h5><p id="m-duration">X Weeks</p></div>
                    <div class="info-item"><h5>Career Benefit</h5><p id="m-benefit">Benefit</p></div>
                    <div class="info-item"><h5>Enrollment Fee</h5><p id="m-price" style="color:var(--primary); font-weight:800;"></p></div>
                </div>
                <div style="margin-top:40px;">
                    <button class="btn btn-primary" style="width:100%;">Enroll Now & Start Learning</button>
                </div>
            </div>
        </div>
    </div>

    <div id="facultyModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeModal('facultyModal')">&times;</span>
            <div class="faculty-modal-grid">
                <div style="text-align: center;">
                    <img id="f-modal-img" src="" class="faculty-img" style="width: 180px; height: 180px;">
                    <h2 id="f-modal-name" style="color: var(--primary);"></h2>
                    <p id="f-modal-role" style="color: #94a3b8;"></p>
                </div>
                <div>
                    <div class="fac-detail-box"><h6>Teaching Experience</h6><p id="f-modal-exp"></p></div>
                    <div class="fac-detail-box" style="margin-top: 15px;"><h6>Education Background</h6><p id="f-modal-edu"></p></div>
                    <div class="fac-detail-box" style="margin-top: 15px;"><h6>Students Mentored</h6><p id="f-modal-students" style="font-size: 1.5rem; font-weight: 800; color: var(--primary);"></p></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openCourse(title, desc, instructor, duration, benefit, price) {
            document.getElementById('m-title').innerText = title;
            document.getElementById('m-desc').innerText = desc;
            document.getElementById('m-instructor').innerText = instructor;
            document.getElementById('m-duration').innerText = duration;
            document.getElementById('m-benefit').innerText = benefit;
            document.getElementById('m-price').innerText = price;
            document.getElementById('courseModal').style.display = 'flex';
        }

        function openFaculty(name, role, exp, edu, students, img) {
            document.getElementById('f-modal-name').innerText = name;
            document.getElementById('f-modal-role').innerText = role;
            document.getElementById('f-modal-exp').innerText = exp;
            document.getElementById('f-modal-edu').innerText = edu;
            document.getElementById('f-modal-students').innerText = students;
            document.getElementById('f-modal-img').src = img;
            document.getElementById('facultyModal').style.display = 'flex';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        window.onclick = function(event) {
            let courseModal = document.getElementById('courseModal');
            let facultyModal = document.getElementById('facultyModal');
            if (event.target == courseModal) courseModal.style.display = "none";
            if (event.target == facultyModal) facultyModal.style.display = "none";
        }
    </script>

</body>
</html>
