<?php
session_start();

$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'iwt';

// Connect to MySQL
$con = new mysqli($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

// Check connection
if ($con->connect_error) {
    die('Failed to connect to MySQL: ' . $con->connect_error);
}

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
