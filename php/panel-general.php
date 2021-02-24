
<?php

	if(!verificaAdmin($mysqli)){
		forzaLogout("");
	}



	$query="SELECT COUNT(*) as numero FROM users";
	$row=estrai($query,$mysqli);
	$registrati=$row["numero"];

	$query="SELECT COUNT(*) as numero FROM logs WHERE (evento='registrazione' OR evento='registrazione su invito') AND date(timestamp)=CURRENT_DATE";
	$row=estrai($query,$mysqli);
	$registratiOggi=$row["numero"];

	$query="SELECT COUNT(*) as numero FROM logs WHERE evento='login' AND date(timestamp)=CURRENT_DATE";
	$row=estrai($query,$mysqli);
	$loginOggi=$row["numero"];

	$query="SELECT COUNT(*) as numero FROM users WHERE personaggiAcquistati=".$maxPersonaggiAcquistabili;
	$row=estrai($query,$mysqli);
	$mercatiCompletati=$row["numero"];

	$query="SELECT COUNT(*) as numero FROM users WHERE personaggiSchierati=".$maxPersonaggiSchierabili;
	$row=estrai($query,$mysqli);
	$schieramentiCompletati=$row["numero"];

	$query="SELECT COUNT(*) as numero FROM gruppi";
	$row=estrai($query,$mysqli);
	$gruppi=$row["numero"];

	$query="SELECT COUNT(*) as numero FROM users WHERE gruppo<>'NULL'";
	$row=estrai($query,$mysqli);
	$utentiInGruppo=$row["numero"];

	$query="SELECT COUNT(*) as numero FROM users U INNER JOIN gruppi G ON G.nome=U.gruppo WHERE G.clan='Stark'";
	$row=estrai($query,$mysqli);
	$utentiStark=$row["numero"];

	$query="SELECT COUNT(*) as numero FROM users U INNER JOIN gruppi G ON G.nome=U.gruppo WHERE G.clan='Targaryen'";
	$row=estrai($query,$mysqli);
	$utentiTargaryen=$row["numero"];

	$query="SELECT COUNT(*) as numero FROM users U INNER JOIN gruppi G ON G.nome=U.gruppo WHERE G.clan='Lannister'";
	$row=estrai($query,$mysqli);
	$utentiLannister=$row["numero"];

	$utentiSenzaGruppo=$registrati-$utentiInGruppo;

	$query="SELECT status FROM aperture WHERE soggetto='sito' AND programmato IS NULL ORDER BY timestamp DESC LIMIT 1";
	$row=estrai($query,$mysqli);
 	if(!$row) $sito='ONLINE';
 	else {
 		if($row["status"]==1) $sito='MANUTENZIONE';
 		else $sito='ONLINE';
 	}
 	$query="SELECT status FROM aperture WHERE soggetto='registrazioni' AND programmato IS NULL ORDER BY timestamp DESC LIMIT 1";
	$row=estrai($query,$mysqli);
 	if(!$row) $registrazioni='APERTE';
 	else {
 		if($row["status"]==1) $registrazioni='CHIUSE';
 		else $registrazioni='APERTE';
 	}
	$query="SELECT status,ufficiale FROM aperture WHERE soggetto='mercato' AND programmato IS NULL ORDER BY timestamp DESC LIMIT 1";
	$row=estrai($query,$mysqli);
 	if(!$row){ 
 		$mercato='APERTO';
 		$ufficiale="";
 	}else {
 		if($row["status"]==1) $mercato='CHIUSO';
 		else $mercato='APERTO';

 		$ufficiale=$row["ufficiale"];
 	}
	$query="SELECT status FROM aperture WHERE soggetto='schieramento' AND programmato IS NULL ORDER BY timestamp DESC LIMIT 1";
	$row=estrai($query,$mysqli);
 	if(!$row) $schieramento='APERTO';
 	else {
 		if($row["status"]==1) $schieramento='CHIUSO';
 		else $schieramento='APERTO';
 	}

 	if($sito=="ONLINE"){
 		$class1="col-md-3 col-sm-6 col-xs-12 stats-block status-box green-block";
 	}else{
 		$class1="col-md-3 col-sm-6 col-xs-12  stats-block status-box red-block";
 	}

 	if($registrazioni=="APERTE"){
 		$class2="col-md-3 col-sm-6  col-xs-12  stats-block status-box green-block";
 	}else{
 		$class2="col-md-3 col-sm-6  col-xs-12  stats-block status-box red-block";
 	}

 	if($mercato=="APERTO"){
 		$class3="col-md-3 col-sm-6  col-xs-12  stats-block status-box green-block";
 	}else{
 		$class3="col-md-3 col-sm-6  col-xs-12  stats-block status-box red-block";
 	}

 	if($schieramento=="APERTO"){
 		$class4="col-md-3 col-sm-6 col-xs-12  stats-block status-box green-block";
 	}else{
 		$class4="col-md-3 col-sm-6 col-xs-12 stats-block status-box red-block";
 	}


