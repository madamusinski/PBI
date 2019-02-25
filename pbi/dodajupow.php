<?php
require 'DB.php';
$db = new DB();
$test = $db->getDB();
//var_dump($test instanceof PDO);

    session_start();

    //$t=time();

  

    if(!isset($_SESSION["user"])){
        header("Location: login.php");
        die();
    }else{
        /*if(isset($_GET['logged']) && trim($_GET['logged']=='1')){
            echo"<script> alert('Twoim administratorem danych osobowych jest Wojewódzki Szpital Specjalistyczny Nr 2 w Jastrzębiu-Zdrój');</script>";
        }*/
        if((time() - $_SESSION['last_login_timestamp']) > 1200){
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
	
	$prac = $db->pokazPracownika($_GET['ID']);
	$itemsCel = array();
	$itemsCel = $db->populateCel();
	//print_r($itemsCel);
	
	//print_r($dejta);
	//print_r(isset($_GET['ID'];
	$imie = $prac['IMIE'];
	$nazw = $prac['NAZW'];
	if(isset($_POST['submit'])){
		//echo "<meta http-equiv='refresh' content='0'>";
		//echo $_POST['nazw'] . " " . $_POST['imie'] . " " . print_r($_POST['submit']);
		$prac = "";
		$imie = "";
		$nazw = "";
		print_r($_POST['id']);
		print_r($_POST['nazw']);
		print_r($_POST['imie']);
		
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
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		<div class="row">
			<div class="col-sm-4">
				<div class="form-row">
					<div class="form-group col-sm-2">
						<label for="PracID">ID</label>
						<input type="text" class="form-control" id="PracID" name="id" value=" <?php echo $_GET['ID'];?>" readonly>
					</div>
					<div class="form-group col-sm-10">
						<label for="PracImie">Imie</label>
						<input type="text" class="form-control" id="PracImie" name="imie" value=" <?php echo $imie;?>" readonly>
					</div>
				</div>
				<div class="form-group">
					<label for="PracNazw">Nazwisko</label>
					<input type="text" class="form-control" id="PracNazw" name="nazw" value=" <?php echo $nazw;?>" readonly>
				</div>
				<div class="form-row">
					<div class="form-group col-sm-4">
						<label for="UpowCel">Cel</label>
						<select class="form-control" id="UpowCel" name="cel">
							<option selected>BRAK</option>
							<?php
							$itemsCel = array();
							$itemsCel = $db->populateCel();
								foreach($itemsCel as $item){
									printf('<option value="%d">%s</option>', $item['ID'], $item['NAZW']);
								}
							?>
						</select>
					</div>
					<div class="form-group col-sm-4">
						<label for="PracImie">Imie</label>
						<input type="text" class="form-control" id="PracImie" name="imie" value=" <?php echo $imie;?>" readonly>
					</div>
					<div class="form-group col-sm-4">
						<label for="PracImie">Imie</label>
						<input type="text" class="form-control" id="PracImie" name="imie" value=" <?php echo $imie;?>" readonly>
					</div>
				</div>
				
			</div>
		<div class="col-sm-4">
			
				<div class="form-group">
					<label for="PracNazw">Nazwisko</label>
					<input type="text" class="form-control" id="PracNazw" name="nazw1" placeholder="Podaj Nazwisko..." autocomplete="off">
				</div>
				<div class="form-group">
					<label for="PracNazw">Imię</label>
					<input type="text" class="form-control" id="PracImie" name="imie1" placeholder="Podaj Imię...">
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