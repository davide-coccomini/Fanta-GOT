<?php
include("php/config.php");
include("php/utility.php");

  $query="SELECT contenuto FROM docs WHERE tipo='regolamento' AND lingua ='".$lingua."' ORDER BY timestamp DESC LIMIT 1";
  $ris=estrai($query,$mysqli);
?>


<!DOCTYPE html>
<html <?php echo "lang='".$lingua."'"; ?>>
  <head>
    <meta http-equiv="Cache-control" content="public">
    <meta name="verification" content="9005be0e8c8f09e61e49cff38e1c7c35" />
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="expires" content="0">
    <link rel="icon" href="/img/favicon.ico" />
    <link rel="stylesheet" href="/css/bootstrap.min.css" async>
    <link href="/css/custom.min.css" rel="stylesheet" async>
    <!--<link rel="stylesheet" href="/css/animate.min.css" async>-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src='https://www.google.com/recaptcha/api.js' async></script>
    <script src="/js/fb-manager.js" async></script>
    <!--<script src="/js/wow.min.js" async></script>-->
    <title><?php echo $pageTitle ?></title>
    <meta name="author" content="Davide Coccomini">
    <meta name="copyright" content="fantagot.com">
    <meta name="generator" content="vscode">
    <meta name="description" <?php echo "content='".$loginSidebar1." ".$loginSidebar2."'"?>>
    <meta name="keywords" content="fantagot2019 fantagot 2019 fanta-got fanta-game-of-thrones fanta-gameofthrones game of thrones game gioco fantacalcio fanta il trono di spade gioco jon snow daenerys targaryan jamie lannister tyrion fanta-got-international stark 2019 final season 8">
    <meta name="robots" content="all">
    <meta name="dc.language" content="ita" scheme="RFC1766">
    <meta http-equiv="expires" content="Sun, 1 september 2021 12.33.17 GMT ">
    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <script>
      (adsbygoogle = window.adsbygoogle || []).push({
        google_ad_client: "ca-pub-7269857134561204",
        enable_page_level_ads: true
      });
    </script>
  </head>
 <body>
 <div class="container">
    <div class="modal-dialog-rules" id="modal-dialog-rules" role="document">
      <div class="modal-rules">
        <div class="modal-rules-body">
          <?php
            echo $ris["contenuto"];
          ?>
          <br><br>
        <a href="index.php"><button class="col-md-4 col-md-offset-4 btn col-xs-12 btn btn-primary"><?php echo $goToPlay; ?></button></a>
        <br><br>
        </div>
      </div>
    </div>
    </div>



</body>
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script>
  (adsbygoogle = window.adsbygoogle || []).push({
    google_ad_client: "ca-pub-7269857134561204",
    enable_page_level_ads: true
  });
</script>
</html>