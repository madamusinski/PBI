<?php
    $wybor = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if(isset($_POST['filtr'])){
                $wybor = filter_var($_POST['filtr'], FILTER_SANITIZE_STRING);
            }
        }
?>