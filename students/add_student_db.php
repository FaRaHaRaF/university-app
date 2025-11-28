<?php
$page_title = "Add Student (Database) - University App";
require_once '../includes/header.php';
require_once '../config/db_connect.php';

$message = ''; $message_type = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname'] ?? '');
    $matricule = trim($_POST['matricule'] ?? '');
    $group_id = trim($_POST['group_id'] ?? '');
    
    try {
        $pdo = getDBConnection();
        if (empty($fullname) || empty($matricule) || empty($group_id)) {
            throw new Exception("All fields are required");
        }
        $stmt = $pdo->prepare("SELECT id FROM students WHERE matricule = ?");
        $stmt->execute([$matricule]);
        if ($stmt->fetch()) throw new Exception("Matricule $matricule already exists");
        
        $stmt = $pdo->prepare("INSERT INTO students (fullname, matricule, group_id) VALUES (?, ?, ?)");
        $stmt->execute([$fullname, $matricule, $group_id]);
        $message = "Student added successfully!"; $message_type = "success";
    } catch (Exception $e) {
        $message = $e->getMessage(); $message_type = "error";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Add Student (Database)</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: inline-block; width: 100px; }
        input { padding: 5px; width: 200px; }
        .error { color: red; } .success { color: green; }
        button { padding: 10px 20px; background: #28a745; color: white; border: none; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>Add Student (Database)</h1>
    <?php if ($message): ?><div class="<?php echo $message_type; ?>"><?php echo $message; ?></div><?php endif; ?>
    <form method="POST">
        <div class="form-group"><label>Full Name:</label><input type="text" name="fullname" required></div>
        <div class="form-group"><label>Matricule:</label><input type="text" name="matricule" required></div>
        <div class="form-group"><label>Group ID:</label><input type="text" name="group_id" required></div>
        <button type="submit">Add Student</button>
    </form>
    <p><a href="list_students.php">View Students</a> | <a href="../index.php">Main Menu</a></p>
</body>
</html>

<?php require_once '../includes/footer.php'; ?>