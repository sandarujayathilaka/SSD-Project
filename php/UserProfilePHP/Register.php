<?php
// Sanitize form inputs to prevent XSS
$fName = htmlspecialchars($_POST['Fname'], ENT_QUOTES, 'UTF-8');
$lName = htmlspecialchars($_POST['Lname'], ENT_QUOTES, 'UTF-8');
$ad1 = htmlspecialchars($_POST['state'], ENT_QUOTES, 'UTF-8');
$ad2 = htmlspecialchars($_POST['city'], ENT_QUOTES, 'UTF-8');
$ad3 = htmlspecialchars($_POST['province'], ENT_QUOTES, 'UTF-8');
$ad4 = htmlspecialchars($_POST['country'], ENT_QUOTES, 'UTF-8');
$Tel = htmlspecialchars($_POST['tel'], ENT_QUOTES, 'UTF-8');
$mail = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

// Hash the password
$pass = $_POST['password'];
$hashedPassword = password_hash($pass, PASSWORD_BCRYPT);

// Validate the file upload
$target_dir = "../../images/UserProfileIMAGES/uploads/";
$target_file = $target_dir . basename($_FILES["file"]["name"]);

// Check if the file is an actual image to prevent malicious uploads
$check = getimagesize($_FILES["file"]["tmp_name"]);
if ($check !== false) {
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        echo "The file " . htmlspecialchars(basename($_FILES["file"]["name"]), ENT_QUOTES, 'UTF-8') . " has been uploaded.";
    } else {
        die("Error while uploading your file.");
    }
} else {
    echo "File is not an image.";
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'iwt');
if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
} else {
    $data = $_POST['data'];
    $allData = htmlspecialchars(implode(" ", $data), ENT_QUOTES, 'UTF-8');

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO user (Fname, Lname, FullName, Address, Tel, Email, Password, Profile, AllCat) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $fName, $lName, $fullName, $fullADD, $Tel, $mail, $hashedPassword, $target_file, $allData);

    $execval = $stmt->execute();
    if ($execval) {
        echo "<h1>Registration successfully...</h1>";
        header("Location: ../../html/home/home.html");
    }

$stmt->close();
$conn->close();
?>
