<?php
session_start();
require_once 'db.php';
 
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
 
$user_id = $_SESSION['user_id'];
 
// Fetch user's products
$stmt = $conn->prepare("SELECT * FROM products WHERE seller_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Products | Market Hub</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav>
        <a href="index.php" class="logo">MarketHub</a>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="add_product.php">Add Product</a>
            <a href="logout.php">Logout</a>
        </div>
    </nav>
 
    <div class="container">
        <div class="hero" style="padding: 2rem 1rem;">
            <h1>My Store</h1>
            <p>Manage your listed products here.</p>
        </div>
 
        <div class="products-grid">
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="product-card">
                        <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>">
                        <div class="product-info">
                            <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                            <div class="price">$<?php echo number_format($row['price'], 2); ?></div>
                            <p><?php echo htmlspecialchars($row['description']); ?></p>
                            <div class="card-footer" style="border-top: none; padding-top: 0;">
                                <a href="delete_product.php?id=<?php echo $row['id']; ?>" class="auth-btn" style="background: #e74c3c; display: block; text-align: center; text-decoration: none; padding: 0.6rem 1rem; font-size: 0.9rem; margin-top: 10px;" onclick="return confirm('Are you sure you want to delete this product?')">Delete Product</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div style="grid-column: 1/-1; text-align: center; padding: 3rem;">
                    <h2>You haven't added any products yet.</h2>
                    <p><a href="add_product.php" style="color: var(--primary);">Start selling today!</a></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
 
