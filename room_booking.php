<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $room_type = $_POST['room_type'];
    $num_days = $_POST['num_days']; // Retrieve the number of days from form input

    // Get room price from the view
    $stmt = $pdo->prepare("SELECT Price_per_day FROM view_available_rooms WHERE Room = ?");
    $stmt->execute([$room_type]);
    $price = $stmt->fetchColumn();

    // Quantity is now the number of days booked
    $quantity = $num_days;  // Use the number of days as the quantity
    $total_price = $quantity * $price;  // Calculate total price based on the number of days

    // Insert into orders table
    $stmt_insert = $pdo->prepare("INSERT INTO orders (item, quantity, cost, total_price) VALUES (?, ?, ?, ?)");
    $stmt_insert->execute([$room_type, $quantity, $price, $total_price]);

    // Call the stored procedure to update room availability
    $stmt_proc = $pdo->prepare("CALL proc_update_availability(?)");
    $stmt_proc->execute([$room_type]);
    
    $error = "Room booked successfully for $num_days days!";
}

// Query available rooms
$stmt = $pdo->query("SELECT * FROM view_available_rooms");
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Booking</title>
    <link rel="stylesheet" href="services.css">
</head>
<body>
    <a href="logout.php" class="logout-btn">Logout</a>
    <a href="dashboard.php" class="dashboard-btn">Dashboard</a>
    <a href="bill.php" class="bill-btn">Bill</a>

    <div class="dashboard-container">
        <h2>Room Booking</h2>
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        <!-- Room Booking Form -->
        <form method="POST" action="room_booking.php">
    <div class="form-group">
        <label for="room_type">Select Room</label>
        <select name="room_type" id="room_type" required>
            <?php while ($row = $stmt->fetch()) { ?>
                <option value="<?= htmlspecialchars($row['Room']) ?>"><?= htmlspecialchars($row['Room']) ?> - $<?= htmlspecialchars($row['Price_per_day']) ?> per night</option>
            <?php } ?>
        </select>
    </div>

    <div class="form-group">
        <label for="num_days">Number of Days</label>
        <input type="number" name="num_days" id="num_days" min="1" required>
    </div>

    <button type="submit">Book Room</button>
</form>

    </div>
</body>
</html>
