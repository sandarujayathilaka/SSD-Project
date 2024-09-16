<?php
// Start the session
session_start();

// Google OAuth URLs and your credentials
$client_id = '171830815870-ts1kr0h37548mk1rnjhcj3r5qjo1rt6p.apps.googleusercontent.com';  // Replace with your Google Client ID
$redirect_uri = 'www.google.com';  // Replace with your Redirect URI

// Google OAuth URL
$google_login_url = "https://accounts.google.com/o/oauth2/auth?response_type=code&client_id=$client_id&redirect_uri=$redirect_uri&scope=email%20profile&access_type=online";

?>

<!DOCTYPE html>
<html>
<head>
    <title>FOODOVEN | Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background-image: url('https://img.freepik.com/free-photo/empty-wood-table-top-abstract-blurred-restaurant-cafe-background-can-be-used-display-montage-your-products_7191-916.jpg?w=740');
            width: 100%;
            background-size: 1000px 700px;
        }
        form {
            border: 3px solid #f1f1f1;
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 50%;
            background-image: url(https://www.wallpapertip.com/wmimgs/3-36163_dark-blur.jpg);
            color: white;
            border-radius: 14px;
            box-shadow: 0 0 8px  #669999;
        }
        h2 {
            display: block;
            margin-left: auto;
            margin-right: auto;
            text-align: center;
            background-color: #d1d0d1;
            color: rgb(19, 182, 4);
            box-shadow: #f1f1f1;
            width: 30%;
            border-radius:10px 1px;
            padding: 12px 20px;
            box-shadow: 0 0 13px 0  #007c1b;
        }
        input[type=text], input[type=password] {
            width: 25%;
            display: block;
            margin-bottom: 5%;
            margin-top: 1%;
            margin-left: auto;
            margin-right: auto;
            font-size: small;
            padding: 12px 20px;
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 3px 0  #669999;
            outline: none;
            background-color: #dddddd;
        }
        label {
            width: 25%;
            display: block;
            margin-bottom: 4%;
            margin-left: 44%;
            margin-right: auto;
            margin-bottom: 0%;
        }
        #submit {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 40%;
            background-color: #04AA6D;
            padding: 12px 20px;
            border: none;
            border-radius: 14px;
            outline: none;
        }
        #acc {
            text-align: center;
            margin-top: 2%;
            background-color: aliceblue;
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 30%;
            border-radius: 5px;
        }
        button:hover {
            opacity: 0.8;
        }
        .cancelbtn {
            width: auto;
            padding: 10px 18px;
            background-color: #f44336;
            border: none;
            border-radius: 14px;
            outline: none;
        }
        .imgcontainer {
            text-align: center;
            margin: 24px 0 12px 0;
        }
        img {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 20%;
        }
        .container {
            padding: 16px;
        }
        span.psw {
            float: right;
            padding-top: 16px;
        }
        @media screen and (max-width: 300px) {
            span.psw {
                display: block;
                float: none;
            }
            .cancelbtn {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<h2>Login Form</h2>

<form action="../../php/UserProfilePHP/authenticate.php" method="post">
    <div class="imgcontainer">
        <img src="../../images/UserProfileIMAGES/login.png" title="login icon">
    </div>

    <div class="container">
        <label for="uname"><b>Username</b></label>
        <input type="text" placeholder="Enter Username" name="username" required>

        <label for="psw"><b>Password</b></label>
        <input type="password" placeholder="Enter Password" name="password" required>
        
        <button type="submit" id="submit">Login</button>

        <!-- Google Login Button -->
        <div style="text-align: center; margin-top: 10px;">
            <a href="<?php echo htmlspecialchars($google_login_url); ?>">Login with Google</a>
        </div>

        <div id="acc">
            <a href="../../html/UserProfileHTML/Register.html">Don't have an Account?</a>
        </div>
    </div>

    <div class="container" style="background-color:#f1f1f1">
        <button type="reset" class="cancelbtn">Cancel</button>
        <span class="psw"> <a href="../../php/UserProfilePHP/reset.php">Forgot password?</a></span>
    </div>
</form>

</body>
</html>
