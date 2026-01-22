<?php







/*include 'db.php'; // Your DB connection (make sure $conn is valid)

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Basic validation
    $product_name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? '';
    $category_id = $_POST['category_id'] ?? '';
    $subcategory_id = $_POST['subcategory_id'] ?? '';

    if (empty($product_name) || empty($subcategory_id) || empty($price) || empty($category_id)) {
        echo "❌ Please fill in all required fields.";
        exit;
    }

    if (!is_numeric($price) || !is_numeric($subcategory_id) || !is_numeric($category_id)) {
        echo "❌ Invalid input.";
        exit;
    }

    // Handle image upload
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== 0) {
        echo "❌ Please upload a valid product image.";
        exit;
    }

    $image_name = basename($_FILES['image']['name']);
    $tmp_name = $_FILES['image']['tmp_name'];
    $upload_dir = "uploads/";
    $target_file = $upload_dir . $image_name;

    if (!move_uploaded_file($tmp_name, $target_file)) {
        echo "❌ Failed to upload image.";
        exit;
    }

    // Insert product into DB
    $stmt = $conn->prepare("INSERT INTO products (name, description, price, image_url, category_id, subcategory_id) VALUES (?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        echo "❌ Prepare statement failed: " . $conn->error;
        exit;
    }

    $stmt->bind_param("ssdssi", $product_name, $description, $price, $image_name, $category_id, $subcategory_id);

    if ($stmt->execute()) {
        echo "✅ Product added successfully.";
    } else {
        echo "❌ Error adding product: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    exit;
}

// Fetch categories and subcategories for the form
$categories = [];
$subcategories = [];

$catResult = $conn->query("SELECT category_id, name FROM categories");
if ($catResult) {
    while ($row = $catResult->fetch_assoc()) {
        $categories[] = $row;
    }
}

$subcatResult = $conn->query("SELECT subcategory_id, subcategory_name FROM subcategories");
if ($subcatResult) {
    while ($row = $subcatResult->fetch_assoc()) {
        $subcategories[] = $row;
    }
}
?>

<!-- HTML Form -->
<form method="POST" enctype="multipart/form-data">
    <input type="text" name="name" placeholder="Product Name" required><br>
    <textarea name="description" placeholder="Description" required></textarea><br>
    <input type="number" step="0.01" name="price" placeholder="Price" required><br>
    <input type="file" name="image" required><br>

    <select name="category_id" required>
        <option value="">Select Category</option>
        <?php foreach ($categories as $cat): ?>
            <option value="<?= htmlspecialchars($cat['category_id']) ?>">
                <?= htmlspecialchars($cat['name']) ?>
            </option>
        <?php endforeach; ?>
    </select><br>

    <select name="subcategory_id" required>
        <option value="">Select Subcategory</option>
        <?php foreach ($subcategories as $subcat): ?>
            <option value="<?= htmlspecialchars($subcat['subcategory_id']) ?>">
                <?= htmlspecialchars($subcat['subcategory_name']) ?>
            </option>
        <?php endforeach; ?>
    </select><br>

    <button type="submit">Add Product</button>
</form>*/

include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? '';
    $category_id = $_POST['category_id'] ?? '';
    $subcategory_id = $_POST['subcategory_id'] ?? '';

    if (empty($product_name) || empty($subcategory_id) || empty($price) || empty($category_id)) {
        echo "<p class='error'>❌ Please fill in all required fields.</p>";
        exit;
    }

    if (!is_numeric($price) || !is_numeric($subcategory_id) || !is_numeric($category_id)) {
        echo "<p class='error'>❌ Invalid input.</p>";
        exit;
    }

    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== 0) {
        echo "<p class='error'>❌ Please upload a valid product image.</p>";
        exit;
    }

    $image_name = basename($_FILES['image']['name']);
    $tmp_name = $_FILES['image']['tmp_name'];
    $upload_dir = "uploads/";
    $target_file = $upload_dir . $image_name;

    if (!move_uploaded_file($tmp_name, $target_file)) {
        echo "<p class='error'>❌ Failed to upload image.</p>";
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO products (name, description, price, image_url, category_id, subcategory_id) VALUES (?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        echo "<p class='error'>❌ Prepare statement failed: " . $conn->error . "</p>";
        exit;
    }

    $stmt->bind_param("ssdssi", $product_name, $description, $price, $image_name, $category_id, $subcategory_id);

    if ($stmt->execute()) {
        echo "<p class='success'>✅ Product added successfully.</p>";
    } else {
        echo "<p class='error'>❌ Error adding product: " . $stmt->error . "</p>";
    }

    $stmt->close();
    $conn->close();
    exit;
}

$categories = [];
$subcategories = [];

$catResult = $conn->query("SELECT category_id, name FROM categories");
if ($catResult) {
    while ($row = $catResult->fetch_assoc()) {
        $categories[] = $row;
    }
}

$subcatResult = $conn->query("SELECT subcategory_id, subcategory_name FROM subcategories");
if ($subcatResult) {
    while ($row = $subcatResult->fetch_assoc()) {
        $subcategories[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product</title>
    <style>
        body {
            background: #f9f9f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 30px;
        }

        form {
            max-width: 500px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        input[type="text"],
        input[type="number"],
        textarea,
        select,
        input[type="file"] {
            width: 100%;
            padding: 10px 14px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 1rem;
        }

        textarea {
            resize: vertical;
        }

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

        button:hover {
            background-color: #0056b3;
        }

        .success {
            color: green;
            text-align: center;
            font-weight: bold;
        }

        .error {
            color: red;
            text-align: center;
            font-weight: bold;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<h2>Add New Product</h2>

<form id="addProductForm" method="POST" enctype="multipart/form-data">
    <input type="text" name="name" placeholder="Product Name" required>
    <textarea name="description" placeholder="Description" required></textarea>
    <input type="number" step="0.01" name="price" placeholder="Price" required>
    <input type="file" name="image" required>

    <select name="category_id" required>
        <option value="">Select Category</option>
        <?php foreach ($categories as $cat): ?>
            <option value="<?= htmlspecialchars($cat['category_id']) ?>">
                <?= htmlspecialchars($cat['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <select name="subcategory_id" required>
        <option value="">Select Subcategory</option>
        <?php foreach ($subcategories as $subcat): ?>
            <option value="<?= htmlspecialchars($subcat['subcategory_id']) ?>">
                <?= htmlspecialchars($subcat['subcategory_name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit">Add Product</button>
</form>

<script>
document.getElementById('addProductForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);

    fetch(window.location.href, {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        if (data.includes("✅")) {
            alert("✅ Product added successfully!");
            window.location.href = 'index.php';
        } else {
            alert(data); // Show the error message
        }
    })
    .catch(error => {
        alert("❌ An error occurred: " + error);
    });
});
</script>

</body>
</html>
