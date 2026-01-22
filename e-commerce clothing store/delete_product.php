<?php
session_start();


if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    // Not admin, redirect or deny access
    header('Location: index.php');
    exit;
}


include 'db.php';

if (isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);

    // Get image filename to delete the file (optional)
    $query = "SELECT image_url FROM products WHERE id = $product_id";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $image_path = "uploads/" . $row['image_url'];
        if (file_exists($image_path)) {
            unlink($image_path); // delete image file
        }

        // Delete product record
        $delete_query = "DELETE FROM products WHERE id = $product_id";
        $conn->query($delete_query);
    }
}
header('Location: ' . $_SERVER['HTTP_REFERER']); // redirect back
exit;
?>
