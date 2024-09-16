<?php
require "config.php";

$ID = $_GET['id'] ?? null;

if ($ID) {
  
    $stmt = $con->prepare("DELETE FROM nutriacc WHERE ID = ?");
    
    if ($stmt) {
        
        $stmt->bind_param("i", $ID);
        
        if ($stmt->execute()) {
         
            header("Location: ./admNutri.php");
            exit();
        } else {
         
            echo "Error: " . $stmt->error;
        }
    
        $stmt->close();
    } else {
     
        echo "Error preparing statement: " . $con->error;
    }
} else {
    echo "No ID provided.";
}


$con->close();
?>
