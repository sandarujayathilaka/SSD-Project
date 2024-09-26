<?php
$servername = "localhost";
$username = "root";
$password = "";
$db="iwt";

// Include the custom error handler
require "../ErrorHandling/ErrorHandler.php";

$con = new mysqli($servername, $username, $password,$db);

if ($con->connect_error) {
die("Connection failed: " . $con->connect_error);
}

?>
