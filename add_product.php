<?php
// 1. Enable error reporting at top of file
error_reporting(E_ALL);
ini_set('display_errors', 1);
 
session_start();
require_once 'db.php';
 
// 2. Security: Prevent access without login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
 
$error = '';
$success = '';
 
// Check if database connection exists
if (!isset($conn) || $conn->connect_error) {
    die("Database connection error. Please check db.php");
}
 
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
    // 3. Proper Validation
    $title = trim($_POST['title']);
    $price = trim($_POST['price']);
    $description = trim($_POST['description']);
    $category = trim($_POST['category']); // Capture category
    $seller_id = $_SESSION['user_id'];
 
    if (empty($title) || empty($price) || empty($description) || empty($category)) {
        $error = "All fields (including category) are required!";
    } elseif (!is_numeric($price)) {
        $error = "Price must be a valid number.";
    } elseif ($price < 0) {
        $error = "Price cannot be negative.";
    } elseif ($price > 99999999.99) {
        $error = "Price is too high. Maximum allowed is 99,999,999.99";
    } elseif (!isset($_FILES['image']) || $_FILES['image']['error'] === UPLOAD_ERR_NO_FILE) {
        $error = "Please upload a product image.";
    } else {
        // 4. Safe Image Upload Logic
        $image_err = $_FILES['image']['error'];
 
        if ($image_err !== UPLOAD_ERR_OK) {
            switch ($image_err) {
                case UPLOAD_ERR_INI_SIZE:
                    $error = "The uploaded file exceeds the upload_max_filesize directive in php.ini.";
                    break;
                case UPLOAD_ERR_FORM_SIZE:
                    $error = "The uploaded file exceeds the MAX_FILE_SIZE directive specified in the HTML form.";
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $error = "The uploaded file was only partially uploaded.";
                    break;
                case UPLOAD_ERR_NO_TMP_DIR:
                    $error = "Missing a temporary folder.";
                    break;
                default:
                    $error = "Unknown upload error occurred (Code: $image_err).";
                    break;
            }
        } else {
            $file_tmp = $_FILES['image']['tmp_name'];
            $file_name = $_FILES['image']['name'];
            $file_size = $_FILES['image']['size'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $allowed_exts = ['jpg', 'jpeg', 'png', 'gif'];
 
            if (!in_array($file_ext, $allowed_exts)) {
                $error = "Only JPG, JPEG, PNG, and GIF files are allowed.";
            } elseif (!@getimagesize($file_tmp)) {
                $error = "File is not a valid image.";
            } elseif ($file_size > 5242880) {
                $error = "File size must be less than 5MB.";
            } else {
                $new_file_name = "prod_" . time() . "_" . bin2hex(random_bytes(4)) . "." . $file_ext;
                $target_path = __DIR__ . '/' . $new_file_name;
 
                if (move_uploaded_file($file_tmp, $target_path)) {
                    // 5. Corrected Database Insertion with Category
                    $stmt = $conn->prepare("INSERT INTO products (title, price, description, category, image, seller_id) VALUES (?, ?, ?, ?, ?, ?)");
                    if ($stmt) {
                        // Bind 6 parameters: s (title), s (price as string), s (description), s (category), s (image), i (seller_id)
                        // Note: binding price as 's' prevents floating point 'out of range' issues on some MySQL strict modes
                        $stmt->bind_param("sssssi", $title, $price, $description, $category, $new_file_name, $seller_id);
                        if ($stmt->execute()) {
                            $success = "Product added successfully!";
                            $_POST = array(); // Clear form
                        } else {
                            $error = "Database Error: " . $stmt->error;
                            if (file_exists($target_path)) unlink($target_path);
                        }
                        $stmt->close();
                    } else {
                        $error = "Prepare failed: " . $conn->error;
                        if (file_exists($target_path)) unlink($target_path);
                    }
                } else {
                    $error = "Failed to move uploaded file. Check root directory permissions.";
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product | Market Hub</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav>
        <a href="index.php" class="logo">MarketHub</a>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="my_products.php">My Products</a>
            <a href="logout.php">Logout</a>
        </div>
    </nav>
 
    <div class="container upload-container">
        <div class="auth-container" style="max-width: 100%;">
            <h2>Add New Product</h2>
 
            <?php if($error): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
 
            <?php if($success): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
 
            <form method="POST" action="add_product.php" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Product Title</label>
                    <input type="text" name="title" value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>" placeholder="e.g. Handmade Ceramic Vase" required>
                </div>
                <!-- 1. Category Dropdown Select -->
                <div class="form-group">
                    <label>Category</label>
                    <select name="category" required style="width: 100%; padding: 0.8rem 1.2rem; border-radius: 12px; border: 1px solid #ddd; background: rgba(255,255,255,0.8); font-size: 1rem;">
                        <option value="">Select Category</option>
                        <option value="Handmade" <?php echo (isset($_POST['category']) && $_POST['category'] == 'Handmade') ? 'selected' : ''; ?>>Handmade</option>
                        <option value="Vintage" <?php echo (isset($_POST['category']) && $_POST['category'] == 'Vintage') ? 'selected' : ''; ?>>Vintage</option>
                        <option value="Digital" <?php echo (isset($_POST['category']) && $_POST['category'] == 'Digital') ? 'selected' : ''; ?>>Digital</option>
                        <option value="Jewelry" <?php echo (isset($_POST['category']) && $_POST['category'] == 'Jewelry') ? 'selected' : ''; ?>>Jewelry</option>
                        <option value="Art" <?php echo (isset($_POST['category']) && $_POST['category'] == 'Art') ? 'selected' : ''; ?>>Art</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Price ($)</label>
                    <input type="number" step="0.01" name="price" value="<?php echo isset($_POST['price']) ? htmlspecialchars($_POST['price']) : ''; ?>" placeholder="0.00" required>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="5" placeholder="Tell buyers about your product..." required><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                </div>
                <div class="form-group">
                    <label>Product Image</label>
                    <input type="file" name="image" accept="image/*" required>
                </div>
                <button type="submit" name="add_product" class="auth-btn">Upload Product</button>
            </form>
        </div>
    </div>
</body>
</html>
 
