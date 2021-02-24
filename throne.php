

<!DOCTYPE html>
<html lang="it">
 <head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="expires" content="0">
    <link rel="icon" href="/img/favicon.ico" />

    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">

    <link rel="stylesheet" type="text/css" href="css/customthrone.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"> </script>
    <script src="js/wow.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/1.18.0/TweenMax.min.js"></script>
    <script src="js/sparks.js"></script>
    <title>FantaGOT</title>
    <meta name="author" content="Davide Coccomini">
    <meta name="copyright" content="fantavikings.it">
    <meta name="generator" content="Sublime Text">
    <meta name="description" content="Organizza la tua squadra, effettua scommesse sugli avvenimenti dei prossimi episodi della nuova stagione della serie TV Vikings e scala la classifica!">
    <meta name="keywords" content="fantavikings2017 fantavikings fanta vikings 2017 serietv serie tv quinta stagione gioco mercato market schieramento formation scommessa bet indovina">
    <meta name="robots" content="all">
    <meta name="dc.language" content="ita" scheme="RFC1766">
    <meta http-equiv="expires" content="Sun, 1 september 2019 12.33.17 GMT ">
 </head>
 <body>


    <?php
    session_start();
    
    include("php/utility.php");
    include("php/config.php");
    ?>

 	<audio loop="loop" autoplay="autoplay">
	  <source src="./media/throne.mp3" type="audio/mpeg">
	</audio>
    <div id="content" class="col-md-12 col-xs-12 col-sm-12">
     <a href="./">
     	<div class="row">
         	<img src="img/logo.png" id="logo" class="col-md-4 col-md-offset-4 col-lg-4 col-lg-offset-4 col-xs-12 col-sm-12">
    	</div>
     </a>
	    <div class="row">
	    	<center><img src="img/throne.png" id="throne"></center>
	    </div>
        <div id="titles" class="col-md-12">
            <div id="corner" class="col-md-6 col-md-offset-3 col-xs-12 col-sm-12">

            <?php
            $query = "SELECT * FROM (titoliassegnati TA INNER JOIN users U ON TA.assegnato = U.username) INNER JOIN titoli T ON T.id=TA.titolo WHERE TA.tipo='utente' AND T.lingua = '".$lingua."'";
            $result=$mysqli->query($query);
           
  	        while($row=$result->fetch_assoc()){
             echo'<h2>'.$row["nome"].'</h2>
                  <p>'.$row['username'].'</p>';
            }
            $query = "SELECT *, T.nome as titolo FROM (titoliassegnati TA INNER JOIN gruppi G ON TA.assegnato = G.nome) INNER JOIN titoli T ON T.id=TA.titolo WHERE TA.tipo='gruppo' AND T.lingua = '".$lingua."'";
            $result=$mysqli->query($query);
       
            while($row=$result->fetch_assoc()){
              echo'<h2>'.$row["titolo"].'</h2>
                   <p>'.$row['nome'].'</p>';
             }
            ?>
      </div>
    </div>
<script>
window.onscroll = function() {
  growShrinkLogo()
};

function growShrinkLogo() {
  var Logo = document.getElementById("throne")
  if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
    Logo.style.width = '20%';
  } else {
    Logo.style.width = '40%';
  }
}
</script>
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script>
  (adsbygoogle = window.adsbygoogle || []).push({
    google_ad_client: "ca-pub-7269857134561204",
    enable_page_level_ads: true
  });
</script>

</body>

