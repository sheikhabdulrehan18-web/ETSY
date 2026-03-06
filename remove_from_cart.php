<?php
session_start();
include 'db.php';
 
if (isset($_GET['id']) && isset($_SESSION['user_id'])) {
    $id = intval($_GET['id']);
    $user_id = $_SESSION['user_id'];
    $conn->query("DELETE FROM cart WHERE id=$id AND user_id=$user_id");
}
 
header("Location: cart.php");
exit();
?>
 
