<?php
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

?>

<!DOCTYPE html>
<html lang="pl-PL">
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type"text/css" href="pbistyle.css">
</head>
<body>
<?php
//include 'filtr.php';
    $nameErr = "";
    $name = "";
    $wybor = "PNAZW";

    

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
       if (empty($_POST["name"])) {
         $nameErr = "Name is required";
       } 
       else {
         $name = test_input($_POST["name"]);
         if (!preg_match("/^[%a-zA-ZąćęłńóśźżĄĆĘŁŃÓŚŹŻ\- ]*$/",$name)) {
           $nameErr = "Only letters and white space allowed"; 
         }
       }
       if(empty($_POST["filtr"])){
           $wybor = "PNAZW";
       }
       else{
           $wybor = $_POST["filtr"];
       }
    }


    function test_input($data) {
       $data = trim($data);
       $data = stripslashes($data);
       $data = htmlspecialchars($data);
       return $data;
    }
  
    

?>
<div id="container">
    <div id="menu">
            <ul>
                <li><a href="pbi.php">Przeglądaj pracowników</a></li>
                <li><a href="login.php?out=1">Wyloguj</a></li>
            </ul>
    </div>
    <div id="search">
        
        <ul>
            <li><form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post"> 
                <input type="text" name="name" autocomplete="off" value="<?php if(isset($_POST['submit'])){if(!empty($_POST['name'])){echo $name;}}?>"> <!-- uzupelniac po odswiezeniu ozna $_SESSION -->
                <span class="error"> <?php echo $nameErr;?></span>
                <input type="submit" name="submit" value="Szukaj">

                <label>Szukaj po</label>
                <select name="filtr" id="filtr" action="">
                    <option value="PNAZW" <?php if(isset($_POST['filtr']) && ($_POST['filtr'] == 'PNAZW')) { echo 'selected="selected"';} ?>>Nazwisko</option>
                    <option value="PIMIE" <?php if(isset($_POST['filtr']) && ($_POST['filtr'] == 'PIMIE')) { echo 'selected="selected"';} ?>>Imię</option>
                    <option value="STANOWISKO" <?php if(isset($_POST['filtr']) && ($_POST['filtr'] == 'STANOWISKO')) { echo 'selected="selected"';} ?>>Stanowisko</option>
                    <option value="JEDNOSTKA" <?php if(isset($_POST['filtr']) && ($_POST['filtr'] == 'JEDNOSTKA')) { echo 'selected="selected"';} ?>>Jednostka</option>
                </select>
            </form></li>
            <script>
            document.getElementById("filtr".value = "<?php echo $POST["filtr"]?>");
        </script>
            <li>

            </li>
            <li style="color:white">
               <?php echo "Witaj ".$_SESSION['user'];?>
            </li>
            
        </ul>
    </div>
</div>
<?php 
   
//var_dump($_POST['filtr']);
//  echo (isset($_POST['filtr']) && ($_POST['filtr'] == "PIMIE") ? " selected=\"selected\"" :'');

//reporting errors 
/*
if(isset($wybor)){
    echo "$wybor" . "<br>";
}
else{
    echo "dupa" . "<br>";
}
error_reporting(E_ALL);
ini_set('display_errors', 1);

*/



include_once 'DB.php';

