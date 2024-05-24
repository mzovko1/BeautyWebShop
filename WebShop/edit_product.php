<?php
session_start();

include("db.php");

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}

if (isset($_POST['log-out'])) {
    session_destroy();
    header("location: index.php");
    exit;
}

if(!isset($_GET['id'])) {
    exit('No product ID provided.');
}

$id = $_GET['id'];

$sql = "SELECT * FROM `products` WHERE id=?";
$stmt = $spoj->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update-product'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $image = $_POST['image'];

    $sql = "UPDATE `products` SET name=?, description=?, price=?, quantity=?, image=? WHERE id=?";
    $stmt = $spoj->prepare($sql);
    $stmt->bind_param('ssdisi', $name, $description, $price, $quantity, $image, $id);
    $stmt->execute();
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete-product'])) {
    $sql = "DELETE FROM `products` WHERE id=?";
    $stmt = $spoj->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    header('Location: dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/add_product.css">
    <title>Edit Product</title>
</head>

<body>
    <nav class="nav-dashboard">
        <h1><a href="dashboard.php">Dashboard</a></h1>

        <div class="dashboard-menu">
            <a href="orders.php">Orders</a>
            <form method="post">
                <button type="sumbit" name="log-out">Log out</button>
            </form>
            <a href="add_product.php">Add Product</a>
        </div>
    </nav>
    <form class = "product-form" method="post">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" value="<?php echo $product['name']; ?>" required>

        <label for="description">Description</label>
        <textarea id="description" name="description" required><?php echo $product['description']; ?></textarea>

        <label for="price">Price</label>
        <input type="number" id="price" name="price" step="0.01" value="<?php echo $product['price']; ?>" required>

        <label for="quantity">Quantity</label>
        <input type="number" id="quantity" name="quantity" value="<?php echo $product['quantity']; ?>" required>

        <label for="image">Image URL</label>
        <input type="text" id="image" name="image" value="<?php echo $product['image']; ?>" required>

        <input type="submit" name="update-product" value="Update Product">
        <input type="submit" name="delete-product" value="Delete Product">
    </form>
</body>

</html>
