<?php
session_start();
include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $item = $_POST['item'];
    $quantity = $_POST['quantity']; // Get quantity from the form

    // Validate that the quantity is a positive integer
    if (!is_numeric($quantity) || $quantity <= 0) {
        $message = "<p class='error'>Please enter a valid quantity greater than 0.</p>";
    } else {
        // Get item price from the database
        $stmt_price = $pdo->prepare("SELECT Price FROM laundry_services WHERE Item = ?");
        if ($stmt_price->execute([$item])) {
            $price = $stmt_price->fetchColumn();

            if ($price) {
                // Calculate total price
                $total_price = $price * $quantity;

                // Insert into orders table
                $stmt_insert = $pdo->prepare("INSERT INTO orders (item, quantity, cost, total_price) VALUES (?, ?, ?, ?)");
                if ($stmt_insert->execute([$item, $quantity, $price, $total_price])) {
                    $message = "<p class='success'>Laundry service requested successfully!</p>";
                } else {
                    $message = "<p class='error'>Failed to request laundry service. Please try again.</p>";
                }
            } else {
                $message = "<p class='error'>Item price not found.</p>";
            }
        } else {
            $message = "<p class='error'>Failed to fetch item price. Please try again.</p>";
        }
    }
}

// Fetch laundry services for the dropdown
$stmt = $pdo->query("SELECT * FROM laundry_services");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laundry Service Request</title>
    <link rel="stylesheet" href="services.css">
</head>
<body>
    <a href="logout.php" class="logout-btn">Logout</a>
    <a href="dashboard.php" class="dashboard-btn">Dashboard</a>
    <a href="bill.php" class="bill-btn">Bill</a>

    <!-- Laundry Services Form -->
    <div class="form-container">
        <form method="POST" action="laundry.php">
            <h2 style="font-size: 25px">Laundry Service</h2>
            <?= $message ?>
            <br>
            <br>

            <!-- Display success/error message -->

            <div class="form-group">
                <label for="item">Select Laundry Item</label>
                <select name="item" id="item" required>
                    <?php while ($row = $stmt->fetch()) { ?>
                        <option value="<?= htmlspecialchars($row['Item']) ?>"><?= htmlspecialchars($row['Item']) ?> - $<?= number_format($row['Price'], 2) ?></option>
                    <?php } ?>
                </select>
            </div>
            <br>

            <div class="form-group">
                <label for="quantity">Number of Items</label>
                <input type="number" id="quantity" name="quantity" min="1" value="1" required>
            </div>
            <br>

            <button type="submit">Request Laundry Service</button>
        </form>
    </div>
</body>
</html>
