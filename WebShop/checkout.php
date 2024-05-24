<?php
include("db.php");
session_start();

if (!isset($_SESSION['cart_item']) || empty($_SESSION['cart_item'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $address = $_POST['address'];
    $email = $_POST['email'];

    $total = 0;
    foreach ($_SESSION["cart_item"] as $item) {
        $total += $item["price"] * $item["quantity"];
    }

    $sql = "INSERT INTO `orders` (`name`, `surname`, `address`, `email`, `total`) VALUES ('" . $name . "', '" . $surname . "', '" . $address . "', '" . $email . "', '" . $total . "')";
    $orderInsert = $spoj->query($sql);
    $lastInsertId = $spoj->insert_id;

    if (!empty($lastInsertId)) {
        foreach ($_SESSION["cart_item"] as $item) {
            $orderItems = "INSERT INTO `ordered_items`(`order_id`, `item_id`, `quantity`) VALUES ('" . $lastInsertId . "', '" . $item["id"] . "', '" . $item["quantity"] . "')";
            $spoj->query($orderItems);

            $updateProductQuantity = "UPDATE `products` SET `quantity` = `quantity` - " . $item["quantity"] . " WHERE `id` = " . $item["id"];
            $spoj->query($updateProductQuantity);
        }
    }
    unset($_SESSION["cart_item"]);
    header("Location: order_confirmation.php");
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/checkout.css">
    <title>Checkout</title>
</head>

<body>
<nav class="nav-web-market">
        <h1><a href="index.php">Beauty WebShop</a></h1>
        <div class="nav-menu">
            <a href="products.php">Products</a>
            <a href="cart.php">Cart</a>
            <?php
            if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
                echo '<a href="login.php">Login</a>';
            } else {
                echo '<a href="dashboard.php">Dashboard</a>';
            }
            ?>
        </div>
    </nav>
    <div class="checkout-content">
        <h2>Checkout</h2>
        <form method="post">
            <label for="name">Name:</label><br>
            <input type="text" id="name" name="name" required><br>
            <label for="surname">Surname:</label><br>
            <input type="text" id="surname" name="surname" required><br>
            <label for="address">Home address:</label><br>
            <input type="text" id="address" name="address" required><br>
            <label for="email">Email address:</label><br>
            <input type="email" id="email" name="email" required><br>
            <input type="submit" value="Submit">
        </form>
    </div>
</body>

</html>
