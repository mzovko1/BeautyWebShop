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


if (isset($_POST['delete-order'])) {
    $order_id = $_POST['delete-order'];
    $sqlOrders = "DELETE FROM `orders` WHERE `id`='$order_id'";
    $sqlOrderItem = "DELETE FROM `ordered_items` WHERE `order_id`='$order_id'";
    $spoj->query($sqlOrders);
    $spoj->query($sqlOrderItem);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/orders.css">
    <title>Orders</title>
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
    <div class="order-content">
        <?php
        $sqlOrder = "SELECT * FROM orders";
        $resultOrder = $spoj->query($sqlOrder);

        if ($resultOrder->num_rows > 0) {
            while ($row = $resultOrder->fetch_assoc()) {
                $order_id = $row["id"];
                $sqlOrderItem = "SELECT * FROM `ordered_items` WHERE `order_id`='$order_id'";
                $resultOrderItem = $spoj->query($sqlOrderItem);

                echo '<div class="order">
                <div class="order-first-section">
                    <h2>Order No. <span>' . $order_id . '</span></h2>
                    <form method="post">
                        <button  class="remove-order-btn" type="sumbit" name="delete-order" value="' . $order_id . '">Delete order</button>
                    </form>
                </div>
                <div>
                    <div class="name-surname-order">
                        <p>Name: <span>' . $row["name"] . '</span></p>
                        <p>Surname: <span>' . $row["surname"] . '</span></p>
                    </div>
                    <div class="address-order">
                        <p>Home address: <span>' . $row["address"] . '</span></p>
                        <p>Email address: <a href="mailto:' . $row["email"] . '">' . $row["email"] . '</a></p>
                        <p>Total: ' . $row["total"] . ' € </p>
                    </div>
                    <div>
                        <h3 id="order-title">Ordered items</h3>
                        <div class="ordered-items-container">';
                while ($rowItem = $resultOrderItem->fetch_assoc()) {
                    $item_id = $rowItem["item_id"];
                    $sqlProduct = "SELECT * FROM `products` WHERE `id`='$item_id'";
                    $resultProduct = $spoj->query($sqlProduct);
                    $product = $resultProduct->fetch_assoc();
                    echo '<div class="ordered-item">
                            <h3>' . $product["name"] . '</h3>
                            <p> Price: ' . $product["price"] . ' €</p>
                            <p> Amount: ' . $rowItem["quantity"] . '</p>
                        </div>';
                }

                echo '</div>
                    </div>
                </div>
            </div>';
            }
        } else {
            echo '<p id="orders-message">There are no orders!</p>';
        }
        $spoj->close();
        ?>
    </div>

</body>

</html>
