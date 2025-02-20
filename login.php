<?php
session_start();
include 'db.php'; // Ensure you have a db.php file that contains the correct database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username']; // Get the username from the form
    $password = $_POST['password']; // Get the password from the form

    // Query to fetch the user based on the username
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id']; // Store user ID in session
        header('Location: dashboard.php'); // Redirect to the dashboard after successful login
        exit();
    } 
    else {
        $error = "Invalid username or password!"; // Show error if login fails
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>

    <!-- Login Form -->
    <div class="container">

        <form method="POST" action="login.php" class="form-container" id="loginForm">
            <h2>Login</h2>
             <!-- Error message if login fails -->
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
            
            <!-- Username input for login -->
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" placeholder="Enter your username" required>
            
            <!-- Password input for login -->
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
            
            <!-- Submit button for login -->
            <button type="submit" id="submit_btn">Login</button>

            <br><br>
            <!-- Registration link for new users -->
            <p style="text-align: right;">Don't have an account? <a href="register.php">Register now</a></p>
        </form>
    </div>

    <script>
        // JavaScript for client-side validation
        function validateForm() {
            var username = document.getElementById('username').value;
            var password = document.getElementById('password').value;

            if (username === '' || password === '') {
                alert('All fields are required');
                return false;
            } 
            else {
                alert("Successfully logged in!");
                window.location.href = 'dashboard.php';
                return false;
            }
        }
    </script>
</body>
</html>
