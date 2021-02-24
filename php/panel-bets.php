
<div class="content">
<?php
	if(!verificaAdmin($mysqli)){
		forzaLogout("");
	}
	if(!isset($_GET["ep"])){
		forzaLogout("php/");
	}
	if(!isset($_GET["l"])){
		$language = $lingue[0];
	}else{
		$language = filtra($_GET["l"],$mysqli);
	}
$ep=filtra($_GET["ep"],$mysqli);
if($ep==1) $epprec=10;
else $epprec=$ep-1;

if($ep==10) $epsucc=1;
else $epsucc=$ep+1;

?>

<div class="col-md-12 col-xs-12">
	<div class="col-md-6 col-md-offset-3 col-xs-10 col-xs-offset-1">
		<center><div class="row" id="flags-content">
			<?php
				foreach($lingue as $l){
					if($l!=$language){
						echo'<a href="panel-index.php?p=panel-bets&ep='.$ep.'&l='.$l.'"><img src="../img/flags/'.$l.'.png" class="flag"></a>';
					}else{
						echo'<a href="panel-index.php?p=panel-bets%ep='.$ep.'&l='.$l.'"><img src="../img/flags/'.$l.'.png" class="flag cur-flag"></a>';
					}
				}
			?>
		</div></center>
	</div>
</div>


<?php

echo '<center><div class="row scorearrows"><a href="panel-index.php?p=panel-bets&ep='.$epprec.'"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>';
echo " Episodio ".$ep;
echo ' <a href="panel-index.php?p=panel-bets&ep='.$epsucc.'"><i class="fa fa-arrow-right" aria-hidden="true"></i></a></div>';
?>




<center><h3>Inserisci una nuova scommessa per l'episodio <?php echo $ep; ?></h3></center>
<div class="row">
	<div class="col-md-4 col-md-offset-4">
		<?php
		echo'<form method="POST" action="php/panel-bets-process.php?action=0&ep='.$ep.'&l='.$language.'">';
		?>
			<center><input type="text" class="form-control" name="descrizione" placeholder="Descrizione evento">
			<input type="number" class="form-control" name="pv" placeholder="Punteggio se verificato">
			<input type="number" class="form-control" name="pnv" placeholder="Punteggio se non verificato"><br>
			<input type="number" class="form-control" name="associazione" placeholder="Associazione"><br>
			<input type="submit" class="btn btn-primary" value="Inserisci"><br>
		<?php
			echo"<a href='php/panel-bets-process.php?action=4&ep=".$ep."&l=".$language."'><input type='button' class='btn btn-primary' value='Concatena documento'></a>";
		?>
		</center>
		</form>
	</div>
</div>
<?php
if($_COOKIE["admin"]>2){
	$applyScores="'applyScores'";
	echo'<br>
    <center><i class="fa fa-lock" onclick="unlock(this,'.$applyScores.');"  aria-hidden="true"></i><br>
    <a href="php/panel-bets-process.php?action=3&ep='.$ep.'&l='.$language.'"> <button class="btn btn-success" id="applyScores" disabled>APPLICA</button></a></center><br>';
}

echo'<div class="table-responsive"><table id="tablerank" class="table sortable text-center tableranking">';
echo"<thead><tr><th>ID</th><th>Descrizione</th><th>Punteggio se verificato</th><th>Punteggio se non verificato</th><th>Associazione</th><th>Azioni</th></tr></thead>";

$query="SELECT * FROM scommesse WHERE lingua='".$language."' AND episodio=".$ep;

$result=$mysqli->query($query);
echo "<center><h3>Scommesse create</h3></center>";
	while($row=$result->fetch_assoc())
	{
	 if(!isset($row["verificato"]))		
	 	$class="rowtableranking2";
	 else if($row["verificato"]==0)
	 	$class="rowtablerankingred";
	 else
	 	$class="rowtablerankinggreen";

       echo "<tr class='".$class."'><td>".$row['id']."</td><td>".$row['descrizione']."</td><td>".$row['punteggioVerificato']."</td><td>".$row['punteggioNonVerificato']."</td><td>".$row['associazione']."</td>";
        echo'<td><a href="php/panel-bets-process.php?action=1&id='.$row['id'].'&ep='.$ep.'&l='.$language.'"><input type="button" value="Elimina" class="btn btn-warning"></a>
       		 <a href="php/panel-bets-process.php?action=2&id='.$row['id'].'&s=1&ep='.$ep.'&l='.$language.'"><input class="btn btn-success" type="button" value="Verificato"></a>
       		 <a href="php/panel-bets-process.php?action=2&id='.$row['id'].'&s=0&ep='.$ep.'&l='.$language.'"><input class="btn btn-danger" type="button" value="Non verificato"></a>
       		 <a href="php/panel-bets-process.php?action=2&id='.$row['id'].'&s=null&ep='.$ep.'&l='.$language.'"><input class="btn btn-primary" type="button" value="Invalida"></a>
        </td></tr>';

	}
echo "</table></div>";

?>

<?php
$opzioni = 0;
$descrizione = "";
$punteggio = 0;
$query = "SELECT *,COUNT(*) as numero FROM scommessestagionali WHERE lingua ='".$language."'";
$row = estrai($query,$mysqli);
if($row["numero"]>0){
	$descrizione = $row["descrizione"];
	$punteggio = $row["punteggio"];
	$query = "SELECT COUNT(*) as opzioni FROM opzioniscommessestagionali WHERE scommessa = ".$row['id'];
	$row = estrai($query,$mysqli);
	$opzioni = $row["opzioni"];
}

