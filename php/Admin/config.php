<?php

$con=new mysqli("localhost","root","root","iwt");
if($con->connect_error){
die("Connection failed: " . $con->connect_error);
}

?>