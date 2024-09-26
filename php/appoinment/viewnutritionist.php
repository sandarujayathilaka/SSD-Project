<?php 
require 'config.php'; 

session_start([
    'cookie_lifetime' => 86400,  
    'cookie_secure' => false,     
    'cookie_httponly' => true,   
    'cookie_samesite' => 'Strict' 
]);

if (!isset($_SESSION['loggedin'])) {
    header('Location: ../../html/home/home.html'); 
    exit;
}

$sql3 = "SELECT * FROM nutritionist";
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
    <title>View Nutritionist</title>
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
    <h1 class="info-title">Nutritionist Information</h1>
    <table class="table4">
        <thead>
            <tr>
                <th>Nutritionist ID</th>
                <th>Nutritionist Name</th>
                <th>Email</th>
                <th>Address</th>
                <th>Contact Number</th>
                <th>Specialization</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result3->num_rows > 0) {
                while ($row3 = $result3->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row3['nutritionistID']}</td>
                            <td>{$row3['nutritionistname']}</td>
                            <td>{$row3['email']}</td>
                            <td>{$row3['Address']}</td>
                            <td>{$row3['ContactNumber']}</td>
                            <td>{$row3['categorey']}</td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No nutritionists found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
