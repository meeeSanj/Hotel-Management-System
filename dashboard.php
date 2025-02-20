<?php
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include 'db.php';

// Create the orders table if it does not exist
$stmt_create_table = $pdo->prepare(" 
    CREATE TABLE IF NOT EXISTS orders (
        order_id INT AUTO_INCREMENT PRIMARY KEY,
        item VARCHAR(35), 
        quantity INT,
        cost INT, 
        total_price INT 
    );
");
$stmt_create_table->execute();

// Create order_deletion_log
$stmt_create_table_deletion = $pdo->prepare(" 
    CREATE TABLE IF NOT EXISTS order_deletion_log (
        log_id INT AUTO_INCREMENT PRIMARY KEY,
        order_id INT,
        item VARCHAR(35),
        quantity INT,
        cost INT,
        total_price INT,
        deleted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
");
$stmt_create_table_deletion->execute();

// Check for actions (food, room, or laundry)
if (isset($_GET['action'])) {
    // Handle room booking, food order, or laundry services
    $action = $_GET['action'];
    $stmt = $pdo->prepare("INSERT INTO orders (item, quantity, cost, total_price) VALUES (?, ?, ?, ?)");
    
    // Sample logic for ordering food
    if ($action == 'food') {
        // Example food order
        $item = 'Grilled Chicken Salad';
        $quantity = 2;
        $cost = 10; // Fetch the actual cost from the database if needed
        $total_price = $quantity * $cost;
        $stmt->execute([$item, $quantity, $cost, $total_price]);
    }

    // Redirect to the dashboard page
    header('Location: dashboard.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="login.css"> <!-- Reuse the same styles as login -->
</head>
<body>
    <a href="logout.php" class="logout-btn">Logout</a>
    <a href="bill.php" class="bill-btn">Bill</a>
    
    <div class="dashboard-container">
        <h2>Welcome to the Dashboard</h2>
        
        <!-- Box for service options -->
        <div class="options-box">
            <div class="service-options">
                <a href="room_booking.php" class="service-btn">Book a Room</a>
                <a href="order_food.php" class="service-btn">Order Food</a>
                <a href="laundry.php" class="service-btn">Laundry Services</a>
            </div>
        </div>
    </div>
</body>
</html>
