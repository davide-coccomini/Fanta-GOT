<?php
if(!verificaAdmin($mysqli)){
		forzaLogout("php/");
	}
controllaRank(3);

$query="SELECT *,scadenzaDonatore+INTERVAL 6 HOUR as scadenza FROM users WHERE donatore>0";
?>
<div class="container logscontainer">
<div class="row">
<div class="col-md-8 col-md-offset-2 col-xs-12">
	<center><form method="POST" action="php/panel-donor-process.php">
		<input type="text" name="user" placeholder="Username">
		<input type="number" name="days" placeholder="Giorni di durata">
		<input type="submit" value="setta/desetta" class="btn btn-primary">
	</form></center>
 </div>
</div>
<div class="row">
<center><h3>Donatori</h3></center>
  <div class="table-responsive col-md-8 col-md-offset-2">
	<table class="table sortable text-center tableranking">
	<thead>
		<tr><th>USERNAME</th><th>EMAIL</th><th>TIPOLOGIA</th><th>SCADENZA</th></tr>
	</thead>

	<?php
		$riga=$mysqli->query($query);
	    if(!$riga) exit ("Non ci sono log");
		for($i=0;$row=$riga->fetch_assoc();$i++)
	    {
	    	if($row["donatore"]==1) $donatore = "Donazione";
	    	else if($row["donatore"]==2) $donatore = "Promo Code";
	    	else $donatore = "Inviti";
	    	echo"<tr class='rowtableranking2'><td>".$row['username']."</td><td>".$row['email']."</td><td>".$donatore."</td><td>".$row['scadenza']."</td></tr>";
	    }
	?>
	</table>
  </div>
 </div>
 </div>
