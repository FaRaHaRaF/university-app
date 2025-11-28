<?php
// attendance/attendance_list.php - JSON VERSION
$page_title = "Attendance List - University App";
require_once '../includes/header.php';

// Get all attendance JSON files
$attendance_files = glob("attendance_*.json");
$attendance_data = [];

// Read all attendance files
foreach ($attendance_files as $file) {
    // Extract date from filename (attendance_2023-11-28.json -> 2023-11-28)
    $date = str_replace(['attendance_', '.json'], '', $file);
    
    if (file_exists($file)) {
        $day_attendance = json_decode(file_get_contents($file), true);
        $attendance_data[$date] = $day_attendance;
    }
}

// Sort by date (newest first)
krsort($attendance_data);
?>

<div class="attendance-list-container">
    <h2>Attendance Records</h2>
    
    <?php if (empty($attendance_files)): ?>
        <div class="empty-state">
            <h3>No Attendance Records</h3>
            <p>No attendance has been taken yet.</p>
            <a href="take_attendance.php" class="btn-primary">Take Attendance</a>
        </div>
    <?php else: ?>
        <?php foreach ($attendance_data as $date => $day_records): ?>
        <div class="attendance-day">
            <h3 class="day-header">
                <?php echo date('F j, Y', strtotime($date)); ?>
                <span class="record-count">(<?php echo count($day_records); ?> students)</span>
            </h3>
            
            <div class="attendance-table">
                <div class="table-header">
                    <div>Student ID</div>
                    <div>Name</div>
                    <div>Group</div>
                    <div>Status</div>
                </div>
                
                <?php foreach ($day_records as $record): ?>
                <div class="table-row">
                    <div><?php echo $record['student_id']; ?></div>
                    <div><?php echo htmlspecialchars($record['name']); ?></div>
                    <div><?php echo htmlspecialchars($record['group']); ?></div>
                    <div>
                        <span class="status-badge <?php echo $record['status']; ?>">
                            <?php echo ucfirst($record['status']); ?>
                        </span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <?php
            // Calculate stats for this day
            $present_count = array_filter($day_records, function($record) {
                return $record['status'] === 'present';
            });
            $present_count = count($present_count);
            $absent_count = count($day_records) - $present_count;
            ?>
            
            <div class="day-stats">
                <strong>Summary:</strong> 
                <span class="present-stat"><?php echo $present_count; ?> Present</span> • 
                <span class="absent-stat"><?php echo $absent_count; ?> Absent</span> • 
                <span class="total-stat"><?php echo count($day_records); ?> Total</span>
            </div>
        </div>
        <?php endforeach; ?>
        
        <div class="summary">
            <p><strong>Total Days: <?php echo count($attendance_data); ?></strong></p>
        </div>
    <?php endif; ?>
    
    <div class="actions">
        <a href="take_attendance.php" class="btn-primary">Take New Attendance</a>
        <a href="../index.php" class="btn-secondary">Back to Home</a>
    </div>
</div>

<style>
.attendance-list-container { max-width: 1000px; margin: 0 auto; }
.attendance-day { 
    background: white; 
    border-radius: 15px; 
    padding: 25px; 
    margin-bottom: 30px; 
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    border-left: 5px solid #667eea;
}
.day-header { 
    color: #2d3748; 
    margin-bottom: 20px; 
    padding-bottom: 10px;
    border-bottom: 2px solid #e2e8f0;
}
.record-count { 
    font-size: 1rem; 
    color: #718096; 
    font-weight: normal;
}
.attendance-table { 
    border-radius: 10px; 
    overflow: hidden; 
    margin-bottom: 15px;
    border: 1px solid #e2e8f0;
}
.table-header, .table-row { 
    display: grid; 
    grid-template-columns: 1fr 2fr 1fr 1fr; 
    gap: 20px; 
    padding: 15px 20px; 
    border-bottom: 1px solid #e2e8f0; 
}
.table-header { 
    background: #f8f9fa; 
    font-weight: bold; 
    color: #2d3748;
}
.table-row:hover {
    background: #f7fafc;
}
.status-badge { 
    padding: 6px 12px; 
    border-radius: 20px; 
    font-size: 0.8rem; 
    font-weight: 600; 
}
.present { 
    background: #c6f6d5; 
    color: #22543d; 
}
.absent { 
    background: #fed7d7; 
    color: #742a2a; 
}
.day-stats { 
    background: #edf2f7; 
    padding: 12px 20px; 
    border-radius: 8px; 
    font-size: 0.9rem;
}
.present-stat { color: #22543d; font-weight: 600; }
.absent-stat { color: #742a2a; font-weight: 600; }
.total-stat { color: #2d3748; font-weight: 600; }
.summary { 
    text-align: center; 
    background: #667eea; 
    color: white; 
    padding: 15px; 
    border-radius: 10px; 
    margin: 20px 0; 
}
.actions { 
    display: flex; 
    gap: 15px; 
    justify-content: center; 
    margin-top: 30px;
}
.btn-primary { 
    background: #667eea; 
    color: white; 
    padding: 12px 25px; 
    text-decoration: none; 
    border-radius: 8px; 
    font-weight: 600;
}
.btn-secondary { 
    background: #a0aec0; 
    color: white; 
    padding: 12px 25px; 
    text-decoration: none; 
    border-radius: 8px; 
    font-weight: 600;
}
.empty-state { 
    text-align: center; 
    padding: 60px 20px; 
    background: white; 
    border-radius: 15px; 
    color: #718096;
}
</style>

<?php require_once '../includes/footer.php'; ?>