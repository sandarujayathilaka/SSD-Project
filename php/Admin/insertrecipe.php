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


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token validation failed");
    }
    $title = htmlspecialchars($_POST["title"]);
    $selectedUser = htmlspecialchars($_POST["select1"]);

    $stmt = $con->prepare("INSERT INTO recipe (UserName, Category) VALUES (?, ?)");
    $stmt->bind_param("ss", $selectedUser, $title);

    if ($stmt->execute()) {
        echo '<script>alert("Category Name is inserted.");</script>';
        header("Location: ./admrecipe.php");
        exit();
    } else {
        echo '<script>alert("Error: ' . $con->error . '");</script>';
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
        <?php
  
        $sql = "SELECT UserName FROM adminacc";
        $result = $con->query($sql);

        echo "<label class='formele'>User Name:</label><br><br>";
        echo "<select name='select1' id='selectmenu'>";

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . htmlspecialchars($row['UserName']) . "'>" . htmlspecialchars($row['UserName']) . "</option>";
            }
        }

        echo "</select>";
        ?>

        <br><br>
        <label class="formele">Category Name:</label><br><br>
        <input type="text" class="formele" id="title" name="title" required><br><br>
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
        <input type="submit" id="submit" name="submit" value="Submit">
    </div>
</form>

</body>
</html>
