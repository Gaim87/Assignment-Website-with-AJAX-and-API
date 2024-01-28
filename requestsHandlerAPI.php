<?php

//Connect to the database. (Includes the contents of "dbConnect.php" in this file)
include_once ('dbConnect.php');

header("Content-Type: application/json");                           //This .php file will output/send a JSON object.


$decodedClientJSON = json_decode (file_get_contents("php://input"), true);      //Converts the JSON object sent by the client to a PHP object. "true", to save it in an associative array.
$dateSearchCriterion = $decodedClientJSON["date"];                              //Variables for each of the array's contents.
$hospitalSearchCriterion = $decodedClientJSON["hospital"];

$queryDB = mysqli_query ($conn, "select Waiting_Four_Mins_And_Lower, Waiting_Five_To_Twelve_Mins, Waiting_Over_Twelve_Mins from patient_waiting_times
                                where Month_And_Year = '" . $dateSearchCriterion . "' and Hospital_Name = '" . $hospitalSearchCriterion . "'");         //Query the DB according to the search criteria.
$helperArray = array ();

while ($row = mysqli_fetch_assoc ($queryDB))                //Save the query's result in an associative array. (The table's columns' take the name of the database's columns/attributes)
{
    $helperArray[] = $row; 
}

$queryResultInJSON = json_encode ($helperArray);            //Converts a PHP variable to JSON.

echo ($queryResultInJSON);          //Sends the JSON to the client.

mysqli_close ($conn);
?>