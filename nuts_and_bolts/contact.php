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

        <title>Contact Us | Nuts and Bolts</title>

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
                        <a class="nav-link active" aria-current="page" href="contact.php">Contact Us</a>
                        <?php if($employeeLoggedIn): ?>
                            <a class="nav-link" href="add.php">Add Products</a>
                            <a class="nav-link" href="update.php">Update Products</a>
                            <a class="nav-link" href="register.php">Register Employee</a>
                        <?php endif; ?> 
                    </div>
                    <div class="navbar-nav ms-auto flex-nowrap">
                    <?php if($userLoggedIn): ?>
                        <?php echo '<p class="nav-link">'. $_SESSION['username'] . '</p>' ?>
                        <span class="collapse show nav-link" id="navbarNavAltMarkup">|</span>
                        <a class="nav-link" href="logout.php">Logout</a>
                    <?php elseif($employeeLoggedIn): ?>
                        <?php echo '<p class="nav-link">'. $_SESSION['firstname'] . '</p>' ?>
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
            <h1>Get In touch with us</h1>
            <h2>Email</h2>
            <p>Nutsandbolts@gmail.com</p>
            <h2>Phone number</h2>
            <p> +1 (614) 555-5555 </p>
            <h2> Address </h2>
            <p> 69 E Magon Blv, Columbus, TX 68734</p>
        </div>

<?php require_once "include/footer.php"; ?>