?>

<?php
$query="SELECT status FROM aperture WHERE soggetto='Lannister' ORDER BY timestamp DESC LIMIT 1";
	$row=estrai($query,$mysqli);
 	if(!$row) $clan1='APERTO';
 	else {
 		if($row["status"]==1) $clan1='CHIUSO';
 		else $clan1='APERTO';
 	}
 	if($clan1=="APERTO"){
 		$class5="col-md-4 col-sm-4 col-xs-12 stats-block status-box green-block";
 	}else{
 		$class5="col-md-4 col-sm-4 col-xs-12  stats-block status-box red-block";
 	}
 	$query="SELECT status FROM aperture WHERE soggetto='Stark' ORDER BY timestamp DESC LIMIT 1";
 	$row=estrai($query,$mysqli);
 	if(!$row) $clan2='APERTO';
 	else {
 		if($row["status"]==1) $clan2='CHIUSO';
 		else $clan2='APERTO';
 	}
 	if($clan2=="APERTO"){
 		$class6="col-md-4 col-sm-4 col-xs-12 stats-block status-box green-block";
 	}else{
 		$class6="col-md-4 col-sm-4 col-xs-12  stats-block status-box red-block";
 	}
 	$query="SELECT status FROM aperture WHERE soggetto='Targaryen' ORDER BY timestamp DESC LIMIT 1";
 		$row=estrai($query,$mysqli);
 	if(!$row) $clan3='APERTO';
 	else {
 		if($row["status"]==1) $clan3='CHIUSO';
 		else $clan3='APERTO';
 	}
 	if($clan3=="APERTO"){
 		$class7="col-md-4 col-sm-4 col-xs-12 stats-block status-box green-block";
 	}else{
 		$class7="col-md-4 col-sm-4 col-xs-12  stats-block status-box red-block";
 	}

?>
<div class="content">
	<div class="row text-center">
		<?php
			/*RESET DI FINE STAGIONE*/
			$resetSeason = "'resetSeason'";

			if($episodioCorrente==$totEpisodi && $_COOKIE["admin"]>=2){
				echo '<div class="alert alert-danger"><strong>Attenzione!</strong> Siamo giunti alla fine della stagione! <br><br>
					  <i class="fa fa-lock" onclick="unlock(this,'.$resetSeason.');"  aria-hidden="true"></i><br>
					  <a href="php/panel-resets.php?r=3"><input type="button" id="resetSeason" value="RIAVVIA SEASON" class="btn btn-danger" disabled></a><br></div>';
			}

			/*FINE RESET DI FINE STAGIONE*/
		?>
	</div>

