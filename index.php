<?php
session_start();
require_once 'db.php';
 
// Fetch all products
$query = "SELECT p.*, u.name as seller_name FROM products p JOIN users u ON p.seller_id = u.id ORDER BY p.created_at DESC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MarketHub | Discover Unique Products</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="bg-blobs">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="blob blob-3"></div>
    </div>
 
    <nav>
        <a href="index.php" class="logo">MarketHub</a>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="my_products.php">My Products</a>
                <a href="add_product.php">Add Product</a>
                <a href="logout.php" class="btn-primary">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="signup.php" class="btn-primary">Signup</a>
            <?php endif; ?>
        </div>
    </nav>
 
    <div class="hero">
        <h1>Handcrafted & <br> Unique Items.</h1>
        <p>Discover one-of-a-kind items from independent sellers worldwide. Expertly crafted, just for you.</p>
    </div>
 
    <div class="container">
        <div class="products-grid">
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="product-card" onclick="location.href='product.php?id=<?php echo $row['id']; ?>'">
                        <div class="img-container">
                            <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>">
                        </div>
                        <div class="product-info">
                            <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                            <div class="price"><?php echo number_format($row['price'], 2); ?></div>
                            <p><?php echo htmlspecialchars($row['description']); ?></p>
                            <div class="card-footer">
                                <span class="category-tag"><?php echo htmlspecialchars($row['category'] ?? 'General'); ?></span>
                                <span class="seller-badge">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                    <?php echo htmlspecialchars($row['seller_name']); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div style="grid-column: 1/-1; text-align: center; padding: 5rem;">
                    <h2>No products found yet.</h2>
                    <p>Be the first one to add a product!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
 
