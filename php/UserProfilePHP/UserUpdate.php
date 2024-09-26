<?php
session_start([
    'cookie_lifetime' => 86400,  
    'cookie_secure' => false,     
    'cookie_httponly' => true,   
    'cookie_samesite' => 'Strict' 
]);

if (!isset($_SESSION['loggedin'])) {
    header('Location: login.html');
    exit;
}

require('config.php');

$profile = $_SESSION['name'];

$stmt = $con->prepare("SELECT * FROM user WHERE Email = ?");
$stmt->bind_param('s', $profile);
$stmt->execute();
$stmt->bind_result($idX, $fname, $lname, $fullNameX, $ad, $mobile, $emailxx, $pw, $image, $lastColumnX);
$stmt->fetch();
$stmt->close();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link rel="stylesheet" href="../../css/UserProfileCSS/header.css">
    <link rel="stylesheet" href="../../css/UserProfileCSS/userUpdate.css">
</head>
<body>
<nav>
    <ul>
        <li><a href="../../html/home/home.html">Home</a></li>
        <li><a href="../userphp/my.php">My Recipes</a></li>
        <li><a href="../../html/aboutus.html">About Us</a></li>
        <li><a href="../../php/Admin/contact.php">Contact Us</a></li>
        <li><a href="../../html/userhtml/new 1.html">Help</a></li>
    </ul>
    <div id="banner">
        <img src="../../images/logo.png" width="60px" class="banner-img">
    </div>
    <div id="logbtn">
        <a href="../../php/UserProfilePHP/logout.php"><button class="logL">Logout</button></a>
    </div>
    <div id="regbtn">
        <a href="#"><h1 class="logN"><?= htmlspecialchars($_SESSION['name']) ?></h1></a>
    </div>
</nav>

<div class="content">
    <p id="up"><b>Your account details can be updated below<br><?= htmlspecialchars($_SESSION['name']) ?></b></p>
    <div>
        <form action="TheFinalUpdate.php" method="POST" enctype="multipart/form-data">
            <div class="tag">
                <p>Profile<br><img id="profile" src="<?= htmlspecialchars($image) ?>" alt="Profile Picture"></p>
                <p>Profile Picture<br><input type="file" name="file" value="<?= htmlspecialchars($image) ?>"></p>
            </div>

            <p>First Name <input type="text" name="fname" value="<?= htmlspecialchars($fname) ?>">&nbsp;&nbsp;&nbsp;
            Last Name <input type="text" name="lname" value="<?= htmlspecialchars($lname) ?>"></p>

            <p>Address<br><input type="text" size="50px" class="address-input" name="address" value="<?= htmlspecialchars($ad) ?>"></p>

            <p>Mobile<br><input type="tel" name="tel" value="<?= htmlspecialchars($mobile) ?>"></p>

            <p>Email<br><input type="email" name="email" value="<?= htmlspecialchars($_SESSION['name']) ?>"></p>

            <p>Password<br><input type="password" name="password" value=""></p>
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
            <input type="submit" class="update-input" name="update" title="Update your Details" value="Update">
        </form>
    </div>
</div>

<footer id="footer">
    <div id="fbanner">
        <h2>#FOODOVEN</h2>
    </div>
    <div id="social">
        <h5>Follow us on :</h5>
    </div>
    <div id="icon">
        <div id="fb"><a href="https://www.facebook.com/" target="_blank"><img src="../../images/UserProfileIMAGES/facebook.png" class="img1" alt="Facebook"></a></div>
        <div id="tweet"><a href="https://twitter.com/" target="_blank"><img src="../../images/UserProfileIMAGES/twitter.png" class="img2" alt="Twitter"></a></div>
        <div id="insta"><a href="https://www.instagram.com/" target="_blank"><img src="../../images/UserProfileIMAGES/instragram.png" class="img3" alt="Instagram"></a></div>
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
</html>
