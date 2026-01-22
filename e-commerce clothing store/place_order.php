<?php
session_start();

// Your order processing code here

// Clear cart in session
unset($_SESSION['cart']);

// Return success response (if AJAX)
echo json_encode(['success' => true]);
