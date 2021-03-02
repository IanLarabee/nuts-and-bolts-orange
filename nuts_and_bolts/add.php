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
            $errors['sku'] = 'A product sku is required';
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
                $errors['price'] = 'The product price is must be of numeric values';
            }
        }

        if(!array_filter($errors)) {
            $name = mysqli_real_escape_string($conn, $_POST['name']);
            $sku = mysqli_real_escape_string($conn, $_POST['sku']);
            $desc = mysqli_real_escape_string($conn, $_POST['desc']);
            $price = mysqli_real_escape_string($conn, $_POST['price']);

            $sql;//TODO: Add SQL statement to insert values into inventory

            if(mysqli_query($conn, $sql)) {
                $_SESSION['success'] = htmlspecialchars($name)." was successfully added to the inventory!";
                header("Location: {$_SERVER['REQUEST_URI']}", true, 303);
                exit;
            }
        }
    }
?>
<?php require_once "include/header.php"; ?>

        <title>Home | Nuts and Bolts</title>

    </head>
    <!-- The input fields allow the user to add a product name, product SKU, a description, and a price-->
    <body>

        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class = "container">
                <a class="navbar-brand" href="index.php">Nuts and Bolts</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                    <div class="navbar-nav">
                        <a class="nav-link" aria-current="page" href="index.php">Home</a>
                        <a class="nav-link" href="products.php">Products</a>
                        <a class="nav-link active" aria-current="page" href="add.php">Add Products</a>
                        <a class="nav-link" href="faq.php">FAQ</a>
                        <a class="nav-link" href="contact.php">Contact Us</a>
                    </div>
                </div>
            </div>
        </nav>
        
        <div class="container">
            <h1>Add Products</h1>
            <!-- Form Submissions will be here-->
            <form action="add.php" method="POST">
                <!-- Product Name-->
                <div class="form-group col-md-4 mx-5">
                    <label for="productName" class="form-label">Product Name</label>
                    <input type ="text" class="form-control" id="productName" name ="name" value = "<?php echo htmlspecialchars($name) ?>">
                    <p class="text-danger">
                        <?php echo $errors['name'] ?>
                    </p>
                </div>
                <!-- Product SKU-->
                <div class="form-group col-md-5 mx-5">
                    <label for="productSKU" class="form-label">SKU</label>
                    <input type ="text" class="form-control" id="productSKU" name ="sku" value = "<?php echo htmlspecialchars($sku) ?>">
                    <p class="text-danger">
                        <?php echo $errors['sku'] ?>
                    </p>
                </div>
                <!-- Product Description-->
                <div class="form-group col-md-8 mx-5">
                    <label for="productDescription" class="form-label">Description</label>
                    <textarea class="form-control" id="productDescription" name="desc" value="<?php echo htmlspecialchars($desc) ?>"></textarea>
                    <p class="text-danger">
                        <?php echo $errors['desc'] ?>
                    </p>
                </div>
                <!-- Price-->
                <div class="form-group col-md-1 mx-5">
                    <label for="productPrice" class="form-label">Price</label>
                    <input type="text" class="form-control" id="productPrice" name="price" value="<?php echo htmlspecialchars($price) ?>">
                    <p class="text-danger">
                        <?php echo $errors['price'] ?>
                    </p>
                </div>
                <button class="btn btn-primary" type="submit" name="submit">Submit</button>
            </form>
            <p class="text-success">
                <?php                
                    if(isset($_SESSION['success'])) {
                        echo $_SESSION['success'];
                        unset($_SESSION['success']);
                    }
                ?>
            </p>
        </div>

<?php require_once "include/footer.php"; ?>