?>
<center><h3>Gestisci scommessa stagionale</h3></center>
<div class="row">
	<div class="col-md-4 col-md-offset-1">
	<?php
	    echo "<center><h4>Imposta la scommessa</h4></center>";
		echo'<form method="POST" action="php/panel-bets-process.php?action=5&ep='.$ep.'&l='.$language.'">';
	
		if($opzioni > 0){ // è già presente una scommessa stagionale
			$query = "SELECT id FROM scommessestagionali WHERE lingua ='".$language."'";
			$row = estrai($query,$mysqli);
			$idScommessa = $row["id"];

			$query = "SELECT *, S.id as idScommessa FROM scommessestagionali S INNER JOIN opzioniscommessestagionali OS ON OS.scommessa = S.id WHERE S.lingua ='".$language."'";
			$result=$mysqli->query($query);
			echo '<input type="text" class="form-control" name="descrizione" placeholder="Descrizione evento" value="'.$descrizione.'"><br>
		    <input type="number" class="form-control" name="punteggio" placeholder="Punteggio se verificato" value="'.$punteggio.'"><br>';
		    echo '<div id="dynamicInput">';
			for($i=1; $row=$result->fetch_assoc(); $i++){
				echo "<input type='number' class='col-md-2' name='id".$i."' value='".$row["id"]."' readonly>";
				echo '<input type="text" class="col-md-8" name="opzione'.$i.'" value="'.$row["opzione"].'">';
				echo '<input type="number" class="col-md-2" name="associazione'.$i.'" value="'.$row["associazione"].'">';
				echo '<br><br>';
			}
			echo '</div><br>
			<input type="hidden" value="'.$opzioni.'" id="opzioni" name="opzioni">
			<input type="button" class="btn" value="+" onclick="addInput();" /><br><br>
		 	<input type="submit" class="btn btn-primary" value="Aggiorna"><br><br>';
		 	echo '<a href="php/panel-bets-process.php?action=6&id='.$idScommessa.'&ep='.$ep.'&l='.$language.'"> <input type="button" class="btn btn-danger" value="Elimina"></a>';
		}else{ // non è presente una scommessa stagionale
			echo '<input type="text" class="form-control" name="descrizione" placeholder="Descrizione evento"><br>
		    <input type="number" class="form-control" name="punteggio" placeholder="Punteggio se verificato"><br>
		    <input type="hidden" value="0" id="opzioni" name="opzioni">
		    <div id="dynamicInput"></div><br>
		    <input type="button" class="btn" value="+" onclick="addInput();" /><br><br>
		 	<input type="submit" class="btn btn-primary" value="Inserisci"><br>';
		}
		echo "</form>";

	?>
	</div>
	<div class="col-md-offset-2 col-md-4">
		<?php
			if($opzioni>0){ // è già presente una scommessa stagionale
				echo "<center><h4>Imposta le risposte corrette</h4></center>";
				echo "<form method='POST' action='php/panel-bets-process.php?action=7&ep=".$ep."&l=".$language."'>";
				echo "<input type='hidden' name='opzioni' value='".$opzioni."'>";
				$query = "SELECT * FROM scommessestagionali S INNER JOIN opzioniscommessestagionali OS ON OS.scommessa = S.id WHERE S.lingua ='".$language."'";
				$result=$mysqli->query($query);
				echo "<div class='table-responsive'><table class='table text-center tableranking'><thead><tr><th>OPZIONE</th><th>CORRETTA</th><th>NON CORETTA</th></tr></thead>";
				for($i=1; $row=$result->fetch_assoc(); $i++){
					echo '<tr class="custom-control custom-radio"><td>'.$row["opzione"].'</td>';
					$checkedT = ($row["corretta"]==1)?"checked":"";
					$checkedF = ($row["corretta"]==0)?"checked":"";
					echo '<div class="btn-group"><td><label class="btn btn-success" ><input type="radio" class="custom-control-input" name="corretta'.$i.'" value="1" '.$checkedT.'></label></td>';
					echo '<td><label class="btn btn-danger"><input type="radio" class="custom-control-input" name="corretta'.$i.'" value="0" '.$checkedF.'></label></td></div></tr>';
					echo '<input type="hidden" name="id'.$i.'" value="'.$row["id"].'"></div>';
				}
				echo "</table></div>";
				echo "<br><input type='submit' class='btn btn-primary' value='Aggiorna'></form>";
				$applySeason="'applySeason'";
				echo'<br>
				    <center><i class="fa fa-lock" onclick="unlock(this,'.$applySeason.');"  aria-hidden="true"></i><br>
				    <a href="php/panel-bets-process.php?action=8&ep='.$ep.'&l='.$language.'"> <button class="btn btn-success" id="applySeason" disabled>APPLICA</button></a></center><br>';
			}
		?>
	</div>
</div>


<script>


function addInput() {
	var opzioni = parseInt($("#opzioni").val()) + 1;
    var testo = $("<input/>").attr("type", "text").attr("name","opzione"+opzioni).attr("placeholder","Testo");
    var associazione = $("<input/>").attr("type", "number").attr("name","associazione"+opzioni).attr("placeholder","Associazione");
    var br = $("<br>");
    $("#dynamicInput").append(testo);
    $("#dynamicInput").append(associazione);
    $("#dynamicInput").append(br);
    $("#opzioni").attr("value", opzioni);
}
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