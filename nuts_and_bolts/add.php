<?php require_once "include/header.php"; ?>

    <title>Home | Nuts and Bolts</title>
    </head>

    <!-- The input fields allow the user to add a product name, product SKU, a description, and a price-->
    <body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class = "container">
            <a class="navbar-brand" href="#">Nuts and Bolts</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <a class="nav-link" aria-current="page" href="index.php">Home</a>
                    <a class="nav-link" href="products.php">Products</a>
                    <a class="nav-link" href="faq.php">FAQ</a>
                    <a class="nav-link active" href="contact.php">Contact Us</a>
                </div>
            </div>
        </div>
    </nav>

    <br>
    <br>
    <!-- Form Submissions will be here-->
    <form>
        <!-- Product Name-->
        <div class="form-group col-md-4 mx-5">
            <label for="productName">Product Name</label>
            <input type="text" class="form-control" id="productName" aria-describedby="emailHelp" placeholder="Enter Product Name...">
        </div>
        <!-- Product SKU-->
        <div class="form-group col-md-5 mx-5">
            <label for="productSKU">Product SKU</label>
            <input type="number" class="form-control" id="productSKU" placeholder="Enter Product SKU...">
        </div>
        <!-- Product Description-->
        <div class="form-group col-md-8 mx-5">
            <label for="productDescription">Product Description</label>
            <input type="text" class="form-control" id="productDescription" placeholder="Enter Product description...">
        </div>
        <!-- Price-->
        <div class="form-group col-md-1 mx-5">
            <label for="price">Price</label>
            <input type="text" class="form-control" id="price" placeholder="Enter a price...">
        </div>
        <br>
    <button type="submit" class="btn btn-primary mx-5">Submit Form</button>
</form>


<?php require_once "include/footer.php"; ?>