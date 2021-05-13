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
        var toastIDCounter = 0;

        (function ($) {
            $.fn.bsToast = function (options) {
                if (typeof options === "string") {
                    options = {
                        body: options
                    }
                }
                var settings = $.extend({
                    // These are the defaults.
                    body: "MISSING body: <br/>$(...).bsToast({body: 'toast body text here'})<br/><strong><em>html is OK!</em></strong>",
                    animation: true, // Apply a CSS fade transition to the toast
                    autohide: true,	 // Auto hide the toast
                    delay: 3000,	 // Delay hiding the toast (ms)
                    dispose: true
                }, options);

                var $toastContainer = $("#toast-container");

                if ($toastContainer.length === 0) {
                    // re-create toastPosition and toastContainer
                    var $toastPosition = $("<div>", {
                        "id": "toast-position",
                        "aria-live": "polite",
                        "aria-atomic": "true",
                        "style": "position: fixed; min-height: 200px;top: 5px;right: 5px;"
                    });

                    $toastContainer = $("<div>", {
                        "id": "toast-container",
                        "style": "position: absolute; top: 5px; right: 5px;"
                    });

                    $(document.body).append($toastPosition);
                    $toastPosition.append($toastContainer)
                }

                var toastid = "toast-id-" + toastIDCounter;
                toastIDCounter++

                var $toast = $("<div>", {
                    "id": toastid,
                    "class": "toast",
                    "style": "min-width: 300px;",
                    "role": "alert",
                    "aria-live": "assertive",
                    "aria-atomic": true
                });

                if (settings.header && settings.header.text) {
                    var $header = $("<div>", {"class": "toast-header"});
                    if (settings.header.logo) {
                        $header.append(`<img src="${settings.header.logo}" class="rounded mr-2" height="25" width="25" alt="logo">`)
                    }
                    $header.append(`<strong class="mr-auto">${settings.header.text}</strong>`)
                    // $header.append(`<small class="text-muted">just now</small>`)
                    $header.append(`<button type="button" class="ms-auto btn-close" data-bs-dismiss="toast" aria-label="Close"></button>`)
                    $toast.append($header)
                }

                var $toastBody = $("<div>", {"class": "toast-body"});
                $toastBody.html(settings.body)
                $toast.append($toastBody)
                $toastContainer.append($toast)

                var toastEl = $toast[0]
                toastEl.addEventListener('hidden.bs.toast', toastEl.remove)
                var t = new bootstrap.Toast(toastEl, {delay: settings.delay});
                t.show()
            };

        }(jQuery));

        $(document).ready(function(){
            $('.product-card').on('submit', function(){
                $.ajax({
                    type: 'post',
                    context: this,
                    data: {add: $(this).find("p[class='card-text product-sku mb-0']").text().slice(5)},
                    success: function(){
                        $(document).bsToast({
                        header: {
                            text: "Cart"
                        },
                        body: '<div class="row"><div class="col-5"><img src="' + $(this).find('.card-img-top').attr('src') + '" class="d-inline" style="height: 100px; width: 100px; object-fit: scale-down;"></div><div class="col-7"><h5>' + $(this).find('.card-title').text() + '</h5><p> was added to your cart</p></div></div>'
                        })
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
                <a class="navbar-brand" href="index.php"><img src="assets/nutsandboltslogo.png" alt="Nuts and Bolts" id="logo"></a>
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

        <div class = "container">
            <h1>Products</h1>

            <?php 
            
            $result = mysqli_query($conn, "SELECT name FROM categories");
            
            echo '<div class="row">
                <div class="col bg-light">
                <h3>Filter</h3>
                <h5 class="border-bottom border-2">Categories</h5>
                    <form action="products.php" method="POST">';

            while($row = mysqli_fetch_array($result)) {
                echo '<input class="form-check-input" type="checkbox" id="'.$row['name'].'" name="category[]" value="'.$row['name'].'"';
                
                if(isset($_POST['category']) && in_array($row['name'], $_POST['category'])) {
                    echo 'checked';
                }

                echo '><label class="form-check-label" for="'.$row['name'].'"> '.$row['name'].'</label><br>';
            }
            
            echo'<br>
                <h5 class="border-bottom border-2">Price</h5>
                <input class="form-check-input" type="checkbox" id="great-deal" name="category[great_deal]" value="true"';
            
            if(isset($_POST['category']) && isset($_POST['category']['great_deal'])) {
                echo 'checked';
            }

            echo'><label class="form-check-label" for="great-deal"> Great Deals</label><br><br>
                <input class="btn btn-primary btn-sm" type="submit" name="submit" value="Submit">
            </form>
            </div>
                <div class="col-10">
                    <div class="row">';

            if(isset($_POST['submit']) && isset($_POST['category'][0])) {
                $categories = implode("', '", $_POST['category']);
                $greatDeal = implode(", ", array(0 , 1));
                if(isset($_POST['category']['great_deal']) && $_POST['category']['great_deal'] == 'true') {
                    $greatDeal = 1;
                }
                $result = mysqli_query($conn, "SELECT product_id, product_name, sku, description, price, quantity, name as catname, id as catid FROM nutsandbolts.inventory INNER JOIN nutsandbolts.categories ON (category_id=id) AND name IN ('$categories') AND great_deal IN ($greatDeal) ORDER BY catname ASC, quantity DESC");
            } elseif((isset($_POST['submit']) || isset($_POST['category'])) && isset($_POST['category']['great_deal'])) {
                $result = mysqli_query($conn, "SELECT product_id, product_name, sku, description, price, quantity, name as catname, id as catid FROM nutsandbolts.inventory INNER JOIN nutsandbolts.categories ON (category_id=id) AND great_deal=1 ORDER BY catname ASC, quantity DESC");
            } else {
                $result = mysqli_query($conn, "SELECT inventory.product_id, inventory.product_name, inventory.sku, inventory.description, inventory.price, inventory.quantity, categories.name as catname, categories.id as catid FROM inventory LEFT JOIN categories ON (inventory.category_id=categories.id) ORDER BY categories.name ASC, inventory.quantity DESC");
            }
            
            if(mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_array($result))
                {
                    $prodId = $row['product_id'];
                    $imgSql = "SELECT * FROM images WHERE product_id = $prodId";
                    $imgResult = mysqli_query($conn, $imgSql);
                    $imgRow = $imgResult->fetch_assoc();

                    echo '<div class="col-auto d-none d-sm-block">
                        <form class="product-card">
                            <div class="card h-100">
                                ' . (mysqli_num_rows($imgResult) == 0 ? '<h5>Image Unavailable</h5>' : '<img src="data:image/jpg;charset=utf8;base64,'. base64_encode($imgRow['imagedata']). '"  class="card-img-top" style="width: 235px; object-fit: scale-down;" />') . '
                                <div class="card-body" style="width: 249px; height:325px;">
                                    <div class="d-flex flex-column" style="height: 300px;">    
                                        <h5 class="card-title">' . $row['product_name'] . '</h5>
                                        <p class="card-text product-sku mb-0"><small class = "text-muted">SKU: ' . $row['sku'] . '</small></p>
                                        <span class="badge rounded-pill bg-primary">'.$row['catname'].'</span>
                                        <p class="card-text mt-2">' . $row['description'] . '</p>
                                        <h2 class="card-text mt-auto">$' . $row['price'] . '</h2>
                                        <p class="card-text">' . $row['quantity']. ' in stock</p>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row">
                                    ' .($row['quantity'] > 0 ? '<button class="btn btn-primary select" type="submit">Add to Cart</button>' : '<button class="btn btn-secondary" disabled data-bs-toggle="button" autocomplete="off">Unavailable</button>'). '
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-auto d-block d-sm-none">
                        <form class="product-card">
                            <div class="card h-100">
                                ' . (mysqli_num_rows($imgResult) == 0 ? '<h5>Image Unavailable</h5>' : '<img src="data:image/jpg;charset=utf8;base64,'. base64_encode($imgRow['imagedata']). '"  class="card-img-top h-100 w-100" style="object-fit: scale-down;" />') . '
                                <div class="card-body" style="width: 249px; height:325px;">
                                    <div class="d-flex flex-column" style="height: 300px;">    
                                        <h5 class="card-title">' . $row['product_name'] . '</h5>
                                        <p class="card-text product-sku mb-0"><small class = "text-muted">SKU: ' . $row['sku'] . '</small></p>
                                        <span class="card-text badge rounded-pill bg-primary" style="width: 145px;">'.$row['catname'].'</span>
                                        <p class="card-text mw-50">' . $row['description'] . '</p>
                                        <h2 class="card-text mt-auto">$' . $row['price'] . '</h2>
                                        <p class="card-text">' . $row['quantity']. ' in stock</p>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row">
                                    ' .($row['quantity'] > 0 ? '<button class="btn btn-primary select" type="submit">Add to Cart</button>' : '<button class="btn btn-secondary" disabled data-bs-toggle="button" autocomplete="off">Unavailable</button>'). '
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>'
                    ;

                }
            } else {
                echo'<div class="alert alert-secondary" role="alert">
                No products found matching this criteria!
            </div>';
            }

            echo '</div></div></div>';
            mysqli_close($conn);
            ?>
                <br>
                <div class="alert alert-success alert-dismissible fade show" role="alert" style="display:none;">
                    <span id="alert-message"></span>
                </div>
            </div>  
        </div>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
<?php require_once "include/footer.php"; ?>
