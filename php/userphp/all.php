<?php
session_start([
    'cookie_lifetime' => 86400,  
    'cookie_secure' => false,     
    'cookie_httponly' => true,   
    'cookie_samesite' => 'Strict' 
]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FOODOVEN</title>
    <link rel="stylesheet" href="../../css/user/all_recipe.css">

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
            <img src ="../../images/Untitled-4.png" width="63px" length="63px">
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

    <br>

    <a href="../../php/userphp/addrec.php"><button class="con">Add recipes</button></a>
    <a href="../../php/userphp/my.php"><button class="con">My recipes</button></a>

    <div class="titl">
        <center>
            <h1>- Recipes -</h1>
        </center>
    </div>
    <br>

    <center>
    <div class="card-container">
    <?php
        require "config.php";

        $sql = "SELECT * FROM add_recipe";
        $take = $con->query($sql);

        if ($take->num_rows > 0) {
            while ($row = $take->fetch_assoc()) {
                echo "
                <div class='recipe-card'>
                    <img src='" . htmlspecialchars($row['Images']) . "' alt='Recipe Image'>
                    <div class='recipe-content'>
                        <h2>" . htmlspecialchars($row['Title']) . "</h2>
                        <p><strong>By:</strong> " . htmlspecialchars($row['user_name']) . "</p>
                        <p><strong>Category:</strong> " . htmlspecialchars($row['category']) . "</p>
                        <p><strong>Time:</strong> " . htmlspecialchars($row['Time']) . "</p>
                        <p><strong>Ingredients:</strong> " . htmlspecialchars($row['Ingredients']) . "</p>
                        <p><strong>Description:</strong> " . htmlspecialchars($row['Description']) . "</p>
                    </div>
                </div>";
            }
        } else {
            echo "<p>No recipes found.</p>";
        }
    ?>
    </div>
    </center>

    <footer id="footer">
        <div id="fbanner">
            <h2>#FOODOVEN</h2>
        </div>
        <div id="social">
            <h5>Follow us on:</h5>
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
    </footer>

</body>


</html>
