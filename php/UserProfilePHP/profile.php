<?php

if(isset($_GET['message'])){

	echo'<script type="text/javascript" nonce="random123">alert("Data Succesfully Updated")</script>';
}

session_start([
    'cookie_lifetime' => 86400,  
    'cookie_secure' => true,     
    'cookie_httponly' => true,   
    'cookie_samesite' => 'Strict' 
]);

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if (!isset($_SESSION['loggedin'])) {
		exit;
}

require('config.php');

$profile=$_SESSION['name'];
		
$stmt = $con->prepare("SELECT * FROM user WHERE Email= '$profile'");

$stmt->execute();
$stmt->bind_result($id, $fname,$lname,$fullNameX,$ad,$mobile,$emailxx,$pw,$image,$lastColumnX);
$stmt->fetch();
$stmt->close();
$profile=$_SESSION['name'];

?>


<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Profile Page</title>
    	<link rel="stylesheet" href="../../css/UserProfileCSS/header.css">
		<link rel="stylesheet" href="../../css/UserProfileCSS/profile.css" />

	</head>
	<body class="loggedin" >

	<nav class="navbar">

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
	   <a href="#"><H class="logN"><?=$_SESSION['name']?></H></a>
	</div>

</nav>

<form method="POST" action="UserUpdate.php">
<input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">	
<div>
	<h4 > &nbsp;<?=$_SESSION['name']?>&nbsp;Your account details are below</h5></marquee>
	<div class="tag">
		<img  id="profile"src="<?=$image?>">
		<br>
		<h4 class="profile-name" ><?=$_SESSION['name']?></h4>

	
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
						<td>Mobile :</td>
						<td><?=$mobile?></td>
					</tr>
					
					<tr>
						<td>Account Password:</td>
						<td><?=$pw?></td>
					</tr>
					
				</table>
				<div class="edit-button-container">
					<a href="UserUpdate.php"><input type="button" title="Click here to Edit" class="Edit edit-button" value="Edit"></a>
				</div>
				</center>
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
			 
			 <div id="fb"><a href="https://www.facebook.com/" target="_blank"><img src="../../images/UserProfileIMAGES/facebook.png" class="img1"></a></div>
			 <div id="tweet"><a href="https://twitter.com/" target="_blank"><img src="../../images/UserProfileIMAGES/twitter.png" class="img2"></a></div>
			 <div id="insta"><a href="https://www.instagram.com/" target="_blank"><img src="../../images/UserProfileIMAGES/instragram.png" class="img3"></a></div>
			
		 </div>
	
		 <div id="links">
			 <h4 id="linkh5"><u>Quick Links</u></h5>
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