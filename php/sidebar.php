<?php
	verificaAppartenenzaGruppo($_COOKIE["username"],$mysqli);

	$query="SELECT status FROM aperture WHERE soggetto='mercato' AND programmato IS NULL ORDER BY timestamp DESC LIMIT 1";
	$row=estrai($query,$mysqli);
	$statusMercato = $row["status"];

?>


		<div class="col-md-10 profile-sidebar-box">
			<div class="profile-sidebar">
				<div class="profile-title text-white">
					<center><h2><?php echo $marketSidebarTitle1;?> </h2>
					<hr class="hrschierati-sm">
						<?php
								if($statusMercato!=0) 
									echo "<h3 class='red-text subtitle'>(".$closedText.")</h3>"; ?>
					<p><?php echo $marketSidebarDescription1.$maxPersonaggiAcquistabili.$marketSidebarDescription2.$maxPersonaggiSchierabili.$marketSidebarDescription3?></p>	</center>
				</div>
				<div class="profile-userpic">
				<div class="col-md-12 profile-userpic-img">
					<?php
					if(isset($_COOKIE["clan"]))
					  echo'<img src="/img/clanicons/'.$_COOKIE['clan'].'.png" class="img-responsive" alt="Clan">';
					else
					  echo'<img src="/img/clanicons/no-clan.png" class="img-responsive" alt="Clan">';
					?>
					</div>
				</div>
		
		
				<?php
					$titolo = $marketSidebarTooltip1." ".$utentiDaInvitare." ".$marketSidebarTooltip2;
					echo'<input type="text" id="link-box" class="col-md-10 col-md-offset-1 col-xs-12" onclick="select()" value="https://fantagot.com/index/home/1/'.$_COOKIE['username'].'" data-toggle="tooltip" data-placement="top" title="'.$titolo.'" readonly>';
				?>
				<div class="profile-usertitle">
				 <div class="row sidebar-buttons">
					<div class="profile-usertitle-name col-md-6 col-xs-6">
						<?php
							if(isset($_COOKIE["clan"]))
							  echo '<a class="col-md-12" href="/index/ranking-clan"><button class="sidebar-btn-create">'.$_COOKIE['clan'].'</button></a>';
							else
							  echo '<a class="col-md-12" href="/index/group-creation"><button class="sidebar-btn-create">'.$marketSidebarButton1.'</button></a>';	
						?>
					</div>
					<div class="profile-usertitle-name col-md-6 col-xs-6">
						<?php
							if(isset($_COOKIE["gruppo"]))
							  echo "<a class='col-md-12' href='/index/group-page'><button class='sidebar-btn-join'>".$_COOKIE['gruppo']."</button></a>";
							else
							  echo '<a class="col-md-12" href="/index/group-join"><button class="sidebar-btn-join">'.$marketSidebarButton2.'</button></a>';	  
						?>
					</div>
				 </div>
				</div>
				
				<div class="profile-usermenu">
				<?php 
			
				 if(!isset($_COOKIE["posizione"]))
				 	$posizione="--";
				 else
				 	$posizione=$_COOKIE["posizione"];
				 if(!isset($_COOKIE["punteggioSettimanale"]))
				 	$punteggioSettimanale="--";
				 else
				 	$punteggioSettimanale=$_COOKIE["punteggioSettimanale"];
				 if(!isset($_COOKIE["posizioneSettimanale"]))
				 	$posizioneSettimanale="--";
				 else 
				 	$posizioneSettimanale=$_COOKIE["posizioneSettimanale"];
				?>
					<ul class="nav">
						<center>
							<h3><?php echo $marketSidebarTitle2; ?></h3>
							<hr class="hrschierati-sm">
						</center>
						<li>
							<?php echo $marketSidebarLabel2 ?> <span class="pull-right"><?php echo $posizione; ?></span>  
						</li>
						<li>
							<?php echo $marketSidebarLabel3 ?><span class="pull-right"><?php echo $_COOKIE["punteggioNetto"]; ?></span>
						</li>
						<li>
							<?php echo $marketSidebarLabel11 ?><span class="pull-right"><?php 
																							if(isset( $_COOKIE["punteggioScommesse"])) echo  $_COOKIE["punteggioScommesse"];
																								else echo 0; ?></span>
						</li>
						<li>
							<?php echo $marketSidebarLabel12 ?><span class="pull-right"><?php  if(isset($_COOKIE["punteggioArena"])) echo $_COOKIE["punteggioArena"]; ?></span>
						</li>
						<br>
						<center>
							<h3><?php echo $marketSidebarTitle3; ?></h3>
							<hr class="hrschierati-sm">
						</center>
						<li>
							<?php echo $marketSidebarCredits; ?> <span class="pull-right"> <?php echo $_COOKIE["crediti"]; ?> </span>
						</li>
						<li>
							<?php
							 if($_COOKIE["personaggiAcquistati"]<$maxPersonaggiAcquistabili)
								echo $marketSidebarLabel4.'<span class="pull-right red-text">'.$_COOKIE["personaggiAcquistati"].'/'.$maxPersonaggiAcquistabili.'</span>';
							 else
								echo $marketSidebarLabel4.'<span class="pull-right green-text"><i class="fa fa-check" aria-hidden="true"></i></span>';
							?>
							
						</li>
						<li>
							<?php
							 if($_COOKIE["personaggiSchierati"]<$maxPersonaggiSchierabili)
								echo $marketSidebarLabel5.'<span class="pull-right red-text">'.$_COOKIE["personaggiSchierati"].'/'.$maxPersonaggiSchierabili.'</span>';
							 else
								echo $marketSidebarLabel5.'<span class="pull-right green-text"><i class="fa fa-check" aria-hidden="true"></i></span>';
							?>
						</li>
						<li>
						<?php
							if($_COOKIE["scommessaEffettuata"]==0){
								echo $marketSidebarLabel6.'<span class="pull-right red-text">0/1</span>';
							}else{
								echo $marketSidebarLabel6.'<span class="pull-right green-text"><i class="fa fa-check" aria-hidden="true"></i></span>';
							}
						?>
						</li>
						<?php
							$query="SELECT status FROM aperture WHERE soggetto='mercato' AND programmato IS NULL ORDER BY timestamp DESC LIMIT 1";
							$row=estrai($query,$mysqli);
						 	if(!$row) $mercato='<span class="pull-right green-text clickable" id="statusMercato" onclick="changeLabel(this,0)">'.$marketSidebarStatus1.'</span>';
						 	else {
						 		if($row["status"]==1) $mercato='<span class="pull-right red-text clickable" id="statusMercato" onclick="changeLabel(this,0)">'.$marketSidebarStatus2.'</span>';
						 		else $mercato='<span class="pull-right green-text clickable" id="statusMercato" onclick="changeLabel(this,0)">'.$marketSidebarStatus1.'</span>';
							 }
						 	$query = "SELECT COUNT(*) as numero, programmato - INTERVAL ".$timezone." HOUR as programmato,status FROM aperture WHERE soggetto='mercato' AND programmato IS NOT NULL ORDER BY programmato ASC LIMIT 1";
						 	$row = estrai($query,$mysqli);
						 	if($row["numero"]==1){
								$programmazione = explode(" ",$row["programmato"]);
								$data = $programmazione[0];
								$orario = $programmazione[1];
								$labelProgrammazione = $data."<br>".$orario;
						 		if($row["status"]==0){
						 			$programmazioneMercato = "<span class='pull-right green-text clickable' id='programmazioneMercato' onclick='changeLabel(this,0)' style='display:none'>".$labelProgrammazione.$timezoneLabel."</span>";
						 		}else{
						 			$programmazioneMercato = "<span class='pull-right programmazione-text red-text clickable' id='programmazioneMercato' onclick='changeLabel(this,0)' style='display:none'>".$labelProgrammazione.$timezoneLabel."</span>";
						 		}
						 		
						 	}else{
						 		$programmazioneMercato = "";
						 	}
							$query="SELECT status FROM aperture WHERE soggetto='schieramento' AND programmato IS NULL ORDER BY timestamp DESC LIMIT 1";

							$row=estrai($query,$mysqli);

						 	if(!$row) {
						 		$schieramento='<span class="pull-right red-text clickable" id="statusSchieramento" onclick="changeLabel(this,1)">'.$marketSidebarStatus2.'</span>';
								 $scommesse = '<span class="pull-right red-text clickable" id="statusScommesse" onclick="changeLabel(this,2)">'.$marketSidebarStatus2.'</span>';
								 $arena = '<span class="pull-right red-text clickable" id="statusArena" onclick="changeLabel(this,3)">'.$marketSidebarStatus2.'</span>';
						 	}else {
						 		if($row["status"]==1){
						 			$schieramento='<span class="pull-right red-text clickable" id="statusSchieramento" onclick="changeLabel(this,1)">'.$marketSidebarStatus2.'</span>';
									 $scommesse='<span class="pull-right red-text clickable" id="statusScommesse" onclick="changeLabel(this,2)">'.$marketSidebarStatus2.'</span>';
									 $arena='<span class="pull-right red-text clickable" id="statusArena" onclick="changeLabel(this,3)">'.$marketSidebarStatus2.'</span>';
						 		}else{ 
						 			$schieramento='<span class="pull-right green-text clickable" id="statusSchieramento" onclick="changeLabel(this,1)">'.$marketSidebarStatus1.'</span>';
									 $scommesse='<span class="pull-right green-text clickable" id="statusScommesse" onclick="changeLabel(this,2)">'.$marketSidebarStatus1.'</span>';
									 $arena='<span class="pull-right green-text clickable" id="statusArena" onclick="changeLabel(this,3)">'.$marketSidebarStatus1.'</span>';
						 		}
						 	}
						 	$query = "SELECT COUNT(*) as numero, programmato - INTERVAL ".$timezone." HOUR as programmato,status FROM aperture WHERE soggetto='schieramento' AND programmato IS NOT NULL ORDER BY programmato ASC LIMIT 1";
						 	$row = estrai($query,$mysqli);
						 	if($row["numero"]==1){
								$programmazione = explode(" ",$row["programmato"]);
								$data = $programmazione[0];
								$orario = $programmazione[1];
								$labelProgrammazione = $data."<br>".$orario;
						 		if($row["status"]==0){
						 			$programmazioneSchieramento = "<span class='pull-right green-text programmazione-text clickable' onclick='changeLabel(this,1)' style='display:none' id='programmazioneSchieramento'>".$labelProgrammazione.$timezoneLabel."</span>";
									$programmazioneScommesse = "<span class='pull-right green-text programmazione-text clickable' onclick='changeLabel(this,2)' style='display:none' id='programmazioneScommesse'>".$labelProgrammazione.$timezoneLabel."</span>";
									$programmazioneArena = "<span class='pull-right green-text programmazione-text clickable' onclick='changeLabel(this,3)' style='display:none' id='programmazioneArena'>".$labelProgrammazione.$timezoneLabel."</span>";
						 		}
						 		else{

						 			$programmazioneSchieramento = "<span class='pull-right red-text programmazione-text clickable' onclick='changeLabel(this,1)' style='display:none' id='programmazioneSchieramento'>".$labelProgrammazione.$timezoneLabel."</span>";
									$programmazioneScommesse = "<span class='pull-right red-text programmazione-text clickable' onclick='changeLabel(this,2)' style='display:none' id='programmazioneScommesse'>".$labelProgrammazione.$timezoneLabel."</span>";
									$programmazioneArena = "<span class='pull-right red-text programmazione-text clickable' onclick='changeLabel(this,3)' style='display:none' id='programmazioneArena'>".$labelProgrammazione.$timezoneLabel."</span>";
						 		}
						 	}else{
								 $programmazioneSchieramento = "";
								 $programmazioneScommesse = "";
								 $programmazioneArena = "";
						 	}
						?>
						<li>
							<?php
							 if($_COOKIE["invitatiVerificati"]<$utentiDaInvitare)
								echo $marketSidebarLabel1.' <span class="pull-right red-text">'.$_COOKIE["invitatiVerificati"].'/'.$utentiDaInvitare.'</span>';
							 else
								echo $marketSidebarLabel1.' <span class="pull-right green-text"><i class="fa fa-check" aria-hidden="true"></i></span>';
							?>
						</li>
						<br>
						<center>
							<h3><?php echo $marketSidebarTitle4; ?></h3>
							<hr class="hrschierati-sm">
						</center>
						<li>
							<?php echo $marketSidebarLabel7." ".$programmazioneMercato.$mercato; ?>
						</li>
						<li>
						    <?php echo $marketSidebarLabel8." ".$programmazioneSchieramento.$schieramento; ?>
						</li>
						<li>
							<?php echo $marketSidebarLabel9." ".$programmazioneScommesse.$scommesse; ?>
						</li>
						<li>
							<?php echo $marketSidebarLabel10." ".$programmazioneArena.$arena; ?>
						</li>
					</ul>
				</div>
			</div>
			<div class="social-box">
				<h3><?php echo $marketSidebarButton3; ?></h3>
				<div class="sharethis-inline-follow-buttons"></div>	
				</div>
			<div class="donation-box">
				<a href="/index/about-donation"><button class="btndon btn btn-warning col-md-8 col-md-offset-2" ><?php echo $becomeContributor; ?></button></a>
			</div>
		</div>
		

<script>

$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
});

function changeLabel(scope,type){
	if(type==0){ // mercato
		var id = "#programmazioneMercato";
	}else if(type==1){ // schieramento e scommesse
		var id = "#programmazioneSchieramento";
	}else if(type==2){
		var id = "#programmazioneScommesse";
	}else{
		var id = "#programmazioneArena";
	}
	if($(id).length) { // se esiste una programmazione
		if("#"+scope.id!=id){ // sta cliccando sullo status
		 idScope = "#"+scope.id;
	     $(idScope).fadeOut(400);
	     $(id).delay(500).fadeIn(400);
		}else{ // sta cliccando sulla data
		 idScope = "#"+scope.id;
		 $(idScope).fadeOut(400);
		  if(type==0){
		  	var idLabel = "#statusMercato";
		  }else if(type==1){
		  	var idLabel = "#statusSchieramento";
		  }else if(type==2){
		  	var idLabel = "#statusScommesse";
		  }else{
			var idLabel = "#statusArena";
		  }
		  $(idLabel).delay(500).fadeIn(400);
		}
	   
	}
}
</script>