@extends('teacher.layout')

@section('title', 'Dashboard')
@section('page_title', 'Welcome')
@section('active_nav', 'dashboard')

@section('content')
<div class="grid" style="grid-template-columns: repeat(5, minmax(120px, 1fr));">
    <div class="card">
        <div class="muted">Assigned Students</div>
        <h2 id="metric-students" style="margin:8px 0 0;">{{ $studentCount }}</h2>
    </div>
    <div class="card">
        <div class="muted">Your Classes</div>
        <h2 id="metric-classes" style="margin:8px 0 0;">{{ $classCount }}</h2>
    </div>
    <div class="card">
        <div class="muted">Avg Progress</div>
        <h2 id="metric-progress" style="margin:8px 0 0;">{{ $avgProgress }}%</h2>
    </div>
    <div class="card">
        <div class="muted">Avg Quiz Score</div>
        <h2 id="metric-quiz" style="margin:8px 0 0;">{{ $avgQuiz }}%</h2>
    </div>
    <div class="card">
        <div class="muted">Pending Lessons</div>
        <h2 id="metric-pending" style="margin:8px 0 0;">{{ $pendingLessons }}</h2>
    </div>
</div>

<div class="grid" style="grid-template-columns: 2fr 1fr; margin-top: 14px;">
    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
            <h3 style="margin:0;">Top Student Progress</h3>
            <a href="{{ route('teacher.students') }}" class="btn-ghost">Manage Students</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Course Completion</th>
                        <th>Latest Quiz</th>
                        <th>Report</th>
                    </tr>
                </thead>
                <tbody id="top-students-body">
                    @forelse($topStudents as $row)
                        <tr>
                            <td>{{ $row['name'] }}</td>
                            <td>{{ $row['progress'] }}%</td>
                            <td>{{ $row['quiz'] !== null ? $row['quiz'].'%' : 'N/A' }}</td>
                            <td><a class="btn-primary" href="{{ route('teacher.students.report', $row['id']) }}">View</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="muted">No assigned students yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <h3 style="margin-top:0;">Recent Quiz Activity</h3>
        <div id="recent-activity-list" class="grid" style="grid-template-columns: 1fr;">
            @forelse($recentActivity as $activity)
                <div class="card" style="padding:10px;">
                    <strong>{{ $activity['student'] }}</strong>
                    <div class="muted" style="margin-top:4px;">{{ $activity['message'] }}</div>
                    <div class="muted" style="margin-top:4px; font-size:12px;">{{ $activity['time'] }}</div>
                </div>
            @empty
                <div class="muted">No quiz activity yet.</div>
            @endforelse
        </div>
    </div>
</div>

<div class="card" style="margin-top:14px;">
    <h3 style="margin-top:0;">Your Classes</h3>
    <div class="grid" style="grid-template-columns: repeat(3, minmax(150px, 1fr));" id="classes-list">
        @forelse($classes as $class)
            <div class="card" style="padding:10px;">
                <strong>{{ $class['name'] }}</strong>
            </div>
        @empty
            <div class="muted">No classes assigned to you.</div>
        @endforelse
    </div>
    <div class="muted" style="margin-top:10px;">Auto-refreshes every 20 seconds</div>
</div>
@endsection

@push('scripts')
<script>
    async function refreshTeacherDashboardMetrics() {
        try {
            const response = await fetch("{{ route('teacher.dashboard.metrics') }}", {
                headers: { 'Accept': 'application/json' },
                credentials: 'same-origin',
            });
            if (!response.ok) return;
            const data = await response.json();

            document.getElementById('metric-students').textContent = data.studentCount ?? 0;
            document.getElementById('metric-classes').textContent = data.classCount ?? 0;
            document.getElementById('metric-progress').textContent = `${data.avgProgress ?? 0}%`;
            document.getElementById('metric-quiz').textContent = `${data.avgQuiz ?? 0}%`;
            document.getElementById('metric-pending').textContent = data.pendingLessons ?? 0;

            const studentBody = document.getElementById('top-students-body');
            studentBody.innerHTML = '';
            const topStudents = Array.isArray(data.topStudents) ? data.topStudents : [];
            if (!topStudents.length) {
                studentBody.innerHTML = '<tr><td colspan="4" class="muted">No assigned students yet.</td></tr>';
            } else {
                topStudents.forEach((row) => {
                    const tr = document.createElement('tr');
                    const tdName = document.createElement('td');
                    tdName.textContent = row.name ?? 'Student';
                    const tdProgress = document.createElement('td');
                    tdProgress.textContent = `${row.progress ?? 0}%`;
                    const tdQuiz = document.createElement('td');
                    tdQuiz.textContent = row.quiz != null ? `${row.quiz}%` : 'N/A';
                    const tdLink = document.createElement('td');
                    const link = document.createElement('a');
                    link.className = 'btn-primary';
                    link.href = `/teacher/students/${row.id}/report`;
                    link.textContent = 'View';
                    tdLink.appendChild(link);
                    tr.append(tdName, tdProgress, tdQuiz, tdLink);
                    studentBody.appendChild(tr);
                });
            }

            const recentList = document.getElementById('recent-activity-list');
            recentList.innerHTML = '';
            const activities = Array.isArray(data.recentActivity) ? data.recentActivity : [];
            if (!activities.length) {
                recentList.innerHTML = '<div class="muted">No quiz activity yet.</div>';
            } else {
                activities.forEach((item) => {
                    const box = document.createElement('div');
                    box.className = 'card';
                    box.style.padding = '10px';
                    const title = document.createElement('strong');
                    title.textContent = item.student ?? 'Student';
                    const message = document.createElement('div');
                    message.className = 'muted';
                    message.style.marginTop = '4px';
                    message.textContent = item.message ?? '';
                    const time = document.createElement('div');
                    time.className = 'muted';
                    time.style.marginTop = '4px';
                    time.style.fontSize = '12px';
                    time.textContent = item.time ?? '';
                    box.append(title, message, time);
                    recentList.appendChild(box);
                });
            }
        } catch (error) {
        }
    }

    setInterval(refreshTeacherDashboardMetrics, 20000);
</script>
@endpush
