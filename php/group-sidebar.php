
<?php

			$username=$_COOKIE["username"];
			if(isset($_COOKIE["gruppo"])){
				$query="SELECT *,G.password as parola,G.posizione AS posizioneGruppo FROM gruppi G INNER JOIN users U ON U.gruppo=G.nome WHERE U.username='".$username."'";
				$row=estrai($query,$mysqli);
				if($row["capo"]==$_COOKIE["username"]){
					$capo=1;
				}else{
					$capo=0;
				}
				$clan=$row["clan"];
				$motto=$row["motto"];
				$nome=$row["nome"];
				$password=$row["parola"];
				$membri=$row["membri"];
				$posizione=$row["posizioneGruppo"];
				$media=$row["mediaPunteggi"];
			}

?>

<div class="col-md-3">
 <div class="col-md-12">
	<div class="group-sidebar">
					<div class="profile-userpic">
							<?php
							if(isset($_COOKIE["clan"]))
							  echo'<img src="/img/clanicons/'.$_COOKIE['clan'].'.png" class="img-responsive" alt="">';
							else
							  echo'<img src="/img/clanicons/no-clan.png" class="img-responsive" alt="">';
							?>
					</div>
			 
				<div class="profile-usermenu">
				<?php echo "<center><div class='text-white testogr'>".$motto."</div></center>"; ?>
				<ul class="nav" style="color:white">
					<li>
						<b><?php echo $groupSidebarLabel1; ?>:</b><span class="pull-right"><?php echo $clan  ?></span>
					</li>
					<br>
					<li>
						<b><?php echo $groupSidebarLabel2; ?>:</b><span class="pull-right"><?php echo $nome ?></span>
					</li>
					<br>
					<li>
						<b><?php echo $groupSidebarLabel3; ?>:</b><span class="pull-right"><?php echo $password  ?></span>
					</li>
					<br>
					<li>
						<b><?php echo $groupSidebarLabel4; ?>:</b><span class="pull-right"><?php echo $membri;
							if($clan != "White Walkers")
							 echo "/10";
						?></span>
					</li>
					<br>
					<li>
						<b><?php echo $groupSidebarLabel5; ?>:</b><span class="pull-right"><?php echo $posizione ?></span>
					</li>
					<br>
					<li>
						<b><?php echo $groupSidebarLabel6; ?>:</b><span class="pull-right"><?php echo $media ?></span>
					</li>
				  </ul>
				</div>
			
			 <?php
			 	if($_GET["p"]=="group-page")
			 	{
			 		echo "<a href='/index/group-board'><button class='col-md-6 col-md-offset-3 col-xs-offset-3 col-xs-6 btn btn-primary btn-group'>".$groupSidebarButton1."</button></a>";
			 	}else if($_GET["p"]=="group-board"){
			 		echo "<a href='/index/group-page'><button class='col-md-6 col-md-offset-3 col-xs-offset-3 col-xs-6 btn btn-primary btn-group'>".$groupSidebarButton2."</button></a>";	
			 	}
			 	if($capo==1)
			 		echo "<a href='/php/group-process.php?action=1'><button class='col-md-6 col-md-offset-3 col-xs-offset-3 col-xs-6 btn btn-danger btn-group'>".$groupSidebarButton3."</button></a>";
			 	else
			 		echo "<a href='/php/group-process.php?action=3'><button class='col-md-6 col-md-offset-3 btn btn-danger btn-group'>".$groupSidebarButton4."</button></a>";
			 	
			 ?>
			</div>
		</div>
	</div>