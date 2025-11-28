<?php
// students/delete_student.php
require_once '../config/db_connect.php';

if (!isset($_GET['id'])) {
    header('Location: list_students.php');
    exit;
}

$student_id = $_GET['id'];
$message = ''; $message_type = '';

try {
    $pdo = getDBConnection();
    
    // Check if student exists
    $stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
    $stmt->execute([$student_id]);
    $student = $stmt->fetch();
    
    if (!$student) {
        throw new Exception("Student not found");
    }
    
    // Delete student
    $stmt = $pdo->prepare("DELETE FROM students WHERE id = ?");
    $stmt->execute([$student_id]);
    
    $message = "✅ Student deleted successfully!";
    $message_type = "success";
    
} catch (Exception $e) {
    $message = "❌ " . $e->getMessage();
    $message_type = "error";
}

// Redirect back to list with message
header("Location: list_students.php?message=" . urlencode($message) . "&type=" . urlencode($message_type));
exit;