<?php

	verificaAppartenenzaGruppo($_COOKIE["username"],$mysqli);
	if(!isset($_COOKIE["gruppo"])){
		header('location: /index/group-creation');
	    nuovoMsg($generalAlert39);
		$mysqli->close();
		die();
	}

?>


<div class="row">

<?php

include("php/group-sidebar.php");

?>

	<div class="col-md-8">
		<form action = "/php/group-process.php?action=6" method="POST">
		  <div id="group-message-form">
			<div class="row"><textarea name="testo" class="col-md-12 col-sm-12 col-xs-12" rows="4" placeholder=<?php echo $groupBoardPlaceholder1; ?>></textarea></div>
			<div class="row"><input type="submit" class="btn btn-primary col-sm-4 col-sm-offset-4 col-xs-4 col-xs-offset-4 col-md-offset-5 col-md-2" value=<?php echo $groupBoardButton1; ?>></div>
		 </div>
		</form><br>
	   <?php

		$query = "SELECT * FROM messaggi WHERE gruppo = '".$nome."' ORDER BY timestamp DESC";
		$result=$mysqli->query($query);

			while($row=$result->fetch_assoc()){
				$query = "SELECT capo FROM gruppi WHERE nome='".$nome."'";
				$riga = estrai($query,$mysqli);
				echo "<div class='alert alert-info infobox' style='padding-bottom:37px;'>";
				echo '<div class="col-md-12 col-xs-12 col-sm-12"><b>'.$row['username'];
				if($_COOKIE["username"]==$row["username"] || $_COOKIE["username"]==$riga["capo"]){
					echo"<a href='/php/group-process.php?action=7&id=".$row['id']."'><span id='close' aria-hidden='true' style='float:right;margin-top:-11px;'>&times;</span></a>";
				}
				echo "<div style='float:right'>".$row['timestamp']."</b></div>";
				echo"</div><br><br>";
				echo '<div class="col-md-12 col-xs-12 col-sm-12">'.$row['testo']."</div></div><br>";
			}

		?>

	</div>

</div>