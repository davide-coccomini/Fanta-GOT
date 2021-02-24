
<nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="index.php"><img src="img/logo2.png" width="150px" height="25px"></a>
    
    </div>

    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
     
      <ul class="nav navbar-nav navbar-right">
        <li data-toggle="modal" data-target="#modalHandbook"><a href="#"><i class="fa fa-book" aria-hidden="true"></i> <b>Manuale</b></a></li>
    <?php
    if($_COOKIE["admin"]>1){
      echo'<li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><b><i class="fa fa-group"></i>  Gestione utenti</b> <span class="caret"></span></a>
          <ul class="dropdown-menu inverse-dropdown">
            <li><a href="panel-index.php?p=panel-ban">Ban</a></li>
            <li><a href="panel-index.php?p=panel-verification">Attivazione account</a></li>
            <li><a href="panel-index.php?p=panel-logs&t=0">Logs</a></li>
            <li><a href="panel-index.php?p=panel-announcements">Annunci</a></li>
            <li><a href="panel-index.php?p=panel-contacts">Contatti</a></li>
            <li><a href="panel-index.php?p=panel-inactive">Inattivi</a></li>';
            
              if($_COOKIE["admin"]>2){
                 echo'
                 <li><a href="panel-index.php?p=panel-appellation">Titoli</a></li>
                 <li><a href="panel-index.php?p=panel-staff">Staff</a></li>
                 <li><a href="panel-index.php?p=panel-bugs">Segnalazioni</a></li>
                 <li><a href="panel-index.php?p=panel-donor">Donatori</a></li>
                 <li><a href="panel-index.php?p=panel-email">Emails</a></li>
                 <li><a href="panel-index.php?p=panel-codes">Codici promo</a></li>';
                  
              }
            }
          echo"</ul></li>";
      ?>
          
        
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><b><i class='fa fa-play'></i> Gestione gioco</b> <span class="caret"></span></a>
          <ul class="dropdown-menu inverse-dropdown">
          <?php
          if($_COOKIE["admin"]>1){
            echo'<li><a href="panel-index.php?p=panel-rules">Regolamento</a></li>';
            echo'<li><a href="panel-index.php?p=panel-rules-composer">Rules composer</a></li>';
            echo'<li><a href="panel-index.php?p=panel-scheduled-closers">Programmazione status</a></li>';
          }
          if($_COOKIE["admin"]>2){
             echo'<li><a href="panel-index.php?p=panel-countdown">Countdown</a></li>
             <li><a href="panel-index.php?p=panel-config">Config</a></li>
             <li><a href="panel-index.php?p=panel-characters">Personaggi</a></li>';
          }
          ?>

             <li><a href="panel-index.php?p=panel-scores-docs">Docs punteggi</a></li>

            <?php

            echo'<li><a href="panel-index.php?p=panel-scores&ep='.$episodioCorrente.'">Setting punteggi</a></li>
            <li><a href="panel-index.php?p=panel-bets&ep='.$episodioCorrente.'">Setting scommesse</a></li>';

            ?>
          </ul>
         </li>
         <li><a href="panel-index.php?p=panel-general"><i class="fa fa-pie-chart" aria-hidden="true"></i><b> Generale</b></a>
         <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><b><i class='fa fa-cog'></i> <?php echo $_COOKIE["username"] ?></b> <span class="caret"></span></a>
          <ul class="dropdown-menu inverse-dropdown">
            <li><a href="index.php?p=change-password">Cambia password</a></li>
            <?php
            if($_COOKIE["messaggi"]==1)
              echo'<li><a href="php/unablemessage.php">Disattiva gli avvisi</a></li>';
            else
              echo'<li><a href="php/unablemessage.php">Attiva gli avvisi</a></li>';

            if($_COOKIE["admin"]>0){
              echo'<li><a href="panel-index.php">Amministra</a></li>';
            }
            ?>
            
            <li><a href="php/logout.php">Logout</a></li>
          </ul>
       </li>
      </ul>
    </div>
  </div>
</nav>