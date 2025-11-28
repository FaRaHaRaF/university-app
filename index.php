<?php
// index.php - Home Dashboard
$page_title = "University App - Dashboard";
require_once 'includes/header.php';

// Get stats from database
try {
    require_once 'config/db_connect.php';
    $pdo = getDBConnection();
    $student_count = $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
    $active_sessions = $pdo->query("SELECT COUNT(*) FROM attendance_sessions WHERE status = 'open'")->fetchColumn();
    $today = date('Y-m-d');
    $today_attendance = $pdo->query("SELECT COUNT(*) FROM attendance WHERE date = '$today'")->fetchColumn();
} catch (Exception $e) {
    $student_count = $active_sessions = $today_attendance = 0;
}
?>

<div class="dashboard">
    <!-- Quick Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">👨‍🎓</div>
            <div class="stat-info">
                <div class="stat-number"><?php echo $student_count; ?></div>
                <div class="stat-label">Total Students</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">✅</div>
            <div class="stat-info">
                <div class="stat-number"><?php echo $today_attendance; ?></div>
                <div class="stat-label">Today's Attendance</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">🎯</div>
            <div class="stat-info">
                <div class="stat-number"><?php echo $active_sessions; ?></div>
                <div class="stat-label">Active Sessions</div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
        <h2>Quick Actions</h2>
        <div class="actions-grid">
            <a href="students/add_student.php" class="action-card">
                <div class="action-icon">➕</div>
                <h3>Add Student</h3>
                <p>Register new student</p>
            </a>
            <a href="attendance/take_attendance.php" class="action-card">
                <div class="action-icon">📝</div>
                <h3>Take Attendance</h3>
                <p>Mark today's presence</p>
            </a>
            <a href="sessions/create_session.php" class="action-card">
                <div class="action-icon">🚀</div>
                <h3>Start Session</h3>
                <p>Create new class session</p>
            </a>
            <a href="students/list_students.php" class="action-card">
                <div class="action-icon">📋</div>
                <h3>View Students</h3>
                <p>See all registered students</p>
            </a>
        </div>
    </div>
</div>

<style>
.stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin: 30px 0; }
.stat-card { background: white; padding: 25px; border-radius: 15px; display: flex; align-items: center; gap: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
.stat-icon { font-size: 3rem; }
.stat-number { font-size: 2rem; font-weight: bold; color: #667eea; }
.stat-label { color: #718096; }
.quick-actions { margin-top: 40px; }
.actions-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 25px; margin-top: 20px; }
.action-card { background: white; padding: 30px; border-radius: 15px; text-align: center; text-decoration: none; color: inherit; box-shadow: 0 5px 15px rgba(0,0,0,0.1); transition: all 0.3s ease; }
.action-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.15); }
.action-icon { font-size: 3rem; margin-bottom: 15px; }
.action-card h3 { color: #2d3748; margin-bottom: 10px; }
.action-card p { color: #718096; }
</style>

<?php require_once 'includes/footer.php'; ?>