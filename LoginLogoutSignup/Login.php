<?php

//This class manages the users' logging in procedure.
session_start();

//Connect to the database. (Includes the contents of "dbConnect.php" in this file)
include_once ('dbConnect.php');

//If there is a HTTP POST request with the given id.
if(isset($_POST["SubmitLoginData"]))
{
    //Save the "Username" and "Password" textboxes' contents in variables.
    $loginUsername = strip_tags(mysqli_real_escape_string($conn, $_POST['LoginUsername']));
    $loginPassword = strip_tags(mysqli_real_escape_string($conn, $_POST['LoginPassword']));
   
    //Query the DB for the current (still not logged in) user's username and password.
    $queryUserTable = mysqli_query($conn, "select Username, User_Password from users where Username = '$loginUsername';");
    //Save the above query's results into an associative array.
    $arrayUserTable = mysqli_fetch_assoc ($queryUserTable);
    //Check the validity of the password by first decrypting it.
    $verifiedPassword = password_verify ($loginPassword, $arrayUserTable ['User_Password']);

    if ($verifiedPassword)
    {
        $_SESSION['username'] = $loginUsername;     //Global variables.
        $_SESSION['loggedIn'] = true;

        //Redirects the user.
        echo '<script>alert ("You have successfully logged in!");
        window.location.replace("../ECWaitTimesCharts.php");
        </script>';
    }
    else
    {
        echo '<script>alert ("Wrong username or password. Please retry!");
        window.location.replace("../index.php");
        </script>';
    }
}

$conn->close();
?>