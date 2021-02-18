<!DOCTYPE html>
<html>
    <head>
    </head>
    <body>
        <?php
            include ("connect.php");

            $sql = "SELECT TimePressed FROM ButtonTime WHERE UserID='".$_COOKIE["id"]."'";

            if(sqlsrv_query($conn, $sql)){
                $timePressed = (sqlsrv_fetch_array(sqlsrv_query($conn, $sql)));
                $timePassed = date_diff(date_create($timePressed[0]), date_create(date("Y-m-d H:i:s")));
                echo "<h3>".$timePassed->format("%y years, %m months, %d days, %h hours, %i minutes, and %s seconds")." ago</h3>";
            } else{
                die(print_r(sqlsrv_errors(), true));
            }
        sqlsrv_close($conn);
        ?>
    </body>
</html>
