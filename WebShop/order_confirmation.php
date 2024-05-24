<?php
session_start();
?>  

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/order-confirmation.css">
    <title>Susscesful order</title>
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
            ?>        </div>
    </nav>
    <div class="successful-order-content">
        <img id="successful-order-logo" src="./imgs/package-delivered-icon.png" />
        <div class="order-outcome-content">
            <h2>Order Successful!</h2>
            <p>Thank you for your order.</p>
            <a href="index.php">Home page</a>
        </div>
    </div>
</body>

</html>