$db_connection = new DB();
$db_connection = $db_connection->getDB();
	
	//print_r($name);
    $sql = "SELECT * FROM V_UPOW_2 WHERE " . $wybor . " like '" . $name . "%'";
    //$sql = "SELECT * FROM V_UPOW_2 WHERE $wybor like '$name%'";
    //$sqlquery = oci_parse($db_connection, $zapytanko);
    //oci_execute($sqlquery);
	$sth = $db_connection->prepare($sql);
	$sth->setFetchMode(PDO::FETCH_ASSOC);
	$sth->execute();
	//print_r($sth->fetch());
    /*
    echo "<table border='1'>\n";
    echo "<tr><td>" . 'cos tam' . "</td></tr></table>";
    while ($row = oci_fetch_array($sqlquery, OCI_ASSOC+OCI_RETURN_NULLS)) {
        echo "<tr>\n";
        foreach ($row as $item) {
            echo "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
        }
        echo "</tr>\n";
    }
    echo "</table>\n"; */
    echo "<table>";
	//$db_connection->pokazPracID();
    echo "<tr><th></th><th>ID upraw</th><th>ID prac</th><th>Nazwisko</th><th>Imie</th><th>Stanowisko</th><th>Jednostka</th>
    <th>Data odbr upr</th><th>Data od</th><th>Data do</th><th>KSM ODDZ</th><th>KSM IP</th><th>KSM BO</th>
	<th>KSM BP</th><th>KSS TER</th><th>KSS GL</th><th>KSS RIS REJ</th><th>KSS RIS OPIS</th><th>KSS REH</th>
	<th>KS AO</th><th>KS MT</th><th>KS MZ</th><th>KS FKW</th><th>KS ESM</th><th>KSZ KAD</th><th>KSZ PLA</th>
	<th>KSZ GRA</th><th>KS PPS</th><th>KS AKM</th><th>INNE</th>
    <th>Domena</th><th>Cel</th><th>Zgoda</th><th>Zakres upr</th><th>UWAGI</th>";
    //$row = oci_fetch_all($sqlquery, OCI_ASSOC+OCI_RETURN_NULLS + OCI_FETCHSTATEMENT_BY_ROW)
	//var_dump($sth->fetchAll(PDO::FETCH_ASSOC));
    while($row = $sth->fetch()){
        echo "<tr><td>";
		echo "<a href=dodajupow.php?ID=" .$row['ID'] . ">
			<img src=\"https://img.icons8.com/color/48/000000/multi-edit.png\">
		</a>";
		echo "</td><td>";
        echo $row['ID'];
        echo "</td><td>";
		echo $row['PID'];
		echo "</td><td>";
        echo $row['PNAZW'];
        echo "</td><td>";
        echo $row['PIMIE'];
        echo "</td><td>";
        echo $row['STANOWISKO'];
        echo "</td><td>";
        echo $row['JEDNOSTKA'];
        echo "</td><td>";
        echo $row['DATAODBRUPR'];
        echo "</td><td>";
        echo $row['DATAOD'];
        echo "</td><td>";
        echo $row['DATADO'];
        echo "</td><td>";
        echo $row['KSMODDZIAŁ'];
        echo "</td><td>";
        echo $row['KSMIP'];
        echo "</td><td>";
        echo $row['KSMBO'];
        echo "</td><td>";
		echo $row['KSMBP'];
		echo "</td><td>";
		echo $row['KSSTER'];
		echo "</td><td>";
		echo $row['KSSGL'];
		echo "</td><td>";
		echo $row['KSSRISREJ'];
		echo "</td><td>";
		echo $row['KSSRISOPIS'];
		echo "</td><td>";
		echo $row['KSSREH'];
		echo "</td><td>";
		echo $row['KSAO'];
		echo "</td><td>";
		echo $row['KSMT'];
		echo "</td><td>";
		echo $row['KSMZ'];
		echo "</td><td>";
		echo $row['KSFKW'];
		echo "</td><td>";
		echo $row['KSESM'];
		echo "</td><td>";
		echo $row['KSZKAD'];
		echo "</td><td>";
		echo $row['KSZPLA'];
		echo "</td><td>";
		echo $row['KSZGRA'];
		echo "</td><td>";
		echo $row['KSPPS'];
		echo "</td><td>";
		echo $row['KSAKM'];
		echo "</td><td>";
		echo $row['INNE'];
		echo "</td><td>";
        echo $row['DOMENA'];
        echo "</td><td>";
        echo $row['CEL'];
        echo "</td><td>";
        echo $row['ZGODA'];
        echo "</td><td>";
        echo $row['ZAKRESUPRAWNIENIA'];
        echo "</td><td>";
		echo $row['UWAGI'];
		echo "</td></tr>";
        
        
    }
    echo "</table>";

?>
</body>
</html>




