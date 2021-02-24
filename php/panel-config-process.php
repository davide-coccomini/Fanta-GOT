<?php
include("config.php");
include("utility.php");
if(!verificaAdmin($mysqli)){
		forzaLogout("");
}

controllaRank(2);
	$ep = filtra($_POST["episodio"],$mysqli);
	$schierabili = filtra($_POST["schierabili"],$mysqli);
	$acquistabili = filtra($_POST["acquistabili"],$mysqli);
	$cr = filtra($_POST["crediti"],$mysqli);
	$totep = filtra($_POST["totEpisodi"],$mysqli);
	$users = filtra($_POST["utentiDaInvitare"],$mysqli);
	$scores = filtra($_POST["punteggiDaMostrare"],$mysqli);

	$query = "UPDATE config SET episodioCorrente = ".$ep.", maxPersonaggiSchierabili = ".$schierabili.",maxPersonaggiAcquistabili = ".$acquistabili.", creditiIniziali = ".$cr.", utentiDaInvitare = ".$users.",punteggiDaMostrare=".$scores.", totEpisodi = ".$totep;
	esegui($query,$mysqli);
	header('location: ../panel-index.php?p=panel-config&m=Parametri aggiornati');
	nuovoMsg();
	$mysqli->close();
	die();
?>