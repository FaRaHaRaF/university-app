<?php
// students/update_student.php
$page_title = "Update Student - University App";
require_once '../config/db_connect.php';
require_once '../includes/header.php';

if (!isset($_GET['id'])) {
    header('Location: list_students.php');
    exit;
}

$student_id = $_GET['id'];
$message = ''; $message_type = '';

try {
    $pdo = getDBConnection();
    
    // Get current student data
    $stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
    $stmt->execute([$student_id]);
    $student = $stmt->fetch();
    
    if (!$student) {
        throw new Exception("Student not found");
    }
    
    // Process update
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $fullname = trim($_POST['fullname'] ?? '');
        $matricule = trim($_POST['matricule'] ?? '');
        $group_id = trim($_POST['group_id'] ?? '');
        
        if (empty($fullname) || empty($matricule) || empty($group_id)) {
            throw new Exception("All fields are required");
        }
        
        // Check if matricule exists for another student
        $stmt = $pdo->prepare("SELECT id FROM students WHERE matricule = ? AND id != ?");
        $stmt->execute([$matricule, $student_id]);
        if ($stmt->fetch()) {
            throw new Exception("Matricule '$matricule' already exists for another student");
        }
        
        // Update student
        $stmt = $pdo->prepare("UPDATE students SET fullname = ?, matricule = ?, group_id = ? WHERE id = ?");
        $stmt->execute([$fullname, $matricule, $group_id, $student_id]);
        
        $message = "✅ Student updated successfully!";
        $message_type = "success";
        
        // Refresh student data
        $stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
        $stmt->execute([$student_id]);
        $student = $stmt->fetch();
    }
    
} catch (Exception $e) {
    $message = "❌ " . $e->getMessage();
    $message_type = "error";
}
?>

<div class="form-container">
    <h2>Update Student</h2>
    
    <?php if ($message): ?>
        <div class="message <?php echo $message_type; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="student-form">
        <div class="form-group">
            <label for="fullname">Full Name:</label>
            <input type="text" id="fullname" name="fullname" 
                   value="<?php echo htmlspecialchars($student['fullname']); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="matricule">Matricule:</label>
            <input type="text" id="matricule" name="matricule" 
                   value="<?php echo htmlspecialchars($student['matricule']); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="group_id">Group ID:</label>
            <input type="text" id="group_id" name="group_id" 
                   value="<?php echo htmlspecialchars($student['group_id']); ?>" required>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn-primary">Update Student</button>
            <a href="list_students.php" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<style>
.form-container { max-width: 600px; margin: 0 auto; }
.student-form { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
.form-group { margin-bottom: 20px; }
.form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #2d3748; }
.form-group input { width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; }
.form-actions { display: flex; gap: 15px; margin-top: 30px; }
.btn-primary { background: #667eea; color: white; padding: 12px 30px; border: none; border-radius: 8px; cursor: pointer; }
.btn-secondary { background: #a0aec0; color: white; padding: 12px 30px; text-decoration: none; border-radius: 8px; text-align: center; }
.message { padding: 15px; border-radius: 8px; margin-bottom: 20px; }
.success { background: #c6f6d5; color: #22543d; border: 1px solid #9ae6b4; }
.error { background: #fed7d7; color: #742a2a; border: 1px solid #feb2b2; }
</style>

<?php require_once '../includes/footer.php'; ?>