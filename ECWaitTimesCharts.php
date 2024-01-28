<?php

//The code that displays the site's main webpage, submits the user's selection to the server (using an XMLHTTPRequest object) and finally processes the latter's response.
session_start();

//If the user is not logged in, he is redirected to the initial webpage.
if (!isset ($_SESSION['loggedIn']))
    header ("location: index.php");

//Connect to the database. (Includes the contents of "dbConnect.php" in this file)
include_once ('dbConnect.php');
?>

<!--The page's layout.-->
<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    
    <link rel="stylesheet" href="Style.css"/>   <!--Includes the CSS styling-->

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>     <!--Includes the Google Charts API-->

    <title>Waiting Time Charts</title>
</head>

<body>
    <div id="usernameDisplay">
        <?php 
            if (isset ($_SESSION['loggedIn']))
                {
                    echo   'Hello, ' . $_SESSION['username'] . '!&nbsp;';
                } 
        ?>
    </div>

    <form action="LoginLogoutSignup/Logout.php">
        <button type="submit" id="logoutButton">Logout</button>
    </form>




    <div id="ChartsPageIntro">
      <h1>Northern Ireland Emergency Care Waiting Times</h1>
      <p class="ChartsPageMessage">Use the three comboboxes to select month/year, hospital and visualization method.</p>
      <p class="ChartsPageMessage">After that, press the "Show Info" button to display the respective chart.</p>
    </div>

    <div id="comboboxesDiv">
        <div>
            <form id="datesForm">
                <label for="monthAndYear">Choose a date:</label>

                <select name="monthAndYear" id="chooseDateCombobox">
                <?php
                    //Queries the database and populates the combobox.
                    $sql = mysqli_query($conn, "SELECT distinct Month_And_Year FROM patient_waiting_times;");

                    while ($row = $sql->fetch_assoc())  //The table's columns' take the name of the database's columns/attributes.
                    {
                        echo "<option value=" . $row['Month_And_Year'] . ">" . $row['Month_And_Year'] . "</option>";
                    }
                ?>
                </select>
            </form>
        </div>

        <div>
            <form id="hospitalsForm">
                <label for="hospital">Choose a hospital:</label>

                <select name="hospital" id="chooseHospitalCombobox">
                <?php
                    //Queries the database and populates the combobox.
                    $sql = mysqli_query($conn, "SELECT distinct Hospital_Name FROM patient_waiting_times;");

                    while ($row = $sql->fetch_assoc())
                    {
                        echo "<option value=" . $row['Hospital_Name'] . ">" . $row['Hospital_Name'] . "</option>";
                    }
                ?>
                </select>
            </form>
        </div>

        <div>
            <form id="chartsForm">
                <label for="visualizationCharts">Choose a visualization method:</label>

                <select name="visualizationCharts" id="chooseVisualizationChartCombobox">
                    <option value="Pie chart">Pie chart</option>"
                    <option value="Column chart">Column chart</option>"
                    <option value="Bar chart">Bar chart</option>"
                    <option value="Area chart">Area chart</option>"
                    <option value="Line chart">Line chart</option>"
                    <option value="Scatter chart">Scatter chart</option>"
                </select>
            </form>
        </div>

        <div>
            <form>
                <button type="button" id="chartsPageSubmitButton" onclick="submitChartData()" name="submitButton">Show Info</button>
            </form>
        </div>


        <script>
            function submitChartData ()         //(Over)Simplified function to help understand each phase. Creates a XMLHTTPRequest object and uses it to send a JSON object to the server and
                                                //also defines the actions taken when the server replies.
            {
                var selectedDate = document.getElementById("chooseDateCombobox");                           //Assign the combobox element to a variable.
                var selectedHospital = document.getElementById("chooseHospitalCombobox");
                var selectedChart = document.getElementById("chooseVisualizationChartCombobox");
                var selectedDateValue = selectedDate.options[selectedDate.selectedIndex].text;              //Assign the combobox element's selected value to a variable.
                var selectedHospitalValue = selectedHospital.options[selectedHospital.selectedIndex].text;
                var selectedChartValue = selectedChart.options[selectedChart.selectedIndex].text;

                var jsonObject = {                          //Create a variable/array with the comboboxes' contents. It will, later, be converted to JSON.
                    date: selectedDateValue,
                    hospital: selectedHospitalValue
                };

                var jsonString = JSON.stringify(jsonObject);                    //Convert the previous JavaScript variable to JSON.

                let xhr = new XMLHttpRequest();                                 //Create a XMLHttpRequest object, to send the JSON to the server.
                let url = "RequestsHandlerAPI.php";                             //The server's file that will process the request.

                xhr.open("POST", url, true);                                    //POST request to the given URL. "true", to make it an asynchronous request.
                xhr.setRequestHeader("Content-Type", "application/json");       //Defines the type of data to be sent (JSON).
                xhr.onreadystatechange = function ()                            //Function to perform if a successful reply is sent by the server.
                {
                    if (xhr.readyState === 4 && xhr.status === 200)             //State 4 means server has replied, transaction complete. Status 200 means "OK", Request succeeded.
                    {
                        var parsedServerJSON = JSON.parse (this.responseText);            //Convert the JSON sent by the server to a JavaScript variable (XMLHTTPRequest obj property).
                        var helperArray = parsedServerJSON[0];                            //Save the converted JSON's data in an array.

                        if (typeof helperArray == "undefined")
                            alert ("Sorry, there exist no records for the combination of criteria that you have chosen!");
                        else
                        {                    
                            patientsWaitingFourMinsAndLower = parseInt(helperArray['Waiting_Four_Mins_And_Lower']);       //Save each column of the array in a separate variable.
                            patientsWaitingFiveToTwelveMins = parseInt(helperArray['Waiting_Five_To_Twelve_Mins']);
                            patientsWaitingOverTwelveMins = parseInt(helperArray['Waiting_Over_Twelve_Mins']);
                        }

                        google.charts.load('current', {'packages':['corechart']});                                //Load the Visualization API and the corechart package.

                        google.charts.setOnLoadCallback(drawChart);                                               //Set a callback to run when the Google Visualization API is loaded.

                        // Callback that creates and populates a data table, instantiates the pie chart, passes in the data and draws it.
                        function drawChart()
                        {                            
                            var data = new google.visualization.DataTable();                                    //Create the chart's data table.

                            data.addColumn('string', 'Patients');               //The chart's two components/x and y axes.
                            data.addColumn('number', 'Waiting time');
                            data.addRows([
                                ['Four Minutes or Less', patientsWaitingFourMinsAndLower],
                                ['Five to Twelve Minutes', patientsWaitingFiveToTwelveMins],
                                ['Twelve Minutes or More', patientsWaitingOverTwelveMins],      //The chart's data series, the information it shows.
                            ]);

                            var options = {'title':'Patients\' waiting time',                   //The chart's title and dimensions.
                                            'width':400,
                                            'height':300};

                            if (selectedChartValue == "Pie chart")                                                  //Check the type of chart the user has selected.
                            {
                                var chart = new google.visualization.PieChart(document.getElementById('chart'));  //Instantiate and draw the respective chart, according to the "data" and "options" variables.
                                chart.draw(data, options);
                            }
                            else if (selectedChartValue == "Column chart")
                            {
                                var chart = new google.visualization.ColumnChart(document.getElementById('chart'));
                                options.width = 500;
                                chart.draw(data, options);
                            }
                            else if (selectedChartValue == "Bar chart")
                            {
                                var chart = new google.visualization.BarChart(document.getElementById('chart'));
                                options.width = 500;
                                chart.draw(data, options);
                            }
                            else if (selectedChartValue == "Area chart")
                            {
                                var chart = new google.visualization.AreaChart(document.getElementById('chart'));
                                options.width = 500;
                                chart.draw(data, options);
                            }
                            else if (selectedChartValue == "Line chart")
                            {
                                var chart = new google.visualization.LineChart(document.getElementById('chart'));
                                options.width = 500;
                                chart.draw(data, options);
                            }
                            else
                            {
                                var chart = new google.visualization.ScatterChart(document.getElementById('chart'));
                                options.width = 500;
                                chart.draw(data, options);
                            }
                        }
                    }
                };

                xhr.send(jsonString);       //Sends the JSON object to the server.
            }
        </script>
    </div>

    <div id="chart">
    <script type="text/javascript">


</script>
    </div>

    <footer>
        <div class="FooterContent">
          <h1>About</h1>
          <p>I am a 2nd-year BSc (Hons) Computer Science student and this site was made as part of our Application Development module assignment.</p>
        </div>
    </footer>
</body>
</html>