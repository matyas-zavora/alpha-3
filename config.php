<?php
// You can change these values if you want to, but if you follow the instructions in the README.md file,
// there is no need for it.
// ! Do not change anything below the line !
$db_server = "localhost"; //MySQL server address
$db_user = "root"; //MySQL username
$db_pass = ""; //MySQL password
$db_name = "alpha3"; //MySQL database name
$db_port = "3306"; //MySQL port

// -------------------------------------
$conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name, $db_port);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}