<?php
$host = 'localhost';
$dbname = 'rsk9_53';
$username = 'rsk9_53';
$password = '123456';
 
$conn = new mysqli($host, $username, $password, $dbname);
 
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
 
// Security: Prevent direct access to db.php
if (basename($_SERVER['PHP_SELF']) == 'db.php') {
    header("Location: index.php");
    exit();
}
?>
 
