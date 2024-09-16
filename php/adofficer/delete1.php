<?php
require "config.php";

$del = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($del > 0) {

    $stmt = $con->prepare("DELETE FROM addedit WHERE Adid = ?");
    $stmt->bind_param("i", $del); 

    if ($stmt->execute()) {
        echo 'Deleted Successfully';
    } else {
        echo "Error: " . $stmt->error;
    }

    header("Location: main.php");
    exit();
} else {
    echo "Invalid ID!";
}
?>
