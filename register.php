<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if password is at least 6 characters long (already validated in the procedure)
    // if (strlen($password) < 6) {
    //     $error = "Password must be at least 6 characters long!";
    // } else {
        try {
            // Call the stored procedure to validate the user input
            $stmt = $pdo->prepare("CALL proc_validate_user_input(?, ?, ?)");
            $stmt->execute([$username, $email, $password]);

            // Proceed to insert the user if validation is successful
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$username, $email, $hashed_password]);

            // Success message
            $success = "Registration successful! <a href='login.php'>Click here to login</a>";
        } catch (PDOException $e) {
            // Catch SQL errors from the procedure or the insert
            $error = $e->getMessage();
        // }
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="login.css"> <!-- Same CSS file as login -->
</head>
<body>

    <!-- Registration Form -->
    <div class="container">
        <form method="POST" action="register.php" class="form-container" id="registerForm">
            <h2>Register</h2>

            <!-- Error message for email already registered -->
            <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>

            <!-- Username input for registration -->
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" placeholder="Enter your username" required>

            <!-- Email input for registration -->
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>

            <!-- Password input for registration -->
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>

            <!-- Submit button for registration -->
            <button type="submit">Register</button>

            <br><br>
             <!-- Already have an account? -->
            <p>Already have an account? <a href="login.php">Login here</a></p>

            <!-- Success message -->
            <?php if (isset($success)) { echo "<p class='success'>$success</p>"; } ?>
        </form>
    </div>

</body>
</html>
