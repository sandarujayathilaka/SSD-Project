<?php 

session_start([
    'cookie_lifetime' => 86400,  
    'cookie_secure' => true,     
    'cookie_httponly' => true,   
    'cookie_samesite' => 'Strict' 
]);
require 'config.php';  

$secret_key = 'your_secret_key'; // Store this securely in environment variables or config

// Check if CSRF token is set
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$errors = [];

// Function to generate HMAC
function generate_hmac($data, $key) {
    return hash_hmac('sha256', $data, $key);
}

// Function to validate the HMAC
function validate_hmac($data, $hmac, $key) {
    return hash_equals($hmac, generate_hmac($data, $key));
}

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

// Function to show a generic 500 error
function handle_500_error() {
    http_response_code(500);
    die('An internal server error occurred. Please try again later.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validate CSRF token
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            handle_500_error(); // Invalid CSRF token
        }

        if (isset($_POST['Add'])) {
            // Sanitize and validate inputs
            $addID = filter_var(trim($_POST['addID']), FILTER_SANITIZE_STRING);
            $adddesc = filter_var(trim($_POST['adddesc']), FILTER_SANITIZE_STRING);
            $addAddress = filter_var(trim($_POST['addAddress']), FILTER_SANITIZE_STRING);
            $addContactNumber = filter_var(trim($_POST['addContactNumber']), FILTER_SANITIZE_STRING);
            $addEmail = filter_var(trim($_POST['addEmail']), FILTER_SANITIZE_EMAIL);
            $adddate = filter_var($_POST['adddate'], FILTER_SANITIZE_STRING);
            $addtime = filter_var($_POST['addtime'], FILTER_SANITIZE_STRING);

            // Input validation
            if (empty($addID) || empty($adddesc) || empty($addAddress) || empty($addContactNumber) || empty($addEmail) || empty($adddate) || empty($addtime)) {
                throw new Exception('All fields are required.');
            }

            if (!validate_email($addEmail)) {
                throw new Exception('Invalid email format.');
            }

            if (!validate_phone($addContactNumber)) {
                throw new Exception('Invalid contact number. It must be a 10-digit number.');
            }

            if (!validate_date($adddate)) {
                throw new Exception('Invalid date format.');
            }

            if (!validate_time($addtime)) {
                throw new Exception('Invalid time format.');
            }
     

        }

        if (isset($_POST['Delete'])) {
            // Sanitize and validate deleteID (provided by user)
            $deleteID = filter_var(trim($_POST['deleteID']), FILTER_SANITIZE_STRING);

            if (empty($deleteID)) {
                throw new Exception('Appointment ID is required for cancellation.');
            }

            

        }

    } catch (Exception $e) {
        
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

    <!-- Appointment form -->
    <form method="post" action="userapp.php">
        <?php include ('errors.php'); ?> 

        <div class="input-groupA">
            <label>Appointment ID</label>
            <input type="text" name="addID" required>
            
        </div>

        <div class="input-groupA">
            <label>Description</label>
            <input type="text" name="adddesc" required>
        </div>

        <div class="input-groupA">
            <label>Address</label>
            <input type="text" name="addAddress" required>
        </div>

        <div class="input-groupA">
            <label>Contact Number</label>
            <input type="text" name="addContactNumber" required>
        </div>

        <div class="input-groupA">
            <label>Email address</label>
            <input type="email" name="addEmail" required>
        </div>

        <div class="input-groupA">
            <label>Date</label>
            <input type="date" name="adddate" required>
        </div>

        <div class="input-groupA">
            <label>Time</label>
            <input type="time" name="addtime" required>
        </div>

        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

        <div class="input-groupA">
            <button type="submit" name="Add" class="btnA">Book</button>
        </div>
    </form>

    <br><br><br>

    <div class="headerAD">
        <h2>Cancel Booking</h2>
    </div>

    <!-- Deletion form -->
    <form method="post" action="userapp.php" class="delete">
        <div class="input-groupA">
            <label>Appointment ID</label>
            <input type="text" name="deleteID" required>
            
        </div>

        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

        <div class="input-groupA">
            <button type="submit" name="Delete" class="btnA">Delete</button>
        </div>
    </form>
</body>
</html>
