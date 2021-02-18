<!doctype html>
<?php
    include("config/connect.php");
    //Query whether the visitor has an id yet
    if(!isset($_COOKIE["id"]))
    {
        $cookieName = "id";
        $cookieValue = uniqid();
        //Create cookie id using uniqid()
        setcookie($cookieName, $cookieValue, time() + (86400 * 365), "/");
        //Insert new column into ButtonTime
        if (!mysqli_query($conn, "INSERT INTO buttontime(UserId, TimePressed) VALUES('$cookieValue', NULL)")){
            echo "ID error: ". mysqli_error($conn);
        }
    }
    mysqli_close($conn);
?>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="js/functions.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">