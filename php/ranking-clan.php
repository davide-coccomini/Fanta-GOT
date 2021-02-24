<?php
$queryDaEseguire="SELECT *,AVG(mediaPunteggi) as media FROM gruppi WHERE mediaPunteggi<>0 AND membri>=3 GROUP BY clan ORDER BY media DESC";
$result=$mysqli->query($queryDaEseguire);
$rowFirst=$result->fetch_assoc();
$row=$result->fetch_assoc();
$nomiClan = array(
    0 => "Targaryen",
    1 => "Stark",
    2 => "Lannister",
);


if(!isset($rowFirst["clan"]))
{
	$fClanName="Targaryen";
	$nomiClan[0]=1;
	$mediaFirst="-";
}else{
	$fClanName=$rowFirst["clan"];
	for($i=0;$i<3;$i++){
		if($nomiClan[$i]==$rowFirst["clan"]){
			break;
		}
	}
	$nomiClan[$i]=1;
	$mediaFirst=round($rowFirst["media"]);
}
if(!isset($row["clan"])){
	for($i=0;$i<3;$i++){
		if($nomiClan[$i]!=1){
			$clanName=$nomiClan[$i];
			break;
		}
	}
   $nomiClan[$i]=1;
   $media="-";
}else{
	$clanName=$row["clan"];
	$nomiClan[$i]=1;
	$media=round($row["media"]);
}
?>
<div  class="content bg-opacity">
<center><h1 class="ranking-title">Ranking</h2></center>
<div class="row">
<div class="col-md-11">
	<div class="rankingbox col-md-3 col-md-offset-1" id="clanthird">
		<center><?php echo '<img src="../img/clanicons/'.$fClanName.'.png">'  ?></center>
		<div class="profile-usermenu">
			<ul class="nav">
				<li><center>
					<div class='posizione'><?php echo $rankingClanTitle1; ?></div>
				</center></li>
				<li>
					<?php echo $rankingsTableLabel6; ?><span class="pull-right"><?php echo $fClanName; ?></span>	
				</li>
				<li>
					<center><div id="ranking-clan-label"><b><?php echo $scoreText; ?></b></div><br><div class='punteggio'><?php echo $mediaFirst; ?></div></center>
				</li>
			</ul>
		</div>
	</div>
	<div class="rankingbox col-md-3 col-md-offset-1" id="clanthird">
		<center><?php echo '<img src="../img/clanicons/'.$clanName.'.png">'  ?></center>
		<div class="profile-usermenu">
			<ul class="nav">
				<li><center>
					<div class='posizione'><?php echo $rankingClanTitle2; ?></div>
				</center></li>
				<li>
					<?php echo $rankingsTableLabel6; ?><span class="pull-right"><?php echo $clanName; ?></span>	
				</li>
				<li>
					<center><div id="ranking-clan-label"><b><?php echo $scoreText; ?></b></div><br><div class='punteggio'><?php echo $media; ?></div></center>
				</li>
			</ul>
		</div>
	</div>
	<?php $row=$result->fetch_assoc();
	if(!isset($row["clan"])){
	for($i=0;$i<3;$i++){
		if($nomiClan[$i]!=1){
			$clanName=$nomiClan[$i];
			break;
		}
	}
	   $nomiClan[$i]=1;
	   $media="-";
	}else{
		$clanName=$row["clan"];
		$nomiClan[$i]=1;
		$media=round($row["media"]);
	}?>
	<div class="rankingbox col-md-3 col-md-offset-1" id="clanthird">
		<center><?php echo '<img src="../img/clanicons/'.$clanName.'.png">'  ?></center>
		<div class="profile-usermenu">
			<ul class="nav">
				<li><center>
					<div class='posizione'><?php echo $rankingClanTitle3; ?></div>
				</center></li>
				<li>
					<?php echo $rankingsTableLabel6; ?><span class="pull-right"><?php echo $clanName; ?></span>	
				</li>
				<li>
					<center><div id="ranking-clan-label"><b><?php echo $scoreText; ?></b></div><br><div class='punteggio'><?php echo $media;  ?></div></center>
				</li>
			</ul>
		</div>
	</div>
	</div>
</div>
</div>

  