<?php
// attendance/take_attendance.php - JSON VERSION
$page_title = "Take Attendance - University App";
require_once '../includes/header.php';

$today = date('Y-m-d');
$attendance_file = "attendance_$today.json";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (file_exists($attendance_file)) {
        $error = "Attendance for today has already been taken.";
    } else {
        $attendance = [];
        if (file_exists('../students/students.json')) {
            $students = json_decode(file_get_contents('../students/students.json'), true);
            foreach ($students as $student) {
                $student_id = $student['student_id'];
                $status = $_POST["attendance"][$student_id] ?? 'absent';
                $attendance[] = ["student_id" => $student_id, "status" => $status, "name" => $student['name'], "group" => $student['group']];
            }
            $json_data = json_encode($attendance, JSON_PRETTY_PRINT);
            if (file_put_contents($attendance_file, $json_data)) {
                $success = "Attendance saved for $today!"; 
                $attendance_taken = true;
            } else {
                $error = "Error saving attendance";
            }
        } else {
            $error = "No students found";
        }
    }
}

$attendance_taken = file_exists($attendance_file);
$students = [];
if (file_exists('../students/students.json')) {
    $students = json_decode(file_get_contents('../students/students.json'), true);
}
?>

<div class="attendance-container">
    <h1>Take Attendance - <?php echo date('F j, Y'); ?></h1>
    
    <?php if (isset($error)): ?>
        <div class="error-message"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php if (isset($success)): ?>
        <div class="success-message">
            <?php echo $success; ?>
            <div style="margin-top: 15px;">
                <a href="attendance_list.php" class="btn-primary">View Attendance List</a>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($attendance_taken && !isset($_POST['submit'])): ?>
        <div class="info-message">
            <h3>✅ Attendance Already Taken</h3>
            <p>Attendance for today has already been recorded.</p>
            <a href="attendance_list.php" class="btn-primary">View Attendance List</a>
        </div>
    <?php endif; ?>
    
    <?php if (empty($students)): ?>
        <div class="error-message">
            No students found. <a href="../students/add_student.php">Add students first</a>.
        </div>
    <?php else: ?>
        <?php if (!$attendance_taken): ?>
            <form method="POST" class="attendance-form">
                <div class="students-table">
                    <div class="table-header">
                        <div>Student ID</div>
                        <div>Name</div>
                        <div>Group</div>
                        <div>Attendance</div>
                    </div>
                    
                    <?php foreach ($students as $student): ?>
                    <div class="table-row">
                        <div><?php echo $student['student_id']; ?></div>
                        <div><?php echo htmlspecialchars($student['name']); ?></div>
                        <div><?php echo htmlspecialchars($student['group']); ?></div>
                        <div class="attendance-options">
                            <label>
                                <input type="radio" name="attendance[<?php echo $student['student_id']; ?>]" value="present" checked> 
                                <span class="present">✅ Present</span>
                            </label>
                            <label>
                                <input type="radio" name="attendance[<?php echo $student['student_id']; ?>]" value="absent"> 
                                <span class="absent">❌ Absent</span>
                            </label>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn-primary">Save Attendance</button>
                    <a href="../index.php" class="btn-secondary">Cancel</a>
                </div>
            </form>
        <?php endif; ?>
    <?php endif; ?>
</div>

<style>
.attendance-container { max-width: 1000px; margin: 0 auto; }
.students-table { background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.1); margin-bottom: 25px; }
.table-header, .table-row { display: grid; grid-template-columns: 1fr 2fr 1fr 2fr; gap: 20px; padding: 15px 20px; border-bottom: 1px solid #eee; }
.table-header { background: #f8f9fa; font-weight: bold; }
.attendance-options { display: flex; gap: 15px; }
.attendance-options label { cursor: pointer; display: flex; align-items: center; gap: 5px; }
.present { color: #38a169; }
.absent { color: #e53e3e; }
.info-message, .success-message { text-align: center; padding: 30px; background: white; border-radius: 15px; margin: 20px 0; border: 1px solid; }
.info-message { background: #e6fffa; border-color: #38b2ac; }
.success-message { background: #c6f6d5; border-color: #9ae6b4; }
.error-message { background: #fed7d7; color: #742a2a; padding: 15px; border-radius: 8px; margin: 15px 0; border: 1px solid #feb2b2; }
.btn-primary { background: #667eea; color: white; padding: 12px 25px; text-decoration: none; border-radius: 8px; display: inline-block; border: none; cursor: pointer; }
.btn-secondary { background: #a0aec0; color: white; padding: 12px 25px; text-decoration: none; border-radius: 8px; display: inline-block; }
.form-actions { display: flex; gap: 15px; justify-content: center; margin-top: 25px; }
</style>

<?php require_once '../includes/footer.php'; ?>