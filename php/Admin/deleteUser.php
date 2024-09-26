<?php
require "config.php";

// Ensure errors are logged
ini_set('log_errors', 'On');
ini_set('error_log', '../../error.log');

// Retrieve ID
$ID = $_GET['id'] ?? null;

if ($ID) {
    try {
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
    } catch (mysqli_sql_exception $e) {
        error_log("SQL Exception: " . $e->getMessage(), 3, "../../error.log");
        echo "An error occurred while processing your request.";
    }
} else {
    echo "No ID provided.";
}

$con->close();
?>
