<!-- The login page-->
<?php require_once "include/header.php" ?>
<title>Login Page | Nuts and Bolts</title>
</head>

<body>

<!--The navbar -->

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
                    <a class="nav-link" href="add.php">Add Products</a>
                    <a class="nav-link" href="update.php">Update Products</a>
                    <a class="nav-link" href="faq.php">FAQ</a>
                    <a class="nav-link" href="contact.php">Contact Us</a>
                </div>
                <div class="navbar-nav ms-auto flex-nowrap">
                    <a class="nav-link active" aria-current="page" href="register.php">Register</a>
                    <span class="collapse show nav-link" id="navbarNavAltMarkup">|</span>
                    <a class="nav-link" href="login.php">Login</a>
                </div>
            </div>
        </div>
    </nav>

    <!--The Login form-->

    <div class="container">
        <h1>Login</h1>
        <!--username text box-->
        <div class="container bg-light text-dark">
            <div class="form-group col-md-6">
                    <label for="userName" class="form-label">Username:</label>
                    <input type="text" class="form-control" name="userName" id="userName">
            </div>
        <!--password text box-->
        <div class="form-group col-md-6">
            <label for="password" class="form-label">Password:</label>
            <input type="password" class="form-control" name="password" id="password">
                </div>
        </div>
        <!--login button -->
        <br>
        <button class="btn btn-primary" type="submit" name="submit">Login</button>
    </div>


</body>

<?php require_once "include/footer.php"; ?>
