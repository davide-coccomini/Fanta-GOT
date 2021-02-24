<!DOCTYPE html>
<html lang="en">
 <head>
    <!-- TradeDoubler site verification 3006743 -->
    <meta name="verification" content="9005be0e8c8f09e61e49cff38e1c7c35" />
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="expires" content="0">
    <link rel="shortcut icon" href="/img/favicon.ico" />
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/blog.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/animate.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="/js/bootstrap.min.js"> </script>
    <script src="https://www.google.com/recaptcha/api.js"></script>
    <script src="/js/fb-manager.js"></script>
    <script src="/js/wow.min.js"></script>
    <title>FantaVikings - Scommetti sui tuoi personaggi preferiti di Vikings</title>
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

    <!-- Navigation -->
    

<nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
     <h1><a class="navbar-brand" href="/index/market"><img src="/img/logo2.png" width="150px" height="25px"></a></h1>
    
    </div>

    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
     
      <ul class="nav navbar-nav navbar-right"> 
       <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><b><i class="fa fa-bolt" aria-hidden="true"></i></i> Extra</b> <span class="caret"></span></a>
          <ul class="dropdown-menu inverse-dropdown">
            <li><a href="/index/scores-generator"<?php if($_COOKIE["donatore"]==0 && $_COOKIE["admin"]==0) echo"id='red-text'";?> >Scores generator</a></li>
             <li><a href="/index/analytics"<?php if($_COOKIE["donatore"]==0 && $_COOKIE["admin"]==0) echo"id='red-text'";?> >Analytics</a></li>
          </ul>
        </li>


         <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><b><i class='fa fa-group'></i> Gruppo</b> <span class="caret"></span></a>
          <ul class="dropdown-menu inverse-dropdown">
          <?php
            if(!isset($_COOKIE["gruppo"])){
             echo'<li><a href="/index/group-creation">Crea un gruppo</a></li>
                  <li><a href="/index/group-join">Entra in un gruppo</a></li>';
            }else{
              echo'<li><a href="/index/group-page">Pagina del gruppo</a></li>';
            }
          ?>
            
          </ul>
        </li>
       
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><b><i class='fa fa-line-chart'></i> Classifiche</b> <span class="caret"></span></a>
          <ul class="dropdown-menu inverse-dropdown">

            <li><a href="/valhalla" class="yellow-text">Valhalla</a></li>
            <li><a href="/index/ranking">Globale</a></li>
            <?php
            if($episodioCorrente>1){
              echo'<li><a href="/index/ranking-bets">Scommesse</a></li>
              <li><a href="/index/ranking-episodes">Episodi</a></li>';
            }else{
              echo'<li><a href="#" id="red-text" disabled>Scommesse</a></li>
              <li><a href="#" id="red-text" disabled>Episodi</a></li>';
            }
            
            ?>
            <li><a href="/index/ranking/g">Gruppi (in corsa)</a></li>
            <li><a href="/index/ranking/gn">Gruppi</a></li>
            
            <li><a href="/index/ranking-clan">Clan</a></li>

          </ul>
        </li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><b><i class='fa fa-play'></i> Gioca</b> <span class="caret"></span></a>
          <ul class="dropdown-menu inverse-dropdown">
            <li><a href="/index/market">Mercato</a></li>
            <?php 
              if($_COOKIE["personaggiAcquistati"]==$maxPersonaggiAcquistabili)
               echo '<li><a href="/index/formation">Schieramento</a></li>';
              else
               echo '<li><a href="#" id="red-text" disabled>Schieramento</a></li>';
            ?>
            <li><a href="/index/bets">Scommesse</a></li>
          </ul>
        
          </li>
          <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><b><i class="fa fa-info-circle" aria-hidden="true"></i> Informazioni</b> <span class="caret"></span></a>
          <ul class="dropdown-menu inverse-dropdown">
            <li data-toggle="modal" data-target="#modal"><a href="#">Regolamento</a></li>
            <li data-toggle="modal" data-target="#modalScores"><a href="#">Ultimi punteggi</a></li>
            <li><a href="/countdown">Countdown</a></li>
          </ul>
          </li>
          <?php
          if($_COOKIE["annunciDaLeggere"]==0)
          echo'<li data-toggle="modal" data-target="#modalAnnouncements"><a href="#"><i class="fa fa-bullhorn" aria-hidden="true"></i> <b>Annunci</b></a>';
          else
            echo'<li data-toggle="modal" class="notread" onclick="aggiornaParametro();" data-target="#modalAnnouncements"><a href="#"><i class="fa fa-bullhorn" aria-hidden="true"></i> <b>Annunci</b></a>';
         
         ?>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><b><i class='fa fa-cog'></i> <?php echo $_COOKIE["username"] ?></b> <span class="caret"></span></a>
          <ul class="dropdown-menu inverse-dropdown">
            <li><a href="/index/change-password">Cambia password</a></li>
            <?php
             if(isset($_GET["p"]))
              {
                $page=$_GET["p"];  
              }else{
                $page="market";
              }
            if($_COOKIE["messaggi"]==1){
              echo'<li><a href="/php/unablemessage.php?pa='.$page.'">Disattiva gli avvisi</a></li>';
            }else{
              echo'<li><a href="/php/unablemessage.php?pa='.$page.'">Attiva gli avvisi</a></li>';
            }
            ?>
             
            <li data-toggle="modal" data-target="#modalBugs"><a href="#"></i>Segnala un problema</a></li>
            <li data-toggle="modal" data-target="#modalCodes"><a href="#">Usa un codice promo</a></li>
            <li><a href="about-donation" class="yellow-text">Effettua una donazione</a></li>
            <li data-toggle="modal" data-target="#modalContactUs"><a href="#">Contattaci</a></li>
            <li><a href="https://www.facebook.com/fantavikings/" target="_blank">Facebook</a></li>
            <li><a href="https://twitter.com/Fanta_Vikings/" target="_blank">Twitter</a></li>
            <li><a href="/sponsor">Sponsor</a></li>
            <?php
            if($_COOKIE["admin"]>0){
              echo'<li><a href="/panel-index.php">Amministra</a></li>';
            }
            ?>
            </a>
            <li><a href="/php/logout.php">Logout</a></li>
          </ul>
       </li>
        
      </ul>
    </div>
  </div>
