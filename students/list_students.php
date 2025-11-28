<?php
// students/list_students.php
$page_title = "Student List - University App";
require_once '../config/db_connect.php';
require_once '../includes/header.php';

// Check for messages from delete operation
if (isset($_GET['message']) && isset($_GET['type'])) {
    $message = $_GET['message'];
    $message_type = $_GET['type'];
}

try {
    $pdo = getDBConnection();
    $students = $pdo->query("SELECT * FROM students ORDER BY id DESC")->fetchAll();
} catch (Exception $e) {
    $error = $e->getMessage();
}
?>

<div class="students-management">
    <div class="header-actions">
        <a href="add_student.php" class="btn-primary">➕ Add New Student</a>
    </div>

    <?php if (isset($message)): ?>
        <div class="message <?php echo $message_type; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div class="error-message"><?php echo $error; ?></div>
    <?php elseif (empty($students)): ?>
        <div class="empty-state">
            <h3>No Students Found</h3>
            <p>Get started by adding your first student.</p>
            <a href="add_student.php" class="btn-primary">Add First Student</a>
        </div>
    <?php else: ?>
        <div class="students-table">
            <div class="table-header">
                <div>ID</div>
                <div>Full Name</div>
                <div>Matricule</div>
                <div>Group</div>
                <div>Created</div>
                <div>Actions</div>
            </div>
            <?php foreach ($students as $student): ?>
            <div class="table-row">
                <div><?php echo $student['id']; ?></div>
                <div><?php echo htmlspecialchars($student['fullname']); ?></div>
                <div><?php echo htmlspecialchars($student['matricule']); ?></div>
                <div><?php echo htmlspecialchars($student['group_id']); ?></div>
                <div><?php echo date('M j, Y', strtotime($student['created_at'])); ?></div>
                <div class="actions">
                    <a href="update_student.php?id=<?php echo $student['id']; ?>" class="btn-edit">Edit</a>
                    <a href="delete_student.php?id=<?php echo $student['id']; ?>" class="btn-delete" onclick="return confirm('Delete this student?')">Delete</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
.header-actions { margin-bottom: 25px; }
.btn-primary { background: #667eea; color: white; padding: 12px 20px; text-decoration: none; border-radius: 8px; display: inline-block; }
.students-table { background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
.table-header, .table-row { display: grid; grid-template-columns: 80px 1fr 1fr 100px 120px 150px; gap: 15px; padding: 15px 20px; border-bottom: 1px solid #eee; }
.table-header { background: #f8f9fa; font-weight: bold; }
.actions { display: flex; gap: 8px; }
.btn-edit { background: #f6ad55; color: white; padding: 6px 12px; text-decoration: none; border-radius: 5px; font-size: 0.8rem; }
.btn-delete { background: #e53e3e; color: white; padding: 6px 12px; text-decoration: none; border-radius: 5px; font-size: 0.8rem; }
.empty-state { text-align: center; padding: 60px 20px; color: #718096; }
.error-message { background: #fed7d7; color: #c53030; padding: 15px; border-radius: 8px; margin: 15px 0; }
.message { padding: 15px; border-radius: 8px; margin-bottom: 20px; }
.success { background: #c6f6d5; color: #22543d; border: 1px solid #9ae6b4; }
.error { background: #fed7d7; color: #742a2a; border: 1px solid #feb2b2; }
</style>

<?php require_once '../includes/footer.php'; ?>