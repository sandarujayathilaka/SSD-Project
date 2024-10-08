<?php
session_start([
    'cookie_lifetime' => 86400,  
    'cookie_secure' => false,     
    'cookie_httponly' => true,   
    'cookie_samesite' => 'Strict' 
]);

// Generate CSRF token if not set
if (!isset($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Check CSRF token safely
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
  die('Invalid CSRF token'); // Stop the process if the token is invalid
}



if (isset($_GET['message'])) {
    echo '<script type="text/javascript">alert("Data Successfully Updated")</script>';
}


$_SESSION["mail"] = $_POST['myMail'];
$_SESSION["tp"] = $_POST['myMail'];

require('config.php');

$myMail = $_POST['myMail'];
$myMobile = $_POST['myMobile'];

if (isset($_POST['submit'])) {

   
    $stmt = $con->prepare("SELECT Email, Tel FROM user WHERE Email = ?");
    $stmt->bind_param("s", $myMail);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $max = $row["Email"];
            $maz = $row["Tel"];
        }

        if ($myMail == $max || $myMobile == $maz) {
            $_SESSION["mail"] = $_POST['myMail'];
            $_SESSION["tp"] = $_POST['myMail'];
        } else {
            header('Location: reset.php?message=updated');
        }

    } else {
        echo "<h1>No results</h1>";
    }

    $stmt->close();
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
  <link rel="stylesheet" href="../../css/UserProfileCSS/reset2.css">
  <!-- <style>
    body {
      background-image: url(../../images/UserProfileIMAGES/myUserBG.jpg);
    }
    input {
      color: rgb(204, 0, 204);
      border-radius: 14px;
      background-color: rgb(246, 246, 246);
      border: none;
      outline: none;
      box-shadow: 24px aqua;
      padding: 14px 12px;
      font-size: medium;
      font-weight: 40px;
      display: block;
      margin-left: auto;
      margin-right: auto;
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
  </style> -->
</head>
<body>
  <nav>
    <ul>
      <li><a href="../../html/home/index.html">Home</a></li>
      <li><a href="../../html/aboutus.html">About Us</a></li>
      <li><a href="../../html/userhtml/new 1.html">Help</a></li>
    </ul>
    <div id="banner">
      <img src="../../images/logo.png" width="60px" class="logo">
    </div>
  </nav>

  <h4 class="account-available">Account Available <br><?=$myMail?></h4>
  <form method='POST' action='LastUpdate.php'>
    <div class="yo">
      <div class="dept">
        <label id="label">Enter New Password </label>
        <input type='password' id='password' name='password' required size='25px'>
      </div>
      <div class="dept">
        <label id="label">Confirm Password </label>
        <input type='password' id='confirm_password' name='password' required size='25px'>
      </div>
      <input type='Submit' name='summon' value='Update Password' size='26px'  class="update-button">
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

  <footer id="footer">
    <div id="fbanner">
      <h2>#FOODOVEN</h2>
    </div>
    <div id="social">
      <h5>Follow us on :</h5>
    </div>
    <div id="icon">
      <div id="fb"><a href="https://www.facebook.com/" target="_blank"><img src="../../images/UserProfileIMAGES/facebook.png" class="img1"></a></div>
      <div id="tweet"><a href="https://twitter.com/" target="_blank"><img src="../../images/UserProfileIMAGES/twitter.png" class="img2"></a></div>
      <div id="insta"><a href="https://www.instagram.com/" target="_blank"><img src="../../images/UserProfileIMAGES/instragram.png" class="img3"></a></div>
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
</body>
</html>
