<?php
    require_once "config/connect.php";
	
	$sql = "INSERT INTO inventory(product_name, sku, description, price) 
	VALUES('$name', '$sku', '$desc', '$price')";
	
	
	if ($conn->query($sql) === TRUE) {
		echo "A new record has been created"; 
		} else {
		echo "Error has occurred: ". $sql . mysqli_error->($conn);
		}
		exit;
	
	<?php require_once "include/footer.php"; ?>