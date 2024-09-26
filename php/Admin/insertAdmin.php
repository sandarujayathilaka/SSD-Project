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
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

require "config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token validation failed");
    }
    $name = htmlspecialchars($_POST["name"]);
    $nic = htmlspecialchars($_POST["nic"]);
    $uname = htmlspecialchars($_POST["username"]);
    $pword = htmlspecialchars($_POST["password"]);
    $hashedPassword = password_hash($pword, PASSWORD_BCRYPT);

   
    if (isset($_FILES["file"]) && $_FILES["file"]["error"] === UPLOAD_ERR_OK) {
        $fileName = $_FILES['file']['name'];
        $fileTempName = $_FILES['file']['tmp_name'];
        $fileSize = $_FILES['file']['size'];
        $fileType = $_FILES['file']['type'];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $allowed = ['png', 'jpg', 'jpeg'];
        if (in_array($fileExt, $allowed)) {
            $target_dir = "../../images/";
            $target_file = $target_dir . time() . "-" . basename($fileName);

            if (move_uploaded_file($fileTempName, $target_file)) {
              
                $stmt = $con->prepare("INSERT INTO adminacc (Admin_Name, NIC, UserName, Pword, Link) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssss", $name, $nic, $uname, $hashedPassword, $target_file);

                if ($stmt->execute()) {
                    header("Location: ./adminAcc.php");
                    exit();
                } else {
                    echo '<script>alert("Username already exists. Please choose a unique username.");</script>';
                }

                $stmt->close();
            } else {
                echo '<script>alert("Error uploading file.");</script>';
            }
        } else {
            echo '<script>alert("Invalid file type. Please upload a PNG, JPG, or JPEG file.");</script>';
        }
    } else {
        echo '<script>alert("No file uploaded or error occurred.");</script>';
    }
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

<form id="recipe" method="POST" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <div id="formdiv">
        <label class="formele">Name:</label><br><br>
        <input type="text" class="formele" id="name" name="name" required><br><br>

        <label class="formele">NIC:</label><br><br>
        <input type="text" class="formele" id="nic" name="nic" required><br><br>

        <label class="formele">User Name:</label><br><br>
        <input type="text" class="formele" id="username" name="username" required><br><br>

        <label class="formele">Password:</label><br><br>
        <input type="password" class="formele" id="password" name="password" required><br><br>

        <label class="formele">Profile Picture:</label><br><br>
        <input type="file" id="img" name="file" required><br><br>
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

        <input type="submit" id="submit" name="submit" value="Submit">
    </div>
</form>

</body>
</html>
