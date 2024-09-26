<?php
require 'config.php'; 

session_start([
    'cookie_lifetime' => 86400,  
    'cookie_secure' => true,     
    'cookie_httponly' => true,   
    'cookie_samesite' => 'Strict' 
]);


if (!isset($_SESSION['loggedin'])) {
    header('Location: ../../html/home/home.html'); 
    exit;
}

$sql3 = "SELECT * FROM appoinment";
$stmt = $con->prepare($sql3);

if ($stmt === false) {
    die("Error preparing statement: " . $con->error);
}

if (!$stmt->execute()) {
    die("Error executing statement: " . $stmt->error);
}

$result3 = $stmt->get_result();

if (!$result3) {
    die("Error fetching results: " . $con->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Appointments</title>
    <link rel="stylesheet" href="../../css/appoinment/userapp.css">
    <link href="https://fonts.googleapis.com/css2?family=Alfa+Slab+One&family=Open+Sans:wght@300&display=swap" rel="stylesheet">
</head>
<header>
    <nav>
        <ul>
            <li><a href="userapp.php">Book Appointment</a></li>
            <li><a href="viewappointments.php">View Appointments</a></li>
            <li><a href="../../html/home/home.html">Back to Home</a></li>
        </ul>
        <div id="banner"><img src="../../images/appoinment/logo.png" class="img0" alt="Logo"></div>
    </nav>
</header>
<body>
    <h1 style="text-align: center; margin-top: 80px;">Appointments</h1>
    <table class="table4">
        <thead>
            <tr>
                <th>Appointment ID</th>
                <th>Appointment Description</th>
                <th>Address</th>
                <th>Contact Number</th>
                <th>Email</th>
                <th>Date</th>
                <th>Time</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result3->num_rows > 0) {
                while ($row3 = $result3->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row3['appID']}</td>
                            <td>{$row3['appDescription']}</td>
                            <td>{$row3['address']}</td>
                            <td>{$row3['contact']}</td>
                            <td>{$row3['email']}</td>
                            <td>{$row3['date']}</td>
                            <td>{$row3['time']}</td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No appointments found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
