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
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
require "config.php";

if (isset($_GET["id"])) {
    $ID = $_GET["id"];

    $stmt = $con->prepare("SELECT * FROM `nutriacc` WHERE `ID` = ?");
    $stmt->bind_param("i", $ID); 

    $stmt->execute();

    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $name = $row['Nutri_Name'];
        $nic = $row['NIC'];
        $uname = $row['UserName'];
        $pword = $row['Pword'];
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
    <title>FOODOVEN</title>
    <link rel="stylesheet" href="../../css/Admin/admin.css">
    <link rel="stylesheet" href="../../css/Admin/recipe.css">
</head>
<body>

<nav>
    <img id="logo" src="../images/logo1.png">

    <div id="logbtn">
        <p id="note">Administrator : <?=$_SESSION['name']?></p>
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

<form id="recipe" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <div id="formdiv">
        <label class="formele">ID :</label><br><br>
        <input type="text" name="id" value="<?php echo $ID ?>" readonly><br><br>

        <label class="formele">Name :</label><br><br>
        <input type="text" class="formele" id="name" name="name" required value="<?php echo $name ?>"><br><br>

        <label class="formele">NIC :</label><br><br>
        <input type="text" class="formele" id="nic" name="nic" required value="<?php echo $nic ?>"><br><br>

        <label class="formele">User Name :</label><br><br>
        <input type="text" class="formele" id="username" name="username" required value="<?php echo $uname ?>"><br><br>

        <label class="formele">Password :</label><br><br>
        <input type="password" class="formele" id="password" name="password" required value="<?php echo $pword ?>"><br><br>
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
        <input type="submit" id="submit" name="submit" value="Update">
    </div>
</form>

</body>

<?php

require "config.php";

if (isset($_POST["submit"])) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token validation failed");
    }
    $id = $_POST["id"];
    $name = $_POST["name"];
    $nic = $_POST["nic"];
    $uname = $_POST["username"];
    $pword = $_POST["password"];
    $hashedPassword = password_hash($pword, PASSWORD_BCRYPT);


    $stmt = $con->prepare("UPDATE `nutriacc` SET `Nutri_Name` = ?, `NIC` = ?, `UserName` = ?, `Pword` = ? WHERE `ID` = ?");
    $stmt->bind_param("ssssi", $name, $nic, $uname,  $hashedPassword, $id); 

  
    if ($stmt->execute()) {
        header("Location: ./admNutri.php?msg=success");
    } else {
        header("Location: ./admNutri.php?msg1=failed");
    }

    $stmt->close(); 
}
?>

</html>
