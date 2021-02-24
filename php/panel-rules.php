<?php
	if(!verificaAdmin($mysqli)){
		forzaLogout("");
	}
	if(!isset($_GET["l"])){
		$language = $lingue[0];
	}else{
		$language = filtra($_GET["l"],$mysqli);
	}
	controllaRank(2);
	$query="SELECT contenuto FROM docs WHERE tipo='regolamento' AND lingua = '".$language."' ORDER BY timestamp DESC LIMIT 1";
	echo $query;
	$row=estrai($query,$mysqli);

?>

<br><br><br><br>

<div class="col-md-12 col-xs-12">
	<div class="col-md-6 col-md-offset-3 col-xs-10 col-xs-offset-1">
		<center><div class="row" id="flags-content">
			<?php
				foreach($lingue as $l){
					if($l!=$language){
						echo'<a href="panel-index.php?p=panel-rules&l='.$l.'"><img src="../img/flags/'.$l.'.png" class="flag"></a>';
					}else{
						echo'<a href="panel-index.php?p=panel-rules&l='.$l.'"><img src="../img/flags/'.$l.'.png" class="flag cur-flag"></a>';
					}
				}
			?>
		</div></center>
	</div>
</div>

<div class="container">

<div class="row">

<?php

echo'<form method="POST" action="php/panel-rules-process.php?l='.$language.'">';

?>
	<textarea name="newrules" class="col-md-12 col-sm-12 col-xs-12" rows="22"> <?php echo $row["contenuto"] ?></textarea>
	<center><input type="submit" value="Aggiorna regolamento" class="btn btn-primary"> </center>
</form>


</div>

</div>