<?php
$servername = "mysql";
$username = "root";
$password = "root";
$dbname = "bookstore";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
// echo "Connected successfully";
date_default_timezone_set('Asia/Ho_Chi_Minh');
mysqli_set_charset($conn, "utf8");

?>