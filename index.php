<?php
session_start();

include("php/utility.php");
include("php/config.php");

  if(isset($_GET["p"]))
  {
    $p=strtolower($_GET["p"]);  
  }else{
    $p="home";
  }
if($p!="cookies"){
  if($p=="home" || $p=="login-page" || $p=="registration-page" || $p=="password-recover"){
      if(isset($_COOKIE["login"])){
          if($_COOKIE["login"]==1){
               header('location: /index/market');
               $mysqli->close();
               die();     
          }
      }
  }else if(!isset($_COOKIE["login"])){
       $p="home";
  }else if(isset($_COOKIE["login"]) && $_COOKIE["login"]==0) {  
       $p="home";
  }
}    
if(!isset($_COOKIE["messaggi"]))
{
  setcookie("messaggi",1, time() + (86400 * 30), "/"); 
}
?>

<!DOCTYPE html>
<html <?php echo "lang='".$lingua."'"; ?>>
  <head>
    <meta http-equiv="Cache-control" content="public">
    <meta name="verification" content="9005be0e8c8f09e61e49cff38e1c7c35" />
    <meta charset="utf-8">
    <meta property="og:image" content="https://fantagot.com/img/fantagot7.jpg"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="expires" content="1000">
    <link rel="icon" href="/img/favicon.ico" />
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link href="/css/custom.css" rel="stylesheet">
    <!--<link rel="stylesheet" href="/css/animate.min.css" async>-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
    <script src="//platform-api.sharethis.com/js/sharethis.js#property=5c9390a6fb6af900122eca89&product=inline-follow-buttons"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src='https://www.google.com/recaptcha/api.js' async></script>
    <script src="/js/fb-manager.js" async></script>
    <link rel="canonical" href="https://www.fantagot.com/"/>
    <!--<script src="/js/wow.min.js" async></script>-->
    <title><?php echo $pageTitle ?></title>
    <meta name="author" content="Davide Coccomini">
    <meta name="copyright" content="fantagot.com">
    <meta name="generator" content="vscode">
    <meta name="description" <?php echo 'content="'.$description.'"'; ?>>
    <meta name="keywords" content="fantagot2019 fantagot 2019 fanta-got fanta-game-of-thrones fanta-gameofthrones fanta game of thrones game gioco fantacalcio fanta il trono di spade gioco jon snow daenerys targaryan jamie lannister tyrion fanta-got-international stark 2019 final season 8">
    <meta name="robots" content="all">
    <meta http-equiv="expires" content="Sun, 1 september 2021 12.33.17 GMT ">

    
  </head>
<?php
if(isset($_COOKIE["login"])){
  verificaReset($mysqli);
  aggiornaInformazioni($mysqli,$personaggiDisponibili,$utentiDaInvitare);
}
if($p=="home"){
  echo "<body id='bodylogin'>";
}else{
  echo "<body>";
}

if($p!="home" && $p!="ranking-clan")
  echo "<div class='content'>";


if($p!="home"){
    controllaManutenzione($mysqli); 
}
if(!include("php/".$p.".php")){
  $p="home";
}else{
  include_once("php/".$p.".php");
}
if(!isset($_SESSION["letto"])){
  $_SESSION["letto"]=0;
}
?>



<?php             
  echo "</div>";          
if(isset($_COOKIE["login"])){
  echo' <footer id="myFooter">
            <div class="col-md-10 col-xs-7 padding-top-sm">©2019 FantaGOT '.$developedBy.' <a target="blank_" href="http://bit.ly/2EhaV4q">Davide Coccomini</a> '.$and.' Mirko Di Lucia | '.$designedBy.' <a target="blank_" href="https://murasakihaiku.com/">Murasaki Haiku</a></div> 
            <div class="col-md-2 col-xs-5 social-networks">
              <a href="https://www.instagram.com/fantagotinternational/" target="_blank" class="instagram">
                <i class="fa fa-instagram"></i>
              </a>
              <a href="https://www.facebook.com/fantagotofficial" target="_blank" class="facebook">
                <i class="fa fa-facebook-official"></i>
              </a>
              <a href="https://t.me/fantagotofficial" target="_blank" class="telegram">
                <i class="fa fa-paper-plane"></i>
              </a>
            </div>
        </footer>';
}else{
  if(controllaManutenzioneNoRedirect($mysqli))
   echo "<div class='row'><div class='col-md-12 col-sm-12 alert-box' id='alert-maintenance'>".$maintenanceText."</div></div>";
  else if(controllaRegistrazioniNoRedirect($mysqli)){
    echo "<div class='row'><div class='col-md-12 col-sm-12 alert-box' id='alert-maintenance'>".$registrationClosedText."</div></div>";
  }
}

if(isset($_COOKIE["login"])){
   if($_COOKIE["login"]==1){
    include("php/navbar.php");
    include("php/rules.php");
    include("php/scores.php");
    include("php/battles.php");
    include("php/bug-report.php");
    include("php/contact-us.php");
    include("php/announcements.php");
    include("php/promocode.php");
   }
}
include("php/modal.php");

controllaScadenzaProgrammazione($mysqli);
?>
<!-- Chiamata asincrona -->

 <link href="/css/bootstrap-sortable.css" rel="stylesheet" async> 
 <script src="/js/bootstrap-sortable.js" async></script>

<script>
function fade(id, io, tm)
{
  var cross = document.getElementById("close");
  var el = document.getElementById(id);
  el.style.opacity = 1;
  el.onclick = function(event){ 
    if (event.target == el || event.target == cross) {
       el.style.opacity = 0;
       setTimeout(function(){el.style.display = "none"},500);
    }
  }
  
    el.style.transition = "opacity " + tm + "s";
    el.style.WebkitTransition = "opacity " + tm + "s";
}
</script>
<?php
if(!isset($_COOKIE["login"])){
  echo'<script src="/js/cookies-'.$lingua.'.js" type="text/javascript" async></script>';
}
?>
</body>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-117921514-2"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-117921514-2');
</script>

</html>