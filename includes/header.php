<?php
// includes/header.php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'University App'; ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            padding: 0;
        }
        
        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 25px;
        }
        
        .logo a {
            display: flex;
            align-items: center;
            gap: 15px;
            text-decoration: none;
            color: white;
            padding: 18px 0;
        }
        
        .logo-icon { font-size: 2rem; }
        .logo-text { font-size: 1.5rem; font-weight: bold; }
        .logo-subtitle { font-size: 0.85rem; opacity: 0.9; }
        
        .nav-menu {
            display: flex;
            list-style: none;
            gap: 8px;
        }
        
        .nav-link {
            display: block;
            padding: 15px 22px;
            text-decoration: none;
            color: white;
            font-weight: 500;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        
        .nav-link:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
        }
        
        .nav-link.active {
            background: rgba(255, 255, 255, 0.25);
        }
        
        .main-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 25px;
        }
        
        .content-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        
        .page-title {
            color: #2d3748;
            font-size: 2.2rem;
            margin-bottom: 10px;
        }
        
        .page-subtitle {
            color: #718096;
            font-size: 1.1rem;
            margin-bottom: 30px;
            border-left: 4px solid #667eea;
            padding-left: 15px;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="nav-container">
            <div class="logo">
                <a href="index.php">
                    <div class="logo-icon">🏫</div>
                    <div>
                        <div class="logo-text">University App</div>
                        <div class="logo-subtitle">Advanced Web Programming</div>
                    </div>
                </a>
            </div>
            
<nav>
    <ul class="nav-menu">
        <li><a href="/university_app/index.php" class="nav-link <?php echo $current_page == 'index.php' ? 'active' : ''; ?>">🏠 Home</a></li>
        <li><a href="/university_app/students/list_students.php" class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'students/') !== false ? 'active' : ''; ?>">👨‍🎓 Students</a></li>
        <li><a href="/university_app/attendance/take_attendance.php" class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'attendance/') !== false ? 'active' : ''; ?>">✅ Attendance</a></li>
        <li><a href="/university_app/sessions/create_session.php" class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'sessions/') !== false ? 'active' : ''; ?>">🎯 Sessions</a></li>
        <li><a href="/university_app/database/test_connection.php" class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'database/') !== false ? 'active' : ''; ?>">🗄️ Database</a></li>
    </ul>
</nav>
        </div>
    </header>

    <div class="main-container">
        <div class="content-card">
            <?php if (isset($page_title) && $current_page !== 'index.php'): ?>
                <h1 class="page-title"><?php echo $page_title; ?></h1>
                <div class="page-subtitle">
                    <?php 
                    if (strpos($_SERVER['PHP_SELF'], 'students/') !== false) echo "Student Management System";
                    elseif (strpos($_SERVER['PHP_SELF'], 'attendance/') !== false) echo "Attendance Tracking System";
                    elseif (strpos($_SERVER['PHP_SELF'], 'sessions/') !== false) echo "Session Management System";
                    else echo "University Management System";
                    ?>
                </div>
            <?php endif; ?>