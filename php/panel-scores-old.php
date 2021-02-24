 <div class="col-md-12 logscontainer">


<?php
if(!verificaAdmin($mysqli)){
		forzaLogout("php/");
	}


if(!isset($_GET["ep"])){
	forzaLogout("php/");
}
$ep=filtra($_GET["ep"],$mysqli);
if($ep==1) $epprec=10;
else $epprec=$ep-1;

if($ep==10) $epsucc=1;
else $epsucc=$ep+1;
$resetScores="'resetScores'";
$applyScores="'applyScores'";
echo '<center><div class="row scorearrows"><a href="panel-index.php?p=panel-scores&ep='.$epprec.'"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>';
echo " Episodio ".$ep;
echo ' <a href="panel-index.php?p=panel-scores&ep='.$epsucc.'"><i class="fa fa-arrow-right" aria-hidden="true"></i></a></div><center><i class="fa fa-lock" onclick="unlock(this,'.$resetScores.');"  aria-hidden="true"></i></center> 
<center><a href="php/panel-scores-process.php?action=2&ep='.$ep.'"><button class="btn btn-danger" id="resetScores" disabled>RESET</button></a></center>';

if($_COOKIE["admin"]>2){
	echo'<br>
    <center><i class="fa fa-lock" onclick="unlock(this,'.$applyScores.');"  aria-hidden="true"></i><br>
    <a href="php/panel-scores-process.php?action=3&ep='.$ep.'"> <button class="btn btn-success" id="applyScores" disabled>APPLICA</button></a></center>';
}
echo'<br><a href="php/panel-scores-process.php?action=4&ep='.$ep.'"><button class="btn btn-primary" id="generateScoreDocument">Sovrascrivi documento</button></a><a href="php/panel-scores-process.php?action=6&ep='.$ep.'"><button class="btn btn-primary" id="generateScoreDocument">Concatena documento</button></a></center>';
echo "<form></form>";

$queryDaEseguire="SELECT * FROM personaggi ORDER BY prezzo DESC";
$result=$mysqli->query($queryDaEseguire);
$row=$result->fetch_assoc();
            do{
            	$query="SELECT * FROM punteggipersonaggi WHERE episodio=".$ep." AND personaggio='".$row['id']."'";
            	$riga=estrai($query,$mysqli);
            	if(!$riga) $action=0; //inserisci
            	else $action=1; //aggiorna

                $id=$row["id"];
                $percorso=$row["percorso"];
                $nome=$row["nome"];
                if($action==0){
                    echo '<form method="POST" action="php/panel-scores-process.php?action=0"><div class="panel-scores-box col-lg-3 col-md-3 col-sm-3 col-xs-6 filter hdpe">';
                    echo '<img src="img/personaggi/'.$percorso.'" class="col-lg-offset-4 col-md-offset-2 col-sm-offset-2 col-xs-offset-1 img-responsive" id="img-not-buyed">';
                    echo '<div class="pgname text-center">'.$nome.'</div>';
                    echo '<input type="hidden" name="pg" value="'.$id.'">';
                    echo '<input type="hidden" name="ep" value="'.$ep.'">';
                    echo '<center><textarea class="col-md-12" name="dettagli" placeholder="Dettagli"></textarea><br><input type="number" class="col-md-offset-1 col-md-10 col-xs-12" min="-500" max="500" name="punteggio" placeholder="Nessun punteggio"></center><br><br>';
                    echo '<input type="submit" class="btn btn-buy" value="INSERISCI">';
                    echo '</div></form>';
                }else{
				echo '<form method="POST" action="php/panel-scores-process.php?action=1"><div class="panel-scores-box col-lg-3 col-md-3 col-sm-3 col-xs-6  filter hdpe">';
                    echo '<img src="img/personaggi/'.$percorso.'" class="col-lg-offset-4 col-md-offset-3 col-sm-offset-2 col-xs-offset-1 img-responsive" id="img-market">';
                    echo '<div class="pgname text-center">'.$nome.'</div>';
                    echo '<input type="hidden" name="pg" value="'.$id.'">';
                    echo '<input type="hidden" name="ep" value="'.$ep.'">';
                    echo '<center><textarea class="col-md-12" name="dettagli">'.$riga["dettagli"].'</textarea><br><input type="number" class="col-md-offset-1 col-md-10 col-xs-12" min="-500" max="500" name="punteggio" value="'.$riga["punteggio"].'"></center><br><br>';
                    echo '<input type="submit" class="btn btn-buy" value="AGGIORNA">';
                    echo '</div></form>';
                }
               }while($row=$result->fetch_assoc());

?>
</div>

<script>
function unlock(locker,id){
    var target=document.getElementById(id);
    target.disabled=!target.disabled;
    if(locker.className=="fa fa-lock"){
        locker.className="fa fa-unlock-alt";
    }else{
        locker.className="fa fa-lock";
    }
}

</script>