</nav>

    <!-- Page Content -->
    <div class="container">

      <div class="row">

        <!-- Post Content Column -->
        <div class="col-lg-8">

          <!-- Title -->
          <h1 class="mt-4">Post Title</h1>

          <!-- Author -->
          <p class="lead">
            by
            <a href="#">Start Bootstrap</a>
          </p>

          <hr>

          <!-- Date/Time -->
          <p>Posted on January 1, 2018 at 12:00 PM</p>

          <hr>

          <!-- Preview Image -->
          <img class="img-fluid rounded" src="http://placehold.it/900x300" alt="">

          <hr>

          <!-- Post Content -->
          <p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ducimus, vero, obcaecati, aut, error quam sapiente nemo saepe quibusdam sit excepturi nam quia corporis eligendi eos magni recusandae laborum minus inventore?</p>

          <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ut, tenetur natus doloremque laborum quos iste ipsum rerum obcaecati impedit odit illo dolorum ab tempora nihil dicta earum fugiat. Temporibus, voluptatibus.</p>

          <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eos, doloribus, dolorem iusto blanditiis unde eius illum consequuntur neque dicta incidunt ullam ea hic porro optio ratione repellat perspiciatis. Enim, iure!</p>

          <blockquote class="blockquote">
            <p class="mb-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
            <footer class="blockquote-footer">Someone famous in
              <cite title="Source Title">Source Title</cite>
            </footer>
          </blockquote>

          <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Error, nostrum, aliquid, animi, ut quas placeat totam sunt tempora commodi nihil ullam alias modi dicta saepe minima ab quo voluptatem obcaecati?</p>

          <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Harum, dolor quis. Sunt, ut, explicabo, aliquam tenetur ratione tempore quidem voluptates cupiditate voluptas illo saepe quaerat numquam recusandae? Qui, necessitatibus, est!</p>

          <hr>

          <!-- Comments Form -->
          <div class="card my-4">
            <h5 class="card-header">Leave a Comment:</h5>
            <div class="card-body">
              <form>
                <div class="form-group">
                  <textarea class="form-control" rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
              </form>
            </div>
          </div>

          <!-- Single Comment -->
          <div class="media mb-4">
            <img class="d-flex mr-3 rounded-circle" src="http://placehold.it/50x50" alt="">
            <div class="media-body">
              <h5 class="mt-0">Commenter Name</h5>
              Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla. Donec lacinia congue felis in faucibus.
            </div>
          </div>

          <!-- Comment with nested comments -->
          <div class="media mb-4">
            <img class="d-flex mr-3 rounded-circle" src="http://placehold.it/50x50" alt="">
            <div class="media-body">
              <h5 class="mt-0">Commenter Name</h5>
              Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla. Donec lacinia congue felis in faucibus.

              <div class="media mt-4">
                <img class="d-flex mr-3 rounded-circle" src="http://placehold.it/50x50" alt="">
                <div class="media-body">
                  <h5 class="mt-0">Commenter Name</h5>
                  Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla. Donec lacinia congue felis in faucibus.
                </div>
              </div>

              <div class="media mt-4">
                <img class="d-flex mr-3 rounded-circle" src="http://placehold.it/50x50" alt="">
                <div class="media-body">
                  <h5 class="mt-0">Commenter Name</h5>
                  Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla. Donec lacinia congue felis in faucibus.
                </div>
              </div>

            </div>
          </div>

        </div>

        <!-- Sidebar Widgets Column -->
        <div class="col-md-4">

          <!-- Search Widget -->
          <div class="card my-4">
            <h5 class="card-header">Search</h5>
            <div class="card-body">
              <div class="input-group">
                <input type="text" class="form-control" placeholder="Search for...">
                <span class="input-group-btn">
                  <button class="btn btn-secondary" type="button">Go!</button>
                </span>
              </div>
            </div>
          </div>

          <!-- Categories Widget -->
          <div class="card my-4">
            <h5 class="card-header">Categories</h5>
            <div class="card-body">
              <div class="row">
                <div class="col-lg-6">
                  <ul class="list-unstyled mb-0">
                    <li>
                      <a href="#">Web Design</a>
                    </li>
                    <li>
                      <a href="#">HTML</a>
                    </li>
                    <li>
                      <a href="#">Freebies</a>
                    </li>
                  </ul>
                </div>
                <div class="col-lg-6">
                  <ul class="list-unstyled mb-0">
                    <li>
                      <a href="#">JavaScript</a>
                    </li>
                    <li>
                      <a href="#">CSS</a>
                    </li>
                    <li>
                      <a href="#">Tutorials</a>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>

          <!-- Side Widget -->
          <div class="card my-4">
            <h5 class="card-header">Side Widget</h5>
            <div class="card-body">
              You can put anything you want inside of these side widgets. They are easy to use, and feature the new Bootstrap 4 card containers!
            </div>
          </div>

        </div>

      </div>
      <!-- /.row -->

    </div>
    <!-- /.container -->

    <!-- Footer -->
    <footer class="py-5 bg-dark">
      <div class="container">
        <p class="m-0 text-center text-white">Copyright &copy; Your Website 2018</p>
      </div>
      <!-- /.container -->
    </footer>
  </body>
</html>
