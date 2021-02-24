<?php
if(!verificaAdmin($mysqli)){
		forzaLogout("php/");
	}
controllaRank(2);

$query="SELECT * FROM users U WHERE gruppo IS NOT NULL AND U.username NOT IN (SELECT P.username FROM punteggi P)";
$result=$mysqli->query($query);
?>
<div class="container logscontainer">
<center><br><br><i class="fa fa-lock" onclick="unlock(this,'buttonResetAll');"  aria-hidden="true"></i></center>
<?php
	if($_COOKIE["admin"]==3)
	{
		echo"<center><a href='php/panel-inactive-process.php?action=0'><input type='button' class='btn btn-danger' id='buttonResetAll' value='Caccia tutti' disabled></a></center>";
	}
?>
<center><h3>Inattivi</h3></center>
<table class="table sortable text-center tableranking">
	<thead>
		<tr><th>USERNAME</th><th>GRUPPO</th><th>AZIONI</th></tr>
	</thead>

	<?php
     	 while($row=$result->fetch_assoc()){
     	 	$username=$row["username"];
     	 	if($row["segnalato"]==1)
     	 		$class="rowtablerankingred";
     	 	else
     	 		$class="rowtableranking2";
            echo"<tr class='".$class."'><td>".$row['username']."</td><td>".$row['gruppo']."</td><td><a href='php/panel-inactive-process.php?action=1&user=".$username."''><input type='button' class='btn btn-danger' value='Caccia'></a></td></tr>";
         }
     ?>
</table>
</div>

 <script>

function unlock(locker,id){
	var target=document.getElementById(id);
	target.disabled=!target.disabled;
	if(locker.className=="fa fa-lock"){
		locker.className="fa fa-unlock-alt";
	}else{
		locker.className="fa fa-lock";
	}
}

</script>