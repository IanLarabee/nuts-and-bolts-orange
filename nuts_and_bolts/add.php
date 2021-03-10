<?php
    require_once "config/connect.php";

    session_start();

    $name = '';
    $sku = '';
    $desc = '';
    $price = '';
    $success = '';

    $errors = array('name'=>'', 'sku'=>'', 'desc'=>'', 'price'=>'');

    if(isset($_POST['submit'])) {
        if(empty($_POST['name'])) {
            $errors['name'] = 'A product name is required';
        } else {
            $name = $_POST['name'];
            if(!preg_match('/^[\s\S]{1,255}$/', $name)){
                $errors['name'] = 'The product name must be no longer than 255 characters';
            }
        }

        if(empty($_POST['sku'])) {
            $errors['sku'] = 'A product SKU is required';
        } else {
            $sku = $_POST['sku'];
            if(!preg_match('/^[0-9A-Z]{1,12}$/', $sku)){
                $errors['sku'] = 'The product SKU must be no longer than 12 capital alphanumeric characters';
            } else if(mysqli_fetch_assoc(mysqli_query($conn, "SELECT sku FROM inventory WHERE sku='$sku'"))["sku"] == $sku){
                $errors['sku'] = 'This SKU already exists';
            }
        }

        if(empty($_POST['desc'])) {
            $errors['desc'] = 'A product description is required';
        } else {
            $desc = $_POST['desc'];
            if(!preg_match('/^[\s\S]{1,255}$/', $desc)){
                $errors['desc'] = 'The product description must be no longer than 255 characters';
            }
        }

        if(empty($_POST['price'])) {
            $errors['price'] = 'A product price is required';
        } else {
            $price = $_POST['price'];
            if(!is_numeric($price)){
                $errors['price'] = 'The product price must be a numeric value';
            }
        }

        if(!array_filter($errors)) {
            $name = mysqli_real_escape_string($conn, $_POST['name']);
            $sku = mysqli_real_escape_string($conn, $_POST['sku']);
            $desc = mysqli_real_escape_string($conn, $_POST['desc']);
            $price = mysqli_real_escape_string($conn, $_POST['price']);

            $sql = "INSERT INTO inventory(product_name, sku, description, price) VALUES('$name', '$sku', '$desc', '$price')";

            if(mysqli_query($conn, $sql)) {
                $_SESSION['postStatus'] = true;
                $_SESSION['name'] = htmlspecialchars($name);
                header("Location: {$_SERVER['REQUEST_URI']}", true, 303);
                mysqli_close($conn);
                exit;
            }
        } else {
            $_SESSION['postStatus'] = false;
        }
    }
?>
<?php require_once "include/header.php"; ?>

        <title>Add Products | Nuts and Bolts</title>

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
                        <a class="nav-link" href="index.php">Home</a>
                        <a class="nav-link" href="products.php">Products</a>
                        <a class="nav-link active" aria-current="page" href="add.php">Add Products</a>
                        <a class="nav-link" href="update.php">Update Products</a>
                        <a class="nav-link" href="faq.php">FAQ</a>
                        <a class="nav-link" href="contact.php">Contact Us</a>
                        <a class="nav-link" href="register.php">Register Employee</a>
                    </div>
                </div>
            </div>
        </nav>
        
        <div class="container">
            <h1>Add Products</h1>
            <div class="container bg-light text-dark">
                <form class="row g-3" action="add.php" method="POST">
                    <div class="form-group col-12">
                        <label for="productName" class="form-label">Product Name:</label>
                        <input type ="text" class="form-control" id="productName" name ="name" value = "<?php echo htmlspecialchars($name); ?>">
                        <p class="text-danger">
                            <?php echo $errors['name']; ?>
                        </p>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="productSKU" class="form-label">SKU:</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text">#</span>
                            <input type ="text" class="form-control" id="productSKU" name ="sku" value = "<?php echo htmlspecialchars($sku); ?>">
                        </div>
                        <p class="text-danger">
                            <?php echo $errors['sku']; ?>
                        </p>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="productPrice" class="form-label">Price:</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text">$ </span>
                            <input type="text" class="form-control" id="productPrice" name="price" value="<?php echo htmlspecialchars($price); ?>">
                        </div>
                        <p class="text-danger">
                            <?php echo $errors['price']; ?>
                        </p>
                    </div>
                    <div class="form-group col-12">
                        <label for="productDescription" class="form-label">Description:</label>
                        <textarea class="form-control" id="productDescription" name="desc" rows="4" cols="50"><?php echo htmlspecialchars($desc); ?></textarea>
                        <p class="text-danger">
                            <?php echo $errors['desc']; ?>
                        </p>
                    </div>
                    <button class="btn btn-primary" type="submit" name="submit">Submit</button>
                    
                    <?php                
                        if(isset($_SESSION['postStatus']) && $_SESSION['postStatus']) {
                    ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo $_SESSION['name']; ?> was added successfully to the inventory!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php
                        } elseif(isset($_SESSION['postStatus']) && !$_SESSION['postStatus']) {
                    ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php if($name =='') { echo 'This item';}else{ echo htmlspecialchars($name);} ?> could not be added to the inventory due to an error.
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