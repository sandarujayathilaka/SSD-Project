<?php
// Retrieve and sanitize form data
$category = $_POST['select1'];
$username = $_POST['user_name'];
$title = $_POST['Title'];
$time = $_POST['Time'];
$ingredients = $_POST['Ingredients'];
$description = $_POST['Description'];

$image = null;
if (isset($_FILES['Images']) && $_FILES['Images']['error'] == UPLOAD_ERR_OK) {
    $image = $_FILES['Images']['name'];
    $targetDir = "../../images/uploads/"; 
    $targetFile = $targetDir . basename($image);
    move_uploaded_file($_FILES['Images']['tmp_name'], $targetFile);
}

$conn = new mysqli('localhost', 'root', '', 'iwt');

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$stmt = $conn->prepare("INSERT INTO add_recipe (category, user_name, Title, Time, Ingredients, Description, Images) VALUES (?, ?, ?, ?, ?, ?, ?)");

if ($stmt === false) {
    die('Prepare failed: ' . $conn->error);
}

$stmt->bind_param("sssssss", $category, $username, $title, $time, $ingredients, $description, $image);

if ($stmt->execute()) {
    echo "Success";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
