<?php
$host = 'localhost';
$db = 'Clothing_store';
$user = 'root';
$pass = 'Hadi1234@@1234';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
