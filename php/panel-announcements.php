 <?php
if(!verificaAdmin($mysqli)){
		forzaLogout("php/");
	}
controllaRank(2);
if(!isset($_GET["l"])){
	$language = $lingue[0];
}else{
	$language = filtra($_GET["l"],$mysqli);
}

?>
<div class="content">
<center>
<?php

foreach($lingue as $l){
	if($l!=$language){
		echo'<a href="panel-index.php?p=panel-announcements&l='.$l.'"><img src="../img/flags/'.$l.'.png" class="flag"></a>';
	}else{
		echo'<a href="panel-index.php?p=panel-announcements&l='.$l.'"><img src="../img/flags/'.$l.'.png" class="flag cur-flag"></a>';
	}
}
?>

<h3>Annunci predefiniti</h3>
<a href="php/panel-announcements-process.php?action=2&a=0"><button class="btn-primary">Chiusura mercato</button></a>
<a href="php/panel-announcements-process.php?action=2&a=1"><button class="btn-primary">Apertura mercato</button></a>
<a href="php/panel-announcements-process.php?action=2&a=2"><button class="btn-primary">Chiusura schieramento</button></a>
<a href="php/panel-announcements-process.php?action=2&a=3"><button class="btn-primary">Apertura schieramento</button></a>
<a href="php/panel-announcements-process.php?action=2&a=4"><button class="btn-primary">Pubblicazione punteggi</button></a>
<a href="php/panel-announcements-process.php?action=2&a=5"><button class="btn-primary">Applicazione punteggi</button></a>

</center>


<?php


$query="SELECT * FROM annunci WHERE lingua='IT' ORDER BY timestamp DESC";
$result=$mysqli->query($query);
?>
<div class="container logscontainer">
<div class="row">
<div class="col-md-8 col-md-offset-2 col-xs-12">
	<center><form method="POST" action="php/panel-announcements-process.php">
		<input type="text" name="titolo" class="col-md-12" id="titletextbox" placeholder="Titolo">
		<textarea name="contenuto" class="col-md-12 col-sm-12 col-xs-12" rows="22" placeholder="Contenuto"></textarea><br>
		<input type="hidden" name="lingua" <?php echo "value='".$language."'"; ?>>
		<input type="submit" value="Pubblica" class="btn btn-primary">
	</form></center>
 </div>
</div>
<div>
<center><br><br><i class="fa fa-lock" onclick="unlock(this,'buttonResetAll');"  aria-hidden="true"></i></center>
<?php
	if($_COOKIE["admin"]==3)
	{
		echo"<center><a href='php/panel-announcements-process.php?action=1'><input type='button' class='btn btn-danger' id='buttonResetAll' value='Rimuovi tutto' disabled></a></center>";
	}
?>
<center><h3>Annunci</h3></center>
	<?php
     	 while($row=$result->fetch_assoc()){
     	 	$id=$row["id"];
              echo "<div class='row text-center announcement-block'>
                      <div class='col-md-12 col-xs-12 col-sm-12 announcement-title' style='text-align:left'>".$row['titolo']."<div style='float:right'>".$row['timestamp']."</div></div>
                      <div class='col-md-12 col-xs-12 col-sm-12 announcement-text'>".$row['contenuto']."</div><br><br>
                      <div class='announcement-sign'>".$row['username']."</div>
                      <a href='php/panel-announcements-process.php?action=0&id=".$id."'><input type='button' class='btn btn-danger' value='Rimuovi'></a>
                    </div>";
            }
     ?>

 </div>
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