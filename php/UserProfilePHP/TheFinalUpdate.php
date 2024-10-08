<?php
session_start([
    'cookie_lifetime' => 86400,  
    'cookie_secure' => false,     
    'cookie_httponly' => true,   
    'cookie_samesite' => 'Strict' 
]);

if (!isset($_SESSION['loggedin'])) {
    header('Location: login.html');
    exit;
}

require('config.php');

$profile = $_SESSION['name'];

if (isset($_POST['update'])) {

    // Sanitize and validate inputs
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $fname = filter_var($_POST['fname'], FILTER_SANITIZE_STRING);
    $lname = filter_var($_POST['lname'], FILTER_SANITIZE_STRING);
    $address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
    $tel = filter_var($_POST['tel'], FILTER_SANITIZE_STRING);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit;
    }

    $password = $_POST['password'];
    $hashedPassword = !empty($password) ? password_hash($password, PASSWORD_BCRYPT) : null;

    // Handle file upload securely
    $target_dir = "../../images/UserProfileIMAGES/uploads/";
    $target_file = null;
    
    if (!empty($_FILES["file"]["name"])) {
        $filename = basename($_FILES["file"]["name"]);
        $filename = preg_replace("/[^a-zA-Z0-9\._-]/", "", $filename); // Sanitize filename
        $target_file = $target_dir . uniqid() . "_" . $filename;
        
        $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array($fileType, $allowedTypes)) {
            if (!move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                echo "Error uploading the file.";
                exit;
            }
        } else {
            echo "Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.";
            exit;
        }
    }

    $query = "UPDATE user SET 
                Email = ?, 
                Fname = ?, 
                Lname = ?, 
                Address = ?, 
                Tel = ?";

    if ($hashedPassword) {
        $query .= ", Password = ?";
    }
    
    if ($target_file) {
        $query .= ", Profile = ?";
    }
    
    $query .= " WHERE Email = ?";

    $stmt = $con->prepare($query);

    if ($hashedPassword && $target_file) {
        $stmt->bind_param("ssssssss", $email, $fname, $lname, $address, $tel, $hashedPassword, $target_file, $profile);
    } elseif ($hashedPassword) {
        $stmt->bind_param("sssssss", $email, $fname, $lname, $address, $tel, $hashedPassword, $profile);
    } elseif ($target_file) {
        $stmt->bind_param("ssssss", $email, $fname, $lname, $address, $tel, $target_file, $profile);
    } else {
        $stmt->bind_param("ssssss", $email, $fname, $lname, $address, $tel, $profile);
    }

    if ($stmt->execute()) {
      
        header('Location: profile.php?message=updated');
        exit;
    } else {
        echo "Error updating profile: " . $stmt->error;
    }

    $stmt->close();
}

$con->close();
?>
