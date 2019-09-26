
<?php



/*awardspace*/
$db_server   = "fdb22.awardspace.net";
$db_username = "";
$db_password = "";
$db_name = "";


$connection = mysqli_connect($db_server, $db_username, $db_password, $db_name);
if (!$connection) {
    die("prisijungimas.php  failed: " . mysqli_connect_error());
    echo "db gedimas <br>";
}


?>


