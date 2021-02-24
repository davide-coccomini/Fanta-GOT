<?php
if(!verificaAdmin($mysqli)){
		forzaLogout("");
}

controllaRank(2);

?>

<div class="container logscontainer text-center">

	<form action="php/panel-config-process.php" method="POST">
	Episodio corrente: <input type="number" name="episodio" <?php echo 'value="'.$episodioCorrente.'"'; ?>><br>
	Personaggi acquistabili: <input type="number" name="acquistabili" <?php echo 'value="'.$maxPersonaggiAcquistabili.'"'; ?>><br>
	Personaggi schierabili: <input type="number" name="schierabili" <?php echo 'value="'.$maxPersonaggiSchierabili.'"'; ?>><br>
	Crediti iniziali: <input type="number" name="crediti" <?php echo 'value="'.$creditiIniziali.'"'; ?>><br>
	Utenti da invitare: <input type="number" name="utentiDaInvitare" <?php echo 'value="'.$utentiDaInvitare.'"'; ?>><br>
	Totale episodi: <input type="number" name="totEpisodi" <?php echo 'value="'.$totEpisodi.'"'; ?>><br>
	Punteggi da mostrare: <input type="number" name="punteggiDaMostrare" <?php echo 'value="'.$punteggiDaMostrare.'"'; ?>><br><br>
	<input type="submit" value="Aggiorna parametri" class="btn btn-primary">
	</form>

</div>