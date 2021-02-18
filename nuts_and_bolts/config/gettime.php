<?php
    include ("connect.php");

    $sql = "SELECT TimePressed FROM buttontime WHERE UserID='".$_COOKIE["id"]."'";

    if(mysqli_query($conn, $sql)){
        $timePressed = (mysqli_fetch_array(mysqli_query($conn, $sql)));
        $timePassed = date_diff(date_create($timePressed[0]), date_create(date("Y-m-d H:i:s")));
        echo "<h3>".$timePassed->format("%y years, %m months, %d days, %h hours, %i minutes, and %s seconds")." ago</h3>";
    } else{
        echo "GET TIME error: ".mysqli_error($conn);
    }
    mysqli_close($conn);
?>