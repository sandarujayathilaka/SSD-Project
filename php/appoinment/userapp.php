<?php 
require 'config.php';  
session_start(); 

$errors = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['Add'])) {
        $addID = $_POST['addID'];
        $adddesc = $_POST['adddesc'];
        $addAddress = $_POST['addAddress'];
        $addContactNumber = $_POST['addContactNumber'];
        $addEmail = $_POST['addEmail'];
        $adddate = $_POST['adddate'];
        $addtime = $_POST['addtime'];

        if (empty($addID)) array_push($errors, "Appointment ID is required");
        if (empty($adddesc)) array_push($errors, "Description is required");
        if (empty($addAddress)) array_push($errors, "Address is required");
        if (empty($addContactNumber)) array_push($errors, "Contact Number is required");
        if (empty($addEmail)) array_push($errors, "Email is required");
        if (empty($adddate)) array_push($errors, "Date is required");
        if (empty($addtime)) array_push($errors, "Time is required");

        if (count($errors) == 0) {
            $stmt = $con->prepare("INSERT INTO appoinment (appID, appDescription, address, contact, email, date, time) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", $addID, $adddesc, $addAddress, $addContactNumber, $addEmail, $adddate, $addtime);

            if ($stmt->execute()) {
                echo "Appointment booked successfully.";
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        }
    }

    if (isset($_POST['Delete'])) {
        $deleteID = $_POST['deleteID'];

        if (empty($deleteID)) array_push($errors, "Appointment ID is required");

        if (count($errors) == 0) {
            $stmt = $con->prepare("DELETE FROM appoinment WHERE appID = ?");
            $stmt->bind_param("s", $deleteID);

            if ($stmt->execute()) {
                if ($con->affected_rows > 0) {
                    echo "Appointment canceled successfully.";
                } else {
                    array_push($errors, "No appointment found with the provided ID.");
                }
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Recipe User</title>
    <link rel="stylesheet" href="../../css/appoinment/userapp.css" type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Alfa+Slab+One&family=Open+Sans:wght@300&display=swap" rel="stylesheet">
</head>
<header>
    <nav>
        <ul>
            <li><a href="userapp.php">Book Appointment</a></li>
            <li><a href="viewappointments.php">View Appointments</a></li>
            <li><a href="../../html/home/home.html">Back to Home</a></li>
        </ul>
        <div id="banner"><img src="../../images/appoinment/logo.png" class="img0"></div>
    </nav>
</header>
<body>
    <div class="headerA">
        <h2>Make Appointment</h2>
    </div>

    <!-- Appointment Booking Form -->
    <form method="post" action="userapp.php">
        <?php include('errors.php'); ?>

        <div class="input-groupA">
            <label>Appointment ID</label>
            <input type="text" name="addID">
        </div>

        <div class="input-groupA">
            <label>Description</label>
            <input type="text" name="adddesc">
        </div>

        <div class="input-groupA">
            <label>Address</label>
            <input type="text" name="addAddress">
        </div>

        <div class="input-groupA">
            <label>Contact Number</label>
            <input type="text" name="addContactNumber">
        </div>

        <div class="input-groupA">
            <label>Email Address</label>
            <input type="email" name="addEmail">
        </div>

        <div class="input-groupA">
            <label>Date</label>
            <input type="date" name="adddate">
        </div>

        <div class="input-groupA">
            <label>Time</label>
            <input type="time" name="addtime">
        </div>

        <div class="input-groupA">
            <button type="submit" name="Add" class="btnA">Book</button>
        </div>
    </form>

    <br><br><br>

    <div class="headerAD">
        <h2>Cancel Booking</h2>
    </div>

    <!-- Appointment Cancellation Form -->
    <form method="post" action="userapp.php" class="delete">
        <div class="input-groupA">
            <label>Appointment ID</label>
            <input type="text" name="deleteID">
        </div>

        <div class="input-groupA">
            <button type="submit" name="Delete" class="btnA">Delete</button>
        </div>
    </form>
</body>
</html>
