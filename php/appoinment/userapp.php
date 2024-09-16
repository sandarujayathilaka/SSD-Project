<?php 
session_start();
require 'config.php';  
 
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$errors = [];

function validate_phone($phone) {
    return preg_match('/^[0-9]{10}$/', $phone); 
}

if (isset($_POST['Add'])) {
    
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Invalid CSRF token.");
    }

    // Sanitize and validate input data
    $addID = filter_var(trim($_POST['addID']), FILTER_SANITIZE_STRING);
    $adddesc = filter_var(trim($_POST['adddesc']), FILTER_SANITIZE_STRING);
    $addAddress = filter_var(trim($_POST['addAddress']), FILTER_SANITIZE_STRING);
    $addContactNumber = filter_var(trim($_POST['addContactNumber']), FILTER_SANITIZE_STRING);
    $addEmail = filter_var(trim($_POST['addEmail']), FILTER_SANITIZE_EMAIL);
    $adddate = $_POST['adddate'];
    $addtime = $_POST['addtime'];

    if (empty($addID) || empty($adddesc) || empty($addAddress) || empty($addContactNumber) || empty($addEmail) || empty($adddate) || empty($addtime)) {
        $errors[] = "All fields are required.";
    }

    if (!filter_var($addEmail, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (!validate_phone($addContactNumber)) {
        $errors[] = "Invalid contact number. It must be a 10-digit number.";
    }

    if (!DateTime::createFromFormat('Y-m-d', $adddate)) {
        $errors[] = "Invalid date format.";
    }

    if (!DateTime::createFromFormat('H:i', $addtime)) {
        $errors[] = "Invalid time format.";
    }

    if (count($errors) === 0) {
    
        $stmt = $con->prepare("INSERT INTO appointments (id, description, address, contact_number, email, date, time) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $addID, $adddesc, $addAddress, $addContactNumber, $addEmail, $adddate, $addtime);

        if ($stmt->execute()) {
            echo '<script type="text/javascript">alert("Appointment booked successfully.");</script>';
        } else {
            echo '<script type="text/javascript">alert("Error booking appointment.");</script>';
        }

        $stmt->close();
    } else {
        foreach ($errors as $error) {
            echo '<script type="text/javascript">alert("Error: ' . $error . '");</script>';
        }
    }
}


if (isset($_POST['Delete'])) {

   
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Invalid CSRF token.");
    }


    $deleteID = filter_var(trim($_POST['deleteID']), FILTER_SANITIZE_STRING);

    if (empty($deleteID)) {
        $errors[] = "Appointment ID is required for cancellation.";
    }

  
    if (count($errors) === 0) {
      
        $stmt = $con->prepare("DELETE FROM appointments WHERE id = ?");
        $stmt->bind_param("s", $deleteID);

        if ($stmt->execute()) {
            echo '<script type="text/javascript">alert("Appointment canceled successfully.");</script>';
        } else {
            echo '<script type="text/javascript">alert("Error canceling appointment.");</script>';
        }

        $stmt->close();
    } else {
        foreach ($errors as $error) {
            echo '<script type="text/javascript">alert("Error: ' . $error . '");</script>';
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

    <!-- Appointment form -->
    <form method="post" action="userapp.php">
        <?php include ('errors.php'); ?> 

        <div class="input-groupA">
            <label>Appointment ID</label>
            <input type="text" name="addID">
        </div>
        <div class="input-groupA">
            <label>Appointment ID</label>
            <input type="text" name="addID">
        </div>

        <div class="input-groupA">
            <label>Description</label>
            <input type="text" name="adddesc">
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
            <label>Address</label>
            <input type="text" name="addAddress">
        </div>

        <div class="input-groupA">
            <label>Contact Number</label>
            <input type="text" name="addContactNumber">
        </div>

        <div class="input-groupA">
            <label>Email address</label>
            <input type="email" name="addEmail">
        </div>

        <div class="input-groupA">
            <label>Date</label>
            <input type="date" name="adddate">
        </div>
        <div class="input-groupA">
            <label>Date</label>
            <input type="date" name="adddate">
        </div>

        <div class="input-groupA">
            <label>Time</label>
            <input type="time" name="addtime">
        </div>

        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

        <div class="input-groupA">
            <button type="submit" name="Add" class="btnA">Book</button>
        </div>
    </form>

    <br><br><br>
        <div class="input-groupA">
            <button type="submit" name="Add" class="btnA">Book</button>
        </div>
    </form>

    <br><br><br>

    <div class="headerAD">
        <h2>Cancel Booking</h2>
    </div>
    <div class="headerAD">
        <h2>Cancel Booking</h2>
    </div>

    <form method="post" action="userapp.php" class="delete">
        <div class="input-groupA">
            <label>Appointment ID</label>
            <input type="text" name="deleteID">
        </div>

        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

        <div class="input-groupA">
            <button type="submit" name="Delete" class="btnA">Delete</button>
        </div>
    </form>
        <div class="input-groupA">
            <button type="submit" name="Delete" class="btnA">Delete</button>
        </div>
    </form>
</body>
</html>

