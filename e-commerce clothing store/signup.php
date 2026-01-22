<?php
define('db_server', 'localhost');
define('db_user', 'root');
define('db_password', 'Hadi1234@@1234');
define('db_DBname', 'clothing_store');

$con = mysqli_connect(db_server, db_user, db_password, db_DBname);
if (!$con) {
    die('Failed. ' . mysqli_connect_error());
}

$email = $_POST['email'];
$password = $_POST['psw'];

$sql = "INSERT INTO users (email, password) VALUES ('$email', '$password')";

if (mysqli_query($con, $sql)) {
    header('Location: index.php');
} else {
    echo 'Error! ' . mysqli_error($con);
}

mysqli_close($con);
// Assuming $username, $email, and $password are taken from the signup form
$role = 'user'; // Default to 'user'

// Check if the email is for admin
if (strpos($email, 'admin@') !== false) {
    $role = 'admin'; // Set the role to admin if the email matches the admin pattern
}

// Insert user into the database
$query = "INSERT INTO users (username, email, password, role1) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param('ssss', $username, $email, password_hash($password, PASSWORD_DEFAULT), $role);
$stmt->execute();





?>
