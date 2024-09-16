<?php
require "config.php";

$ID = $_GET['id'] ?? null;

if ($ID) {
 
    $stmt = $con->prepare("DELETE FROM adminacc WHERE ID = ?");
    
    if ($stmt) {
        
        $stmt->bind_param("i", $ID);
        
        if ($stmt->execute()) {
           
            header("Location: ./adminAcc.php");
            exit();
        } else {
          
            echo "Error: " . $stmt->error;
        }
        
       
        $stmt->close();
    } else {
       
        echo "Error preparing statement: " . $con->error;
    }
}


$con->close();
?>
