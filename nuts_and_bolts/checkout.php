<?php require_once "config/connect.php"; ?>
<?php
    session_start();

    //3 happens to be our guest user id
    $userid = 3;

    if(isset($_SESSION['userId'])) {
        $userid = $_SESSION['userId'];
    }

    $receiptid = uniqid($userid);
    $_SESSION['receiptID'] = $receiptid;

    if(isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        mysqli_query($conn,"INSERT INTO receipts(receiptID, username, saleDate) VALUES ('$receiptid', '$username', NOW())");
    } else {
        mysqli_query($conn,"INSERT INTO receipts(receiptID, username, saleDate) VALUES ('$receiptid', 'guest', NOW())");
    }

    foreach($_SESSION['cart'] as $sku => $qty)
    {
        $q_sql = "SELECT * FROM inventory WHERE sku = $sku";
        $result = mysqli_query($conn, $q_sql);

        $row = mysqli_fetch_array($result);

        if($row['quantity'] >= $qty)
        {
            $c_sql = "UPDATE inventory SET quantity = quantity-$qty WHERE sku=$sku";
            mysqli_query($conn, $c_sql);
            mysqli_query($conn,"INSERT INTO receipt_details(receiptId, sku, quantity) VALUES ('$receiptid', '$sku', '$qty')");
        }
        else
        {
            $_SESSION['cartStatus'] = "Oops! We don't have enough of the product '" . $row['product_name'] . "' to fulfill your order. Please try ordering a smaller quantity";
            header("Location: cart.php", true, 303);
            exit();
        }
    }

    header("location: receipt.php", true, 303);
    exit();
?>