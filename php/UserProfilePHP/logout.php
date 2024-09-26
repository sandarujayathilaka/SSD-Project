<?php
// Start the session with the same parameters
session_start([
    'cookie_lifetime' => 86400,  
    'cookie_secure' => true,     
    'cookie_httponly' => true,   
    'cookie_samesite' => 'Strict' 
]);

// Clear all session variables
$_SESSION = [];


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


session_destroy();


header('Location: ../Html/login.html');
exit();
?>
