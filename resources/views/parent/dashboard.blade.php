<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Dashboard | NomadEdu</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* --- THEME VARIABLES (Dark Mode by Default) --- */
        :root {
            --primary: #fbbf24;
            --bg-color: #050505;
            --text-color: #ffffff;
            --text-muted: #94a3b8;
            --glass-bg: rgba(255, 255, 255, 0.05);
            --border-color: rgba(255, 255, 255, 0.1);
            --sidebar-bg: #0a0a0a;
            --hover-bg: rgba(251, 191, 36, 0.1);
        }

        /* --- LIGHT MODE VARIABLES --- */
        body.light-mode {
            --bg-color: #f8fafc;
            --text-color: #0f172a;
            --text-muted: #475569;
            --glass-bg: #ffffff;
            --border-color: rgba(0, 0, 0, 0.1);
            --sidebar-bg: #ffffff;
            --hover-bg: rgba(251, 191, 36, 0.2);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; transition: background 0.3s, color 0.3s; }
        body { background: var(--bg-color); color: var(--text-color); display: flex; height: 100vh; overflow: hidden; }

        /* --- SIDEBAR --- */
        .sidebar {
            width: 280px; height: 100%; background: var(--sidebar-bg); border-right: 1px solid var(--border-color);
            padding: 30px 20px; display: flex; flex-direction: column;
        }
        .sidebar h2 { color: var(--primary); font-size: 1.8rem; font-weight: 800; margin-bottom: 40px; text-align: center; }
        .nav-menu { flex: 1; display: flex; flex-direction: column; gap: 10px; }
        .nav-item {
            padding: 15px 20px; border-radius: 12px; color: var(--text-muted); font-weight: 600;
            cursor: pointer; display: flex; align-items: center; gap: 15px; text-decoration: none;
        }
        .nav-item:hover, .nav-item.active { background: var(--hover-bg); color: var(--primary); }
        .theme-toggle { margin-top: auto; padding: 15px; background: var(--glass-bg); border-radius: 12px; text-align: center; cursor: pointer; border: 1px solid var(--border-color); font-weight: bold; }

        /* --- MAIN CONTENT --- */
        .main-content { flex: 1; padding: 40px 50px; overflow-y: auto; }
        
        /* Header & Notifications */
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; }
        .header h1 { font-size: 2.2rem; }
        .header h1 span { color: var(--primary); }
        .user-controls { display: flex; align-items: center; gap: 20px; }
        .attendance-badge { background: rgba(34, 197, 94, 0.1); color: #22c55e; padding: 8px 15px; border-radius: 50px; font-weight: 700; border: 1px solid rgba(34, 197, 94, 0.2); }
        .notification-bell { font-size: 1.5rem; position: relative; cursor: pointer; }
        .notification-bell::after { content: '3'; position: absolute; top: -5px; right: -8px; background: red; color: white; font-size: 0.7rem; width: 18px; height: 18px; border-radius: 50%; display: flex; align-items: center; justify-content: center; }

        /* Dashboard Grid Layout */
        .dashboard-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 30px; }
        .glass-card { background: var(--glass-bg); border: 1px solid var(--border-color); border-radius: 24px; padding: 30px; backdrop-filter: blur(10px); box-shadow: 0 10px 30px rgba(0,0,0,0.05); }

        /* Continue Learning Banner */
        .continue-banner { display: flex; justify-content: space-between; align-items: center; background: linear-gradient(135deg, rgba(251,191,36,0.1), transparent); border-color: var(--primary); }
        .continue-banner h3 { font-size: 1.5rem; margin-bottom: 10px; }
        .progress-bar { width: 100%; height: 8px; background: var(--border-color); border-radius: 10px; margin-top: 15px; overflow: hidden; }
        .progress-fill { height: 100%; background: var(--primary); border-radius: 10px; width: 65%; }

        /* Grid for Stats */
        .stats-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 30px; }
        .stat-box h4 { color: var(--text-muted); font-size: 0.9rem; margin-bottom: 5px; text-transform: uppercase; }
        .stat-box h2 { font-size: 2.5rem; color: var(--text-color); }

        /* Enrolled Courses & Notes */
        .section-title { margin: 30px 0 20px; font-size: 1.3rem; }
        .course-list, .notes-list { display: flex; flex-direction: column; gap: 15px; }
        .list-item { display: flex; justify-content: space-between; align-items: center; padding: 15px; background: var(--glass-bg); border-radius: 15px; border: 1px solid var(--border-color); }
        .list-item h5 { font-size: 1.1rem; margin-bottom: 5px; }
        .list-item p { font-size: 0.85rem; color: var(--text-muted); }

        /* Badges */
        .badges-container { display: flex; gap: 15px; flex-wrap: wrap; }
        .badge { width: 60px; height: 60px; background: var(--hover-bg); border: 1px solid var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; cursor: pointer; transition: 0.3s; }
        .badge:hover { transform: scale(1.1); box-shadow: 0 0 15px rgba(251, 191, 36, 0.3); }

        /* Buttons */
        .btn { padding: 10px 20px; border-radius: 10px; font-weight: 700; border: none; cursor: pointer; background: var(--primary); color: #000; transition: 0.3s; }
        .btn:hover { background: var(--primary-hover); transform: translateY(-2px); }
        .btn-outline { background: transparent; border: 1px solid var(--primary); color: var(--primary); }

        /* --- AI ASSISTANT CHAT UI --- */
        .ai-chat-btn { position: fixed; bottom: 30px; right: 30px; width: 60px; height: 60px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; cursor: pointer; box-shadow: 0 10px 20px rgba(0,0,0,0.3); z-index: 2000; transition: 0.3s; }
        .ai-chat-btn:hover { transform: scale(1.1); }
        
        .ai-chat-window { position: fixed; bottom: 100px; right: 30px; width: 350px; background: var(--sidebar-bg); border: 1px solid var(--primary); border-radius: 20px; overflow: hidden; display: none; flex-direction: column; box-shadow: 0 15px 40px rgba(0,0,0,0.5); z-index: 2000; }
        .chat-header { background: var(--primary); padding: 15px; font-weight: bold; color: #000; display: flex; justify-content: space-between; }
        .chat-body { height: 300px; padding: 20px; overflow-y: auto; display: flex; flex-direction: column; gap: 15px; }
        .msg { padding: 10px 15px; border-radius: 15px; max-width: 80%; font-size: 0.9rem; line-height: 1.4; }
        .msg.ai { background: var(--glass-bg); align-self: flex-start; border: 1px solid var(--border-color); }
        .msg.user { background: var(--primary); color: #000; align-self: flex-end; }
        .chat-input { display: flex; border-top: 1px solid var(--border-color); }
        .chat-input input { flex: 1; padding: 15px; background: transparent; border: none; color: var(--text-color); outline: none; }
        .chat-input button { padding: 0 20px; background: var(--primary); border: none; font-weight: bold; cursor: pointer; color: #000; }
    </style>
</head>
<body>

    <aside class="sidebar">
        <h2>NomadEdu</h2>
        <div class="nav-menu">
            <a href="#" class="nav-item active">?? Dashboard</a>
            <a href="#courses" class="nav-item">?? My Courses</a>
            <a href="#quizzes" class="nav-item">?? Quizzes</a>
            <a href="#notes" class="nav-item">?? Downloads</a>
            <a href="#badges" class="nav-item">?? Achievements</a>
        </div>
        
        <div class="theme-toggle" onclick="toggleTheme()">?? Toggle Dark Mode</div>
        
        <form action="{{ route('logout') }}" method="POST" style="margin-top: 15px;">
            @csrf
            <button type="submit" style="width:100%; background:transparent; border:none; cursor:pointer;" class="nav-item">?? Logout</button>
        </form>
    </aside>

    <main class="main-content">
        <header class="header">
            <h1>Welcome back, <span>{{ auth()->user()->name }}</span>!</h1>
            <div class="user-controls">
                <div class="attendance-badge">Parent Portal</div>
                <div class="notification-bell">??</div>
                <div style="width: 45px; height: 45px; background: var(--primary); border-radius: 50%; border: 2px solid #fff;"></div>
            </div>
        </header>

        <div class="dashboard-grid">
            
            <div>
                <div class="glass-card continue-banner">
                    <div style="flex:1;">
                        <h4 style="color:var(--primary); text-transform:uppercase; font-size:0.8rem; margin-bottom:5px;">Student Progress</h4>
                        <h3>Advanced Mathematics</h3>
                        <p style="color:var(--text-muted); font-size:0.95rem;">Module 4: Calculus Fundamentals</p>
                        <div class="progress-bar"><div class="progress-fill"></div></div>
                        <p style="font-size:0.8rem; margin-top:8px;">65% Completed</p>
                    </div>
                    <button class="btn" style="margin-left:30px;">View details ?</button>
                </div>

                <div class="stats-grid">
                    <div class="glass-card stat-box">
                        <h4>Overall Progress</h4>
                        <h2>78%</h2>
                        <p style="color: #22c55e; font-size:0.85rem;">? 5% this week</p>
                    </div>
                    <div class="glass-card stat-box">
                        <h4>Lessons Completed</h4>
                        <h2>24</h2>
                        <p style="color: var(--primary); font-size:0.85rem;">Out of 32</p>
                    </div>
                </div>

                <h3 class="section-title" id="courses">Currently Enrolled</h3>
                <div class="course-list">
                    <div class="list-item">
                        <div>
                            <h5>Organic Chemistry</h5>
                            <p>Next Live Class: Tomorrow, 10:00 AM</p>
                        </div>
                    </div>
                    <div class="list-item">
                        <div>
                            <h5>Digital Skills Development</h5>
                            <p>Assignment pending: UI Basics</p>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="glass-card" id="badges">
                    <h3 style="margin-bottom: 20px;">Achievements</h3>
                    <div class="badges-container">
                        <div class="badge" title="First Login">??</div>
                        <div class="badge" title="Perfect Attendance">??</div>
                        <div class="badge" title="Quiz Master">??</div>
                        <div class="badge" title="Night Owl Learner">??</div>
                    </div>
                </div>

                <div class="glass-card" style="margin-top: 30px;" id="quizzes">
                    <h3 style="margin-bottom: 20px;">Pending Quizzes</h3>
                    <div class="list-item" style="padding: 10px; margin-bottom:10px;">
                        <div>
                            <h5 style="font-size: 0.95rem;">Maths Weekly Test</h5>
                            <p style="font-size: 0.75rem;">Due in 2 days</p>
                        </div>
                    </div>
                </div>

                <div class="glass-card" style="margin-top: 30px;" id="notes">
                    <h3 style="margin-bottom: 20px;">Study Materials</h3>
                    <div class="notes-list">
                        <div class="list-item" style="padding: 10px;">
                            <span style="font-size: 1.2rem;">??</span>
                            <div style="flex:1; margin-left:10px;">
                                <h5 style="font-size:0.9rem;">Chemistry_Ch4_Notes.pdf</h5>
                                <p style="font-size:0.75rem;">2.4 MB</p>
                            </div>
                            <span style="cursor:pointer; color:var(--primary);">??</span>
                        </div>
                        <div class="list-item" style="padding: 10px;">
                            <span style="font-size: 1.2rem;">??</span>
                            <div style="flex:1; margin-left:10px;">
                                <h5 style="font-size:0.9rem;">Maths_Formulas.pdf</h5>
                                <p style="font-size:0.75rem;">1.1 MB</p>
                            </div>
                            <span style="cursor:pointer; color:var(--primary);">??</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <div class="ai-chat-btn" onclick="toggleChat()">??</div>
    
    <div class="ai-chat-window" id="chatWindow">
        <div class="chat-header">
            NomadAI Assistant
            <span style="cursor:pointer;" onclick="toggleChat()">?</span>
        </div>
        <div class="chat-body" id="chatBody">
            <div class="msg ai">Hello! I am NomadAI. Need help with Math or Chemistry today?</div>
            <div class="msg user">Yes, explain Calculus.</div>
            <div class="msg ai">Calculus is the mathematical study of continuous change. It has two major branches: Differential and Integral...</div>
        </div>
        <div class="chat-input">
            <input type="text" placeholder="Type your question..." id="chatInput">
            <button onclick="sendMsg()">Send</button>
        </div>
    </div>

    <script>
        // 1. Dark Mode Toggle
        function toggleTheme() {
            document.body.classList.toggle('light-mode');
        }

        // 2. AI Chat Toggle
        function toggleChat() {
            const chat = document.getElementById('chatWindow');
            chat.style.display = (chat.style.display === 'flex') ? 'none' : 'flex';
        }

        // 3. Simple AI Chat Simulation
        function sendMsg() {
            const input = document.getElementById('chatInput');
            const chatBody = document.getElementById('chatBody');
            if(input.value.trim() !== '') {
                // User message
                chatBody.innerHTML += `<div class="msg user">${input.value}</div>`;
                
                // Bot response simulation
                setTimeout(() => {
                    chatBody.innerHTML += `<div class="msg ai">That's a great question! However, I am currently a UI demo. Connect backend APIs to make me smart!</div>`;
                    chatBody.scrollTop = chatBody.scrollHeight; // Auto-scroll to bottom
                }, 1000);
                
                input.value = ''; // Clear input
                chatBody.scrollTop = chatBody.scrollHeight;
            }
        }
    </script>
</body>
</html>
