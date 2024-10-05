<?php
session_start();
require "config.php";

// Check if user is logged in
if (!isset($_SESSION['name'])) {
    header("Location: login.php"); // Redirect to login if not authenticated
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $recipeId = intval($_GET['id']);
    $username = $_SESSION['name']; // Get the logged-in user's username

    // Prepare a query to check if the recipe belongs to the logged-in user
    $stmt = $con->prepare("SELECT user_name FROM add_recipe WHERE Recipe_ID = ?");
    $stmt->bind_param("i", $recipeId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $recipe = $result->fetch_assoc();

        // Check if the logged-in user is the owner of the recipe
        if ($recipe['user_name'] === $username) {
            // Owner matches, proceed to delete
            $stmt = $con->prepare("DELETE FROM add_recipe WHERE Recipe_ID = ?");
            $stmt->bind_param("i", $recipeId);

            if ($stmt->execute()) {
                // Redirect back to recipes page
                // header("Location: my.php");
                echo '<script type="text/javascript" nonce="random123">alert("Recipe deleted successfully!"); window.location = "my.php";</script>';
                exit();
            } else {
                // If there's an error deleting the recipe
                echo '<script type="text/javascript" nonce="random123">alert("Error deleting recipe: ' . addslashes($con->error) . '"); window.location = "my.php";</script>';
                exit();
            }
        } else {
            // If user is not authorized to delete
            echo '<script type="text/javascript" nonce="random123">alert("You are not authorized to delete this recipe."); window.location = "my.php";</script>';
            exit();
        }
    } else {
        // If recipe is not found
        echo '<script type="text/javascript" nonce="random123">alert("Recipe not found."); window.location = "my.php";</script>';
        exit();
    }

    $stmt->close();
}
?>
