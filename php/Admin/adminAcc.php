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
    echo '<script> alert("Successfully Updated") </script>';
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
    <h3>Administrator Accounts</h3><hr>
</div>
<a id="new_recipe_btn" href="./insertAdmin.php">Add Admin</a>
</body>

<?php
include "config.php";

try {
    $sql = "SELECT ID, Link, Admin_Name, NIC, UserName, Pword FROM adminacc";
    $stmt = $con->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result(); 

  
    echo "<table cellpadding='1' class='admintable' border='1'>";
    echo "<tr>";
    echo "<th>ID</th>";
    echo "<th>Image</th>";
    echo "<th>Name</th>";
    echo "<th>NIC</th>";
    echo "<th>Username</th>";
    echo "<th>Password</th>";
    echo "<th>Action</th>";
    echo "</tr>";

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $img = htmlspecialchars($row["Link"]);
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row["ID"]) . "</td>";
            echo "<td><img src='" . $img . "' height='125' width='100'></td>";
            echo "<td>" . htmlspecialchars($row["Admin_Name"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["NIC"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["UserName"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["Pword"]) . "</td>";
            echo "<td>";
            echo "<a id='deletelink' href='./deleteAdminAcc.php?id=" . urlencode($row['ID']) . "'>Delete</a>";
            echo " <a id='editlink' href='./editAdminAcc.php?id=" . urlencode($row['ID']) . "'>Edit</a>";
            echo "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='7'>No administrators found</td></tr>";
    }

    echo "</table>";
} catch (Exception $e) {
 
    error_log("Database error: " . $e->getMessage());
    echo "<p>An error occurred while retrieving administrator data. Please try again later.</p>";
}

$con->close();
?>

</html>
