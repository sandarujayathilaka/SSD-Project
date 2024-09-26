<?php  
// Initialize the $errors array
$errors = array();

$con = new mysqli("localhost", "root", "", "iwt");

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Add appointment
// if (isset($_POST['Add'])) {

//    $addID = $_POST['addID'];  
//    $adddesc = $_POST['adddesc'];
//    $addAddress = $_POST['addAddress'];
//    $addContactNumber = $_POST['addContactNumber'];
//    $addEmail = $_POST['addEmail'];
//    $addtime = $_POST['addtime'];
//    $adddate = $_POST['adddate'];

//    // Validate inputs
//    if (empty($addID)) array_push($errors, "Appointment ID is required");
//    if (empty($adddesc)) array_push($errors, "Description is required");
//    if (empty($addAddress)) array_push($errors, "Address is required");
//    if (empty($addContactNumber)) array_push($errors, "Contact Number is required");
//    if (empty($addEmail)) array_push($errors, "Email is required");
//    if (empty($adddate)) array_push($errors, "Date is required");
//    if (empty($addtime)) array_push($errors, "Time is required");

//    if (count($errors) == 0) {
//        $stmt = $con->prepare("INSERT INTO appoinment (appID, appDescription, address, contact, email, date, time) VALUES (?, ?, ?, ?, ?, ?, ?)");
//        $stmt->bind_param("sssssss", $addID, $adddesc, $addAddress, $addContactNumber, $addEmail, $adddate, $addtime);

//        if ($stmt->execute()) {
//            echo "Appointment added successfully!";
//        } else {
//            array_push($errors, "Error: " . $stmt->error);
//        }

//        $stmt->close();
//    }
// }

// // Delete appointment
// if (isset($_POST['Delete'])) {

//    $deleteID = $_POST['deleteID'];

//    if (empty($deleteID)) {
//        array_push($errors, "Appointment ID is required");
//    }


//    if (count($errors) == 0) {
//        $stmt = $con->prepare("DELETE FROM appoinment WHERE appID = ?");
//        $stmt->bind_param("s", $deleteID);

//        if ($stmt->execute()) {
//            if ($stmt->affected_rows == 0) {
//                array_push($errors, "Wrong Appointment ID");
//            } else {
//                echo "Appointment deleted successfully!";
//            }
//        } else {
//            array_push($errors, "Error: " . $stmt->error);
//        }

//        $stmt->close();
//    }
// }
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

    try {
    $stmt = $con->prepare("INSERT INTO appoinment (appID, appDescription, address, contact, email, date, time) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $addID, $adddesc, $addAddress, $addContactNumber, $addEmail, $adddate, $addtime);

    if ($stmt->execute()) {
        echo '<script>alert("Appointment booked successfully.");</script>';
    } else {
        throw new Exception("Database error: Unable to book appointment.");
    }
} catch (Exception $e) {
    error_log($e->getMessage());  // Log the error for internal review
    echo '<script>alert("There was an error processing your request. Please try again later.");</script>';
    echo '<script>alert("Error: ' . $e->getMessage() . '");</script>';
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
      
        $stmt = $con->prepare("DELETE FROM appoinment WHERE appID = ?");
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
$con->close();
?>
