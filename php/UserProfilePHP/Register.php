<?php
session_start(); // Start session to store CSRF token

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Invalid CSRF token');
    }

    // Retrieve and sanitize user inputs
$fName = filter_var($_POST['Fname'], FILTER_SANITIZE_STRING);
$lName = filter_var($_POST['Lname'], FILTER_SANITIZE_STRING);

$fullName = $fName . " " . $lName;

$ad1 = filter_var($_POST['state'], FILTER_SANITIZE_STRING);
$ad2 = filter_var($_POST['city'], FILTER_SANITIZE_STRING);
$ad3 = filter_var($_POST['province'], FILTER_SANITIZE_STRING);
$ad4 = filter_var($_POST['country'], FILTER_SANITIZE_STRING);

$fullADD = $ad1 . ", " . $ad2 . ", " . $ad3 . ", " . $ad4;

$Tel = filter_var($_POST['tel'], FILTER_SANITIZE_STRING);
$mail = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$pass = $_POST['password'];

// Validate email
if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
    die("Invalid email format.");
}

// Hash the password
$hashedPassword = password_hash($pass, PASSWORD_BCRYPT);

// File upload handling
$target_dir = "../../images/UserProfileIMAGES/uploads/";
$filename = basename($_FILES["file"]["name"]);
$filename = preg_replace("/[^a-zA-Z0-9\._-]/", "", $filename);
$target_file = $target_dir . uniqid() . "_" . $filename;

// Validate file type
$fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
$allowedTypes = array('jpg', 'jpeg', 'png', 'gif');
if (!in_array($fileType, $allowedTypes)) {
    die("Invalid file type.");
}

// Move the uploaded file
if (isset($_FILES["file"]) && $_FILES["file"]["error"] == 0) {
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        echo "The file " . htmlspecialchars($filename) . " has been uploaded.";
    } else {
        die("Error while uploading your file.");
    }
} else {
    die("File not available or error during file upload.");
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'iwt');
if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}


$data = isset($_POST['data']) ? $_POST['data'] : [];
$allData = implode(" ", array_map('htmlspecialchars', $data));

$stmt = $conn->prepare("INSERT INTO user (Fname, Lname, FullName, Address, Tel, Email, Password, Profile, AllCat) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssssss", $fName, $lName, $fullName, $fullADD, $Tel, $mail, $hashedPassword, $target_file, $allData);

if ($stmt->execute()) {
    echo "<h1>Registration successfully...</h1>";
    header("Location: ../../html/home/home.html");
} else {
    echo "<h1>Registration failed. Please try again.</h1>";
}

$stmt->close();
$conn->close();
    
    unset($_SESSION['csrf_token']);
}

?>