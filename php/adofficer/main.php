
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FOODOVEN</title>
    <link rel="stylesheet" href="../../css/index.css">
    <link rel="stylesheet" href="../../css/forms.css">
</head>
<body>

    <nav>
        <ul>
            <li><a href="main.php">Add Advertisement</a></li>
            <li><a href="addeditview_advertisement.php">View Existing</a></li>
            <li><a href="../Admin/logout.php">Logout</a></li>
        </ul>

        <div id="banner">
            <img src="../../Images/logo.png" class="logo1">
        </div>
    </nav>

    <br>
    <br>

    <center>
        <h2>Add/Update Advertisement</h2>
    </center>
    <br/>

    <form method="post" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <br>
        Advertisement ID :</br>
        <input type="text" name="Oid" placeholder="Advertisement ID" required> <br/><br/>

        Related :<br/>
        <input type="text" name="related" placeholder="Foods/Not foods" required> <br/><br/>

        Contact Number :<br/>
        <input type="tel" name="mobile" placeholder="0xxxxxxxxx" pattern="[0-9]{10}" required><br/><br/>

        E-mail : <br/>
        <input type="email" name="email" placeholder="abc@gmail.com" required><br/><br/>

        Details : <br/>
        <textarea name="address" rows="8" cols="58" required></textarea> <br/><br/>

        Date : <br/>
        <input type="date" name="day" required> <br/><br/>

        Insert Image :<br>
        <input type="file" name="img" accept="image/*" required> <br/><br/>
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
        <input type="submit" value="Save" name="submit">
        <br/><br/>
    </form>
    <br/><br/>

    <footer id="footer">
        <div id="fbanner">
            <h2>#FOODOVEN</h2>
        </div>
        <div id="social">
            <h5>Follow us on :</h5>
        </div>
        <div id="icon">
            <div id="fb"><a href="https://www.facebook.com/" target="_blank"><img src="../../Images/facebook.png" class="img1"></a></div>
            <div id="tweet"><a href="https://twitter.com/" target="_blank"><img src="../../Images/twitter.png" class="img2"></a></div>
            <div id="insta"><a href="https://www.instagram.com/" target="_blank"><img src="../../Images/instragram.png" class="img3"></a></div>
        </div>
        <div id="links">
            <h5 id="linkh5"><u>Quick Links</u></h5>
            <ul>
                <li><a href="../../html/aboutus.html">About Us</a></li>
                <li><a href="#">Contact Us</a></li>
                <li><a href="#">Help</a></li>
            </ul>
        </div>
        <div id="copy">
            <p>Copyright &copy; 2022 FoodOven.<br>All Rights Reserved.</p>
        </div>
    </footer>

</body>
</html>

<?php
session_start([
    'cookie_lifetime' => 86400,  
    'cookie_secure' => false,     
    'cookie_httponly' => true,   
    'cookie_samesite' => 'Strict' 
]);
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../../html/UserProfileHTML/Login.html');
    exit;
}

// Generate CSRF token if it doesn't exist
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

require "config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token validation failed");
    }

    // Retrieve form data
    $id = $_POST["Oid"];
    $Rel = $_POST["related"];
    $Phone = $_POST["mobile"];
    $Mail = $_POST["email"];
    $Data = $_POST["address"];
    $Date = $_POST["day"];

    $fName = $_FILES['img']['name'];
    $fTName = $_FILES['img']['tmp_name'];
    $fSize = $_FILES['img']['size'];
    $fError = $_FILES['img']['error'];
    $fType = $_FILES['img']['type'];

    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $maxFileSize = 2 * 1024 * 1024;  // 2 MB max file size

    if (in_array($fType, $allowedTypes) && $fSize <= $maxFileSize) {
        $target_dir = "../Images/";
        $target_file = $target_dir . basename($fName);

        if (move_uploaded_file($fTName, $target_file)) {
            $stmt = $con->prepare("INSERT INTO addedit (Adid, rel, number, mail, deta, dates, link) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("issssss", $id, $Rel, $Phone, $Mail, $Data, $Date, $target_file);

            if ($stmt->execute()) {
                echo "Success! Advertisement added.";
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Error uploading the image.";
        }
    } else {
        echo "Invalid file type or size exceeded.";
    }
}
?>

