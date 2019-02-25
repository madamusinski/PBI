<?php
    session_start();

    

  

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

    $nameErr = "";
    $name = "";
    $wybor = "NAZW";

    

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
           $wybor = "NAZW";
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
                <li><a href="dodajprac.php">Dodaj pracownika</a></li>
                <li><a href="upowaznienia.php">Przeglądaj upoważnienia</a></li>
                <li><a href="login.php?out=1">Wyloguj</a></li>
            </ul>
    </div>
    <div id="search">
        
        <ul>
            <li><form action="" method="post"> 
                <input type="text" name="name" autocomplete="off" value="<?php echo $name;?>">
                <span class="error"> <?php echo $nameErr;?></span>
                <input type="submit" name="submit" value="Szukaj">

                <label>Szukaj po</label>
                <select name="filtr" id="filtr" action="">
                    <option value="NAZW" <?php if(isset($_POST['filtr']) && ($_POST['filtr'] == 'NAZW')) { echo 'selected="selected"';}?>>Nazwisko</option>
                    <option value="IMIE" <?php if(isset($_POST['filtr']) && ($_POST['filtr'] == 'IMIE')) { echo 'selected="selected"';}?>>Imię</option>
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
   



include_once 'DB.php';
require_once 'Connection.php';
require_once 'User.php';

$db_connection = new DB();
$db_connection = $db_connection->getDB();
//$db = new Connection();
//$db_connection = new User();
//var_dump($db_connection->db);
//var_dump(Connection::$connection);

    $sql = "SELECT * FROM V_PRAC WHERE " . $wybor . " like '" . $name . "%'";
   
	$sth = $db_connection->prepare($sql);
	$sth->setFetchMode(PDO::FETCH_ASSOC);
	$sth->execute();

    echo "<table>";
	
    echo "<tr><th></th><th>ID prac</th><th>Nazwisko</th><th>Imie</th><th>Ilość upoważnień";
  
    while($row = $sth->fetch()){
        echo "<tr><td>";
		echo "<a href=dodajupow.php?ID=" .$row['ID'] . ">
			<img src=\"https://img.icons8.com/color/48/000000/add-property.png\">
		</a>";
		echo "</td><td>";
        echo $row['ID'];
        echo "</td><td>";
        echo $row['NAZW'];
        echo "</td><td>";
        echo $row['IMIE'];
        echo "</td><td>";
        echo $row['IL_UPOWAZNIEN'];
		echo "</td></tr>";
    }
    echo "</table>";

?>
</body>
</html>




