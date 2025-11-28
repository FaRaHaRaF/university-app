<?php
// sessions/create_session.php - Exercise 5
$page_title = "Create Session - University App";
require_once '../config/db_connect.php';
require_once '../includes/header.php';

$message = ''; $message_type = '';
$session_id = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_id = $_POST['course_id'] ?? '';
    $group_id = trim($_POST['group_id'] ?? '');
    $professor_id = $_POST['professor_id'] ?? '';
    $date = $_POST['date'] ?? date('Y-m-d');
    
    try {
        $pdo = getDBConnection();
        
        // Validation
        if (empty($course_id) || empty($group_id) || empty($professor_id)) {
            throw new Exception("All fields are required");
        }
        
        // Check if session already exists for this course/group/date
        $stmt = $pdo->prepare("SELECT id FROM attendance_sessions WHERE course_id = ? AND group_id = ? AND date = ?");
        $stmt->execute([$course_id, $group_id, $date]);
        if ($stmt->fetch()) {
            throw new Exception("A session for this course, group, and date already exists");
        }
        
        // Insert new session
        $stmt = $pdo->prepare("INSERT INTO attendance_sessions (course_id, group_id, date, opened_by, status) VALUES (?, ?, ?, ?, 'open')");
        $stmt->execute([$course_id, $group_id, $date, $professor_id]);
        
        $session_id = $pdo->lastInsertId();
        $message = "✅ Session created successfully! Session ID: " . $session_id;
        $message_type = "success";
        
    } catch (Exception $e) {
        $message = "❌ " . $e->getMessage();
        $message_type = "error";
    }
}

// Get courses and professors for dropdown
try {
    $pdo = getDBConnection();
    $courses = $pdo->query("SELECT * FROM courses ORDER BY name")->fetchAll();
    $professors = $pdo->query("SELECT * FROM professors ORDER BY name")->fetchAll();
} catch (Exception $e) {
    $courses = [];
    $professors = [];
    $error = $e->getMessage();
}
?>

<div class="form-container">
    <h2>Create Attendance Session</h2>
    
    <?php if (isset($error)): ?>
        <div class="error-message"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php if ($message): ?>
        <div class="message <?php echo $message_type; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="session-form">
        <div class="form-group">
            <label for="course_id">Course:</label>
            <select id="course_id" name="course_id" required>
                <option value="">Select Course</option>
                <?php foreach ($courses as $course): ?>
                <option value="<?php echo $course['id']; ?>">
                    <?php echo htmlspecialchars($course['name'] . ' (' . $course['code'] . ')'); ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label for="group_id">Group ID:</label>
            <input type="text" id="group_id" name="group_id" 
                   value="<?php echo htmlspecialchars($_POST['group_id'] ?? ''); ?>" 
                   required placeholder="e.g., ISIL-01">
        </div>
        
        <div class="form-group">
            <label for="professor_id">Professor:</label>
            <select id="professor_id" name="professor_id" required>
                <option value="">Select Professor</option>
                <?php foreach ($professors as $professor): ?>
                <option value="<?php echo $professor['id']; ?>">
                    <?php echo htmlspecialchars($professor['name']); ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label for="date">Session Date:</label>
            <input type="date" id="date" name="date" 
                   value="<?php echo $_POST['date'] ?? date('Y-m-d'); ?>" 
                   required>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn-primary">Create Session</button>
            <a href="close_session.php" class="btn-secondary">Close Sessions</a>
            <a href="../index.php" class="btn-secondary">Back to Home</a>
        </div>
    </form>
    
    <?php if ($session_id): ?>
        <div class="session-info">
            <h3>Session Created Successfully!</h3>
            <p><strong>Session ID:</strong> <?php echo $session_id; ?></p>
            <p>You can now <a href="../attendance/take_attendance.php">take attendance</a> for this session.</p>
        </div>
    <?php endif; ?>
</div>

<style>
.form-container { max-width: 600px; margin: 0 auto; }
.session-form { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
.form-group { margin-bottom: 20px; }
.form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #2d3748; }
.form-group input, .form-group select { 
    width: 100%; 
    padding: 12px; 
    border: 2px solid #e2e8f0; 
    border-radius: 8px; 
    font-size: 16px; 
}
.form-actions { display: flex; gap: 15px; margin-top: 30px; flex-wrap: wrap; }
.btn-primary { background: #667eea; color: white; padding: 12px 25px; border: none; border-radius: 8px; cursor: pointer; }
.btn-secondary { background: #a0aec0; color: white; padding: 12px 25px; text-decoration: none; border-radius: 8px; text-align: center; }
.session-info { 
    background: #c6f6d5; 
    color: #22543d; 
    padding: 20px; 
    border-radius: 10px; 
    margin-top: 25px;
    border: 1px solid #9ae6b4;
}
.message { padding: 15px; border-radius: 8px; margin-bottom: 20px; }
.success { background: #c6f6d5; color: #22543d; border: 1px solid #9ae6b4; }
.error { background: #fed7d7; color: #742a2a; border: 1px solid #feb2b2; }
.error-message { background: #fed7d7; color: #c53030; padding: 15px; border-radius: 8px; margin: 15px 0; }
</style>

<?php require_once '../includes/footer.php'; ?>