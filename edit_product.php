<?php
session_start();
include 'db.php';
 
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'seller') {
    header("Location: login.php");
    exit();
}
 
$id = $_GET['id'];
$seller_id = $_SESSION['user_id'];
$result = $conn->query("SELECT * FROM products WHERE id=$id AND seller_id=$seller_id");
 
if ($result->num_rows == 0) {
    die("Product not found or access denied.");
}
 
$product = $result->fetch_assoc();
$error = "";
$success = "";
 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = $_POST['price'];
    $category = $conn->real_escape_string($_POST['category']);
    $image = $conn->real_escape_string($_POST['image']);
 
    $sql = "UPDATE products SET title='$title', description='$description', price='$price', category='$category', image='$image' WHERE id=$id";
 
    if ($conn->query($sql)) {
        $success = "Product updated! <a href='my_products.php'>Go back</a>";
        // Refresh data
        $product['title'] = $title;
        $product['description'] = $description;
        $product['price'] = $price;
        $product['category'] = $category;
        $product['image'] = $image;
    } else {
        $error = "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - EtsyClone</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
 
<nav class="navbar">
    <a href="index.php" class="logo">EtsyClone</a>
    <div class="nav-links">
        <a href="my_products.php">My Shop</a>
    </div>
</nav>
 
<div class="auth-container" style="max-width: 600px;">
    <h2 class="section-title">Edit Product</h2>
    <?php if ($error) echo "<div class='alert alert-error'>$error</div>"; ?>
    <?php if ($success) echo "<div class='alert alert-success'>$success</div>"; ?>
 
    <form method="POST">
        <div class="form-group">
            <label>Product Title</label>
            <input type="text" name="title" value="<?php echo htmlspecialchars($product['title']); ?>" required>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="5" required style="width: 100%; padding: 10px; background: rgba(255,255,255,0.05); border: 1px solid var(--border-color); color: white; border-radius: 10px;"><?php echo htmlspecialchars($product['description']); ?></textarea>
        </div>
        <div class="form-group">
            <label>Price ($)</label>
            <input type="number" step="0.01" name="price" value="<?php echo $product['price']; ?>" required>
        </div>
        <div class="form-group">
            <label>Category</label>
            <select name="category">
                <option value="Jewelry" <?php if($product['category'] == 'Jewelry') echo 'selected'; ?>>Jewelry</option>
                <option value="Clothing" <?php if($product['category'] == 'Clothing') echo 'selected'; ?>>Clothing</option>
                <option value="Home Decor" <?php if($product['category'] == 'Home Decor') echo 'selected'; ?>>Home Decor</option>
                <option value="Art" <?php if($product['category'] == 'Art') echo 'selected'; ?>>Art</option>
                <option value="Digital" <?php if($product['category'] == 'Digital') echo 'selected'; ?>>Digital</option>
            </select>
        </div>
        <div class="form-group">
            <label>Image URL</label>
            <input type="url" name="image" value="<?php echo htmlspecialchars($product['image']); ?>" required>
        </div>
        <button type="submit" class="btn-primary btn-block">Update Product</button>
    </form>
</div>
 
</body>
</html>
 
