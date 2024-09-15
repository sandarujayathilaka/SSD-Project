<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    exit;
}

if (isset($_GET['message'])) {
    echo '<script type="text/javascript">alert("Data Successfully Updated")</script>';
}

$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'iwt';
$con = new mysqli($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

if ($con->connect_error) {
    exit('Failed to connect to MySQL: ' . $con->connect_error);
}

$profile = $_SESSION['name'];

$stmt = $con->prepare("SELECT id, Fname, Lname, Address, Tel, Email, Password, Image FROM user WHERE Email = ?");
$stmt->bind_param('s', $profile);
$stmt->execute();
$stmt->bind_result($id, $fname, $lname, $ad, $mobile, $emailxx, $pw, $image);
$stmt->fetch();
$stmt->close();
$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Profile Page</title>
    <link rel="stylesheet" href="../../css/UserProfileCSS/header.css">
    <style>
        th, td {
            color: rgb(255, 255, 255);
            width: 50%;
            background-color: rgb(15, 165, 55);
            line-height: 50px;
            font-weight: bolder;
            font-family: 'Gill Sans', sans-serif;
            padding: 12px 5px;
        }

        tr {
            line-height: 60px;
        }

        table {
            margin: 5% auto;
            width: 80%;
            text-align: center;
        }

        form {
            border: 3px solid #f1f1f1;
            margin: 2% auto;
            width: 80%;
            background-image: url('https://www.wallpapertip.com/wmimgs/3-36163_dark-blur.jpg');
            color: white;
            border-radius: 14px;
            box-shadow: 0 0 8px #669999;
            padding: 20px;
        }

        .tag {
            text-align: center;
            margin-top: 5%;
        }

        #profile {
            border-radius: 100%;
            width: 250px;
            height: 250px;
            box-shadow: 0 0 8px #669999;
        }

        h4 {
            text-align: center;
            margin-top: 2%;
        }

        footer {
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body class="loggedin">
    <nav style="margin-bottom: 2%;">
        <ul>
            <li><a href="../../html/home/home.html">Home</a></li>
            <li><a href="../../php/userphp/my.php">My Recipes</a></li>
            <li><a href="../../php/appoinment/userapp.php">Appointment</a></li>
            <li><a href="../../php/UserProfilePHP/profile.php">My Profile</a></li>
            <li><a href="../Admin/contact.php">Contact Us</a></li>
        </ul>
        <div id="banner">
            <h1>#FOODOVEN</h1>
        </div>
        <div id="logbtn">
            <a href="../../html/UserProfileHTML/Login.html"><button class="logL">Logout</button></a>
        </div>
        <div id="regbtn">
            <a href="#"><h2><?=$_SESSION['name']?></h2></a>
        </div>
    </nav>

    <form method="POST" action="UserUpdate.php">
        <div>
            <h4><?=$_SESSION['name']?>, Your account details are below</h4>
            <div class="tag">
                <img id="profile" src="<?=$image?>" alt="Profile Image">
                <h4><?=$_SESSION['name']?></h4>
            </div>
        </div>
        <center>
            <table>
                <tr>
                    <td>Email:</td>
                    <td><?=$_SESSION['name']?></td>
                </tr>
                <tr>
                    <td>First Name:</td>
                    <td><?=$fname?></td>
                </tr>
                <tr>
                    <td>Last Name:</td>
                    <td><?=$lname?></td>
                </tr>
                <tr>
                    <td>Address:</td>
                    <td><?=$ad?></td>
                </tr>
                <tr>
                    <td>Mobile:</td>
                    <td><?=$mobile?></td>
                </tr>
                <tr>
                    <td>Account Password:</td>
                    <td><?=$pw?></td>
                </tr>
            </table>
            <div style="text-align:center; margin-bottom: 10%;">
                <a href="UserUpdate.php"><input type="button" title="Click here to Edit" style="padding: 24px 12px; width: 20%;" class="Edit" value="Edit"></a>
            </div>
        </center>
    </form>

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
            <h4 id="linkh5"><u>Quick Links</u></h4>
            <ul>
                <li><a href="../../html/aboutus.html">About Us</a></li>
                <li><a href="../Admin/contact.php">Contact Us</a></li>
                <li><a href="../../html/userhtml/new 1.html">Help</a></li>
            </ul>
        </div>
        <div id="copy">
            <p>Copyright &copy; 2022 FoodOven.<br>All Rights Reserved.</p>
        </div>
    </footer>
</body>
</html>
