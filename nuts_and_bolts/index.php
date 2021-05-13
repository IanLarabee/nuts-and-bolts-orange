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
<?php require_once "config/connect.php"; ?>

        <title>Home | Nuts and Bolts</title>

    </head>
    <body>

        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container">
                <a class="navbar-brand" href="index.php"><img src="assets/nutsandboltslogo.png" alt="Nuts and Bolts" id="logo"></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                    <div class="navbar-nav">
                        <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                        <?php if($employeeLoggedIn): ?>
                            <div class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarLightDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Products
                                </a>
                                <div class="dropdown-menu dropdown-menu-light" aria-labelledby="navbarLightDropdownMenuLink">
                                    <a class="dropdown-item" href="products.php">Products List</a>
                                    <a class="dropdown-item" href="add.php">Add Products</a>
                                    <a class="dropdown-item" href="update.php">Update Products</a>
                                    <a class="dropdown-item" href="addCategory.php">Add Categories</a>
                                    <a class="dropdown-item" href="salesreport.php">Product Sales</a>
                                    <a class="dropdown-item" href="addCode.php">Add Discount Code</a>
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
        
        <div class="container">
            <h1>Great Deals!</h1>
            <?php 
                $result = mysqli_query($conn, "SELECT product_id, product_name, price, description FROM inventory WHERE great_deal = 1");
                
                echo '  <div id="carouselExampleDark" class="carousel carousel-dark slide border border-2" data-bs-ride="carousel">
                    <div class="carousel-indicators mt-4 mb-0">
                        <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>';

                for($i = 1; $i < mysqli_num_rows($result); $i++){
                    echo'
                        <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="'.$i.'" aria-label="Slide '.($i + 1).'"></button>';
                }
                
                echo '
                    </div>
                    <form action="products.php" method="POST">
                        <div class="carousel-inner">';
                $row = mysqli_fetch_array($result);

                $prodId = $row['product_id'];
                    $imgSql = "SELECT * FROM images WHERE product_id = $prodId";
                    $imgResult = mysqli_query($conn, $imgSql);
                    $imgRow = $imgResult->fetch_assoc();

                    echo '
                            <div class="carousel-item active">
                            '.(mysqli_num_rows($imgResult) == 0 ? '<h5>Image Unavailable</h5>' : '<input type="image" src="data:image/jpg;charset=utf8;base64,'.base64_encode($imgRow['imagedata']).'" class="d-block w-100 img-fluid py-1" style="height: 350px; width: 700px; object-fit: scale-down;" name="category[great_deal]" value="true">').'
                                <div class="carousel-caption d-none d-md-block bg-light border-top" style="position: relative; left: 0; top: 0;">
                                    <h4>Get this '.$row['product_name'].' for only </h4>
                                    <h3 class="text-success">$'.$row['price'].'</h3>
                                    <h5>along with other great deals!</h5>
                                </div>
                                <div class="carousel-caption d-block d-sm-none shadow bg-body rounded">
                                    <h5>'.$row['product_name'].'</h5>
                                    <h3 class="text-success">$'.$row['price'].'</h3>
                                </div>
                            </div>';

                while($row = mysqli_fetch_array($result))
                {
                    $prodId = $row['product_id'];
                    $imgSql = "SELECT * FROM images WHERE product_id = $prodId";
                    $imgResult = mysqli_query($conn, $imgSql);
                    $imgRow = $imgResult->fetch_assoc();

                    echo '
                            <div class="carousel-item">
                            '.(mysqli_num_rows($imgResult) == 0 ? '<h5>Image Unavailable</h5>' : '<input type="image" src="data:image/jpg;charset=utf8;base64,'.base64_encode($imgRow['imagedata']).'" class="d-block w-100 img-fluid py-1" style="height: 350px; width: 700px; object-fit: scale-down;" name="category[great_deal]" value="true">').'
                                <div class="carousel-caption d-none d-md-block bg-light border-top" style="position: relative; left: 0; top: 0;">
                                    <h4>'.$row['product_name'].'</h4>
                                    <h5>Only</h5>
                                    <h3 class="text-success">$'.$row['price'].'</h3>
                                </div>
                                <div class="carousel-caption d-block d-sm-none shadow bg-body rounded">
                                    <h5>'.$row['product_name'].'</h5>
                                    <h3 class="text-success">$'.$row['price'].'</h3>
                                </div>
                            </div>';

                }

                echo '
                    </form>
                    </div>  
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>';
                mysqli_close($conn);
                
            ?>
        </div>  
<?php require_once "include/footer.php"; ?>
