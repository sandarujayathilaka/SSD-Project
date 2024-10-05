<?php
session_start([
    'cookie_lifetime' => 86400,  
    'cookie_secure' => false,     
    'cookie_httponly' => true,   
    'cookie_samesite' => 'Strict' 
]);

// Generate a new CSRF token if one doesn't exist or refresh it.
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
    <title>FOODOVEN</title>
    <link rel="stylesheet" href="../../css/user/add.css">
	
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
        <img src ="../../images/Untitled-4.png" width ="63px" length="63px"
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
   </div>


   
</nav>
<br></br>

<a href="../../php/userphp/my.php"> <button id="ads">-back</button></a>

<br></br>
<center>
<h1 class="my">My recipe - Add a recipe</h1><br></br>
</center>
<div id="F">
<form action="../../php/userphp/addrecipe.php" method="POST"  enctype="multipart/form-data">
<input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

<?php



require "config.php";



$sql= "SELECT Category FROM recipe";

$result=$con->query($sql);



echo ("<label class='formele'>"."-Category :"."</label>"."<br>"."<br>");

echo("<select name='select1' class='t' required>");
echo("<option value='' disabled selected>Select a category</option>"); // Placeholder option


if($result->num_rows>0){



   while($row=$result->fetch_assoc()){


   echo("<option value='" . htmlspecialchars($row['Category']) . "'>" . htmlspecialchars($row['Category']) . "</option>");


   }




}



echo("</select>");




?>
<br></br>

 -Title :</br></br>
   <input class="e" type="text" placeholder="Recipe title" name="Title" required title="Please enter a title for your recipe." maxlength="100"></br></br>
 -Cooking Time :</br></br>
   <input class="w" type="text" placeholder="Time Duration" required name="Time" title="Please specify the cooking time." pattern="^\d+\s*(minutes|hours)?$"></br></br>
 -Main ingredients :</br></br>
   <textarea class="ing" rows="10" cols="35" name="Ingredients" required title="Please list the main ingredients."></textarea></br></br>
 -Description :</br></br>
   <textarea class="des" rows="8" cols="50" name="Description" required title="Please provide a description of the recipe."></textarea></br></br>
 -Upload an image: <input class="up"type="file" name="Images" accept="image/*" required title="Please upload an image of the recipe." ><br></br>
  <input class ="y" type="Submit" name="submit"> <input class="y" type="Reset"> <br></br>

</form>
</div>





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
             <li><a href="#">About Us</a></li>
             <li><a href="../Admin/contact.php">Contact Us</a></li>
             <li><a href="../../html/userhtml/new 1.html">Help</a></li>
         </ul>
     </div>
     <div id="copy">
         <p>Copyright &copy; 2022 FoodOven.<br>All Rights Reserved.</p>
     </div>

    </footer-->



</body>
</html>