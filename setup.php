<?php
$page_title = "Setup Helper - University App";
require_once 'includes/header.php';
if ($_POST['create_files'] ?? false) {
    $files_to_create = [
        'students/add_student.php' => '<?php echo "<h3>Add Student (JSON) - PLACEHOLDER</h3><p>File created! Replace with actual code.</p>"; ?>',
        'attendance/take_attendance.php' => '<?php echo "<h3>Take Attendance - PLACEHOLDER</h3><p>File created! Replace with actual code.</p>"; ?>',
        'students/add_student_db.php' => '<?php echo "<h3>Add Student (Database) - PLACEHOLDER</h3><p>File created! Replace with actual code.</p>"; ?>',
        'students/list_students.php' => '<?php echo "<h3>List Students - PLACEHOLDER</h3><p>File created! Replace with actual code.</p>"; ?>',
        'attendance/create_session.php' => '<?php echo "<h3>Create Session - PLACEHOLDER</h3><p>File created! Replace with actual code.</p>"; ?>',
        'attendance/close_session.php' => '<?php echo "<h3>Close Session - PLACEHOLDER</h3><p>File created! Replace with actual code.</p>"; ?>',
    ];

    $created = [];
    foreach ($files_to_create as $file => $content) {
        if (!file_exists($file)) {
            // Create directory if needed
            $dir = dirname($file);
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
            file_put_contents($file, $content);
            $created[] = $file;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Setup Helper</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f0f8ff; }
        .container { background: white; padding: 30px; border-radius: 15px; max-width: 800px; margin: 0 auto; }
        button { background: #4CAF50; color: white; padding: 15px 30px; border: none; border-radius: 8px; cursor: pointer; font-size: 16px; }
        button:hover { background: #45a049; }
        .success { color: #4CAF50; padding: 10px; background: #e8f5e8; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🛠️ Setup Helper</h1>
        
        <?php if (isset($created)): ?>
            <div class="success">
                <h3>✅ Created files:</h3>
                <ul>
                    <?php foreach ($created as $file): ?>
                        <li><?php echo $file; ?></li>
                    <?php endforeach; ?>
                </ul>
                <p><a href="../index.php">Return to Main Menu</a> to see the links!</p>
            </div>
        <?php endif; ?>

        <h2>Missing Files:</h2>
        <ul>
            <?php
            $files = [
                'students/add_student.php',
                'attendance/take_attendance.php', 
                'database/test_connection.php',
                'students/add_student_db.php',
                'students/list_students.php',
                'attendance/create_session.php',
                'attendance/close_session.php'
            ];
            
            foreach ($files as $file) {
                $exists = file_exists($file);
                echo "<li>" . ($exists ? "✅" : "❌") . " $file</li>";
            }
            ?>
        </ul>

        <form method="POST">
            <p>Click below to create placeholder files for all missing exercises:</p>
            <button type="submit" name="create_files" value="1">Create Missing Files</button>
        </form>

        <p><a href="index.php">← Back to Main Menu</a></p>
    </div>
</body>
</html><?php require_once 'includes/footer.php'; ?>