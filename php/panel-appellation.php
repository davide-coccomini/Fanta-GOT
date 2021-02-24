<?php

controllaRank(3);
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
			echo'<a href="panel-index.php?p=panel-appellation&l='.$l.'"><img src="../img/flags/'.$l.'.png" class="flag"></a>';
		}else{
			echo'<a href="panel-index.php?p=panel-appellation&l='.$l.'"><img src="../img/flags/'.$l.'.png" class="flag cur-flag"></a>';
		}
	}
?>
</center>

<div class="row">
	<div class="col-md-6 col-md-offset-3">
		<form method="POST" action="php/panel-appellation-process.php?action=0">
			<center><h3>Inserisci un nuovo titolo</h3>
			<input type="text" class="form-control" name="nome" placeholder="Nome">
			<input type="text" class="form-control" name="descrizione" placeholder="Descrizione">
			<input type="text" class="form-control" name="associazione" placeholder="Associazione">
			<input type="hidden" name="lingua" <?php echo "value='".$language."'"; ?>>
			<input type="submit" class="btn btn-primary" value="Inserisci"></center>
		</form><br><br>
	</div>
</div>
<div class="row">
	<div class="col-md-5 col-md-offset-1"> 
		<form method="POST" action="php/panel-appellation-
		process.php?action=2">
		<center><h3>Assegna un titolo ad un utente</h3>
		<input type="text" class="form-control" name="username" placeholder="Username">
		<select name="tipo">
		<?php
			$query="SELECT * FROM titoli WHERE lingua ='".$language."'";
			echo $query;
			$result=$mysqli->query($query);
			while($row=$result->fetch_assoc()){
				echo "<option value='".$row['id']."'>".$row['nome']."</option>";
			}
		?>
		</select><br><br>
		<input type="submit" class="btn btn-primary" value="Assegna">
		</form>
	</div>
	<div class="col-md-5"> 
		<form method="POST" action="php/panel-appellation-process.php?action=2&g=1">
		<center><h3>Assegna un titolo ad un gruppo</h3>
		<input type="text" class="form-control" name="gruppo" placeholder="Nome del gruppo">
		<select name="tipo">
		<?php
			$query="SELECT * FROM titoli WHERE lingua ='".$language."'";
			$result=$mysqli->query($query);
			while($row=$result->fetch_assoc()){
				echo "<option value='".$row['id']."'>".$row['nome']."</option>";
			}
		?>
		</select><br><br>
		<input type="submit" class="btn btn-primary" value="Assegna">
		</form>
	</div>
</div>
<?php
echo'<div class="table-responsive"><table id="tablerank" class="table sortable text-center tableranking">';
echo"<thead><tr><th>#</th><th>Nome</th><th>Descrizione</th><th>Associazione</th><th>Azioni</th></tr></thead>";

$query="SELECT * FROM titoli WHERE lingua ='".$language."'";
$result=$mysqli->query($query);
echo "<center><h3>Titoli creati</h3></center>";
	while($row=$result->fetch_assoc())
	{
       echo "<tr class='rowtableranking2'><td>".$row['id']."</td><td>".$row['nome']."</td><td>".$row['descrizione']."</td><td>".$row['associazione']."</td>";
        echo'<td><a href="php/panel-appellation-process.php?action=1&id='.$row['id'].'"><input type="button" value="Elimina"></a></td></tr>';
	}
echo "</table></div>";

?>
<center><a href="php/panel-appellation-process.php?action=3"><input type="button" class="btn btn-danger" value="Resetta titoli assegnati"></a></center>
