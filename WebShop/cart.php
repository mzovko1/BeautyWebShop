<?php
session_start();

require_once("db.php");

if (!empty($_GET["action"])) {
    switch ($_GET["action"]) {
        case "add":
            if (!empty($_POST["quantity"])) {
                $productById = mysqli_query($spoj, "SELECT * FROM products WHERE id='" . $_GET["id"] . "'");
                $itemArray = mysqli_fetch_array($productById,MYSQLI_ASSOC);
                if (!empty($_SESSION["cart_item"])) {
                    if (in_array($itemArray["id"],array_keys($_SESSION["cart_item"]))) {
                        foreach ($_SESSION["cart_item"] as $k => $v) {
                            if ($itemArray["id"] == $k) {
                                if ($_POST["quantity"] <= $itemArray["quantity"]) { 
                                    $_SESSION["cart_item"][$k]["quantity"] = $_POST["quantity"];
                                } else {
                                    echo '<span class="stock-notice">Sorry, we only have ' . $itemArray["quantity"] . ' items in stock.</span>';
                                }
                            }
                        }
                    } else {
                        if ($_POST["quantity"] <= $itemArray["quantity"]) {
                            $_SESSION["cart_item"][$itemArray["id"]] = array('id' => $itemArray["id"], 'name' => $itemArray["name"], 'quantity' => $_POST["quantity"], 'price' => $itemArray["price"], 'image' => $itemArray["image"]);
                        } else {
                            echo '<span class="stock-notice">Sorry, we only have ' . $itemArray["quantity"] . ' items in stock.</span>';
                        }
                    }
                } else {
                    if ($_POST["quantity"] <= $itemArray["quantity"]) {
                        $_SESSION["cart_item"][$itemArray["id"]] = array('id' => $itemArray["id"], 'name' => $itemArray["name"], 'quantity' => $_POST["quantity"], 'price' => $itemArray["price"], 'image' => $itemArray["image"]);
                    } else {
                        echo '<span class="stock-notice">Sorry, we only have ' . $itemArray["quantity"] . ' items in stock.</span>';
                    }
                }
            }
            break;

        case "remove":
            if (!empty($_SESSION["cart_item"])) {
                foreach ($_SESSION["cart_item"] as $k => $v) {
                    if ($_GET["id"] == $k)
                        unset($_SESSION["cart_item"][$k]);
                    if (empty($_SESSION["cart_item"]))
                        unset($_SESSION["cart_item"]);
                }
            }
            break;

        case "empty":
            unset($_SESSION["cart_item"]);
            break;
    }
}

function update_cart() {
    echo "<script>
            location.reload();
          </script>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/cart.css">
    <link rel="stylesheet" href="./styles/navbar.css">
    <title>Cart</title>
</head>

<body>
<nav class="nav-web-market">
        <h1><a href="index.php">Beauty WebShop</a></h1>
        <div class="nav-menu">
            <a href="products.php">Products</a>
            <?php
            if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
                echo '<a href="login.php">Login</a>';
            } else {
                echo '<a href="dashboard.php">Dashboard</a>';
            }
            ?>
        </div>
    </nav>

    <div class="cart-content">
        <?php
        if (isset($_SESSION["cart_item"])) {
            $total_quantity = 0;
            $total_price = 0;
            ?>
            <table class="tbl-cart" cellpadding="10" cellspacing="1">
                <tbody>
                    <tr>
                        <th style="text-align:left;">Name</th>
                        <th style="text-align:left;">Image</th>
                        <th style="text-align:right;" width="5%">Quantity</th>
                        <th style="text-align:right;" width="10%">Unit Price</th>
                        <th style="text-align:right;" width="10%">Price</th>
                        <th style="text-align:center;" width="5%">Remove</th>
                    </tr>
                    <?php
                    foreach ($_SESSION["cart_item"] as $item) {
                        $item_price = $item["quantity"] * $item["price"];
                        ?>
                        <tr>
                            <td><?php echo $item["name"]; ?></td>
                            <td><img src="<?php echo $item["image"]; ?>" height="50" width="50"></td>
                            <td style="text-align:right;">
                                <form method="post" action="cart.php?action=add&id=<?php echo $item["id"]; ?>">
                                    <input type="number" name="quantity" value="<?php echo $item["quantity"]; ?>" size="2" onchange="this.form.submit()" />
                                </form>
                            </td>
                            <td style="text-align:right;"><?php echo "$ " . $item["price"]; ?></td>
                            <td style="text-align:right;"><?php echo "$ " . number_format($item_price, 2); ?></td>
                            <td style="text-align:center;"><a href="cart.php?action=remove&id=<?php echo $item["id"]; ?>" class="btnRemoveAction" onclick="update_cart()">Remove Item</a></td>
                        </tr>
                    <?php
                        $total_quantity += $item["quantity"];
                        $total_price += ($item["price"] * $item["quantity"]);
                    }
                    ?>

                    <tr>
                        <td colspan="3" align="right">Total:</td>
                        <td align="right"><?php echo $total_quantity; ?></td>
                        <td align="right" colspan="2"><strong><?php echo "$ " . number_format($total_price, 2); ?></strong></td>
                    </tr>
                </tbody>
            </table>
        <?php
        } else {
            ?>
            <div class="no-records">Your Cart is Empty</div>
        <?php
        }
        ?>
    </div>

    <div class="cart-nav">
        <a href="cart.php?action=empty" class="btnEmpty">Empty Cart</a>
        <a href="checkout.php" class="btnCheckout">Checkout</a>
    </div>
</body>

</html>
