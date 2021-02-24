<?php
if(!verificaAdmin($mysqli)){
		forzaLogout("php/");
	}
controllaRank(2);

$query="SELECT * FROM users WHERE ban=1";
?>
<div class="container logscontainer">
<div class="row">
<div class="col-md-8 col-md-offset-2 col-xs-12">
	<center><form method="POST" action="php/panel-ban-process.php">
		<input type="text" name="user" placeholder="Username">
		<input type="submit" value="ban/sban" class="btn btn-primary">
	</form></center>
 </div>
</div>
<div class="row">
<center><h3>Utenti bannati</h3></center>
  <div class="table-responsive col-md-8 col-md-offset-2">
	<table class="table sortable text-center tableranking">
	<thead>
		<tr><th>USERNAME</th><th>EMAIL</th></tr>
	</thead>

	<?php
		$riga=$mysqli->query($query);
	    if(!$riga) exit ("Non ci sono log");
		for($i=0;$row=$riga->fetch_assoc();$i++)
	    {
	    	echo"<tr class='rowtableranking2'><td>".$row['username']."</td><td>".$row['email']."</td></tr>";
	    }
	?>
	</table>
  </div>
 </div>
 </div>
