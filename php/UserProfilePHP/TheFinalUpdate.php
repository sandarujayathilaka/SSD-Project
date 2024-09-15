<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    header('Location: login.html');
    exit;
}

// Database connection
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'iwt';

$con = new mysqli($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

if ($con->connect_error) {
    die("Connection Failed: " . $con->connect_error);
}

$profile = $_SESSION['name'];

// Check if form was submitted
if (isset($_POST['update'])) {

    // Sanitize inputs
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $fname = filter_var($_POST['fname'], FILTER_SANITIZE_STRING);
    $lname = filter_var($_POST['lname'], FILTER_SANITIZE_STRING);
    $address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
    $tel = filter_var($_POST['tel'], FILTER_SANITIZE_STRING);
    $password = $_POST['password']; 

    // Handle file upload securely
    $target_dir = "../../images/UserProfileIMAGES/uploads/";
    $filename = basename($_FILES["file"]["name"]);
    $filename = preg_replace("/[^a-zA-Z0-9\._-]/", "", $filename); // Sanitize filename
    $target_file = $target_dir . uniqid() . "_" . $filename;

    // Validate file type
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');

    // Handle file upload if a file was provided
    if (!empty($_FILES["file"]["name"])) {
        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                echo "The file " . basename($_FILES["file"]["name"]) . " has been uploaded.";
            } else {
                echo "Error uploading the file.";
                exit;
            }
        } else {
            echo "Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.";
            exit;
        }
    } else {
        // No new file uploaded, so don't update the profile picture
        $target_file = null;
    }

    // Secure password hashing (if password is being updated)
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Prepare update query
    $query = "UPDATE user SET 
                Email = ?, 
                Fname = ?, 
                Lname = ?, 
                Address = ?, 
                Tel = ?, 
                Password = ?";

    // If a new profile picture was uploaded, include it in the update
    if ($target_file) {
        $query .= ", Profile = ?";
    }

    $query .= " WHERE Email = ?";

    // Prepare the SQL statement
    $stmt = $con->prepare($query);

    if ($target_file) {
    
        $stmt->bind_param("ssssssss", $email, $fname, $lname, $address, $tel, $hashedPassword, $target_file, $profile);
    } else {
    
        $stmt->bind_param("sssssss", $email, $fname, $lname, $address, $tel, $hashedPassword, $profile);
    }

    // Execute the query
    if ($stmt->execute()) {
        // Success
        header('Location: profile.php?message=updated');
        exit;
    } else {
        echo "Error updating profile.";
    }

    // Close the statement and connection
    $stmt->close();
}

$con->close();

?>
