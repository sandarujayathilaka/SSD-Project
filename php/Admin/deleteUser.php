<?php
require "config.php";

$ID = $_GET['id'] ?? null;

if ($ID) {
    $stmt = $con->prepare("DELETE FROM `user` WHERE ID = ?");
    
    if ($stmt) {
        $stmt->bind_param("i", $ID);
        
        if ($stmt->execute()) {
            header("Location: ./admUser.php");
            exit();
        } else {
            error_log("Database error: " . $stmt->error, 3, "../../error.log");
            echo "An error occurred while processing your request.";
        }
        
        $stmt->close();
    } else {
        error_log("Statement preparation error: " . $con->error, 3, "../../error.log");
        echo "An error occurred while preparing the request.";
    }
} else {
    echo "No ID provided.";
}

$con->close();
?>
