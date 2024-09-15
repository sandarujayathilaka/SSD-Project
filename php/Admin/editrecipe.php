<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../../html/UserProfileHTML/Login.html');
    exit;
}

require "config.php";

if (isset($_GET["id"])) {
    $ID = $_GET["id"];

    
    $stmt = $con->prepare("SELECT * FROM `recipe` WHERE `id` = ?");
    $stmt->bind_param("i", $ID);

  
    $stmt->execute();

  
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $title = $row['Category'];
        $Username = $row['UserName'];
    } else {
        header("Location: ./admrecipe.php");
        exit;
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

        <?php
        
        $stmt = $con->prepare("SELECT ID, UserName FROM adminacc");
        $stmt->execute();
        $result = $stmt->get_result();

        echo "<label class='formele'>User Name :</label><br><br>";
        echo "<select name='select1' id='selectmenu'>";

        while ($row = $result->fetch_assoc()) {
            echo "<option value=\"" . htmlspecialchars($row['UserName']) . "\"" . ($Username == $row['UserName'] ? " selected" : "") . ">" . htmlspecialchars($row['UserName']) . "</option>";
        }

        echo "</select>";

        $stmt->close(); 
        ?>

        <input type="hidden" name="id" value="<?php echo htmlspecialchars($ID); ?>">

        <br><br>
        <label class="formele">Category :</label><br><br>
        <input type="text" class="formele" name="title" required value="<?php echo htmlspecialchars($title); ?>"><br><br>

        <input type="submit" id="submit" name="submit" value="Update">

    </div>
</form>

</body>

<?php
if (isset($_POST["submit"])) {
    $ID = $_POST["id"];
    $title = $_POST["title"];
    $select = $_POST["select1"];

    
    $stmt = $con->prepare("UPDATE `recipe` SET `UserName` = ?, `Category` = ? WHERE `id` = ?");
    $stmt->bind_param("ssi", $select, $title, $ID); 
    if ($stmt->execute()) {
        header("Location: ./admrecipe.php");
    } else {
        echo "Error: " . $con->error;
    }

    $stmt->close(); 
}
?>

</html>
