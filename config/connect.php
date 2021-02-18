<?php
$conn = mysqli_connect('aa56arosc77wow.cp9sea0sr7fk.us-east-1.rds.amazonaws.com', 'teamOrange', '6CvCuQhgNMbZXDy', 'nutsandbolts', 3306);

if(!$conn){
    echo "Connection error: " . mysqli_connect_error();
}
?>