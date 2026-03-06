<?php
session_start();
include 'db.php';
 
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);
 
    // Check if item already in cart
    $check = $conn->query("SELECT * FROM cart WHERE user_id=$user_id AND product_id=$product_id");
 
    if ($check->num_rows > 0) {
        $conn->query("UPDATE cart SET quantity = quantity + $quantity WHERE user_id=$user_id AND product_id=$product_id");
    } else {
        $conn->query("INSERT INTO cart (user_id, product_id, quantity) VALUES ($user_id, $product_id, $quantity)");
    }
}
 
header("Location: cart.php");
exit();
?>
 
