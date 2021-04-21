<?php
    session_start();

    if(isset($_SESSION['isUser']) || isset($_SESSION['isEmployee'])){
        $userLoggedIn = $_SESSION['isUser'];
        $employeeLoggedIn = $_SESSION['isEmployee'];
    } else {
        $userLoggedIn = false;
        $employeeLoggedIn = false;
    }
?>

<?php require_once "include/header.php"; ?>
<?php require_once "config/connect.php" ?>

<title>Weekly Sales Report | Nuts and Bolts</title>
</head>

<body>

        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container">
                <a class="navbar-brand" href="index.php">Nuts and Bolts</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                    <div class="navbar-nav">
                        <a class="nav-link" href="index.php">Home</a>
                        <?php if($employeeLoggedIn): ?>
                            <div class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle active" href="#" id="navbarLightDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Products
                                </a>
                                <div class="dropdown-menu dropdown-menu-light" aria-labelledby="navbarLightDropdownMenuLink">
                                    <a class="dropdown-item" href="products.php">Products List</a>
                                    <a class="dropdown-item" href="add.php">Add Products</a>
                                    <a class="dropdown-item" href="update.php">Update Products</a>
                                    <a class="dropdown-item" href="addCategory.php">Add Categories</a>
                                    <a class="dropdown-item" href="salesreport.php">Product Sales</a>
                                </div>
						    </div>
                        <?php else: ?>
                            <a class="nav-link" href="products.php">Products</a>
                        <?php endif; ?> 
                        <a class="nav-link" href="faq.php">FAQ</a>
                        <a class="nav-link" href="contact.php">Contact Us</a>
                    </div>
                    <div class="navbar-nav ms-auto flex-nowrap">
                        <?php if($userLoggedIn): ?>
                            <div class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarLightDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <?php echo '<span>'. $_SESSION['username'] . '</span>' ?>
                                </a>
                                <div class="dropdown-menu dropdown-menu-light" aria-labelledby="navbarLightDropdownMenuLink">
                                    <a class="dropdown-item" href="history.php">Order History</a>
                                </div>
                            </div>
                            <span class="collapse show nav-link" id="navbarNavAltMarkup">|</span>
                            <a class="nav-link" href="logout.php">Logout</a>
                        <?php elseif($employeeLoggedIn): ?>
                            <div class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarLightDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <?php echo '<span>'. $_SESSION['firstname'] . '</span>' ?>
                                </a>
                                <div class="dropdown-menu dropdown-menu-light" aria-labelledby="navbarLightDropdownMenuLink">
                                    <a class="dropdown-item" href="history.php">Order History</a>
                                    <a class="dropdown-item" href="register.php">Register Employees</a>
                                </div>
                            </div>
                            <span class="collapse show nav-link" id="navbarNavAltMarkup">|</span>
                            <a class="nav-link" href="logout.php">Logout</a>
                        <?php else: ?>
                            <a class="nav-link" href="register.php">Register</a>
                            <span class="collapse show nav-link" id="navbarNavAltMarkup">|</span>
                            <a class="nav-link" href="login.php">Login</a>
                        <?php endif; ?>
                        <a class="nav-link" href="cart.php"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart4" viewBox="0 0 16 16">
                            <path d="M0 2.5A.5.5 0 0 1 .5 2H2a.5.5 0 0 1 .485.379L2.89 4H14.5a.5.5 0 0 1 .485.621l-1.5 6A.5.5 0 0 1 13 11H4a.5.5 0 0 1-.485-.379L1.61 3H.5a.5.5 0 0 1-.5-.5zM3.14 5l.5 2H5V5H3.14zM6 5v2h2V5H6zm3 0v2h2V5H9zm3 0v2h1.36l.5-2H12zm1.11 3H12v2h.61l.5-2zM11 8H9v2h2V8zM8 8H6v2h2V8zM5 8H3.89l.5 2H5V8zm0 5a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0zm9-1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0z"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!--Table begins here -->
    <br>
    <div class="container">
        <table class="table table-hover table-bordered">
            <thead>
                <tr>
                <th scope="col">Product Id</th>
                <th scope="col">Product Name</th>
                <th scope="col"><?php echo date('m/d/y', strtotime('-1 days')); ?></th>
                <th scope="col"><?php echo date('m/d/y', strtotime('-2 days')); ?></th>
                <th scope="col"><?php echo date('m/d/y', strtotime('-3 days')); ?></th>
                <th scope="col"><?php echo date('m/d/y', strtotime('-4 days')); ?></th>
                <th scope="col"><?php echo date('m/d/y', strtotime('-5 days')); ?></th>
                <th scope="col"><?php echo date('m/d/y', strtotime('-6 days')); ?></th>
                <th scope="col"><?php echo date('m/d/y', strtotime('-7 days')); ?></th>
                </tr>
            </thead>

            <!-- PHP begins here-->
            <?php  
            
                $query = "SELECT product_id, product_name, saleDate, quantity FROM inventory, receipts";
                $result = mysqli_query($conn, $query);

                $row = mysqli_fetch_array($result);

                foreach ($row as $query_result){

                

                $product_id = $row['product_id'];
                $product_name = $row['product_name'];
                $sale_date = $row['saleDate'];
                $quantity = $row['quantity'];


                echo("

                <tbody>
                    <tr>
                        <th scope='row'>$product_id</th><!-- product id goes here-->
                        <td>$product_name</td><!-- product_name goes here-->
                        <td>$quantity</td><!--receipts.saleDAte goes here-->
                        <td>$quantity</td>
                        <td>$quantity</td>
                        <td>$quantity</td>
                        <td>$quantity</td>
                        <td>$quantity</td>
                        <td>$quantity</td>
                    </tr>
                </tbody>
                        
                
                
                ");
                }
                

            ?>
        </table>
    </div>
<?php require_once "include/footer.php"; ?>