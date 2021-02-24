<?php
    include("../config.php");
    include("../utility.php");
    $array = array();
    $action = filtra($_POST["action"],$mysqli);
    $lingua = filtra($_POST["lingua"],$mysqli);
    if($action == 0){
        $query = "SELECT * FROM users U WHERE U.lingua = '".$lingua."' AND U.email NOT IN (SELECT UV.email FROM users_verification UV WHERE UV.status=2)";
        $result=$mysqli->query($query);
        while($row=$result->fetch_assoc()){
            $array[] = $row["email"];
        }
        echo json_encode($array); 
    }else if($action == 1){
        $min = filtra($_POST["min"],$mysqli)-1;
        $max = filtra($_POST["max"],$mysqli);
        $limit = $max-$min;

        $query = "SELECT * FROM users U WHERE U.lingua = '".$lingua."' AND U.email NOT IN (SELECT UV.email FROM users_verification UV WHERE UV.status=2) LIMIT ".$limit." OFFSET ".$min;
        $result=$mysqli->query($query);
        while($row=$result->fetch_assoc()){
            $array[] = $row["email"];
        }
        $quantita = $max-$min;
        $query = "INSERT INTO newsletters(numero) VALUES(".$quantita.")";
        esegui($query,$mysqli);
        inviaLog("[ADMIN]Seleziona email", $min."-".$max,$mysqli);
        echo json_encode($array); 
    }
?>