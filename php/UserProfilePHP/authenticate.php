<?php
session_start([
    'cookie_lifetime' => 86400,  
    'cookie_secure' => true,     
    'cookie_httponly' => true,   
    'cookie_samesite' => 'Strict' 
]);

// Generate CSRF token if not set
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
  }

if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    // Invalid CSRF token, handle the error
    die('Invalid CSRF token');
}

require 'config.php';

function verify_user($con, $table, $username_field, $password_field, $redirect_url) {
    if ($stmt = $con->prepare("SELECT $username_field, $password_field FROM $table WHERE $username_field = ?")) {
        $stmt->bind_param('s', $_POST['username']);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($UserName, $Pword);
            $stmt->fetch();

            // Verify the hashed password
            if (password_verify(trim($_POST['password']), $Pword)) {
                session_regenerate_id();
                $_SESSION['loggedin'] = TRUE;
                $_SESSION['name'] = $_POST['username'];
                header("Location: $redirect_url");
                exit();
            } else {
                echo 'Incorrect password!';
            }
        } else {
            echo 'Incorrect username!';
        }
        $stmt->close();
    } else {
        echo 'Failed to prepare the SQL statement.';
    }
}

// Check if the username and password fields are filled
if (isset($_POST['username'], $_POST['password'])) {

 // Sanitize user inputs
    $username = htmlspecialchars(trim($_POST['username']), ENT_QUOTES, 'UTF-8');
    $password = htmlspecialchars(trim($_POST['password']), ENT_QUOTES, 'UTF-8');


     // Validate email format
    if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
        die('Invalid email format');
    }

    // User Login
    verify_user($con, 'user', 'Email', 'Password', '../../html/home/home.html');
    
    // Admin Login
    verify_user($con, 'adminacc', 'UserName', 'Pword', '../Admin/dashboard.php');
    
    // Nutritionist Login
    verify_user($con, 'nutriacc', 'UserName', 'Pword', '../appoinment/nutrionistapp.php');
    
    // Officer Login
    verify_user($con, 'officeracc', 'UserName', 'Pword', '../adofficer/main.php');
} else {
    echo 'Please fill both the username and password fields!';
}

$con->close();
?>
