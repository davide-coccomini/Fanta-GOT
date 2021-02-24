<?php
if(!verificaAdmin($mysqli)){
		forzaLogout("php/");
	}
controllaRank(2);

$query="SELECT * FROM aperture WHERE programmato IS NOT NULL";
?>
<div class="container logscontainer">
<div class="row">
<div class="col-md-8 col-md-offset-2 col-xs-12">
	<center><form method="POST" action="php/panel-scheduled-closers-process.php?action=0">
		<div class="row">
			<input type="text" class="col-md-6" name="data" placeholder="(formato: 2017-09-18 18:03:34)">
			<select name="soggetto" class="col-md-3">
			  <option value="sito">Sito</option>
			  <option value="registrazioni">Registrazioni</option>
			  <option value="mercato">Mercato</option>
			  <option value="schieramento">Schieramento</option>
			</select>
			<select name="status" class="col-md-3">
			  <option value="0">Apertura</option>
			  <option value="1">Chiusura</option>
			</select>
		 </div><br>
			<input type="submit" class="col-md-4 col-md-offset-4 btn btn-primary" value="Crea" class="btn btn-primary">
		
	</form></center>
 </div>
</div>
<div class="row">
<center><h3>Codici creati</h3></center>
  <div class="table-responsive col-md-8 col-md-offset-2">
	<table class="table sortable text-center tableranking">
	<thead>
		<tr><th>SOGGETTO</th><th>EVENTO</th><th>PROGRAMMATO PER</th><th>AZIONI</th></tr>
	</thead>

	<?php
		$riga=$mysqli->query($query);
	    if(!$riga) exit ("Non ci sono azioni programmate");
		for($i=0;$row=$riga->fetch_assoc();$i++)
	    {
	    	if($row["status"]==0) 
	    		$azione = "Apertura";
	    	else
	    		$azione = "Chiusura";
	    	echo"<tr class='rowtableranking2'><td>".$row['soggetto']."</td><td>".$azione."</td><td>".$row['programmato']."</td><td><a href='php/panel-scheduled-closers-process.php?action=1&id=".$row['id']."'><button class='btn btn-danger'>Annulla</button></a> </tr>";
	    }
	?>
	</table>
  </div>
 </div>
 </div>
