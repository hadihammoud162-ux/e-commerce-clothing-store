<?php
session_start();

// Check if user is logged in (optional, but recommended)
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Not logged in', 'session' => $_SESSION]);
    exit;
}

// Get product info from POST request
$product = $_POST['product'] ?? null;
$price = $_POST['price'] ?? null;

if (!$product || !$price) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing product or price']);
    exit;
}

// Initialize cart in session if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add product to cart (simple push, you can extend to quantity etc.)
$_SESSION['cart'][] = [
    'product' => $product,
    'price' => floatval($price),
];

// Return success response with current cart count
echo json_encode([
    'success' => true,
    'cartCount' => count($_SESSION['cart']),
        'cartItems' => $_SESSION['cart'],
    'totalPrice' => array_sum(array_column($_SESSION['cart'], 'price')),
]);
