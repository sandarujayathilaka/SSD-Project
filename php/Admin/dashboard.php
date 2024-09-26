<?php
session_start([
    'cookie_lifetime' => 86400,  
    'cookie_secure' => false,     
    'cookie_httponly' => true,   
    'cookie_samesite' => 'Strict' 
]);
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../../html/UserProfileHTML/Login.html');
    exit;
}


include "config.php";

function getCount($con, $table) {
    $stmt = $con->prepare("SELECT COUNT(ID) AS count FROM $table");
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['count'] ?? 0;  
}

$adminCount = getCount($con, 'adminacc');
$nutritionistCount = getCount($con, 'nutriacc');
$officerCount = getCount($con, 'officeracc');
$userCount = getCount($con, 'user');
$contactCount = getCount($con, 'contacts');
$recipeCount = getCount($con, 'Recipe');

$con->close();
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
    <div id="logbtn">
        <p id="note">Administrator: <?= htmlspecialchars($_SESSION['name']); ?></p>
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

<div id="dash">
    <div id="admincount">
        <h2 class="dashhead">Admins</h2>
        <img class="dashimg" src="../../images/dashadmin.png">
        <br>
        <h2 class="dashcount"><?= $adminCount; ?></h2>
    </div>
    <div id="usercount">
        <h2 class="dashhead">Users</h2>
        <img class="dashimg" src="../../images/dashuser.png">
        <br>
        <h2 class="dashcount"><?= $userCount; ?></h2>
    </div>
    <div id="nutricount">
        <h2 class="dashhead">Nutritionists</h2>
        <img id="dashimg1" src="../../images/adminmed.png">
        <br>
        <h2 class="dashcount"><?= $nutritionistCount; ?></h2>
    </div>
    <div id="officercount">
        <h2 class="dashhead">Officers</h2>
        <img class="dashimg" src="../../images/dashof.png">
        <br>
        <h2 class="dashcount"><?= $officerCount; ?></h2>
    </div>
    <div id="contacts">
        <h2 class="dashhead">Inquiries</h2>
        <img id="dashcon" src="../../images/dashcon.png">
        <br>
        <h2 class="dashcount"><?= $contactCount; ?></h2>
    </div>
    <div id="cat">
        <h2 class="dashhead">Categories</h2>
        <img id="catimg" src="../../images/cat.png">
        <br>
        <h2 class="dashcount"><?= $recipeCount; ?></h2>
    </div>
</div>

</body>
</html>
