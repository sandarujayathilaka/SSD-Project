<?php

$fName = $_POST['Fname'];
$lName = $_POST['Lname'];

$fullName = $fName . " " . $lName;

$ad1 = $_POST['state'];
$ad2 = $_POST['city'];
$ad3 = $_POST['province'];
$ad4 = $_POST['country'];

$fullADD = $ad1 . " , " . $ad2 . " , " . $ad3 . " , " . $ad4;

$Tel = $_POST['tel'];

$mail = $_POST['email'];

$pass = $_POST['password'];

// Hash the password 
$hashedPassword = password_hash($pass, PASSWORD_BCRYPT);

$target_dir = "../../images/UserProfileIMAGES/uploads/";

// Sanitize the file name
$filename = basename($_FILES["file"]["name"]);
$filename = preg_replace("/[^a-zA-Z0-9\._-]/", "", $filename);

// Create unique file name to avoid overwriting
$target_file = $target_dir . uniqid() . "_" . $filename;

// Validate file type
$fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
$allowedTypes = array('jpg', 'jpeg', 'png', 'gif');
if (!in_array($fileType, $allowedTypes)) {
    die("Invalid file type.");
}

// Move the uploaded file to the target directory
if (isset($_FILES["file"])) {
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        echo "The file " . $filename . " is uploaded.";
    } else {
        echo "Error while uploading your file.";
    }
} else {
    echo "File not available";
}


echo('<br>');
echo($target_file);

// Database connection
$conn = new mysqli('localhost', 'root', '', 'iwt');

if ($conn->connect_error) {
    echo "$conn->connect_error";
    die("Connection Failed: " . $conn->connect_error);
} else {
    $data = $_POST['data'];
    $allData = implode(" ", $data);
    echo $allData;

    $stmt = $conn->prepare("INSERT INTO user (Fname, Lname, FullName, Address, Tel, Email, Password, Profile, AllCat) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    $stmt->bind_param("sssssssss", $fName, $lName, $fullName, $fullADD, $Tel, $mail, $hashedPassword, $target_file, $allData);
    
    $execval = $stmt->execute();
    echo $execval;
    echo "<h1>Registration successfully...</h1>";
 
    header("Location: ../../html/home/home.html");

    $stmt->close();
    $conn->close();
}
?>
