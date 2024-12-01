<?php
// Connection parameters for the original and archive databases
$sourceHost = 'localhost';
$sourceDb = 'datatables_demo';
$archiveHost = 'localhost';
$archiveDb = 'archive_db';
$username = 'root';
$password = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $userId = $_POST['id'];

    try {
        // Connect to the source database
        $sourcePdo = new PDO("mysql:host=$sourceHost;dbname=$sourceDb", $username, $password);
        $sourcePdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Fetch the user's data from the source database
        $stmt = $sourcePdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Connect to the archive database
            $archivePdo = new PDO("mysql:host=$archiveHost;dbname=$archiveDb", $username, $password);
            $archivePdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Insert the user into the archive database
            $insertStmt = $archivePdo->prepare("
                INSERT INTO users (id, first_name, last_name, email, created_at)
                VALUES (:id, :first_name, :last_name, :email, :created_at)
            ");
            $insertStmt->execute([
                'id' => $user['id'],
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'email' => $user['email'],
                'created_at' => $user['created_at']
            ]);

            // Delete the user from the source database
            $deleteStmt = $sourcePdo->prepare("DELETE FROM users WHERE id = :id");
            $deleteStmt->execute(['id' => $userId]);
        }

        // Redirect back to index.php
        header("Location: index.php");
        exit;
    } catch (PDOException $e) {
        // Handle errors
        die("Error: " . $e->getMessage());
    }
} else {
    // Redirect if no ID provided
    header("Location: index.php");
    exit;
}
?>
