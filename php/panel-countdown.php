<?php
if(!verificaAdmin($mysqli)){
		forzaLogout("php/");
	}
controllaRank(3);

$query="SELECT * FROM countdown";
?>
<div class="container logscontainer">
<div class="row">
<div class="col-md-8 col-md-offset-2 col-xs-12">
	<center><form method="POST" action="php/panel-countdown-process.php?action=0">
		<input type="text" class="col-md-4" name="testo" placeholder="Testo">
		<input type="text" class="col-md-4" name="dataInizio" placeholder="Inizio (formato: Wed Nov 29 22:00:00 +0000 2017)">
		<input type="text" class="col-md-4" name="dataFine" placeholder="Termine (formato: Wed Nov 29 22:00:00 +0000 2017)">
		
		<input type="submit" class="col-md-2" value="Crea" class="btn btn-primary">
	</form></center>
 </div>
</div>
<div class="row">
<center><h3>Codici creati</h3></center>
  <div class="table-responsive col-md-8 col-md-offset-2">
	<table class="table sortable text-center tableranking">
	<thead>
		<tr><th>TESTO</th><th>DATA INIZIO</th><th>DATA FINE</th><th>AZIONI</th></tr>
	</thead>

	<?php
		$riga=$mysqli->query($query);
	    if(!$riga) exit ("Non ci sono log");
		for($i=0;$row=$riga->fetch_assoc();$i++)
	    {
	    	echo"<tr class='rowtableranking2'><td>".$row['testo']."</td><td>".$row['dataInizio']."</td><td>".$row['dataFine']."</td><td><a href='php/panel-countdown-process.php?action=1&id=".$row['id']."'><button class='btn btn-danger'>Rimuovi</button></a> </tr>";
	    }
	?>
	</table>
  </div>
 </div>
 </div>
