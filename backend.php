<?php
// Connection parameters
$host = 'localhost';
$dbname = 'datatables_demo';
$username = 'root';
$password = '';

// Initialize users array
$users = [];

try {
    // Database connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch data
    $sql = "SELECT * FROM users";
    $stmt = $pdo->query($sql);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Log error and show user-friendly message
    error_log("Database Error: " . $e->getMessage());
    echo "<div style='color: red; font-weight: bold;'>Unable to fetch users. Please try again later.</div>";
}
?>
