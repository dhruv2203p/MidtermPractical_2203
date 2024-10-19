<?php
$host = "localhost"; // Your database host
$db_username = "root"; // Your database username
$db_password = ""; // Your database password
$db_name = "perfume_inventory"; // Your database name

$mysqli = new mysqli($host, $db_username, $db_password, $db_name);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
?>
