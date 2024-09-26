<?php
session_start([
    'cookie_lifetime' => 86400,    // Session cookie lifetime of 1 day
    'cookie_secure' => true,       // Send cookie only over HTTPS
    'cookie_httponly' => true,     // Prevent JavaScript access to session cookies
    'cookie_samesite' => 'Strict', // Strict policy to prevent CSRF
]);

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Generate a CSRF token if it doesn't exist
}

// Google OAuth credentials
$client_id = '44275124310-kfc6tuf0hdqjv3a4t1adse43i5suqfil.apps.googleusercontent.com';  // Replace with your Google Client ID
$redirect_uri = 'http://localhost/SSD-Project/php/UserProfilePHP/google_callback.php';  // Update with your Google callback URL

// Google OAuth URL for login
$google_login_url = "https://accounts.google.com/o/oauth2/auth?response_type=code&client_id=$client_id&redirect_uri=$redirect_uri&scope=email%20profile&access_type=online";


// Facebook OAuth credentials
$facebook_client_id = '1566984040892940';  // Replace with your Facebook App ID
$facebook_redirect_uri = 'http://localhost/SSD-Project/php/UserProfilePHP/facebook_callback.php';  // Update with your Facebook callback URL

// Facebook OAuth URL for login
$facebook_login_url = "https://www.facebook.com/v12.0/dialog/oauth?client_id=$facebook_client_id&redirect_uri=$facebook_redirect_uri&scope=email,public_profile";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FOODOVEN | Login</title>
    <link rel="stylesheet" href="../../css/UserProfileCSS/login2.css">
</head>
<body>

<h2>Login Form</h2>

<form action="../../php/UserProfilePHP/authenticate.php" method="post">
    <div class="imgcontainer">
        <img src="../../images/UserProfileIMAGES/login.png" alt="login icon">
    </div>

    <div class="container">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

        <label for="uname"><b>Username</b></label>
        <input type="text" placeholder="Enter Username" name="username" required>

        <label for="psw"><b>Password</b></label>
        <input type="password" placeholder="Enter Password" name="password" required>

        <button type="submit" id="submit">Login</button>

        <!-- Google Login Button -->
        <div class="google-login">
            <a href="<?php echo htmlspecialchars($google_login_url); ?>">Login with Google</a>
        </div>

        <!-- Facebook Login Button -->
        <div class="google-login">
            <a href="<?php echo htmlspecialchars($facebook_login_url); ?>">Login with Facebook</a>
        </div>

        <div id="acc">
            <a href="../../html/UserProfileHTML/Register.html">Don't have an Account?</a>
        </div>
    </div>

    <div class="container cancel-container">
        <button type="reset" class="cancelbtn">Cancel</button>
        <span class="psw"><a href="../../php/UserProfilePHP/reset.php">Forgot password?</a></span>
    </div>
</form>

</body>
</html>