<div class="container stats-container">

	<div class="row">
		<div <?php echo "class='".$class1."'" ?>><i class="fa fa-server" aria-hidden="true"></i><br>
			<?php echo "<b>Server</b><br>".$sito; ?>  
			<br><br>

			<?php
			if($_COOKIE["admin"]>1)
				echo'<a href="php/panel-closers.php?s=0"><input type="button" value="Cambia" class="btn btn-primary"></a>';
				
			?>
		</div>
		<div <?php echo "class='".$class2."'" ?>><i class="fa fa-user-plus" aria-hidden="true"></i><br>
			<?php echo "<b>Registrazioni</b><br>".$registrazioni; ?>  
			<br><br>
			<?php
			if($_COOKIE["admin"]>1)
				echo'<a href="php/panel-closers.php?s=1"><input type="button" value="Cambia" class="btn btn-primary"></a>';
			?>
		</div>
		<div <?php echo "class='".$class3."'" ?>><i class="fa fa-shopping-basket" aria-hidden="true"></i><br>
			<?php echo "<b>Mercato</b><br>".$mercato; ?>  
			<br><br>
			<?php
			if($_COOKIE["admin"]>1)
				echo'<a href="php/panel-closers.php?s=2"><input type="button" value="Cambia" class="btn btn-primary"></a>';
			if($ufficiale==0){
				echo'<a href="php/panel-closers.php?s=0&r=1"><input type="button" value="Ufficializza chiusure" class="btn btn-danger"></a>';
			}else if($ufficiale==1){
				echo'<a href="php/panel-closers.php?s=0&r=1"><input type="button" value="Deufficializza chiusure" class="btn btn-danger"></a>';
			}
	
			?>
		</div>
		<div <?php echo "class='".$class4."'" ?>><i class="fa fa-list-ol" aria-hidden="true"></i><br>
			<?php echo "<b>Schieramento</b><br>".$schieramento ?> 
			<br><br>
		<?php
		if($_COOKIE["admin"]>1)
			echo'<a href="php/panel-closers.php?s=3"><input type="button" value="Cambia" class="btn btn-primary"></a>';
		?>
		</div>

		</div>
	</div>

	<div class="container stats-container col-md-12">
		<div <?php echo "class='".$class5."'" ?>><i class="fa fa-flag" aria-hidden="true"></i><br>
				<?php echo "<b>Lannister</b><br>".$clan1 ?> 
				<br><br>
			<?php
			if($_COOKIE["admin"]>1)
				echo'<a href="php/panel-closers.php?s=4"><input type="button" value="Cambia" class="btn btn-primary"></a>';
			?>
		</div>
		<div <?php echo "class='".$class6."'" ?>><i class="fa fa-flag" aria-hidden="true"></i><br>
				<?php echo "<b>Stark</b><br>".$clan2 ?> 
				<br><br>
			<?php
			if($_COOKIE["admin"]>1)
				echo'<a href="php/panel-closers.php?s=5"><input type="button" value="Cambia" class="btn btn-primary"></a>';
			?>
		</div>
		<div <?php echo "class='".$class7."'" ?>><i class="fa fa-flag" aria-hidden="true"></i><br>
				<?php echo "<b>Targaryen</b><br>".$clan3 ?> 
				<br><br>
			<?php
			if($_COOKIE["admin"]>1)
				echo'<a href="php/panel-closers.php?s=6"><input type="button" value="Cambia" class="btn btn-primary"></a>';
			?>
		</div>
	</div>
</div>


<div class="container">
<?php

