<?php
include("db.php");
session_start();

$product_id = $_GET['id'];

$sql = "SELECT * FROM `products` WHERE `id` = " . $product_id;
$result = $spoj->query($sql);
$product = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/product.css">
    <title><?php echo $product["name"]; ?></title>
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

    <div class="product-container">
        <img src="<?php echo $product["image"]; ?>" alt="<?php echo $product["name"]; ?>">
        <h2><?php echo $product["name"]; ?></h2>
        <p><?php echo $product["description"]; ?></p>
        <p><?php echo $product["price"]; ?>€</p>
        <form method="post" action="cart.php?action=add&id=<?php echo $product["id"]; ?>">
            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?php echo $product["quantity"]; ?>" />
            <input type="submit" value="Add to Cart" class="btnAddAction" />
        </form>
    </div>
</body>

</html>
