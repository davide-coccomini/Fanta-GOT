
<div class="container">
<div class="bg-blue alert alert-info  col-md-10 col-md-offset-1">
<center>
	<h2><?php 
		$query="SELECT status FROM aperture WHERE soggetto='schieramento' AND programmato IS NULL ORDER BY timestamp DESC LIMIT 1";
		$riga=estrai($query,$mysqli);
		$status=$riga["status"];
		echo $arenaTitle;
		if($status!=0) 
            echo "<h3 class='red-text subtitle'>(".$closedText.")</h3>";
            
        $disabled = ($status != 0) ? "disabled":"";
	?></h2>
	<p class="testo16">
	<?php echo $arenaSubTitle ?>
	</p>
</center><br><br>

<?php
$sfidaAccettata = 0;
$query = "SELECT * FROM sfide WHERE (sfidante = '".$_COOKIE['username']."' OR sfidato = '".$_COOKIE['username']."') AND status IS NOT NULL AND status <> 4";
$row = estrai($query,$mysqli);
if(isset($row["status"])){
    $regolaSfidante = $regolaSfidato = $sfidato = $sfidante = "";
    if(isset($row["sfidato"])) $sfidato = $row["sfidato"];
    if(isset($row["sfidante"])) $sfidante = $row["sfidante"];
    if(isset($row["regolaSfidato"])) $regolaSfidato = $row["regolaSfidato"];
    if(isset($row["regolaSfidante"])) $regolaSfidante = $row["regolaSfidante"];

    if(($row['sfidato'] == $_COOKIE['username'] && $row['status'] == 1)||($row['sfidante'] == $_COOKIE['username'] && $row['status'] == 2)){
        echo '<div class="col-md-12 text-center">
                <div id = "arena-rules-container">
                <div class="alert alert-info alert-arena col-md-6 col-md-offset-3 col-xs-12"><div class="text-white">'.$arenaLabel2.'</div><br></div>';
        if(isset($row['sfidante'])){
            if(($row['sfidante'] == $_COOKIE['username'] && $row['status'] == 2)){
                $regola = $row["regolaSfidato"];
                $query = "SELECT nome FROM vociregolamento WHERE associazione = ".$regola." AND lingua = '".$lingua."'";
                $row = estrai($query,$mysqli);
                echo "<div class='alert alert-info alert-arena col-md-6 col-xs-12 col-md-offset-3'><div class='text-white'>".$arenaLabel3.": <b>".$row['nome']."</b></div><br></div>";
            }
        }
        if(isset($row['sfidato'])){
            if(($row['sfidato'] == $_COOKIE['username'] && $row['status'] == 3)){
                $regola = $row["regolaSfidante"];
                $query = "SELECT nome FROM vociregolamento WHERE associazione = ".$regola." AND lingua = '".$lingua."'";
                $row = estrai($query,$mysqli);
                echo "<div class='alert alert-info alert-arena col-md-6 col-xs-12'><div class='text-white'>".$arenaLabel3.": <b>".$row['nome']."</b></div><br></div>";
            }
        }
        
        $query = "SELECT * FROM vociregolamento WHERE lingua ='".$lingua."' AND arena = 1";
        $result=$mysqli->query($query);
        echo'<form action="../php/arena-process.php?action=1" method="POST" class="col-md-12">';
        echo'<div class="table-responsive"><table id="tablerank" class="table sortable text-center tableranking">';
        echo"<thead><tr><th>".$arenaTableTitle1."</th><th>".$arenaTableTitle2."</th><th>".$arenaTableTitle3."</th><th>".$arenaTableTitle4."</th></tr></thead>";
            while($row=$result->fetch_assoc())
            {
             if(($sfidante == $_COOKIE["username"] && $regolaSfidato == $row["associazione"])||($sfidato == $_COOKIE["username"] && $regolaSfidante == $row["associazione"])) 
                continue; // Salta la regola che ha già scelto l'altro giocatore
              
             echo "<tr class='rowtableranking2'><td>".$row['nome']."</td><td>".$row['dettagli']."</td><td>".round($row['punteggioMassimo']/2)."</td>";   
             echo'<td><input type="radio" name="rule" id="radio'.$row["id"].'" value="'.$row["associazione"].'"><label for="radio'.$row['id'].'"></label></td>';
             echo '</tr>';
            }
        echo "</table></div>";
        echo '</div>
        </div>';
    }   
}

