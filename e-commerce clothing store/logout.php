<?php
session_start();

unset($_SESSION['uname']);
unset($_SESSION['isLoggedIn']); // Unset the session variable

session_destroy();

header('Location: index.php');
exit();
?>
