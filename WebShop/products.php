<?php
session_start();
include("db.php");

$sqlProducts = "SELECT * FROM `products`";
$resultProducts = $spoj->query($sqlProducts);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/style.css">
    <title>Beauty WebShop</title>
</head>

<body>
<nav class="nav-web-market">
        <h1><a href="index.php">Beauty WebShop</a></h1>
        <div class="nav-menu">
        <?php
            if(isset($_SESSION["cart_item"])) {
            $cart_count = count($_SESSION["cart_item"]);
            ?><a class="cart-a" href="cart.php"><img src="imgs/cart.png" alt="Cart"/><span><?php echo $cart_count; ?></span></a><?php
            }
            ?>
            <a href="products.php">Products</a>
            <?php
            if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
                echo '<a href="login.php">Login</a>';
            } else {
                echo '<a href="dashboard.php">Dashboard</a>';
            }
            ?>        </div>
    </nav>

    <div class="products-container">
        <?php
        if ($resultProducts->num_rows > 0) {
            while ($product = $resultProducts->fetch_assoc()) {
                echo '<div class="product-card">
                <img src="' . $product["image"] . '" alt="' . $product["name"] . '">
                <div class="product-info">
                    <h3>' . $product["name"] . '</h3>
                    <p>' . $product["description"] . '</p>
                    <div class="product-details">
                        <span class="product-price">' . $product["price"] . '€</span>
                        <a href="product.php?id=' . $product["id"] . '" class="btnAddAction">Buy now</a>
                    </div>
                </div>
              </div>';
            }
        } else {
            echo '<p>Sorry, there are no products available at the moment.</p>';
        }
        $spoj->close();
        ?>
    </div>
</body>

</html>
