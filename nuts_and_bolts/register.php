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
<?php require_once "include/header.php" ?>
<?php
    require_once "config/connect.php";


    $userGroup = "users";

    if(isset($_SESSION['isEmployee']) && $_SESSION['isEmployee']) {
        $userGroup = "employees";
    }

    $firstName = "";
    $lastName = "";
    $username = "";

    //Array for user errors
    $errors = array('firstName'=>'', 'lastName'=>'', 'username'=>'', 'password'=>'', 'confirm' =>'', 'empty'=>'');

    //If user submits form
    if(isset($_POST['submit'])) {

        //Store their responses 
        if(isset($_SESSION['isEmployee']) && $_SESSION['isEmployee']) {
            $firstName = $_POST['firstName'];
            $lastName = $_POST['lastName'];
        }
        $username = $_POST['username'];
        $password = $_POST['password'];
        $confirm = $_POST['confirm'];

        //First name requirements
        $fCharNum = preg_match('/^[\s\S]{1,255}$/', $firstName);

        //Last name requirements
        $lCharNum = preg_match('/^[\s\S]{1,255}$/', $lastName);

        //Username requirements
        $uCharNum = preg_match('/^[\s\S]{1,255}$/', $username);
        $uUpperCase = preg_match('@[A-Z]@', $username);
        $uSpaces = preg_match("/\\s/", $username);

        //Password requirements
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number = preg_match('@[0-9]@', $password);
        $specialChars = preg_match('@[^\w]@', $password);

        //If any fields are empty, give error
        if(isset($_SESSION['isEmployee']) && $_SESSION['isEmployee']) {
            if(empty($firstName) || empty($lastName)) {
                $errors['empty'] = "All fields are required";
            }
            //Check first name requirements
            else if(!$fCharNum)
            {
                $errors['firstName'] = "First name is too long";
            }
            //Check last name requirements
            else if(!$lCharNum)
            {
                $errors['lastName'] = "Last name is too long";
            }
        }
        
        if(empty($username) || empty($password) || empty($confirm))
        {
            $errors['empty'] = "All fields are required";
        }
        //Check username requirements
        else if ($uUpperCase || $uSpaces || !$uCharNum)
        {
            $errors['username'] = "Username should only be lowercase, no spaces";
        }
        //Check password requirements
        else if (!$uppercase || !$lowercase || !$number || !$specialChars)
        {
            $errors['password'] = "Password should include at least one uppercase letter, one lowercase letter, one number, and one special character.";
        }
        else
        {
            $u_sql = "SELECT * FROM $userGroup WHERE username='$username'";
            $result = mysqli_query($conn, $u_sql);

            //If username already exists, give error
            if (mysqli_num_rows($result) > 0)
            {
                $errors['username'] = "That username is taken";
            }
            //If passwords don't match, give error
            else if ($password !== $confirm)
            {
                $errors['confirm'] = "Passwords do not match";
            }
            //Enter user in DB
            else
            {   
                $stmt = $conn->prepare("INSERT INTO $userGroup(first_name, last_name, username, password) VALUES(?, ?, ?, ?)");
                $stmt->bind_param("ssss", $firstName, $lastName, $username, $hash);
                
                if(isset($_SESSION['isEmployee']) && $_SESSION['isEmployee']) {
                    $firstName = mysqli_real_escape_string($conn, $_POST['firstName']);
                    $lastName = mysqli_real_escape_string($conn, $_POST['lastName']);
                }
                $username = mysqli_real_escape_string($conn, $_POST['username']);
                $password = mysqli_real_escape_string($conn, $_POST['password']);
                $hash = password_hash($password, PASSWORD_BCRYPT);

                if($stmt->execute())
                {
                    $_SESSION['postStatus'] = true;
                    $_SESSION['username'] = htmlspecialchars($username);
                    header("Location: {$_SERVER['REQUEST_URI']}", true, 303);
                    header("Location: login.php");
                    $stmt->close();
                    $conn->close();
                    exit;
                }
            }
        }
    }
?>

<title>Registration | Nuts and Bolts</title>
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
                    <a class="nav-link" href="Cart.php"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart4" viewBox="0 0 16 16">
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
                    <a class="nav-link active" aria-current="page" href="register.php">Register</a>
                    <span class="collapse show nav-link" id="navbarNavAltMarkup">|</span>
                    <a class="nav-link" href="login.php">Login</a>
                <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- This is the Registration form-->
    <div class="container">
        <h1>Register<?php if(isset($_SESSION['isEmployee']) && $_SESSION['isEmployee']) { echo "Employees"; }?></h1>
        
        <div class="container bg-light text-dark">
            <form class="row g-3" action="register.php" method="POST">
                <?php if(isset($_SESSION['isEmployee']) && $_SESSION['isEmployee']) { ?>
                    <!--firstName text box-->
                    <div class="form-group col-md-6">
                        <label for="firstName" class="form-label">First Name:</label>
                        <input type="text" class="form-control" name="firstName" id="firstName" value = "<?php echo htmlspecialchars($firstName); ?>">
                        <span class="text-danger">
                            <?php echo $errors['firstName']; ?>
                        </span>
                    </div>
                    <!--lastName text box-->
                    <div class="form-group col-md-6">
                        <label for="lastName" class="form-label">Last Name:</label>
                        <input type="text" class="form-control" name="lastName" id="lastName" value = "<?php echo htmlspecialchars($lastName); ?>">
                        <span class="text-danger">
                            <?php echo $errors['lastName']; ?>
                        </span>
                    </div>
                <?php } ?>
                <!--username text box-->
                <div class="form-group col-12">
                    <label for="username" class="form-label">Username:</label>
                    <input type="text" class="form-control" name="username" id="username" value = "<?php echo htmlspecialchars($username); ?>">
                    <span class="text-danger">
                        <?php echo $errors['username']; ?>
                    </span>
                </div>
                <!-- password text box-->
                <div class="form-group col-12">
                    <label for="password" class="form-label">Password:</label>
                    <input type="password" class="form-control" name="password" id="password">
                    <span class="text-danger">
                        <?php echo $errors['password']; ?>
                    </span>
                </div>
                <!--confirm password text box-->
                <div class="form-group col-12">
                    <label for="confirm" class="form-label">Confirm Password:</label>
                    <input type="password" class="form-control" name="confirm" id="confirm">
                    <span class="text-danger">
                        <?php echo $errors['confirm']; ?>
                    </span>
                    <span class="text-danger">
                        <?php echo $errors['empty']; ?>
                    </span>
                </div>
                <!--The Register button-->
                <button class="btn btn-primary" type="submit" name="submit">Register</button>
                <!--Success/fail message-->
                <?php                
                        if(isset($_SESSION['postStatus']) && $_SESSION['postStatus']) {
                    ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo $_SESSION['username']; ?> was successfully registered!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php
                        } elseif(isset($_SESSION['postStatus']) && !$_SESSION['postStatus']) {
                    ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php if($username =='') { echo 'This user';}else{ echo htmlspecialchars($username);} ?> could not be registered due to an error.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php
                        }
                        unset($_SESSION['postStatus']);
                        unset($_SESSION['username']);
                    ?>
            </form>
        </div>
    </div>
<?php require_once "include/footer.php"; ?>