<?php
if(isset($_GET["m"]) && isset($_SESSION["letto"]))
	{	
		if($_SESSION["letto"]==0){
			if(isset($_COOKIE["messaggi"])){
				if($_COOKIE["messaggi"]==1){
					leggiMsg();
				?>
					<div id='messagecontent' style='opacity:0;' onclick="fade('messagecontent','out',0.5)"><div id='messagebox' class='col-md-5'>
					

					<div onclick="fade('messagecontent','out',0.5)">
					<?php
					echo"<div class='col-md-1 col-md-offset-11 col-xs-1 col-xs-offset-11 modaltop'><span id='close' aria-hidden='true'>&times;</span></div></div><img src='img/logo.png' id='messageimg'><p>".$_GET['m']."</p><center>";
				   if($_COOKIE["primoLogin"]==1){
				    	setcookie("primoLogin",0,time() + (86400 * 30), "/");
						echo '<center><a data-toggle="modal" data-target="#modal" href="#"><b>Scopri come giocare</b></a><br><div id="socialicons"><div class="col-md-12 col-xs-12 social-networks"><a href="https://twitter.com/Fanta_Vikings/" target="_blank" class="twitter"><i class="fa fa-twitter-square fa-3x"></i></a><a href="https://www.facebook.com/fantavikings/" target="_blank" class="facebook"><i class="fa fa-facebook-official fa-3x" style="margin-left:3px"></i></a></div></div></center><br>';
						$query="UPDATE users SET primoLogin=0 WHERE username='".$_COOKIE['username']."'";
						esegui($query,$mysqli);
					}
					if(isset($_COOKIE["login"])){
						  if(isset($_GET["p"]))
						  {
						    $page=$_GET["p"];  
						  }else{
						    $page="market";
						  }
						echo "Non vuoi più vedere questi avvisi? <a href='php/unablemessage.php?pa=".$page."'>clicca qui</a></center>";
					}
				
					echo "</div></div>";
					echo "<script>setTimeout(function(){fade('messagecontent','in',1.5)},500);</script>";

				}
			}
		}
	}
?>