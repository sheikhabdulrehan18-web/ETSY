<?php
session_start();
include 'db.php';
 
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
 
$user_id = $_SESSION['user_id'];
$cart_items = $conn->query("SELECT c.*, p.title, p.price, p.image FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id=$user_id");
 
$total_price = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - EtsyClone</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
 
<nav class="navbar">
    <a href="index.php" class="logo">EtsyClone</a>
    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="search.php">Shop</a>
        <a href="cart.php" class="btn-primary">Cart</a>
        <a href="profile.php">Profile</a>
        <a href="logout.php" class="btn-outline">Logout</a>
    </div>
</nav>
 
<div class="section">
    <h1 class="section-title">Your Cart</h1>
 
    <div class="container" style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; max-width: 1200px; margin: 0 auto;">
 
        <div class="cart-items">
            <?php if ($cart_items->num_rows > 0): ?>
                <?php while($item = $cart_items->fetch_assoc()): ?>
                    <?php $subtotal = $item['price'] * $item['quantity']; $total_price += $subtotal; ?>
                    <div class="product-card" style="display: flex; padding: 1rem; gap: 1rem; align-items: center; margin-bottom: 1rem;">
                        <img src="<?php echo htmlspecialchars($item['image']); ?>" style="width: 100px; height: 100px; border-radius: 10px; object-fit: cover;">
                        <div style="flex: 1;">
                            <h3><?php echo htmlspecialchars($item['title']); ?></h3>
                            <p style="color: var(--primary);">$<?php echo $item['price']; ?></p>
                            <p>Quantity: <?php echo $item['quantity']; ?></p>
                        </div>
                        <div style="text-align: right;">
                            <p style="font-weight: bold; margin-bottom: 0.5rem;">$<?php echo number_format($subtotal, 2); ?></p>
                            <a href="remove_from_cart.php?id=<?php echo $item['id']; ?>" style="color: #ff5555; font-size: 0.9rem;">Remove</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Your cart is empty. <a href="index.php" style="color: var(--primary);">Go shopping</a></p>
            <?php endif; ?>
        </div>
 
        <?php if ($cart_items->num_rows > 0): ?>
        <div class="cart-summary" style="background: var(--card-bg); padding: 2rem; border-radius: 15px; border: 1px solid var(--border-color); height: fit-content; sticky; top: 100px;">
            <h2 style="margin-bottom: 1rem;">Summary</h2>
            <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                <span>Subtotal</span>
                <span>$<?php echo number_format($total_price, 2); ?></span>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 1rem; font-weight: bold; font-size: 1.2rem; color: var(--primary);">
                <span>Total</span>
                <span>$<?php echo number_format($total_price, 2); ?></span>
            </div>
            <a href="checkout.php" class="btn-primary btn-block" style="text-align: center;">Proceed to Checkout</a>
        </div>
        <?php endif; ?>
 
    </div>
</div>
 
</body>
</html>
 
