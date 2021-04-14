<?php
    session_start();

    if(isset($_SESSION['isUser']) || isset($_SESSION['isEmployee'])){
        $userLoggedIn = $_SESSION['isUser'];
        $employeeLoggedIn = $_SESSION['isEmployee'];
    } else {
        $userLoggedIn = false;
        $employeeLoggedIn = false;
    }

	if(isset($_POST['clear'])){
		$_SESSION['cart'] = array();
		header("Location: {$_SERVER['REQUEST_URI']}", true, 303);
	}

	if(isset($_POST['delete'])) {
        unset($_SESSION['cart'][$_POST['delete']]);
		header("Location: {$_SERVER['REQUEST_URI']}", true, 303);
    }
?>
<?php require_once "include/header.php"; ?>
<?php require_once "config/connect.php"; ?>
		<title>Your Cart</title>

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
            <h1>Cart</h1>
			
			<?php
			if(isset($_SESSION['cart']) && count($_SESSION['cart']) != 0) {
				$total = 0;
				echo(
					"<div class='card-body'>
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
									<tbody>"
					);
		
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
						"<form action='cart.php' method='POST'>
							<tr>
								<td class='p-4'>
									<div class='media align-items-center'>
										<div class='media-body'>
											<strong>$name</strong>
											<br>
											<small>
												<span class='text-muted'>$description</span>
											</small>
											<input name='delete' value='$sku' style='display: none;'>
											<button class='btn btn-secondary btn-sm delete' type='submit' style='float: right;'>Remove from cart</button>
										</div>
									</div>
								</td>
								<td class='text-right font-weight-semibold align-middle p-4'>$$price</td>
								<td class='align-middle p-4'>$qty</td>
								<td class='text-right font-weight-semibold align-middle p-4'>$$subtotal</td>
							</tr>
							</form>"
					);
				  }
				}
				echo(
					"				</tbody>
								</table>
						</div>
						<br>
						<div class='row'>
							<div class='col-md-3'>
								<form action='cart.php' method='POST'>
									<input class='btn btn-danger' type='submit' name='clear' value='Clear Cart'>
								</form>
							</div>
							<div class='col-md-8 text-end'>
								<h4><strong>Total price:</strong></h4>
							</div>
							<div class='col-md-1 ms-auto text-end'>
								<h4>$$total</h4>
							</div>			
						</div>
						<div class='row'>
							<div class='col-md-1 ms-auto'>
								<a class='btn btn-primary' href='checkout.php' role='button'>Checkout</a>
							</div>
						</div>
						<form action='cart.php' method='POST'>
							<input name='cmd' value='true' style='display: none;'>
							<button class='btn btn-primary' type='submit'>Clear Cart</button>
						</form>
					</div>
					"
				);
			} else {
				?>
				<div class="alert alert-secondary" role="alert">
				Your cart is currently empty!
				</div>
				<?php 
			}
			if(isset($_SESSION["cartStatus"])) {
			?>
				<div class="alert alert-danger alert-dismissible fade show" style="margin-top: 1.7em;" role="alert">
					<?php echo $_SESSION["cartStatus"]; ?>
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
				</div>
			<?php
				unset($_SESSION["cartStatus"]); 
				}
			?>
		</div>
		
<?php require_once "include/footer.php"; ?>
