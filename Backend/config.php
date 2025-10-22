<?php

$host = "shuttle.proxy.rlwy.net";
$port = 39718;
$dbname = "railway";
$username = "root";
$password = "MsIdAzDZZJxptQoAzQBwdGqqmrAsekqh";


$conn = new mysqli($host, $username, $password, $dbname, $port);

 
if(!$conn){
    die("ERROR: Could not connect. " . $conn->connect_error);
}

 $conn->set_charset("utf8mb4");


error_reporting(E_ALL);
ini_set('display_errors', 1);

date_default_timezone_set('Asia/Colombo');//ලංකාවේ වේලාව  සහ දිනය අනුව
?>