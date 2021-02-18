<?php require_once "include/header.php"; ?>
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

<?php require_once "include/footer.php"; ?>
