<?php

//This class manages the users' signing up procedure.
session_start();

//Connect to the database. (Includes the contents of "dbConnect.php" in this file)
include_once ('dbConnect.php');

//If there is a HTTP POST request with the given id.
if (isset ($_POST["SubmitSignupData"]))
{
    //Save the textboxes' contents in variables
    $signupUsername = strip_tags(mysqli_real_escape_string($conn, $_POST['SignupUsername']));           //"Username" textbox.
    $signupPassword = strip_tags(mysqli_real_escape_string($conn, $_POST['SignupPassword']));           //"Password" textbox.
    $verifiedPassword = strip_tags(mysqli_real_escape_string($conn, $_POST['SignupPasswordRepeat']));   //"Repeat Password" textbox.
    $signupEmail = strip_tags(mysqli_real_escape_string($conn, $_POST['SignupEmail']));                 //"Email Address" textbox.
    $helperBool = true;
    //Checks that the "Password" and "Repeat Password" textboxes contain identical text.
    $passwordVerified = ($verifiedPassword == $signupPassword);
   
    //Query the DB for previously saved usernames.
    $queryUserTable = mysqli_query($conn, "Select Username from users;");
    $arrayUserTable = array();

    //Save the above query's results into an associative array.
    while ($helperArray = mysqli_fetch_assoc ($queryUserTable))
    {
        array_push($arrayUserTable, $helperArray);
    }

    //Checks if the inputted username is already in use.  Two foreach statements because we have an array inside another array.
    foreach ($arrayUserTable as $result1)
    {
        foreach ($result1 as $result2)
        {
            if ($signupUsername == $result2)
                $helperBool = false;
        }
    }

    //If the username is not already in use and the two inputted passwords match.
    if ($helperBool && $passwordVerified)
    {
        //Encrypts the password before saving it in the DB.
        $encryptedPassword = password_hash ($signupPassword, PASSWORD_DEFAULT);

        //Updates the DB.
        mysqli_query ($conn, "insert into users
                              values ('$signupUsername', '$encryptedPassword', '$signupEmail');");

        //Redirects the user.
        echo '<script>alert ("You have been successfully registered!");
        window.location.replace("../index.php");
        </script>';
    }
    else
    {
        echo '<script>alert("The username you have entered is already in use or the passwords you have entered do not match!");
        window.location.replace("../index.php");</script>';
    }
}

$conn->close();
?>