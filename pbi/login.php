<?php
// initialize session
session_start();
 
include("authenticate.php");
 
// check to see if user is logging out
if(isset($_GET['out'])&&($_GET['out']==1)){
	// destroy session
	session_unset();
	$_SESSION = array();
	unset($_SESSION['user'],$_SESSION['access']);
	session_destroy();
}

 
// check to see if login form has been submitted
if(isset($_POST['userLogin'])){
	// run information through authenticator
	if(authenticate($_POST['userLogin'],$_POST['userPassword']))
	{
        //set time of last login
        $_SESSION['last_login_timestamp'] = time();
        // authentication passed
      		header("Location: pbi.php");
		die();
	} else {
		// authentication failed
		$error = 1;
	}
}

?>
 
<!DOCTYPE html>
<html lang="pl-PL">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Logowanie do portalu PBI</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="loginform.css">
</head>
<body>
<div id="loginform">
<div class="w3-container w3-blue forma">
  <h2>Logowanie do portalu PBI</h2>
</div>

<form action="login.php" method="post" class="w3-container forma">
  <p>
  <label>Nazwa użytkownika: </label>
  <input class="w3-input" type="text" name="userLogin"></p>
  <p>
  <label>Hasło: </label>
  <input class="w3-input" type="password" name="userPassword"></p>
  <p>
  <input type="submit" name="submit" value="Submit">
</p>
<p>
    <?php
        // output error to user
        if(isset($error)) echo "Login failed: Incorrect user name, password, or rights<br />";
 
        // output logout success
        if(isset($_GET['out'])) echo "Logout successful";
    ?>
</p>
</form>
</div> <!-- login form-->
</body>
</html> 