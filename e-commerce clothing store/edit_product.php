<?php
session_start();
include 'db.php'; // Your DB connection, should set $conn

if (!isset($_GET['product_id'])) {
    echo "<p style='color:red;'>No product ID specified.</p>";
    exit;
}

$product_id = (int)$_GET['product_id'];
$error = '';
$success = '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? '';
    $category_id = $_POST['category_id'] ?? '';
    $subcategory_id = $_POST['subcategory_id'] ?? '';

    if (empty($name) || empty($price) || empty($category_id) || empty($subcategory_id)) {
        $error = "Please fill in all required fields.";
    } elseif (!is_numeric($price)) {
        $error = "Price must be a valid number.";
    } else {
        // Handle image upload if a new file is uploaded
        $image_name = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $image_name = basename($_FILES['image']['name']);
            $tmp_name = $_FILES['image']['tmp_name'];
            $upload_dir = "uploads/";
            $target_file = $upload_dir . $image_name;

            if (!move_uploaded_file($tmp_name, $target_file)) {
                $error = "Failed to upload image.";
            }
        }

        if (!$error) {
            // Build SQL for update
            if ($image_name) {
                $stmt = $conn->prepare("UPDATE products SET name=?, description=?, price=?, image_url=?, category_id=?, subcategory_id=? WHERE id=?");
                $stmt->bind_param("ssdssii", $name, $description, $price, $image_name, $category_id, $subcategory_id, $product_id);
            } else {
                $stmt = $conn->prepare("UPDATE products SET name=?, description=?, price=?, category_id=?, subcategory_id=? WHERE id=?");
                $stmt->bind_param("ssdiii", $name, $description, $price, $category_id, $subcategory_id, $product_id);
            }

        if ($stmt->execute()) {
    // Redirect to the same edit page with a success message
    header("Location: index.php?product_id=$product_id&updated=1");
    
    exit;
} else {
    $error = "Error updating product: " . $stmt->error;
}

            $stmt->close();
        }
    }
}

// Fetch existing product data to prefill form
$stmt = $conn->prepare("SELECT * FROM products WHERE id=?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<p style='color:red;'>Product not found.</p>";
    exit;
}

$product = $result->fetch_assoc();
$stmt->close();

// Fetch categories for dropdown
$categories = [];
$catResult = $conn->query("SELECT category_id, name FROM categories");
if ($catResult) {
    while ($row = $catResult->fetch_assoc()) {
        $categories[] = $row;
    }
}

// Fetch subcategories for dropdown
$subcategories = [];
$subcatResult = $conn->query("SELECT subcategory_id, subcategory_name FROM subcategories");
if ($subcatResult) {
    while ($row = $subcatResult->fetch_assoc()) {
        $subcategories[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Edit Product</title>
    <style>
        body {
            background: #f9f9f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 30px;
        }
        form {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        input[type="text"], input[type="number"], textarea, select, input[type="file"] {
            width: 100%;
            padding: 10px 14px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 1rem;
        }
        textarea { resize: vertical; }
        button {
            padding: 12px 20px;
            background-color: #007bff;
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 1rem;
            cursor: pointer;
            width: 100%;
            margin-top: 15px;
        }
        button:hover { background-color: #0056b3; }
        .success { color: green; font-weight: bold; margin-bottom: 15px; }
        .error { color: red; font-weight: bold; margin-bottom: 15px; }
        img { max-width: 150px; margin-top: 10px; border-radius: 8px; }
    </style>
</head>
<body>

<h2>Edit Product</h2>

<?php if ($error): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php if ($success): ?>
    <p class="success"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <label>Product Name *</label>
    <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required />

    <label>Description</label>
    <textarea name="description"><?= htmlspecialchars($product['description']) ?></textarea>

    <label>Price *</label>
    <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($product['price']) ?>" required />

    <label>Category *</label>
    <select name="category_id" required>
        <option value="">Select Category</option>
        <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['category_id'] ?>" <?= $cat['category_id'] == $product['category_id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Subcategory *</label>
    <select name="subcategory_id" required>
        <option value="">Select Subcategory</option>
        <?php foreach ($subcategories as $subcat): ?>
            <option value="<?= $subcat['subcategory_id'] ?>" <?= $subcat['subcategory_id'] == $product['subcategory_id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($subcat['subcategory_name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Current Image</label><br />
    <img src="uploads/<?= htmlspecialchars($product['image_url']) ?>" alt="Product Image" />

    <label>Change Image</label>
    <input type="file" name="image" accept="image/*" />

    <button type="submit">Update Product</button>
</form>

</body>
</html>
