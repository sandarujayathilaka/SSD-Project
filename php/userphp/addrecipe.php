<?php

session_start([
    'cookie_lifetime' => 86400,  
    'cookie_secure' => false,     
    'cookie_httponly' => true,   
    'cookie_samesite' => 'Strict' 
]);

require "config.php";

// Generate CSRF token if not set
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Set the target directory for uploads
$targetDir = "../../images/recipe-uploads/";

// Check if the uploads directory exists, create it if not
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0755, true); // Create directory with permissions
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  
    // Check for CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Invalid CSRF token.'); 
    }

    $category = $_POST['select1'];
    $username = $_SESSION['name'];
    $Title = $_POST['Title'];
    $Time = $_POST['Time'];
    $Ingredients = $_POST['Ingredients'];
    $Description = $_POST['Description'];

    // Check if an image was uploaded
    if (isset($_FILES['Images'])) {
        if ($_FILES['Images']['error'] === UPLOAD_ERR_OK) {
            $targetFile = $targetDir . basename($_FILES['Images']['name']);
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            // Check if the file is an actual image
            $check = getimagesize($_FILES['Images']['tmp_name']);
            if ($check === false) {
                die("File is not an image.");
            }

            // Move the uploaded file to the target directory
            if (move_uploaded_file($_FILES['Images']['tmp_name'], $targetFile)) {
                // Image successfully uploaded, prepare for database insertion
                if ($con->connect_error) {
                    die('Connection failed: ' . $con->connect_error);
                } else {
                    // Prepare SQL statement for database insertion
                    $stmt = $con->prepare("INSERT INTO add_recipe (category, user_name, Title, Time, Ingredients, Description, Images) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("sssssss", $category, $username, $Title, $Time, $Ingredients, $Description, $targetFile);

                    if ($stmt->execute()) {
                        // header('Location: ./my.php');
                        echo '<script type="text/javascript" nonce="random123">alert("Recipe added successfully!"); window.location = "my.php";</script>';
                        exit();
                    } else {
                        echo "Error: " . $stmt->error;
                    }

                    $stmt->close();
                    $con->close();
                }
            } else {
                die("Error uploading file.");
            }
        } else {
            switch ($_FILES['Images']['error']) {
                case UPLOAD_ERR_INI_SIZE:
                    die('File is too large. Please upload a smaller file.');
                case UPLOAD_ERR_FORM_SIZE:
                    die('File is too large according to form specification.');
                case UPLOAD_ERR_PARTIAL:
                    die('File was only partially uploaded. Please try again.');
                case UPLOAD_ERR_NO_FILE:
                    die('No file was uploaded. Please choose a file.');
                case UPLOAD_ERR_NO_TMP_DIR:
                    die('Missing a temporary folder. Check server configuration.');
                case UPLOAD_ERR_CANT_WRITE:
                    die('Failed to write file to disk.');
                case UPLOAD_ERR_EXTENSION:
                    die('File upload stopped by a PHP extension.');
                default:
                    die('Unknown upload error.');
            }
        }
    } else {
        die('No file input found.');
    }
}
?>