<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../../html/UserProfileHTML/Login.html');
    exit;
}

require "config.php";

$ID = $_GET["id"] ?? null;

if ($ID) {
   
    $stmt = $con->prepare("SELECT * FROM `nutriacc` WHERE `ID` = ?");
    $stmt->bind_param("i", $ID);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $name = htmlspecialchars($row['Nutri_Name']);
        $nic = htmlspecialchars($row['NIC']);
        $uname = htmlspecialchars($row['UserName']);
        $pword = htmlspecialchars($row['Pword']);
    } else {
        header("Location: ./admNutri.php");
        exit;
    }
    
    $stmt->close();
} else {
    header("Location: ./admNutri.php");
    exit;
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
        <p id="note">Administrator: <?=$_SESSION['name']?></p>
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

<form id="recipe" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <div id="formdiv">
        <label class="formele">ID :</label><br><br>
        <input type="text" name="id" value="<?php echo htmlspecialchars($ID); ?>" readonly><br><br>
        <label class="formele">Name :</label><br><br>
        <input type="text" class="formele" id="name" name="name" required value="<?php echo htmlspecialchars($name); ?>"><br><br>
        <label class="formele">NIC :</label><br><br>
        <input type="text" class="formele" id="nic" name="nic" required value="<?php echo htmlspecialchars($nic); ?>"><br><br>
        <label class="formele">User Name :</label><br><br>
        <input type="text" class="formele" id="username" name="username" required value="<?php echo htmlspecialchars($uname); ?>"><br><br>
        <label class="formele">Password :</label><br><br>
        <input type="password" class="formele" id="password" name="password" required value="<?php echo htmlspecialchars($pword); ?>"><br><br>
        <input type="submit" id="submit" name="submit" value="Update">
    </div>
</form>

<?php
if (isset($_POST["submit"])) {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $nic = $_POST["nic"];
    $uname = $_POST["username"];
    $pword = $_POST["password"];
    
 
    $stmt = $con->prepare("UPDATE `nutriacc` SET `Nutri_Name` = ?, `NIC` = ?, `UserName` = ?, `Pword` = ? WHERE `ID` = ?");
    $stmt->bind_param("ssssi", $name, $nic, $uname, $pword, $id);
    
    if ($stmt->execute()) {
        header("Location: ./admNutri.php?msg=success");
    } else {
        header("Location: ./admNutri.php?msg1=failed");
    }
    
    $stmt->close();
    $con->close();
}
?>
</body>
</html>
