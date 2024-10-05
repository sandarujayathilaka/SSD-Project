
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FOODOVEN</title>
    <link rel="stylesheet" href="../../css/user/my_recipe.css">
	
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

     <div id="banner">
         <img src ="../../images/Untitled-4.png" width ="63px" length="63px">
     </div>
   
     <div id="logbtn">
          <?php
              // Check if the user is logged in
              if (isset($_SESSION['name']) && !empty($_SESSION['name'])) {
                  // Show log out button if the user is logged in
                  echo '<a href="../UserProfilePHP/logout.php"><button class="log">Log out</button></a>';
              } else {
                  // Show log in button if the user is not logged in
                  echo '<a href="../UserProfilePHP/login.php"><button class="log">Log in</button></a>';
              }
          ?>
      </div>

      <div id="regbtn">
          <?php
              // Show register button only if the user is not logged in
              if (!isset($_SESSION['name']) || empty($_SESSION['name'])) {
                  echo '<a href="../../html/UserProfileHTML/Register.html"><button class="log">Register</button></a>';
              }
          ?>
      </div>
</nav>



<br></br>

<div id="btn-container">
<a href="../../php/userphp/addrec.php"><button class = "con">Add recipes></button></a>
<a href="../../php/userphp/all.php"><button class = "con">All recipes</button></a>
</div>

<div class="titl"><center>
<h1>-My Recipes-</h1>
</center></div></br>

<center>
<?php
session_start([
    'cookie_lifetime' => 86400,  
    'cookie_secure' => false,     
    'cookie_httponly' => true,   
    'cookie_samesite' => 'Strict' 
]);

 require "config.php";
 
    $stmt = $con->prepare("SELECT * FROM add_recipe WHERE user_name = ?");
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($con->error)); // Error handling
    }
    $stmt->bind_param("s", $_SESSION['name']);
    $stmt->execute();
    $take = $stmt->get_result();
 
 
	 echo ("<table id='table1' cellpadding='1' border='5'>");
	 echo ("<tr>");
	  echo ("<th>"."Recipe ID"."</th>");
	 echo ("<th>"."Category"."</th>");
	 echo ("<th>"."Email"."</th>");
	 echo ("<th>"."Title"."</th>");
	 echo ("<th>"."Time"."</th>");
	 echo ("<th>"."Ingredients"."</th>");
	  echo ("<th>"."Description"."</th>");
      echo ("<th>"."Image"."</th>");
	  echo ("<th>"."Action"."</th>");
	 if($take->num_rows>0){
	 while($row=$take->fetch_assoc()){
		 
		 
		 echo("<tr>");
		 echo("<td>".$row['Recipe_ID']."</td>");
		 echo("<td>".$row['category']."</td>");
		 echo("<td>".$row['user_name']."</td>");
		 echo("<td>".$row['Title']."</td>");
		 echo("<td>".$row['Time']."</td>");
		 echo("<td>".$row['Ingredients']."</td>");
		 echo("<td>".$row['Description']."</td>");
         echo "<td><img src='" . htmlspecialchars($row['Images']) . "' alt='Recipe Image' height='100px'></td>"; // Adjust width/height as needed


		 echo "<td>
                <a id='editlink' href='./editrecipe.php?id=".$row['Recipe_ID']."'>Edit</a>
               
                <a id='deletelink' class='delete-recipe' href='./recipedelete.php?id=".$row['Recipe_ID']."'>Delete</a> 
            </td>";
	 }
	 echo "</table>";
 }  
 ?>
</center> 
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
             <li><a href="../Admin/contact.php">Contact Us</a></li>
             <li><a href="../../html/userhtml/new 1.html">Help</a></li>
         </ul>
     </div>
     <div id="copy">
         <p>Copyright &copy; 2022 FoodOven.<br>All Rights Reserved.</p>
     </div>

    </footer-->
 
</body>

<script nonce="random123">
    document.addEventListener('DOMContentLoaded', function () {
        // Select all delete links
        const deleteLinks = document.querySelectorAll('.delete-recipe');
        
        // Loop through all delete links and attach event listeners
        deleteLinks.forEach(function (link) {
            link.addEventListener('click', function (event) {
                // Prevent default navigation if confirmation is canceled
                if (!confirm('Are you sure you want to delete this recipe?')) {
                    event.preventDefault(); // Stop the navigation
                }
            });
        });
    });
</script>


</html>

