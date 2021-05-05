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
<?php
    require_once "config/connect.php";

    $name = '';

    $errors = array('name'=>'');

    if (isset($_SESSION['isEmployee']) && $_SESSION['isEmployee'] == true) {
        ;
    } else {
        $_SESSION['loginmessage'] = True;
        header("location: login.php");
    }

    if(isset($_POST['submit'])){

        if(empty($_POST['name'])) {
            $errors['name'] = 'A category name is required';
        } else {
            $name = $_POST['name'];
            if(!preg_match('/^[\s\S]{1,255}$/', $name)){
                $errors['name'] = 'That category name is too long';
            }
        }

        if(!array_filter($errors))
        {
            $stmt = $conn->prepare("INSERT INTO categories(name) VALUES(?)");
            $stmt->bind_param("s", $name);

            $name = mysqli_real_escape_string($conn, $_POST['name']);
            $name = stripslashes($name);

            if($stmt->execute()) {
                $_SESSION['postStatus'] = true;
                $_SESSION['name'] = htmlspecialchars($name);
                header("Location: {$_SERVER['REQUEST_URI']}", true, 303);
                $stmt->close();
                $conn->close();
                exit;
            }
        } else {
            $_SESSION['postStatus'] = false;
        }
        
    }
?>
<?php require_once "include/header.php"; ?>

<title>Add Category | Nuts and Bolts</title>

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
            
            <h1>Add Category</h1>
            <div class="container bg-light text-dark">
                <form class="row g-3" action="addCategory.php" method="POST">
                    <div class="form-group col-12">
                        <label for="categoryName" class="form-label">Category Name:</label>
                        <input type ="text" class="form-control" id="categoryName" name ="name" value = "<?php echo htmlspecialchars($name); ?>">
                        <span class="text-danger">
                            <?php echo $errors['name']; ?>
                        </span>
                    </div>
                    <button class="btn btn-primary" type="submit" name="submit">Add Category</button>

                    <?php                
                        if(isset($_SESSION['postStatus']) && $_SESSION['postStatus']) {
                    ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo $_SESSION['name']; ?> was added successfully to categories!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php
                        } elseif(isset($_SESSION['postStatus']) && !$_SESSION['postStatus']) {
                    ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php if($name =='') { echo 'This category';}else{ echo htmlspecialchars($name);} ?> could not be added due to an error.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php
                        }
                        unset($_SESSION['postStatus']);
                        unset($_SESSION['name']);
                    ?>

                </form>
            </div>

        
        </div>


<?php require_once "include/footer.php"; ?>