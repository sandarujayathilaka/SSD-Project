<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../../html/UserProfileHTML/Login.html');
    exit;
}

require "config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   
    $name = htmlspecialchars($_POST["name"]);
    $nic = htmlspecialchars($_POST["nic"]);
    $uname = htmlspecialchars($_POST["username"]);
    $pword = htmlspecialchars($_POST["password"]);
    
   
    $stmt = $con->prepare("INSERT INTO officeracc (Officer_Name, NIC, UserName, Pword) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $nic, $uname, $pword);

    if ($stmt->execute()) {
        header("Location: ./admOfficer.php");
        exit();
    } else {
        echo '<script>alert("Username already exists. Please choose a unique username.");</script>';
    }

    $stmt->close();
    $con->close();
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

<form id="recipe" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <div id="formdiv">
        <label class="formele">Name:</label><br><br>
        <input type="text" class="formele" id="name" name="name" required><br><br>

        <label class="formele">NIC:</label><br><br>
        <input type="text" class="formele" id="nic" name="nic" required><br><br>

        <label class="formele">User Name:</label><br><br>
        <input type="text" class="formele" id="username" name="username" required><br><br>

        <label class="formele">Password:</label><br><br>
        <input type="password" class="formele" id="password" name="password" required><br><br>

        <input type="submit" id="submit" name="submit" value="Submit">
    </div>
</form>

</body>
</html>
