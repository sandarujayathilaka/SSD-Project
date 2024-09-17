<?php 
session_start();
require 'config.php';  
 
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$errors = [];

// Function to validate phone numbers (contact number)
function validate_phone($phone) {
    return preg_match('/^[0-9]{10}$/', $phone); // 10 digits validation
}

// Validate date format
function validate_date($date) {
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date;
}

// Validate time format (HH:MM format)
function validate_time($time) {
    return preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', $time);
}

// Validate Email
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Booking appointment form submission
if (isset($_POST['Add'])) {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Invalid CSRF token.");
    }

    // Sanitize and validate inputs
    $addID = filter_var(trim($_POST['addID']), FILTER_SANITIZE_STRING);
    $adddesc = filter_var(trim($_POST['adddesc']), FILTER_SANITIZE_STRING);
    $addAddress = filter_var(trim($_POST['addAddress']), FILTER_SANITIZE_STRING);
    $addContactNumber = filter_var(trim($_POST['addContactNumber']), FILTER_SANITIZE_STRING);
    $addEmail = filter_var(trim($_POST['addEmail']), FILTER_SANITIZE_EMAIL);
    $adddate = filter_var($_POST['adddate'], FILTER_SANITIZE_STRING);
    $addtime = filter_var($_POST['addtime'], FILTER_SANITIZE_STRING);

    // Check required fields
    if (empty($addID) || empty($adddesc) || empty($addAddress) || empty($addContactNumber) || empty($addEmail) || empty($adddate) || empty($addtime)) {
        $errors[] = "All fields are required.";
    }

    // Validate email
    if (!validate_email($addEmail)) {
        $errors[] = "Invalid email format.";
    }

    // Validate phone number
    if (!validate_phone($addContactNumber)) {
        $errors[] = "Invalid contact number. It must be a 10-digit number.";
    }

    // Validate date
    if (!validate_date($adddate)) {
        $errors[] = "Invalid date format.";
    }

    // Validate time
    if (!validate_time($addtime)) {
        $errors[] = "Invalid time format.";
    }

    // If no errors, process the form
    if (count($errors) === 0) {
        $stmt = $con->prepare("INSERT INTO appointments (id, description, address, contact_number, email, date, time) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $addID, $adddesc, $addAddress, $addContactNumber, $addEmail, $adddate, $addtime);

        if ($stmt->execute()) {
            echo '<script>alert("Appointment booked successfully.");</script>';
        } else {
            echo '<script>alert("Error booking appointment.");</script>';
        }

        $stmt->close();
    } else {
        foreach ($errors as $error) {
            echo '<script>alert("Error: ' . $error . '");</script>';
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

