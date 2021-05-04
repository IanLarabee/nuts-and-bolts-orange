<?php
    session_start();

    if(isset($_SESSION['isUser']) || isset($_SESSION['isEmployee'])){
        $userLoggedIn = $_SESSION['isUser'];
        $employeeLoggedIn = $_SESSION['isEmployee'];
    } else {
        $userLoggedIn = false;
        $employeeLoggedIn = false;
    }
    $total = 0;
	$receiptTotal = 0
?>
<?php require_once "include/header.php" ?>
<?php require_once "config/connect.php" ?>

		<title>Your Receipt</title>

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
                                <a class="nav-link dropdown-toggle" href="#" id="navbarLightDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
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

		<div class="container">
				<h1>Receipt <?php if(isset($_SESSION['receiptID'])) { echo("#: ".$_SESSION['receiptID']);} elseif(isset($_GET['receiptID'])){ echo("#: ".$_GET['receiptID']);}?></h1>
				<?php
					if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {	
				?>
				<div class='card-body'>
					<div class='table-responsive'>
						<table class='table table-bordered m-0'>
							<thead>
								<tr>
									<th class='text-center py-3 px-4' style='min-width: 400px;'>Product</th>
									<th class='text-right py-3 px-4' style='width: 100px;'>Price</th>
									<th class='text-center py-3 px-4' style='width: 120px;'>Quantity</th>
									<th class='text-right py-3 px-4' style='width: 100px;'>Subtotal</th>
								</tr>
							</thead>
							<tbody>
				<?php
					foreach($_SESSION['cart'] as $sku => $qty) {
				
					$query  = "SELECT * FROM inventory WHERE sku='$sku'";
					$result = mysqli_query($conn, $query);

					if(mysqli_num_rows($result) == 1) {
						$row = mysqli_fetch_array($result);
				
						$id = $row['product_id'];
						$name = $row['product_name'];
						$description = $row['description'];
						$price = $row['price'];
						$subtotal = $price * $qty;
						$total = $total + $subtotal;

						echo(
						"
						<tr>
							<td class='p-4'>
								<div class='media align-items-center'>
									<div class='media-body'>
										<strong>$name</strong>
										<br>
										<small>
											<span class='text-muted'>$description</span>
										</small>
									</div>
								</div>
							</td>
							<td class='text-right font-weight-semibold align-middle p-4'>$$price</td>
							<td class='align-middle p-4'>$qty</td>
							<td class='text-right font-weight-semibold align-middle p-4'>$$subtotal</td>
						</tr>"
						);
					}
					}
					echo(
						"</tbody>
						</table>
						</div>
							<div class='text-right mt-4'>
								<h4><strong>Total price:</strong> $$total</h4>
							</div>
						</div>"
					);
					?>
			</div>
			<?php 
				unset($_SESSION['cart']);
				unset($_SESSION['receiptID']); 
			}else if(isset($_GET['receiptID'])) {
				
				$receiptsResult = mysqli_query($conn, 'SELECT receiptId, saleDate, username FROM receipts WHERE receiptId = \''.$_GET['receiptID'].'\' ORDER BY saleDate DESC');
                if(mysqli_num_rows($receiptsResult) == 0) {
                    echo '
                        <div class="alert alert-secondary" role="alert">
					        <span>No order history found!</span>
				        </div>
                    ';
                }
                while($receipt = mysqli_fetch_array($receiptsResult)) {
                    
                    if($receipt['username'] != $_SESSION['username']) {
                        $_SESSION['loginmessage'] = True;
                        header("location: login.php");
                    }

                    $receiptDetailsResult = mysqli_query($conn, 'SELECT sku, quantity, salePrice FROM receipt_details WHERE receiptId = \''.$receipt['receiptId'].'\'');
                    
                    while($receiptDetails = mysqli_fetch_array($receiptDetailsResult)) {
                        $receiptTotal = $receiptTotal + ($receiptDetails['quantity'] * $receiptDetails['salePrice']);
                    }

                    echo '
                        <div class="row">
                            <div class="col">
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <div class="row row-cols-auto">
                                            <div class="col border-end">
                                                <div class="row">
                                                    <span><small class="text-muted">Order Placed</small></span>
                                                </div>
                                                <div class="row">
                                                    <span>'.date_format(date_create($receipt['saleDate']), "n/d/y h:i A").'</span>
                                                </div>
                                            </div>
                                            <div class="col me-auto">
                                                <div class="row">
                                                    <span><small class="text-muted">Total</small></span>
                                                </div>
                                                <div class="row">
                                                    <span>$'.$receiptTotal.'</span>
                                                </div>
                                            </div>
                                            <div class="col ms-auto">
                                                <div class="row">
                                                    <span class="text-end"><small class="text-muted">Receipt ID</small></span>
                                                </div>
                                                <div class="row">
                                                    <span class="text-end">'.$receipt['receiptId'].'</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="container">';

                    $receiptTotal = 0;

                    $receiptDetailsResult = mysqli_query($conn, 'SELECT sku, quantity, salePrice FROM receipt_details WHERE receiptId = \''.$receipt['receiptId'].'\'');
                    
                    while($receiptDetails = mysqli_fetch_array($receiptDetailsResult)) {
                        $productDetails = mysqli_query($conn, 'SELECT product_name, price FROM inventory WHERE sku = \''.$receiptDetails['sku'].'\'');
                        
                        if(mysqli_num_rows($productDetails) == 0) {
                            echo '
                                <div class="row row-cols-auto g-0 border-bottom">
                                    <div class="col p-3">
                                        <img class="img-fluid" style="width:100px;">
                                    </div>
                                    <div class="col me-auto">
                                        <div class="card-body">
                                            <h5 class="card-title">This Item No Longer Exists</h5>
                                            <p class="card-text"><small class="text-muted">Price Not Available</small></p>
                                        </div>
                                    </div>
                                </div>';

                            continue;
                        }
                        
                        $productDetails = mysqli_fetch_array($productDetails);
                        
                        echo '
                            <div class="row row-cols-auto g-0 border-bottom">
                                <div class="col p-3">
                                    <img class="img-fluid" style="width:100px;">
                                </div>
                                <div class="col me-auto">
                                    <div class="card-body">
                                        <h5 class="card-title">'.$productDetails['product_name'].'</h5>
                                        <h6 class="card-subtitle mb-2 text-muted">Quantity: '.$receiptDetails['quantity'].'</h6>
                                        <p class="card-text"><small class="text-muted">'.$productDetails['price'].'</small></p>
                                    </div>
                                </div>
                            </div>';
                    }
                    
                    echo '
                                    </div>
                                </div>
                            </div>
                        </div>';
                }

			} 
			else { ?>
				<div class="alert alert-secondary" role="alert">
					<span>No receipt to be shown!</span>
				</div>
			<?php } ?>
		</div>
<?php require_once "include/footer.php"; ?>
