<?php
if(!verificaAdmin($mysqli)){
		forzaLogout("php/");
	}
controllaRank(3);

$query="SELECT * FROM codicipromo";
?>
<div class="container logscontainer">
<div class="row">
<div class="col-md-8 col-md-offset-2 col-xs-12">
	<center><form method="POST" action="php/panel-codes-process.php?action=0">
		<input type="text" name="codice" placeholder="Codice">
		<input type="number" name="maxUtilizzi" placeholder="Utilizzi" min="0" max="300">
		<input type="submit" value="Genera" class="btn btn-primary">
	</form></center>
 </div>
</div>
<div class="row">
<center><h3>Codici creati</h3></center>
  <div class="table-responsive col-md-8 col-md-offset-2">
	<table class="table sortable text-center tableranking">
	<thead>
		<tr><th>CODICE</th><th>MASSIMO UTILIZZI</th><th>UTILIZZI</th><th>AZIONI</th></tr>
	</thead>

	<?php
		$riga=$mysqli->query($query);
	    if(!$riga) exit ("Non ci sono log");
		for($i=0;$row=$riga->fetch_assoc();$i++)
	    {
	    	echo"<tr class='rowtableranking2'><td>".$row['codice']."</td><td>".$row['maxUtilizzi']."</td><td>".$row['utilizzi']."</td><td><a href='php/panel-codes-process.php?action=1&id=".$row['id']."'><button class='btn btn-danger'>Revoca</button></a> </tr>";
	    }
	?>
	</table>
  </div>
 </div>
 </div>
