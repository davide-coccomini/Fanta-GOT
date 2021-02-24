<?php

if($_COOKIE["personaggiAcquistati"]!=$maxPersonaggiAcquistabili){
	header('location: index/market');
	nuovoMsg($generalAlert38_1.$maxPersonaggiAcquistabili.$generalAlert38_2);
	$mysqli->close();
	die();	
}

?>

<div class="row">
<div class="col-md-4 col-xs-10 col-md-offset-0 col-xs-offset-1 schieramentocontent bg-blue">
<div class="schieramentobox">
	<div class="titoloschieramento">
		<center><h1><?php echo $formationTitle1 ?></h1>
		<hr class="hrschieramento">
		<hr class="hrschieramento-sm"></center>
	</div>
<div>
<?php
$query="SELECT status FROM aperture WHERE soggetto='schieramento' AND programmato IS NULL ORDER BY timestamp DESC LIMIT 1";
$row=estrai($query,$mysqli);
$status=$row["status"];

$query = "SELECT *,PA.id as acquisto,PA.schierato as schierato, P.nome as nome FROM personaggiacquistati PA INNER JOIN personaggi P ON PA.personaggio=P.id WHERE username='".$_COOKIE['username']."' ORDER BY P.prezzo DESC, P.nome ASC";
$result=$mysqli->query($query);

while($comprati=$result->fetch_assoc()){
	$cookie="schierato".$comprati["acquisto"];


 if($status==0){ // schieramento aperto
 	if($comprati["schierato"]==1){
 		$class="col-md-12 col-xs-12 pgschieramento";
 		$titolo=$comprati["nome"].": ".$formationTooltip1;
 	}else{
 		$class="col-md-12 col-xs-12 pgschieramento pgdaschierare";
		$titolo=$comprati["nome"].": ".$formationTooltip2;
 	}
	if(isset($_COOKIE[$cookie])){
		echo "<div class='col-md-4 col-xs-6 boxschieramento'><img class='".$class."' src='../img/personaggi/".$comprati['percorso']."' data-toggle='tooltip' data-placement='top' title='".$titolo."'><br><a href='../php/formation-process.php?action=1&id=".$comprati['acquisto']."'><button class='btn-schieramento col-md-8  col-xs-8 col-xs-offset-2 btn btn-warning'>".$formationButton1."</button></a></div>";
	}else{
		if($_COOKIE["personaggiSchierati"]==$maxPersonaggiSchierabili){
		 echo "<div class='col-md-4 col-xs-6 boxschieramento'><img class='".$class."' src='../img/personaggi/".$comprati['percorso']."' data-toggle='tooltip' data-placement='top' title='".$titolo."'><br><button class=' btn-schieramento col-md-8 col-xs-8 col-xs-offset-2 btn btn-success' disabled>".$formationButton2."</button></div>";
		}else{
		 echo "<div class='col-md-4 col-xs-6 boxschieramento'><img class='".$class."' src='../img/personaggi/".$comprati['percorso']."' data-toggle='tooltip' data-placement='top' title='".$titolo."'><br><a href='../php/formation-process.php?action=0&id=".$comprati['acquisto']."'><button class=' btn-schieramento col-md-8 col-xs-8 col-xs-offset-2 btn btn-success'>".$formationButton2."</button></a></div>";
		}
	}
 }else{ //schieramento chiuso
	if($comprati["schierato"]==1){
		$class="col-md-12 col-xs-12 pgschieramento";
		$titolo=$comprati["nome"].": ".$formationTooltip1;
	}else{
	   $class="col-md-12 col-xs-12 pgschieramento pgdaschierare";
	   $titolo=$comprati["nome"].": ".$formationTooltip2;
	}
 	echo "<div class='min-height'><div class='col-md-4 col-xs-6 boxschieramento'><img class='".$class."' data-toggle='tooltip' data-placement='top' title='".$titolo."' src='../img/personaggi/".$comprati['percorso']."'></div></div>";
 }
	
}
               

?>
</div>
</div>
</div>
	<div class="col-md-7 col-md-offset-1 col-sm-10 col-xs-10 col-xs-offset-1 schieramentocontent bg-blue text-white">
<center>
	<h2><?php echo $formationTitle5; ?> </h2>
	<?php
		if($status!=0) 
			echo "<h3 class='red-text subtitle'>(".$closedText.")</h3>";
	?>
	<p class="testo16">
	<?php echo $formationDescription1.$maxPersonaggiSchierabili.$formationDescription2.$formationDescription3 ?>
	</p>
</center><br><br>

	<div class="schieramentobox">
	<div class="titoloschieramento">
	<center><h1><?php echo $formationTitle2 ?></h1>
	</div>
	<hr class="hrschierati">
	<hr class="hrschierati-sm"></center>
	<?php
	$i=0;
	$query="SELECT *,P.nome as nomePersonaggio,P.punteggio as totale FROM (schieramenti S INNER JOIN personaggiacquistati PA ON S.acquisto=PA.id) INNER JOIN personaggi P ON P.id=PA.personaggio WHERE PA.username='".$_COOKIE['username']."'";
	$result=$mysqli->query($query);
	$ris=$result;
    echo "<div id='boxschierati' class='col-md-10 col-md-offset-1 col-xs-12 col-xs-offset-0'>";
		while($schierati=$result->fetch_assoc()){
			$i++;
			if(!isset($schierati["totale"]))
				$titolo=$schierati["nomePersonaggio"].": ".$marketTooltip1;
			else
				$titolo=$schierati["nomePersonaggio"].": ".$marketTooltip2." ".$schierati['totale']." ".$marketTooltip3;
			echo "<div class='col-md-3' data-toggle='tooltip' data-placement='bottom' title='".$titolo."'><img class='col-md-12 col-xs-6 pgschierato' src='../img/personaggi/".$schierati['percorso']."'></div>";
		}
	echo "</div>";
		
	if($i==0){
		echo "<div class='row'><center><div class='testo-schieramento' style='margin:1px;'><b>".$formationTitle3." ".$maxPersonaggiSchierabili." ".$formationTitle4."</b></div></center></div>";
	}
echo "</div>";
	?>
	
	</div>
	</div><br>
</div>

<script>

$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
});

</script>