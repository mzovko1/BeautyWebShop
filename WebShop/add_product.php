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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add-product'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $image = $_POST['image'];

    $sql = "INSERT INTO `products` (name, description, price, quantity, image) VALUES (?, ?, ?, ?, ?)";
    $stmt = $spoj->prepare($sql);
    $stmt->bind_param('ssdis', $name, $description, $price, $quantity, $image);
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
    <title>Add Product</title>
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
        <input type="text" id="name" name="name" required>

        <label for="description">Description</label>
        <textarea id="description" name="description" required></textarea>

        <label for="price">Price</label>
        <input type="number" id="price" name="price" step="0.01" required>

        <label for="quantity">Quantity</label>
        <input type="number" id="quantity" name="quantity" required>

        <label for="image">Image URL</label>
        <input type="text" id="image" name="image" required>

        <input type="submit" name="add-product" value="Add Product">
    </form>
</body>

</html>
