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

		<title>FAQ | Nuts and Bolts</title>

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
						<a class="nav-link active" aria-current="page" href="faq.php">FAQ</a>
						<a class="nav-link" href="contact.php">Contact Us</a>
					  	<?php if($employeeLoggedIn): ?>
							<a class="nav-link" href="add.php">Add Products</a>
							<a class="nav-link" href="update.php">Update Products</a>
							<a class="nav-link" href="register.php">Register Employee</a>
            			<?php endif; ?>
						<a class="nav-link" href="cart.php"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart4" viewBox="0 0 16 16">
                        <path d="M0 2.5A.5.5 0 0 1 .5 2H2a.5.5 0 0 1 .485.379L2.89 4H14.5a.5.5 0 0 1 .485.621l-1.5 6A.5.5 0 0 1 13 11H4a.5.5 0 0 1-.485-.379L1.61 3H.5a.5.5 0 0 1-.5-.5zM3.14 5l.5 2H5V5H3.14zM6 5v2h2V5H6zm3 0v2h2V5H9zm3 0v2h1.36l.5-2H12zm1.11 3H12v2h.61l.5-2zM11 8H9v2h2V8zM8 8H6v2h2V8zM5 8H3.89l.5 2H5V8zm0 5a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0zm9-1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0z"/></svg></a>
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
					</div>
				</div>
			</div>
		</nav>

		<div class="container">
			<h1>Frequently Asked Questions (FAQ)</h1>

			<h2>What are Nuts and Bolts Hardware Store hours?</h2>
			<p>Monday - Friday 7 AM - 10 PM; Saturday 8 AM - 8 PM; Sunday 10 AM - 6 PM</p>

			<h2>What is our return policy</h2>
			<p>All returns accepted within 7 days of purchase with no questions asked.</p>

			<h2>Our Story</h2>
			<p>Founded in 1976, Nuts and Bolts was a product of hard work paying off during hard times. Nuts and Bolts merely started as a dream until store founder and former veteran James Augustus Deathblade was able to make it a reality. 
			In his mid-thirties working as a bartender and fixing his friends' motorcycles at the local VA by day, Deathblade loved working with his hands but ends weren't meeting; he needed a change. 
			Over the course of five long years, James pooled together what he could and was finally able to launch Nuts and Bolts and hasn't looked back since. Nuts and Bolts keeps prices low so that everyone has a chance to get their job done!</p>
		</div>
	
<?php require_once "include/footer.php"; ?>