<?php require_once "config/connect.php" ?>
<?php
    session_start();

    if(isset($_SESSION['isUser']) || isset($_SESSION['isEmployee'])){
        $userLoggedIn = $_SESSION['isUser'];
        $employeeLoggedIn = $_SESSION['isEmployee'];
    } else {
        $userLoggedIn = false;
        $employeeLoggedIn = false;
    }

    if(isset($_SESSION['isEmployee']) && $_SESSION['isEmployee'] == false) {
        $_SESSION['loginmessage'] = True;
        header("location: login.php");
    } 

    if(isset($_POST['submit'])) {
        $_SESSION['date'] = $_POST['dateSelect'];
        header("Location: {$_SERVER['REQUEST_URI']}", true, 303);
    }

    if(isset($_POST['dateSelect'])) {
        $sales = [];

        $table = '';

        $formattedDate = date("m/d/y",strtotime($_POST['dateSelect']));

        $yearOfSale = date("Y", strtotime($_POST['dateSelect']));
        $weekOfDate = date("W", strtotime($_POST['dateSelect']));

        $table .= '
            <div class="table-responsive">
                <table id="sales" class="table table-hover table-bordered">
                    <tr class="table-secondary">
                        <th>Product SKU</th>';
        
        if(date("w", strtotime($_POST['dateSelect'])) == 1) {
            $table .= '
                <th>'.$formattedDate.'</th>
                <th>'.date("m/d/y", strtotime("+1 day $formattedDate")).'</th>
                <th>'.date("m/d/y", strtotime("+2 day $formattedDate")).'</th>
                <th>'.date("m/d/y", strtotime("+3 day $formattedDate")).'</th>
                <th>'.date("m/d/y", strtotime("+4 day $formattedDate")).'</th>
                <th>'.date("m/d/y", strtotime("+5 day $formattedDate")).'</th>
                <th>'.date("m/d/y", strtotime("+6 day $formattedDate")).'</th>';
        } else {
            $table .= '
            <th>'.date("m/d/y", strtotime("last monday $formattedDate")).'</th>
            <th>'.date("m/d/y", strtotime("last monday +1 day $formattedDate")).'</th>
            <th>'.date("m/d/y", strtotime("last monday +2 day $formattedDate")).'</th>
            <th>'.date("m/d/y", strtotime("last monday +3 day $formattedDate")).'</th>
            <th>'.date("m/d/y", strtotime("last monday +4 day $formattedDate")).'</th>
            <th>'.date("m/d/y", strtotime("last monday +5 day $formattedDate")).'</th>
            <th>'.date("m/d/y", strtotime("last monday +6 day $formattedDate")).'</th>';
        }

        $table .= '
            </tr>
            <tr>
        ';
        
        $salesOnDate = mysqli_query($conn, "SELECT dayofweek(saleDate) as day, receiptId FROM receipts WHERE year(saleDate)='$yearOfSale' AND WEEK(saleDate)='$weekOfDate' ORDER BY day ASC");
        
        if(mysqli_num_rows($salesOnDate) == 0) {
            $table = '
                    <div class="alert alert-secondary" role="alert">
                        <span>No sales could be found!</span>
                    </div>
            ';
            echo $table;
            exit;
        }

        while($receipts = mysqli_fetch_array($salesOnDate)) {
            $receiptId = $receipts['receiptId'];
            
            $receiptDetails = mysqli_query($conn, "SELECT sku, quantity, (quantity * salePrice) as total FROM receipt_details WHERE receiptId='$receiptId'");
            while($salesOnReceipt = mysqli_fetch_array($receiptDetails)) {
                if(!isset($sales[$salesOnReceipt['sku']][$receipts['day']])){
                    $sales[$salesOnReceipt['sku']][$receipts['day']] = $salesOnReceipt['quantity'];
                    continue;
                }
                $sales[$salesOnReceipt['sku']][$receipts['day']] += $salesOnReceipt['quantity'];
            }
        }

        foreach($sales as $sku => $day) {
            $table .= '
                <td>'.$sku.'</td>
            ';

            for($i = 2; $i <= 8; $i++) {
                if(array_key_exists($i, $sales[$sku])){
                    $table .= '
                        <td>'.$sales[$sku][$i].'</td>
                    ';
                } else {
                    $table .= '
                        <td>0</td>
                    ';
                }
            }

            $table .= '
                </tr>
                <tr>
            ';
        }
        $table .= '</table></div>';
        echo $table;
        exit;
    }
?>

<?php require_once "include/header.php"; ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script>
        $(document).ready(function(){
            $("#date-selection").on("change", function(){

                var userDate = moment($(this).val());

                if(userDate.isValid()) {
                    $.ajax({
                        type: 'post',
                        url: 'salesreport.php',
                        data: {dateSelect: userDate.format("MM/DD/YYYY")},
                        beforeSend: function() {
                            $("#sale-table").css("visibility", "hidden");
                            $("#load-spinner").css({
                                "visibility": "visible",
                                "width": "6rem",
                                "height": "6rem"});
                            $("#table-title").css("visibility", "hidden");
                        },
                        success: function(response){
                            $("#load-spinner").css({
                                "visibility": "hidden",
                                "width": "0.5rem",
                                "height": "0.5rem"});
                            $("#table-title").css("visibility", "visible");
                            $("#table-title").html("Sales for the week of " + userDate.format("MM/DD/YYYY"));
                            $("#sale-table").html(response);
                            $("#sale-table").css("visibility", "visible");
                        }
                    });
                }
            });
        });
    </script>

    <title>Weekly Sales Report | Nuts and Bolts</title>
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

        <!--Table begins here -->
        <div class="container">
            <h1>Product Sales</h1>
            <label for="date-selection">Choose date:</label>
            <input id="date-selection" type="date" class="form-control">
            <br>
            <div class="d-flex justify-content-center">
                <div id="load-spinner" class="spinner-border" role="status" style="visibility: hidden;"></div>
            </div>
            <h2 id="table-title"></h2>
            <div id="sale-table"></div>
        </div>
<?php require_once "include/footer.php"; ?>
