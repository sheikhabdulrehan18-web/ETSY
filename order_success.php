<?php
session_start();
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success - EtsyClone</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .success-container {
            text-align: center;
            padding: 5rem 2rem;
            max-width: 600px;
            margin: 0 auto;
        }
        .checkmark {
            font-size: 5rem;
            color: #4CAF50;
            animation: float 2s infinite ease-in-out;
        }
    </style>
</head>
<body>
 
<nav class="navbar">
    <a href="index.php" class="logo">EtsyClone</a>
    <div class="nav-links">
        <a href="index.php">Home</a>
    </div>
</nav>
 
<div class="success-container">
    <div class="checkmark">✔</div>
    <h1 style="margin-bottom: 1rem;">Order Placed Successfully!</h1>
    <p>Thank you for your purchase. Your order #<?php echo $_GET['id']; ?> has been confirmed.</p>
    <br>
    <a href="index.php" class="btn-primary">Back to Shopping</a>
</div>
 
</body>
</html>
 
