<?php
session_start();  //start or resume an existing session

include '../inc/dbConnection.php';

$conn = getDBConnection("blockbuster");

$username = $_POST['username'];
$password = md5($_POST['password']);   //hash("sha1",$_POST['password']);

//USE NAMEDPARAMETERS TO PREVENT SQL INJECTION
//$sql = "SELECT * FROM admin WHERE username = '$username' AND password = '$password'";
$sql = "SELECT * FROM admin WHERE username = :username AND password = :password";

$namedParameters[':username'] = $username;
$namedParameters[':password'] = $password;


$statement = $conn->prepare($sql);
$statement->execute($namedParameters);
$record = $statement->fetch(PDO::FETCH_ASSOC);

//print_r($record);

 if (empty($record)) { //wrong credentials
     
     echo "Wrong username or password";
     
 } else {
     
     $_SESSION["adminName"] = $record['firstName'] . " " . $record['lastName'];
     $_SESSION['username'] = $record['username'];
     header("Location: admin.php"); //redirect to the main admin program
     
 }


?>