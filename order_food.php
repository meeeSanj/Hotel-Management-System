<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include 'db.php';

// Fetch the food items from the view
$stmt = $pdo->query("SELECT * FROM view_food_items");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $item = $_POST['item'];
    $quantity = $_POST['quantity'];

    // Fetch the price of the selected item
    $stmt_price = $pdo->prepare("SELECT Price FROM view_food_items WHERE Item = ?");
    $stmt_price->execute([$item]);
    $row = $stmt_price->fetch();
    $price = $row['Price'];  // Get the price of the selected item

    // Calculate the total price
    $total_price = $price * $quantity;

    try {
        // Insert into the orders table
        $stmt_insert = $pdo->prepare("INSERT INTO orders (item, quantity, cost, total_price) VALUES (?, ?, ?, ?)");
        $stmt_insert->execute([$item, $quantity, $price, $total_price]);

        // After successful insertion, provide the success message
        $success_message = "Your order has been placed successfully!";
    } catch (PDOException $e) {
        // Handle database insertion errors
        $error_message = "Error placing the order: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Food</title>
    <link rel="stylesheet" href="services.css">
</head>
<body>
    <a href="logout.php" class="logout-btn">Logout</a>
    <a href="dashboard.php" class="dashboard-btn">Dashboard</a>
    <a href="bill.php" class="bill-btn">Bill</a>

    <div class="order-food-container">
        <div class="order-options-box">
            <form method="POST" action="order_food.php" class="order-food-form">
                <h2 style="font-size: 30px">Order Food</h2>
                <?php
                // Display success or error message if set
                if (isset($success_message)) {
                    echo "<p class='success'>$success_message</p>";
                } elseif (isset($error_message)) {
                    echo "<p class='error'>$error_message</p>";
                }
                ?>
                <br><br>
                
                <label for="item">Select Food Item</label>
                <select name="item" id="item" required>
                    <?php while ($row = $stmt->fetch()) { ?>
                        <option value="<?= $row['Item'] ?>"><?= $row['Item'] ?> - $<?= $row['Price'] ?></option>
                    <?php } ?>
                </select><br><br><br>

                <label for="quantity">Quantity</label>
                <input type="number" name="quantity" id="quantity" placeholder="Quantity" required><br>

                <button type="submit">Order</button>
            </form>
        </div>
    </div>
</body>
</html>
