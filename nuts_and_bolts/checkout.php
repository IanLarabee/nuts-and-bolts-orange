<?php require_once "config/connect.php"; ?>
<?php
    session_start();

    foreach($_SESSION['cart'] as $sku => $qty)
    {
        $q_sql = "SELECT * FROM inventory WHERE sku = $sku";
        $result = mysqli_query($conn, $q_sql);

        $row = mysqli_fetch_array($result);

        if($row['quantity'] >= $qty)
        {
            $c_sql = "UPDATE inventory SET quantity = quantity-$qty WHERE sku=$sku";
            mysqli_query($conn, $c_sql);
        }
        else
        {
            $_SESSION['cartStatus'] = "Oops! We don't have enough of the product " . $row['product_name'] . " to fulfill your order. Please try ordering a smaller quantity";
            header("Location: cart.php", true, 303);
            exit();
        }
    }
    $_SESSION['cart'] = array();
?>