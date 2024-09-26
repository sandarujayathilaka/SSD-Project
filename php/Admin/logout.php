<?php
// Start the session with the same parameters
session_start([
    'cookie_lifetime' => 86400,  
    'cookie_secure' => false,     
    'cookie_httponly' => true,   
    'cookie_samesite' => 'Strict' 
]);

// Clear all session variables
$_SESSION = [];

// If you want to remove the session cookie (optional)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();

    setcookie(session_name(), '', time() - 42000, 
        $params["path"], 
        $params["domain"], 
        $params["secure"], 
        $params["httponly"]
    );

   
    header('Set-Cookie: ' . session_name() . '=; expires=' . gmdate('D, d-M-Y H:i:s T', time() - 42000) . 
    '; path=' . $params['path'] . '; domain=' . $params['domain'] . 
    '; secure; HttpOnly; SameSite=Strict');
}

// Destroy the session
session_destroy();

// Redirect to the login page
header('Location: ../../html/UserProfileHTML/Login.html');
exit();
?>
