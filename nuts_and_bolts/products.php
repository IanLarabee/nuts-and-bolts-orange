<?php
    session_start();

    if(isset($_SESSION['isUser']) || isset($_SESSION['isEmployee'])){
        $userLoggedIn = $_SESSION['isUser'];
        $employeeLoggedIn = $_SESSION['isEmployee'];
    } else {
        $userLoggedIn = false;
        $employeeLoggedIn = false;
    }

    if(!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    if(isset($_POST['add'])) {
        $_SESSION['cart'][$_POST['add']] += 1;
    }
?>
<?php require_once "include/header.php"; ?>
<?php require_once "config/connect.php" ?>
    <script>
        $(document).ready(function(){
            $('.product-card').on('submit', function(){
                $.ajax({
                    type: 'post',
                    context: this,
                    data: {add: $(this).find("p[class='card-text product-sku']").text().slice(5)},
                    success: function(){
                        $("div[class='alert alert-success alert-dismissible fade show']").show();
                        $('#alert-message').text($(this).find('.card-title').text() + " was added to your cart.");
                        <?php
                            //$newSku = key($_SESSION['cart'][count($_SESSION['cart']) - 1]);
                        ?>
                    }
                });
                return false;
            });
        });
    </script>
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
                        <a class="nav-link" href="faq.php">FAQ</a>
                        <a class="nav-link" href="contact.php">Contact Us</a>
                        <?php if($employeeLoggedIn): ?>
                            <a class="nav-link" href="add.php">Add Products</a>
                            <a class="nav-link" href="update.php">Update Products</a>
                            <a class="nav-link" href="register.php">Register Employee</a>
                        <?php endif; ?> 
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

        <div class = "container">
            <h1>Products</h1>
            
            <div class = "row row-cols-1 row-cols-md-4 g-3">

            <?php $result = mysqli_query($conn, "SELECT * FROM inventory"); ?>
    
            <?php 
            while($row = mysqli_fetch_array($result))
            {
                echo '<div class = "col">
                    <form class="product-card">
                        <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">' . $row['product_name'] . '</h5>
                                    <p class="card-text product-sku"><small class = "text-muted">SKU: ' . $row['sku'] . '</small></p>
                                </div>
                                <ul class="h-100 list-group list-group-flush">
                                    <li class="list-group-item">' . $row['description'] . '</li>
                                </ul>
                                <div class="card-body">
                                    <p class="card-text">$' . $row['price'] . '</p>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">' . $row['quantity']. ' in stock</p>
                                </div>
                                <div class="card-body row">
                                    ' .($row['quantity'] > 0 ? '<button class="btn btn-primary select" type="submit">Add to Cart</button>' : '<p class="text-danger">Out of stock</p>'). '
                                </div>
                            </div>
                        </div>
                    </form>'
                ;

            }

            echo '</div>';
            mysqli_close($conn);
            ?>
                <br>
                <div class="alert alert-success alert-dismissible fade show" role="alert" style="display:none;">
                    <span id="alert-message"></span>
                </div>
            </div>  
        </div>

<?php require_once "include/footer.php"; ?>