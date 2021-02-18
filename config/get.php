<?php
	include ("connect.php");
	
	if (!mysqli_query($conn, "UPDATE buttontime SET TimePressed='".date("Y-m-d H:i:s")."' WHERE UserId='".$_COOKIE["id"]."'")){
            echo "GET error: ". mysqli_error($conn);
    }
    mysqli_close($conn);
?>