<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $conn->query("INSERT INTO categories (name) VALUES ('$name')");
    echo "Category added.";
}
?>

<form method="POST" enctype="multipart/form-data">
    <select name="category_id" required>
        <option value="">Select Category</option>
        <?php
        include 'db.php';
        $result = $conn->query("SELECT * FROM categories");
        while ($row = $result->fetch_assoc()) {
              var_dump($row); 
            echo "<option value='" . $row['category_id'] . "'>" . $row['name'] . "</option>";
        }
        ?>
    </select>
    <button type="submit">Submit</button>
</form>




