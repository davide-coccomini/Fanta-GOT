<?php
if(!verificaAdmin($mysqli)){
		forzaLogout("php/");
	}
controllaRank(3);

$query="SELECT * FROM users_verification UV INNER JOIN users U ON UV.email=U.email WHERE status=0";
?>
<div class="container logscontainer">
<div class="row">
<center><h3>Account da verificare</h3></center>
  <div class="table-responsive col-md-8 col-md-offset-2">
	<table class="table sortable text-center tableranking">
	<thead>
		<tr><th>USERNAME</th><th>EMAIL</th><th>AZIONI</th></tr>
	</thead>

	<?php
		$riga=$mysqli->query($query);
	    if(!$riga) exit ("Non ci sono log");
		for($i=0;$row=$riga->fetch_assoc();$i++)
	    {
	    	echo"<tr class='rowtableranking2'><td>".$row['username']."</td><td>".$row['email']."</td><td><a href='php/panel-verification-process.php?action=0&email=".$row['email']."'><button class='btn btn-success'>Attiva</button></a> </tr>";
	    }
	?>
	</table>
  </div>
 </div>
 </div>
