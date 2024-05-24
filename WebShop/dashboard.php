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

$sqlProducts = "SELECT * FROM `products`";
$resultProducts = $spoj->query($sqlProducts);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/dashboard.css">
    <title>Dashboard</title>
</head>

<body>
    <nav class="nav-dashboard">
        <h1> <?php echo $_SESSION['email']; ?></h1>

        <div class="dashboard-menu">
            <a href="orders.php">Previous orders</a>
            <a href="add_product.php">Add new product</a>
            <form method="post">
                <button type="sumbit" name="log-out">Log out</button>
            </form>
        </div>
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
                                <a href="edit_product.php?id=' . $product["id"] . '" class="btnEditProduct">Edit</a>
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