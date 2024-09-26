
<?php

session_start([
    'cookie_lifetime' => 86400,  
    'cookie_secure' => true,     
    'cookie_httponly' => true,   
    'cookie_samesite' => 'Strict' 
]);


// Generate CSRF token if not set
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  
    // Check for CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Invalid CSRF token.'); 
    }
  $category = $_POST['select1'];
  $username = $_POST['user_name'];
  $Title = $_POST['Title'];
  $Time = $_POST['Time'];
  $Ingredients = $_POST['Ingredients'];
  $Description = $_POST['Description'];
$Images = $_POST['Images'];

$conn = new mysqli('localhost','root','','iwt');
if($conn->connect_error){
	die('connection failed:'.$conn->connect_error);
}
else{
	$stmt ="insert into add_recipe(category,user_name,Title,Time,Ingredients,Description,Images)values('$category','$username','$Title','$Time','$Ingredients','$Description','$Images')";
	if($conn->query($stmt)){
		
		
		echo "Success";
		
	}
	
	else
		echo "Ërror :".$conn->error;

 


$conn->close();


}
}

?>
