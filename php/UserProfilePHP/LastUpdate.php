<?php
session_start();

// Retrieve the email from the session
$Ymail = isset($_SESSION["mail"]) ? $_SESSION["mail"] : null;

if (!$Ymail) {
    // Redirect or throw an error if the email session is not set
    die("Session expired or invalid.");
}

echo "Email: " . htmlspecialchars($Ymail); 


$con = new mysqli("localhost", "root", "", "iwt");

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
} else {
    echo "Database connection successful.";
}

if (isset($_POST['summon'])) {
    $myPass = $_POST['password'];

    if (!empty($myPass)) {
       
        $hashedPass = password_hash($myPass, PASSWORD_DEFAULT);

        $stmt = $con->prepare("UPDATE user SET Password = ? WHERE Email = ?");
        $stmt->bind_param("ss", $hashedPass, $Ymail);

        if ($stmt->execute()) {
            header('Location: ../../html/UserProfileHTML/Login.html?message=updated');
        } else {
            echo '<script type="text/javascript">alert("Data Not Updated: ' . $stmt->error . '")</script>';
        }

        $stmt->close();
    } else {
        echo '<script type="text/javascript">alert("Password cannot be empty!")</script>';
    }
}

$con->close();
?>
