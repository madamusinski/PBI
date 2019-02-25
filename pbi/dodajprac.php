<?php
include_once 'DB.php';
$db = new DB();

$dbh = $db->getDB();

    session_start();

    //$t=time();

  

    if(!isset($_SESSION["user"])){
        header("Location: login.php");
        die();
    }else{
        /*if(isset($_GET['logged']) && trim($_GET['logged']=='1')){
            echo"<script> alert('Twoim administratorem danych osobowych jest ');</script>";
        }*/
        if((time() - $_SESSION['last_login_timestamp']) > 600){
            //echo"<script> alert('10 seconds over!');</script>";
            header("Location: login.php");
        }else{
            $_SESSION['last_login_timestamp'] = time();
        }

    }

    if($_SESSION['access'] != 2) {
        // another example...
        // user is logged in but not a manager, let's stop him
        //echo "Access Denied!";
        die("Access Denied");
    }
	
	if(isset($_POST['submit'])){
		echo '<BR> submit został wysłany';
		if(!empty($_POST['nazw'])&&!empty($_POST['imie']))
		{
			echo '<BR> nazw i imie nie są puste';
		$nazw = $_POST['nazw'];
		$imie = $_POST['imie'];
		$db->dodajPracownika($nazw, $imie);
		}else{
			echo '<BR> nazw i imie są PUSTE!';
		}
		//echo "<meta http-equiv='refresh' content='0'>";
		//echo $_POST['nazw'] . " " . $_POST['imie'] . " " . print_r($_POST['submit']);
		
		
	}

?>

<!DOCTYPE html>
<html lang="pl-PL">
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type"text/css" href="pbistyle.css">
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">

		<!-- jQuery library -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

		<!-- Popper JS -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"></script>

		<!-- Latest compiled JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
</head>
<body>
<div id="container">
	<div class="row">
		<div class="col-sm-12">
			<div id="menu">
					<ul>
						<li><a href="pbi.php">Powrót</a></li>
					</ul>
			</div>		
		</div>
	</div>
	<div class="row">
		<div class="col-sm-4">
		</div>
		<div class="col-sm-4">
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
				<div class="form-group">
					<label for="PracNazw">Nazwisko</label>
					<input type="text" class="form-control" id="PracNazw" name="nazw" placeholder="Podaj Nazwisko...">
				</div>
				<div class="form-group">
					<label for="PracNazw">Imię</label>
					<input type="text" class="form-control" id="PracImie" name="imie" placeholder="Podaj Imię...">
				</div>
				<div class="form-group">
					<button type="submit" name="submit" class="brn brn-primary mb-2">Wyślij</button>
				</div>
			</form>
		</div>
		<div class="col-sm-4">
		</div>
	</div>
</div>	
</div>
</body>
</html>