<?php
$host = 'localhost'; // Your DB host
$dbname = 'project'; // Your DB name
$username = 'root'; // Your DB username
$password = 'root'; // Your DB password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>
