<?php
session_start();

$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'iwt';

// Connect to MySQL
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

// Check if the username and password fields are filled
if (!isset($_POST['username'], $_POST['password'])) {
    exit('Please fill both the username and password fields!');
}

// User Login
if ($stmt = $con->prepare('SELECT Email, Password FROM user WHERE Email = ?')) {
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($Email, $Password);
        $stmt->fetch();


		$stmt->bind_result($Email, $Password);
$stmt->fetch();


        // Verify the hashed password

		$enteredPassword = trim($_POST['password']);
	
        if (password_verify($enteredPassword, $Password)) {
			   echo "Password verified successfully!<br>";
            session_regenerate_id();

            $_SESSION['loggedin'] = TRUE;
            $_SESSION['name'] = $_POST['username'];
            echo 'Welcome ' . $_SESSION['name'] . '!';
            header('Location: ../../html/home/home.html');
        } else {
            echo 'Incorrect password aaaaaa!';
        }
    } else {
        echo 'Incorrect username bbbbb!';
    }
    $stmt->close();
}

// Admin Login
if ($stmt = $con->prepare('SELECT UserName, Pword FROM adminacc WHERE UserName = ?')) {
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($UserName, $Pword);
        $stmt->fetch();

        // Verify the hashed password
        if (password_verify($_POST['password'], $Pword)) {
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['name'] = $_POST['username'];
            header('Location: ../Admin/dashboard.php');
        } else {
            echo 'Incorrect password!';
        }
    } else {
        echo 'Incorrect username!';
    }
    $stmt->close();
}

// Nutritionist Login
if ($stmt = $con->prepare('SELECT UserName, Pword FROM nutriacc WHERE UserName = ?')) {
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($UserName, $Pword);
        $stmt->fetch();

        // Verify the hashed password
        if (password_verify($_POST['password'], $Pword)) {
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['name'] = $_POST['username'];
            header('Location: ../appoinment/nutrionistapp.php');
        } else {
            echo 'Incorrect password!';
        }
    } else {
        echo 'Incorrect username!';
    }
    $stmt->close();
}

// Officer Login
if ($stmt = $con->prepare('SELECT UserName, Pword FROM officeracc WHERE UserName = ?')) {
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($UserName, $Pword);
        $stmt->fetch();

        // Verify the hashed password
        if (password_verify($_POST['password'], $Pword)) {
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['name'] = $_POST['username'];
            header('Location: ../adofficer/main.php');
        } else {
            echo 'Incorrect password!';
        }
    } else {
        echo 'Incorrect username!';
    }
    $stmt->close();
}

$con->close();
?>
