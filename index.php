<?php
session_start();

if (isset ($_SESSION['loggedIn']))
    header ("location: ECWaitTimesCharts.php");
?>

<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    
    <link rel="stylesheet" href="Style.css"/>  <!--Css Styling-->

    <title>Home</title>
</head>
<body>
    <div id="HomeRectangle"></div>

    <div id="SiteIntro">
      <h1>Northern Ireland Emergency Care Waiting Times</h1>      
      <h1>Welcome!</h1>
      <p id="IntroParagraph">This site displays information on the waiting time of patients who attended Emergency Care Departments in Northern Ireland, during each month from April 2008 to June 2018.</p>
    </div>

    <div id="Buttons">
        <button id="SignupButton" onclick="document.getElementById('SignUpWindow').style.display='block'">Sign Up</button>    <!--Button to open the window for signing up-->
        <button id="LoginButton"onclick="document.getElementById('LoginWindow').style.display='block'">Login</button>  <!--Button to open the window for logging in-->

        <div id="SignUpWindow" class="modal">
            <span onclick="document.getElementById('SignUpWindow').style.display='none'" class="close" title="Close Modal">&times;</span>
            <form class="modal-content" action="LoginLogoutSignup/Signup.php" method="POST">
            <div class="container">
                <h1>Sign Up</h1>
                <p>Please fill the form!</p>
                <hr>

                <label for="SignupUsername"><b>Username</b></label>
                <input type="text" name="SignupUsername" required>

                <label for="SignupEmail"><b>Email Address</b></label>
                <input type="text" name="SignupEmail" required>

                <label for="SignupPassword"><b>Password</b></label>
                <input type="password" name="SignupPassword" required>

                <label for="SignupPasswordRepeat"><b>Repeat Password</b></label>
                <input type="password" name="SignupPasswordRepeat" required>

                <div class="clearfix">
                    <button type="submit" class="signup" name="SubmitSignupData">Sign Up</button>
                    <button type="button" onclick="document.getElementById('SignUpWindow').style.display='none'" class="signupCancelButton">Cancel</button>
                </div>
            </div>
            </form>
        </div>


        <div id="LoginWindow" class="modal">
            <span onclick="document.getElementById('LoginWindow').style.display='none'" class="close" title="Close Modal">&times;</span>
  
            <form class="modal-content" action="LoginLogoutSignup/Login.php" method="POST">
                <div class="container">
                    <h1>Log In</h1>
                    <hr>

                    <label for="LoginUsername"><b>Username</b></label>
                    <input type="text" name="LoginUsername" required>

                    <label for="LoginPassword"><b>Password</b></label>
                    <input type="password" name="LoginPassword" required>
                    
                    <div class="clearfix">
                    <button type="submit" class="login" name="SubmitLoginData">Log In</button>
                    <button type="button" onclick="document.getElementById('LoginWindow').style.display='none'" class="loginCancelButton">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <footer>
        <div class="FooterContent">
          <h1>About</h1>
          <p>I am a 2nd-year BSc (Hons) Computer Science student and this site was made as part of our Application Development module assignment.</p>
        </div>
    </footer>
</body>
</html>