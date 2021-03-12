<?php require_once "include/header.php"; ?>
<?php require_once "config/connect.php" ?>

    <title>Products | Nuts and Bolts</title>
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
                        <a class="nav-link active" aria-current="page" href="products.php">Products</a>
                        <a class="nav-link" href="add.php">Add Products</a>
                        <a class="nav-link" href="update.php">Update Products</a>
                        <a class="nav-link" href="faq.php">FAQ</a>
                        <a class="nav-link" href="contact.php">Contact Us</a>
                    </div>
                    <div class="navbar-nav ms-auto flex-nowrap">
                        <a class="nav-link" href="register.php">Register</a>
                        <span class="collapse show nav-link" id="navbarNavAltMarkup">|</span>
                        <a class="nav-link" href="login.php">Login</a>
                    </div>
                </div>
            </div>
        </nav>

        <div class = "container">
            <h1>Products</h1>

            <div class = "row row-cols-1 row-cols-md-4 g-3">

            <?php $result = mysqli_query($conn, "SELECT * FROM inventory"); ?>
    
            <?php 
            while($row = mysqli_fetch_array($result))
            {
                echo '<div class = "col">
                    <div class="card h-100">
                    
                            <div class="card-body">
                                <h5 class="card-title">' . $row['product_name'] . '</h5>
                                <p class="card-text"><small class = "text-muted">SKU: ' . $row['sku'] . '</small></p>
                            </div>
                            <ul class="h-100 list-group list-group-flush">
                                <li class="list-group-item">' . $row['description'] . '</li>
                            </ul>
                            <div class="card-body">
                                <p class="card-text">$' . $row['price'] . '</p>
                            </div>
                        </div>
                    </div>';

            }

            echo '</div>';
            mysqli_close($conn);
            ?>
                
            </div>  
        </div>

<?php require_once "include/footer.php"; ?>