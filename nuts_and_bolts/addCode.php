<?php
    session_start();

    if(isset($_SESSION['isUser']) || isset($_SESSION['isEmployee'])){
        $userLoggedIn = $_SESSION['isUser'];
        $employeeLoggedIn = $_SESSION['isEmployee'];
    } else {
        $userLoggedIn = false;
        $employeeLoggedIn = false;
    }

    if (isset($_SESSION['isEmployee']) && $_SESSION['isEmployee'] == true) {
        ;
    } else {
        $_SESSION['loginmessage'] = True;
        header("location: login.php");
    }
?>
<?php
    require_once "config/connect.php";

    $code = '';
    $dollarsOff = '';
    $purchaseAmount = '';
    $startDate = '';
    $endDate = '';

    $errors = array('code' => '', 'dollarsOff' => '', 'purchaseAmount'=> '', 'startDate'=> '', 'endDate'=> '');
    if(isset($_POST['submit'])){

        if(empty($_POST['code'])) {
            $errors['code'] = 'A discount code is required';
        } else {
            $code = $_POST['code'];
            if(!preg_match('/^[\s\S]{5,15}$/', $code)){
                $errors['code'] = 'Discount codes must be between 5-15 characters long';
            }
        }

        if(empty($_POST['dollarsOff'])) {
            $errors['dollarsOff'] = 'A discount amount is required';
        } else {
            $dollarsOff = $_POST['dollarsOff'];
            if($dollarsOff < 1){
                $errors['dollarsOff'] = 'Discount must be one or more dollars';
            }
        }

        if(empty($_POST['purchaseAmount'])) {
            $errors['purchaseAmount'] = 'A total purchase amount is required';
        } else {
            $purchaseAmount = $_POST['purchaseAmount'];
            if($purchaseAmount < 2){
                $errors['purchaseAmount'] = 'Total purchase amount must be greater than one dollar';
            }
        }

        if(empty($_POST['startDate'])) {
            $errors['startDate'] = "The discount's starting date is required";
        } else {
            $startDate = $_POST['startDate'];
            if(strtotime($startDate) < strtotime('now')){
                $errors['startDate'] = 'Start date must be the current date or later';
            }
        }

        if(empty($_POST['endDate'])) {
            $errors['endDate'] = "The discount's ending date is required";
        } else {
            $endDate = $_POST['endDate'];
            if(strtotime($endDate) < strtotime($startDate)){
                $errors['endDate'] = 'End date must be after start date';
            }
        }


        if(!array_filter($errors))
        {
            $stmt = $conn->prepare("INSERT INTO discounts(code, dollars_off, purchase_amount, start_date, end_date) VALUES(?, ?, ?, ?, ?)");
            $stmt->bind_param("siiss", $code, $dollarsOff, $purchaseAmount, $startDate, $endDate);

            $code = mysqli_real_escape_string($conn, $_POST['code']);
            $dollarsOff = mysqli_real_escape_string($conn, $_POST['dollarsOff']);
            $purchaseAmount = mysqli_real_escape_string($conn, $_POST['purchaseAmount']);
            $startDate = mysqli_real_escape_string($conn, $_POST['startDate']);
            $endDate = mysqli_real_escape_string($conn, $_POST['endDate']);

            $code = stripslashes($code);
            $dollarsOff = stripslashes($dollarsOff);
            $purchaseAmount = stripslashes($purchaseAmount);
            $startDate = stripslashes($startDate);
            $endDate = stripslashes($endDate);


            if($stmt->execute()) {
                $_SESSION['code'] = htmlspecialchars($code);
                $_SESSION['postStatus'] = true;
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

<title>Add Discount Code | Nuts and Bolts</title>

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
                                    <a class="dropdown-item" href="addCode.php">Add Discount Code</a>
                                </div>
						    </div>
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
            
            <h1>Add Discount Code</h1>
            <div class="container bg-light text-dark">
                <form class="row g-3" action="addCode.php" method="POST">
                    <div class="form-group col-12">
                        <label for="code" class="form-label">Discount Code:</label>
                        <input type ="text" class="form-control" name ="code" id="code" value = "<?php echo htmlspecialchars($code); ?>">
                        <span class="text-danger">
                            <?php echo $errors['code']; ?>
                        </span>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="dollarsOff" class="form-label">Dollars Off:</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text">$ </span>
                            <input type="number" class="form-control" name="dollarsOff" id="dollarsOff">
                        </div>
                        <span class="text-danger">
                            <?php echo $errors['dollarsOff']; ?>
                        </span>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="purchaseAmount" class="form-label">Purchase Amount:</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text">$ </span>
                            <input type="number" class="form-control" name="purchaseAmount" id="purchaseAmount">
                        </div>
                        <span class="text-danger">
                            <?php echo $errors['purchaseAmount']; ?>
                        </span>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="startDate" class="form-label">Start Date:</label>
                        <input class="form-control" type="date" name="startDate" id="startDate">
                        <span class="text-danger">
                            <?php echo $errors['startDate']; ?>
                        </span>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="endDate" class="form-label">End Date:</label>
                        <input class="form-control" type="date" name="endDate" id="endDate">
                        <span class="text-danger">
                            <?php echo $errors['endDate']; ?>
                        </span>
                    </div>
                    <button class="btn btn-primary" type="submit" name="submit">Add Code</button>

                    <?php                
                        if(isset($_SESSION['postStatus']) && $_SESSION['postStatus']) {
                    ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo $_SESSION['code']; ?> was added successfully to discounts!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php
                        } elseif(isset($_SESSION['postStatus']) && !$_SESSION['postStatus']) {
                    ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php if($code =='') { echo 'This discount code';}else{ echo htmlspecialchars($code);} ?> could not be added due to an error.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php
                        }
                        unset($_SESSION['postStatus']);
                        unset($_SESSION['code']);
                    ?>

                </form>
            </div>

        
        </div>


<?php require_once "include/footer.php"; ?>