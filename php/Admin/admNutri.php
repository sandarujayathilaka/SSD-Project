<?php
session_start([
    'cookie_lifetime' => 86400,  
    'cookie_secure' => true,     
    'cookie_httponly' => true,   
    'cookie_samesite' => 'Strict' 
]);
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../../html/UserProfileHTML/Login.html');
    exit;
}
?>

<?php

if (isset($_GET['msg'])) {
    echo '<script>alert("Successfully Updated")</script>';
} elseif (isset($_GET['msg1'])) {
    echo '<script>alert("Your Username already exists. Please refill the form with a unique username.");</script>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FOODOVEN</title>
    <link rel="stylesheet" href="../../css/Admin/admin.css">
</head>

<body>

<nav>
    <img id="logo" src="../images/logo1.png">
    <div id="logbtn">
        <p id="note">Administrator: <?= htmlspecialchars($_SESSION['name']) ?></p>
        <a href="logout.php"><button class="log">Logout</button></a>
    </div>
</nav>

<div id="verticalnav">
    <div id="adminbanner">
        <img id="logo" src="../../images/adminlogo.png">
    </div>
    <ul>
        <li class="list"><a href="./dashboard.php"><img id="img1" src="../../images/12.png"> Dashboard</a></li>                             
        <li class="list"><a href="./admUser.php"><img id="img1" src="../../images/user.png"> Users</a></li>
        <li class="list"><a href="./admrecipe.php"><img id="img1" src="../../images/recipe.png"> Categories</a></li>
        <li class="list"><a href="./admNutri.php"><img id="img1" src="../../images/medi1.png"> Nutritionists</a></li>
        <li class="list"><a href="./admOfficer.php"><img id="img1" src="../../images/officer.png"> Ad Officers</a></li>
        <li class="list"><a href="./admcontact.php"><img id="img1" src="../../images/contact.png"> Contacts</a></li>
        <li class="list"><a href="./adminAcc.php"><img id="img1" src="../../images/admin.png"> Administrators</a></li>
    </ul>
</div>

<div class="topic">
    <h3>Nutritionist Accounts</h3><hr>
</div>

<a id="new_recipe_btn" href="./insertNutri.php">Add Nutritionist</a>
</body>

<?php
include "config.php";

try {
    $sql = "SELECT ID, Nutri_Name, NIC, UserName, Pword FROM Nutriacc";
    $stmt = $con->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "<table cellpadding='1' class='admintable' border='1'>";
    echo "<tr>";
    echo "<th>ID</th>";
    echo "<th>Name</th>";
    echo "<th>NIC</th>";
    echo "<th>Username</th>";
    echo "<th>Action</th>";
    echo "</tr>";

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row["ID"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["Nutri_Name"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["NIC"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["UserName"]) . "</td>";
            echo "<td>";
            echo "<a id='deletelink' href='./deleteNutri.php?id=" . urlencode($row['ID']) . "'>Delete</a>";
            echo " <a id='editlink' href='./editNutri.php?id=" . urlencode($row['ID']) . "'>Edit</a>";
            echo "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No nutritionists found</td></tr>";
    }

    echo "</table>";
} catch (Exception $e) {
    error_log("Database error: " . $e->getMessage());
    echo "<p>An error occurred while retrieving nutritionist data. Please try again later.</p>";
}

$con->close();
?>

</html>
