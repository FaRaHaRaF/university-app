<?php
// database/test_connection.php - Exercise 3
$page_title = "Test Database Connection - University App";
require_once '../includes/header.php';
?>

<div class="test-container">
    <h2>Database Connection Test</h2>
    
    <?php
    try {
        require_once '../config/db_connect.php';
        $pdo = getDBConnection();
        
        echo '<div class="success-message">';
        echo '✅ <strong>Connection successful!</strong><br>';
        echo 'Connected to database: ' . DB_NAME . '<br>';
        
        // Test query
        $stmt = $pdo->query("SELECT VERSION() as version");
        $version = $stmt->fetch();
        echo 'MySQL Version: ' . $version['version'] . '<br>';
        
        // Check tables
        $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        echo 'Tables found: ' . count($tables) . '<br>';
        
        if (!empty($tables)) {
            echo '<div style="margin-top: 10px;">';
            foreach ($tables as $table) {
                echo '• ' . $table . '<br>';
            }
            echo '</div>';
        }
        
        echo '</div>';
        
    } catch (Exception $e) {
        echo '<div class="error-message">';
        echo '❌ <strong>Connection failed!</strong><br>';
        echo 'Error: ' . $e->getMessage() . '<br><br>';
        
        echo '<strong>Troubleshooting:</strong><br>';
        echo '1. Make sure WAMP is running (green icon)<br>';
        echo '2. Check database name in config.php<br>';
        echo '3. Verify username/password in config.php<br>';
        echo '4. Create database in phpMyAdmin if missing';
        echo '</div>';
    }
    ?>
    
    <div style="margin-top: 20px;">
        <a href="../index.php" class="btn-primary">Back to Home</a>
    </div>
</div>

<style>
.test-container { max-width: 800px; margin: 0 auto; }
.success-message { 
    background: #c6f6d5; 
    color: #22543d; 
    padding: 20px; 
    border-radius: 10px; 
    border: 1px solid #9ae6b4;
    margin: 20px 0;
}
.error-message { 
    background: #fed7d7; 
    color: #742a2a; 
    padding: 20px; 
    border-radius: 10px; 
    border: 1px solid #feb2b2;
    margin: 20px 0;
}
.btn-primary { 
    background: #667eea; 
    color: white; 
    padding: 12px 25px; 
    text-decoration: none; 
    border-radius: 8px; 
    display: inline-block;
}
</style>

<?php require_once '../includes/footer.php'; ?>