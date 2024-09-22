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
?>

<?php
require "config.php";

if (isset($_GET["id"])) {
    $ID = $_GET["id"];

    $stmt = $con->prepare("SELECT * FROM `adminacc` WHERE `ID` = ?");
    $stmt->bind_param("i", $ID); 

    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $name = $row['Admin_Name'];
        $nic = $row['NIC'];
        $uname = $row['UserName'];
        $pword = $row['Pword'];
        $url = $row['Link'];
    } else {
        header("Location:./adminAcc.php");
        exit;
    }

    $stmt->close();
}
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
    <link rel="stylesheet" href="../../css/Admin/recipe.css">
</head>
<body>

<nav>
<div id="logbtn">
    <p id="note">Administrator : <?= $_SESSION['name'] ?></p>
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
        <img src="<?php echo $url ?>" alt="<?php echo $url ?>" style="width:100px;height:125px;"><br><br>
        <input type="hidden" name="exurl" value="<?php echo $url ?>"><br><br>
        Profile Picture : <input type="file" id="img" name="file" required><br><br>
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
        <input type="submit" id="submit" name="submit" value="Update">
    </div>
</form>

<?php
require "config.php";

if (isset($_POST["submit"])) {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("Invalid CSRF token");
    }
    $id = $_POST["id"];
    $name = $_POST["name"];
    $nic = $_POST["nic"];
    $uname = $_POST["username"];
    $pword = $_POST["password"];
    $exurl = $_POST["exurl"];

    // Handle file upload
    $fileName = $_FILES['file']['name'];
    $fileTempName = $_FILES['file']['tmp_name'];
    $fileSize = $_FILES['file']['size'];
    $fileError = $_FILES['file']['size'];
    $fileType = $_FILES['file']['type'];
    $fileExt = explode(".", $fileName);
    $realFile = strtolower(end($fileExt));

    if (unlink($exurl)) {
        // File deleted
    } else {
        echo "There was an error deleting the image";
        exit;
    }

    $target_dir = "../../Images/";
    $target_file = $target_dir . time() . "-" . basename($_FILES["file"]["name"]);

    if (isset($_FILES["file"])) {
        $allowed = array('png', 'jpg', 'jpeg');

        if (in_array($realFile, $allowed)) {
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                echo "<script>alert('The file " . basename($_FILES["file"]["name"]) . " is uploaded.')</script>";

                // Prepared statement for UPDATE query
                $stmt = $con->prepare("UPDATE `adminacc` SET `Admin_Name`=?, `NIC`=?, `UserName`=?, `Pword`=?, `Link`=? WHERE `ID` = ?");
                $stmt->bind_param("sssssi", $name, $nic, $uname, $pword, $target_file, $id);

                if ($stmt->execute()) {
                    header("Location: ./adminAcc.php?msg=success");
                } else {
                    echo "Error: " . $con->error;
                }

                $stmt->close();
            } else {
                echo "Error while uploading your file.";
            }
        } else {
            echo '<script>alert("Wrong file type. Please re-enter details and upload the file again.");</script>';
        }
    } else {
        echo "File not available";
    }

    $con->close();
}
?>

</body>
</html>
