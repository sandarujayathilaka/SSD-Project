<?php
session_start([
    'cookie_lifetime' => 86400,
    'cookie_secure' => true,
    'cookie_httponly' => true,
    'cookie_samesite' => 'Strict',
]);

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Facebook OAuth credentials
$facebook_client_id = '1566984040892940';  // Replace with your Facebook App ID
$facebook_client_secret = '1f0249ef26251bb2f3b9e42ca71523c8';  // Replace with your Facebook App Secret
$facebook_redirect_uri = 'http://localhost/SSD-Project/php/UserProfilePHP/facebook_callback.php';  // Update with your Facebook callback URL

require 'config.php';  // Database connection

// Exchange the authorization code for an access token
if (isset($_GET['code'])) {
    $code = $_GET['code'];

    // Exchange code for access token
    $token_url = 'https://graph.facebook.com/v12.0/oauth/access_token';
    $token_data = [
        'client_id' => $facebook_client_id,
        'redirect_uri' => $facebook_redirect_uri,
        'client_secret' => $facebook_client_secret,
        'code' => $code,
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $token_url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($token_data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $response_data = json_decode($response, true);

    if (isset($response_data['access_token'])) {
        $access_token = $response_data['access_token'];

        // Fetch user information from Facebook
        $user_info_url = 'https://graph.facebook.com/me?fields=id,name,email,picture.width(400).height(400)&access_token=' . $access_token;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $user_info_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $user_info = curl_exec($ch);
        curl_close($ch);

        $user_info = json_decode($user_info, true);

        // Debugging: Print fetched user data from Facebook
        echo "<h1>Fetched User Data</h1>";
        echo "<pre>";
        print_r($user_info);
        echo "</pre>";

        if (isset($user_info['email'])) {
            $email = $user_info['email'];
            $name = $user_info['name'] ?? 'Unknown User';  // Use the name field if available

            // Split the name into first and last names
            $name_parts = explode(' ', $name);
            $first_name = $name_parts[0]; // First name is usually the first part
            $last_name = isset($name_parts[1]) ? $name_parts[1] : ''; // Last name is the second part if available
            $profile_pic = $user_info['picture']['data']['url'] ?? '';

            // Check if the user exists in the database
            $stmt = $con->prepare('SELECT * FROM user WHERE Email = ?');
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // User exists, log them in
                session_regenerate_id();
                $_SESSION['loggedin'] = TRUE;
                $_SESSION['name'] = $email;
                header('Location: ../../html/home/home.html');  // Redirect to the home page
                exit();
            } else {
                // Debugging: Print what will be inserted
                echo "<h2>Data to be Inserted</h2>";
                echo "<p>Email: $email</p>";
                echo "<p>Full Name: $name</p>";
                echo "<p>First Name: $first_name</p>";
                echo "<p>Last Name: $last_name</p>";
                echo "<img src='$profile_pic' alt='Profile Picture' height='100' width='100'>";

                // User does not exist, create a new user
                $stmt = $con->prepare('INSERT INTO user (Email, FullName, Fname, Lname, Address, Tel, Password, Profile) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
                if (!$stmt) {
                    // Debugging: Show error if prepare fails
                    echo "Prepare failed: (" . $con->errno . ") " . $con->error;
                    exit();
                }

                // Prepare to insert empty values for Address, Tel, and Password
                $empty_value = '';

                $stmt->bind_param('ssssssss', $email, $name, $first_name, $last_name, $empty_value, $empty_value, $empty_value, $profile_pic);
                if ($stmt->execute()) {
                    session_regenerate_id();
                    $_SESSION['loggedin'] = TRUE;
                    $_SESSION['name'] = $email;
                    header('Location: ../../html/home/home.html');  // Redirect to the home page
                    exit();
                } else {
                    // Debugging: Show error if execution fails
                    echo 'Failed to create user: ' . $stmt->error;
                }
            }
        } else {
            echo 'Failed to retrieve user information from Facebook.';
        }
    } else {
        echo 'Failed to retrieve access token.';
    }
} else {
    echo 'Invalid request. Authorization code is missing.';
}

$con->close();
?>
