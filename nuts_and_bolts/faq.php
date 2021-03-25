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
			<p>Monday - Friday 7 AM - 10 PM; Saturday 8 AM - 8 PM; Sunday 10 AM - 5 PM</p>

			<h2>What is our return policy</h2>
			<p>All returns accepted within 7 days of purchase with no questions asked.</p>

			<h2>Our Story</h2>
			<p>Founded in 1976, Nuts and Bolts was a product of hard work paying off during hard times. Nuts and Bolts merely started as a dream until store founder and former veteran James Augustus Deathblade was able to make it a reality. 
			In his mid-thirties working as a bartender and fixing his friends' motorcycles at the local VA by day, Deathblade loved working with his hands but ends weren't meeting; he needed a change. 
			Over the course of five long years, James pooled together what he could and was finally able to launch Nuts and Bolts and hasn't looked back since. Nuts and Bolts keeps prices low so that everyone has a chance to get their job done!</p>
		</div>
	
<?php require_once "include/footer.php"; ?>