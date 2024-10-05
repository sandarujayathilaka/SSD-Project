<?php
session_start();
require "config.php";

// Check if user is logged in
if (!isset($_SESSION['name'])) {
    header("Location: login.php"); // Redirect to login if not authenticated
    exit();
}

// Initialize the variable
$recipeId = null; // Set a default value

// Get the recipe ID from the URL
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $recipeId = intval($_GET['id']);
    $username = $_SESSION['name'];

    // Prepare a query to fetch the recipe details
    $stmt = $con->prepare("SELECT * FROM add_recipe WHERE Recipe_ID = ? AND user_name = ?");
    $stmt->bind_param("is", $recipeId, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $recipe = $result->fetch_assoc();
    } else {
        // If no recipe found or user is not the owner
        echo '<script type="text/javascript" nonce="random123">alert("Recipe not found or you are not authorized to edit this recipe."); window.location = "my.php";</script>';
        exit();
    }

    $stmt->close();
}

// Handle form submission to update the recipe
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("Invalid CSRF token.");
    }

    // Ensure $recipeId is set from the GET parameters
    if (isset($_GET['id'])) {
        $recipeId = intval($_GET['id']);
    }

    // Get updated recipe data from the form
    $title = $_POST['Title'];
    $time = $_POST['Time'];
    $ingredients = $_POST['Ingredients'];
    $description = $_POST['Description'];
    $category = $_POST['select1'];

    // Check if recipeId is valid
    if ($recipeId === null) {
        die("Error: Recipe ID is not defined."); // Exit if recipe ID is not available
    }

    $stmt = $con->prepare("UPDATE add_recipe SET Title = ?, Time = ?, Ingredients = ?, Description = ?, category = ? WHERE Recipe_ID = ?");
    // Ensure $recipeId is an integer and $username is a string
    $stmt->bind_param("sssssi", $title, $time, $ingredients, $description, $category, $recipeId);
    
    if ($stmt->execute()) {
        // Redirect back to recipes page
        // header("Location: my.php");
        echo '<script type="text/javascript" nonce="random123">alert("Recipe updated successfully!"); window.location = "my.php";</script>';
        exit();
    } else {
        // echo "Error updating recipe: " . $con->error;
        echo '<script type="text/javascript" nonce="random123">alert("Error updating recipe: ' . addslashes($con->error) . '"); window.location = "my.php";</script>';
    }

    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Recipe - FOODOVEN</title>
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
            <img src="../../images/Untitled-4.png" width="63px" length="63px">
        </div>
    </nav>

    <br>

    <a href="../../php/userphp/my.php"> <button id="ads">-back</button></a>

    <br>
    <center>
        <h1 class="my">My recipe - Edit Recipe</h1><br>
    </center>
    <div id="F">
        <form action="editrecipe.php?id=<?php echo $recipeId; ?>" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            
            <label class="formele">-Category :</label><br><br>
            <select name="select1" class="t" required>
                <option value="" disabled>Select a category</option>
                <?php
                // Fetch categories for dropdown
                $sql = "SELECT Category FROM recipe";
                $result = $con->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . htmlspecialchars($row['Category']) . "'" . ($recipe['category'] === $row['Category'] ? " selected" : "") . ">" . htmlspecialchars($row['Category']) . "</option>";
                    }
                }
                ?>
            </select>
            <br><br>

            -Title :</br>
            <input class="e" type="text" placeholder="Recipe title" name="Title" value="<?php echo htmlspecialchars($recipe['Title']); ?>" required maxlength="100"><br><br>
            -Cooking Time :</br>
            <input class="w" type="text" placeholder="Time Duration" required name="Time" value="<?php echo htmlspecialchars($recipe['Time']); ?>" pattern="^\d+\s*(minutes|hours)?$"><br><br>
            -Main ingredients :</br>
            <textarea class="ing" rows="10" cols="35" name="Ingredients" required><?php echo htmlspecialchars($recipe['Ingredients']); ?></textarea><br><br>
            -Description :</br>
            <textarea class="des" rows="8" cols="50" name="Description" required><?php echo htmlspecialchars($recipe['Description']); ?></textarea><br><br>
            <input class="y" type="submit" name="submit" value="Update Recipe"> 
            <input class="y" type="reset" value="Reset"> 
            <br><br>
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
