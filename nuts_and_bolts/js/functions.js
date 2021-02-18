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