?>
<div class="col-md-6 col-md-offset-3 text-center">
  <?php
    $query = "SELECT *, COUNT(*) as numero FROM sfide WHERE (sfidante='".$_COOKIE['username']."' OR sfidato = '".$_COOKIE['username']."') AND (status <> 4 OR status IS NULL)";
    $row = estrai($query,$mysqli);
    if($row["numero"] == 0) // L'utente deve rendersi disponibile
        echo'<a href="../php/arena-process.php?action=0"><button class="btn btn-success" '.$disabled.'>'.$arenaButton1.'</button></a>';
    else if(($row['sfidante'] == $_COOKIE['username'] && $row['status'] == 2)||($row['sfidato'] == $_COOKIE['username'] && $row['status'] == 1)){ // Sfidante o sfidato deve scegliere la regola
        echo'<input type="submit" class="btn btn-success" value="'.$arenaButton3.'"></form> ';
        echo'<a href="../php/arena-process.php?action=0"><button class="btn btn-warning btn-arena" '.$disabled.'>'.$arenaButton2.'</button></a>'; 
    }else if($row["status"] == 3){ // Sfidante e sfidato hanno scelto la regola
        echo '<div class="row"><div class="alert alert-info alert-arena col-md-12 col-xs-12"><center><div class="text-white">'.$arenaLabel4.'<br></div></center><br></div></div>';
        $query = "SELECT * FROM sfide WHERE (sfidante = '".$_COOKIE['username']."' OR sfidato = '".$_COOKIE['username']."') AND status IS NOT NULL AND status <> 4";
        $row = estrai($query,$mysqli);
        if(isset($row["regolaSfidante"]) && isset($row["regolaSfidato"])){
            echo "<div class='row'>";
            $sfidato = $row["sfidato"];
            $sfidante = $row["sfidante"]; 
            $regolaSfidato = $row["regolaSfidato"];
            $regolaSfidante = $row["regolaSfidante"];
            echo'<div class="table-responsive"><table id="tablerank" class="table sortable text-center tableranking">';
            echo'<tr><th>'.$arenaTableTitle5.'</th><th>'.$arenaTableTitle6.'</th><th>'.$arenaTableTitle7.'</th><th>'.$arenaTableTitle8.'</th></tr>';
            $query = "SELECT nome,punteggioMassimo FROM vociregolamento WHERE associazione = ".$regolaSfidato." AND lingua = '".$lingua."'";
            $row = estrai($query,$mysqli);
            $punteggio = round($row["punteggioMassimo"]/2);
            $clan = ottieniClan($sfidato, $mysqli);
            echo "<tr  class='rowtableranking2'><td>".$sfidato."</td> <td>".$clan."</td> <td>".$row['nome']."</td> <td>+".$punteggio."</td></b></td></tr><br>";

            $query = "SELECT nome,punteggioMassimo FROM vociregolamento WHERE associazione = ".$regolaSfidante." AND lingua = '".$lingua."'";
            $row = estrai($query,$mysqli);
            $punteggio = round($row["punteggioMassimo"]/2);
            $clan = ottieniClan($sfidante, $mysqli);
            echo "<tr class='rowtableranking2'><td>".$sfidante."</td> <td>".$clan."</td> <td>".$row['nome']."</td> <td>+".$punteggio."</td></b></td></tr><br>";
            echo "</table></div>";
            echo "</div>";
        }
        
        echo'<a href="../php/arena-process.php?action=0"><button class="btn btn-warning btn-arena" '.$disabled.'>'.$arenaButton2.'</button></a>';
    }else if($row['sfidato'] == $_COOKIE['username'] && $row['status'] == 2){ // Lo sfidante sta scegliendo la regola quindi lo sfidato deve aspettare
        $query = "SELECT nome,punteggioMassimo FROM vociregolamento WHERE lingua ='".$lingua."' AND associazione = '".$row['regolaSfidato']."'";
        $row = estrai($query,$mysqli);
        $punteggio = round($row["punteggioMassimo"]/2);
        echo '<div class="alert alert-info alert-arena col-md-12 col-xs-12"><center><div class="text-white">'.$arenaLabel5.': <b>'.$row["nome"].' (+'.$punteggio.')</b>.<br>'.$arenaLabel6.'</div></center><br></div>';
        echo'<a href="../php/arena-process.php?action=0"><button class="btn btn-warning btn-arena" '.$disabled.'>'.$arenaButton2.'</button></a>';
    }else{ // Nessuno dei due ha scelto la regola
        echo '<div class="alert alert-info alert-arena col-md-12 col-xs-12"><center><div class="text-white">'.$arenaLabel1.'</div></center><br></div>';
        echo'<a href="../php/arena-process.php?action=0"><button class="btn btn-warning btn-arena" '.$disabled.'>'.$arenaButton2.'</button></a>';
    }
   ?>
   <br><br>
   </div>
</div>
</div>





