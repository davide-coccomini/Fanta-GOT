
<div class="row">
<div class="text-white bg-blue alert alert-info col-md-6 col-md-offset-3">
<center>
	<h2><?php 
		$query="SELECT status FROM aperture WHERE soggetto='schieramento' AND programmato IS NULL ORDER BY timestamp DESC LIMIT 1";
		$riga=estrai($query,$mysqli);
		$status=$riga["status"];
		echo $betsTitle1;
		if($status!=0) 
			echo "<h3 class='red-text subtitle'>(".$closedText.")</h3>";
	?></h2>
	
	<p class="testo16">
	<?php echo $betsDescription1 ?>
	</p>
</center><br><br>
<div class="table-responsive">
<?php






$query = "SELECT *, COUNT(*) as numero FROM scommessestagionali WHERE lingua='".$lingua."'";
$row = estrai($query,$mysqli);

$descrizioneScommessaStagionale = $row["descrizione"];
$punteggioScommessaStagionale = $row["punteggio"];
$idScommessaStagionale = $row["id"];
if($row["numero"]>0){
	echo "<center><h3>".$betsTitle3."</h3></center>";
	echo'<form method="POST" action="../php/bets-process.php?action=2"><table id="tablerank" class="table sortable text-center tableranking">';
	echo"<thead><tr><th>".$betsTableLabel1."</th><th>".$betsTableLabel2."</th><th>".$betsTableLabel5."</th><th>".$betsTableLabel4."</th>";
	echo "<tr class='rowtableranking2'><td>".$descrizioneScommessaStagionale."</td><td>".$punteggioScommessaStagionale."</td>";

	$query = "SELECT * FROM opzioniscommessestagionali WHERE scommessa = ".$idScommessaStagionale;
	$result=$mysqli->query($query);

	echo "<td><div class='col-md-12 col-xs-10 col-xs-offset-1'><select name='opzione' class='form-control' id='select-bet'>";
	if(!isset($_COOKIE["opzioneScommessaStagionale"])){
		$opzioneSelezionata = "";
		echo "<option value='NULL'></option>";
	}else{
		$opzioneSelezionata = $_COOKIE["opzioneScommessaStagionale"];
		$query = "SELECT *,O.id as idOpzione FROM opzioniscommessestagionali O INNER JOIN scommessestagionali S ON S.id=O.scommessa WHERE O.associazione=".$opzioneSelezionata." AND S.lingua='".$lingua."'";
		$row = estrai($query,$mysqli);
		echo "<option value='".$row["idOpzione"]."'>".$row['opzione']."</option>";
	}
			while($row=$result->fetch_assoc())
			{
				if($row["associazione"]!=$opzioneSelezionata){
		    	 echo "<option value='".$row['id']."'>".$row['opzione']."</option>";
				}
		    }
		echo "</select></td>";

		if($episodioCorrente>1 || $status!=0){
			if($opzioneSelezionata!=""){
				echo "<td><input type='submit' value='".$betsButton3."' class='btn btn-primary btn-bets' disabled><a href='../php/bets-process.php?action=3'>    <input type='button' class='btn btn-danger btn-bets' value='".$betsButton2."'' disabled></a></td>";
			}else{
				echo "<td><input type='submit' value='".$betsButton1."' class='btn btn-success' disabled></td>";
			}
			echo "</tr></table>";
		}else{
			if($opzioneSelezionata!=""){
				echo "<td><input type='submit' value='".$betsButton3."' class='btn btn-primary btn-bets'><a href='../php/bets-process.php?action=3'>    <input type='button' class='btn btn-danger btn-bets' value='".$betsButton2."'></a></td>";
			}else{
				echo "<td><input type='submit' value='".$betsButton1."' class='btn btn-success btn-bets'></td>";
			}
			echo "</tr></table></form>";	
		}
}
echo "</div>";
echo "<center><h3>".$betsTitle2."</h3></center>";
echo'<div class="table-responsive"><table id="tablerank" class="table sortable text-center tableranking">';
echo"<thead><tr><th>".$betsTableLabel1."</th><th>".$betsTableLabel2."</th><th>".$betsTableLabel3."</th>";
echo "<th>".$betsTableLabel4."</th>";
echo "</tr></thead>";
$query="SELECT *,COUNT(*) as numero FROM scommesseeffettuate WHERE episodio=".$episodioCorrente." AND username='".$_COOKIE['username']."'";
$riga=estrai($query,$mysqli);

$query="SELECT * FROM scommesse WHERE lingua = '".$lingua."' AND episodio=".$episodioCorrente;
$result=$mysqli->query($query);

	while($row=$result->fetch_assoc())
	{
       echo "<tr class='rowtableranking2'><td>".$row['descrizione']."</td><td>".$row['punteggioVerificato']."</td><td>".$row['punteggioNonVerificato']."</td>";
     if($status==0){
	    if($riga["numero"]==0){ 
	       echo'<td>
	       		 <a href="../php/bets-process.php?action=0&id='.$row['id'].'"><input class="btn btn-success" type="button" value="'.$betsButton1.'"></a>
	        </td></tr>';
		 }else if($riga["numero"]>0 && $riga["scommessa"]==$row["associazione"]){
		 	  echo'<td><a href="../php/bets-process.php?action=1&id='.$row['id'].'"><input class="btn btn-danger btn-bets" type="button" value="'.$betsButton2.'"></a>
	        </td></tr>';
		 }else{
		 	echo'<td><input class="btn btn-success" type="button" value="'.$betsButton1.'" disabled>
	        </td></tr>';
		 }
	 }else{
	 	if($riga["numero"]==0){ 
	       echo'<td><input class="btn btn-success" type="button" value="'.$betsButton1.'" disabled>
	        </td></tr>';
		 }
		 if($riga["numero"]>0 && $riga["scommessa"]==$row["associazione"]){
		 	  echo'<td><input class="btn btn-danger btn-bets" type="button" value="'.$betsButton2.'" disabled>
	        </td></tr>';
		 }
		 if($riga["numero"]>0 && $riga["scommessa"]!=$row["associazione"]){
		 	echo'<td><input class="btn btn-success" type="button" value="'.$betsButton1.'" disabled>
	        </td></tr>';
		 }
	 }
	}
echo "</table></div>";


?>
</div>
</div>
</div>