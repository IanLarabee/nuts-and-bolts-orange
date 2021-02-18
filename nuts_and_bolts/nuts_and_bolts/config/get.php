<?php
	include ("connect.php");
	
	if (!sqlsrv_query($conn, "UPDATE ButtonTime SET TimePressed='".date("Y-m-d H:i:s")."' WHERE UserId='".$_COOKIE["id"]."'")){
        die(print_r(sqlsrv_errors(), true));
    }
    sqlsrv_close($conn);
?>