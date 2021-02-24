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
?>



<div class="content">


<div class="col-md-12 col-xs-12">
	<div class="col-md-6 col-md-offset-3 col-xs-10 col-xs-offset-1">
		<center><div class="row" id="flags-content">
			<?php
				foreach($lingue as $l){
					if($l!=$language){
						echo'<a href="panel-index.php?p=panel-rules-composer&l='.$l.'"><img src="../img/flags/'.$l.'.png" class="flag"></a>';
					}else{
						echo'<a href="panel-index.php?p=panel-rules-composer&l='.$l.'"><img src="../img/flags/'.$l.'.png" class="flag cur-flag"></a>';
					}
				}
			?>
		</div></center>
	</div>
</div>

<center><h3>Inserisci una nuova voce nel regolamento</h3></center>
<div class="row">
	<div class="col-md-4 col-md-offset-4">
		<?php
		echo'<form method="POST" action="php/panel-rules-composer-process.php?action=0&l='.$language.'">';
		?>
			<center><input type="text" class="form-control" name="nome" placeholder="Nome">
			<input type="text" class="form-control" name="dettagli" placeholder="Dettagli">
			<input type="number" class="form-control" name="min" placeholder="Punteggio minimo">
			<input type="number" class="form-control" name="max" placeholder="Punteggio massimo">
			<input type="number" class="form-control" name="associazione" placeholder="Associazione"><br>
			<input type="submit" class="btn btn-primary" value="Inserisci"></center>
		</form>
	</div>
</div>
<?php
echo'<div class="table-responsive"><table id="tablerank" class="table sortable text-center tableranking">';
echo"<thead><tr><th>#</th><th>Nome</th><th>Dettagli</th><th>Punteggio Minimo</th><th>Punteggio Massimo</th><th>Associazione</th><th>Azioni</th></tr></thead>";

$query="SELECT * FROM vociregolamento WHERE lingua ='".$language."'";
$result=$mysqli->query($query);
echo "<center><h3>Voci regolamento</h3></center>";
	while($row=$result->fetch_assoc())
	{
       echo "<tr class='rowtableranking2'><td><b>".$row['id']."</b></td><td>".$row['nome']."</td><td>".$row['dettagli']."</td><td>".$row['punteggioMinimo']."</td><td>".$row['punteggioMassimo']."</td><td>".$row['associazione']."</td>";
	   echo'<td>';
			
	   if($row["arena"]){
		echo'<a href="php/panel-rules-composer-process.php?action=4&id='.$row['id'].'&l='.$language.'"><input type="button" class="btn btn-danger" value="Disattiva Arena"></a>';
	   }else{
		echo'<a href="php/panel-rules-composer-process.php?action=4&id='.$row['id'].'&l='.$language.'"><input type="button" class="btn btn-success" value="Attiva Arena"></a>';
	   }
	   
	   echo'<br><br><a href="php/panel-rules-composer-process.php?action=1&id='.$row['id'].'&l='.$language.'"><input type="button" class="btn btn-danger" value="Elimina"></a>
			</td></tr>';
	}
echo "</table></div>";

?>
<center>
	<?php 
	 echo'<a href="php/panel-rules-composer-process.php?action=2&l='.$language.'">';
	?>
	<input type="button" value="Sovrascrivi regolamento" class="btn btn-danger">
	</a>
	<?php
	echo'<a href="php/panel-rules-composer-process.php?action=3&l='.$language.'">';
	?>
	<input type="button" value="Concatena regolamento" class="btn btn-danger"></a></center>
</div>