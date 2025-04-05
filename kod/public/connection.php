<?php 
    $servername = "localhost";
    $username = "root";
    $password = "";
    $db_name = "kisallatbolt";  
    $conn = new mysqli($servername, $username, $password, $db_name, port: 3306);
    if($conn->connect_error){
        die("Kapcsolat sikertelen".$conn->connect_error);
    }
    echo "";
?>

