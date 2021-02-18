<?php
    include("config/connect.php");
    //Query whether the visitor has an id yet
    if(!isset($_COOKIE["id"]))
    {
        $cookieName = "id";
        $cookieVaule = uniqid();
        //Create cookie id using uniqid()
        setcookie($cookieName, $cookieVaule, time() + (86400 * 365), "/");
        //Insert new column into ButtonTime
        if (!sqlsrv_query($conn, "INSERT INTO ButtonTime(UserId, TimePressed) VALUES('$cookieVaule', NULL)")){
            die(print_r(sqlsrv_errors(), true));
        }
    }
    sqlsrv_close($conn);
?>
<!doctype html>
<html lang="en">
    <head>
        <script>
            function timerDisplay() {
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (this.readyState==4 && this.status==200) {
                        document.getElementById("time").innerHTML=this.responseText;
                    }
                }
                xmlhttp.open("POST", "config/gettime.php", true);
                xmlhttp.send();
                refreshTimer();
            }
            function refreshTimer()  {
                setTimeout('timerDisplay()', 1000);
            }
            function sendTime() {
                var xhttp = new XMLHttpRequest();
                xhttp.open("GET", "config/get.php", true);
                xhttp.send();
            }
        </script>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">

        <title>Hello, world!</title>
    </head>
    <body onload="timerDisplay()">
        <div class="container">
            <h1>Hello, world!</h1>
            <p>The last time you clicked the button was:</p>
            <div id="time"></div>
            <br>
            <button type="button" class="btn btn-primary" onclick="sendTime()">Store Time</button>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
    </body>
</html>
