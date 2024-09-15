<?php
require 'config.php'; 

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userName = htmlspecialchars($_POST['user_name'], ENT_QUOTES, 'UTF-8');
    $title = htmlspecialchars($_POST['Title'], ENT_QUOTES, 'UTF-8');
    $time = htmlspecialchars($_POST['Time'], ENT_QUOTES, 'UTF-8');
    $ingredients = htmlspecialchars($_POST['Ingredients'], ENT_QUOTES, 'UTF-8');
    $description = htmlspecialchars($_POST['Description'], ENT_QUOTES, 'UTF-8');
    $category = htmlspecialchars($_POST['select1'], ENT_QUOTES, 'UTF-8');

    // Handle file upload
    $image = null;
    if (isset($_FILES['Images']) && $_FILES['Images']['error'] == UPLOAD_ERR_OK) {
        $image = $_FILES['Images']['name'];
        $targetDir = "../../images/uploads/"; // Ensure this directory exists and is writable
        $targetFile = $targetDir . basename($image);
        move_uploaded_file($_FILES['Images']['tmp_name'], $targetFile);
    }

    $stmt = $con->prepare("INSERT INTO recipe (user_name, Title, Time, Ingredients, Description, Category, Images) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $userName, $title, $time, $ingredients, $description, $category, $image);

    if ($stmt->execute()) {
        $message = "Recipe added successfully!";
    } else {
        $message = "Error: " . $stmt->error;
    }

    $stmt->close();
    $con->close();
}

// Fetch categories for the dropdown
$sql = "SELECT Category FROM recipe";
$result = $con->query($sql);
$categories = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = htmlspecialchars($row['Category'], ENT_QUOTES, 'UTF-8');
    }
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
            <img src="../../images/Untitled-4.png" alt="Logo" width="63" height="63">
        </div>
        <div id="logbtn">
            <a href="#"><button class="log">Log out</button></a>
        </div>
    </nav>

    <br><br>

    <a href="../../php/userphp/my.php"><button id="ads">-back</button></a>

    <br><br>
    <center>
        <h1 class="my">My Recipe - Add a Recipe</h1><br><br>
    </center>
    <div id="F">
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" enctype="multipart/form-data">
            <?php
            echo "<label class='formele'>-Category :</label><br><br>";
            echo "<select name='select1' class='t'>";
            foreach ($categories as $cat) {
                echo "<option value='$cat'>$cat</option>";
            }
            echo "</select>";
            ?>
            <br><br>
            -User name :<br><br>
            <input class="us" type="text" placeholder="Add your name" name="user_name" required><br><br>
            -Title :<br><br>
            <input class="e" type="text" placeholder="Recipe title" name="Title" required><br><br>
            -Cooking Time :<br><br>
            <input class="w" type="text" placeholder="Time Duration" name="Time" required><br><br>
            -Main ingredients :<br><br>
            <textarea class="ing" rows="10" cols="35" name="Ingredients" required></textarea><br><br>
            -Description :<br><br>
            <textarea class="des" rows="8" cols="50" name="Description" required></textarea><br><br>
            -Upload an image: <input class="up" type="file" name="Images"><br><br>
            <input class="y" type="submit" name="submit"> <input class="y" type="reset"><br><br>
            <?php
            if (isset($message)) {
                echo "<p>$message</p>";
            }
            ?>
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
            <div id="fb"><a href="https://www.facebook.com/" target="_blank"><img src="../../images/facebook.png" class="img1" alt="Facebook"></a></div>
            <div id="tweet"><a href="https://twitter.com/" target="_blank"><img src="../../images/twitter.png" class="img2" alt="Twitter"></a></div>
            <div id="insta"><a href="https://www.instagram.com/" target="_blank"><img src="../../images/instragram.png" class="img3" alt="Instagram"></a></div>
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
    </footer>

</body>
</html>
