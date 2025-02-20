<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle the remove action
    if (isset($_POST['remove'])) {
        $order_id = $_POST['order_id'];

        // Remove the order based on order_id
        $stmt = $pdo->prepare("DELETE FROM orders WHERE order_id = ?");
        $stmt->execute([$order_id]);
    }
}

// Query to select all orders
$stmt = $pdo->query("SELECT * FROM orders");

// Fetch all orders from the database
$orders = $stmt->fetchAll();

// Calculate the total price of all orders
$total_bill = 0;
foreach ($orders as $row) {
    $total_bill += $row['total_price'];
}

// Query to select all removed items from the order_deletion_log
$stmt_deleted = $pdo->query("SELECT * FROM order_deletion_log ORDER BY deleted_at DESC");

// Fetch all deleted orders
$deleted_orders = $stmt_deleted->fetchAll();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bill</title>
    <link rel="stylesheet" href="bill.css">
</head>
<body>
    <a href="logout.php" class="logout-btn">Logout</a>
    <a href="dashboard.php" class="dashboard-btn">Dashboard</a>

    <!-- Main container for stacking cart and removed list -->
    <div class="main-container">
       <!-- Cart Section -->
<form method="POST" action="bill.php">
    <?php if (empty($orders)) { ?>
        <div class="no-items-container">
            <div class="no-items-box">
                <div class="no-items-icon">
                    <!-- Cart Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a1 1 0 0 0 1 .61h9.72a1 1 0 0 0 .95-.68l3.38-10.05H6"></path>
                    </svg>
                </div>
                <p style="text-align: center">No items in cart</p>
            </div>
        </div>
    <?php } else { ?>
        <div class="bill-box">
            <h1 style="text-align: center">Cart</h1>
            <table>
                <tr>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($orders as $row) { ?>
                    <tr>
                        <td><?= htmlspecialchars($row['item']) ?></td>
                        <td>
                            <!-- Display quantity as plain text -->
                            <?= htmlspecialchars($row['quantity']) ?>
                        </td>
                        <td><?= htmlspecialchars($row['total_price']) ?></td>
                        <td>
                            <!-- Hidden field for order ID -->
                            <input type="hidden" name="order_id" value="<?= htmlspecialchars($row['order_id']) ?>">
                            <!-- Remove button -->
                            <button type="submit" name="remove" class="remove-btn">Remove</button>
                        </td>
                    </tr>
                <?php } ?>
            </table>
            <div class="total-price">
                <h3>Total Price: $<?= number_format($total_bill, 2) ?></h3>
            </div>
        </div>
    <?php } ?>
</form>


        <!-- Removed Items Section -->
        <div class="removed-items-box">
            <h2>Removed Items</h2>
            <?php if (empty($deleted_orders)) { ?>
                <p>No removed items</p>
            <?php } else { ?>
                <table>
                    <tr>
                        <th>Item</th>
                        <th>Quantity</th>
                        <th>Total Price</th>
                        <th>Deleted At</th>
                    </tr>
                    <?php foreach ($deleted_orders as $row) { ?>
                        <tr>
                            <td><?= htmlspecialchars($row['item']) ?></td>
                            <td><?= htmlspecialchars($row['quantity']) ?></td>
                            <td><?= htmlspecialchars($row['total_price']) ?></td>
                            <td><?= htmlspecialchars($row['deleted_at']) ?></td>
                        </tr>
                    <?php } ?>
                </table>
            <?php } ?>
        </div>
    </div>
</body>
</html>
