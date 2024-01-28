<?php

//The credentials of the server and the database.
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "EmergencyCareDB";

//Establishing the connection.
$conn = mysqli_connect($servername, $username, $password, $dbname);

//Checking for errors.
if ($conn->connect_error)
{
    die("Connection failed: " . $conn->connect_error);
        
    echo 'alert ("Error in connecting to the DB");';
}
?>