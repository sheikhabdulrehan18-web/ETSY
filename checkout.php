<?php
session_start();
include 'db.php';
 
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
 
$user_id = $_SESSION['user_id'];
$cart_items = $conn->query("SELECT c.*, p.price FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id=$user_id");
 
$total_amount = 0;
$order_items = []; 
 
if ($cart_items->num_rows > 0) {
    while($item = $cart_items->fetch_assoc()) {
        $total_amount += $item['price'] * $item['quantity'];
        $order_items[] = $item;
    }
} else {
    header("Location: cart.php");
    exit();
}
 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process Order
    $conn->query("INSERT INTO orders (user_id, total_amount) VALUES ($user_id, $total_amount)");
    $order_id = $conn->insert_id;
 
    foreach ($order_items as $item) {
        $pid = $item['product_id'];
        $price = $item['price'];
        $qty = $item['quantity'];
        $conn->query("INSERT INTO order_items (order_id, product_id, price, quantity) VALUES ($order_id, $pid, $price, $qty)");
    }
 
    // Clear Cart
    $conn->query("DELETE FROM cart WHERE user_id=$user_id");
 
    header("Location: order_success.php?id=$order_id");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - EtsyClone</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
 
<nav class="navbar">
    <a href="index.php" class="logo">EtsyClone</a>
    <div class="nav-links">
        <a href="cart.php">Back to Cart</a>
    </div>
</nav>
 
<div class="auth-container" style="max-width: 600px;">
    <h2 class="section-title">Checkout</h2>
    <div class="alert alert-success">Total to Pay: $<?php echo number_format($total_amount, 2); ?></div>
 
    <form method="POST">
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="name" required placeholder="John Doe">
        </div>
        <div class="form-group">
            <label>Address</label>
            <input type="text" name="address" required placeholder="123 Main St">
        </div>
        <div class="form-group">
            <label>Card Number (Dummy)</label>
            <input type="text" value="4242 4242 4242 4242" readonly style="color: grey;">
        </div>
        <button type="submit" class="btn-primary btn-block">Pay Now</button>
    </form>
</div>
 
</body>
</html>
 
