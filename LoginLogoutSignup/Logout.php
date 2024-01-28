<?php

//This class manages the users' logging out procedure.
session_start();

//Resets (empties) the global variable containing the current session's information.
$_SESSION = array();

session_destroy();

echo '<script>alert ("You have successfully logged out!");
        window.location.replace("../index.php");
        </script>';
?>