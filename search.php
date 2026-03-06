<?php
session_start();
include 'db.php';
 
$category = isset($_GET['category']) ? $conn->real_escape_string($_GET['category']) : '';
$search = isset($_GET['q']) ? $conn->real_escape_string($_GET['q']) : '';
 
$sql = "SELECT * FROM products WHERE 1=1";
if ($category) {
    $sql .= " AND category = '$category'";
}
if ($search) {
    $sql .= " AND (title LIKE '%$search%' OR description LIKE '%$search%')";
}
 
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search & Shop - EtsyClone</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
 
<nav class="navbar">
    <a href="index.php" class="logo">EtsyClone</a>
    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="search.php" class="btn-primary">Shop</a>
        <?php if(isset($_SESSION['user_id'])): ?>
            <?php if($_SESSION['role'] == 'seller'): ?>
                <a href="my_products.php">My Shop</a>
            <?php endif; ?>
            <a href="cart.php">Cart</a>
            <a href="profile.php">Profile</a>
            <a href="logout.php" class="btn-outline">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="signup.php" class="btn-primary">Sign Up</a>
        <?php endif; ?>
    </div>
</nav>
 
<div class="section">
    <h1 class="section-title">Shop All Items</h1>
 
    <div class="search-bar" style="margin-bottom: 2rem;">
        <form action="search.php" method="GET">
            <input type="text" name="q" placeholder="Search for items..." value="<?php echo htmlspecialchars($search); ?>">
            <select name="category" style="padding: 10px; border-radius: 20px; border: none; outline: none;">
                <option value="">All Categories</option>
                <option value="Jewelry" <?php if($category == 'Jewelry') echo 'selected'; ?>>Jewelry</option>
                <option value="Clothing" <?php if($category == 'Clothing') echo 'selected'; ?>>Clothing</option>
                <option value="Home Decor" <?php if($category == 'Home Decor') echo 'selected'; ?>>Home Decor</option>
                <option value="Art" <?php if($category == 'Art') echo 'selected'; ?>>Art</option>
                <option value="Digital" <?php if($category == 'Digital') echo 'selected'; ?>>Digital</option>
            </select>
            <button type="submit">Search</button>
        </form>
    </div>
 
    <div class="product-grid">
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <a href="product.php?id=<?php echo $row['id']; ?>" class="product-card">
                    <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="img" class="product-image">
                    <div class="product-info">
                        <h3 class="product-title"><?php echo htmlspecialchars($row['title']); ?></h3>
                        <p class="product-price">$<?php echo $row['price']; ?></p>
                        <span style="font-size: 0.8rem; color: var(--text-muted);"><?php echo htmlspecialchars($row['category']); ?></span>
                    </div>
                </a>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No products found matching your search.</p>
        <?php endif; ?>
    </div>
</div>
 
</body>
</html>
 
