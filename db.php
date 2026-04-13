<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "carbon_tracker";

$conn = mysqli_connect($host, $user, $pass, $db);

// check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>