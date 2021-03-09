<?php require_once "include/header.php" ?>
<?php
    require_once "config/connect.php";

    session_start();

    $firstName = "";
    $username = "";

    //Array for user errors
    $errors = array('username'=>'', 'password'=>'', 'confirm' =>'', 'empty'=>'');

    //If user submits form
    if(isset($_POST['register'])) {

        //Store their responses 
        $firstName = $_POST['firstName'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $confirm = $_POST['confirm'];

        //Username requirements
        $uUpperCase = preg_match('@[A-Z]@', $username);
        $uSpaces = preg_match("/\\s/", $username);

        //Password requirements
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number = preg_match('@[0-9]@', $password);
        $specialChars = preg_match('@[^\w]@', $password);

        //If any fields are empty, give error
        if(empty($firstName) || empty($username) || empty($password) || empty($confirm))
        {
            $errors['empty'] = "All fields are required";
        } 
        //Check username requirements
        else if ($uUpperCase || $uSpaces)
        {
            $errors['username'] = "Username should only be lowercase with no spaces";
        }
        //Check password requirements
        else if (!$uppercase || !$lowercase || !$number || !$specialChars)
        {
            $errors['password'] = "Password should include at least one uppercase letter, one lowercase letter, one number, and one special character.";
        }
        else
        {
            $u_sql = "SELECT * FROM employees WHERE username='$username'";
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
                $hash = password_hash($password, PASSWORD_BCRYPT);
                $sql = "INSERT INTO employees(username, password) VALUES('$username', '$hash')";
            }
        }
    }
?>

<title>Register Employee | Nuts and Bolts</title>
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class = "container">
                <a class="navbar-brand" href="index.php">Nuts and Bolts</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                    <div class="navbar-nav">
                        <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                        <a class="nav-link" href="products.php">Products</a>
                        <a class="nav-link" href="add.php">Add Products</a>
                        <a class="nav-link" href="update.php">Update Products</a>
                        <a class="nav-link" href="faq.php">FAQ</a>
                        <a class="nav-link" href="contact.php">Contact Us</a>
                        <a class="nav-link" href="register.php">Register Employee</a>
                    </div>
                </div>
            </div>
        </nav>


    <!-- This is the Register Employee form-->
    <div class="container">
        <h1>Register Employee</h1>
        
        <div class="container bg-light text-dark">
            <form >
                <!--user name text box-->
                <div class="form-group col-6">
                    <label for="username" class="form-label">UserName:</label>
                    <input type="text" class="form-control" id="username">
                </div>
                <br>
                <!-- password text box-->
                <div class="form-group col-6">
                    <label for="password" class="form-label">Password:</label>
                    <input type="text" class="form-control" id="password">
                </div>
                <br>
                <!--The Register button-->
                <button class="btn btn-primary" type="submit" name="submit">Register</button>

            </form>
        
        </div>
    
    </div>

</body>

<?php require_once "include/footer.php"; ?>