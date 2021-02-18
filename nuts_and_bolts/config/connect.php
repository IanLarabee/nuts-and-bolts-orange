<?php
    $conn = sqlsrv_connect('aa10tpi5v27slik.cp9sea0sr7fk.us-east-1.rds.amazonaws.com, 1433', 'TeamOrangeB01', 'qyYgesGxH*Zb', 'NutsAndBoltsDB');

    if(!$conn) {
        die(print_r(sqlsrv_errors(), true));
    }
?>