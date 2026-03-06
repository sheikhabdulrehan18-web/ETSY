<?php
session_start();
require_once 'db.php';
 
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
 
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];
 
    // First, verify the product belongs to the user and gets the image path
    $stmt = $conn->prepare("SELECT image FROM products WHERE id = ? AND seller_id = ?");
    $stmt->bind_param("ii", $product_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
 
    if ($result->num_rows === 1) {
        $product = $result->fetch_assoc();
        $image_path = $product['image'];
 
        // Delete from database
        $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
        $stmt->bind_param("i", $product_id);
 
        if ($stmt->execute()) {
            // Delete image file if it exists
            if (file_exists($image_path)) {
                unlink($image_path);
            }
            $_SESSION['success_msg'] = "Product deleted successfully.";
        }
    }
}
 
header("Location: my_products.php");
exit();
?>
 
