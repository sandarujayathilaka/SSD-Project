<?php
        session_start([
			'cookie_lifetime' => 86400,  
			'cookie_secure' => true,     
			'cookie_httponly' => true,   
			'cookie_samesite' => 'Strict' 
		]);

        // require "../ErrorHandling/ErrorHandler.php";
        require "config.php";

        // Trigger a test error to check if custom error handler works
        trigger_error("Test error logging", E_USER_NOTICE);
    
        // Generate CSRF token if not already set
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FOODOVEN</title>
    <link rel="stylesheet" href="../../css/Admin/contact.css">
    <link rel="stylesheet" href="../../css/Admin/index.css">
</head>
<body>
    <nav>
    <ul>
        <li><a href="../../html/home/home.html">Home</a></li>
        <li><a href="../../php/userphp/my.php">My Recipes</a></li>
        <li><a href="../../php/appoinment/userapp.php">Appointment</a></li>
        <li><a href="../../php/UserProfilePHP/profile.php">My Profile</a></li>
        <li><a href="../Admin/contact.php">Contact Us</a></li>
    </ul>
    <img id="logo" src="../../images/logo.png">
    </div>
    </nav>

    <div id="label1">
        <h1>Contact Us</h1>
        <p>If you have any issue regarding this system, please let us know via the email address, phone numbers, or queries. We look forward to helping you.</p>
    </div>
    <div id="img">
        <img src="../../images/contact-us.png" width="650px" alt="vector image">
    </div>

    <div id="details">
        <h3>#FOODOVEN</h3><br>
        <p>E-mail: contactus@foodoven.com<br><br>Tel: +9411-1234560</p>
    </div>

    <form id="contact" method="POST" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
     <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
        <div id="formdiv">

            <label class="formele">Your Name :</label><br><br>
            <input type="text" class="formele" id="fname" name="fname" required><br><br>

            <label class="formele">Your Email :</label><br><br>
            <input type="mail" class="formele" id="email" name="email" required><br><br>

            <label class="formele">Phone :</label><br><br>
            <input type="tel" class="formele" id="tp" name="tp" required><br><br>

            <label class="formele">Subject :</label><br><br>
            <input type="text" class="formele" id="sub" name="sub" required><br><br>

            <label class="formele">Gender :</label><br><br>
            <label class="formele">Male: <input type="radio" id="male" name="gender" value="Male" required></label>
            <label class="formele">Female: <input type="radio" id="female" name="gender" value="Female"></label><br><br>
 


            <input type="submit" id="submit" name="submit" value="Submit">
        </div>
    </form>

    <footer id="footer">
        <div id="fbanner">
            <h2>#FOODOVEN</h2>
        </div>
        <div id="social">
            <h5>Follow us on :</h5>
        </div>
        <div id="icon">
            <div id="fb"><a href="https://www.facebook.com/" target="_blank"><img src="../../images/facebook.png" class="img1"></a></div>
            <div id="tweet"><a href="https://twitter.com/" target="_blank"><img src="../../images/twitter.png" class="img2"></a></div>
            <div id="insta"><a href="https://www.instagram.com/" target="_blank"><img src="../../images/instragram.png" class="img3"></a></div>
        </div>

        <div id="links">
            <h5 id="linkh5"><u>Quick Links</u></h5>
            <ul>
                <li><a href="../../html/aboutus.html">About Us</a></li>
                <li><a href="../../php/Admin/contact.php">Contact Us</a></li>
                <li><a href="../../html/userhtml/new 1.html">Help</a></li>
            </ul>
        </div>
        <div id="copy">
            <p>Copyright &copy; 2022 FoodOven.<br>All Rights Reserved.</p>
        </div>
    </footer>

</body>

<?php

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (isset($_POST["fname"]) && isset($_POST["email"]) && isset($_POST["tp"]) && isset($_POST["sub"])) {

            try{

                // Validate the CSRF token
                if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
                    die("Invalid CSRF token");
                }
    
               
                $name = htmlspecialchars($_POST["fname"]);
                $email = htmlspecialchars($_POST["email"]);
                $phone = htmlspecialchars($_POST["tp"]);
                $subject = htmlspecialchars($_POST["sub"]);
                $gender = isset($_POST["gender"]) ? htmlspecialchars($_POST["gender"]) : '';
        
                if (empty($name) || empty($email) || empty($phone) || empty($subject) || empty($gender)) {
                    throw new Exception("All fields are required!");
                }
    
                $stmt = $con->prepare("INSERT INTO contacts (Name, Email, Phone, Subject, Gender) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssss", $name, $email, $phone, $subject, $gender);
    
               
                if (!$stmt->execute()) {
                    throw new Exception("Error: " . $stmt->error);
                }
    
                // Success message (safe to display to users)
                echo '<div id="mesbox"><h3 id="message">Successfully Submitted</h3></div>';
                $stmt->close();
    
            } catch (Exception $e) {
                error_log($e->getMessage());
            
                echo '<div id="mesbox"><h3 id="message">An error occurred. Please try again later.</h3></div>';

            }

        } else {
            echo "Required fields are missing.";
        }

    }

$con->close();

?>



</html>
