<?php
session_start();
include 'db.php';
 
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
 
$user_id = $_SESSION['user_id'];
$user_query = $conn->query("SELECT * FROM users WHERE id=$user_id");
$user = $user_query->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - EtsyClone</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
 
<nav class="navbar">
    <a href="index.php" class="logo">EtsyClone</a>
    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="search.php">Shop</a>
        <?php if($_SESSION['role'] == 'seller'): ?>
            <a href="my_products.php">My Shop</a>
            <a href="add_product.php">Sell</a>
        <?php endif; ?>
        <a href="cart.php">Cart</a>
        <a href="profile.php" class="btn-primary">Profile</a>
        <a href="logout.php" class="btn-outline">Logout</a>
    </div>
</nav>
 
<div class="section">
    <div class="container" style="max-width: 800px; margin: 0 auto;">
        <h1 class="section-title">My Profile</h1>
        <div class="auth-container" style="margin: 0; max-width: 100%;">
            <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Role:</strong> <?php echo ucfirst($user['role']); ?></p>
            <p><strong>Member Since:</strong> <?php echo date("F j, Y", strtotime($user['created_at'])); ?></p>
        </div>
 
        <?php if ($user['role'] == 'buyer'): ?>
            <h2 class="section-title" style="margin-top: 3rem;">My Orders</h2>
            <?php
            $orders = $conn->query("SELECT * FROM orders WHERE user_id=$user_id ORDER BY created_at DESC");
            if ($orders->num_rows > 0):
            ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Total</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($order = $orders->fetch_assoc()): ?>
                        <tr>
                            <td>#<?php echo $order['id']; ?></td>
                            <td>$<?php echo $order['total_amount']; ?></td>
                            <td><?php echo date("d M Y", strtotime($order['created_at'])); ?></td>
                            <td><span class="alert-success" style="padding: 5px 10px; font-size: 0.8rem;">Paid</span></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
                <p>No orders found.</p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
 
</body>
</html>
 
