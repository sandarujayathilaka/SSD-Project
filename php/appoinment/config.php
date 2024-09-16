<?php  
// Initialize the $errors array
$errors = array();

$con = new mysqli("localhost", "root", "", "iwt");

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Add appointment
if (isset($_POST['Add'])) {

   $addID = $_POST['addID'];  
   $adddesc = $_POST['adddesc'];
   $addAddress = $_POST['addAddress'];
   $addContactNumber = $_POST['addContactNumber'];
   $addEmail = $_POST['addEmail'];
   $addtime = $_POST['addtime'];
   $adddate = $_POST['adddate'];

   // Validate inputs
   if (empty($addID)) array_push($errors, "Appointment ID is required");
   if (empty($adddesc)) array_push($errors, "Description is required");
   if (empty($addAddress)) array_push($errors, "Address is required");
   if (empty($addContactNumber)) array_push($errors, "Contact Number is required");
   if (empty($addEmail)) array_push($errors, "Email is required");
   if (empty($adddate)) array_push($errors, "Date is required");
   if (empty($addtime)) array_push($errors, "Time is required");

   if (count($errors) == 0) {
       $stmt = $con->prepare("INSERT INTO appoinment (appID, appDescription, address, contact, email, date, time) VALUES (?, ?, ?, ?, ?, ?, ?)");
       $stmt->bind_param("sssssss", $addID, $adddesc, $addAddress, $addContactNumber, $addEmail, $adddate, $addtime);

       if ($stmt->execute()) {
           echo "Appointment added successfully!";
       } else {
           array_push($errors, "Error: " . $stmt->error);
       }

       $stmt->close();
   }
}

// Delete appointment
if (isset($_POST['Delete'])) {

   $deleteID = $_POST['deleteID'];

   if (empty($deleteID)) {
       array_push($errors, "Appointment ID is required");
   }


   if (count($errors) == 0) {
       $stmt = $con->prepare("DELETE FROM appoinment WHERE appID = ?");
       $stmt->bind_param("s", $deleteID);

       if ($stmt->execute()) {
           if ($stmt->affected_rows == 0) {
               array_push($errors, "Wrong Appointment ID");
           } else {
               echo "Appointment deleted successfully!";
           }
       } else {
           array_push($errors, "Error: " . $stmt->error);
       }

       $stmt->close();
   }
}

$con->close();
?>
