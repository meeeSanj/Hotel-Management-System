<?php
session_start();

// Include the database connection
include 'db.php';

// SQL to truncate the `orders` and `order_deletion_log` tables
$stmt_truncate_orders = $pdo->prepare("TRUNCATE TABLE orders");
$stmt_truncate_logs = $pdo->prepare("TRUNCATE TABLE order_deletion_log");

try {
    // Execute the truncate statements
    $stmt_truncate_orders->execute();
    $stmt_truncate_logs->execute();
} catch (PDOException $e) {
    // Handle any errors that occur during the truncate query
    echo "Error: " . $e->getMessage();
}

// Clear the session
session_unset();
session_destroy();

// Redirect to the login page
header('Location: login.php');
exit();
?>
