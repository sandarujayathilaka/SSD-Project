<?php 
require 'config.php';
session_start([
    'cookie_lifetime' => 86400,  
    'cookie_secure' => false,     
    'cookie_httponly' => true,   
    'cookie_samesite' => 'Strict' 
]);

if (!isset($_SESSION['loggedin'])) {
    header('Location: ../Php/home.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Appointments</title>
    <link rel="stylesheet" href="../../css/appoinment/nutri.css">
    <link href="https://fonts.googleapis.com/css2?family=Alfa+Slab+One&family=Open+Sans:wght@300&display=swap" rel="stylesheet">
</head>

<header>
    <nav>
        <ul> 
            <li><a href="nutrionistapp.php">My Appointments</a></li> 
            <li><a href="../../html/home/index.html">Back to Home</a></li>
        </ul>
        <div id="banner">
            <img src="../../images/appoinment/logo.png" class="img0">
        </div>
    </nav>
</header>

<body>

<p>Welcome back, <?=$_SESSION['name']?>!</p>

<h1 class="nutri-title">My Appointments</h1>
<br>

<table class="table2">
    <tr>
        <th>Appointment ID</th>
        <th>Appoinment Description</th>
        <th>Recipe User Address</th>
        <th>Contact number</th>
        <th>Email</th>
        <th>Date</th>
        <th>Time</th>
    </tr>

    <?php
 
    $stmt = $con->prepare("SELECT * FROM appoinment");
    
    $stmt->execute();
    
    $result3 = $stmt->get_result();
    
    if ($result3->num_rows >= 1) {
        while ($row3 = $result3->fetch_assoc()) {
            echo "<tr><td>" . htmlspecialchars($row3["appID"]) . "</td>
                      <td>" . htmlspecialchars($row3["appDescription"]) . "</td>
                      <td>" . htmlspecialchars($row3["address"]) . "</td>
                      <td>" . htmlspecialchars($row3["contact"]) . "</td>
                      <td>" . htmlspecialchars($row3["email"]) . "</td>
                      <td>" . htmlspecialchars($row3["date"]) . "</td>
                      <td>" . htmlspecialchars($row3['time']) . "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "<tr><td colspan='7'>No appointments found.</td></tr>";
    }

    $stmt->close();
    ?>
</table>

</body>
</html>
