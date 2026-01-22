<?php
session_start();
echo "<pre>";
print_r($_SESSION);
echo "</pre>";


if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}


$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
    echo "Your cart is empty.";
    exit;
}

// Here you would process the order: save it to DB, reduce stock, etc.
// For now, just display the cart:

echo "<h1>Your Cart</h1><ul>";
$total = 0;
foreach ($cart as $item) {
    echo "<li>" . htmlspecialchars($item['product']) . " - $" . number_format($item['price'], 2) . "</li>";
    $total += $item['price'];
}
echo "</ul>";
echo "<p><strong>Total: $" . number_format($total, 2) . "</strong></p>";

// Optionally, add a form/button to confirm purchase, clear cart, etc.