if($_COOKIE["admin"]>2){
$resetMercato="'resetmercato'";
$resetschieramento="'resetschieramento'";
$resetscommesse="'resetscommesse'";
	echo '
<div class="container stats-container">
	<div class="row">
		<div class="col-md-4 col-sm-4 col-xs-12 stats-block"><i class="fa fa-shopping-basket" aria-hidden="true"></i><br>
			<b>Mercati</b>
			<center><br><br><i class="fa fa-lock" onclick="unlock(this,'.$resetMercato.');"  aria-hidden="true"></i></center>
			<a href="php/panel-resets.php?r=0"><input type="button" id="resetmercato" value="RESET" class="btn btn-danger" disabled></a><br>
		</div>
		<div class="col-md-4 col-sm-4 col-xs-12  stats-block"><i class="fa fa-list-ol" aria-hidden="true"></i><br>
			<b>Schieramenti</b> 
			<center><br><br><i class="fa fa-lock" onclick="unlock(this,'.$resetschieramento.');" aria-hidden="true"></i></center>
			<a href="php/panel-resets.php?r=1"><input type="button" id="resetschieramento" value="RESET" 
			class="btn btn-danger" disabled></a>
		</div>
		<div class="col-md-4 col-sm-4 col-xs-12  stats-block"><i class="fa fa-cube" aria-hidden="true"></i><br>
			<b>Scommesse</b> 
			<center><br><br><i class="fa fa-lock" onclick="unlock(this,'.$resetscommesse.');" aria-hidden="true"></i></center>
			<a href="php/panel-resets.php?r=2"><input type="button" id="resetscommesse" value="RESET" class="btn btn-danger" disabled></a>
		</div>
	</div>
</div>';
$penalita='"penalita"';
echo "<div class='container stats-container'>
	<div class='row'>
		<div class='col-md-6 col-md-offset-3 stats-block'>
		<form action='php/panel-scores-process.php?action=5' method='POST'>
				
			<i class='fa fa-times' aria-hidden='true'></i><br>
			<b>Penalità</b><br><br>
			<input type='number' placeholder='Punteggio penalità' name='punti'><br>
			<center><br><br><i class='fa fa-lock' onclick='unlock(this,".$penalita.");''  aria-hidden='true'></i></center>
				<input type='submit' id='penalita' value='APPLICA' class='btn btn-danger' disabled>
			</form><br>
		</div>
	</div>
</div>";
}
?>
<div class="container stats-container">

 <div class="row">
	<div class="col-md-3 col-sm-3 stats-block"><i class="fa fa-database" aria-hidden="true"></i><br>
		<?php echo "<b>Utenti registrati</b><br>".$registrati; ?>  
	</div>

	<div class="col-md-3 col-sm-3 stats-block"><i class="fa fa-user-plus" aria-hidden="true"></i><br>
		<?php echo "<b>Registrati oggi</b><br>".$registratiOggi; ?> 
	</div>

	<div class="col-md-3 col-sm-3 stats-block"><i class="fa fa-sign-in" aria-hidden="true"></i><br>
		<?php echo "<b>Login oggi</b><br>".$loginOggi; ?> 
	</div>

	<div class="col-md-3 col-sm-3 stats-block"><i class="fa fa-sitemap" aria-hidden="true"></i><br>
		<?php echo "<b>Gruppi creati</b><br>".$gruppi; ?> 
	</div>
 </div>
<br>
 <div class="row">
	<div class="col-md-3 col-sm-3 stats-block"><i class="fa fa-shopping-cart" aria-hidden="true"></i><br>
	 	<?php echo "<b>Mercati completati</b><br>".$mercatiCompletati; ?> 
	</div>

	<div class="col-md-3 col-sm-3 stats-block"><i class="fa fa-list-ol" aria-hidden="true"></i><br>
		<?php echo "<b>Schieramenti completati</b><br>".$schieramentiCompletati; ?>  
	</div>
	<div class="col-md-3 col-sm-3 stats-block"><i class="fa fa-users" aria-hidden="true"></i><br>
		<?php echo "<b>Utenti in un gruppo</b><br>".$utentiInGruppo; ?> 
	</div>
	<div class="col-md-3 col-sm-3 stats-block"><i class="fa fa-street-view" aria-hidden="true"></i><br>
		<?php echo "<b>Utenti senza gruppo</b><br>".$utentiSenzaGruppo; ?> 
	</div>
 </div>
<br>
 <div class="row">
	<div class="col-md-4 col-sm-4 stats-block"><i class="fa fa-flag" aria-hidden="true"></i><br>
		<?php echo "<b>Utenti in Targaryen</b><br>".$utentiTargaryen; ?>  
	</div>

	<div class="col-md-4 col-sm-4 stats-block"><i class="fa fa-flag" aria-hidden="true"></i><br>
		<?php echo "<b>Utenti in Lannister</b><br>".$utentiLannister; ?> 
	</div>
	<div class="col-md-4 col-sm-4 stats-block"><i class="fa fa-flag" aria-hidden="true"></i></i><br>
	 	<?php echo "<b>Utenti in Stark</b><br>".$utentiStark; ?> 
	</div>
 </div>

</div>

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