<?php

	if(!verificaAdmin($mysqli)){
		forzaLogout("");
	}
	controllaRank(2);
	if(!isset($_GET["t"])) //tipo logs
    {
    	header('location: panel-index.php?p=panel-general');
		$mysqli->close();
		die();
    }
	if(isset($_POST["filtro"])){
    	$f=filtra($_POST["filtro"],$mysqli);
    }

    if($_GET["t"]==0){//userlog
		if(isset($_POST["filtro"]))
        	$query="SELECT * FROM logs WHERE id NOT IN (SELECT id FROM logs WHERE evento LIKE '[ADMIN]%' OR evento LIKE '[!]%') AND (UPPER(evento) LIKE UPPER('%".$f."%') OR UPPER(username) LIKE UPPER('%".$f."%')  OR UPPER(evento) LIKE UPPER('%".$f."%') OR timestamp LIKE '%".$f."%' OR ip LIKE '%".$f."%') ORDER BY timestamp DESC LIMIT 200";
  		else
			$query="SELECT * FROM logs WHERE id NOT IN (SELECT id FROM logs WHERE evento LIKE '[ADMIN]%' OR evento LIKE '[!]%') ORDER BY timestamp DESC LIMIT 200";
	}else if($_GET["t"]==1){ //adminlog
		controllaRank(3);
    	if(isset($_POST["filtro"]))
        	$query="SELECT * FROM logs WHERE evento LIKE '[ADMIN]%' AND (UPPER(evento) LIKE UPPER('%".$f."%') OR UPPER(username) LIKE UPPER('%".$f."%') OR timestamp LIKE '%".$f."%' OR ip LIKE '%".$f."%') ORDER BY timestamp DESC";
  		else
    		$query="SELECT * FROM logs WHERE evento LIKE '[ADMIN]%' ORDER BY timestamp DESC LIMIT 200";
	}else if($_GET["t"]){
		controllaRank(3);
		if(isset($_POST["filtro"]))
		$query="SELECT * FROM logs WHERE evento LIKE '[!]%' AND (UPPER(evento) LIKE UPPER('%".$f."%') OR UPPER(username) LIKE UPPER('%".$f."%') OR timestamp LIKE '%".$f."%' OR ip LIKE '%".$f."%') ORDER BY timestamp DESC";
  		else
    		$query="SELECT * FROM logs WHERE evento LIKE '[!]%' ORDER BY timestamp DESC LIMIT 200";
	}

?>

<div class="container logscontainer ">
 <div class="row">
 <div class="col-md-8 col-md-offset-2 col-xs-12">
	<center><form method="POST" action="panel-index.php?p=panel-logs&t=<?php echo $_GET['t']; ?>">
		<input type="text" name="filtro" >
		<input type="submit" value="filtra" class="btn btn-primary">
		<a href="panel-index.php?p=panel-logs&t=0"><input type="button" value="userlogs" class="btn btn-primary"></a>
		<a href="panel-index.php?p=panel-logs&t=1"><input type="button" value="adminlogs" class="btn btn-primary"></a>
		<a href="panel-index.php?p=panel-logs&t=2"><input type="button" value="alertlogs" class="btn btn-primary"></a>
	</form></center>
 </div>
</div>
<div class="row">
  <div class="table-responsive">
	<table class="table sortable text-center tableranking">
	<thead>
		<tr><th>TIMESTAMP</th><th>USERNAME</th><th>EVENTO</th><th>DETTAGLI</th><th>IP</th></tr>
	</thead>

	<?php
		$riga=$mysqli->query($query);
	    if(!$riga) exit ("Non ci sono log");
		for($i=0;$row=$riga->fetch_assoc();$i++)
	    {
	    	echo"<tr class='rowtableranking2'><td>".$row['timestamp']."</td><td>".$row['username']."</td><td>".$row['evento']."</td><td>".$row['dettagli']."</td><td>".$row['ip']."</td></tr>";
	    }
	?>
	</table>
  </div>
 </div>
</div>
