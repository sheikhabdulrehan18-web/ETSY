<?php
session_start();
include 'db.php';
 
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$result = $conn->query("SELECT p.*, u.name as seller_name FROM products p JOIN users u ON p.seller_id = u.id WHERE p.id=$id");
 
if ($result->num_rows == 0) {
    die("Product not found.");
}
 
$product = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['title']); ?> - EtsyClone</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .product-detail-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            max-width: 1000px;
            margin: 4rem auto;
            padding: 2rem;
            background: var(--card-bg);
            border-radius: 20px;
            border: 1px solid var(--border-color);
            backdrop-filter: blur(10px);
        }
        .detail-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 15px;
        }
        .detail-info {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .tag-category {
            background: rgba(255, 255, 255, 0.1);
            padding: 5px 15px;
            border-radius: 50px;
            font-size: 0.9rem;
            align-self: flex-start;
            margin-bottom: 1rem;
        }
        @media (max-width: 768px) {
            .product-detail-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
 
<nav class="navbar">
    <a href="index.php" class="logo">EtsyClone</a>
    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="search.php">Shop</a>
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="cart.php">Cart</a>
            <a href="profile.php">Profile</a>
        <?php else: ?>
            <a href="login.php" class="btn-primary">Login</a>
        <?php endif; ?>
    </div>
</nav>
 
<div class="product-detail-container">
    <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="product" class="detail-image">
 
    <div class="detail-info">
        <span class="tag-category"><?php echo htmlspecialchars($product['category']); ?></span>
        <h1 style="font-size: 2.5rem; margin-bottom: 0.5rem;"><?php echo htmlspecialchars($product['title']); ?></h1>
        <p style="color: var(--text-muted); margin-bottom: 1.5rem;">Sold by <?php echo htmlspecialchars($product['seller_name']); ?></p>
 
        <h2 style="font-size: 2rem; color: var(--primary); margin-bottom: 1.5rem;">$<?php echo $product['price']; ?></h2>
 
        <p style="line-height: 1.8; margin-bottom: 2rem;"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
 
        <?php if(isset($_SESSION['user_id'])): ?>
            <?php if($_SESSION['role'] == 'buyer'): ?>
                <form action="add_to_cart.php" method="POST">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <div style="display: flex; gap: 10px; margin-bottom: 1rem;">
                        <input type="number" name="quantity" value="1" min="1" style="width: 80px; padding: 10px; border-radius: 10px; border: 1px solid var(--border-color); background: rgba(255,255,255,0.1); color: white;">
                        <button type="submit" class="btn-primary" style="flex: 1;">Add to Cart</button>
                    </div>
                </form>
            <?php elseif($_SESSION['role'] == 'seller' && $_SESSION['user_id'] == $product['seller_id']): ?>
                <a href="edit_product.php?id=<?php echo $product['id']; ?>" class="btn-outline" style="display: block; text-align: center;">Edit Product</a>
            <?php endif; ?>
        <?php else: ?>
            <a href="login.php" class="btn-primary" style="text-align: center;">Login to Buy</a>
        <?php endif; ?>
    </div>
</div>
 
</body>
</html>
 
