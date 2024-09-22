<?php
session_start([
    'cookie_lifetime' => 86400,  
    'cookie_secure' => true,     
    'cookie_httponly' => true,   
    'cookie_samesite' => 'Strict' 
]);

// Sanitize the session data before outputting
$Ymail = htmlspecialchars($_SESSION["mail"], ENT_QUOTES, 'UTF-8');

echo "Email: " . htmlspecialchars($Ymail); 

// Secure database connection
$con = new mysqli("localhost", "root", "", "iwt");
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
} else {
    echo "done";
}

// Sanitize the password input to prevent XSS
$myPass = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');

// Hash the password to prevent plaintext storage
$hashedPass = password_hash($myPass, PASSWORD_BCRYPT);

if (isset($_POST['summon'])) {
    // Use prepared statements to prevent SQL injection
    $query = $con->prepare("UPDATE user SET Password = ? WHERE Email = ?");
    $query->bind_param("ss", $hashedPass, $Ymail);

    if ($query->execute()) {
        header('Location:../../html/UserProfileHTML/Login.html?message=updated');
    } else {
        echo '<script type="text/javascript">alert("Data Not Updated")</script>';
    }
}
?>
