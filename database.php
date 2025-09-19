<?php

$hostname = "localhost";
$username = "root";
$password = "";
$database_name = "irsyad";

$db = mysqli_connect ($hostname, $username, $password, $database_name);

if ($db->connect_error){
    echo "Akun berhasil di bikin";
    die ("error!");
}

?>