<?php
include("php/config.php");
include("php/utility.php");
?>
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
    <link rel="stylesheet" type="text/css" href="css/customcountdown.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"> </script>
    <title><?php echo $pageTitle ?></title>
    <meta name="author" content="Davide Coccomini">
    <meta name="copyright" content="fantavikings.it">
    <meta name="generator" content="Sublime Text">
    <meta name="description" content="Organizza la tua squadra, effettua scommesse sugli avvenimenti dei prossimi episodi della nuova stagione della serie TV Vikings e scala la classifica!">
    <meta name="keywords" content="fantavikings2017 fantavikings fanta vikings 2017 serietv serie tv quinta stagione gioco mercato market schieramento formation scommessa bet indovina">
    <meta name="robots" content="all">
    <meta name="dc.language" content="ita" scheme="RFC1766">
    <meta http-equiv="expires" content="Sun, 1 september 2019 12.33.17 GMT ">
    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script>
  (adsbygoogle = window.adsbygoogle || []).push({
    google_ad_client: "ca-pub-7269857134561204",
    enable_page_level_ads: true
  });
</script>
 </head>

<body id="bodylogin">
<?php

    $query="SELECT * FROM countdown ORDER BY id DESC LIMIT 1";
    $row=estrai($query,$mysqli);
    $testo=$row["testo"];
    $dataFine=$row["dataFine"];
    $dataInizio=$row["dataInizio"];
?>

    <script type="text/javascript">

var dataFine = <?php echo json_encode( $dataFine ) ?>;
var dataInizio = <?php echo json_encode( $dataInizio ) ?>;
$('document').ready(function() {

'use strict';

var start = Date.parse(dataInizio) /1000;

var end = Date.parse(dataFine)/1000;

var now = new Date().getTime() / 1000;

$('.countdown').final_countdown({

'start': start,

'end': end,

'now': now

});

});

</script>
<div class="container">
 <div class="row"> <a href="index/home"> <center><h1><div id="logoindex" class="col-xs-12"><div class="col-xs-12 col-md-6 col-md-offset-3 logoindex"></div></div></h1></center></a></div>
  <div class="row"><h2 class="col-md-12 col-xs-12 intestazione"><?php echo $testo; ?></h2></div>
<div class="countdown countdown-container container"
     data-start="1362139200"
     data-end="1392002800"
     data-now="1387461319"
     data-border-color="rgba(255, 255, 255, .7)">
    <div class="clock row">
        <div class="clock-item clock-days countdown-time-value col-sm-6 col-xs-8 col-xs-offset-2 col-md-offset-0 col-sm-offset-0 col-md-3">
            <div class="wrap">
                <div class="inner">
                    <div id="canvas-days" class="clock-canvas"></div>

                    <div class="text">
                        <p class="val">0</p>
                        <p class="type-days type-time">DAYS</p>
                    </div><!-- /.text -->
                </div><!-- /.inner -->
            </div><!-- /.wrap -->
        </div><!-- /.clock-item -->

        <div class="clock-item clock-hours countdown-time-value col-sm-6  col-xs-8 col-xs-offset-2 col-md-offset-0 col-sm-offset-0 col-md-3">
            <div class="wrap">
                <div class="inner">
                    <div id="canvas-hours" class="clock-canvas"></div>

                    <div class="text">
                        <p class="val">0</p>
                        <p class="type-hours type-time">HOURS</p>
                    </div><!-- /.text -->
                </div><!-- /.inner -->
            </div><!-- /.wrap -->
        </div><!-- /.clock-item -->

        <div class="clock-item clock-minutes countdown-time-value col-sm-6 col-xs-8 col-xs-offset-2 col-md-offset-0 col-sm-offset-0 col-md-3">
            <div class="wrap">
                <div class="inner">
                    <div id="canvas-minutes" class="clock-canvas"></div>

                    <div class="text">
                        <p class="val">0</p>
                        <p class="type-minutes type-time">MINUTES</p>
                    </div><!-- /.text -->
                </div><!-- /.inner -->
            </div><!-- /.wrap -->
        </div><!-- /.clock-item -->

        <div class="clock-item clock-seconds countdown-time-value col-sm-6 col-xs-8 col-xs-offset-2 col-md-offset-0 col-sm-offset-0 col-md-3">
            <div class="wrap">
                <div class="inner">
                    <div id="canvas-seconds" class="clock-canvas"></div>

                    <div class="text">
                        <p class="val">0</p>
                        <p class="type-seconds type-time">SECONDS</p>
                    </div><!-- /.text -->
                </div><!-- /.inner -->
            </div><!-- /.wrap -->
        </div><!-- /.clock-item -->
    </div><!-- /.clock -->
</div><!-- /.countdown-wrapper -->

 <a href="index/home"><button class="col-md-4 col-md-offset-4 btn col-xs-12 btn btn-primary">VAI AL GIOCO</button></a>
</div>
<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="js/kinetic.js"></script>
<script type="text/javascript" src="js/jquery.final-countdown.js"></script>


</body>
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script>
  (adsbygoogle = window.adsbygoogle || []).push({
    google_ad_client: "ca-pub-7269857134561204",
    enable_page_level_ads: true
  });
</script>
</html>