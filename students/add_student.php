<?php
// students/add_student.php
$page_title = "Add Student - University App";
require_once '../config/db_connect.php';
require_once '../includes/header.php';

$message = ''; $message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname'] ?? '');
    $matricule = trim($_POST['matricule'] ?? '');
    $group_id = trim($_POST['group_id'] ?? '');
    
    try {
        $pdo = getDBConnection();
        
        // Validation
        if (empty($fullname) || empty($matricule) || empty($group_id)) {
            throw new Exception("All fields are required");
        }
        
        // Check if matricule already exists
        $stmt = $pdo->prepare("SELECT id FROM students WHERE matricule = ?");
        $stmt->execute([$matricule]);
        if ($stmt->fetch()) {
            throw new Exception("Student with matricule '$matricule' already exists");
        }
        
        // Insert new student
        $stmt = $pdo->prepare("INSERT INTO students (fullname, matricule, group_id) VALUES (?, ?, ?)");
        $stmt->execute([$fullname, $matricule, $group_id]);
        
        $message = "✅ Student added successfully!";
        $message_type = "success";
        
        // Clear form
        $_POST = [];
        
    } catch (Exception $e) {
        $message = "❌ " . $e->getMessage();
        $message_type = "error";
    }
}
?>

<div class="form-container">
    <h2>Add New Student</h2>
    
    <?php if ($message): ?>
        <div class="message <?php echo $message_type; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="student-form">
        <div class="form-group">
            <label for="fullname">Full Name:</label>
            <input type="text" id="fullname" name="fullname" 
                   value="<?php echo htmlspecialchars($_POST['fullname'] ?? ''); ?>" 
                   required placeholder="Enter full name">
        </div>
        
        <div class="form-group">
            <label for="matricule">Matricule:</label>
            <input type="text" id="matricule" name="matricule" 
                   value="<?php echo htmlspecialchars($_POST['matricule'] ?? ''); ?>" 
                   required placeholder="Enter matricule number">
        </div>
        
        <div class="form-group">
            <label for="group_id">Group ID:</label>
            <input type="text" id="group_id" name="group_id" 
                   value="<?php echo htmlspecialchars($_POST['group_id'] ?? ''); ?>" 
                   required placeholder="e.g., ISIL-01">
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn-primary">Add Student</button>
            <a href="list_students.php" class="btn-secondary">View All Students</a>
        </div>
    </form>
</div>

<style>
.form-container { max-width: 600px; margin: 0 auto; }
.student-form { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
.form-group { margin-bottom: 20px; }
.form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #2d3748; }
.form-group input { width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.3s; }
.form-group input:focus { border-color: #667eea; outline: none; }
.form-actions { display: flex; gap: 15px; margin-top: 30px; }
.btn-primary { background: #667eea; color: white; padding: 12px 30px; border: none; border-radius: 8px; cursor: pointer; font-size: 16px; }
.btn-secondary { background: #a0aec0; color: white; padding: 12px 30px; text-decoration: none; border-radius: 8px; font-size: 16px; text-align: center; }
.message { padding: 15px; border-radius: 8px; margin-bottom: 20px; }
.success { background: #c6f6d5; color: #22543d; border: 1px solid #9ae6b4; }
.error { background: #fed7d7; color: #742a2a; border: 1px solid #feb2b2; }
</style>

<?php require_once '../includes/footer.php'; ?>