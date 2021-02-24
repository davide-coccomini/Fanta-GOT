
<nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
     <h1><a class="navbar-brand" href="/index/market"><img src="/img/logo2.png" id="navbar-brand-logo"></a></h1>
    
    </div>

    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
     
      <ul class="nav navbar-nav navbar-right"> 
       <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><b> <?php echo $navbarTitle1 ?></b> <span class="caret"></span></a>
          <ul class="dropdown-menu inverse-dropdown">
            <li><a href="/index/scores-generator"<?php if($_COOKIE["donatore"]==0 && $_COOKIE["admin"]==0) echo"id='red-text'";?>> <?php echo $navbarSubTitle1 ?></a></li>
             <li><a href="/index/analytics"<?php if($_COOKIE["donatore"]==0 && $_COOKIE["admin"]==0) echo"id='red-text'";?> ><?php echo $navbarSubTitle2 ?></a></li>
          </ul>
        </li>


         <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><b> <?php echo $navbarTitle2 ?></b> <span class="caret"></span></a>
          <ul class="dropdown-menu inverse-dropdown">
          <?php
            if(!isset($_COOKIE["gruppo"])){
             echo'<li><a href="/index/group-creation">'.$navbarSubTitle3.'</a></li>
                  <li><a href="/index/group-secondary-creation">'.$navbarSubTitle33.'</a></li>
                  <li><a href="/index/group-join">'.$navbarSubTitle4.'</a></li>';
            }else{
              echo'<li><a href="/index/group-page">'.$navbarSubTitle5.'</a></li>';
            }
          ?>
            
          </ul>
        </li>
       
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><b> <?php echo $navbarTitle3 ?></b> <span class="caret"></span></a>
          <ul class="dropdown-menu inverse-dropdown">

            <!--<li><a href="/throne" class="yellow-text"><?php echo $navbarSubTitle30 ?></a></li>-->
            <li><a href="/index/ranking"><?php echo $navbarSubTitle6 ?></a></li>
            <?php
            if($episodioCorrente>1){
              echo'<li><a href="/index/ranking-bets">'.$navbarSubTitle7.'</a></li>
              <li><a href="/index/ranking-arena">'.$navbarSubTitle31.'</a></li>
              <li><a href="/index/ranking-episodes">'.$navbarSubTitle8.'</a></li>';
            }else{
              echo'<li><a href="#" id="red-text" disabled>'.$navbarSubTitle7.'</a></li>
              <li><a href="#" id="red-text" disabled>'.$navbarSubTitle31.'</a></li>
              <li><a href="#" id="red-text" disabled>'.$navbarSubTitle8.'</a></li>';
            }
            
            ?>
            <li><a href="/index/ranking/g"><?php echo $navbarSubTitle9 ?></a></li>
            <li><a href="/index/ranking/gn"><?php echo $navbarSubTitle10 ?></a></li>
            <li><a href="/index/ranking/gsr"><?php echo $navbarSubTitle34 ?></a></li>
            <li><a href="/index/ranking/gs"><?php echo $navbarSubTitle33 ?></a></li>
            <li><a href="/index/ranking-clan"><?php echo $navbarSubTitle11 ?></a></li>

          </ul>
        </li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><b><?php echo $navbarTitle4 ?></b> <span class="caret"></span></a>
          <ul class="dropdown-menu inverse-dropdown">
            <li><a href="/index/market"><?php echo $navbarSubTitle12 ?></a></li>
            <?php 
              if($_COOKIE["personaggiAcquistati"]==$maxPersonaggiAcquistabili)
               echo '<li><a href="/index/formation">'.$navbarSubTitle13.'</a></li>';
              else
               echo '<li><a href="#" id="red-text" disabled>'.$navbarSubTitle13.'</a></li>';
            ?>
            <li><a href="/index/bets"><?php echo $navbarSubTitle14 ?></a></li>
            <li><a href="/index/arena"><?php echo $navbarSubTitle31 ?></a></li>
          </ul>
        
          </li>
          <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><b><?php echo $navbarTitle5 ?></b> <span class="caret"></span></a>
          <ul class="dropdown-menu inverse-dropdown">
            <li data-toggle="modal" data-target="#modal"><a href="#"><?php echo $navbarSubTitle15 ?></a></li>
            <li data-toggle="modal" data-target="#modalScores"><a href="#"><?php echo $navbarSubTitle16 ?></a></li>
            <li data-toggle="modal" data-target="#modalArena"><a href="#"><?php echo $navbarSubTitle32 ?></a></li>
           
          </ul>
          </li>

        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><b><?php echo $_COOKIE["username"] ?></b> <span class="caret"></span></a>
          <ul class="dropdown-menu inverse-dropdown">
            <li><a href="/index/change-password"><?php echo $navbarSubTitle18 ?></a></li>
            <?php
             if(isset($_GET["p"]))
              {
                $page=$_GET["p"];  
              }else{
                $page="market";
              }
            if($_COOKIE["messaggi"]==1){
              echo'<li><a href="/php/unablemessage.php?pa='.$page.'">'.$navbarSubTitle19.'</a></li>';
            }else{
              echo'<li><a href="/php/unablemessage.php?pa='.$page.'">'.$navbarSubTitle20.'</a></li>';
            }
            ?>
             
            <li data-toggle="modal" data-target="#modalBugs"><a href="#"></i><?php echo $navbarSubTitle21 ?></a></li>
            <li data-toggle="modal" data-target="#modalCodes"><a href="#"><?php echo $navbarSubTitle22 ?></a></li>
            <li><a href="about-donation" class="yellow-text"><?php echo $navbarSubTitle23 ?></a></li>
            <li data-toggle="modal" data-target="#modalContactUs"><a href="#"><?php echo $navbarSubTitle24 ?></a></li>
            <li><a href="https://www.facebook.com/fantagotofficial/" target="_blank"><?php echo $navbarSubTitle25 ?></a></li>
            <li><a href="https://twitter.com/Fanta_Vikings/" target="_blank"><?php echo $navbarSubTitle26 ?></a></li>
            <li><a href="/sponsor"><?php echo $navbarSubTitle27 ?></a></li>
            <?php
            if($_COOKIE["admin"]>0){
              echo'<li><a href="/panel-index.php">'.$navbarSubTitle28.'</a></li>';
            }
            ?>
            </a>
            <li><a href="/php/logout.php"><?php echo $navbarSubTitle29 ?></a></li>
          </ul>
       </li>
       <?php
          if($_COOKIE["annunciDaLeggere"]==0)
            echo'<li data-toggle="modal" data-target="#modalAnnouncements"><a href="#"><i class="fa fa-bullhorn" aria-hidden="true"></i></a>';
          else
            echo'<li data-toggle="modal" id="unreadAnnouncement" onclick="aggiornaParametro(0);" data-target="#modalAnnouncements"><a href="#"><i class="fa fa-bullhorn" aria-hidden="true"></i></a>';
         
         
         ?>
        <?php
          $query = "SELECT COUNT(*) as numero FROM notifiche WHERE username='".$_COOKIE["username"]."' AND letto = 0";
          $row = estrai($query,$mysqli);
          $nonLette = $row["numero"];
          $result;
          if($nonLette >= 5){ // Seleziona tutte le non lette 
            $query = "SELECT *, timestamp - INTERVAL ".$timezone." HOUR as timestamp FROM notifiche WHERE letto = 0 AND username='".$_COOKIE["username"]."' ORDER BY timestamp DESC";
            $result=$mysqli->query($query);
          }else{ // Seleziona solo le ultime 5
            $query = "SELECT *, timestamp - INTERVAL ".$timezone." HOUR as timestamp FROM notifiche WHERE username='".$_COOKIE["username"]."' ORDER BY timestamp DESC LIMIT 5";
            $result=$mysqli->query($query);
          }
        ?>
        <li class="dropdown">
           <?php
            if($nonLette > 0){
              echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" onclick="aggiornaParametro(1)" id="notificationLink"><i class="fa fa-bell" id="unreadNotification" aria-hidden="true"></i></a>';
            }else{
              echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-bell" aria-hidden="true"></i></a>';
            }
           ?>
          <ul class="dropdown-menu notify-drop">
            <div class="notify-drop-title">
            	<div class="row">
            		<div class="col-md-6 col-sm-6 col-xs-6 text-white"><?php echo $navbarNotification; ?> (<b id="notificationsNumber"><?php echo $nonLette; ?></b>)</div>
            	</div>
            </div>
            <div class="drop-content">
              <?php 
              $n = false;
              while($row=$result->fetch_assoc()){
                if(isset($row["parametro"])){
                  if($row["parametro"]>0)
                     $testo = $notifications[$row["testo"]]."(+".$row["parametro"].")";
                  else
                     $testo = $notifications[$row["testo"]]."(".$row["parametro"].")";
                }else{
                  $testo = $notifications[$row["testo"]];
                }
                echo '<li class="notification">
                       <a href="'.$row["link"].'">
                        <div class="col-md-3 col-sm-3 col-xs-3"><div class="notify-img"><img class="col-md-12 col-xs-12" src="/img/notificationicons/'.$row["immagine"].'.jpg" alt="Icon"></div></div>
                        <div class="col-md-9 col-sm-9 col-xs-9 pd-l0">'.$testo.'</a>
                        <hr>
                        <p class="time">'.$row["timestamp"].$timezoneLabel.'</p>
                        </div>
                       </a>
                      </li>';
                $n = true;
              }
              if(!$notifications){
                echo "<div class='text-center'>Non hai ancora ricevuto nessuna notifica</div>";
              }
              ?>
            </div>
          </ul>
        </li>
      <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><b> <?php echo "<img src='/img/flags/".getLanguage($mysqli).".png' class='flag'>"; ?></b> <span class="caret"></span></a>
          <ul class="dropdown-menu inverse-dropdown"  id="dropdown-flags">
            <?php
              foreach($lingue as $l){
                if($l!=getLanguage($mysqli)){
                  echo '<li class="flag-box"><a href="/php/change-language.php?l='.$l.'&pa='.$page.'"><img src="/img/flags/'.$l.'.png" class="flag"> '.$l.'</a></li>';
                }
              }
            ?>
            
          </ul>
     </li>
      </ul>
    </div>
     
  </div>
</nav>



<script>
function aggiornaParametro($parametro){
  if($parametro == 0){ // Annunci
    var target=document.getElementById("unreadAnnouncement");
    target.id="";
    $.ajax({
      type: "POST",
      url: "../php/ajax/announcements.php",
      data: "",
      dataType: "html",
      success: function(risposta){
        
      },
      error: function(){
     
      }
    });
  }else{ // Notifiche
    var target=document.getElementById("unreadNotification");
    target.id="";
    target = document.getElementById("notificationLink");
    target.onclick = "";
    setTimeout(function(){ resettaNotifiche(); }, 2000);

    $.ajax({
      type: "POST",
      url: "../php/ajax/notifications.php",
      data: "",
      dataType: "html",
      success: function(risposta){
        
      },
      error: function(){
     
      }
    });
  }
}

function resettaNotifiche(){
  var target = document.getElementById("notificationsNumber");
  target.firstChild.nodeValue = "0";
}
</script>