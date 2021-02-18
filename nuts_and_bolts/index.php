<?php require_once "include/header.php"; ?>

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
