<?php
if(!verificaAdmin($mysqli)){
		forzaLogout("php/");
	}

controllaRank(3);

$query="SELECT * FROM users WHERE admin>0 ORDER BY admin DESC";
?>
<div class="container logscontainer">
<div class="row">
<div class="col-md-8 col-md-offset-2 col-xs-12">
	<center><form method="POST" action="php/panel-staff-process.php">
		<input type="text" name="user" placeholder="Username">
		<select name="rank">
		  <option value="0">Rimuovi</option>
		  <option value="1">1</option>
		  <option value="2">2</option>
		  <option value="3">3</option>
		</select>
		<input type="submit" value="Setta" class="btn btn-primary">
	</form></center>
 </div>
</div>
<div class="row">
<center><h3>STAFF</h3></center>
  <div class="table-responsive col-md-8 col-md-offset-2">
	<table class="table sortable text-center tableranking">
	<thead>
		<tr><th>USERNAME</th><th>RANK</th><th>EMAIL</th></tr>
	</thead>

	<?php
		$riga=$mysqli->query($query);
	    if(!$riga) exit ("Non ci sono log");
		for($i=0;$row=$riga->fetch_assoc();$i++)
	    {
	    	echo"<tr class='rowtableranking2'><td>".$row['username']."</td><td>".$row['admin']."</td><td>".$row['email']."</td></tr>";
	    }
	?>
	</table>
  </div>
 </div>
 </div>
