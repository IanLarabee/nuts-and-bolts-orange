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


    $errors = [];

    $_SESSION['userId'] = ""; 
    $_SESSION['username'] = "";
    $_SESSION['firstname'] = "";
    $_SESSION['isUser'] = false;
    $_SESSION['isEmployee'] = false;

    if(isset($_POST['submit'])) {

        try {

            $time=time()-900;
            $ip_address=getIpAddr();

            $username = $_POST['username'];
            $password = $_POST['password'];

            //Prevent SQL Injection
            $username = stripcslashes($username);  
            $password = stripcslashes($password);  
            $username = mysqli_real_escape_string($conn, $username);  
            $password = mysqli_real_escape_string($conn, $password);

            if(empty($username) || empty($password))
            {
                array_push($errors, "All fields are required");
            }
            else 
            {
                
                $stmt = $conn->prepare("SELECT id, first_name, username, password FROM employees WHERE username=?");
                $stmt->bind_param("s", $username);

                if($stmt->execute())
                {
                    $stmt->store_result();

                    if ($stmt->num_rows == 1)
                    {
                        $stmt->bind_result($id, $firstName, $username, $hash);

                        if($stmt->fetch())
                        {
                            //$query=mysqli_query($conn,"select count(*) as total_count from loginlogs where TryTime > $time and user_id='$id'");
                            //$check_login_row=mysqli_fetch_assoc($query);
                            //$total_count=$check_login_row['total_count'];
                                    
                            /*if($total_count==3)
                            {
                                array_push($errors, "Too many failed login attempts. Please try again after 15 minutes.");
                            }
                            else*/ if(password_verify($password, $hash))
                            {
                                session_start();

                                $_SESSION['isEmployee'] = true;
                                $_SESSION['userId'] = $id;
                                $_SESSION['firstname'] = $firstName;
                                $_SESSION['username'] = $username;
                                mysqli_query($conn,"delete from loginlogs where user_id='$id'");
                                $stmt->close();
                                $conn->close();
                                header("Location: index.php");
                            }
                            else
                            {
                                goto usercheck;
                            }
                        }
                    } 
                    else
                    {
                        usercheck:

                        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username=?");
                        $stmt->bind_param("s", $username);

                        if($stmt->execute())
                        {
                            $stmt->store_result();

                            if ($stmt->num_rows == 1)
                            {
                                $stmt->bind_result($id, $username, $hash);

                                if($stmt->fetch())
                                {
                                    $query=mysqli_query($conn,"select count(*) as total_count from loginlogs where TryTime > $time and user_id='$id'");
                                    $check_login_row=mysqli_fetch_assoc($query);
                                    $total_count=$check_login_row['total_count'];

                                    if($total_count==3)
                                    {
                                        array_push($errors, "Too many failed login attempts. Please try again after 15 minutes.");
                                    }
                                    else if(password_verify($password, $hash))
                                    {
                                        session_start();

                                        $_SESSION['isUser'] = true;
                                        $_SESSION['userId'] = $id;
                                        $_SESSION['username'] = $username;
                                        mysqli_query($conn,"delete from loginlogs where user_id='$id'");
                                        $stmt->close();
                                        $conn->close();
                                        header("Location: index.php");
                                    }
                                    else
                                    {
                                        array_push($errors, "Invalid username or password");
                                        $total_count++;
                                        $rem_attm=3-$total_count;

                                        if($rem_attm==0)
                                        {
                                            array_push($errors, "Too many failed login attempts. Please login after 15 minutes");
                                        }

                                        $try_time=time();
                                        mysqli_query($conn,"insert into loginlogs(IpAddress,TryTime,user_id) values('$ip_address','$try_time','$id')");
                                    }
                                }
                            } 
                        }
                    }
                }
                        
                }
                 
            } catch (Exception $e) {
                array_push($errors, "An unexpected error has occurred. Please login again");
            }   
        }

    
        function getIpAddr(){
        if (!empty($_SERVER['HTTP_CLIENT_IP'])){
            $ipAddr=$_SERVER['HTTP_CLIENT_IP'];
        } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $ipAddr=$_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ipAddr=$_SERVER['REMOTE_ADDR'];
        }
        return $ipAddr;
    }
?>

<?php require_once "include/header.php"; ?>

<title>Login | Nuts and Bolts</title>
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
                <a class="nav-link" href="cart.php"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart4" viewBox="0 0 16 16">
                        <path d="M0 2.5A.5.5 0 0 1 .5 2H2a.5.5 0 0 1 .485.379L2.89 4H14.5a.5.5 0 0 1 .485.621l-1.5 6A.5.5 0 0 1 13 11H4a.5.5 0 0 1-.485-.379L1.61 3H.5a.5.5 0 0 1-.5-.5zM3.14 5l.5 2H5V5H3.14zM6 5v2h2V5H6zm3 0v2h2V5H9zm3 0v2h1.36l.5-2H12zm1.11 3H12v2h.61l.5-2zM11 8H9v2h2V8zM8 8H6v2h2V8zM5 8H3.89l.5 2H5V8zm0 5a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0zm9-1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0z"/></svg></a> 
                </div>
                <div class="navbar-nav ms-auto flex-nowrap">
                    <a class="nav-link" href="register.php">Register</a>
                    <span class="collapse show nav-link" id="navbarNavAltMarkup">|</span>
                    <a class="nav-link active" aria-current="page" href="login.php">Login</a>
                </div>
            </div>
        </div>
    </nav>

    <!--display message if redirected from add/update products-->
    <?php if (isset($_SESSION['loginmessage'])) {
    ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
            You must be logged in as an employee to access this page.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['loginmessage']);
    }
    ?>   

    <div class="container">
        <h1>Login</h1>
        
        <div class="container bg-light text-dark">
            <form class="row g-3" action="login.php" method="post">
                <div class="form-group col-md-12">
                    <label for="username" class="form-label">Username:</label>
                    <input type="text" class="form-control" name="username" id="username">
                </div>
                <!--password text box-->
                <div class="form-group col-md-12">
                    <label for="password" class="form-label">Password:</label>
                    <input type="password" class="form-control" name="password" id="password">
                    <?php if (count($errors) > 0): ?>
                        <?php foreach ($errors as $error): ?>
                            <span class="text-danger"><?=$error?></span><br>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <!--login button -->
                <button class="btn btn-primary" type="submit" name="submit">Login</button>
            </form>
        </div>
    </div>

<?php require_once "include/footer.php"; ?>