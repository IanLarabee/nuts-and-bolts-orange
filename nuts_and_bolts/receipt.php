<?php
    session_start();

    if(isset($_SESSION['isUser']) || isset($_SESSION['isEmployee'])){
        $userLoggedIn = $_SESSION['isUser'];
        $employeeLoggedIn = $_SESSION['isEmployee'];
    } else {
        $userLoggedIn = false;
        $employeeLoggedIn = false;
    }
	$total = 0
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
						<a class="nav-link" href="products.php">Products</a>
						<a class="nav-link" href="faq.php">FAQ</a>
						<a class="nav-link" href="contact.php">Contact Us</a>
                
					  	<?php if($employeeLoggedIn): ?>
							<a class="nav-link" href="add.php">Add Products</a>
							<a class="nav-link" href="update.php">Update Products</a>
							<a class="nav-link" href="register.php">Register Employee</a>
            			<?php endif; ?>
					</div>
					<div class="navbar-nav ms-auto flex-nowrap">
					<?php if($userLoggedIn): ?>
                        <?php echo '<span class="nav-link">'. $_SESSION['username'] . '</span>' ?>
                        <span class="collapse show nav-link" id="navbarNavAltMarkup">|</span>
                        <a class="nav-link" href="logout.php">Logout</a>
                    <?php elseif($employeeLoggedIn): ?>
                        <?php echo '<span class="nav-link">'. $_SESSION['firstname'] . '</span>' ?>
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
				<h1>Receipt <?php if(isset($_SESSION['receiptID'])) { echo("#: ".$_SESSION['receiptID']);}?></h1>
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
			} else { ?>
				<div class="alert alert-secondary" role="alert">
					<span>No checkout has been made recently!</span>
				</div>
			<?php } ?>
		</div>
<?php require_once "include/footer.php"; ?>