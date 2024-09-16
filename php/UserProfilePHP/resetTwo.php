<?php
session_start();

// CSRF Protection - Generate token if it doesn't exist
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Database connection using MySQLi
$con = new mysqli("localhost", "root", "", "iwt");
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Check if form is submitted for password reset
if (isset($_POST['summon'])) {

    // Check CSRF token validation
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Invalid CSRF token.");
    }

    // Sanitize inputs
    $myMail = filter_var($_POST['myMail'], FILTER_SANITIZE_EMAIL);
    $myMobile = filter_var($_POST['myMobile'], FILTER_SANITIZE_STRING);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Check if the passwords match
    if ($password !== $confirmPassword) {
        echo '<script type="text/javascript">alert("Passwords do not match.");</script>';
    } else {
        // Hash the password before saving it to the database
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Prepared statement to select user data
        $stmt = $con->prepare("SELECT Email, Tel FROM user WHERE Email = ? AND Tel = ?");
        $stmt->bind_param("ss", $myMail, $myMobile);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // If user is found, update their password
            $updateStmt = $con->prepare("UPDATE user SET Password = ? WHERE Email = ?");
            $updateStmt->bind_param("ss", $hashedPassword, $myMail);
            if ($updateStmt->execute()) {
                echo '<script type="text/javascript">alert("Password successfully updated.");</script>';
            } else {
                echo '<script type="text/javascript">alert("Error updating password.");</script>';
            }
            $updateStmt->close();
        } else {
            echo '<script type="text/javascript">alert("No account found with the provided email and mobile.");</script>';
        }

        $stmt->close();
    }
}
$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password</title>
  <link rel="stylesheet" href="../../css/UserProfileCSS/header.css">
  <style>
    body {
      background-image: url(../../images/UserProfileIMAGES/myUserBG.jpg);
    }
    input {
      color: rgb(204, 0, 204);
      border-radius: 14px;
      background-color: rgb(246, 246, 246);
      border: none;
      outline: none;
      padding: 14px 12px;
      font-size: medium;
      margin-left: auto;
      margin-right: auto;
      display: block;
    }
    .yo {
      background-color: rgb(47, 234, 125);
      display: block;
      margin-left: auto;
      margin-right: auto;
      width: 445px;
      height: 315px;
      border-radius: 14px;
      margin-bottom: 20%;
      margin-top: 8%;
    }
    .dept {
      margin-top: 12px;
      display: block;
      margin-left: 85px;
      margin-right: auto;
      width: 40%;
      height: 30px;
      border-radius: 4px;
      padding: 12px 4px;
      margin-bottom: 10%;
    }
    h4 {
      color: rgb(255, 255, 255);
      padding: 14px 10px;
      font-size: 20px;
    }
  </style>
</head>
<body>
  <nav>
    <ul>
      <li><a href="../../html/home/index.html">Home</a></li>
      <li><a href="../../html/aboutus.html">About Us</a></li>
      <li><a href="../../html/userhtml/new 1.html">Help</a></li>
    </ul>
    <div id="banner">
      <img src="../../images/logo.png" width="60px" style="margin-top: -14px;">
    </div>
  </nav>

  <h4 style="text-align: center; margin-top: 1%;">Reset Password for <br><?= htmlspecialchars($_SESSION['mail']) ?></h4>
  
  <form method="POST" action="LastUpdate.php">
    <div class="yo">
      <!-- Email field -->
      <div class="dept">
        <label>Enter Email</label>
        <input type="email" name="myMail" value="<?= htmlspecialchars($_SESSION['mail']) ?>" required>
      </div>

      <!-- Mobile field -->
      <div class="dept">
        <label>Enter Mobile</label>
        <input type="text" name="myMobile" required>
      </div>

      <!-- Password field -->
      <div class="dept">
        <label>Enter New Password</label>
        <input type="password" id="password" name="password" required>
      </div>

      <!-- Confirm Password field -->
      <div class="dept">
        <label>Confirm Password</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
      </div>

      <!-- CSRF Token Hidden Input -->
      <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

      <input type="submit" name="summon" value="Update Password" style="margin-top: 20%">
    </div>
  </form>

  <script type="text/javascript">
    var password = document.getElementById("password"),
        confirm_password = document.getElementById("confirm_password");

    function validatePassword() {
      if (password.value != confirm_password.value) {
        confirm_password.setCustomValidity("Passwords Don't Match");
      } else {
        confirm_password.setCustomValidity('');
      }
    }

    password.onchange = validatePassword;
    confirm_password.onkeyup = validatePassword;
  </script>
</body>
<footer id="footer">
  <div id="fbanner">
    <h2>#FOODOVEN</h2>
  </div>
  <div id="social">
    <h5>Follow us on :</h5>
  </div>
  <div id="icon">
    <div id="fb">
      <a href="https://www.facebook.com/" target="_blank">
        <img src="../../images/UserProfileIMAGES/facebook.png" class="img1">
      </a>
    </div>
    <div id="tweet">
      <a href="https://twitter.com/" target="_blank">
        <img src="../../images/UserProfileIMAGES/twitter.png" class="img2">
      </a>
    </div>
    <div id="insta">
      <a href="https://www.instagram.com/" target="_blank">
        <img src="../../images/UserProfileIMAGES/instragram.png" class="img3">
      </a>
    </div>
  </div>
  <div id="links">
    <h5 id="linkh5"><u>Quick Links</u></h5>
    <ul>
      <li><a href="../../html/aboutus.html">About Us</a></li>
      <li><a href="../../html/userhtml/new 1.html">Help</a></li>
    </ul>
  </div>
  <div id="copy">
    <p>Copyright &copy; 2022 FoodOven.<br>All Rights Reserved.</p>
  </div>
</footer>
</html>
