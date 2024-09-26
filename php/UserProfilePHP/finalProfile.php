<?php
session_start([
    'cookie_lifetime' => 86400,  
    'cookie_secure' => false,     
    'cookie_httponly' => true,   
    'cookie_samesite' => 'Strict' 
]);

$profile = isset($_SESSION['name']) ? $_SESSION['name'] : 'Guest';
// echo htmlspecialchars($profile); 

require('config.php');

// Check if the form was submitted
if (isset($_POST['update'])) {
    // Sanitize and validate input
    $email = $con->real_escape_string($_POST['email']);
    $fname = $con->real_escape_string($_POST['fname']);
    $lname = $con->real_escape_string($_POST['lname']);
    $address = $con->real_escape_string($_POST['address']);
    $tel = $con->real_escape_string($_POST['tel']);
    $password = $con->real_escape_string($_POST['password']);
    $allCat = $con->real_escape_string($_POST['data']);

    // Hash the new password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

 
    $stmt = $con->prepare("UPDATE user SET Email = ?, Fname = ?, Lname = ?, Address = ?, Tel = ?, Password = ?, AllCat = ? WHERE Email = ?");
    if ($stmt) {
      
        $stmt->bind_param('ssssssss', $email, $fname, $lname, $address, $tel, $hashed_password, $allCat, $_SESSION['name']);
        
        if ($stmt->execute()) {
            echo '<script type="text/javascript">alert("Data Updated");</script>';
        } else {
            echo '<script type="text/javascript">alert("Data Not Updated: ' . $stmt->error . '");</script>';
        }

        $stmt->close();
    } else {
        echo '<script type="text/javascript">alert("Failed to prepare the SQL statement.");</script>';
    }
}

$con->close();
?>
