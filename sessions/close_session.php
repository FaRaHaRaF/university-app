<?php
// sessions/close_session.php - Exercise 5
$page_title = "Close Sessions - University App";
require_once '../config/db_connect.php';
require_once '../includes/header.php';

$message = ''; $message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $session_id = $_POST['session_id'] ?? '';
    
    try {
        $pdo = getDBConnection();
        
        // Check if session exists and is open
        $stmt = $pdo->prepare("SELECT * FROM attendance_sessions WHERE id = ? AND status = 'open'");
        $stmt->execute([$session_id]);
        $session = $stmt->fetch();
        
        if (!$session) {
            throw new Exception("Session not found or already closed");
        }
        
        // Close session
        $stmt = $pdo->prepare("UPDATE attendance_sessions SET status = 'closed' WHERE id = ?");
        $stmt->execute([$session_id]);
        
        $message = "✅ Session closed successfully!";
        $message_type = "success";
        
    } catch (Exception $e) {
        $message = "❌ " . $e->getMessage();
        $message_type = "error";
    }
}

// Get open sessions
try {
    $pdo = getDBConnection();
    $open_sessions = $pdo->query("
        SELECT s.*, c.name as course_name, p.name as professor_name 
        FROM attendance_sessions s 
        JOIN courses c ON s.course_id = c.id 
        JOIN professors p ON s.opened_by = p.id 
        WHERE s.status = 'open'
        ORDER BY s.date DESC, s.created_at DESC
    ")->fetchAll();
} catch (Exception $e) {
    $open_sessions = [];
    $error = $e->getMessage();
}
?>

<div class="sessions-container">
    <h2>Close Attendance Sessions</h2>
    
    <?php if (isset($error)): ?>
        <div class="error-message"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php if ($message): ?>
        <div class="message <?php echo $message_type; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <?php if (empty($open_sessions)): ?>
        <div class="empty-state">
            <h3>No Open Sessions</h3>
            <p>There are no active sessions to close.</p>
            <a href="create_session.php" class="btn-primary">Create New Session</a>
        </div>
    <?php else: ?>
        <div class="close-session-form">
            <h3>Close a Session</h3>
            <form method="POST" class="session-form">
                <div class="form-group">
                    <label for="session_id">Select Session to Close:</label>
                    <select id="session_id" name="session_id" required>
                        <option value="">Select Open Session</option>
                        <?php foreach ($open_sessions as $session): ?>
                        <option value="<?php echo $session['id']; ?>">
                            ID: <?php echo $session['id']; ?> - 
                            <?php echo $session['course_name']; ?> - 
                            Group: <?php echo $session['group_id']; ?> - 
                            Date: <?php echo date('M j, Y', strtotime($session['date'])); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn-warning">Close Session</button>
                    <a href="create_session.php" class="btn-secondary">Create New Session</a>
                </div>
            </form>
        </div>

        <div class="open-sessions-list">
            <h3>Open Sessions</h3>
            <div class="sessions-table">
                <div class="table-header">
                    <div>ID</div>
                    <div>Course</div>
                    <div>Group</div>
                    <div>Date</div>
                    <div>Professor</div>
                    <div>Status</div>
                </div>
                
                <?php foreach ($open_sessions as $session): ?>
                <div class="table-row">
                    <div><?php echo $session['id']; ?></div>
                    <div><?php echo htmlspecialchars($session['course_name']); ?></div>
                    <div><?php echo htmlspecialchars($session['group_id']); ?></div>
                    <div><?php echo date('M j, Y', strtotime($session['date'])); ?></div>
                    <div><?php echo htmlspecialchars($session['professor_name']); ?></div>
                    <div>
                        <span class="status-badge open">Open</span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
    
    <div class="actions">
        <a href="../index.php" class="btn-primary">Back to Home</a>
    </div>
</div>

<style>
.sessions-container { max-width: 1000px; margin: 0 auto; }
.close-session-form { 
    background: white; 
    padding: 25px; 
    border-radius: 15px; 
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}
.open-sessions-list { 
    background: white; 
    padding: 25px; 
    border-radius: 15px; 
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    margin-bottom: 25px;
}
.sessions-table { margin-top: 15px; }
.table-header, .table-row { 
    display: grid; 
    grid-template-columns: 80px 2fr 1fr 120px 2fr 100px; 
    gap: 15px; 
    padding: 12px 15px; 
    border-bottom: 1px solid #eee; 
}
.table-header { background: #f8f9fa; font-weight: bold; }
.status-badge { padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; }
.open { background: #c6f6d5; color: #22543d; }
.btn-warning { background: #ed8936; color: white; padding: 12px 25px; border: none; border-radius: 8px; cursor: pointer; }
.btn-primary { background: #667eea; color: white; padding: 12px 25px; text-decoration: none; border-radius: 8px; display: inline-block; }
.btn-secondary { background: #a0aec0; color: white; padding: 12px 25px; text-decoration: none; border-radius: 8px; display: inline-block; }
.form-actions { display: flex; gap: 15px; margin-top: 20px; }
.empty-state { text-align: center; padding: 60px 20px; background: white; border-radius: 15px; margin: 20px 0; }
.actions { text-align: center; margin-top: 25px; }
.message { padding: 15px; border-radius: 8px; margin-bottom: 20px; }
.success { background: #c6f6d5; color: #22543d; border: 1px solid #9ae6b4; }
.error { background: #fed7d7; color: #742a2a; border: 1px solid #feb2b2; }
.error-message { background: #fed7d7; color: #c53030; padding: 15px; border-radius: 8px; margin: 15px 0; }
</style>

<?php require_once '../includes/footer.php'; ?>