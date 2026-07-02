<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
set_time_limit(600);

$host = 'sql213.infinityfree.com';
$db   = 'if0_42306273_posyandu';
$user = 'if0_42306273';
$pass = 'WeHJgzevsNUXbg';
$file = __DIR__ . '/database/db_snapshot.sql';

echo "<h3>Importing Database Snapshot...</h3>";

if (!file_exists($file)) {
    die("<p style='color: red;'>Error: file 'database/db_snapshot.sql' not found. Make sure you ran db-export.bat before deploying.</p>");
}

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("<p style='color: red;'>Connection failed: " . $conn->connect_error . "</p>");
}

$sql = file_get_contents($file);

if ($conn->multi_query($sql)) {
    do {
        if ($result = $conn->store_result()) {
            $result->free();
        }
    } while ($conn->next_result());
    
    echo "<p style='color: green;'>Success! Database snapshot imported successfully.</p>";
} else {
    echo "<p style='color: red;'>Error importing database: " . $conn->error . "</p>";
}
$conn->close();
?>
